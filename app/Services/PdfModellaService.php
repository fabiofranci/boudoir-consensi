<?php

namespace App\Services;

use setasign\Fpdi\Tcpdf\Fpdi;

class PdfModellaService
{
    public function genera($consenso)
    {
        $data = $consenso->data;

        $pdf = new Fpdi();

        $pdf->SetAutoPageBreak(false);
        $pdf->setPrintHeader(false);
        $pdf->setPrintFooter(false);

        $template = storage_path('app/templates/modella.pdf');

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
        $pdf->SetXY(25, 80.5);
        $pdf->Write(0, $this->fullName($data));

        $pdf->SetXY(24, 99);
        $pdf->Write(0, $this->formatDate(
            $this->value($data, ['data_nascita'])
        ));

        $pdf->SetXY(104, 99);
        $pdf->Write(0, $this->value($data, ['luogo_nascita']));

        $pdf->SetXY(24, 116.5);
        $pdf->Write(0, $this->value($data, ['codice_fiscale']));

        $pdf->SetXY(104, 116.5);
        $pdf->Write(0, $this->value($data, ['nazionalita']));

        $pdf->SetXY(25, 125);
        $pdf->MultiCell(
            160,
            8,
            $this->value($data, ['indirizzo_residenza']),
            0,
            'L'
        );

        $pdf->SetXY(24, 144.5);
        $pdf->Write(0, $this->value($data, ['telefono']));

        $pdf->SetXY(104, 144.5);
        $pdf->Write(0, $this->value($data, ['email']));

        $pdf->SetXY(24, 179.5);
        $pdf->Write(0, $this->value($data, ['zona']));

        $pdf->SetXY(104, 179.5);
        $pdf->Write(0, $this->value($data, ['tecnica']));

        $pdf->SetXY(24, 197);
        $pdf->Write(0, $this->formatDate(
            $this->value($data, ['data_trattamento'])
        ));

        $pdf->SetXY(104, 197);
        $pdf->Write(0, $this->value($data, ['ora_trattamento']));

        $pdf->SetXY(25, 205.5);
        $pdf->Write(0, $this->value($data, ['operatrice_trainer']));

        $this->drawCheck($pdf, ['gravidanza_allattamento'], 20, 238, $data);
        $this->drawCheck($pdf, ['epilessia'], 100, 238, $data);

        $this->drawCheck($pdf, ['diabete_mellito'], 20, 248, $data);
        $this->drawCheck($pdf, ['psoriasi_eczema'], 100, 248, $data);

        $this->drawCheck($pdf, ['disturbi_coagulazione'], 20, 254.2, $data);
        $this->drawCheck($pdf, ['terapia_anticoagulanti_roaccutan'], 100, 254.2, $data);

        $this->drawCheck($pdf, ['keloidosi_cicatrici_ipertrofiche'], 20, 260.6, $data);
        $this->drawCheck($pdf, ['herpes_ricorrente'], 100, 260.6, $data);

        $this->drawCheck($pdf, ['allergie_pigmenti_nichel_anestetici'], 20, 267, $data);
        $this->drawCheck($pdf, ['trattamenti_laser_recenti'], 100, 267, $data);
    }

    private function renderPageTwo(Fpdi $pdf, array $data): void
    {
        $this->drawCheck($pdf, ['malattie_autoimmuni'], 24, 28, $data);
        $this->drawCheck($pdf, ['altre_patologie_presente'], 104, 28, $data);

        $pdf->SetFont('helvetica', '', 9);

        $pdf->SetXY(130, 27.5);
        $pdf->Write(0, $this->value($data, ['altre_patologie_testo']));

        $pdf->SetXY(25, 37);
        $pdf->MultiCell(
            160,
            12,
            $this->value($data, ['note_mediche_aggiuntive']),
            0,
            'L'
        );

        $this->drawCheck($pdf, ['consenso_tecnica_materiali'], 24, 81.5, $data);
        $this->drawCheck($pdf, ['consenso_risultato_guarigione'], 24, 87.5, $data);
        $this->drawCheck($pdf, ['consenso_no_aspettative_garantite'], 24, 93.5, $data);
        $this->drawCheck($pdf, ['consenso_aftercare'], 24, 99.5, $data);

        $this->drawCheck($pdf, ['autorizza_social'], 24, 152.5, $data);
        $this->drawCheck($pdf, ['autorizza_materiali_promozionali'], 24, 158.5, $data);
        $this->drawCheck($pdf, ['autorizza_didattico'], 24, 164.5, $data);

        $pdf->SetFont('helvetica', '', 10);

        if (!empty($data['firma_cliente'])) {
            $firmaCliente = $this->salvaFirmaTemp(
                $data['firma_cliente'],
                'modella_cliente'
            );

            $pdf->Image(
                $firmaCliente,
                25,
                214,
                38,
                15,
                'PNG'
            );
        }

        if (!empty($data['firma_operatrice'])) {
            $firmaOperatrice = $this->salvaFirmaTemp(
                $data['firma_operatrice'],
                'modella_operatrice'
            );

            $pdf->Image(
                $firmaOperatrice,
                105,
                214,
                38,
                15,
                'PNG'
            );
        }

        $dataFirma = $this->formatDate(
            $this->value($data, [
                'data_trattamento',
                'data_seduta',
            ])
        );

        if ($dataFirma === '') {
            $dataFirma = now()->format('d/m/Y');
        }

        $pdf->SetXY(24, 231.5);
        $pdf->Write(0, $dataFirma);
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
