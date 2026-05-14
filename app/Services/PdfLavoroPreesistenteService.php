<?php

namespace App\Services;

use setasign\Fpdi\Tcpdf\Fpdi;

class PdfLavoroPreesistenteService
{
    public function genera($consenso)
    {
        $data = $consenso->data;

        $pdf = new Fpdi();

        $pdf->SetAutoPageBreak(false);
        $pdf->setPrintHeader(false);
        $pdf->setPrintFooter(false);

        $template = storage_path('app/templates/lavoro_preesistente.pdf');

        $pageCount = $pdf->setSourceFile($template);

        for ($pageNo = 1; $pageNo <= $pageCount; $pageNo++) {
            $tpl = $pdf->importPage($pageNo);

            $pdf->AddPage();
            $pdf->useTemplate($tpl, 0, 0, 210);
            $pdf->SetFont('helvetica', '', 10);

            if ($pageNo === 1) {
                $this->renderPageOne($pdf, $data);
            }

            if ($pageNo === 2) {
                $this->renderPageTwo($pdf, $data);
            }

            if ($pageNo === 3) {
                $this->renderPageThree($pdf, $data);
            }
        }

        return $pdf->Output('', 'S');
    }

    private function renderPageOne(Fpdi $pdf, array $data): void
    {
        $pdf->SetXY(70, 89);
        $pdf->Write(0, $this->fullName($data));

        $pdf->SetXY(70, 99);
        $pdf->Write(0, $this->formatDate(
            $this->value($data, [
                'data_nascita',
            ])
        ));

        $pdf->SetXY(70, 109);
        $pdf->Write(0, $this->value($data, [
            'telefono',
        ]));

        $pdf->SetXY(70, 118.5);
        $pdf->Write(0, $this->formatDate(
            $this->value($data, [
                'data_seduta',
                'data_seduta_odierna',
            ])
        ));

        $pdf->SetXY(70, 151);
        $pdf->Write(0, $this->value($data, [
            'zona',
            'zona_trattata',
        ]));

        $pdf->SetXY(70, 168.5);
        $pdf->Write(0, $this->value($data, [
            'periodo_approssimativo_precedente',
            'periodo_trattamento_precedente',
        ]));

        $pdf->SetXY(70, 186);
        $pdf->Write(0, $this->value($data, [
            'operatore_precedente',
            'operatore_centro_precedente',
        ]));

        $this->drawCheck($pdf, ['tecnica_microblading'], 25.5, 204.5, $data);
        $this->drawCheck($pdf, ['tecnica_ago_shading'], 25.5, 210, $data);
        $this->drawCheck($pdf, ['tecnica_mista'], 25.5, 215.5, $data);
        $this->drawCheck($pdf, ['tecnica_non_nota'], 25.5, 221, $data);

        $this->drawCheck($pdf, ['stato_sbiadito'], 25.5, 237.5, $data);
        $this->drawCheck($pdf, ['stato_modificato_tono_direzione'], 25.5, 243, $data);
        $this->drawCheck($pdf, ['stato_irregolare_forma'], 25.5, 248.5, $data);
        $this->drawCheck($pdf, ['stato_pigmento_presente'], 25.5, 254, $data);
        $this->drawCheck($pdf, ['stato_alterazione_cromatica'], 25.5, 259.5, $data);

        $pdf->SetFont('helvetica', '', 9);
        $pdf->SetXY(70, 271);
        $pdf->MultiCell(
            116,
            9,
            $this->value($data, [
                'pigmenti_precedenti',
                'pigmenti_prodotti_precedenti',
            ]),
            0,
            'L'
        );
    }

    private function renderPageTwo(Fpdi $pdf, array $data): void
    {
        $this->drawCheck($pdf, ['rimozione_nessuno'], 25.5, 31.5, $data);
        $this->drawCheck($pdf, ['rimozione_laser'], 25.5, 37, $data);
        $this->drawCheck($pdf, ['rimozione_soluzione_salina'], 25.5, 42.5, $data);
        $this->drawCheck($pdf, ['rimozione_altro'], 25.5, 48, $data);

        $pdf->SetFont('helvetica', '', 9);
        $pdf->SetXY(42, 48);
        $pdf->Write(0, $this->value($data, [
            'rimozione_altro_testo',
        ]));

        $this->drawCheck($pdf, ['obiettivo_rinfresco'], 25.5, 75.5, $data);
        $this->drawCheck($pdf, ['obiettivo_ridisegno'], 25.5, 81, $data);
        $this->drawCheck($pdf, ['obiettivo_copertura'], 25.5, 86.5, $data);
        $this->drawCheck($pdf, ['obiettivo_neutralizzazione'], 25.5, 92, $data);
        $this->drawCheck($pdf, ['obiettivo_completamento'], 25.5, 97.5, $data);
        $this->drawCheck($pdf, ['obiettivo_altro'], 25.5, 103, $data);

        $pdf->SetFont('helvetica', '', 9);
        $pdf->SetXY(42, 103);
        $pdf->Write(0, $this->value($data, [
            'obiettivo_altro_testo',
        ]));

        $pdf->SetXY(70, 119);
        $pdf->MultiCell(
            116,
            14,
            $this->value($data, [
                'note_tecnico_intervento',
                'note_tecnico',
            ]),
            0,
            'L'
        );

        $pdf->SetFont('helvetica', '', 10);
        $pdf->SetXY(74, 145);
        $pdf->Write(0, $this->value($data, [
            'costo',
            'costo_seduta_odierna',
        ]));

        $this->drawCheck($pdf, ['presa_atto_limite_toni'], 25.5, 196, $data);
        $this->drawCheck($pdf, ['presa_atto_piu_sedute'], 25.5, 205.5, $data);
        $this->drawCheck($pdf, ['presa_atto_neutralizzazione_tempi'], 25.5, 215, $data);
        $this->drawCheck($pdf, ['presa_atto_risultato_non_comparabile'], 25.5, 224.5, $data);
        $this->drawCheck($pdf, ['presa_atto_non_responsabilita'], 25.5, 234, $data);

        $pdf->SetFont('helvetica', '', 9);
        $pdf->SetXY(20, 270);
        $pdf->MultiCell(
            176,
            10,
            $this->value($data, [
                'piano_intervento',
            ]),
            0,
            'L'
        );

        }

    private function renderPageThree(Fpdi $pdf, array $data): void
    {

        $pdf->SetFont('helvetica', '', 10);
        $pdf->SetXY(106, 22);
        $pdf->Write(0, $this->value($data, [
            'numero_minimo_sedute',
        ]));

        $pdf->SetXY(106, 28);
        $pdf->Write(0, $this->value($data, [
            'costo_indicativo_seduta',
        ]));

        $pdf->SetXY(106, 35.5);
        $pdf->Write(0, $this->value($data, [
            'intervallo_sedute',
        ]));

        $this->drawCheck($pdf, ['nessuna_terapia_anticoagulante'], 25.5, 70, $data);
        $this->drawCheck($pdf, ['nessuna_terapia_isotretinoina'], 25.5, 75.5, $data);
        $this->drawCheck($pdf, ['nessun_trattamento_laser_peeling'], 25.5, 81, $data);
        $this->drawCheck($pdf, ['nessuna_allergia_pigmenti'], 25.5, 86.5, $data);

        $pdf->SetFont('helvetica', '', 9);
        $pdf->SetXY(70, 98);
        $pdf->MultiCell(
            116,
            10,
            $this->value($data, [
                'farmaci_condizioni_rilevanti',
            ]),
            0,
            'L'
        );

        $this->drawCheck($pdf, ['privacy_dati'], 19, 121, $data);
        $this->drawCheck($pdf, ['privacy_foto'], 19, 126.5, $data);
        $this->drawCheck($pdf, ['privacy_promo'], 19, 131.5, $data);

        $pdf->SetFont('helvetica', '', 10);

        $luogoData = $this->value($data, [
            'luogo_data',
        ]);

        if ($luogoData === '') {
            $luogoData = 'Lucca '.now()->format('d/m/Y');
        }

        $pdf->SetXY(22, 184);
        $pdf->Write(0, $luogoData);

        if (!empty($data['firma_cliente'])) {
            $firmaCliente = $this->salvaFirmaTemp(
                $data['firma_cliente'],
                'cliente_preesistente'
            );

            $pdf->Image(
                $firmaCliente,
                101,
                177,
                38,
                15,
                'PNG'
            );
        }
    }

    private function fullName(array $data): string
    {
        return trim(($data['nome'] ?? '').' '.($data['cognome'] ?? ''));
    }

    private function value(array $data, array $keys): string
    {
        foreach ($keys as $key) {
            if (!array_key_exists($key, $data)) {
                continue;
            }

            $value = $data[$key];

            if ($value === null) {
                continue;
            }

            if (is_string($value)) {
                $value = trim($value);
            }

            if ($value === '') {
                continue;
            }

            return (string) $value;
        }

        return '';
    }

    private function drawCheck(Fpdi $pdf, array $keys, float $x, float $y, array $data): void
    {
        foreach ($keys as $key) {
            if (!empty($data[$key])) {
                $this->check($pdf, $x, $y);

                return;
            }
        }
    }

    private function check(Fpdi $pdf, float $x, float $y): void
    {
        $pdf->SetXY($x, $y);
        $pdf->SetFont('helvetica', 'B', 12);
        $pdf->Write(0, 'X');
    }

    private function salvaFirmaTemp(string $base64, string $suffix): string
    {
        $image = str_replace('data:image/png;base64,', '', $base64);
        $image = str_replace(' ', '+', $image);

        $dir = storage_path('app/firme');

        if (!is_dir($dir)) {
            mkdir($dir, 0755, true);
        }

        $path = $dir.'/temp_'.$suffix.'.png';

        file_put_contents($path, base64_decode($image));

        return $path;
    }

    private function formatDate($date): string
    {
        if (empty($date)) {
            return '';
        }

        try {
            return \Carbon\Carbon::parse($date)->format('d/m/Y');
        } catch (\Exception $e) {
            return (string) $date;
        }
    }
}
