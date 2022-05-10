<?php 
    $style = [
        'head' => 'padding: 5px 0px 0px 10px;text-align:center;',
        'img' => 'width: 220px;height: auto;',
        'body' => 'background: white;text-align:center;margin-top: 20px;border-radius: 5px;border: 1px solid #422800;padding: 10px;box-shadow: 5px 10px #ccc;',
        'p' => ' font-family: sans-serif;',
        'footer' => 'margin-top: 10px;',
        'table' => 'font-family:sans-serif;border-collapse: collapse;width:100%;',
        'th' => 'font-size:10px;border: 1px solid #422800;padding: 8px;padding-top: 12px;padding-bottom: 12px;text-align: left;background-color: #116171;color: #fff;',
        'td' => 'font-size:10px;border: 1px solid #422800;padding: 8px;text-align:left',
        'button_container' => 'padding:10px;text-align:center;margin-top:20px;',
    ];
?>

<div>
    <div style="<?= $style['head'] ?>">
        <img style="<?= $style['img'] ?>" src="<?= LOGO_KF ?>" alt="kf">
        <hr style="border: 1px solid #422800">
        <p><b><?= $location ?></b></p>
    </div>

    <p>Dear <b><?= $sdm->employee_name ?></b>,</p>
    <p>Berikut ini data karyawan yang akan habis kontrak bulan <b><?= toIndoMonth($date) ?></b></p>
    <p>Adapun data karyawan tersebut adalah sebagai berikut:</p>

    <table style="<?= $style['table']  ?>">
        <tr>
            <th style="<?= $style['th'] ?>" colspan="6">Data Karyawan</th>
        </tr>
        <tr>
            <th style="<?= $style['th'] ?>">No</th>
            <th style="<?= $style['th'] ?>">Nama Karyawan</th>
            <th style="<?= $style['th'] ?>">Bagian</th>
            <th style="<?= $style['th'] ?>">Sub Bagian</th>
            <th style="<?= $style['th'] ?>">Status</th>
            <th style="<?= $style['th'] ?>">Tgl Habis Kontrak</th>
        </tr>
        <?php $no = 1; foreach ($emps as $emp) { ?>
            <tr>
                <td style="<?= $style['td'] ?>"><?= $no ?></td>
                <td style="<?= $style['td'] ?>"><?= $emp->employee_name ?></td>
                <td style="<?= $style['td'] ?>"><?= $emp->sub_department ?></td>
                <td style="<?= $style['td'] ?>"><?= $emp->division ?></td>
                <td style="<?= $style['td'] ?>"><?= $emp->employee_status ?></td>
                <td style="<?= $style['td'] ?>"><?= toIndoDate($emp->sk_end_date) ?></td>
            </tr>
        <?php $no++; } ?>
    </table>

    <div style="<?= $style['footer'] ?>">
        <p>Notifikasi email ini dikirim secara otomatis oleh sistem dan tidak memerlukan balasan</p>
        <hr>
        <p>Kw. Industri Pulo Gadung, Blok N6-11, Jl. Rw. Gelam V No.1, RW.9, Jatinegara, Kec. Cakung, Kota Jakarta Timur, Daerah Khusus Ibukota Jakarta 13920</p>
    </div>
</div>