<?php
require_once 'API.class.php';
require_once 'db.class.php';

class MyAPI extends API
{
    private $db;

    public function __construct($request, $origin)
    {
        parent::__construct($request);
        $this->db = new Database();
    }

    protected function getData()
    {
        if ($this->method == 'GET') {
            $this->db->query("SELECT * FROM wiki_search_results");
            return $this->db->resultSet();
        } else {
            return "Only accepts GET requests";
        }
    }

    protected function saveData()
    {

        if ($this->method == 'POST') {
            if (is_string(file_get_contents("php://input"))) {
                $postResults = json_decode(file_get_contents("php://input"), true);
                if (is_array($postResults)) {
                    $rows=[];
                    $this->db->beginTransaction();
                    foreach ($postResults as $key=>$postResult) {

                        $this->db->query('INSERT IGNORE INTO wiki_search_results (title, snippet, changed_at, pageid) VALUES (:title, :snippet, :changed_at,:pageid) ON DUPLICATE KEY UPDATE id=id');
                        $this->db->bind(':pageid', $postResult["pageid"]);
                        $this->db->bind(':title', $postResult["title"]);
                        $this->db->bind(':snippet', $postResult["snippet"]);
                        $this->db->bind(':changed_at', $postResult["timestamp"]);
                        $this->db->execute();

                        if($this->db->rowCount()){
                            array_push($rows,$this->db->rowCount());
                        }
                    }

                    $this->db->endTransaction();
                    return ($rows) ? $rows : false;

                } else {
                    return "Only accepts JSON format data";
                }
            }
            return "Only accepts JSON string";

        } else {
            return "Only accepts POST requests";
        }
    }

    protected function updateData()
    {
        if ($this->method == 'POST') {
            if (is_string(file_get_contents("php://input"))) {
                $postResults = json_decode(file_get_contents("php://input"), true);

                if (is_array($postResults)) {
                    $rows=[];
                    $this->db->beginTransaction();
                    foreach ($postResults as $key=>$postResult) {
                        $this->db->query('UPDATE wiki_search_results 
                                                    SET title = :title, snippet = :snippet, changed_at = :changed_at 
                                                    WHERE pageid=:pageid AND changed_at<:changed_at');

                        $this->db->bind(':pageid', $postResult["pageid"]);
                        $this->db->bind(':title', $postResult["title"]);
                        $this->db->bind(':snippet', $postResult["snippet"]);
                        $this->db->bind(':changed_at', $postResult["timestamp"]);
                        $this->db->execute();
                        if ($this->db->rowCount()) {

                            array_push($rows, $this->db->rowCount());
                        }
                    }

                    $this->db->endTransaction();
                    return (count($rows)>0) ? $rows : false;

                } else {
                    return "Only accepts JSON format data";
                }
            }
            return "Only accepts JSON string";

        } else {
            return "Only accepts POST requests";
        }
    }
}