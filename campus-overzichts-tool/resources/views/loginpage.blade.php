<!DOCTYPE html>
<html lang="en">

<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Tech Campus - Login</title>
    @vite([
    'resources/css/loginpage.css',
    'resources/js/lib/loginpage.js',
    ])
</head>

<body>
    <div>
        <form>
            <div>
                <label for="">E-mail:</label>
                <input type="email" id="email">
                <label for="password">Password:</label>
                <input type="password" id="password">
            </div>
        </form>
        <button id="createAccount">Login</button>
        <p>Geen account? <Span><a href="{{ route('signup') }}">Ga naar Signup</a></Span></p>
    </div>
</body>

</html>