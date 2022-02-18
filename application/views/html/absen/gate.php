<html>

<head>
    <title>S.P.E.K.T.A QR <?= $gate->gate_name ?></title>
    <link href="https://fonts.googleapis.com/css?family=Nunito+Sans:400,400i,700,900&display=swap" rel="stylesheet">
    <link rel="icon" href="<?= asset("img/spekta.png") ?>" type="image/x-icon" />
    <script src="<?= asset('js/jquery.min.js') ?>"></script>
    <script src="<?= asset('js/custom.js') ?>"></script>
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

    i {
        color: #9ABC66;
        font-size: 100px;
        line-height: 200px;
        margin-left: -15px;
    }

    .card {
        background: white;
        padding: 60px;
        border-radius: 4px;
        box-shadow: 0 2px 3px #C8D0D8;
        display: inline-block;
        margin: 0 auto;
    }

    .qr-container {
        display: flex;
        flex-direction: column;
        justify-content: center;
        align-items: center;
        height: 400px;
        width: 400px;
        background: #F8FAF5;
        margin: 0 auto;
    }

    #progressBar {
        width: 90%;
        margin: 10px auto;
        height: 22px;
        background-color: #ccc;
    }

    #progressBar div {
        height: 100%;
        text-align: right;
        padding: 0 10px;
        line-height: 22px;
        width: 0;
        background-color: #116171;
        box-sizing: border-box;
    }

    a.solink {
        position: fixed;
        top: 0;
        width: 100%;
        text-align: center;
        background: #f3f5f6;
        color: #cfd6d9;
        border: 1px solid #cfd6d9;
        line-height: 30px;
        text-decoration: none;
        transition: all .3s;
        z-index: 999
    }

    a.solink::first-letter {
        text-transform: capitalize
    }

    a.solink:hover {
        color: #428bca
    }
</style>

<body>
    <div class="card">
        <div class="qr-container">
            <div id="qr_code">
                <img src="<?= base_url("assets/qr_absen/$gate->gate.png") ?>" alt="<?= $gate->token ?>" />
            </div>
        </div>
        <div id="progressBar">
            <div class="bar"></div>
        </div>
    </div>
</body>

<script>
var timer = 20;
function progress(timeleft, timetotal, $element) {
    var progressBarWidth = timeleft * $element.width() / timetotal;
    $element.find('div').animate({ width: progressBarWidth }, 500);
    if(timeleft > 0) {
        setTimeout(function() {
            progress(timeleft - 1, timetotal, $element);
        }, 1000);
    } else {
        reqJson("<?= base_url('index.php?d=absen&c=Absen&m=genQrCode') ?>", "POST", {gate: "<?= $gate->gate ?>"}, (err, res) => {
            if(res.status === "success") {
                $("#qr_code").html(res.newQR);
                progress(timer, timer, $('#progressBar'));
            } else {
                alert(res.message);
                setTimeout(() => {
                    location.reload();
                }, 1000);
            }
        });
    }
};

progress(timer, timer, $('#progressBar'));

</script>
</html>