<?php
/* 
A domain Class to demonstrate RESTful web services
*/


//DB for local 
define("DB_SERVER_NAME", "accessible-serv.lasige.di.fc.ul.pt");
define("DB_USERNAME", "personaal");
define("DB_PASSWORD", "personaalfcul");
define("DB_NAME","remote_assistant");


//DB for local (REPLY)
/*define("DB_SERVER_NAME", "localhost");
define("DB_USERNAME", "root");
define("DB_PASSWORD", null);
define("DB_NAME","personaal");
*/

Class PlannedActivity{
    public $userID;
    public $title;
    public $type;
    public $intensity;
    public $start_date;
    public $end_date;
    public $all_day;
    public $done;
    public $activityId;
    
    public function PA($userID,$title,$start_date,$end_date,$all_day,$done,$type,$intensity,$activityId){
        $this->userID = $userID;
        $this->title = $title;
        $this->start_date = $start_date;
        $this->end_date = $end_date;
        $this->all_day = $all_day;
        $this->done = $done;
        $this->type  = $type;
        $this->intensity = $intensity;
        $this-> activityId = $activityId;
    }
    
    public function PlannedActivity(){
        $get_arguments = func_get_args();
        $n_args = func_num_args();
        
        if($n_args == 0){
        
        $this->userID = "";
        $this->title ="";
        $this->start_date ="" ;
        $this->end_date ="" ;
        $this->all_day = "";
        $this->done = "";
        $this->type  = "";
        $this->intensity ="" ;
        $this-> activityId = "";
        }
        else {
            call_user_func_array(array($this, "PA"), $get_arguments);
        }
    }
    
    
    public function getPlannedActivities($user){
         // Create connection
        $conn = new mysqli(DB_SERVER_NAME, DB_USERNAME, DB_PASSWORD, DB_NAME);
        // Check connection
        if ($conn->connect_error)
        {
            echo("Connection failed: " . $conn->connect_error);
            $conn->close();
            return;
        } 

        $sql = "SELECT * FROM activity WHERE userid='". $user ."'";
        $result = $conn->query($sql);
        $conn->close();

        if(!$result)
        {
            echo('There was an error running the query [' . $conn->error . ']');

            return false;
        }
        if ($result->num_rows > 0)
        {

            $activitiesArray=[];
            $activities = [];
            while($row = $result->fetch_assoc())
            {
                //echo("ROW ". $row);

                $activity = new PlannedActivity(
                    $row['userid'],
                    $row['title'],
                    $row['start_date'],
                    $row['end_date'],
                    $row['all_day'],
                    $row['done'],
                    $row['type'],
                    $row['intensity'],
                    $row['activityId']

                );
                
                
                //echo($activity);
                array_push($activitiesArray, $activity);
                //array_push($activitiesArray, $activities);
                
            }
            //echo($activitiesArray);
            return $activitiesArray;

        }   
        else
        {
            return false;
            echo "no results";
        }
    }
    
    
    public function getPAOnDate($user, $date){
         // Create connection
        $conn = new mysqli(DB_SERVER_NAME, DB_USERNAME, DB_PASSWORD, DB_NAME);
        // Check connection
        if ($conn->connect_error)
        {
            echo("Connection failed: " . $conn->connect_error);
            $conn->close();
            return;
        } 
       // $format = 'Y-m-d';
        //if (validateDate($date, $format)){
        //$sql = "SELECT * FROM activity WHERE userid='". $user ."' AND start_date ='". $date ."' || end_date = '". $date ."'";
        $sql = "SELECT * FROM activity WHERE userid= '". $user ."' AND (date(start_date) = '". $date ."' || date(end_date) = '". $date ."')";
       //echo($sql);    
        
        $result = $conn->query($sql);
        $conn->close();

        if(!$result)
        {
            echo('There was an error running the query [' . $conn->error . ']');

            return false;
        }
        if ($result->num_rows > 0)
        {

            $activitiesArray=[];
            while($row = $result->fetch_assoc())
            {
                //echo("ROW ". $row);

                $activity = new PlannedActivity(
                    $row['userid'],
                    $row['title'],
                    $row['start_date'],
                    $row['end_date'],
                    $row['all_day'],
                    $row['done'],
                    $row['type'],
                    $row['intensity'],
                    $row['activityId']

                );
                //echo($activity);
                array_push($activitiesArray, $activity);
            }
            //echo($activitiesArray);
            return $activitiesArray;

        }   
        else
        {
            return false;
            echo "no results";
        }
       // }
        //else return ("msg:Wrong date format: yyyy-MM-dd,status:ERROR");
            
            
    }
        
        
    
    public function getPABetweenDates($user, $date1, $date2){
         // Create connection
        $conn = new mysqli(DB_SERVER_NAME, DB_USERNAME, DB_PASSWORD, DB_NAME);
        // Check connection
        if ($conn->connect_error)
        {
            echo("Connection failed: " . $conn->connect_error);
            $conn->close();
            return;
        } 
 
        $sql = "SELECT * FROM activity WHERE userid='". $user ."' AND ((date(start_date) >= '". $date1 ."' AND date(end_date) <= '". $date2 ."' ) OR (date(start_date) <= '". $date1 ."' AND date(end_date) >= '". $date2 ."') OR (date(start_date) <= '". $date2 ."' AND date(end_date) >= '". $date1 ."')) ";
        
        
        $result = $conn->query($sql);
        $conn->close();

        if(!$result)
        {
            echo('There was an error running the query [' . $conn->error . ']');

            return false;
        }
        if ($result->num_rows > 0)
        {

            $activitiesArray=[];
            while($row = $result->fetch_assoc())
            {
                //echo("ROW ". $row);

                $activity = new PlannedActivity(
                    $row['userid'],
                    $row['title'],
                    $row['start_date'],
                    $row['end_date'],
                    $row['all_day'],
                    $row['done'],
                    $row['type'],
                    $row['intensity'],
                    $row['activityId']

                );
                //echo($activity);
                array_push($activitiesArray, $activity);
            }
            //echo($activitiesArray);
            return $activitiesArray;

        }   
        else
        {
            return false;
            echo "no results";
            
        }
    }
        
    public function getPANLastValues($user, $n){
         // Create connection
        $conn = new mysqli(DB_SERVER_NAME, DB_USERNAME, DB_PASSWORD, DB_NAME);
        // Check connection
        if ($conn->connect_error)
        {
            echo("Connection failed: " . $conn->connect_error);
            $conn->close();
            return;
        } 
        
        $currentDate = date('Y-m-d H:i:s');
        
        $sql = "SELECT * FROM activity WHERE userid='". $user ."' AND (date(start_date) <='". $currentDate ."' AND date(end_date) <= '". $currentDate ."') LIMIT ". $n ."";
       
        
        $result = $conn->query($sql);
        $conn->close();

        if(!$result)
        {
            echo('There was an error running the query [' . $conn->error . ']');
            
            //return $conn->error;
            return false;
        }
        if ($result->num_rows > 0)
        {

            $activitiesArray=[];
            while($row = $result->fetch_assoc())
            {
                //echo("ROW ". $row);

                $activity = new PlannedActivity(
                    $row['userid'],
                    $row['title'],
                    $row['start_date'],
                    $row['end_date'],
                    $row['all_day'],
                    $row['done'],
                    $row['type'],
                    $row['intensity'],
                    $row['activityId']

                );
                //echo($activity);
                array_push($activitiesArray, $activity);
            }
            //echo($activitiesArray);
            return $activitiesArray;

        }   
        else
        {
            return false;
            echo "no results";
        }
    }
    
    
    
    public function getPANNextValues($user, $n){
         // Create connection
        $conn = new mysqli(DB_SERVER_NAME, DB_USERNAME, DB_PASSWORD, DB_NAME);
        // Check connection
        if ($conn->connect_error)
        {
            echo("Connection failed: " . $conn->connect_error);
            $conn->close();
            return;
        } 
        
        $currentDate = date('Y-m-d H:i:s');
        
         $sql = "SELECT * FROM activity WHERE userid='". $user ."' AND (start_date >='". $currentDate ."' AND end_date >= '". $currentDate ."') LIMIT ". $n ."";
        
        
        $result = $conn->query($sql);
        $conn->close();

        if(!$result)
        {
            echo('There was an error running the query [' . $conn->error . ']');
            return $conn->error;
            //return false;
        }
        if ($result->num_rows > 0)
        {

            $activitiesArray=[];
            while($row = $result->fetch_assoc())
            {
                //echo("ROW ". $row);

                $activity = new PlannedActivity(
                    $row['userid'],
                    $row['title'],
                    $row['start_date'],
                    $row['end_date'],
                    $row['all_day'],
                    $row['done'],
                    $row['type'],
                    $row['intensity'],
                    $row['activityId']

                );
                //echo($activity);
                array_push($activitiesArray, $activity);
            }
            //echo($activitiesArray);
            return $activitiesArray;

        }   
        else
        {
            return false;
            echo "no results";
        }
    }
    
    
    public function getPAFromDateToNow($user, $date){
         // Create connection
        $conn = new mysqli(DB_SERVER_NAME, DB_USERNAME, DB_PASSWORD, DB_NAME);
        // Check connection
        if ($conn->connect_error)
        {
            echo("Connection failed: " . $conn->connect_error);
            $conn->close();
            return;
        } 
        
        $currentDate = date('Y-m-d');
        
        if($date >= date($currentDate)){
            $sql = "SELECT * FROM activity WHERE userid='". $user ."' (AND date(start_date) >= '". $currentDate ."' (AND date(start_date) <='". $date ."' || date(end_date) <= '". $date ."'))";
            $result = $conn->query($sql);
            $conn->close();
            
    
        }
            else{
        $sql = "SELECT * FROM activity WHERE userid='". $user ."' AND (date(start_date) >='". $date ."' || date(end_date) >= '". $date ."')";
        $result = $conn->query($sql);
        $conn->close();
            
                
        }
                
                
        if(!$result)
        {
            echo('There was an error running the query [' . $conn->error . ']');

            return false;
        }
        if ($result->num_rows > 0)
        {

            $activitiesArray=[];
            while($row = $result->fetch_assoc())
            {
                //echo("ROW ". $row);

                $activity = new PlannedActivity(
                    $row['userid'],
                    $row['title'],
                    $row['start_date'],
                    $row['end_date'],
                    $row['all_day'],
                    $row['done'],
                    $row['type'],
                    $row['intensity'],
                    $row['activityId']

                );
                //echo($activity);
                array_push($activitiesArray, $activity);
            }
            //echo($activitiesArray);
            return $activitiesArray;

        }   
        else
        {
            return false;
            echo "no results";
        }
    }
    

    public function getLastAccessToPlan($user){
          // Create connection
        $conn = new mysqli(DB_SERVER_NAME, DB_USERNAME, DB_PASSWORD, DB_NAME);
        // Check connection
        if ($conn->connect_error)
        {
            echo("Connection failed: " . $conn->connect_error);
            $conn->close();
            return;
        } 
        
        $sql = "SELECT last_access_plan FROM users WHERE usersid='". $user ."'";
        
        $result = $conn->query($sql);
        $conn->close();
        if(!$result)
        {
            echo('There was an error running the query [' . $conn->error . ']');
            $conn->close();
            return;
        }
        
        if ($result->num_rows > 0)
        {
            
            while($row = $result->fetch_assoc())
            {
                $result1 = $row['last_access_plan'];
                
            //array_push()    
                
            }
            return $result1;
        }
        
        else 
        {
            //return false;
            echo "no results";
        }
        
    }
    
    
    }
    
    
        
        
   


?>