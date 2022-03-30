<?php $mb = 30; for ($i=1; $i <= $total_print; $i++) { ?>
<div class="spack_container" style="margin-bottom: <?= $mb ?>px;">
    <br>
    <br>
    <br>
    <br>
    <br>
    <br>
    <div class="date_container">
        <p class="font"><?= $letter_date ?></p>
        <p class="font-bold"><?= ($i - 1) + $start_from ?></p>
    </div>

    <div class="body_letter">
        <div class="title font-bold">SURAT PACK</div>
        <br />
        <div class="field">
            <table class="spack_table">
                <tr>
                    <td class="font">PRODUK</td>
                    <td class="font">:</td>
                    <td class="font"><?= $product_name ?></td>
                </tr>
                <tr>
                    <td class="font">KEMASAN</td>
                    <td class="font">:</td>
                    <td class="font"><?= $package_desc ?></td>
                </tr>
                <tr>
                    <td class="font">NO. BATCH</td>
                    <td class="font">:</td>
                    <td class="font"><?= $no_batch ?></td>
                </tr>
                <tr>
                    <td class="font">EXP. DATE</td>
                    <td class="font">:</td>
                    <td class="font"><?= $exp_date ?></td>
                </tr>
            </table>
            <?php if($package_desc != $package_desc_ori) { ?>
                <div class="title font-middle">** ETHICAL **</div>
                <div class="title font-middle">** ECERAN+LOS **</div>
            <?php } ?>
            <br />
            <table class="spack_table">
                <tr>
                    <td class="font">Dikemas Group</td>
                    <td class="font">:</td>
                    <td class="font"><?= $packing_by ?></td>
                </tr>
                <tr>
                    <td class="font">Supervisor</td>
                    <td class="font">:</td>
                    <td class="font"><?= $spv_by ?></td>
                </tr>
            </table>
        </div>
    </div>

    <div class="spack_footer">
        <?php if($package_desc != $package_desc_ori) { ?>
            <div class="font">ECERAN</div>
        <?php } ?>
        <div class="font-bold"><?= ($i - 1) + $start_from ?></div>
        <div class="font">Tgl: <?= $footer_date ?></div>
        <div class="font"><?= $no_batch ?></div>
        <div class="font">ED: <?= $exp_date ?></div>
        <br />
        <div class="font"><?= $product_name ?></div>
        <div class="font">** ETHICAL **</div>
    </div>
</div>
<?php } ?>

<script>
    window.onload = function() { window.print(); }
</script>

<style>
    .spack_container {
        position: relative;
        width: 95%;
        height: 650px;
    }

    .date_container {
        text-align: right;
    }

    .body_letter {
        width: 100%;
    }

    .title {
        width: 100%;
        text-align: center;
    }

    .font {
        font-size:12px;
    }

    .font-bold {
        font-weight:bold;
        font-size:14px;
    }

    .font-middle {
        font-size:18px;
        font-style: italic;
    }

    .spack_table td td {
        padding: 5px;
    }

    .spack_footer {
        text-align: right;
        width: 100%;
        position: absolute;
        bottom: 0;
        right: 0;
    }
</style>