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
    function handleRequest($method, $param)
    {
        switch ($method) {
            case "queryAppointments":
                $res = $this->dh->queryAppointments();
                break;
            case "queryOptions":
                $res = $this->dh->queryOptions($param);
                break;
            case "createNewAppointment":
                $res = $this->dh->createNewAppointment($param);
                break;
            case "submitData":
                $res = $this->dh->createNewEntry($param);
                break;
            case "submitData":
                $res = $this->dh->saveNewComment($param);
            case "checkForUserExistence":
                $res = $this->dh->checkForUserExistence($param);
                break;
            default:
                $res = null;
                break;
        }
        return $res;
    }
}
?>