<?php
include("db/dataHandler.php");

class SimpleLogic
{
    private $dh;
    function __construct()
    {
        //creates new datahandler object
        $this->dh = new DataHandler();
    }

    //result is datahandler function, depending on handed method
    //returns result
    function handleRequest($method)
    {
        switch ($method) {
            case "queryAppointments":
                $res = $this->dh->queryAppointments();
                break;
            default:
                $res = null;
                break;
        }
        return $res;
    }
}
?>