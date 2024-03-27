<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <meta http-equiv="X-UA-Compatible" content="ie=edge">
    <title>Document</title>
</head>
<body>
    <form method="post" action="{{route('signup')}}" novalidate>
        @csrf
        <div>
        <label for="user">
            username    
        </label>
        <input type='text' placeholder="username" id="user" name="username" value="{{old('username')}}"/>
        @error('username')
            <p>{{$message}}</p>
        @enderror
    </div>

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
        <label for="passConf">
            Confirm password 
        </label>
        <input type='password' placeholder="Confirm password" id="passConf" name="password_confirmation" value="{{old('password_confirmation')}}"/>
        @error('password_confirmation')
            <p>{{$message}}</p>
        @enderror
    </div>

    <div>
        <label for="agree">
            I agree to terms 
        </label>
        <input type='checkbox' placeholder="Confirm password" id="agree" name="accept_terms"/>
        @error('accept_terms')
            <p>{{$message}}</p>
        @enderror
    </div>

        <button>Submit</button>
    </form>
</body>
</html>