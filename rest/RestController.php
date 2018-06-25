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


        
    case "all":
        $plannedActivityRestHandler = new PlannedActivityRestHandler();
        $plannedActivityRestHandler -> allPlannedActivities($_GET["user"]);
       break;
        
    case "nLastValues":
        
        $var = $_GET["n"];
        $plannedActivityRestHandler = new PlannedActivityRestHandler();
        if (is_numeric($var) & $var >= 0){
        
        $plannedActivityRestHandler -> nLastValues($_GET["user"], $_GET["n"]);
        }
        else {
            $statusCode = 400;
            $rawData = array('msg' => 'Wrong value: must be number > 0', 'status' => 'ERROR');
            $plannedActivityRestHandler -> encode($statusCode,$rawData);

        }
        
        break;
        
    case "nNextValues":
        $var = $_GET["n"];
        $plannedActivityRestHandler = new PlannedActivityRestHandler();
        if (is_numeric($var) & $var >= 0){
        
        $plannedActivityRestHandler -> nNextValues($_GET["user"], $_GET["n"]);
        }
        else {
            $statusCode = 400;
            $rawData = array('msg' => 'Wrong value: must be number > 0', 'status' => 'ERROR');
            $plannedActivityRestHandler -> encode($statusCode,$rawData);
            //echo($statusCode);
            //echo($rawData);
        }
        break;
    
    case "valuesFromDateToNow":
        $var = $_GET["date"];
        $plannedActivityRestHandler = new PlannedActivityRestHandler();
        if(validateDate($var,'Y-m-d')){
        $plannedActivityRestHandler -> fromDateToNow($_GET["user"], $_GET["date"]);
        }
        else {
            $statusCode = 400;
            $rawData = array('msg' => 'Wrong date format: yyyy-MM-dd', 'status' => 'ERROR');
            $plannedActivityRestHandler -> encode($statusCode,$rawData);
            //echo($statusCode);
            //echo($rawData);
        }
        break;
        
    case "valuesBetweenDates":
         $var1 = $_GET["date1"];
         $var2 = $_GET["date2"];
        $plannedActivityRestHandler = new PlannedActivityRestHandler();
        if(validateDate($var1,'Y-m-d') && (validateDate($var2,'Y-m-d')) ){
        $plannedActivityRestHandler -> betweenDates($_GET["user"], $_GET["date1"], $_GET["date2"]);
        }
        else {
            $statusCode = 400;
            $rawData = array('msg' => 'Wrong date format: yyyy-MM-dd', 'status' => 'ERROR');
            $plannedActivityRestHandler -> encode($statusCode,$rawData);
        }
        break;
        
    case "valuesOnDate":
         $var = $_GET["date"];
        $plannedActivityRestHandler = new PlannedActivityRestHandler();
        if(validateDate($var,'Y-m-d')){
        $plannedActivityRestHandler -> onDate($_GET["user"], $_GET["date"]);
        }
        else {
            $statusCode = 400;
            $rawData = array('msg' => 'Wrong date format: yyyy-MM-dd', 'status' => 'ERROR');
            $plannedActivityRestHandler -> encode($statusCode,$rawData);
        }
        break;
        
    case "lastAccessToPlan":
        $plannedActivityRestHandler = new PlannedActivityRestHandler();
        $plannedActivityRestHandler->lastAccess($_GET["user"]);

	case "" :
		//404 - not found;
		break;
}

    function validateDate($date, $format){
    $d = DateTime::createFromFormat($format, $date);
        
    // The Y ( 4 digits year ) returns TRUE for any integer with any number of digits so changing the comparison from == to === fixes the issue.
    return $d && $d->format($format) === $date;
}




?>