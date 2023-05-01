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

    //Query Votings ~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~
    public function queryVotings($appointmentID){
        $sql = "SELECT user.name, options.time FROM user 
        JOIN zugriff_options ON user.user_id = zugriff_options.user_id 
        JOIN options ON options.options_id = zugriff_options.option_id
        WHERE zugriff_options.app_id = '$appointmentID'";

        $stmnt = $this->connection->prepare($sql);
        $stmnt->execute();
        $tmp = $stmnt->get_result()->fetch_all();

        if(empty($tmp)){
            $tmp[0] = "no previous votes";
        }
        
        return $tmp;
    }

    public function queryUser($userID){
        $query = "SELECT * FROM user WHERE user_id = '$userID'";
        $stmnt = $this->connection->prepare($query);
        $stmnt->execute();
        $tmp = $stmnt->get_result()->fetch_all();

        return $tmp;
    }

    public function queryUserOptions($optionID){
        $query = "SELECT * FROM options WHERE options_id = '$optionID'";
        $stmnt = $this->connection->prepare($query);
        $stmnt->execute();
        $tmp = $stmnt->get_result()->fetch_all();

        return $tmp;
    }

    public function queryComment($id){
        $sql = "SELECT user.name, comments.text FROM user
        JOIN comments ON comments.user_id = user.user_id 
        WHERE comments.appointment_id = '$id'";

        $stmnt = $this->connection->prepare($sql);
        $stmnt->execute();
        $tmp = $stmnt->get_result()->fetch_all();

        if(empty($tmp)){
            $tmp[0] = "no previous comments";
        }
        
        return $tmp;
    }

    //~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~

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

    //db insert
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

    //db insert 
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

    //insert options into db
    public function createNewOptions(array $data){
        //query for appointment id -> will always? be the latest entry
        $result = $this->connection->query("SELECT app_id FROM appointment ORDER BY app_id DESC LIMIT 1");
        $row = $result->fetch_object();
        $id = $row->app_id;

        //go through data array and make prepared statement for every entry
        //alles hier drunter funktioniert
        $length = count($data);
        $sql = "INSERT INTO options (time, appointment_fk) VALUES (?, ?)";

        for ($x = 0; $x < $length; $x++) {
            $stmnt = $this->connection->prepare($sql);
            $stmnt->bind_param("si", $time, $app_id);

            $time = $data[$x];
            $app_id = $id;

            $stmnt->execute();
        } 

        return $id;
    }

    public function getAppointmentDetails($id){
        $query = "SELECT * FROM appointment WHERE app_id = '$id'";
        $stmnt = $this->connection->prepare($query);
        $stmnt->execute();
        $result = $stmnt->get_result()->fetch_all();

        return $result;
    }
}
?>