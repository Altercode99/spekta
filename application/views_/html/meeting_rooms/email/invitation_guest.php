<?php 
    $style = [
        'head' => 'padding: 5px 0px 0px 10px;text-align:center;',
        'img' => 'width: 220px;height: auto;',
        'body' => 'background: white;margin-top: 20px;border-radius: 5px;border: 1px solid #422800;padding: 10px;box-shadow: 5px 10px #ccc;',
        'p' => ' font-family: sans-serif;',
        'footer' => 'margin-top: 10px;',
        'table' => 'font-family:sans-serif;border-collapse: collapse;width:100%;',
        'th' => 'border: 1px solid #422800;padding: 8px;padding-top: 12px;padding-bottom: 12px;text-align: left;background-color: #116171;color: #fff;',
        'td' => 'border: 1px solid #422800;padding: 8px;text-align:left',
        'button_container' => 'padding:10px;text-align:center;margin-top:20px;',
        'button' => 'border: 2px solid #422800;
                border-radius: 30px;
                box-shadow: #422800 4px 4px 0 0;
                color: #ddd;
                cursor: pointer;
                display: inline-block;
                font-weight: 600;
                font-size: 12px;
                padding: 0 12px;
                line-height: 40px;
                text-align: center;
                text-decoration: none;
                user-select: none;
                -webkit-user-select: none;
                touch-action: manipulation;
                width: 150px;'
    ];
    $date = toIndoDateDay(explode(" ", $meeting->start_date)[0]);
    $start = getTime($meeting->start_date);
    $end = getTime($meeting->end_date);
?>

<div>
    <div style="<?= $style['head'] ?>">
        <img style="<?= $style['img'] ?>" src="<?= LOGO_KF ?>" alt="kf">
        <hr style="border: 1px solid #422800">
        <p><b><?= $location ?></b></p>
    </div>

    <div style="<?= $style['body'] ?>">
        <p>Dear Bapak/Ibu <b><?= $guest->name ?></b> dari <b><?= $guest->company ?></b>,</p>
        <p>Berikut ini adalah <b>Undangan <?= $type ?></b> dari <b><?= "$meeting->employee_name ($location)" ?></b> dengan Nomor: <b><?= $meeting->id ?></b></p>
        <p>Yang akan dilaksanakan pada:</p>
        <p style="text-align:center"><b><?= $date.' '.$start.' - '.$end ?></b></p>
        <p>Adapun detail meeting yang akan di laksanan adalah sebagai berikut:</p>
        <table style="<?= $style['table']  ?>">
            <tr>
                <th style="<?= $style['th'] ?>" colspan="2">Detail Meeting</th>
            </tr>
            <tr>
                <td style="<?= $style['td'] ?>">No. Tiket</td>
                <td style="<?= $style['td'] ?>"><?= $meeting->ticket ?></td>
            </tr>
            <tr>
                <td style="<?= $style['td'] ?>">Topik</td>
                <td style="<?= $style['td'] ?>"><b><?= $meeting->name  ?></b></td>
            </tr>
            <tr>
                <td style="<?= $style['td'] ?>">Jenis Meeting</td>
                <td style="<?= $style['td'] ?>"><?= $type  ?></td>
            </tr>
            <tr>
                <td style="<?= $style['td'] ?>">Deskripsi</td>
                <td style="<?= $style['td'] ?>"><?= $meeting->description  ?></td>
            </tr>
            <tr>
                <td style="<?= $style['td'] ?>">Ruang Meeting</td>
                <td style="<?= $style['td'] ?>"><?= $meeting->room_name  ?></td>
            </tr>
            <tr>
                <td style="<?= $style['td'] ?>">Waktu Meeting</td>
                <td style="<?= $style['td'] ?>"><?= $date.' '.$start.' - '.$end ?></td>
            </tr>
            <tr>
                <td style="<?= $style['td'] ?>">Durasi</td>
                <td style="<?= $style['td'] ?>"><?= $meeting->duration ?> Jam</td>
            </tr>
        </table>

    </div>
    
    <p>Silahkan tekan tombol dibawah ini untuk melakukan konfirmasi kehadiran</b>,</p>
    <p style='color:red'><i><b>Note: Jumlah Snack akan di sesuaikan dengan jumlah peserta yang melakukan konfirmasi kehadiran!</b></i></p>

    <div style="<?= $style['button_container'] ?>">
        <a href="<?= $linkA ?>" style="<?= $style['button'] ?> background-color: #116171;">HADIR</a><br/><br/>
        <a href="<?= $linkR ?>" style="<?= $style['button'] ?> background-color: #db8a10;">TIDAK HADIR</a>
    </div>
</div>