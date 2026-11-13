<!DOCTYPE html>
<html lang="en">

<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Tech Campus Dashboard</title>
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
            <input type="text" placeholder="Zoek Artikels">
            <div id="articlelist"></div>
            <div id="welcome">
                <p id="welcomemessage">Welkom Terug!</p>
                <p id="username">User</p>
            </div>
        </div>
        <div id="display">

        </div>
        <div id="companysearch">
            <input type="text" placeholder="Zoek Bedrijven">
            <div id="companylist"></div>
        </div>
    </div>
</body>

</html>
