<?php

include("./models/appointment.php");
class DataHandler
{
    //Mit Erklärung von Gobber Laurin
    private $servername;
    private $userAdmin;
    private $passwordAdmin;
    private $database;
    private $connection;
    
    function __construct(){
        $this->servername = "localhost";
        $this->usernameAdmin = "bif2webscriptinguser";
        $this->passwordAdmin = "bif2023";
        $this->database = "appointment_db";
    
        //create connection
        $this->connection = new MySQLi($this->servername, $this->usernameAdmin, $this->passwordAdmin, $this->database);
        if (!$this->connection){
            die('Could not connect: ' . mysql_error());
        }
    }

    //returns array of all db-appointments
    public function queryAppointments(){
        $query = "SELECT * FROM appointment";
        $stmnt = $this->connection->prepare($query);
        $stmnt->execute();
        $tmp = $stmnt->get_result()->fetch_all();

        return $tmp;
    }

    //returns all db-options for one appointment
    public function queryOptions($appID){
        $query = "SELECT * FROM options WHERE appointment_fk = '$appID'";
        $stmnt = $this->connection->prepare($query);
        $stmnt->execute();
        $tmp = $stmnt->get_result()->fetch_all();

        return $tmp;
    }

    public function createNewAppointment(array $data){
        $sql = "INSERT INTO appointment (title, place, create_date, cease_date, description, duration, active, created_by) VALUES (?, ?, ?, ?, ?, ?, ?, ?)";
        $stmnt = $this->connection->prepare($sql);
        $stmnt-> bind_param("sssssiis", $title, $place, $create_date, $cease_date, $description, $duration, $active, $created_by);

        $title = $data[0];
        $place = $data[1];
        $create_date = $data[2];
        $cease_date = $data[3];
        $description = $data[4];
        $duration = $data[5];
        $active = $data[6];
        $created_by = $data[7];

        $stmnt->execute();

        return $data;
    }

    public function createNewEntry(array $data){
        $sql = "INSERT INTO zugriff_options (app_id, option_id, user_id) VALUES (?, ?, ?)";
        $stmnt = $this->connection->prepare($sql);
        $stmnt-> bind_param("iii", $appID, $optionID, $userID);

        $appID = $data[0];
        $optionID = $data[1];
        $userID = $data[2];

        $stmnt->execute();

        return $data;
    }

    public function saveNewComment(array $data){
        $sql = "INSERT INTO comments (text, user_id, appointment_id) VALUES (?, ?, ?)";
        $stmnt = $this->connection->prepare($sql);
        $stmnt-> bind_param("sii", $text, $userID, $appID);

        $text = $data[0];
        $userID = $data[1];
        $appID = $data[2];

        $stmnt->execute();

        return $data;
    }
    


    /*returns array of all demo-appointments
    public function queryAppointments(){
        $result = array();
        foreach ($this->getDemoData() as $val) {
            array_push($result, $val);
        }
        return $result;
    }
    public function checkForUserExistence($username){
        $query = "SELECT user_id FROM user WHERE name = '$username'";
        $stmnt = $this->connection->prepare($query);
        $stmnt->execute();
        $result = $stmnt->get_result()->fetch_all();

        if(empty($result)){
            //neuen user anlegen
            $sql = "INSERT INTO user (name) VALUES (?)";
            $stmnt = $this->connection->prepare($sql);
            $stmnt-> bind_param("s", $name);

            $name = $username;

            $stmnt->execute();

            //id von neuem User holen
            $query = "SELECT user_id FROM user WHERE name = '$username'";
            $stmnt = $this->connection->prepare($query);
            $stmnt->execute();
            $result = $stmnt->get_result()->fetch_all();

            return $result;

        }
        else{
            return $result;
        }
    }
}
?>