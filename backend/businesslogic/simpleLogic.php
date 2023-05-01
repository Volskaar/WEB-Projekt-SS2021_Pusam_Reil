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
            case "createNewEntry":
                $res = $this->dh->createNewEntry($param);
                break;
            case "saveNewComment":
                $res = $this->dh->saveNewComment($param);
                break;
            case "checkForUserExistence":
                $res = $this->dh->checkForUserExistence($param);
                break;
            case "createNewOptions":
                $res = $this->dh->createNewOptions($param);
                break;
            case "queryVotings":
                $res = $this->dh->queryVotings($param);
                break;
            case "queryUser":
                $res = $this->dh->queryUser($param);
                break;
            case "queryOptions":
                $res = $this->dh->queryUserOptions($param);
                break;
            case "queryComment":
                $res = $this->dh->queryComment($param);
                break;
            default:
                $res = null;
                break;
        }
        return $res;
    }
}
?>