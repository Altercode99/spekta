<?php 
    $style = [
        'head' => 'padding: 5px 0px 0px 10px;text-align:center;',
        'img' => 'width: 220px;height: auto;',
        'body' => 'background: white;margin-top: 20px;border-radius: 5px;border: 1px solid #ccc;padding: 10px;box-shadow: 5px 10px #ccc;',
        'p' => ' font-family: sans-serif;',
        'footer' => 'margin-top: 10px;',
        'table' => 'font-family:sans-serif;border-collapse: collapse;width:100%;',
        'th' => 'border: 1px solid #ddd;padding: 8px;padding-top: 12px;padding-bottom: 12px;text-align: left;background-color: #116171;color: white;',
        'td' => 'border: 1px solid #ddd;padding: 8px;',
    ];

    $date = toIndoDateDay(explode(" ", $data->start_date)[0]);
    $start = getTime($data->start_date);
    $end = getTime($data->end_date);
?>

<div>
    <div style="<?= $style['head'] ?>">
        <img style="<?= $style['img'] ?>" src="<?= LOGO_KF ?>" alt="kf">
        <hr>
        <p><?= locName() ?></p>
    </div>

    <div style="<?= $style['body'] ?>">
        <p>Dear Team,</p>
        <p>Berikut ini adalah <b>Notifikasi Perjalanan Dinas</b> yang telah disetujui oleh <b><?= empName() ?></b> yang akan dilaksanakan pada</P>
        <p style="text-align:center"><b><?= $date.' '.$start.' - '.$end ?></b></p>
        <p>Dengan rincian sebagai berikut:</p>

        <table style="<?= $style['table'] ?>">
            <tr>
                <th style="<?= $style['th'] ?>" colspan="2">Detail Perjalanan</th>
            </tr>
            <tr>
                <td style="<?= $style['td'] ?>">No. Tiket</td>
                <td style="<?= $style['td'] ?>"><b><?= $data->ticket ?></b></td>
            </tr>
            <tr>
                <td style="<?= $style['td'] ?>">Nama Kendaraan</td>
                <td style="<?= $style['td'] ?>"><?= $data->vehicle_name ?></td>
            </tr>
            <tr>
                <td style="<?= $style['td'] ?>">Nomor Polisi</td>
                <td style="<?= $style['td'] ?>"><?= $data->police_no ?></td>
            </tr>
            <tr>
                <td style="<?= $style['td'] ?>">Tipe Kendaraan</td>
                <td style="<?= $style['td'] ?>"><?= $data->brand.' '.$data->type ?></td>
            </tr>
            <tr>
                <td style="<?= $style['td'] ?>">Kapasitas Penumpang</td>
                <td style="<?= $style['td'] ?>"><?= $data->passenger_capacity ?> Orang</td>
            </tr>
            <tr>
                <td style="<?= $style['td'] ?>">Waktu Perjalanan</td>
                <td style="<?= $style['td'] ?>"><?= $date . ' ' .  $start . ' - ' . $end ?></td>
            </tr>
            <tr>
                <td style="<?= $style['td'] ?>">Durasi</td>
                <td style="<?= $style['td'] ?>"><?= $data->duration ?> Jam</td>
            </tr>
            <tr>
                <td style="<?= $style['td'] ?>">Driver</td>
                <td style="<?= $style['td'] ?>"><?= $driver ?></td>
            </tr>
            <tr>
                <td style="<?= $style['td'] ?>;border:1px solid #ccc;vertical-align:text-top;">Penumpang</td>
                <td style="<?= $style['td'] ?>">
                    <?php $no = 1; foreach($passenger as $pass)  { ?>
                        <p><?= "$no. $pass->employee_name ($pass->sub_name)" ?></p>
                    <?php $no++; } ?>
                </td>
            </tr>
            <tr>
                <td style="<?= $style['td'] ?>">Catatan</td>
                <td style="<?= $style['td'] ?>"><?= $data->description ?></td>
            </tr>
        </table>
    </div>

    <div style="<?= $style['footer'] ?>">
    <p>Notifikasi email ini dikirim secara otomatis oleh sistem dan tidak memerlukan balasan</p>
    <hr>
    <p>Kw. Industri Pulo Gadung, Blok N6-11, Jl. Rw. Gelam V No.1, RW.9, Jatinegara, Kec. Cakung, Kota Jakarta Timur, Daerah Khusus Ibukota Jakarta 13920</p>
    </div>
</div>