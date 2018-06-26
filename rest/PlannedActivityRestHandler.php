<?php
require_once("SimpleRest.php");
require_once("PlannedActivity.php");
		
class PlannedActivityRestHandler extends SimpleRest {

	
	
	public function encodeHtml($responseData) {
	
		$htmlResponse = "<table border='1'>";
		foreach($responseData as $key=>$value) {
    			$htmlResponse .= "<tr><td>". $key. "</td><td>". $value. "</td></tr>";
		}
		$htmlResponse .= "</table>";
		return $htmlResponse;		
	}
	
	public function encodeJson($responseData) {
		$jsonResponse = json_encode($responseData);
		return $jsonResponse;		
	}
	
	public function encodeXml($responseData) {
		// creating object of SimpleXMLElement
		$xml = new SimpleXMLElement('<?xml version="1.0"?><mobile></mobile>');
		foreach($responseData as $key=>$value) {
			$xml->addChild($key, $value);
		}
		return $xml->asXML();
	}
	
    
   
    
	public function allPlannedActivities($user) {

		$plannedActivity = new PlannedActivity();
		$rawData = $plannedActivity->getPlannedActivities($user);

		if(empty($rawData)) {
			$statusCode = 200;
			$rawData = array('msg' => 'No planned activities for the specified date or user', 'status' => 'OK');	
		} else {
			$statusCode = 200;
            $rawData = array('listPlannedActivity' => $rawData,'msg' => 'History retrieved', 'status' => 'OK') ;

		}

		 $this->encode($statusCode,$rawData);
		
	}
    
    
    public function nLastValues($user, $n) {

		$plannedActivity = new PlannedActivity();
		$rawData = $plannedActivity->getPANLastValues($user, $n);

		if(empty($rawData)) {
			$statusCode = 200;
			$rawData = array('msg' => 'No planned activities for the specified date or user', 'status' => 'OK');		
		} else {
			$statusCode = 200;
            $rawData = array('listPlannedActivity' => $rawData,'msg' => 'History retrieved', 'status' => 'OK') ;
		}

        $this->encode($statusCode,$rawData);
		//$requestContentType = $_SERVER['HTTP_ACCEPT'];
		//$this ->setHttpHeaders($requestContentType, $statusCode);
				
		  //    $response = json_encode($rawData);
			
			//echo $response;
		
	}
    
    public function nNextValues($user, $n) {

		$plannedActivity = new PlannedActivity();
		$rawData = $plannedActivity->getPANNextValues($user, $n);

		if(empty($rawData)) {
			$statusCode = 200;
			$rawData = array('msg' => 'No planned activities for the specified date or user', 'status' => 'OK');		
		} else {
			$statusCode = 200;
            $rawData = array('listPlannedActivity' => $rawData,'msg' => 'History retrieved', 'status' => 'OK') ;
		}

		 $this->encode($statusCode,$rawData);
		
	}
    
    public function fromDateToNow($user, $date) {

		$plannedActivity = new PlannedActivity();
		$rawData = $plannedActivity->getPAFromDateToNow($user, $date);
		if(empty($rawData)) {
			$statusCode = 200;
			$rawData = array('msg' => 'No planned activities for the specified date or user', 'status' => 'OK');		
		} else {
			$statusCode = 200;
            $rawData = array('listPlannedActivity' => $rawData,'msg' => 'History retrieved', 'status' => 'OK') ;
		}
            $this->encode($statusCode,$rawData);
		
	}
    
    public function betweenDates($user, $date1, $date2) {

		$plannedActivity = new PlannedActivity();
		$rawData = $plannedActivity->getPABetweenDates($user, $date1, $date2);

		if(empty($rawData)) {
			$statusCode = 200;
			$rawData = array('msg' => 'No planned activities for the specified date or user', 'status' => 'OK');		
		} else {
			$statusCode = 200;
            $rawData = array('listPlannedActivity' => $rawData,'msg' => 'History retrieved', 'status' => 'OK') ;
		}

		 $this->encode($statusCode,$rawData);
	}
    
    
        
    public function onDate($user, $date) {

		$plannedActivity = new PlannedActivity();
		$rawData = $plannedActivity->getPAOnDate($user, $date);

		if(empty($rawData)) {
			$statusCode = 200;
			$rawData = array('msg' => 'No planned activities for the specified date or user', 'status' => 'OK');		
		} else {
			$statusCode = 200;
            $rawData = array('listPlannedActivity' => $rawData,'msg' => 'History retrieved', 'status' => 'OK') ;
		}

		 $this->encode($statusCode,$rawData);
		
	}
    
    public function lastAccess($user){
        $plannedActivity = new PlannedActivity();
        $rawData = $plannedActivity->getLastAccessToPlan($user);
        if(empty($rawData)){
            $statusCode = 404;
        }
        else{
            $statusCode = 200;
            $rawData = array('msg'=>"", 'status'=> 'OK', 'value'=> $rawData );
        }

        $this->encode($statusCode, $rawData);
    }
    
    public function encode ($statusCode, $rawData){
        
        //$requestContentType = $_SERVER['HTTP_ACCEPT'];
        $requestContentType = "application/json";
		$this ->setHttpHeaders($requestContentType, $statusCode);
				
        $response = $this->encodeJson($rawData);
        echo $response;
    }
    
    
    
}
?>