<?php


//DB for public vm
//define("DB_SERVER_NAME", "localhost");
//define("DB_USERNAME", "root");
//define("DB_PASSWORD", "Vsw1e3t56.");
//define("DB_NAME","personaal");

//DB for local 
define("DB_SERVER_NAME", "accessible-serv.lasige.di.fc.ul.pt");
define("DB_USERNAME", "personaal");
define("DB_PASSWORD", "personaalfcul");
define("DB_NAME","remote_assistant");




/*ritorna:
 * -1: errorri con la connessione col database
 * FALSE: credenziali non valide
 * TRUE: login effettuato con successo
 */
function login($user, $pw, $returnUser = FALSE)
{
    /*$json= file_get_contents("http://localhost:8888/BancomatWs/json/saldo/1");
	echo $json;*/


    // Create connection
    $conn = new mysqli(DB_SERVER_NAME, DB_USERNAME, DB_PASSWORD, DB_NAME);
    // Check connection
    if ($conn->connect_error)
    {
        echo("Connection failed: " . $conn->connect_error);
        $conn->close();
        return -1;
    } 

    $pw= sha1($pw);

    $sql = "SELECT * FROM users WHERE usersid='".$user."' AND password='".$pw."'";
    $result = $conn->query($sql);

    if(!$result){
        //        echo('There was an error running the query [' . $conn->error . ']');
        $conn->close();
        return -1;
    }


    if ($result->num_rows > 0)
    {
        // right username and password
        $row = $result->fetch_assoc();
        //        echo("id: ".$row['usersid']." pass: ".$row['password']);
    }
    else
    {
        //TODO wrong username or password
        //        echo "Wrong username or password";
        $conn->close();
        return FALSE;
    }

    $conn->close();

    //return the logged user information, used for PersonAAL plugin
    if($returnUser === TRUE)
    {
        return new UserData($user);
    }

    return TRUE;


}




function resetWeeklyGoals()
{
    //procetion variable setted
    if (isset($_REQUEST['ashJWO13dqk14jwh123ndAWDik']) && $_REQUEST['ashJWO13dqk14jwh123ndAWDik'] != "")
    {
        //check if the request 
        if($_REQUEST['ashJWO13dqk14jwh123ndAWDik'] == 'nKAB10293ja86A92JAka1313jakUA786A92kUA79nDKAjnakd')
        {
            //exectue script only if is monday
            $date= date('N');
            if($date == 1)
            {
                echo('today is monday');
                // Create connection
                $conn = new mysqli(DB_SERVER_NAME, DB_USERNAME, DB_PASSWORD, DB_NAME);
                // Check connection
                if ($conn->connect_error)
                {
                    echo("Connection failed: " . $conn->connect_error);
                    $conn->close();
                    return FALSE;
                } 

                $sql = "UPDATE plan SET"
                    . " walkWeekGoal='0',"
                    . " exerciseWeekGoal='0',"
                    . " meetWeekGoal='0',"
                    . " actualWeekWalk='0',"
                    . " actualWeekExercise='0',"
                    . " actualWeekMeet='0'";

                $result = $conn->query($sql);

                if(!$result){
                    echo('There was an error running the query [' . $conn->error . ']');
                    $conn->close();
                    return FALSE;
                }


                $conn->close();
                return TRUE;

            }
            else
                echo('today is not monday');
        }
        else
            echo('error, value not correct');

    }
    else
        echo(' the right variable is not set');
}



function register($username, $password, $name, $surname, $birthDate, $gender, $state, $city, $cap, $address)
{
    // Create connection
    $conn = new mysqli(DB_SERVER_NAME, DB_USERNAME, DB_PASSWORD, DB_NAME);
    // Check connection
    if ($conn->connect_error)
    {
        echo("Connection failed: " . $conn->connect_error);
        $conn->close();
        return FALSE;
    } 

    //check if user already exists

    $checkUserSql = "SELECT * FROM users WHERE usersid='".$username."'";
    $checkUserResult = $conn->query($checkUserSql);

    if ($checkUserResult->num_rows > 0)
    {
        //username already used
        $conn->close();
        return -1;
    }

    $password= sha1($password);

    //create user row
    $createUserSql = "INSERT INTO users (usersid, password, userType, name, surname, gender, birthDate, state, city, cap, address)"
        . " VALUES "
        . "("
        . " '". $username ."',"
        . " '". $password ."',"
        . " 'patient',"
        . " '". $name ."',"
        . " '". $surname  ."',"
        . " '". $gender ."',"
        . " '". $birthDate ."',"
        . " '". $state ."',"
        . " '". $city ."',"
        . " '". $cap ."',"
        . " '". $address ."'"
        . ")";



    $result = $conn->query($createUserSql);


    if(!$result){
        echo('There was an error running the query [' . $conn->error . ']');
        $conn->close();
        return FALSE;
    }

    $conn->close();
    return TRUE;
}

function alterTableUser($user){

}



/***************************************************  DATABSE MANAGER CLASSES ************************************************************/
/*
 * ATTENTION: this classes are meant to be used with javascript ajax call (see js/plugins/DBinterface.js and ajax_request.php)
 *  */

/*class that represent a weight table record on DB
 * ATTENTION: timestamps on DB are saved as JAVASCRIPT timestamps such as timestamp*1000 (in milliseconds)
 *  */
class WeightData{
    //TODO handle database errors

    private $userID;
    private $dataArray;


    public function WeightData($user)
    {
        // Create connection
        $conn = new mysqli(DB_SERVER_NAME, DB_USERNAME, DB_PASSWORD, DB_NAME);
        // Check connection
        if ($conn->connect_error)
        {
            //            echo("Connection failed: " . $conn->connect_error);
            $conn->close();
            return;
        } 

        $sql = "SELECT * FROM weight WHERE usersid='".$user."'";
        $result = $conn->query($sql);

        if(!$result)
        {
            //            echo('There was an error running the query [' . $conn->error . ']');
            $conn->close();
            return;
        }

        if ($result->num_rows > 0)
        {
            //save userID
            $this->userID= $user;

            $row = $result->fetch_assoc();
            //echo("id: ". $row['usersid'] ." json:".$row['weightJSON']);

            $JSONdate = json_decode($row['weightJSON'], true);

            if($JSONdate === NULL)
                echo("error parsing weight JSON");
            else
            {
                $this->dataArray = $JSONdate;
            }

        }
        else
        {
            //TODO wrong username or password
            echo "no results";
        }

    }

    public function getData()
    {
        return $this->dataArray;
    }

    public function printData()
    {
        echo(json_encode($this->dataArray));
    }

    /*
        return data for weight plot in JSON format (compatible with flot data)
     * ex:
     * [
     *  [timestamp, weight],
     *  [timestamp, weight]...
     * ]
          */
    public function getDataForJS()
    {
        $dataForJS= json_encode($this->dataArray, JSON_NUMERIC_CHECK );

        //echo($dataForJS);

        return $dataForJS;

    }

    /*add weight data to database and private dataArray*/
    public function addDataOnDB($timestamp, $weight)
    {
        array_push($this->dataArray, [$timestamp, $weight]);

        $this->updateDataOnDB();
    }

    private function updateDataOnDB()
    {
        // Create connection
        $conn = new mysqli(DB_SERVER_NAME, DB_USERNAME, DB_PASSWORD, DB_NAME);
        // Check connection
        if ($conn->connect_error)
        {
            //            echo("Connection failed: " . $conn->connect_error);
            $conn->close();
            return;
        } 

        $sql = "UPDATE weight SET weightJSON='".$this->getDataForJS()."' WHERE usersid='".$this->userID."'";
        $result = $conn->query($sql);

        if(!$result)
        {
            //            echo('There was an error running the query [' . $conn->error . ']');
            $conn->close();
            return;
        }

        $conn->close();
    }

}

/*class that represent a table record of FiND score on DB
 * ATTENTION: timestamps on DB are saved as JAVASCRIPT timestamps such as timestamp*1000 (in milliseconds)
 *  */

class FindData{
    //TODO handle database errors

    private $userID;
    private $dataArray;


    public function FindData($user)
    {
        // Create connection
        $conn = new mysqli(DB_SERVER_NAME, DB_USERNAME, DB_PASSWORD, DB_NAME);
        // Check connection
        if ($conn->connect_error)
        {
            //            echo("Connection failed: " . $conn->connect_error);
            $conn->close();
            return;
        } 

        $sql = "SELECT * FROM find WHERE usersid='".$user."'";
        $result = $conn->query($sql);

        if(!$result)
        {
            //            echo('There was an error running the query [' . $conn->error . ']');
            $conn->close();
            return;
        }

        if ($result->num_rows > 0)
        {
            //save userID
            $this->userID= $user;

            $row = $result->fetch_assoc();
            //echo("id: ". $row['usersid'] ." json:".$row['scoreJSON']);

            $JSONdate = json_decode($row['scoreJSON'], true);

            if($JSONdate === NULL)
                echo("error parsing score JSON");
            else
            {
                $this->dataArray = $JSONdate;
            }

        }
        else
        {
            //TODO wrong username or password
            echo "no results";
        }

    }

    public function getData()
    {
        return $this->dataArray;
    }

    public function printData()
    {
        echo(json_encode($this->dataArray));
    }


    public function getDataForJS()
    {
        $dataForJS= json_encode($this->dataArray, JSON_NUMERIC_CHECK );


        return $dataForJS;

    }

    /*add weight data to database and private dataArray*/
    public function addDataOnDB($timestamp, $score)
    {
        array_push($this->dataArray, [$timestamp, $score]);

        $this->updateDataOnDB();
    }

    private function updateDataOnDB()
    {
        // Create connection
        $conn = new mysqli(DB_SERVER_NAME, DB_USERNAME, DB_PASSWORD, DB_NAME);
        // Check connection
        if ($conn->connect_error)
        {
            //            echo("Connection failed: " . $conn->connect_error);
            $conn->close();
            return;
        } 

        $sql = "UPDATE find SET scoreJSON='".$this->getDataForJS()."' WHERE usersid='".$this->userID."'";
        $result = $conn->query($sql);

        if(!$result)
        {
            //            echo('There was an error running the query [' . $conn->error . ']');
            $conn->close();
            return;
        }

        $conn->close();
    }

}



class UserData{
    //TODO handle database errors

    public $username;
    private $userType;
    public $name;
    public $surname;
    public $birthDate;
    public $age;
    public $gender;
    public $state;
    public $city;
    public $cap;
    public $address;
    public $last_access_plan;


    public function UserData($user)
    {
        // Create connection
        $conn = new mysqli(DB_SERVER_NAME, DB_USERNAME, DB_PASSWORD, DB_NAME);
        // Check connection
        if ($conn->connect_error)
        {
            echo("Connection failed: " . $conn->connect_error);
            $conn->close();
            return;
        } 

        $sql = "SELECT * FROM users WHERE usersid='".$user."'";
        $result = $conn->query($sql);

        if(!$result)
        {
            echo('There was an error running the query [' . $conn->error . ']');
            $conn->close();
            return;
        }

        if ($result->num_rows > 0)
        {
            //save username
            $this->username= $user;

            $row = $result->fetch_assoc();

            //save user information
            $this->userType= $row['userType'];
            $this->name = $row['name'];
            $this->surname = $row['surname'];
            $this->birthDate = $row['birthDate'];
            $this->gender = $row['gender'];
            $this->state = $row['state'];
            $this->city = $row['city'];
            $this->cap = $row['cap'];
            $this->address = $row['address'];

            //calculate age
            $date= new DateTime($this->birthDate);
            $interval = $date->diff(new DateTime(NULL));
            $this->age= intval($interval->format('%y years'));

        }
        else
        {
            //TODO wrong username or password
            echo "no results";
        }

    }

    //    
    //    public function printUserData()
    //    {
    //        echo($this->userType);
    //        echo($this->name);
    //        echo($this->surname);
    //        echo($this->birthDate);
    //        echo($this->gender);
    //        echo($this->state);
    //        echo($this->city);
    //        echo($this->cap);
    //        echo($this->address);
    //        echo($this->age);
    //    }
    /*
    public getLastAccessPlan($user){
        // Create connection
        $conn = new mysqli(DB_SERVER_NAME, DB_USERNAME, DB_PASSWORD, DB_NAME);
        // Check connection
        if ($conn->connect_error)
        {
            echo("Connection failed: " . $conn->connect_error);
            $conn->close();
            return;
        } 

        $sql = "SELECT last_access_plan FROM users WHERE usersid='".$user."'";
        $result = $conn->query($sql);
        if(!$result)
        {
            echo('There was an error running the query [' . $conn->error . ']');
            $conn->close();
            return;
        }
        if ($result->num_rows > 0)
        {
            //save username
            $this->username= $user;
            $row = $result->fetch_assoc();
            //save user information
            $this->last_access_plan= $row['last_access_plan'];
        }
        else
        {
            //TODO wrong username or password
            echo "no results";
        }
    }
*/



}

class SurveyData{
    //TODO handle database errors

    private $userID;
    private $weight;
    private $height;
    private $age;
    private $motivation;

    private static $WEIGHT= 12;
    private static $HEIGHT= 13;
    private static $AGE= 14;
    private static $MOTIVATION= 15;

    public function SurveyData($user){
        // Create connection
        $conn = new mysqli(DB_SERVER_NAME, DB_USERNAME, DB_PASSWORD, DB_NAME);
        // Check connection
        if ($conn->connect_error)
        {
            echo("Connection failed: " . $conn->connect_error);
            $conn->close();
            return;
        } 

        $sql = "SELECT * FROM users WHERE usersid='".$user."'";
        $result = $conn->query($sql);

        if(!$result)
        {
            echo('There was an error running the query [' . $conn->error . ']');
            $conn->close();
            return;
        }

        if ($result->num_rows > 0)
        {
            //save username
            $this->userID= $user;

            $row = $result->fetch_assoc();

            //save user information
            $this->weight= $row['weight'];
            $this->height = $row['height'];
            $this->age = $row['age'];
            $this->motivation = $row['motivation'];

        }
        else
        {
            //TODO wrong username or password
            echo "no results";
        }
    }
    public function getWeight()
    {
        return $this->weight;
    }

    public function getHeight()
    {
        return $this->height;
    }

    public function getAge()
    {
        return $this->age;
    }

    public function getMotivation()
    {
        return $this->motivation;
    }

    public function setWeight($value)
    {
        $this->weight = $value;
        $this->updateValue(SurveyData::$WEIGHT);
    }

    public function setHeight($value)
    {
        $this->height = $value;
        $this->updateValue(SurveyData::$HEIGHT);
    }

    public function setAge($value)
    {
        $this->age = $value;
        $this->updateValue(SurveyData::$AGE);
    }

    public function setMotivation($value)
    {
        $this->motivation = $value;
        $this->updateValue(SurveyData::$MOTIVATION);
    }

    private function updateValue($field)
    {
        switch($field)
        {
            case SurveyData::$WEIGHT:
                $sql = "UPDATE users SET weight='".$this->weight."' WHERE usersid='".$this->userID."'";
                break;

            case SurveyData::$HEIGHT:
                $sql = "UPDATE users SET height='".$this->height."' WHERE usersid='".$this->userID."'";
                break;

            case SurveyData::$AGE:
                $sql = "UPDATE users SET age='".$this->age."' WHERE usersid='".$this->userID."'";
                break;

            case SurveyData::$MOTIVATION:
                $sql = "UPDATE users SET motivation='".$this->motivation."' WHERE usersid='".$this->userID."'";
                break;

        }

        // Create connection
        $conn = new mysqli(DB_SERVER_NAME, DB_USERNAME, DB_PASSWORD, DB_NAME);
        // Check connection
        if ($conn->connect_error)
        {
            echo("Connection failed: " . $conn->connect_error);
            $conn->close();
            return;
        } 

        $result = $conn->query($sql);

        if(!$result)
        {
            echo('There was an error running the query [' . $conn->error . ']');
            $conn->close();
            return;
        }

        $conn->close();
    }

}


/* CLASS THAT REPRESENTS ACTIVITIES ADDED IN THE CALENDAR */
class Activity {
    public $userID;
    public $title;
    public $type;
    public $intensity;
    public $start_date;
    public $end_date;
    public $all_day;
    public $done;
    public $activityId;
    /*
    public function Activity($userID,$title,$type,$intensity,$start_date,$end_date,$all_day,$done,$activityId){
        $this->userID = $userID;
        $this->title = $title;
        $this->type  = $type;
        $this->intensity = $intensity;
        $this->start_date = $start_date;
        $this->end_date = $end_date;
        $this->all_day = $all_day;
        $this->done = $done;
        $this-> activityId = $activityId;

    }
   */ 

    public function Activity($userID,$title,$start_date,$end_date,$all_day,$done,$type,$intensity,$activityId){
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


    /*
    public function getActivity($user){
        // Create connection
        $conn = new mysqli(DB_SERVER_NAME, DB_USERNAME, DB_PASSWORD, DB_NAME);
        // Check connection
        if ($conn->connect_error)
        {
            echo("Connection failed: " . $conn->connect_error);
            $conn->close();
            return;
        } 

        $sql = "SELECT * FROM activity WHERE usersid='".$user."'";
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

            $activitiesArray=[];
            while($row = $result->fetch_assoc())
            {
                //save variables
                $this->userID= $user;
                $this->title= $row['title'];
                $this->type= $row['type'];
                $this->intensity= $row['intensity'];
                $this->start_date= $row['start_date'];
                $this->end_date= $row['end_date'];
                $this->all_day= $row['all_day'];
                $this->done= $row['done'];
            }

            return $activitiesArray;

        }   
        else
        {

            echo "no results";
        }
    }
  */   



    public function addActivity($userID, $title, $start_date, $end_date, $all_day, $done, $type, $intensity){
        // Create connection
        $conn = new mysqli(DB_SERVER_NAME, DB_USERNAME, DB_PASSWORD, DB_NAME);
        // Check connection
        if ($conn->connect_error)
        {
            //     echo("Connection failed: " . $conn->connect_error);
            $conn->close();
            return false;
        } 

        //convert start_date and end_date to mysql datetime format
        $mysqldateStart = date( 'Y-m-d H:i:s', strtotime($start_date) );
        $mysqldateEnd = date( 'Y-m-d H:i:s', strtotime($end_date) );
        //$mysqldateStart = date( 'Y-m-d H:i:s', $start_date );
        //$mysqldateEnd = date( 'Y-m-d H:i:s', $end_date );
        // echo('start date: '.$start_date);
        //echo($all_day);

        $sql = "INSERT INTO activity (userID, title, start_date, end_date, all_day,done,type, intensity)"
            . " VALUES "
            . "('". $userID ."',"
            . " '". $title ."',"
            . " '". $mysqldateStart ."',"
            . " '". $mysqldateEnd ."',"
            . " '". $all_day ."',"
            . " '". '0' ."',"
            . " '". $type ."',"
            . " '". $intensity ."'"
            . ")";

        //echo($sql);
        $result= $conn->query($sql);
        //echo($result);
        $last_id = $conn->insert_id;
        //echo ("New record created successfully. Last inserted ID is: " . $last_id);

        $conn->close();

        if(!$result)
        {
            //echo('There was an error running the query [' . $conn->error . ']');
            //echo "Error: " . $sql . "<br>" . $conn->error;
            return false;
        }
        else{
            return $last_id;
        }   
    }




    public function getActivity($user){
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
            while($row = $result->fetch_assoc())
            {
                //echo("ROW ". $row);

                $activity = new Activity(
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

    public function getActivitiesFromLastAccess($user){
        // Create connection
        $conn = new mysqli(DB_SERVER_NAME, DB_USERNAME, DB_PASSWORD, DB_NAME);
        // Check connection
        if ($conn->connect_error)
        {
            echo("Connection failed: " . $conn->connect_error);
            $conn->close();
            return;
        } 
        // tem de ser de ontem
        $currentDate = date('Y-m-d H:i:s');

        
        //$sql = "SELECT activityId FROM activity a, users u WHERE u.usersid='". $user ."' AND   a.userid='". $user ."' AND a.start_date > u.last_access_plan AND a.start_date <'".$currentDate."'  AND a.done = 0 ";
        //echo("USERID: ". $user);
        //echo($sql);
        
        $sql = "SELECT title, start_date, end_date, type, intensity, activityId FROM activity a, users u WHERE u.usersid='". $user ."' AND   a.userid='". $user ."' AND a.start_date > u.last_access_plan AND a.start_date <'".$currentDate."'  AND a.done = 0 ";
        
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

            $activitiesToDoArray=[];
            while($row = $result->fetch_assoc())
            {
                $activityInfo = [ $row['title'],$row['type'],$row['start_date'],$row['end_date'], $row['intensity'], $row['activityId']];
                
            array_push($activitiesToDoArray,$activityInfo );
            }
            
            //array_push($activitiesToDoArray, $row['activityId']);
            
            //echo("activities_to_do: " . $activitiesToDoArray);
            return $activitiesToDoArray;
            

        }   
        else
        {
            //return false;
            echo "no results";
        }

    }

    
    public function updateLastAccess($user){
         // Create connection
        $conn = new mysqli(DB_SERVER_NAME, DB_USERNAME, DB_PASSWORD, DB_NAME);
        // Check connection
        if ($conn->connect_error)
        {
            //            echo("Connection failed: " . $conn->connect_error);
            $conn->close();
            return false;
        } 
        
        $currentDate = date('Y-m-d H:i:s');
        
        $sql = "UPDATE users SET last_access_plan= '".$currentDate."' WHERE usersid='". $user ."'";
        
        $result = $conn->query($sql);
        $conn->close();
        
        if(!$result)
        {
            echo('There was an error running the query [' . $conn->error . ']');
            $conn->close();
            return;
        }
        else{
            return $result;
        }
        
        
    }


    public function setActivityDone($activityId){
        // Create connection
        $conn = new mysqli(DB_SERVER_NAME, DB_USERNAME, DB_PASSWORD, DB_NAME);
        // Check connection
        if ($conn->connect_error)
        {
            //            echo("Connection failed: " . $conn->connect_error);
            $conn->close();
            return false;
        } 


        $sql = "UPDATE activity SET done=1 WHERE activityId ='".$activityId."'";

        $result = $conn->query($sql);
        $conn->close();
        if(!$result)
        {
            echo('There was an error running the query [' . $conn->error . ']');
            $conn->close();
            return;
        }
        else{
            return $result;
        }


    }


}






/*class that represent a plan table record on DB*/
class Plan{

    private $userID;
    private $walkWeekGoal;
    private $exerciseWeekGoal;
    private $meetWeekGoal;
    private $actualWeekWalk;
    private $actualWeekExercise;
    private $actualWeekMeet;
    private $events;

    private static $WALK_GOAL= 0;
    private static $EXERCISE_GOAL= 1;
    private static $MEET_GOAL= 2;
    private static $ACTUAL_WALK= 3;
    private static $ACTUAL_EXERCISE= 4;
    private static $ACTUAL_MEET= 5;
    private static $EVENT= 6;
    private static $ALL_GOALS= 7;

    public function Plan($user)
    {
        // Create connection
        $conn = new mysqli(DB_SERVER_NAME, DB_USERNAME, DB_PASSWORD, DB_NAME);
        // Check connection
        if ($conn->connect_error)
        {
            echo("Connection failed: " . $conn->connect_error);
            $conn->close();
            return;
        } 

        $sql = "SELECT * FROM plan WHERE usersid='".$user."'";
        $result = $conn->query($sql);

        if(!$result)
        {
            echo('There was an error running the query [' . $conn->error . ']');
            $conn->close();
            return;
        }

        if ($result->num_rows > 0)
        {
            $row = $result->fetch_assoc();

            //save variables
            $this->userID= $user;
            $this->walkWeekGoal= $row['walkWeekGoal'];
            $this->exerciseWeekGoal= $row['exerciseWeekGoal'];
            $this->meetWeekGoal= $row['meetWeekGoal'];
            $this->actualWeekWalk= $row['actualWeekWalk'];
            $this->actualWeekExercise= $row['actualWeekExercise'];
            $this->actualWeekMeet= $row['actualWeekMeet'];

            if(json_decode($row['events']) != null)
                $this->events= json_decode($row['events']);
            else
                $this->events= [];

            //TODO parse events into valid format for fullcalendar
            //$JSONdate = json_decode($row['weightJSON'], true);

            //            if($JSONdate === NULL)
            //                echo("error parsin weight JSON");
            //            else
            //            {
            //                //echo($JSONdate['weightData'][0]['date']);
            //                $this->dataArray = $JSONdate;
            //            }

        }
        else
        {
            //TODO wrong username or password
            echo "no results";
        }

    }

    public function printData()
    {
        echo($this->userID."<br>");
        echo($this->walkWeekGoal."<br>");
        echo($this->exerciseWeekGoal."<br>");
        echo($this->meetWeekGoal."<br>");
        echo($this->actualWeekWalk."<br>");
        echo($this->actualWeekExercise."<br>");
        echo($this->actualWeekMeet."<br>");
        print_r($this->events);
    }



    /*******GET********/
    public function getActualWalk()
    {
        return $this->actualWeekWalk;
    }

    public function getActualMeet()
    {
        return $this->actualWeekMeet;
    }

    public function getActualExercise()
    {
        return $this->actualWeekExercise;
    }

    public function getWalkGoal()
    {
        return $this->walkWeekGoal;
    }

    public function getMeetGoal()
    {
        return $this->meetWeekGoal;
    }

    public function getExerciseGoal()
    {
        return $this->exerciseWeekGoal;
    }

    public function getEvents()
    {
        return $this->events;
    }


    /******SET**********/
    public function setActualWalk($value)
    {
        $this->actualWeekWalk = $value;
        $this->updateValue(Plan::$ACTUAL_WALK);
    }

    public function setActualMeet($value)
    {
        $this->actualWeekMeet = $value;
        $this->updateValue(Plan::$ACTUAL_MEET);
    }

    public function setActualExercise($value)
    {
        $this->actualWeekExercise = $value;
        $this->updateValue(Plan::$ACTUAL_EXERCISE);
    }

    public function setWalkGoal($value)
    {
        $this->walkWeekGoal = $value;
        $this->updateValue(Plan::$WALK_GOAL);
    }

    public function setMeetGoal($value)
    {
        $this->meetWeekGoal = $value;
        $this->updateValue(Plan::$MEET_GOAL);
    }

    public function setExerciseGoal($value)
    {
        $this->exerciseWeekGoal = $value;
        $this->updateValue(Plan::$EXERCISE_GOAL);
    }

    public function setGoals($walkGoal, $exerciseGoal, $meetGoal, $walkAmount, $exerciseAmount, $meetAmount)
    {
        $this->walkWeekGoal= $walkGoal;
        $this->exerciseWeekGoal= $exerciseGoal;
        $this->meetWeekGoal= $meetGoal;
        $this->actualWeekWalk= $walkAmount;
        $this->actualWeekExercise= $exerciseAmount;
        $this->actualWeekMeet= $meetAmount;

        $this->updateValue(Plan::$ALL_GOALS);
    }

    public function addEvent($eventJSONstring)
    {
        array_push($this->events, json_decode($eventJSONstring));
        $this->updateValue(Plan::$EVENT);
    }

    public function saveEventList($eventListJSONstring)
    {
        $this->events= json_decode($eventListJSONstring);
        $this->updateValue(Plan::$EVENT);
    }

    private function updateValue($field)
    {
        switch($field)
        {
            case Plan::$ACTUAL_WALK:
                $sql = "UPDATE plan SET actualWeekWalk='".$this->actualWeekWalk."' WHERE usersid='".$this->userID."'";
                break;

            case Plan::$ACTUAL_EXERCISE:
                $sql = "UPDATE plan SET actualWeekExercise='".$this->actualWeekExercise."' WHERE usersid='".$this->userID."'";
                break;

            case Plan::$ACTUAL_MEET:
                $sql = "UPDATE plan SET actualWeekMeet='".$this->actualWeekMeet."' WHERE usersid='".$this->userID."'";
                break;

            case Plan::$WALK_GOAL:
                $sql = "UPDATE plan SET walkWeekGoal='".$this->walkWeekGoal."' WHERE usersid='".$this->userID."'";
                break;

            case Plan::$EXERCISE_GOAL:
                $sql = "UPDATE plan SET exerciseWeekGoal='".$this->exerciseWeekGoal."' WHERE usersid='".$this->userID."'";
                break;

            case Plan::$MEET_GOAL:
                $sql = "UPDATE plan SET meetWeekGoal='".$this->meetWeekGoal."' WHERE usersid='".$this->userID."'";
                break;

            case Plan::$ALL_GOALS:
                $sql = "UPDATE plan SET"
                    . " walkWeekGoal='".$this->walkWeekGoal."',"
                    . " exerciseWeekGoal='".$this->exerciseWeekGoal."',"
                    . " meetWeekGoal='".$this->meetWeekGoal."',"
                    . " actualWeekWalk='".$this->actualWeekWalk."',"
                    . " actualWeekExercise='".$this->actualWeekExercise."',"
                    . " actualWeekMeet='".$this->actualWeekMeet."'"
                    . " WHERE usersid='".$this->userID."'";
                break;

            case Plan::$EVENT:
                $sql = "UPDATE plan SET events='".json_encode($this->events)."' WHERE usersid='".$this->userID."'";
                break;
        }

        // Create connection
        $conn = new mysqli(DB_SERVER_NAME, DB_USERNAME, DB_PASSWORD, DB_NAME);
        // Check connection
        if ($conn->connect_error)
        {
            echo("Connection failed: " . $conn->connect_error);
            $conn->close();
            return;
        } 

        $result = $conn->query($sql);

        if(!$result)
        {
            echo('There was an error running the query [' . $conn->error . ']');
            $conn->close();
            return;
        }

        $conn->close();
    }


}


class Exercise
{
    public $id;
    public $title;
    public $instructions;
    public $isometry;
    public $repetition;
    public $series;
    public $restTimeMilliseconds;
    public $bodyPartUpper;
    public $bodyPartLower;
    public $bodyPartAbdominal;
    public $difficulty;

    public function Exercise($id, $title, $instructions, $isometry, $repetitionJSONstring, $series, $restTimeMilliseconds, $bodyPartUpper, $bodyPartLower, $bodyPartAbdominal,$difficulty)
    {
        $this->id= $id;
        $this->title= $title;
        $this->instructions= json_decode($instructions);
        $this->isometry= $isometry;
        $this->repetition= $repetitionJSONstring;
        $this->series= $series;
        $this->restTimeMilliseconds= $restTimeMilliseconds;
        $this->bodyPartUpper= $bodyPartUpper;
        $this->bodyPartLower= $bodyPartLower;
        $this->bodyPartAbdominal= $bodyPartAbdominal;
        $this->difficulty= $difficulty;
    }

    //return base64 string of the selected image
    public static function getImage($exerciseID)
    {
        // Create connection
        $conn = new mysqli(DB_SERVER_NAME, DB_USERNAME, DB_PASSWORD, DB_NAME);
        // Check connection
        if ($conn->connect_error)
        {
            //echo("Connection failed: " . $conn->connect_error);
            $conn->close();
            return "false1";
        } 


        $stmt = $conn->prepare("SELECT image FROM fitness WHERE id='". $exerciseID ."'"); 

        $stmt->execute();
        $stmt->store_result();

        $stmt->bind_result($image);
        $stmt->fetch();

        $conn->close();

        $imagedata= base64_encode($image);
        //        $conversion= imagecreatefromstring($imagedata);
        //        echo $conversion; 
        //echo '<img src="data:image/gif;base64,'.$imagedata.'"/>';
        return $imagedata;
        //header("Content-Type: image/gif");
        //	echo($image); 
    }
}

/*static class that manage fitness table*/
class Fitness{


    private static function prepareFitnessQuery($bodyPartUpperFilter, $bodyPartLowerFilter, $bodyPartAbdominalFilter, $difficultyFilter)
    {
        $sql= "SELECT * FROM fitness";

        //if all filters are null, return;
        if($bodyPartUpperFilter == false && $bodyPartLowerFilter == false && $bodyPartAbdominalFilter == false && $difficultyFilter == null)
            return $sql;

        $sql= $sql." WHERE ";

        $moreFilters= false;

        //upper body filter
        if($bodyPartUpperFilter == true)
        {
            $moreFilters= true;
            $sql= $sql."(bodyPartUpper='". $bodyPartUpperFilter ."'";
        }

        //lower body filter
        if($bodyPartLowerFilter == true)
        {
            if($moreFilters == true)
                $sql= $sql." OR ";
            else
                $sql= $sql."(";

            $moreFilters= true;
            $sql= $sql."bodyPartLower='". $bodyPartLowerFilter ."'";
        }

        //abdominal body filter
        if($bodyPartAbdominalFilter == true)
        {
            if($moreFilters == true)
                $sql= $sql." OR ";
            else
                $sql= $sql."(";

            $moreFilters= true;
            $sql= $sql."bodyPartAbdominal='". $bodyPartAbdominalFilter ."'";
        }

        //close ()
        if($moreFilters == true)
            $sql= $sql.")";

        //difficulty filter
        if($difficultyFilter != null)
        {
            if($moreFilters == true)
                $sql= $sql." AND ";

            $sql= $sql."difficulty='". $difficultyFilter ."'";
        }



        return $sql;
    }

    //TODO implement pagination
    public static function getExercises($bodyPartUpperFilter, $bodyPartLowerFilter, $bodyPartAbdominalFilter, $difficultyFilter)
    {
        // Create connection
        $conn = new mysqli(DB_SERVER_NAME, DB_USERNAME, DB_PASSWORD, DB_NAME);
        // Check connection
        if ($conn->connect_error)
        {
            //echo("Connection failed: " . $conn->connect_error);
            $conn->close();
            return false;
        } 

        $sql= Fitness::prepareFitnessQuery($bodyPartUpperFilter, $bodyPartLowerFilter, $bodyPartAbdominalFilter, $difficultyFilter);
        //echo($sql."<br><br>");    

        $result = $conn->query($sql);

        $conn->close();

        if(!$result)
            return false;


        if ($result->num_rows > 0)
        {

            $exerciseArray= [];

            //save results into $exerciseArray
            while($row = $result->fetch_assoc())
            {
                $exercise = new Exercise(
                    $row['id'],
                    $row['title'],
                    $row['instructions'],
                    $row['isometry'],
                    $row['repetition'],
                    $row['series'],
                    $row['restTimeMilliseconds'],
                    $row['bodyPartUpper'],
                    $row['bodyPartLower'],
                    $row['bodyPartAbdominal'],
                    $row['difficulty']
                );

                array_push($exerciseArray, $exercise);
            }

            //            print_r($exerciseArray);
            //echo("</br></br> decodifica: ".json_encode($exerciseArray));
            return json_encode($exerciseArray);

        }
        else
        {
            //echo "no results";
            return false;
        }
    }


    public static function addExercise($exerciseJSONstring)
    {
        $exercise= json_decode($exerciseJSONstring);

        // Create connection
        $conn = new mysqli(DB_SERVER_NAME, DB_USERNAME, DB_PASSWORD, DB_NAME);
        // Check connection
        if ($conn->connect_error)
        {
            echo("Connection failed: " . $conn->connect_error);
            $conn->close();
            return false;
        } 

        $sql = "INSERT INTO fitness (image, title, instructions, isometry, repetition, series, restTimeMilliseconds, bodyPartUpper, bodyPartLower, bodyPartAbdominal, difficulty)"
            . " VALUES "
            . "(?,"
            . " '". $exercise->title ."',"
            . " '". json_encode($exercise->instructions) ."',"
            . " '". $exercise->isometry ."',"
            . " '". $exercise->repetition ."',"
            . " '". $exercise->series ."',"
            . " '". $exercise->restTimeMilliseconds ."',"
            . " '". $exercise->bodyPartUpper ."',"
            . " '". $exercise->bodyPartLower ."',"
            . " '". $exercise->bodyPartAbdominal ."',"
            . " '". $exercise->difficulty ."'"
            . ")";

        $stnt= $conn->prepare($sql);

        //send image as BLOB
        $null = NULL;
        $stnt->bind_param("b", $null);
        $stnt->send_long_data(0, file_get_contents($exercise->image));

        //execute query
        $result= $stnt->execute();

        $conn->close();

        if(!$result)
        {
            echo('There was an error running the query [' . $conn->error . ']');
            return false;
        }
        else
            return true;

    }
}



class Contact{

    public $contactName;
    public $telephoneNumber;
    public $status;

    public function Contact($contactName, $telNumber, $status)
    {
        $this->contactName = $contactName;
        $this->telephoneNumber= $telNumber;
        $this->status= $status;
    }
}

/*static class that manage all user contacts*/
class UserContacts{


    public static function getContacts($userID)
    {
        // Create connection
        $conn = new mysqli(DB_SERVER_NAME, DB_USERNAME, DB_PASSWORD, DB_NAME);
        // Check connection
        if ($conn->connect_error)
        {
            //echo("Connection failed: " . $conn->connect_error);
            $conn->close();
            return false;
        } 

        $sql= "SELECT * FROM usercontacts WHERE userID='". $userID ."'";
        //echo($sql."<br><br>");    

        $result = $conn->query($sql);

        $conn->close();

        if(!$result)
            return false;


        if ($result->num_rows > 0)
        {

            $contactsArray= [];

            //save results into $exerciseArray
            while($row = $result->fetch_assoc())
            {
                $contact = new Contact(
                    $row['contactName'],
                    $row['telephoneNumber'],
                    $row['status']
                );

                array_push($contactsArray, $contact);

            }

            //print_r($contactsArray);
            //            echo("</br></br> decodifica: ".json_encode($contactsArray));
            //            return json_encode($contactsArray);
            return $contactsArray;

        }
        else
        {
            //echo "no results";
            return false;
        }
    }

    public static function addContact($userID, $contactName, $phone)
    {
        // Create connection
        $conn = new mysqli(DB_SERVER_NAME, DB_USERNAME, DB_PASSWORD, DB_NAME);
        // Check connection
        if ($conn->connect_error)
        {
            //            echo("Connection failed: " . $conn->connect_error);
            $conn->close();
            return false;
        } 

        $sql = "INSERT INTO usercontacts (userID, contactName, telephoneNumber, status)"
            . " VALUES "
            . "('". $userID ."',"
            . " '". $contactName ."',"
            . " '". $phone ."',"
            . " 'offline'"
            . ")";

        $result= $conn->query($sql);

        $conn->close();

        if(!$result)
        {
            //echo('There was an error running the query [' . $conn->error . ']');
            return false;
        }
        else
            return true;

    }

    public static function deleteContact($userID, $contactName)
    {
        // Create connection
        $conn = new mysqli(DB_SERVER_NAME, DB_USERNAME, DB_PASSWORD, DB_NAME);
        // Check connection
        if ($conn->connect_error)
        {
            //            echo("Connection failed: " . $conn->connect_error);
            $conn->close();
            return false;
        } 

        $sql = "DELETE FROM usercontacts WHERE userID='". $userID ."' AND contactName='". $contactName ."'";

        $result= $conn->query($sql);

        $conn->close();

        if(!$result)
        {
            //echo('There was an error running the query [' . $conn->error . ']');
            return false;
        }
        else
            return true;

    }
}
/**************************************************************************************************************************************/

/*converts JS bool to PHP bool*/
function JStoPHPbool($value)
{
    if($value === "true")
        return TRUE;
    else
        return FALSE;
}



?>