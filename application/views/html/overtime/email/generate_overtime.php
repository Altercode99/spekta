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
        'button' => 'border: 2px solid #422800;
                    border-radius: 30px;
                    box-shadow: #422800 4px 4px 0 0;
                    color: #422800;
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
                    width:280px;'
    ];
?>

<?php 
    $locName = $this->Main->getOne('locations', ['code' => $overtime->location])->name;
?>

<div>
    <div style="<?= $style['head'] ?>">
        <img style="<?= $style['img'] ?>" src="<?= LOGO_KF ?>" alt="kf">
        <hr style="border: 1px solid #422800">
        <p><b><?= $locName ?></b></p>
    </div>

    <div style="<?= $style['body'] ?>">
        <p>Dear Team <b>Teknik & Pemeliharaan</b>,</p>
        <p>Berikut ini adalah data lembur <b><?= $overtime->department ?></b> yang akan dilaksanan pada:</p>
        <p><b><?= toIndoDateDay($overtime->overtime_date) ?></b></p>

        <table style="<?= $style['table'] ?>">
            <tr>
                <th style="<?= $style['th'] ?>" colspan="2">Detail Lembur</th>
            </tr>
            <tr>
                <td style="<?= $style['td'] ?>">No. Memo Lembur</td>
                <td style="<?= $style['td'] ?>"><b><?= $overtime->task_id ?></b></td>
            </tr>
            <tr>
                <td style="<?= $style['td'] ?>">Bagian</td>
                <td style="<?= $style['td'] ?>"><?= $overtime->department ?></td>
            </tr>
            <tr>
                <td style="<?= $style['td'] ?>">Sub Bagian</td>
                <td style="<?= $style['td'] ?>"><?= $overtime->division ?></td>
            </tr>
            <tr>
                <td style="<?= $style['td'] ?>">Tanggal Lembur</td>
                <td style="<?= $style['td'] ?>"><b><?= toIndoDateDay($overtime->overtime_date) ?></b></td>
            </tr>
            <tr>
                <td style="<?= $style['td'] ?>">Waktu Pelaksanaan</td>
                <td style="<?= $style['td'] ?>"><?= '<b>'.toIndoDateTime2($overtime->start_date) .'</b> - <b>'. toIndoDateTime2($overtime->end_date).'</b>' ?></td>
            </tr>
            <tr>
                <td style="<?= $style['td'] ?>">Keperluan Lembur</td>
                <td style="<?= $style['td'] ?>"><?= $overtime->notes ?></td>
            </tr>
            <tr>
                <td style="<?= $style['td'] ?>;border:1px solid #422800;vertical-align:text-top;">Mesin Produksi</td>
                <td style="<?= $style['td'] ?>">
                    <?php 
                        if($overtime->machine_ids) {
                        $machineIds = explode(',', $overtime->machine_ids); 
                        $machines = $this->Mtn->getWhereIn('production_machines', ['id' => $machineIds])->result();
                        $no = 1;
                        foreach ($machines as $m) {
                    ?>
                        <?= '<p>'.$no.'. '.$m->name.'</p>' ?>
                    <?php $no++; } } else { echo '-'; }?>
                </td>
            </tr>
            <tr>
                <td style="<?= $style['td'] ?>;border:1px solid #422800;vertical-align:text-top;">Personil Lembur</td>
                <td style="<?= $style['td'] ?>">
                <?php 
                    $personils = $this->Overtime->getOvertimeDetail(['equal_task_id' => $overtime->task_id, 'notin_status' => 'CANCELED,REJECTED'])->result();
                    $no = 1;
                    foreach ($personils as $personil) {
                ?>
                    <p><?= "$no. $personil->employee_name"?></p>
                <?php $no++; } ?>
                </td>
            </tr>
            <tr>
                <td style="<?= $style['td'] ?>;border:1px solid #422800;vertical-align:text-top;">Kebutuhan Support</td>
                <td style="<?= $style['td'] ?>">
                <?php 
                    $reqs = $this->HrModel->getRequestList($overtime);
                    if(count($reqs['array']) > 0) {
                    $no = 1;
                    foreach ($reqs['array'] as $value) {
                ?>
                    <?= '<p>'.$no.'. '.$value.'</p>'; ?>
                <?php $no++; } } else { echo '-'; }?>
            </tr>
            <tr>
                <td style="<?= $style['td'] ?>;border:1px solid #422800;vertical-align:text-top;">Status Lembur</td>
                <td style="<?= $style['td'] ?>; <?= $overtime->status == 'REJECTED' ? 'color:red;' : null ?>"><?= $overtime->status ?></td>
            </tr>
        </table>
        <table style="<?= $style['table'] ?>">
            <tr>
                <th style="<?= $style['th'] ?>" colspan="2">Detail Personil</th>
            </tr>
            <?php 
                $no = 1;
                foreach ($personils as $personil) {
                    $start = toIndoDateTime2($personil->start_date);
                    $end = toIndoDateTime2($personil->end_date);
            ?>
                <tr>
                    <th style="<?= $style['td'] ?>" colspan="2">Personil #<?= $no ?></th>
                </tr>
                <tr>
                    <td style="<?= $style['td'] ?>;" width="30%">Nama Personil</td>
                    <td style="<?= $style['td'] ?>;" width="70%"><?= $personil->employee_name ?></td>
                </tr>
                <tr>
                    <td style="<?= $style['td'] ?>;" width="30%">Jam Lembur</td>
                    <td style="<?= $style['td'] ?>;" width="70%"><b><?= "$start - $end" ?></b></td>
                </tr>
                <tr>
                    <td style="<?= $style['td'] ?>;" width="30%">Tugas</td>
                    <td style="<?= $style['td'] ?>;" width="70%"><?= $overtime->notes ?></td>
                </tr>
                <tr>
                    <td style="<?= $style['td'] ?>;" width="30%">Status Lembur</td>
                    <td style="<?= $style['td'] ?>;<?= $overtime->status == 'REJECTED' ? 'color:red;' : null?>" width="70%"><?= $overtime->status ?></td>
                </tr>
            <?php $no++; } ?>
        </table>
    </div>


    <div style="<?= $style['button_container'] ?>">
        <a href="<?= $link ?>" style="<?= $style['button'] ?>">Generate Lembur Teknik & Pemeliharaan</a>
    </div>

    <div style="<?= $style['footer'] ?>">
    <p>Notifikasi email ini dikirim secara otomatis oleh sistem dan tidak memerlukan balasan</p>
        <hr>
        <p>Kw. Industri Pulo Gadung, Blok N6-11, Jl. Rw. Gelam V No.1, RW.9, Jatinegara, Kec. Cakung, Kota Jakarta Timur, Daerah Khusus Ibukota Jakarta 13920</p>
    </div>
</div>