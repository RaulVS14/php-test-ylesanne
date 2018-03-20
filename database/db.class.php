<?php
require_once 'config.php';

class Database
{
    /*
     * Variables applied from config.php
     */
    private $host = DB_HOST;
    private $user = DB_USER;
    private $pass = DB_PASS;
    private $name = DB_NAME;

    private $dbh;
    private $error;

    private $stmt;


    /*
     * Database constructor.
     */
    public function __construct()
    {
        // Set Database Source Name
        $dsn = 'mysql:host=' . $this->host . ';dbname=' . $this->name;
        $options = array(
            PDO::ATTR_PERSISTENT => true,
            PDO::ATTR_ERRMODE => PDO::ERRMODE_EXCEPTION);
        try {
            $this->dbh = new PDO($dsn, $this->user, $this->pass, $options);
        } catch (PDOException $e) {
            $this->error = $e->getMessage();
        }
    }

    /*
     * Function to prepare query for execution
     */
    public function query($query)
    {
        $this->stmt = $this->dbh->prepare($query);
    }

    /*
     * Function to bindParameters, and not letting data to be entered directly into query
     */
    public function bind($param, $value, $type = null)
    {
        if (is_null($type)) {
            switch (true) {
                case is_int($value):
                    $type = PDO::PARAM_INT;
                    break;
                case is_bool($value):
                    $type = PDO::PARAM_BOOL;
                    break;
                case is_null($value):
                    $type = PDO::PARAM_NULL;
                    break;
                default:
                    $type = PDO::PARAM_STR;
            }
        }
        $this->stmt->bindValue($param, $value, $type);
    }

    /*
     * FUNCTION FOR EXECUTING PREPARED QUERY
     */
    public function execute()
    {
        return $this->stmt->execute();
    }
    /*
     * FUNCTION TO RETRIEVE MULTIPLE ROWS OF DATA
     */
    public function resultSet()
    {
        $this->execute();
        return $this->stmt->fetchAll(PDO::FETCH_ASSOC);
    }

    /*
     * FUNCTION TO RETRIEVE SINGLE ROW OF DATA
     */
    public function single()
    {
        $this->execute();
        return $this->stmt->fetch(PDO::FETCH_ASSOC);
    }

    /*
     * FUNCTION TO GET COUNT OF ROWS CHANGED
     */
    public function rowCount()
    {
        return $this->stmt->rowCount();
    }

    /*
     * FUNCTION TO GET ID OF LAST INSERTED ROW
     */

    public function lastInsertId()
    {
        return $this->dbh->lastInsertId();
    }

    /*
     * FUNCTION TO START TRANSACTIONS FOR MULTIPLE QUERIES OF SAME KIND
     */
    public function beginTransaction()
    {
        $this->dbh->beginTransaction();
    }

    /*
     * FUNCTION TO END TRANSACTIONS
     */
    public function endTransaction()
    {
        return $this->dbh->commit();
    }

    /*
     * FUNCTION TO CANCEL TRANSACTION AND ROLLBACK CHANGES
     */

    public function cancelTransaction(){
        return $this->dbh->rollBack();
    }

    /*
     * FUNCTION TO DEBUG PARAMETERS
     */
    public function debugDumpParams()
    {
        return $this->stmt->debugDumpParams();
    }
}