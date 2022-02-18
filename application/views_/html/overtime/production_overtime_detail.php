<div style='padding:10px;display:flex;flex-direction:row;justify-content:space-between;width:100%;overflow:auto;height:250px'>
    <div style='width: 50%'>
        <table>
            <tr>
                <td style='padding: 5px;font-family:sans-serif'>Task ID</td>
                <td style='padding: 5px;font-family:sans-serif'>:</td>
                <td style='padding: 5px;font-family:sans-serif'><?= $overtime->task_id ?></td>
            </tr>
            <tr>
                <td style='padding: 5px;font-family:sans-serif'>Sub Unit</td>
                <td style='padding: 5px;font-family:sans-serif'>:</td>
                <td style='padding: 5px;font-family:sans-serif'><?= $overtime->department ?></td>
            </tr>
            <tr>
                <td style='padding: 5px;font-family:sans-serif'>Bagian</td>
                <td style='padding: 5px;font-family:sans-serif'>:</td>
                <td style='padding: 5px;font-family:sans-serif'><?= $overtime->sub_department ?></td>
            </tr>
            <tr>
                <td style='padding: 5px;font-family:sans-serif'>Sub Bagian</td>
                <td style='padding: 5px;font-family:sans-serif'>:</td>
                <td style='padding: 5px;font-family:sans-serif'><?= $overtime->division ?></td>
            </tr>
            <tr>
                <td style='padding: 5px;font-family:sans-serif'>Tanggal Lembur</td>
                <td style='padding: 5px;font-family:sans-serif'>:</td>
                <td style='padding: 5px;font-family:sans-serif'><?= toIndoDate($overtime->overtime_date) ?></td>
            </tr>
            <tr>
                <td style='padding: 5px;font-family:sans-serif'>Waktu Mulai</td>
                <td style='padding: 5px;font-family:sans-serif'>:</td>
                <td style='padding: 5px;font-family:sans-serif'><?= toIndoDateTime2($overtime->start_date) ?></td>
            </tr>
            <tr>
                <td style='padding: 5px;font-family:sans-serif'>Waktu Selesai</td>
                <td style='padding: 5px;font-family:sans-serif'>:</td>
                <td style='padding: 5px;font-family:sans-serif'><?= toIndoDateTime2($overtime->end_date) ?></td>
            </tr>
        </table>
    </div>

    <div style='width: 50%'>
        <table>
            <tr>
                <td style='padding: 5px;font-family:sans-serif'>Total Personil</td>
                <td style='padding: 5px;font-family:sans-serif'>:</td>
                <td style='padding: 5px;font-family:sans-serif'><?= $totalPersonil ?> Orang</td>
            </tr>
            <tr>
                <td style='padding: 5px;font-family:sans-serif;vertical-align:text-top;'>Mesin</td>
                <td style='padding: 5px;font-family:sans-serif;vertical-align:text-top;'>:</td>
                <td style='padding: 5px;font-family:sans-serif;vertical-align:text-top;'>
                <?php if(count($machines) == 0) { echo '-'; } else { foreach ($machines as $machine)  { ?>
                    <p><?= $machine->name ?></p>
                <?php } }?>
                </td>
            </tr>
        </table>
    </div>
</div>