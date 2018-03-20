<?php

require_once "wikipediaRetrievalScript.php";

// SEND ALL DATA FOR SAVING TO REMOTE API, MyAPI
function sendAllData($res)
{
    echo "<br>===================== SENDING DATA FOR SAVING ====================<br>";
    for ($i = 0; $i < count($res); $i++) {
        echo "Sending data to API - " . count($res[$i]) . " articles - ";
        $result = postData($res[$i]);
        $resultJSON = json_decode($result);
        echo "RECEIVED - " . $resultJSON->received_size. ": ";
        if ($resultJSON->success) {
            echo "SUCCESS! Articles saved " . $resultJSON->rows_updated;
        } else {
            echo "SAVING UNSUCCESSFUL!";
        }
        echo "<br>";
    }
}




// FUNCTION CALLS
$results = getAllArticles();
sendAllData($results);

