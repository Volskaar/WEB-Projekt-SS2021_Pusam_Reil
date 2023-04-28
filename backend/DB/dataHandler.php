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
    
    /*returns array of all demo-appointments
    public function queryAppointments(){
        $result = array();
        foreach ($this->getDemoData() as $val) {
            array_push($result, $val);
        }
        return $result;
    }*/

    private static function getDemoData()
    {
        $demodata = [
            [new Appointment(1, "Appointment 1", "Office", "24.04.23", "A small description", 240, ["12:00", "12:30", "13:00", "14:00"], "active")],
            [new Appointment(2, "Appointment 2", "Office", "25.04.23", "A small description", 30, ["13:00", "13:30"], "inactive")],
            [new Appointment(3, "Appointment 3", "Office", "26.04.23", "A small description", 120, ["14:00", "14:30"], "active")],
            [new Appointment(4, "Appointment 4", "Office", "27.04.23", "A small description", 60, ["12:00", "13:30"], "inactive")],
            [new Appointment(4, "Appointment 5", "Office", "28.04.23", "A small description", 90, ["18:00", "19:30"], "inactive")],
        ];
        return $demodata;
    }
}
?>