<!DOCTYPE html>
<html lang="en">

<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <meta name="csrf-token" content="{{ csrf_token() }}">
    <title>Tech Campus - Map Waterland</title>
    @vite([
    'resources/css/loginpage.css',
    'resources/js/lib/loginpage.js',
    ])
</head>

<body>
    <div id="info">
        <div id="filter">
            <div id="titlehead">
                <h1>Welcome back!</h1>
                <h2>Tech Campus Management Tool</h2>
                <p>Lorem ipsum dolor sit amet, consectetur adipisicing elit. Minus ex dolore ad eum pariatur tenetur, rem magnam nesciunt, nostrum praesentium ullam earum dicta quidem molestiae aspernatur possimus tempore veniam soluta amet! Dolores reiciendis repudiandae libero distinctio consectetur ut, a adipisci.</p>
                <div id="socials">
                    <a id="web" class="socialbtn" href="https://campus.purmerend.nl/" target="blank_">
                        <img src="{{ asset('icons/website.svg') }}" alt="website">
                    </a>
                    <a id="linkedIn" class="socialbtn" href="https://www.linkedin.com/company/e-sports-tech-campus/" target="blank_">
                        <img src="{{ asset('icons/linkedin.svg') }}" alt="linkedin">
                    </a>
                    <a id="instagram" class="socialbtn" href="https://www.instagram.com/esports_tech_campus_purmerend/" target="blank_">
                        <img src="{{ asset('icons/instagram.svg') }}" alt="insta">
                    </a>
                </div>
            </div>
        </div>
    </div>
    <div id="formdiv">
        <h1>Log in</h1>
        <form method="POST" action="/login" id="form">
            @csrf
            <div id="emailAlign">
                <div class="vertAlign">
                    <label for="email" autocomplete="true">E-Mail:</label>
                    <input type="email" name="email" id="email">
                    <p class="errorMessage" id="emailMessage" style="display: none;"></p>
                </div>
                <div class="vertAlign">
                    <label for="password">Password:</label>
                    <input type="password" name="password" id="password">
                    <p class="errorMessage" id="passwordMessage" style="display: none;"></p>
                </div>
            </div>
            <div>
                <label for="remember">Remember me:</label>
                <input type="checkbox" name="remember">
            </div>
            <button id="loginToAccount" type="button">Login</button>
            <p>Don't have an account? <span><a href="{{ route('signup') }}">Go to Signup</a></span></p>
        </form>
        <div id="trademark">
            <img src="{{ asset('icons/purmerendLogo.svg') }}" alt="purmerend" id="purmerend">
            <img src="{{ asset('images/ETC logo zwart.png') }}" alt="techCampus" id="techCampus">
        </div>
    </div>
</body>

</html>