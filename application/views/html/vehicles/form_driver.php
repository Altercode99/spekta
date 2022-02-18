<html>

<head>
    <link href="https://fonts.googleapis.com/css?family=Nunito+Sans:400,400i,700,900&display=swap" rel="stylesheet">
    <script type="text/javascript" src="<?= asset('js/jquery.min.js') ?>"></script>
    <script type="text/javascript" src="<?= asset('js/custom.js') ?>"></script>
</head>
<style>
    body {
        text-align: center;
        padding: 40px 0;
        background: #EBF0F5;
    }

    h1 {
        color: #116171;
        font-family: "Nunito Sans", "Helvetica Neue", sans-serif;
        font-weight: 900;
        font-size: 40px;
        margin-bottom: 10px;
    }

    p {
        color: #404F5E;
        font-family: "Nunito Sans", "Helvetica Neue", sans-serif;
        font-size: 20px;
        margin: 0;
    }

    .card {
        background: white;
        padding: 40px;
        border-radius: 4px;
        box-shadow: 0 2px 3px #C8D0D8;
        display: inline-block;
        margin: 0 auto;
    }

    .text-input {
        width: 100%;
        box-sizing: border-box;
        border: 2px solid #ccc;
        border-radius: 4px;
        font-size: 16px;
        background-color: white;
        background-image: url('./public/img/dashboard.png');
        background-position: 10px 9px;
        background-repeat: no-repeat;
        background-size: 25px 25px;
        padding: 12px 20px 12px 40px;
        text-align: right;
    }

    .button {
        background: #116171;
        border-radius: 999px;
        box-shadow: #116171 0 10px 20px -10px;
        box-sizing: border-box;
        color: #FFFFFF;
        cursor: pointer;
        font-family: sans-serif;
        font-size: 16px;
        font-weight: 700;
        line-height: 24px;
        opacity: 1;
        outline: 0 solid transparent;
        padding: 8px 18px;
        user-select: none;
        -webkit-user-select: none;
        touch-action: manipulation;
        width: fit-content;
        word-break: break-word;
        border: 0;
        text-decoration: none;
    }

    .form-control {
        margin-top: 20px;
    }

    @media only screen and (max-device-width: 480px) {
        body {
            -moz-transform: scale(2.5, 2.5);
            zoom: 250%;
        }
    }
</style>

<body>
    <div class="card">
        <div
            style="display:flex;align-items:center;justify-content:center;border-radius:200px; height:200px; width:200px; background: #F8FAF5; margin:0 auto;">
            <img src='<?= asset('img/dashboard.png') ?>' />
        </div>
        <h1 style='font-family: sans-serif'>Driver Form</h1>

        <table style="width:100%">
            <tr>
                <td>Tiket</td>
                <td>:</td>
                <td><?= $trip->ticket ?></td>
            </tr>
            <tr>
                <td>Tujuan</td>
                <td>:</td>
                <td><?= $trip->destination ?></td>
            </tr>
            <tr>
                <td>Penumpang</td>
                <td>:</td>
                <td><?= count(explode(',', $trip->passenger)) ?> Orang</td>
            </tr>
            <tr>
                <td>Kendaraan</td>
                <td>:</td>
                <td><?= $trip->vehicle_name ?></td>
            </tr>
            <tr>
                <td>No. Polisi</td>
                <td>:</td>
                <td><?= $trip->police_no ?></td>
            </tr>
            <tr>
                <td>Waktu Mulai</td>
                <td>:</td>
                <td><?= toIndoDateTime2($trip->start_date) ?></td>
            </tr>
            <tr>
                <td>Waktu Selesai</td>
                <td>:</td>
                <td><?= toIndoDateTime2($trip->end_date) ?></td>
            </tr>
            <tr>
                <td>Durasi</td>
                <td>:</td>
                <td><?= $trip->duration ?></td>
            </tr>
        </table>

        <div>
            <div class="form-control">
                <input type="number" id="start_km" class="text-input" placeholder="Kilomter Awal" value="<?= $trip->start_km ?>" />
            </div>
            <div class="form-control">
                <input type="number" id="end_km" class="text-input" placeholder="Kilomter Akhir" value="<?= $trip->end_km?>" />
            </div>
            <div class="form-control">
                <a type="button" class="button" id="button">Submit</a>
            </div>
        </div>
    </div>
</body>

<script>
    $("#button").on("click", function() {
        let start_km = $("#start_km").val();
        let end_km = $("#end_km").val();
        if(start_km == '' || end_km == '') {
            return alert("Form tidak boleh kosong!");
        }

        reqJson(Public("updateKilometer"), "POST", {
            id: "<?= $trip->ticket ?>",
            start_km,
            end_km
        }, (err, res) => {
            if(res.status === "success") {
                alert(res.message);
            } else {
                alert(res.message);
            }
        });
    });
</script>

<style>
    table tr td {
        font-family: sans-serif;
    }
</style>

</html>