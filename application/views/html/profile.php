<?php 
    $style = [
        'container' => 'padding: 10px;width:100%;display:flex;fle-direction:row;justify-content:space-between',
        'td' => 'padding: 5px;font-family:sans-serif'
    ];
?>

<div style='<?= $style['container'] ?>'>
    <table style="width:50%">
        <tr>
            <td style="<?= $style['td'] ?>">Nama Karyawan</td>
            <td style="<?= $style['td'] ?>">:</td>
            <td style="<?= $style['td'] ?>"><?= $emp->employee_name ?></td>
        </tr>
        <tr>
            <td style="<?= $style['td'] ?>">NPP</td>
            <td style="<?= $style['td'] ?>">:</td>
            <td style="<?= $style['td'] ?>"><?= $emp->nip ?></td>
        </tr>
        <tr>
            <td style="<?= $style['td'] ?>">ID SAP</td>
            <td style="<?= $style['td'] ?>">:</td>
            <td style="<?= $style['td'] ?>"><?= $emp->sap_id ?></td>
        </tr>
        <tr>
            <td style="<?= $style['td'] ?>">No. Kartu Keluarga</td>
            <td style="<?= $style['td'] ?>">:</td>
            <td style="<?= $style['td'] ?>"><?= $emp->parent_nik ?></td>
        </tr>
        <tr>
            <td style="<?= $style['td'] ?>">No. KTP</td>
            <td style="<?= $style['td'] ?>">:</td>
            <td style="<?= $style['td'] ?>"><?= $emp->nik ?></td>
        </tr>
        <tr>
            <td style="<?= $style['td'] ?>">Tempat, Tgl Lahir</td>
            <td style="<?= $style['td'] ?>">:</td>
            <td style="<?= $style['td'] ?>"><?= $emp->birth_place . ', ' .toIndoDate($emp->birth_date) ?></td>
        </tr>
        <tr>
            <td style="<?= $style['td'] ?>">Jenis Kelamin</td>
            <td style="<?= $style['td'] ?>">:</td>
            <td style="<?= $style['td'] ?>"><?= $emp->gender ?></td>
        </tr>
        <tr>
            <td style="<?= $style['td'] ?>">Agama</td>
            <td style="<?= $style['td'] ?>">:</td>
            <td style="<?= $style['td'] ?>"><?= $emp->religion ?></td>
        </tr>
        <tr>
            <td style="<?= $style['td'] ?>">Alamat</td>
            <td style="<?= $style['td'] ?>">:</td>
            <td style="<?= $style['td'] ?>"><?= $emp->address ?></td>
        </tr>
        <tr>
            <td style="<?= $style['td'] ?>">No. TLP</td>
            <td style="<?= $style['td'] ?>">:</td>
            <td style="<?= $style['td'] ?>"><?= $emp->phone ?></td>
        </tr>
        <tr>
            <td style="<?= $style['td'] ?>">No. HP</td>
            <td style="<?= $style['td'] ?>">:</td>
            <td style="<?= $style['td'] ?>"><?= $emp->mobile ?></td>
        </tr>
        <tr>
            <td style="<?= $style['td'] ?>">Email</td>
            <td style="<?= $style['td'] ?>">:</td>
            <td style="<?= $style['td'] ?>"><?= $emp->email ?></td>
        </tr>
    </table>

    <table style="width:50%">
    <tr>
            <td style="<?= $style['td'] ?>">Status Karyawan</td>
            <td style="<?= $style['td'] ?>">:</td>
            <td style="<?= $style['td'] ?>"><?= $emp->employee_status ?></td>
        </tr>
        <tr>
            <td style="<?= $style['td'] ?>">OS Name</td>
            <td style="<?= $style['td'] ?>">:</td>
            <td style="<?= $style['td'] ?>"><?= $emp->os_name ? $emp->os_name : '-' ?></td>
        </tr>
        <tr>
            <td style="<?= $style['td'] ?>">Nomor SK</td>
            <td style="<?= $style['td'] ?>">:</td>
            <td style="<?= $style['td'] ?>"><?= $emp->sk_number ?></td>
        </tr>
        <tr>
            <td style="<?= $style['td'] ?>">Tanggal Berlaku</td>
            <td style="<?= $style['td'] ?>">:</td>
            <td style="<?= $style['td'] ?>"><?= toIndoDate($emp->sk_start_date) ?></td>
        </tr>
        <tr>
            <td style="<?= $style['td'] ?>">Tanggal Berakhir</td>
            <td style="<?= $style['td'] ?>">:</td>
            <td style="<?= $style['td'] ?>"><?= $emp->sk_end_date !== '0000-00-00' || $emp->sk_end_date !== '' ? toIndoDate($emp->sk_end_date) : '-' ?></td>
        </tr>
        <tr>
            <td style="<?= $style['td'] ?>">Tempat, Tgl Lahir</td>
            <td style="<?= $style['td'] ?>">:</td>
            <td style="<?= $style['td'] ?>"><?= $emp->birth_place . ', ' .toIndoDate($emp->birth_date) ?></td>
        </tr>
        <tr>
            <td style="<?= $style['td'] ?>">Sub Unit</td>
            <td style="<?= $style['td'] ?>">:</td>
            <td style="<?= $style['td'] ?>"><?= $emp->dept_name ?></td>
        </tr>
        <tr>
            <td style="<?= $style['td'] ?>">Bagian</td>
            <td style="<?= $style['td'] ?>">:</td>
            <td style="<?= $style['td'] ?>"><?= $emp->sub_name ?></td>
        </tr>
        <tr>
            <td style="<?= $style['td'] ?>">Sub Bagian</td>
            <td style="<?= $style['td'] ?>">:</td>
            <td style="<?= $style['td'] ?>"><?= $emp->division_name ?></td>
        </tr>
        <tr>
            <td style="<?= $style['td'] ?>">Jabatan</td>
            <td style="<?= $style['td'] ?>">:</td>
            <td style="<?= $style['td'] ?>"><?= $emp->rank_name ?></td>
        </tr>
        <tr>
            <td style="<?= $style['td'] ?>">Atasan Langsung</td>
            <td style="<?= $style['td'] ?>">:</td>
            <td style="<?= $style['td'] ?>"><?= $spv ?></td>
        </tr>
        <tr>
            <td style="<?= $style['td'] ?>">Username</td>
            <td style="<?= $style['td'] ?>">:</td>
            <td style="<?= $style['td'] ?>"><?= $user->username ?></td>
        </tr>
    </table>
</div>