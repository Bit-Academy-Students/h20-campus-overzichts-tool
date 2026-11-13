<!-- DIT IS ALLEMAAL TESTING -->

<?php

$sources = 
[
    "https://startupmap.iamsterdam.com/new-startups/f/all_regions/allof_Amsterdam%20region/launch_year_min/anyof_2024", 
    "https://www.dutchgamesindustry.nl/companies?sortBy=lastUpdated",
    "http://localhost:5555/index.php"
];

//$ch = curl_init();
//
//        curl_setopt($ch, CURLOPT_URL, $sources[0]);
//        curl_setopt($ch, CURLOPT_RETURNTRANSFER, true);
//
//$html = curl_exec($ch);
//var_dump($html);

//echo $html;

// Source - https://stackoverflow.com/a/13311209
// Posted by Linus Kleen, modified by community. See post 'Timeline' for change history
// Retrieved 2026-11-11, License - CC BY-SA 4.0

try {
    $ch = curl_init();

    // Check if initialization had gone wrong*    
    if ($ch === false) {
        throw new Exception('failed to initialize');
    }

    // Better to explicitly set URL
    curl_setopt($ch, CURLOPT_URL, $sources[0]);
    // That needs to be set; content will spill to STDOUT otherwise
    curl_setopt($ch, CURLOPT_RETURNTRANSFER, 1);
    // Set more options
    //curl_setopt(/* ... */);
    
    $content = curl_exec($ch);

    // Check the return value of curl_exec(), too
    if ($content === false) {
        throw new Exception(curl_error($ch), curl_errno($ch));
    }

    // Check HTTP return code, too; might be something else than 200
    $httpReturnCode = curl_getinfo($ch, CURLINFO_HTTP_CODE);

    /* Process $content here */
} catch (Exception $e) {
    trigger_error(
        sprintf(
            'Curl failed with error #%d: %s',
            $e->getCode(),
            $e->getMessage()
        ),
        E_USER_ERROR
    );
}
