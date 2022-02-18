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
                <td style='<?= $style['td'] ?>'>Nama Kendaraan</td>
                <td style='<?= $style['td'] ?>'>:</td>
                <td style='<?= $style['td'] ?>'>#name#</td>
            </tr>
            <tr>
                <td style='<?= $style['td'] ?>'>Nomor Polisi</td>
                <td style='<?= $style['td'] ?>'>:</td>
                <td style='<?= $style['td'] ?>'>#police_no#</td>
            </tr>
            <tr>
                <td style='<?= $style['td'] ?>'>Merek</td>
                <td style='<?= $style['td'] ?>'>:</td>
                <td style='<?= $style['td'] ?>'>#brand#</td>
            </tr>
            <tr>
                <td style='<?= $style['td'] ?>'>Tipe</td>
                <td style='<?= $style['td'] ?>'>:</td>
                <td style='<?= $style['td'] ?>'>#type#</td>
            </tr>
            <tr>
                <td style='<?= $style['td'] ?>'>Kapasitas Mesin</td>
                <td style='<?= $style['td'] ?>'>:</td>
                <td style='<?= $style['td'] ?>'>#machine_capacity# CC</td>
            </tr>
            <tr>
                <td style='<?= $style['td'] ?>'>Kilometer Terakhir</td>
                <td style='<?= $style['td'] ?>'>:</td>
                <td style='<?= $style['td'] ?>'>#last_km# KM</td>
            </tr>
            <tr>
                <td style='<?= $style['td'] ?>'>Kapasitas Penumpang</td>
                <td style='<?= $style['td'] ?>'>:</td>
                <td style='<?= $style['td'] ?>'>#passenger_capacity# Orang</td>
            </tr>
        </table>
    </div>
</div>