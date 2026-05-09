<?php

namespace App\Http\Controllers;

use App\Models\Consenso;
use Illuminate\Http\Request;
use App\Services\PdfPrimaSedutaService;

class ConsensoController extends Controller
{
    public function form(string $tipo)
    {
        // tipo: prima_seduta | completamento | preesistente | modella
        return view('consenso.form', compact('tipo'));
    }

    public function salva(Request $request)
    {
        $payload = $request->all();

        // Normalizza checkbox (1/0)
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

        $consenso = Consenso::create([
            'tipo' => $request->input('tipo'),
            'data' => $payload,
        ]);

        return response()->json(['id' => $consenso->id]);
    }

    public function pdf(int $id)
    {
        $consenso = Consenso::findOrFail($id);

        switch ($consenso->tipo) {

            case 'prima_seduta':
                $service = app(\App\Services\PdfPrimaSedutaService::class);
                break;

            default:
                abort(404, 'Template PDF non trovato');
        }

        $pdf = $service->genera($consenso);

        return response($pdf)
            ->header('Content-Type', 'application/pdf');
    }
}