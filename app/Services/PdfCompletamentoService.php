<?php

namespace App\Services;

use setasign\Fpdi\Tcpdf\Fpdi;

class PdfCompletamentoService
{
    public function genera($consenso)
    {
        $data = $consenso->data;

        $pdf = new Fpdi();

        $pdf->SetAutoPageBreak(false);
        $pdf->setPrintHeader(false);
        $pdf->setPrintFooter(false);

        $template = storage_path('app/templates/completamento.pdf');

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
        }

        return $pdf->Output('', 'S');
    }

    private function renderPageOne(Fpdi $pdf, array $data): void
    {
        // Coordinate volutamente raccolte qui per facilitare il riallineamento.

        $pdf->SetXY(70, 82);
        $pdf->Write(0, $this->fullName($data));

        $pdf->SetXY(70, 92);
        $pdf->Write(0, $this->formatDate(
            $this->value($data, [
                'data_prima_seduta',
                'prima_seduta_data',
            ])
        ));

        $pdf->SetXY(70, 107);
        $pdf->Write(0, $this->formatDate(
            $this->value($data, [
                'data_seduta_completamento',
                'data_completamento',
                'data_seduta',
            ])
        ));

        $pdf->SetXY(74, 117);
        $pdf->Write(0, $this->value($data, [
            'costo_seduta_completamento',
            'costo_completamento',
            'costo',
        ]));

        $this->drawCheck($pdf, [
            'assenza_terapie_farmacologiche',
            'nessuna_terapia_farmacologica',
        ], 25.5, 210, $data);

        $this->drawCheck($pdf, [
            'assenza_interventi_estetici_laser',
            'nessun_intervento_estetico_laser',
        ], 25.5, 215, $data);

        $this->drawCheck($pdf, [
            'assenza_reazioni_anomale',
            'nessuna_reazione_anomala',
        ], 25.5, 220, $data);

        $this->drawCheck($pdf, [
            'assenza_gravidanza_sopravvenuta',
            'nessuna_gravidanza_sopravvenuta',
        ], 25.5, 226, $data);

        $pdf->SetFont('helvetica', '', 9);

        $pdf->SetXY(70, 236);
        $pdf->MultiCell(
            116,
            8,
            $this->value($data, [
                'note_aggiuntive',
                'note_salute',
            ]),
            0,
            'L'
        );

        $pdf->SetXY(16, 270);
        $pdf->MultiCell(
            176,
            22,
            $this->value($data, [
                'note_tecnico',
                'valutazione_esito',
                'obiettivi_seduta_completamento',
            ]),
            0,
            'L'
        );
    }

    private function renderPageTwo(Fpdi $pdf, array $data): void
    {
        $pdf->SetFont('helvetica', '', 10);

        $luogoData = $this->value($data, [
            'luogo_data',
        ]);

        if ($luogoData === '') {
            $luogoData = 'Lucca '.now()->format('d/m/Y');
        }

        $pdf->SetXY(22, 118);
        $pdf->Write(0, $luogoData);

        $pdf->SetXY(22, 140);
        $pdf->Write(0, $this->value($data, [
            'tecnico_esecutore',
            'nome_tecnico',
            'tecnico',
        ]));

        if (!empty($data['firma_cliente'])) {
            $firmaCliente = $this->salvaFirmaTemp(
                $data['firma_cliente'],
                'cliente'
            );

            $pdf->Image(
                $firmaCliente,
                100,
                111,
                38,
                15,
                'PNG'
            );
        }

        if (!empty($data['firma_tecnico'])) {
            $firmaTecnico = $this->salvaFirmaTemp(
                $data['firma_tecnico'],
                'tecnico'
            );

            $pdf->Image(
                $firmaTecnico,
                100,
                133,
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
