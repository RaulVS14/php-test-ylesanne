<?php

// https://en.wikipedia.org/w/api.php?action=query&format=json&list=search&utf8=1&srsearch=Tartu&srlimit=500&sroffset=0

function getWikiData($offset = 0, $limit = 500, $search = "Tartu")
{
    $curl = curl_init();

    curl_setopt_array($curl, array(
        CURLOPT_URL => "https://en.wikipedia.org/w/api.php?action=query&format=json&list=search&utf8=1&srsearch=$search&srlimit=$limit&sroffset=$offset",
        CURLOPT_RETURNTRANSFER => true,
        CURLOPT_TIMEOUT => 30,
        CURLOPT_HTTP_VERSION => CURL_HTTP_VERSION_1_1,
        CURLOPT_CUSTOMREQUEST => "GET",
        CURLOPT_HTTPHEADER => array(
            "cache-control: no-cache"
        ),
    ));

    $response = curl_exec($curl);

    $err = curl_error($curl);

    curl_close($curl);
    header('Content-Type: application/json');
    echo $response;
}

getWikiData(4500, 500);

