<?php

$results = [];

// RETRIEVE DATA FROM WIKIPEDIA API
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

    return json_decode($response);
}

// RETRIEVE ALL RESULTS FROM WIKIPEDIA API
function getAllArticles()
{
    echo "<br>===================== RETRIEVING DATA ====================<br>";
    $request = getWikiData();
    $results[0] = $request->query->search;

    $offset = $request->continue->sroffset;
    echo "Retrieved data ... " . count($results[0]) . " articles!";
    $resultSize = $request->query->searchinfo->totalhits;
    for ($i = 1; $i < floor($resultSize / 500) + 1; $i++) {
        echo "<br>";
        $value = getWikiData($offset);

        if (property_exists($value, "continue")) {
            $offset = $value->continue->sroffset;
        }
        $results[$i] = $value->query->search;
        echo "Retrieved data ... " . count($value->query->search) . " articles!";
    }
    echo "<br> Count of result pages " . count($results);
    return $results;
}

// SEND DATA TO REMOTE API, MyAPI
function postData($data, $type = "save")
{
    $sendCurl = curl_init();
    $payload = json_encode($data);
    curl_setopt($sendCurl, CURLOPT_URL, $_SERVER['SERVER_NAME'] . $_SERVER['REQUEST_URI'] . "api/" . $type . "Data");
    curl_setopt($sendCurl, CURLOPT_HTTPHEADER, array('Content-Type: application/json'));
    curl_setopt($sendCurl, CURLOPT_POST, 1);
    curl_setopt($sendCurl, CURLOPT_POSTFIELDS, $payload);
    curl_setopt($sendCurl, CURLOPT_RETURNTRANSFER, true);
    $response = curl_exec($sendCurl);
    curl_close($sendCurl);

    if ($response) {
        return $response;
    }
}

?>
<style>
	a {
		border: 1px solid black;
		padding: 15px;
		background: white;
		display: block;
		width: 175px;
		margin: 20px 0;
		text-decoration: none;
		color: #333;
		text-align: center;
	}

	a:hover {
		background: #c3c3c3;
		color: #fff;
		border-color: #c3c3c3;
	}
</style>
<a href="/php-test-ylesanne">&laquo; Back to saved results</a>
