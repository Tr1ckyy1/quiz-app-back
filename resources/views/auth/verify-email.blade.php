<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <meta http-equiv="X-UA-Compatible" content="ie=edge">
    <link rel="stylesheet" href="{{asset('css/app.css')}}">
    <title>Document</title>
</head>
<body>
    <div class='container'>
        <div class="content">

            <div class='img-container'>
                <img src="{{asset('images/quizwiz-email-logo.png')}}"/>
            </div>
            <h1>Verify your email address to get started</h1>
        </div>
        <p>Hi {{$user}},</p>
        <p>You're almost there! To complete your sign up, please verify your email address.</p>
        <a href="{{$url}}">Verify now</a>
    </div>
</body>
</html>