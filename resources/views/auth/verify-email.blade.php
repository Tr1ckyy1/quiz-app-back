<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <meta http-equiv="X-UA-Compatible" content="ie=edge">
    <title>Quizwiz API</title>
    <style>
        * {
            margin: 0;
            padding: 0;
            box-sizing: border-box;
        }
        .main-container {
            background-color: #f6f6f6;
            padding:4rem 0;
            width: 100%;
        }

        .container {
            width:fit-content;
            height:fit-content;
            margin:auto;
            padding:0 8px;
        }
        .img-container{
            width:fit-content;
            margin:0 auto;
        }
        .content {
            margin:0 auto;
        }
        .container h1 {
            margin-top:2rem;
            text-align: center;
        }

        .container p {
            margin: 1rem 0;
        }

        a {
            margin: 1rem auto;
            display: block;
            width: fit-content;
            background-color: #4b69fd;
            text-align: center;
            border-radius: 10px;
            padding: 16px 50px;
            font-size: 1.2rem;
            text-decoration: none;
            font-weight: bold;
            cursor: pointer;
            white-space: nowrap;
        }
    </style>
</head>
<body>
    <div class="main-container">
        <div class='container'>
            <div class="content">
                <div class='img-container'>
                    <img src="{{asset('quizwiz-email-logo.png')}}"/>
                </div>
                <h1 style="color:black">{{$headerText}}</h1>
            </div>
            <p style="color:black">Hi {{$user}},</p>
            <p style="color:black">{{$text}}</p>
            <a style="color:white" href="{{$url}}">{{$buttonText}}</a>
        </div>
    </div>
</body>
</html>