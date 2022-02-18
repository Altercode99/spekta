<?php 
    $style = [
        'container' => 'display:flex;flex-direction:row;justify-content:space-between;padding:10px;border:1px solid #ddd;cursor:pointer;background-image: linear-gradient(#ddd, #ccc);',
        'left' => 'display:flex;flex-direction:column;justify-content:sapce-between;',
        'right' => 'display:flex;flex-direction:column;justify-content:space-arround;',
        'text' => 'font-family:sans-serif;padding:2px;'
    ]
?>

<div class="main-container">
    <?php 
        foreach ($events as $event) { 
        $start = date('H:i', strtotime($event->start_date));
        $end = date('H:i', strtotime($event->end_date));
        $email = explode(',', $event->passenger);
    ?>
    <div style='<?= $style['container'] ?>' onclick='detailVehicleEventDate("<?= $event->id ?>")'>
        <div style='<?= $style['left'] ?>'>
            <table>
                <tr>
                    <td style='<?= $style['text'] ?>'>Tujuan</td>
                    <td style='<?= $style['text'] ?>'>:</td>
                    <td style='<?= $style['text'] ?>'><?= maxStringLength($event->destination, 43) ?></td>
                </tr>
                <tr>
                    <td style='<?= $style['text'] ?>'>PIC</td>
                    <td style='<?= $style['text'] ?>'>:</td>
                    <td style='<?= $style['text'] ?>'><?= $event->pic ?></td>
                </tr>
                <tr>
                    <td style='<?= $style['text'] ?>'>Driver</td>
                    <td style='<?= $style['text'] ?>'>:</td>
                    <td style='<?= $style['text'] ?>'><?= $event->driver ?></td>
                </tr>
                <tr>
                    <td style='<?= $style['text'] ?>'>Penumpang</td>
                    <td style='<?= $style['text'] ?>'>:</td>
                    <td style='<?= $style['text'] ?>'><?= count($email) ?> Orang</td>
                </tr>
            </table>
        </div>
        <div style='<?= $style['right'] ?>'>
            <span style='<?= $style['text'] ?>'><img src='./public/codebase/icons/clock.png' /> <?= $start.' - '.$end ?></span>
            <span style='<?= $style['text'] ?> text-align:right;'><?= $event->duration ?> Jam</span>
        </div>
    </div>
    <?php } ?>
</div>

<style>
    .main-container {
        width: 100%;
        height: 100%;
        overflowY: scroll;
        overflow: auto;
    }
</style>