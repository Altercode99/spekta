<?php $mb = 30; for ($i=1; $i <= $total_print; $i++) { ?>
<div class="spack_container" style="margin-bottom: <?= $mb ?>px;">
    <br>
    <br>
    <br>
    <br>
    <br>
    <br>
    <br>
    <br>
    <?php if($i > 1) { ?>
        <br>
    <?php } ?>
    <div class="date_container">
        <p class="font"><?= $letter_date ?></p>
        <p class="font-bold"><?= ($i - 1) + $start_from ?></p>
    </div>

    <div class="body_letter">
        <div class="title font-spack"><?= $product_type ?></div>
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
                    <td class="font"><b><?= $no_batch ?></b></td>
                </tr>
                <tr>
                    <td class="font">EXP. DATE</td>
                    <td class="font">:</td>
                    <td class="font"><b><?= $exp_date ?></b></td>
                </tr>
            </table>
            <?php if($package_desc != $package_desc_ori) { ?>
                <div class="title font-middle">** <?= $product_type ?> **</div>
                <div class="title font-middle">** ECERAN+LOS **</div>
            <?php } ?>
            <br />
            <table class="spack_table">
                <tr>
                    <td class="font">Dikemas Group</td>
                    <td class="font">:</td>
                    <td class="font"><?= ucwords(strtolower($packing_by)) ?></td>
                </tr>
                <tr>
                    <td class="font">Supervisor</td>
                    <td class="font">:</td>
                    <td class="font"><?= ucwords(strtolower($spv_by)) ?></td>
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
        <div class="font">** <?= $makloon != '' ? $makloon : $product_type ?> **</div>
    </div>

    <div class="cpr">
      <p style="font-size:10px">-Hardware & Network-</p>
    </div>
</div>
<?php } ?>

<script>
    window.onload = function() { window.print(); }
</script>

<style>
    .spack_container {
        position: relative;
        width: 98%;
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
        font-size:13px;
        font-family: "Times New Roman", Times, serif;
    }

    .font-bold {
        font-weight:bold;
        font-size:16px;
        font-family: "Times New Roman", Times, serif;
    }

    .font-middle {
        font-size:18px;
        font-style: italic;
        font-family: "Times New Roman", Times, serif;
    }

    .font-spack {
        font-size:20px;
        font-family: "Times New Roman", Times, serif;
    }

    .spack_table td td {
        padding: 5px;
    }

    .spack_footer {
        text-align: right;
        width: 100%;
        position: absolute;
        bottom: 20;
        right: 0;
    }

    .cpr {
        text-align: left;
        width: 100%;
        position: absolute;
        bottom: 20;
        left: 0;
    }
</style>