<!DOCTYPE html>
<html lang="en">

<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Tech Campus - Dashboard</title>
    <link rel="icon" type="image/svg+xml" href="{{ asset('favicon.svg') }}">
    <link rel="icon" type="image/png" sizes="32x32" href="{{ asset('favicon-32x32.png') }}">
    <link rel="shortcut icon" href="{{ asset('favicon.ico') }}">
    <link rel="apple-touch-icon" href="{{ asset('apple-touch-icon.png') }}">
    @vite([
        'resources/css/homepage.css',
        'resources/js/lib/homepage.js',
        'resources/css/components/navbar.css',
        'resources/js/lib/navbar.js',
    ])
</head>

<body>
    <x-navbar />
    <div id="rowlayout">
        <div id="articlesearch">
            <div id="articleinputDiv">
                <input id="articleinput" type="text" placeholder="Zoek Artikels">
                <button id="articlesort">Name</button>
            </div>
            <div id="articlelist"></div>
            <div id="welcome">
                <p id="welcomemessage">Welkom Terug!</p>
                <p id="username">User</p>
            </div>
        </div>
        <div id="display">

        </div>
        <div id="companysearch">
            <input id="companyinput" type="text" placeholder="Zoek Bedrijven">
            <div id="companylist"></div>
        </div>
    </div>
</body>

</html>
