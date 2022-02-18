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
                width: 250px;'
    ];

    $date = toIndoDateDay(explode(" ", $meeting->start_date)[0]);
    $start = getTime($meeting->start_date);
    $end = getTime($meeting->end_date);
?>

<div>
    <div style="<?= $style['head'] ?>">
        <img style="<?= $style['img'] ?>" src="<?= LOGO_KF ?>" alt="kf">
        <hr>
        <p><?= locName() ?></p>
    </div>

    <div style="<?= $style['body'] ?>">
        <p>Dear Team <b>Umum</b>,</p>
        <p>Berikut ini adalah <b>Notifikasi Meeting</b> yang akan dilaksanakan pada:</p>
        <p style="text-align:center"><b><?= $date.' '.$start.' - '.$end ?></b></p>
        <p>Dengan rincian sebagai berikut:</p>

        <table style="<?= $style['table'] ?>">
            <tr>
                <th style="<?= $style['th'] ?>" colspan="2">Detail Meeting</th>
            </tr>
            <tr>
                <td style="<?= $style['td'] ?>">No. Tiket</td>
                <td style="<?= $style['td'] ?>"><?= $meeting->ticket ?></td>
            </tr>
            <tr>
                <td style="<?= $style['td'] ?>">Nama Ruangan</td>
                <td style="<?= $style['td'] ?>"><?= $meeting->room_name ?></td>
            </tr>
            <tr>
                <td style="<?= $style['td'] ?>">Waktu Meeting</td>
                <td style="<?= $style['td'] ?>"><?= $date . ' ' .  $start . ' - ' . $end ?></td>
            </tr>
            <tr>
                <td style="<?= $style['td'] ?>">Durasi</td>
                <td style="<?= $style['td'] ?>"><?= $meeting->duration ?> Jam</td>
            </tr>
            <tr>
                <td style="<?= $style['td'] ?>">Snack</td>
                <td style="<?= $style['td'] ?>"><?= $meeting->meal > 0  ? '✓' : '-';?></td>
            </tr>
            <tr>
                <td style="<?= $style['td'] ?>">Total Peserta</td>
                <td style="<?= $style['td'] ?>"><?= $meeting->total_participant ?> Orang</td>
            </tr>
            <tr>
                <td style="<?= $style['td'] ?>">Topik Meeting</td>
                <td style="<?= $style['td'] ?>"><?= $meeting->name ?></td>
            </tr>
            <tr>
                <td style="<?= $style['td'] ?>">Jenis Meeting</td>
                <td style="<?= $style['td'] ?>"><?= $meeting->meeting_type == 'internal' ? 'Meeting Internal' : 'Meeting External' ?></td>
            </tr>
            <tr>
                <td style="<?= $style['td'] ?>">Catatan</td>
                <td style="<?= $style['td'] ?>"><?= $meeting->description ?></td>
            </tr>
        </table>
    </div>

    <div style="<?= $style['button_container'] ?>">
        <a href="<?= $linkApprove ?>" style="<?= $style['button'] ?> background-color: #3399cc;">Approve Meeting: <?= $meeting->id ?></a><br/><br/>
        <a href="<?= $linkReject ?>" style="<?= $style['button'] ?> background-color: #db8a10;">Reject Meeting: <?= $meeting->id ?></a>
    </div>

    <div style="<?= $style['footer'] ?>">
    <p>Notifikasi email ini dikirim secara otomatis oleh sistem dan tidak memerlukan balasan</p>
    <hr>
    <p>Kw. Industri Pulo Gadung, Blok N6-11, Jl. Rw. Gelam V No.1, RW.9, Jatinegara, Kec. Cakung, Kota Jakarta Timur, Daerah Khusus Ibukota Jakarta 13920</p>
    </div>
</div>