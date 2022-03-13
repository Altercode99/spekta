<?php 
    $style = [
        'head' => 'padding: 5px 0px 0px 10px;text-align:center;',
        'img' => 'width: 220px;height: auto;',
        'body' => 'background: white;text-align:center;margin-top: 20px;border-radius: 5px;border: 1px solid #422800;padding: 10px;box-shadow: 5px 10px #ccc;',
        'p' => ' font-family: sans-serif;',
        'footer' => 'margin-top: 10px;',
        'table' => 'font-family:sans-serif;border-collapse: collapse;width:100%;',
        'th' => 'border: 1px solid #422800;padding: 8px;padding-top: 12px;padding-bottom: 12px;text-align: left;background-color: #116171;color: #fff;',
        'td' => 'border: 1px solid #422800;padding: 8px;text-align:left',
        'button_container' => 'padding:10px;text-align:center;margin-top:20px;',
    ];
?>

<div>
    <div style="<?= $style['head'] ?>">
        <img style="<?= $style['img'] ?>" src="<?= LOGO_KF ?>" alt="kf">
        <hr style="border: 1px solid #422800">
        <p><b><?= $location ?></b></p>
    </div>

    <p>Dear <b> <?= $requestor->employee_name ?></b>,</p>
    <p><b>Permintaan Revisi Personil Lembur</b> dengan Nomor: <b><?= $revision->rev_task_id ?></b> sudah di proses oleh <b>SDM</b> dengan status:</p>
    <br />
    <p style="text-align:center"><?= $revision->status ?></p>
    <br />
  
    <p>Adapun lemburan yang hendak di revisi adalah sebagai berikut:</p>
    <table style="<?= $style['table']  ?>">
        <tr>
            <th style="<?= $style['th'] ?>" colspan="2">Data Revisi</th>
        </tr>
        <tr>
            <td style="<?= $style['td'] ?>">Task ID</td>
            <td style="<?= $style['td'] ?>"><b><?= $revision->rev_task_id  ?></b></td>
        </tr>
        <tr>
            <td style="<?= $style['td'] ?>">Task ID Lembur</td>
            <td style="<?= $style['td'] ?>"><b><?= $revision->task_id  ?></b></td>
        </tr>
        <tr>
            <td style="<?= $style['td'] ?>">Sub Unit</td>
            <td style="<?= $style['td'] ?>"><?= $revision->department  ?></td>
        </tr>
        <tr>
            <td style="<?= $style['td'] ?>">Bagian</td>
            <td style="<?= $style['td'] ?>"><?= $revision->sub_department  ?></td>
        </tr>
        <tr>
            <td style="<?= $style['td'] ?>">Sub Unit</td>
            <td style="<?= $style['td'] ?>"><?= $revision->division  ?></td>
        </tr>
        <tr>
            <td style="<?= $style['td'] ?>">Waktu Lembur</td>
            <td style="<?= $style['td'] ?>"><?= toIndoDateTime2($revision->start_date) .' - '.toIndoDateTime2($revision->end_date)  ?></td>
        </tr>
        <tr>
            <td style="<?= $style['td'] ?>">Catatan Revisi</td>
            <td style="<?= $style['td'] ?>;color:green"><b><?= $revision->description ?></b></td>
        </tr>
        <tr>
            <td style="<?= $style['td'] ?>">Tanggapan SDM</td>
            <td style="<?= $style['td'] ?>"><b><?= $revision->response ?></b></td>
        </tr>
    </table>

    <table style="<?= $style['table'] ?> margin-top:20px">
        <tr>
            <th style="<?= $style['th'] ?>" colspan="2">Detail Revisi</th>
        </tr>
        <?php 
            $no = 1;
            foreach ($overtimes as $ovt) {
                $start = toIndoDateTime2($ovt->start_date);
                $end = toIndoDateTime2($ovt->end_date);
        ?>
        <tr>
            <th style="<?= $style['td'] ?>" colspan="2">Personil #<?= $no ?></th>
        </tr>
        <tr>
            <td style="<?= $style['td'] ?>;" width="30%">Nama Personil</td>
            <td style="<?= $style['td'] ?>;" width="70%"><?= $ovt->employee_name ?></td>
        </tr>
        <tr>
            <td style="<?= $style['td'] ?>;" width="30%">Sub Unit</td>
            <td style="<?= $style['td'] ?>;" width="70%"><?= $ovt->department ?></td>
        </tr>
        <tr>
            <td style="<?= $style['td'] ?>;" width="30%">Bagian</td>
            <td style="<?= $style['td'] ?>;" width="70%"><?= $ovt->sub_department ?></td>
        </tr>
        <tr>
            <td style="<?= $style['td'] ?>;" width="30%">Sub Bagian</td>
            <td style="<?= $style['td'] ?>;" width="70%"><?= $ovt->division ?></td>
        </tr>
        <tr>
            <td style="<?= $style['td'] ?>;" width="30%">Jam Lembur</td>
            <td style="<?= $style['td'] ?>;" width="70%"><b><?= "$start - $end" ?></b></td>
        </tr>
        <tr>
            <td style="<?= $style['td'] ?>;" width="30%">Tugas</td>
            <td style="<?= $style['td'] ?>;" width="70%"><?= $ovt->notes ?></td>
        </tr>
        <tr>
            <td style="<?= $style['td'] ?>;" width="30%">Status Awal</td>
            <td style="<?= $style['td'] ?>;color:red;" width="70%"><?= $ovt->status_before ?></td>
        </tr>
        <tr>
            <td style="<?= $style['td'] ?>;" width="30%">Status Akhir</td>
            <td style="<?= $style['td'] ?>;color:red;" width="70%"><?= $ovt->status ?></td>
        </tr>
        <?php $no++; } ?>
    </table>
    <br />
    <table>
        <tr>
            <td style='padding:5px;color:red'>ADD</td>
            <td style='padding:5px;color:red'>:</td>
            <td style='padding:5px;color:red'>Penambahan Personil Baru</td>
        </tr>
        <tr>
            <td style='padding:5px;color:red'>CANCELED</td>
            <td style='padding:5px;color:red'>:</td>
            <td style='padding:5px;color:red'>Pembatalan Lembur Personil</td>
        </tr>
    </table>

    <div style="<?= $style['footer'] ?>">
        <p>Notifikasi email ini dikirim secara otomatis oleh sistem dan tidak memerlukan balasan</p>
        <hr>
        <p>Kw. Industri Pulo Gadung, Blok N6-11, Jl. Rw. Gelam V No.1, RW.9, Jatinegara, Kec. Cakung, Kota Jakarta Timur, Daerah Khusus Ibukota Jakarta 13920</p>
    </div>
</div>