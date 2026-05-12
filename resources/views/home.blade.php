<!doctype html>
<html lang="it">
<head>
    <meta charset="utf-8">
    <title>Boudoir31 Consensi</title>

    <meta name="viewport" content="width=device-width, initial-scale=1">

    <link rel="stylesheet"
          href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.3/dist/css/bootstrap.min.css">

    <style>

        body{
            margin:0;
            min-height:100vh;

            background:
                linear-gradient(
                    rgba(255,248,245,.88),
                    rgba(255,248,245,.92)
                ),
                url('/images/bg.jpg');

            background-size:cover;
            background-position:center;

            font-family: "Helvetica Neue", sans-serif;
            color:#4b3b38;
        }

        .overlay{
            min-height:100vh;
            display:flex;
            align-items:center;
            justify-content:center;
            padding:40px 20px;
        }

        .main-box{
            width:100%;
            max-width:900px;

            background:rgba(255,255,255,.78);

            backdrop-filter: blur(10px);

            border-radius:28px;

            padding:40px;

            box-shadow:
                0 10px 40px rgba(0,0,0,.10);
        }

        .logo{
            text-align:center;
            margin-bottom:15px;
        }

        .logo img{
            max-width:180px;
        }

        h1{
            text-align:center;
            font-weight:300;
            letter-spacing:2px;
            margin-bottom:10px;
        }

        .subtitle{
            text-align:center;
            color:#8c6d67;
            margin-bottom:40px;
        }

        .card-consenso{
            display:block;

            text-decoration:none;

            background:#fff;

            border-radius:22px;

            padding:35px 25px;

            text-align:center;

            transition:.25s;

            border:1px solid rgba(188,148,138,.18);

            color:#4b3b38;

            box-shadow:
                0 3px 12px rgba(0,0,0,.05);
        }

        .card-consenso:hover{

            transform:translateY(-4px);

            box-shadow:
                0 10px 30px rgba(188,148,138,.20);

            border-color:#c8a59c;
        }

        .num{
            font-size:42px;
            font-weight:200;
            color:#c69b91;
            line-height:1;
        }

        .titolo{
            margin-top:15px;
            font-size:24px;
            font-weight:500;
        }

        .desc{
            margin-top:10px;
            font-size:14px;
            color:#7a6965;
        }

        @media(max-width:768px){

            .main-box{
                padding:25px;
            }

            .titolo{
                font-size:20px;
            }
        }

    </style>
</head>

<body>

<div class="overlay">

    <div class="main-box">

        <div class="logo">

            <!-- eventualmente logo -->
            <!-- <img src="/images/logo.png"> -->

        </div>

        <h1>BOUDOIR31</h1>

        <div class="subtitle">
            Centro di Dermopigmentazione Avanzata
        </div>

        <div class="row g-4">

            <div class="col-md-6">

                <a class="card-consenso"
                   href="/consenso/prima_seduta">

                    <div class="num">01</div>

                    <div class="titolo">
                        Prima Seduta
                    </div>

                    <div class="desc">
                        Consenso informato iniziale
                    </div>

                </a>

            </div>

            <div class="col-md-6">

                <a class="card-consenso"
                   href="/consenso/completamento">

                    <div class="num">02</div>

                    <div class="titolo">
                        Completamento
                    </div>

                    <div class="desc">
                        Seduta di completamento
                    </div>

                </a>

            </div>

            <div class="col-md-6">

                <a class="card-consenso"
                   href="/consenso/preesistente">

                    <div class="num">03</div>

                    <div class="titolo">
                        Lavoro Preesistente
                    </div>

                    <div class="desc">
                        Valutazione lavoro esistente
                    </div>

                </a>

            </div>

            <div class="col-md-6">

                <a class="card-consenso"
                   href="/consenso/modella">

                    <div class="num">04</div>

                    <div class="titolo">
                        Modella
                    </div>

                    <div class="desc">
                        Consenso trattamento modella
                    </div>

                </a>

            </div>

        </div>

    </div>

</div>

</body>
</html>