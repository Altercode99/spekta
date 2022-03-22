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
        <p>Dear <b>Team</b>,</p>
        <p>Berikut ini adalah data lembur<?= $level ?>yang <b>DIBATALKAN</b> oleh <b><?= $rank ?></b> pada:</p>
        <p><b><?= toIndoDateDay(date('Y-m-d')) ?></b></p>

        <table style="<?= $style['table'] ?>">
            <tr>
                <th style="<?= $style['th'] ?>" colspan="2">Detail Lembur</th>
            </tr>
            <tr>
                <td style="<?= $style['td'] ?>">No. Memo Lembur</td>
                <td style="<?= $style['td'] ?>"><b><?= $overtime->task_id ?></b></td>
            </tr>
            <tr>
                <td style="<?= $style['td'] ?>">Sub Unit</td>
                <td style="<?= $style['td'] ?>"><?= $overtime->department ?></td>
            </tr>
            <tr>
                <td style="<?= $style['td'] ?>">Bagian</td>
                <td style="<?= $style['td'] ?>"><?= $overtime->sub_department ?></td>
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
                <td style="<?= $style['td'] ?>">Kebutuhan Personil</td>
                <td style="<?= $style['td'] ?>"><?= $overtime->personil ?> Orang</td>
            </tr>
            <tr>
                <td style="<?= $style['td'] ?>">Kebutuhan Lembur</td>
                <td style="<?= $style['td'] ?>"><?= $overtime->notes ?></td>
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
                <td style="<?= $style['td'] ?>;color:red;">REJECTED</td>
            </tr>
        </table>

        
        <?php 
            $personils = $this->Overtime->getOvertimeDetail(['equal_task_id' => $overtime->task_id, 'notin_status' => 'CANCELED', 'order_by' => ['start_date' => 'ASC']])->result();
            $machineList = [];
            foreach ($personils as $personil) {
                if($personil->machine_1) {
                    $machineList[$personil->machine_1] = $personil->machine_1;
                }
                if($personil->machine_2) {
                    $machineList[$personil->machine_2] = $personil->machine_2;
                }
            }

            $personilIdeal = [];
            if(count($machineList) > 0) {
                $machineDetail = $this->Mtn->getWhereIn('production_machines', ['name' => $machineList])->result();
                foreach ($machineDetail as $mcn) {
                    $personilIdeal[$mcn->name] = $mcn->personil_ideal;
                }
            }
            
            $dataMachine = [];
            $dataNonMachine = [];
            foreach ($personils as $personil) {
                $start = toIndoDateTime2($personil->start_date);
                $end = toIndoDateTime2($personil->end_date);
                $st = dtToFloat($personil->start_date);
                if($personil->machine_1) {
                    if(array_key_exists($personil->machine_1, $machineList)) {
                        $dataMachine[$personil->machine_1][$st][] = [
                            'name' => $personil->employee_name,
                            'sub_department' => $personil->sub_department,
                            'division' => $personil->division,
                            'overtime_hour' => "$start - $end",
                            'task' => $personil->notes,
                            'status' => $personil->status,
                            'order' => $st
                        ];
                    } 
                } else {
                    $dataNonMachine[] = [
                        'name' => $personil->employee_name,
                        'sub_department' => $personil->sub_department,
                        'division' => $personil->division,
                        'overtime_hour' => "$start - $end",
                        'task' => $personil->notes,
                        'status' => $personil->status,
                        'order' => $st
                    ];
                }

                if($personil->machine_2) {
                    if(array_key_exists($personil->machine_2, $machineList)) {
                        $dataMachine[$personil->machine_2][$st][] = [
                            'name' => $personil->employee_name,
                            'sub_department' => $personil->sub_department,
                            'division' => $personil->division,
                            'overtime_hour' => "$start - $end",
                            'task' => $personil->notes,
                            'status' => $personil->status,
                            'order' => $st
                        ];
                    }
                }
            }
        ?>

        <?php if(count($dataMachine) > 0) { ?>
        <table style="<?= $style['table'] ?> margin-top:20px">
            <tr>
                <th style="<?= $style['th'] ?>" colspan="2">Detail Personil Mesin</th>
            </tr>
            <?php 
                foreach ($dataMachine as $mKey => $mValue) { 
                    $sesi = 1;
                    foreach ($mValue as $timeKey => $tValue) {
            ?>
                <tr>
                    <th style="<?= $style['td'] ?>;" colspan="2">
                        <div style='display:flex;flex-direction:row;justify-content:space-between;width:100%;'>
                            <span style='font-size: 14px;'><?= $mKey ?> <?php echo count($mValue) > 1 ?  "(Sesi #$sesi)" : ''; ?></span>
                            <?php $color = count($tValue) > $personilIdeal[$mKey] ? 'color:red' : ''; ?>
                            <span style='font-size: 12px;<?= $color ?>'>Personil Ideal: <?= $personilIdeal[$mKey] ?> Orang</span>
                        </div>
                    </th>
                </tr>
                <?php 
                    $sesi++;
                    $no = 1;
                    foreach ($tValue as $pKey => $personil) {
                ?>
                <tr>
                    <td style="<?= $style['td'] ?>;text-align:right;font-size:12px;" width="10%"><?= $no ?></td>
                    <td style="<?= $style['td'] ?>;" width="90%">
                        <span style='font-size:12px;'><?= $personil['name'] ?> 
                            <?php if($personil['status'] != 'CREATED' && $personil['status'] != 'PROCESS') { ?>
                                <span style='color:red'><?= $personil['status'] ?></span>
                            <?php } ?>
                        </span><br>
                        <span style='font-size:12px;'><?= $personil['sub_department'].' ('.$personil['division'].')' ?></span><br>
                        <span style='font-size:12px;'><?= $personil['overtime_hour'] ?></span><br>
                        <span style='font-size:12px;'>Tugas: <?= $personil['task'] ?></span><br>
                    </td>
                </tr>
            <?php $no++; } } } } ?>     
        </table>

        <?php if(count($dataNonMachine) > 0) { ?>
            <table style="<?= $style['table'] ?> margin-top:20px">
                <tr>
                    <th style="<?= $style['th'] ?>" colspan="2">Detail Personil Non Operator</th>
                </tr>
                <?php                
                    $no = 1; 
                    foreach ($dataNonMachine as $empTask => $empValue) { ?>
                    <tr>
                        <td style="<?= $style['td'] ?>;text-align:right;font-size:12px;" width="10%"><?= $no ?></td>
                        <td style="<?= $style['td'] ?>;" width="90%">
                            <span style='font-size:12px;'><?= $empValue['name'] ?> 
                                <?php if($empValue['status'] != 'CREATED' && $empValue['status'] != 'PROCESS') { ?>
                                    <span style='color:red'><?= $empValue['status'] ?></span>
                                <?php } ?>
                            </span><br>
                            <span style='font-size:12px;'><?= $empValue['sub_department'].' ('.$empValue['division'].')' ?></span><br>
                            <span style='font-size:12px;'><?= $empValue['overtime_hour'] ?></span><br>
                            <span style='font-size:12px;'>Tugas: <?= $empValue['task'] ?></span><br>
                        </td>
                    </tr>
                <?php $no++; } ?>
            </table>
        <?php } ?>
    </div>

    <div style="<?= $style['footer'] ?>">
        <p>Notifikasi email ini dikirim secara otomatis oleh sistem dan tidak memerlukan balasan</p>
        <hr>
        <p>Kw. Industri Pulo Gadung, Blok N6-11, Jl. Rw. Gelam V No.1, RW.9, Jatinegara, Kec. Cakung, Kota Jakarta Timur, Daerah Khusus Ibukota Jakarta 13920</p>
    </div>
</div>