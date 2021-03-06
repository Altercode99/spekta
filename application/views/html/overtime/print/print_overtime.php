<?php

class PDF extends PDF_MC_Table
{
    public function Header()
    {
        $fontSize = 12;
        $this->SetFont('Times', '', $fontSize);
        $this->Cell(25, 5, 'Lampiran 2', 0, 0, 'L');
        $this->Cell(155, 5, $this->global, 0, 0, 'R');
        $this->Ln(10);
        $this->SetFont('Times', 'B', $fontSize);
        $this->Cell(180, 5, 'FORM INSTRUKSI OVERTIME', 0, 0, 'C');
        $this->Ln(10);
    }

    //Page Content
    public function Content($ovt, $ovtDetail)
    {

        $fontSize1 = 12;
        $fontSize2 = 10;
        $start = date('Y-m-d', strtotime($ovt->start_date));
        $end = date('Y-m-d', strtotime($ovt->end_date));
        if($start != $end) {
            $rangeDate = toIndoDateDay($start) .' - '.toIndoDateDay($end);
        } else {
            $rangeDate = toIndoDateDay($start);
        }
        $this->SetFont('Times', '', $fontSize1);
        $this->Cell(50, 5, '1. Bagian', 0, 0, 'L');
        $this->Cell(130, 5, ': ' . $ovt->sub_department, 0, 0, 'L');
        $this->Ln();
        $this->Cell(50, 5, '2. Jumlah Orang', 0, 0, 'L');
        $this->Cell(130, 5, ': ' . $ovt->personil, 0, 0, 'L');
        $this->Ln();
        $this->Cell(50, 5, '3. Target Pengerjaan', 0, 0, 'L');
        $this->Ln();
        $this->Cell(50, 5, '    Hari/Tanggal', 0, 0, 'L');
        $this->Cell(130, 5, ': ' . $rangeDate, 0, 0, 'L');
        $this->Ln();
        $this->Cell(50, 5, '4. Jam Mulai', 0, 0, 'L');
        $this->Cell(130, 5, ': ' . getTime($ovt->start_date), 0, 0, 'L');
        $this->Ln();
        $this->Cell(50, 5, '5. Jam Selesai', 0, 0, 'L');
        $this->Cell(130, 5, ': ' . getTime($ovt->end_date), 0, 0, 'L');
        $this->Ln();
        $this->Cell(50, 5, '6. Detail Personil Overtime', 0, 0, 'L');
        $this->Cell(130, 5, ': ', 0, 0, 'L');

        $this->Ln(10);
        $this->SetFont('Times', '', $fontSize2);
        $this->Cell(7, 10, 'No', 1, 0, 'C');
        $this->Cell(50, 10, 'Nama', 1, 0, 'C');
        $this->Cell(73, 10, 'Tugas', 1, 0, 'C');
        $this->Cell(55, 5, 'Realisasi', 1, 0, 'C');
        $this->Ln(5);
        $this->Cell(130, 5, '', 0, 0, 'C');
        $this->Cell(20, 5, 'Jam Mulai', 1, 0, 'C');
        $this->Cell(20, 5, 'Jam Selesai', 1, 0, 'C');
        $this->Cell(15, 5, 'Jam Hit', 1, 0, 'C');
        $this->Ln();

        $this->SetFont('Times', '', $fontSize2);
        $this->SetWidths([7, 50, 73, 20, 20, 15]);
        $no = 1;
        foreach ($ovtDetail as $ovtD) {
            if($ovtD->status != 'CANCELED' && $ovtD != 'REJECTED') {
                $this->Row([$no, ucwords(strtolower($ovtD->employee_name)), $ovtD->notes, getTime($ovtD->start_date), getTime($ovtD->end_date), $ovtD->overtime_hour]);
                $no++;
            }
        }

        $isPPIC = false;
        if($ovtD->sub_department == 1 || $ovtD->sub_department == 2 || $ovtD->sub_department == 3 || $ovtD->sub_department == 4 || $ovtD->sub_department == 13) {
            $isPPIC = true;
        } else {
            $isPPIC = false;
        }

        $this->Ln(5);
        $this->Cell(0.01, $isPPIC ? 100 : 80, $ovt->overtime_review, 0, 0, 'L');
        $this->Cell(57, 5, 'Approval Supervisor', 1, 0, 'C');
        $this->Cell(128, 5, 'Evaluasi Overtime', 1, 0, 'C');
        $this->Ln();
        $this->Cell(35, 5, 'Instruksi', 1, 0, 'L');
        $this->Cell(22, 5, 'Evaluasi', 1, 0, 'L');
        $this->Cell(128, 5, 'Narasi singkat pencapaian pekerjaan dalam overtime', 1, 0, 'L');
        $this->Ln();
        $this->SetFont('Times', 'B', 8);
        $this->setFillColor(164, 213, 180);
        $this->Cell(35, 10, $ovt->apv_spv_date != '0000-00-00 00:00:00' ? toIndoDateTime5($ovt->apv_spv_date) : '', 1, 0, 'C', $ovt->apv_spv_date != '0000-00-00 00:00:00' ? 1 : 0);
        $this->Cell(22, 10, '', 1, 0, 'L');
        $this->Cell(128, $isPPIC ? 90 : 70, $ovt->overtime_review, 1, 0, 'L');

        $this->Ln(10);
        $this->SetFont('Times', '', $fontSize2);
        $this->Cell(57, 5, 'Approval Asman Terkait', 1, 0, 'C');
        $this->Ln(5);
        $this->Cell(35, 5, 'Instruksi', 1, 0, 'L');
        $this->Cell(22, 5, 'Evaluasi', 1, 0, 'L');
        $this->Ln(5);
        $this->SetFont('Times', 'B', 8);
        $this->Cell(35, 10, $ovt->apv_asman_date != '0000-00-00 00:00:00' ? toIndoDateTime5($ovt->apv_asman_date) : '', 1, 0, 'C', $ovt->apv_asman_date != '0000-00-00 00:00:00' ? 1 : 0);
        $this->Cell(22, 10, '', 1, 0, 'L');

        if($isPPIC) {
            $this->Ln(10);
            $this->SetFont('Times', '', $fontSize2);
            $this->Cell(57, 5, 'Approval Asman PP Produksi', 1, 0, 'C');
            $this->Ln(5);
            $this->Cell(35, 5, 'Instruksi', 1, 0, 'L');
            $this->Cell(22, 5, 'Evaluasi', 1, 0, 'L');
            $this->Ln(5);
            $this->SetFont('Times', 'B', 8);
            $this->Cell(35, 10, $ovt->apv_ppic_date != '0000-00-00 00:00:00' ? toIndoDateTime5($ovt->apv_ppic_date) : '', 1, 0, 'C', $ovt->apv_ppic_date != '0000-00-00 00:00:00' ? 1 : 0);
            $this->Cell(22, 10, '', 1, 0, 'L');
        }

        $this->Ln(10);
        $this->SetFont('Times', '', $fontSize2);
        $this->Cell(57, 5, 'Approval Manager Terkait', 1, 0, 'C');
        $this->Ln(5);
        $this->Cell(35, 5, 'Instruksi', 1, 0, 'L');
        $this->Cell(22, 5, 'Evaluasi', 1, 0, 'L');
        $this->Ln(5);
        $this->SetFont('Times', 'B', 8);
        $this->Cell(35, 10, $ovt->apv_mgr_date != '0000-00-00 00:00:00' ? toIndoDateTime5($ovt->apv_mgr_date) : '', 1, 0, 'C', $ovt->apv_mgr_date != '0000-00-00 00:00:00' ? 1 : 0);
        $this->Cell(22, 10, '', 1, 0, 'L');

        $this->Ln(10);
        $this->SetFont('Times', '', $fontSize2);
        $this->Cell(57, 5, 'Approval Plant Manager', 1, 0, 'C');
        $this->Ln(5);
        $this->Cell(35, 5, 'Instruksi', 1, 0, 'L');
        $this->Cell(22, 5, 'Evaluasi', 1, 0, 'L');
        $this->Ln(5);
        $this->SetFont('Times', 'B', 8);
        $this->Cell(35, 10, $ovt->apv_head_date != '0000-00-00 00:00:00' ? toIndoDateTime5($ovt->apv_head_date) : '', 1, 0, 'C', $ovt->apv_head_date != '0000-00-00 00:00:00' ? 1 : 0);
        $this->Cell(22, 10, '', 1, 0, 'L');
    }

    //Page footer
    public function Footer()
    {
        //atur posisi 1.5 cm dari bawah
        $this->SetY(-15);
        //buat garis horizontal
        $this->Line(10, $this->GetY(), 290, $this->GetY());
        //Arial italic 9
        $this->SetFont('Times', 'I', 9);
        //nomor halaman
        $this->Cell(0, 10, 'Halaman ' . $this->PageNo() . ' dari {nb}', 0, 0, 'R');
    }
}

$pdf = new PDF('P');
$pdf->SetMargins(15, 20, 15, 15);
$pdf->SetTitle("Lembur $ovt->task_id");
$pdf->AliasNbPages();
$pdf->SetGlobal($ovt->task_id);
$pdf->AddPage();
$pdf->Content($ovt, $ovtDetail);
$pdf->Output();
