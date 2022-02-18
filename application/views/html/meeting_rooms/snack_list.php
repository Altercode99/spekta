<?php foreach ($snacks as $snack) { ?>
<div class="snack_container <?= $rev->snack_id == $snack->id ? 'snack_selected' : null ?>" id="snack-<?= $snack->id ?>" onclick="selectSnack('<?= $snack->id ?>')">
    <div class="left">
        <img class="snack_img" src="<?= base_url('assets/images/meeting_snacks/' . $snack->filename) ?>" />
    </div>

    <div class="right">
        <table class="snack_table">
            <tr>
                <td>Nama Snack</td>
                <td>:</td>
                <td><?= $snack->name ?></td>
            </tr>
            <tr>
                <td>Harga</td>
                <td>:</td>
                <td>Rp. <?= toNumber($snack->price) ?></td>
            </tr>
        </table>
    </div>
</div>
<?php } ?>

<style>
    .snack_container {
        display: flex;
        flex-direction: row;
        justify-content: space-between;
        border: 0.5px solid #ddd;
        height: 60px;
        cursor: pointer;
    }

    .snack_img {
        width: 100%;
        height: 100%;
        border-top-right-radius: 10px;
        border-bottom-right-radius: 10px;
    }

    .left {
        width: 10%;
        height: 100%;
    }

    .right {
        width: 90%;
    }

    .snack_table tr td {
        font-family: sans-serif;
        padding: 5px;
    }

    .snack_selected {
        background: #ddd;
    }
</style>