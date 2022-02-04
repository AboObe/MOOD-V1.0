<!DOCTYPE html>
<html lang="{{ str_replace('_', '-', app()->getLocale()) }}">
    <head>
        <meta charset="utf-8">
        <meta name="viewport" content="width=device-width, initial-scale=1">

        <title>Laravel</title>

        <!-- Fonts -->
        <link href="https://fonts.googleapis.com/css?family=Nunito:200,600" rel="stylesheet">

        <!-- Styles -->
        <style>
            html, body {
                background-color: #fff;
                color: #636b6f;
                font-family: 'Nunito', sans-serif;
                font-weight: 200;
                height: 100vh;
                margin: 0;
            }

            .full-height {
                height: 100vh;
            }

            .flex-center {
                align-items: center;
                display: flex;
                justify-content: center;
            }

            .position-ref {
                position: relative;
            }

            .top-right {
                position: absolute;
                right: 10px;
                top: 18px;
            }

            .content {
                text-align: center;
            }

            .title {
                font-size: 84px;
            }

            .links > a {
                color: #636b6f;
                padding: 0 25px;
                font-size: 13px;
                font-weight: 600;
                letter-spacing: .1rem;
                text-decoration: none;
                text-transform: uppercase;
            }

            .m-b-md {
                margin-bottom: 30px;
            }
        </style>
    </head>
    <body>
        <div class="position-ref full-height" style="padding-left: 5%;padding-top: 1%;font-size: 25px;background-color: #52575b;color: white">
            <p style="font-weight: bold;margin-top:0px;">PACKAGES</p>
            Here are the subscription packages available for you!
            <div style="padding-left: 5%;padding-top: 0%;background-color: #FFFFFF;color: #52575b; margin-right:5%;border-radius:15px;">
                <p style="padding-top:20px;font-size: 35px;font-weight: bold;color:#DB4928;margin-bottom:0px;">30 MOOD</p>
                <p style="padding-bottom:10px;margin-top:0px;">Enjoy 30 usage days</p>
            </div>
            <div style="padding-left: 5%;padding-top: 0%;background-color: #FFFFFF;color: #52575b; margin-right:5%;border-radius:15px;">
                <p style="padding-top:20px;font-size: 35px;font-weight: bold;color:#DB4928;margin-bottom:0px;">Y - MOOD</p>
                <p style="padding-bottom:10px;margin-top:0px;">Enjoy one year subscription</p>
            </div>
            <p style="font-size: 30px;">You can still enjoy the <span style="font-weight: bold;color:#DB4928;text-decoration: underline;">FREE</span> usage of the app</p>       
        </div>   
    </body>
</html>
