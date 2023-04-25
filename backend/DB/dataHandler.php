<?php
include("./models/appointment.php");
class DataHandler
{
    //returns array of all appointments
    public function queryAppointments(){
        $result = array();
        foreach ($this->getDemoData() as $val) {
            array_push($result, $val);
        }
        return $result;
    }

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