<?php

namespace App\Http\Controllers;

use App\Models\Consenso;
use Illuminate\Http\Request;

use Illuminate\Support\Facades\Mail;
use Illuminate\Support\Facades\Storage;

use App\Mail\ConsensoClienteMail;

class ConsensoController extends Controller
{
    public function form(string $tipo)
    {
        return view('consenso.form', compact('tipo'));
    }

    public function salva(Request $request)
    {
        /*
        |--------------------------------------------------------------------------
        | VALIDAZIONE
        |--------------------------------------------------------------------------
        */

        $request->validate([

            'tipo' => 'required',

            'nome' => 'required',
            'cognome' => 'required',

            'telefono' => 'required',

            'firma_cliente' => 'required',

        ]);

        /*
        |--------------------------------------------------------------------------
        | PAYLOAD
        |--------------------------------------------------------------------------
        */

        $payload = $request->all();

        /*
        |--------------------------------------------------------------------------
        | NORMALIZZA CHECKBOX
        |--------------------------------------------------------------------------
        */

        foreach ([

            'tspciglia',
            'tsplabbra',
            'tspeyeliner',

            'gravidanza_allattamento',
            'malattie_autoimmuni',
            'coagulopatie',
            'patologie_cutanee',
            'allergie',
            'isotretinoina',
            'laser_peeling',

            'privacy_dati',
            'privacy_foto',
            'privacy_promo'

        ] as $k) {

            $payload[$k] = !empty($payload[$k]) ? 1 : 0;
        }

        /*
        |--------------------------------------------------------------------------
        | CREA CONSENSO
        |--------------------------------------------------------------------------
        */

        $consenso = Consenso::create([

            'tipo' => str_replace('-', '_', $request->input('tipo')),

            'data' => $payload,

        ]);

        /*
        |--------------------------------------------------------------------------
        | GENERA PDF
        |--------------------------------------------------------------------------
        */

        switch ($consenso->tipo) {

            case 'prima_seduta':

                $service = app(
                    \App\Services\PdfPrimaSedutaService::class
                );

                break;

            case 'completamento':

                $service = app(
                    \App\Services\PdfCompletamentoService::class
                );

                break;

            default:

                abort(404, 'Template PDF non trovato');
        }

        $pdfContent = $service->genera($consenso);

        /*
        |--------------------------------------------------------------------------
        | SALVA PDF SU STORAGE
        |--------------------------------------------------------------------------
        */

        $filename =
            'consenso_'.$consenso->id.'.pdf';

        $relativePath =
            'pdf/'.$filename;

        Storage::disk('local')->put(
            $relativePath,
            $pdfContent
        );

        /*
        |--------------------------------------------------------------------------
        | SALVA PATH DB
        |--------------------------------------------------------------------------
        */

        $consenso->pdf_path = $relativePath;

        $consenso->save();

        /*
        |--------------------------------------------------------------------------
        | GOOGLE DRIVE
        |--------------------------------------------------------------------------
        */

        try {

            $google = app(
                \App\Services\GoogleDriveService::class
            );

            $driveId = $google->upload(

                storage_path('app/'.$relativePath),

                $filename
            );

            $consenso->google_drive_id = $driveId;

            $consenso->save();

        } catch (\Exception $e) {

            \Log::error(
                'Errore Google Drive: '.$e->getMessage()
            );
        }

        /*
        |--------------------------------------------------------------------------
        | INVIO EMAIL CLIENTE
        |--------------------------------------------------------------------------
        */

        try {

            if (!empty($payload['email'])) {

                Mail::to($payload['email'])
                    ->send(
                        new ConsensoClienteMail($consenso)
                    );
            }

        } catch (\Exception $e) {

            \Log::error(
                'Errore invio mail: '.$e->getMessage()
            );
        }

        /*
        |--------------------------------------------------------------------------
        | RESPONSE
        |--------------------------------------------------------------------------
        */

        return response()->json([

            'success' => true,

            'id' => $consenso->id,

            'pdf_url' => '/consenso/pdf/'.$consenso->id

        ]);
    }

    public function pdf(int $id)
    {
        $consenso = Consenso::findOrFail($id);

        switch ($consenso->tipo) {

            case 'prima_seduta':

                $service = app(
                    \App\Services\PdfPrimaSedutaService::class
                );

                break;

            case 'completamento':

                $service = app(
                    \App\Services\PdfCompletamentoService::class
                );

                break;

            default:

                abort(404, 'Template PDF non trovato');
        }

        $pdf = $service->genera($consenso);

        return response($pdf)
            ->header('Content-Type', 'application/pdf');
    }
}