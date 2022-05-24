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
        /* padding: 40px 0; */
        background: #EBF0F5;
        margin: 10;
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
        height: 90%;
        width: 100%;
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

    .container {
        width: 100%;
        height: 100%;
        display: flex;
        flex-direction: row;
        justify-content: space-between;
        background-color: #fff;
        border: .5px solid #ddd;
    }

    .left-view {
        width: 70%;
    }

    .right-view {
        width: 30%;
        height: 100%;
        display: flex;
        flex-direction: column;
        justify-content: space-between;
    }


    .right-up {
        display: flex;
        align-items: center;
        justify-content: center;
        height: 50%;
        width: 100%;
        border-bottom: .5px solid #ddd;
    }

    .right-down {
        display: flex;
        align-items: center;
        justify-content: center;
        height: 50%;
        width: 100%;
    }

    .left-up {
        width: 100%;
        height: 65%;
        display: flex;
        flex-direction: row;
        justify-content: space-between;
    }

    .left-up-left {
        width: 50%;
        height: 100%;
    }

    .left-up-right {
        width: 50%;
        height: 100%;
    }

    .left-down {
        width: 100%;
        height: 35%;
        display: flex;
        flex-direction: row;
        justify-content: space-between;
        border-right: .5px solid #ddd;
        
    }

    .left-down-content {
        width: 100%;
        display: flex;
        align-items:center;
        justify-content:center;
    }

    .number {
        font-size: 50px;
    }

    /* Slideshow container */
    .slideshow-container {
        max-width: 1000px;
        position: relative;
        margin: auto;
    }

    /* Hide the images by default */
    .mySlides {
        display: none;
    }

    /* Next & previous buttons */
    .prev,
    .next {
        cursor: pointer;
        position: absolute;
        top: 50%;
        width: auto;
        margin-top: -22px;
        padding: 16px;
        color: white;
        font-weight: bold;
        font-size: 18px;
        transition: 0.6s ease;
        border-radius: 0 3px 3px 0;
        user-select: none;
    }

    /* Position the "next button" to the right */
    .next {
        right: 0;
        border-radius: 3px 0 0 3px;
    }

    /* On hover, add a black background color with a little bit see-through */
    .prev:hover,
    .next:hover {
        background-color: rgba(0, 0, 0, 0.8);
    }

    /* Caption text */
    .text {
        color: #f2f2f2;
        font-size: 15px;
        padding: 8px 12px;
        position: absolute;
        bottom: 0px;
        width: 94.5%;
        text-align: center;
        background: rgba(0, 0, 0, 0.3);
    }

    /* Number text (1/3 etc) */
    .numbertext {
        color: #f2f2f2;
        font-size: 12px;
        padding: 8px 12px;
        position: absolute;
        top: 0;
    }

    /* The dots/bullets/indicators */
    .dot {
        cursor: pointer;
        height: 15px;
        width: 15px;
        margin: 0 2px;
        background-color: #bbb;
        border-radius: 50%;
        display: inline-block;
        transition: background-color 0.6s ease;
    }

    .active,
    .dot:hover {
        background-color: #717171;
    }

    /* Fading animation */
    .fade {
        animation-name: fade;
        animation-duration: 1.5s;
    }

    @keyframes fade {
        from {
            opacity: .4
        }

        to {
            opacity: 1
        }
    }
</style>

<body>
    <div class="container">
        <div class="left-view">
            <div class="left-up">
                <div class="left-up-left">
                    <div class="qr-container">
                        <div id="qr_code">
                            <img src="<?= base_url("assets/qr_absen/$gate->gate.png") ?>" alt="<?= $gate->token ?>" />
                        </div>
                    </div>
                    <div id="progressBar">
                        <div class="bar"></div>
                    </div>
                </div>

                <div class="left-up-right">
                    <div class="slideshow-container">
                        <div class="mySlides fade">
                            <div class="numbertext">1 / 3</div>
                            <img src="https://source.unsplash.com/500x300?programming" style="width:100%" height="100%">
                            <div class="text">
                                Title <br>
                                Lorem ipsum dolor, sit amet consectetur adipisicing elit. Laudantium id voluptatibus corrupti culpa libero nemo architecto molestiae rem voluptatum eius iste ea odio ex nostrum blanditiis vero, debitis voluptas delectus! Praesentium facere dignissimos enim ex optio dicta adipisci eveniet sed.
                            </div>
                        </div>

                        <div class="mySlides fade">
                            <div class="numbertext">2 / 3</div>
                            <img src="https://source.unsplash.com/500x300?cofee" style="width:100%" height="100%">
                            <div class="text">
                                Title <br>
                                Lorem ipsum dolor sit amet consectetur adipisicing elit. Enim dolore voluptate ex, eligendi tempore omnis unde doloribus laboriosam vel quod neque eum, facilis nisi. Pariatur sapiente laboriosam provident excepturi, voluptatibus tempore distinctio, reprehenderit odit ipsum modi, libero eveniet quo eius.
                            </div>
                        </div>

                        <div class="mySlides fade">
                            <div class="numbertext">3 / 3</div>
                            <img src="https://source.unsplash.com/500x300?women" style="width:100%" height="100%">
                            <div class="text">
                                Title <br>
                                Lorem, ipsum dolor sit amet consectetur adipisicing elit. Ratione ducimus quo illum nobis, eaque expedita numquam recusandae harum. Quas culpa illo possimus voluptates doloremque cum, molestiae, amet laborum nesciunt inventore reiciendis iusto eos voluptatum laboriosam rem quo ea assumenda excepturi!
                            </div>
                        </div>

                        <a class="prev" onclick="plusSlides(-1)" style="left:0">&#10094;</a>
                        <a class="next" onclick="plusSlides(1)">&#10095;</a>
                    </div>
                    <br>
                </div>
            </div>

            <div class="left-down">
                <!-- <div class="left-down-content"><span class="number">1</span></div>
                <div class="left-down-content"><span class="number">2</span></div>
                <div class="left-down-content"><span class="number">3</span></div> -->
                <img src="https://asset-a.grid.id/crop/0x0:0x0/x/photo/2018/05/30/3491993923.jpg" style="width:50%;">
                <div style="text-align:center;width:50%;">
                    <h3>Bahaya Merokok bagi OS!</h3>
                    <p>Meroko dapat menyebabkan anda terkena <span style="font-weight:bold;font-style:italic;color:red;">Kangker, Jantung, Impotensi, Gangguan Kehamilan & Janin</span>, ingat Sakit potong gaji :).</p>
                </div>
            </div>
        </div>

        <div class="right-view">
            <div class="right-up"><span class="number">Kanan Atas</span></div>
            <div class="right-down"><span class="number">Kanan Bawah</span></div>
        </div>
    </div>
</body>

<script>
    var timer = 20;

    function progress(timeleft, timetotal, $element) {
        var progressBarWidth = timeleft * $element.width() / timetotal;
        $element.find('div').animate({
            width: progressBarWidth
        }, 500);
        if (timeleft > 0) {
            setTimeout(function () {
                progress(timeleft - 1, timetotal, $element);
            }, 1000);
        } else {
            reqJson("<?= base_url('index.php?d=absen&c=Absen&m=genQrCode') ?>", "POST", {
                gate: "<?= $gate->gate ?>"
            }, (err, res) => {
                if (res.status === "success") {
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

    let slideIndex = 0;
    showSlides();

    function showSlides() {
        let i;
        let slides = document.getElementsByClassName("mySlides");
        for (i = 0; i < slides.length; i++) {
            slides[i].style.display = "none";
        }
        slideIndex++;
        if (slideIndex > slides.length) {
            slideIndex = 1
        }
        slides[slideIndex - 1].style.display = "block";
        setTimeout(showSlides, 5000); // Change image every 2 seconds
    }
</script>

</html>