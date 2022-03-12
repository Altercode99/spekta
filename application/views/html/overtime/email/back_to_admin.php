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

    <p>Dear <b><?= $emp->employee_name ?></b>,</p>
    <p>Berikut ini adalah <b>Permintaan Revisi Lembur</b> dari <b><?= $requestor->employee_name ?></b> dengan Task ID: <b><?= $overtime->task_id ?></b></p>
    <p>Instruksi Revisi:</p>
    <br />
    <p style="text-align:center"><?= $revisionNote ?></p>
    <br />
  
    <p>Adapun lemburan yang hendak di revisi adalah sebagai berikut:</p>
    <table style="<?= $style['table']  ?>">
        <tr>
            <th style="<?= $style['th'] ?>" colspan="2">Detail Lembur</th>
        </tr>
        <tr>
            <td style="<?= $style['td'] ?>">Task ID</td>
            <td style="<?= $style['td'] ?>"><b><?= $overtime->task_id  ?></b></td>
        </tr>
        <tr>
            <td style="<?= $style['td'] ?>">Sub Unit</td>
            <td style="<?= $style['td'] ?>"><?= $overtime->department  ?></td>
        </tr>
        <tr>
            <td style="<?= $style['td'] ?>">Bagian</td>
            <td style="<?= $style['td'] ?>"><?= $overtime->sub_department  ?></td>
        </tr>
        <tr>
            <td style="<?= $style['td'] ?>">Sub Unit</td>
            <td style="<?= $style['td'] ?>"><?= $overtime->division  ?></td>
        </tr>
        <tr>
            <td style="<?= $style['td'] ?>">Waktu Lembur</td>
            <td style="<?= $style['td'] ?>"><?= toIndoDateTime2($overtime->start_date) .' - '.toIndoDateTime2($overtime->end_date)  ?></td>
        </tr>
        <tr>
            <td style="<?= $style['td'] ?>">Tugas</td>
            <td style="<?= $style['td'] ?>"><?= $overtime->notes ?></td>
        </tr>
    </table>

    <div style="<?= $style['footer'] ?>">
        <p>Notifikasi email ini dikirim secara otomatis oleh sistem dan tidak memerlukan balasan</p>
        <hr>
        <p>Kw. Industri Pulo Gadung, Blok N6-11, Jl. Rw. Gelam V No.1, RW.9, Jatinegara, Kec. Cakung, Kota Jakarta Timur, Daerah Khusus Ibukota Jakarta 13920</p>
    </div>
</div>