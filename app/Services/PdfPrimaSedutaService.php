<?php

namespace App\Services;

use setasign\Fpdi\Tcpdf\Fpdi;

class PdfPrimaSedutaService
{
    public function genera($consenso)
    {
        $data = $consenso->data;

        $pdf = new Fpdi();

        $pdf->SetAutoPageBreak(false);
        $pdf->setPrintHeader(false);
        $pdf->setPrintFooter(false);

        $template = storage_path('app/templates/prima_seduta.pdf');

        $pageCount = $pdf->setSourceFile($template);

        for ($pageNo = 1; $pageNo <= $pageCount; $pageNo++) {

            $tpl = $pdf->importPage($pageNo);

            $pdf->AddPage();

            $pdf->useTemplate($tpl, 0, 0, 210);

            $pdf->SetFont('helvetica', '', 10);

            /*
            |--------------------------------------------------------------------------
            | PAGINA 1
            |--------------------------------------------------------------------------
            */

            /*
            for ($i = 0; $i < 210; $i += 10) {
                $pdf->Line($i, 0, $i, 297);
                $pdf->Text($i, 5, $i);
            }

            for ($j = 0; $j < 297; $j += 10) {
                $pdf->Line(0, $j, 210, $j);
                $pdf->Text(2, $j, $j);
            }
            */
            
            if ($pageNo == 1) {

                $pdf->SetXY(72, 82);
                $pdf->Write(0, $data['nome'].' '.$data['cognome']);

                $pdf->SetXY(72, 92);
                $pdf->Write(0, $this->formatDate($data['data_nascita'] ?? null));

                $pdf->SetXY(72, 102);
                $pdf->Write(0, $data['codice_fiscale'] ?? '');

                $pdf->SetXY(72, 112);
                $pdf->Write(0, $data['telefono']);

                $pdf->SetXY(72, 122);
                $pdf->Write(0, $data['email']);

                
                $pdf->SetXY(72, 142);
                $pdf->Write(0, $data['zona']);

                $pdf->SetXY(72, 152);
                $pdf->Write(0, $data['tecnica']);

                $pdf->SetXY(72, 162);
                $pdf->Write(0, $data['pigmenti']);

                $pdf->SetXY(72, 172);
                $pdf->Write(0, $this->formatDate($data['data_seduta'] ?? null));

                $pdf->SetXY(75, 182);
                $pdf->Write(0, $data['costo']);

            }

            /*
            |--------------------------------------------------------------------------
            | PAGINA 2
            |--------------------------------------------------------------------------
            */

            if ($pageNo == 2) {

                /*
                |--------------------------------------------------------------------------
                | CHECKBOX ANAMNESI
                |--------------------------------------------------------------------------
                */

                $this->drawCheck($pdf, 'gravidanza_allattamento', 26, 19, $data);

                $this->drawCheck($pdf, 'malattie_autoimmuni', 26, 25, $data);

                $this->drawCheck($pdf, 'coagulopatie', 26, 30, $data);

                $this->drawCheck($pdf, 'patologie_cutanee', 26, 36, $data);

                $this->drawCheck($pdf, 'allergie', 26, 41, $data);

                $this->drawCheck($pdf, 'isotretinoina', 26, 47, $data);

                $this->drawCheck($pdf, 'laser_peeling', 26, 52, $data);

                /*
                |--------------------------------------------------------------------------
                | CAMPI TESTO
                |--------------------------------------------------------------------------
                */

                $pdf->SetFont('helvetica', '', 9);

                $pdf->SetXY(70, 66);
                $pdf->MultiCell(
                    160,
                    20,
                    $data['condizioni_mediche'] ?? '',
                    0,
                    'L'
                );

                $pdf->SetXY(70, 76);
                $pdf->MultiCell(
                    160,
                    20,
                    $data['farmaci_uso'] ?? '',
                    0,
                    'L'
                );

                /*
                |--------------------------------------------------------------------------
                | PRIVACY
                |--------------------------------------------------------------------------
                */

                $this->drawCheck($pdf, 'privacy_dati', 19, 227, $data);

                $this->drawCheck($pdf, 'privacy_foto', 19, 232, $data);

                $this->drawCheck($pdf, 'privacy_promo', 19, 238, $data);
            }

            /*
            |--------------------------------------------------------------------------
            | PAGINA 3
            |--------------------------------------------------------------------------
            */

            if ($pageNo == 3) {

                $pdf->SetXY(20, 30);
                $pdf->Write(0, 'Lucca '.now()->format('d/m/Y'));

                // FIRMA

                if (!empty($data['firma_cliente'])) {

                    $firma = $this->salvaFirmaTemp($data['firma_cliente']);

                    $pdf->Image(
                        $firma,
                        100,
                        20,
                        35,
                        15,
                        'PNG'
                    );
                }
            }
        }

        return $pdf->Output('', 'S');
    }

    private function check($pdf, $x, $y)
    {
        $pdf->SetXY($x, $y);
        $pdf->SetFont('helvetica', 'B', 12);
        $pdf->Write(0, 'X');
    }

    private function drawCheck($pdf, $field, $x, $y, $data)
    {
        if (!empty($data[$field])) {
            $this->check($pdf, $x, $y);
        }
    }

    private function salvaFirmaTemp($base64)
    {
        $image = str_replace('data:image/png;base64,', '', $base64);
        $image = str_replace(' ', '+', $image);

        $path = storage_path('app/firme/temp.png');

        file_put_contents($path, base64_decode($image));

        return $path;
    }

    private function formatDate($date)
    {
        if (empty($date)) {
            return '';
        }

        try {
            return \Carbon\Carbon::parse($date)->format('d/m/Y');
        } catch (\Exception $e) {
            return $date;
        }
    }

}