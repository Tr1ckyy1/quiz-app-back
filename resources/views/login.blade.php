<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <meta http-equiv="X-UA-Compatible" content="ie=edge">
    <title>Document</title>
</head>
<body>
    <form method="post" action="{{route('login')}}" novalidate>
        @csrf
        

<div>
        <label for="email">
            email    
        </label>
        <input type='email' placeholder="email" id="email"  name="email" value="{{old('email')}}"/>
        @error('email')
            <p>{{$message}}</p>
        @enderror
    </div>

<div>
        <label for="pass">
            password    
        </label>
        <input type='password' placeholder="Password" id="pass"  name="password" value="{{old('password')}}"/>
        @error('password')
            <p>{{$message}}</p>
        @enderror
    </div>

    <div>
        <label for="remember">
            Remember for 30 days
        </label>
        <input type='checkbox' placeholder="Confirm password" id="remember" name="remember"/>
        @error('remember')
            <p>{{$message}}</p>
        @enderror
    </div>


        <button>Submit</button>
    </form>

    {{-- <form method="post" action='/aa'>
        @csrf
    <button>Log out </button></form> --}}
</body>
</html>