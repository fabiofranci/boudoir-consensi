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

        if (in_array($request->input('tipo'), [
            'preesistente',
            'lavoro_preesistente',
        ], true)) {

            $request->validate([

                'presa_atto_limite_toni' => 'accepted',
                'presa_atto_piu_sedute' => 'accepted',
                'presa_atto_neutralizzazione_tempi' => 'accepted',
                'presa_atto_risultato_non_comparabile' => 'accepted',
                'presa_atto_non_responsabilita' => 'accepted',

            ]);
        }

        if ($request->input('tipo') === 'prima_seduta') {

            $request->validate([

                'post_non_bagnare' => 'accepted',
                'post_non_trucco' => 'accepted',
                'post_non_sole' => 'accepted',
                'post_non_staccare_crosticine' => 'accepted',
                'post_prodotto_lenitivo' => 'accepted',

            ]);
        }

        if ($request->input('tipo') === 'modella') {

            $request->validate([

                'luogo_nascita' => 'required',
                'nazionalita' => 'required',
                'indirizzo_residenza' => 'required',
                'zona' => 'required',
                'tecnica' => 'required',
                'data_trattamento' => 'required',
                'ora_trattamento' => 'required',
                'operatrice_trainer' => 'required',
                'firma_operatrice' => 'required',

                'consenso_tecnica_materiali' => 'accepted',
                'consenso_risultato_guarigione' => 'accepted',
                'consenso_no_aspettative_garantite' => 'accepted',
                'consenso_aftercare' => 'accepted',

                'autorizza_social' => 'accepted',
                'autorizza_materiali_promozionali' => 'accepted',
                'autorizza_didattico' => 'accepted',

            ]);
        }

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

            'epilessia',
            'diabete_mellito',
            'psoriasi_eczema',
            'disturbi_coagulazione',
            'terapia_anticoagulanti_roaccutan',
            'keloidosi_cicatrici_ipertrofiche',
            'herpes_ricorrente',
            'allergie_pigmenti_nichel_anestetici',
            'trattamenti_laser_recenti',
            'altre_patologie_presente',

            'consenso_tecnica_materiali',
            'consenso_risultato_guarigione',
            'consenso_no_aspettative_garantite',
            'consenso_aftercare',

            'autorizza_social',
            'autorizza_materiali_promozionali',
            'autorizza_didattico',

            'post_non_bagnare',
            'post_non_trucco',
            'post_non_sole',
            'post_non_staccare_crosticine',
            'post_prodotto_lenitivo',

            'tecnica_microblading',
            'tecnica_ago_shading',
            'tecnica_mista',
            'tecnica_non_nota',

            'stato_sbiadito',
            'stato_modificato_tono_direzione',
            'stato_irregolare_forma',
            'stato_pigmento_presente',
            'stato_alterazione_cromatica',

            'rimozione_nessuno',
            'rimozione_laser',
            'rimozione_soluzione_salina',
            'rimozione_altro',

            'obiettivo_rinfresco',
            'obiettivo_ridisegno',
            'obiettivo_copertura',
            'obiettivo_neutralizzazione',
            'obiettivo_completamento',
            'obiettivo_altro',

            'presa_atto_limite_toni',
            'presa_atto_piu_sedute',
            'presa_atto_neutralizzazione_tempi',
            'presa_atto_risultato_non_comparabile',
            'presa_atto_non_responsabilita',

            'nessuna_terapia_anticoagulante',
            'nessuna_terapia_isotretinoina',
            'nessun_trattamento_laser_peeling',
            'nessuna_allergia_pigmenti',

            'gravidanza_allattamento',
            'malattie_autoimmuni',
            'coagulopatie',
            'patologie_cutanee',
            'allergie',
            'isotretinoina',
            'laser_peeling',

            'assenza_terapie_farmacologiche',
            'assenza_interventi_estetici_laser',
            'assenza_reazioni_anomale',
            'assenza_gravidanza_sopravvenuta',

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

            'tipo' => $this->normalizeTipo(
                $request->input('tipo')
            ),

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

            case 'lavoro_preesistente':

            case 'preesistente':

                $service = app(
                    \App\Services\PdfLavoroPreesistenteService::class
                );

                break;

            case 'modella':

                $service = app(
                    \App\Services\PdfModellaService::class
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

            case 'lavoro_preesistente':

            case 'preesistente':

                $service = app(
                    \App\Services\PdfLavoroPreesistenteService::class
                );

                break;

            case 'modella':

                $service = app(
                    \App\Services\PdfModellaService::class
                );

                break;

            default:

                abort(404, 'Template PDF non trovato');
        }

        $pdf = $service->genera($consenso);

        return response($pdf)
            ->header('Content-Type', 'application/pdf');
    }

    private function normalizeTipo(string $tipo): string
    {
        $tipo = str_replace('-', '_', $tipo);

        if ($tipo === 'preesistente') {
            return 'lavoro_preesistente';
        }

        return $tipo;
    }
}
