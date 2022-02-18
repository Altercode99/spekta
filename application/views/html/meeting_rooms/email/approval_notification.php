<?php 
    $style = [
        'head' => 'padding: 5px 0px 0px 10px;text-align:center;',
        'img' => 'width: 220px;height: auto;',
        'body' => 'background: white;margin-top: 20px;border-radius: 5px;border: 1px solid #422800;padding: 10px;box-shadow: 5px 10px #ccc;',
        'p' => ' font-family: sans-serif;',
        'footer' => 'margin-top: 10px;',
        'table' => 'font-family:sans-serif;border-collapse: collapse;width:100%;',
        'th' => 'border: 1px solid #ddd;padding: 8px;padding-top: 12px;padding-bottom: 12px;text-align: left;background-color: #116171;color: white;',
        'td' => 'border: 1px solid #ddd;padding: 8px;',
    ];

    $expDate = explode(" ", $meeting->start_date)[0];
    $date = toIndoDateDay($expDate);
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
        <p>Dear Team,</p>
        <p>Berikut ini adalah <b>Notifikasi Approval Meeting</b> yang telah disetujui oleh <b><?= $empName ?></b> yang akan dilaksanakan pada:</P>
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
                <td style="<?= $style['td'] ?>">Banyaknya Meeting</td>
                <td style="<?= $style['td'] ?>">
                <?php if($meeting->repeat_meet > 1) { ?>
                    <b style='color:red'><?= $meeting->repeat_meet ?>X (Sampai Tgl <?= explode(' ', addDayToDate($expDate, ($meeting->repeat_meet - 1)))[0].' '.$end ?>)</b>
                <?php } else { ?>
                    <b style='color:red'><?= $meeting->repeat_meet ?>X</b>
                <?php } ?>
                </td>
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

    <div style="<?= $style['footer'] ?>">
    <p>Notifikasi email ini dikirim secara otomatis oleh sistem dan tidak memerlukan balasan</p>
    <hr>
    <p>Kw. Industri Pulo Gadung, Blok N6-11, Jl. Rw. Gelam V No.1, RW.9, Jatinegara, Kec. Cakung, Kota Jakarta Timur, Daerah Khusus Ibukota Jakarta 13920</p>
    </div>
</div>