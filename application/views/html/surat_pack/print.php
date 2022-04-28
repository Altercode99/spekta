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
    <br>

    <?php if($i > 10) { ?>
        <br><br>
    <?php } else if($i > 15) { ?>
        <br><br><br>
    <?php } else if($i > 20) { ?>
        <br><br><br><br>
    <?php } else if($i > 25) { ?>
        <br><br><br><br><br>
    <?php } else if($i > 30) { ?>
        <br><br><br><br><br><br>
    <?php } else if($i > 35) { ?>
        <br><br><br><br><br><br><br>
    <?php } else if($i > 40) { ?>
        <br><br><br><br><br><br><br><br>
    <?php } else if($i > 45) { ?>
        <br><br><br><br><br><br><br><br><br>
    <?php } else if($i > 50) { ?>
        <br><br><br><br><br><br><br><br><br><br>
    <?php } else if($i > 55) { ?>
        <br><br><br><br><br><br><br><br><br><br><br>
    <?php } else if($i > 60) { ?>
        <br><br><br><br><br><br><br><br><br><br><br><br>
    <?php } else if($i > 65) { ?>
        <br><br><br><br><br><br><br><br><br><br><br><br><br>
    <?php } else if($i > 70) { ?>
        <br><br><br><br><br><br><br><br><br><br><br><br><br><br>
    <?php } else if($i > 75) { ?>
        <br><br><br><br><br><br><br><br><br><br><br><br><br><br><br>
    <?php } else if($i > 80) { ?>
        <br><br><br><br><br><br><br><br><br><br><br><br><br><br><br><br>
    <?php } else if($i > 85) { ?>
        <br><br><br><br><br><br><br><br><br><br><br><br><br><br><br><br><br>
    <?php } else if($i > 90) { ?>
        <br><br><br><br><br><br><br><br><br><br><br><br><br><br><br><br><br><br>
    <?php } else if($i > 95) { ?>
        <br><br><br><br><br><br><br><br><br><br><br><br><br><br><br><br><br><br><br>
    <?php } else if($i > 100) { ?>
        <br><br><br><br><br><br><br><br><br><br><br><br><br><br><br><br><br><br><br><br>
    <?php } else if($i > 105) { ?>
        <br><br><br><br><br><br><br><br><br><br><br><br><br><br><br><br><br><br><br><br><br>
    <?php } else if($i > 110) { ?>
        <br><br><br><br><br><br><br><br><br><br><br><br><br><br><br><br><br><br><br><br><br><br>
    <?php } else if($i > 115) { ?>
        <br><br><br><br><br><br><br><br><br><br><br><br><br><br><br><br><br><br><br><br><br><br><br>
    <?php } else if($i > 120) { ?>
        <br><br><br><br><br><br><br><br><br><br><br><br><br><br><br><br><br><br><br><br><br><br><br><br>
    <?php } else if($i > 125) { ?>
        <br><br><br><br><br><br><br><br><br><br><br><br><br><br><br><br><br><br><br><br><br><br><br><br><br>
    <?php } else if($i > 130) { ?>
        <br><br><br><br><br><br><br><br><br><br><br><br><br><br><br><br><br><br><br><br><br><br><br><br><br><br>
    <?php } else if($i > 135) { ?>
        <br><br><br><br><br><br><br><br><br><br><br><br><br><br><br><br><br><br><br><br><br><br><br><br><br><br><br>
    <?php } else if($i > 140) { ?>
        <br><br><br><br><br><br><br><br><br><br><br><br><br><br><br><br><br><br><br><br><br><br><br><br><br><br><br><br>
    <?php } else if($i > 145) { ?>
        <br><br><br><br><br><br><br><br><br><br><br><br><br><br><br><br><br><br><br><br><br><br><br><br><br><br><br><br><br>
    <?php } else if($i > 150) { ?>
        <br><br><br><br><br><br><br><br><br><br><br><br><br><br><br><br><br><br><br><br><br><br><br><br><br><br><br><br><br><br>
    <?php } else if($i > 155) { ?>
        <br><br><br><br><br><br><br><br><br><br><br><br><br><br><br><br><br><br><br><br><br><br><br><br><br><br><br><br><br><br><br>
    <?php } else if($i > 1) { ?>
        <br><br>
    <?php } ?>


    <?php 
        if($i == 1) { 
            if($package_desc != $package_desc_ori) {
                $marginTop = '10px';
            } else {
                $marginTop = '95px';;
            }
            
        } else {
            if($package_desc != $package_desc_ori) {
                $marginTop = '20px';
            } else {
                $marginTop = '70px';
            }
        }
    ?>
    <div class="date_container">
        <p class="font"><?= $letter_date ?></p>
        <p class="font-bold"><?= ($i - 1) + $start_from ?></p>
    </div>

    <div class="body_letter">
        <div class="title font-spack"><?= $product_type ? $product_type : 'SURAT PACK' ?></div>
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
                    <td class="font">MFG. DATE</td>
                    <td class="font">:</td>
                    <td class="font"><b><?= $mfg_date ?></b></td>
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
            <table class="spack_table" style="margin-top:-20px;">
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

    <div class="spack_footer" style="margin-top: <?= $marginTop ?>;">
        <?php if($package_desc != $package_desc_ori) { ?>
            <div class="font">ECERAN</div>
        <?php } ?>
        <div class="font-bold"><?= ($i - 1) + $start_from ?></div>
        <div class="font">Tgl: <?= $footer_date ?></div>
        <div class="font"><?= $no_batch ?></div>
        <div class="font">MD: <?= $mfg_date ?></div>
        <div class="font">ED: <?= $exp_date ?></div>
        <div class="font" style="margin-top:25px"><?= $product_name ?></div>
        <div class="font"><?= $makloon != '' ? '** '.$makloon. ' **' : '** '.$product_type.' **' ?></div>
    </div>
<!-- 
    <div class="cpr">
      <p style="font-size:10px">-Hardware & Network-</p>
    </div> -->
</div>
<?php } ?>

<script>
    window.onload = function() { window.print(); }
</script>

<style>
    @media print {
        .spack_footer {
            page-break-after: always;
        }
    }

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
        margin-top:-15px;
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
        position: relative;
    }

    .cpr {
        text-align: left;
        width: 100%;
        position: relative;
    }
</style>