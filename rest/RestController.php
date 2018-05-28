<?php
require_once("PlannedActivityRestHandler.php");
		
$view = "";
if(isset($_GET["view"]))
	$view = $_GET["view"];
/*
controls the RESTful services
URL mapping
*/
switch($view){

	//case "all":
		// to handle REST Url /mobile/list/
	//	$mobileRestHandler = new MobileRestHandler();
	//	$mobileRestHandler->getAllMobiles();
	//	break;
		
	//case "single":
		// to handle REST Url /mobile/show/<id>/
	//	$mobileRestHandler = new MobileRestHandler();
	//	$mobileRestHandler->getMobile($_GET["id"]);
	//	break;
        
    case "all":
        $plannedActivityRestHandler = new PlannedActivityRestHandler();
        $plannedActivityRestHandler -> allPlannedActivities($_GET["user"]);
       break;
        
    case "nLastValues":
        $plannedActivityRestHandler = new PlannedActivityRestHandler();
        $plannedActivityRestHandler -> nLastValues($_GET["user"], $_GET["n"]);
        break;
        
    case "nNextValues":
        $plannedActivityRestHandler = new PlannedActivityRestHandler();
        $plannedActivityRestHandler -> nNextValues($_GET["user"], $_GET["n"]);
        break;
    
    case "valuesFromDateToNow":
        $plannedActivityRestHandler = new PlannedActivityRestHandler();
        $plannedActivityRestHandler -> fromDateToNow($_GET["user"], $_GET["date"]);
        break;
        
    case "valuesBetweenDates":
        $plannedActivityRestHandler = new PlannedActivityRestHandler();
        $plannedActivityRestHandler -> betweenDates($_GET["user"], $_GET["date1"], $_GET["date2"]);
        break;
        
    case "valuesOnDate":
        $plannedActivityRestHandler = new PlannedActivityRestHandler();
        $plannedActivityRestHandler -> onDate($_GET["user"], $_GET["date"]);
        break;

	case "" :
		//404 - not found;
		break;
}






?>