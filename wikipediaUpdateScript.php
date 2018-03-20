<?php
require_once "wikipediaRetrievalScript.php";
// SEND ALL DATA FOR SAVING TO UPDATE API, MyAPI
function updateAllData($res)
{
    echo "<br>===================== SENDING DATA FOR UPDATE ====================<br>";
    for ($i = 0; $i < count($res); $i++) {
        echo "Sending data to API - " . count($res[$i]) . " articles - ";
        $result = postData($res[$i], "update");
        $resultJSON = json_decode($result);
        echo "- RECEIVED - " . $resultJSON->received_size." ";
        if ($resultJSON->success) {
            echo "SUCCESS! Articles updated " . $resultJSON->rows_updated;
        } else {
            echo "UPDATE UNSUCCESSFUL!";
        }
        echo "<br>";
    }
}
$results = getAllArticles();
updateAllData($results);