<?php 
    $style = [
        'room_container' => 'display:flex;flex-direction:row;justify-content:space-between;',
        'img_container' => 'display:flex;justify-content:center;width: 30%;padding:5px;',
        'img' => 'width:130px;height:130px;border-radius:10px;box-shadow: 5px 10px #888888;border:1px solid #ccc',
        'text_container' => 'width: 70%;',
        'td' => 'padding:2px;'
    ];
?>

<div style='<?= $style['room_container'] ?>'>
    <div style='<?= $style['img_container'] ?>'>
        <img src='#img_url#' style='<?= $style['img'] ?>' />
    </div>

    <div style='<?= $style['text_container'] ?>'>
        <table>
            <tr>
                <td style='<?= $style['td'] ?>'>Nama Ruangan</td>
                <td style='<?= $style['td'] ?>'>:</td>
                <td style='<?= $style['td'] ?>'>#name#</td>
            </tr>
            <tr>
                <td style='<?= $style['td'] ?>'>Kapasitas</td>
                <td style='<?= $style['td'] ?>'>:</td>
                <td style='<?= $style['td'] ?>'>#capacity#</td>
            </tr>
            <tr>
                <td style='<?= $style['td'] ?>'>Lokasi Gedung</td>
                <td style='<?= $style['td'] ?>'>:</td>
                <td style='<?= $style['td'] ?>'>#building#</td>
            </tr>
            <tr>
                <td style='<?= $style['td'] ?>'>Lokasi Lantai</td>
                <td style='<?= $style['td'] ?>'>:</td>
                <td style='<?= $style['td'] ?>'>#on_floor#</td>
            </tr>
        </table>
        <div>
            <p><b>Fasilitas</b></p>
            <p>#facility#</p>
        </div>
    </div>
</div>