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
        color: #dd8484;
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
        padding: 60px;
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
        background-image: url('./public/img/key.png');
        background-position: 10px 9px;
        background-repeat: no-repeat;
        background-size: 25px 25px;
        padding: 12px 20px 12px 40px;
        text-align: right;
    }

    .button {
        background: #e04f5f;
        border-radius: 999px;
        box-shadow: #e04f5f 0 10px 20px -10px;
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
            <img src='./public/img/key.png' />
        </div>
        <h1 style='font-family: sans-serif'>Verifikasi PIN</h1>

        <?php if(isset($_GET['action'])) { ?>
        <p style='font-family: sans-serif'>Action: 
        <?php 
            if($_GET['action'] == 'positive') {
                echo 'APPROVAL';
            }  else {
                echo 'REJECTION';
            }  
        ?>
        </p>
        <?php } ?>
        
        <div>
            <div class="form-control">
                <input type="password" id="pin" class="text-input" />
            </div>
            <div class="form-control">
                <a type="button" class="button" id="button">Submit</a>
            </div>
        </div>
    </div>
</body>

<script>
    $("#button").on("click", function() {
        reqJson(Public("verifyPin"), "POST", {token: '<?= $token ?>', pin: $("#pin").val()}, (err, res) => {
            if(res.status === "success") {
                window.location = res.url;
            } else {
                alert(res.message);
            }
        });
    });
</script>

</html>