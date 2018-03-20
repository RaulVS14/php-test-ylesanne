<?php
require_once 'API.class.php';
require_once '../database/db.class.php';

class MyAPI extends API
{
    private $db;

    /*
     * MyAPI constructor
     */

    public function __construct($request, $origin)
    {
        parent::__construct($request);
        $this->db = new Database();
    }

    /*
     * ENDPOINT FOR getData
     */
    protected function getData()
    {
        if ($this->method == 'GET') {
            $this->db->query("SELECT * FROM wiki_search_results");
            return $this->db->resultSet();
        } else {
            return "Only accepts GET requests";
        }
    }

    /*
     * ENDPOINT FOR saveData
     */
    protected function saveData()
    {

        if ($this->method == 'POST') {
            $postData = file_get_contents("php://input");
            if (is_string($postData)) {
                $postResults = json_decode($postData, true);
                if (is_array($postResults)) {
                    return $this->databaseQueryMultiple('INSERT INTO wiki_search_results (title, snippet, changed_at, pageid) VALUES (:title, :snippet, :changed_at,:pageid) ON DUPLICATE KEY UPDATE title=:title, snippet=:snippet, changed_at=:changed_at, pageid=:pageid',
                        $postResults);


                } else {
                    return "Only accepts JSON format data";
                }
            }
            return "Only accepts JSON string";

        } else {
            return "Only accepts POST requests";
        }
    }

    /*
     * ENDPOINT FOR updateData
     */
    protected function updateData()
    {
        if ($this->method == 'POST') {
            $postData = file_get_contents("php://input");
            if (is_string($postData)) {
                $postResults = json_decode($postData, true);
                if (is_array($postResults)) {
                    return $this->databaseQueryMultiple('UPDATE wiki_search_results 
                                                    SET title = :title, snippet = :snippet, changed_at = :changed_at 
                                                    WHERE pageid=:pageid AND changed_at<:changed_at', $postResults);
                } else {
                    return "Only accepts JSON format data";
                }
            }
            return "Only accepts JSON string";

        } else {
            return "Only accepts POST requests";
        }
    }

    /*
     * Private function for handling updateData and saveData endpoint queries to database
     */

    private function databaseQueryMultiple($query = "", $array = [])
    {
        // Response array for query
        $queryResult = Array();
        $queryResult["rows_updated"] = 0;
        $queryResult["data_received"] = false;
        $queryResult["success"] = false;
        $queryResult["result"] = [];
        $queryResult["received_size"] = count($array);

        if (!empty($array)) {
            $queryResult["data_received"] = true;
            if (!empty($query)) {
                $rows = [];
                $this->db->beginTransaction();
                foreach ($array as $key => $postResult) {
                    $this->db->query($query);
                    $this->db->bind(':pageid', $postResult["pageid"]);
                    $this->db->bind(':title', $postResult["title"]);
                    $this->db->bind(':snippet', $postResult["snippet"]);
                    $this->db->bind(':changed_at', $postResult["timestamp"]);
                    $success = $this->db->execute();
                    if ($this->db->rowCount()) {
                        array_push($queryResult["result"], $success);
                        array_push($rows, $this->db->rowCount());
                    }
                }

                $this->db->endTransaction();
                $queryResult["rows_updated"] = count($rows);
                $queryResult["success"] = true;
            } else {
                $queryResult["success"] = false;
            }
        } else {
            $queryResult["success"] = false;
        }
        return $queryResult;
    }
}