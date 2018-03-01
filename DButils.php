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
    
    public function addEventToDB()
    {
        
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


/*class that represent a exercise object (does not include the image field, see getImage() method)
 * 
 * 
 * ecercise JS object:
 *  var ex3= {
        image: 'img/ex3.gif',
        title: 'Wall chair',
        instructions: [
            'Put yourself in a "sit" position, leaning to the wall',
            'Keep the position, breath regulary'
        ],
        isometry: true,
        repetition: 30,
        series: 3,
        restTimeMilliseconds: 6000,
                bodyPartUpper: false,
                bodyPartLower: true,
                bodyPartAbdominal: false,
                difficulty: 'easy'
    };
 *  */
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


/*class that represent a recipe object (does not include the image field, see getImage() method)
 * 
 * 
 * recipe JS object:
 * var recipe5= {
        image: 'img/food5.jpg',
        title: 'Spaghetti with tomatoes and basil',
        ingredientList: [
            {
                ingredient: 'spaghetti',
                quantity: '100gr'
            },
            {
                ingredient: 'basil',
                quantity: '10gr'
            },
            {
                ingredient: 'tomatoe',
                quantity: '2'
            }
        ],
        foodTypeList: [
            'snack'
        ],
        allergenList: [
            'gluten'
        ],
        instructions: 'Will be added soon!',
        prepareTime: 6000,
        kcal: 300
    };
 * 
 *  */
class Recipe
{
    public $id;
    public $title;
    public $ingredientList;
    public $foodTypeList;
    public $allergenList;
    public $instructions;
    public $prepareTime;
    public $kcal;
    
    public function Recipe($id, $title, $ingredientList, $foodTypeList, $allergenList, $instructions, $prepareTime, $kcal)
    {
        $this->id= $id;
        $this->title= $title;
        $this->ingredientList= json_decode($ingredientList);
        $this->foodTypeList= json_decode($foodTypeList);
        $this->allergenList= json_decode($allergenList);
        $this->instructions= $instructions;
        $this->prepareTime= $prepareTime;
        $this->kcal= $kcal;
    }
    
    //return base64 string of the selected image
    public static function getImage($recipeID)
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

  
	$stmt = $conn->prepare("SELECT image FROM diet WHERE id='". $recipeID ."'"); 

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


/*static class that manage diet table*/
class Diet{
    
    
    private static function prepareDietQuery($minKcal, $maxKcal)
    {
        $sql= "SELECT * FROM diet";
        
        //if all filters are null, return;
        if($minKcal == null && $maxKcal == null)
            return $sql;
        
        $sql= $sql." WHERE ";
        
        $moreFilters= false;
        
        //minKcal filter
        if($minKcal != null && $minKcal >= 0)
        {
            $moreFilters= true;
            $sql= $sql."Kcal>='". $minKcal ."'";
        }
        
        //maxKcal filter
        if($maxKcal != null && $maxKcal >= 0)
        {
            if($moreFilters == true)
                $sql= $sql." AND ";
            
            $moreFilters= true;
            $sql= $sql."Kcal<='". $maxKcal ."'";
        }
        
            
        return $sql;
    }
    
    //TODO implement pagination
    public static function getRecipes($minKcal, $maxKcal, $foodTypeList, $allergenList)
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
        
        $sql= Diet::prepareDietQuery($minKcal, $maxKcal);
        //echo($sql."<br><br>");    
        
        $result = $conn->query($sql);

        $conn->close();
        
        if(!$result)
            return false;

        
        if ($result->num_rows > 0)
        {
            
            $recipeArray= [];
            
            //save results into $exerciseArray
            while($row = $result->fetch_assoc())
            {
                $foodTypeFlag= true;
                $notAllergenFlag= true;
                
                
                $recipe = new Recipe(
                        $row['id'],
                        $row['title'],
                        $row['ingredientList'],
                        $row['foodTypeList'],
                        $row['allergenList'],
                        $row['instructions'],
                        $row['prepareTime'],
                        $row['kcal']
                        );
                
                //check on food type
                if($foodTypeList != null && count($foodTypeList) > 0)
                {
                    $foodTypeFlag= false;
                    
                    foreach($foodTypeList as $selectedFoodType)
                    {
                        if(in_array($selectedFoodType, $recipe->foodTypeList))
                        {
                            $foodTypeFlag= true;
                            break;
                        }
                    }
                }
                
                //check on allergens
                if($allergenList != null && count($allergenList) > 0)
                {
                    
                    foreach($allergenList as $selectedAllergen)
                    {
                        if(in_array($selectedAllergen, $recipe->allergenList))
                            $notAllergenFlag= false;
                    }
                }
                
                
                if($foodTypeFlag && $notAllergenFlag)
                {
                    //echo('prendo ricetta: '. $recipe->title .'<br>');
                    array_push($recipeArray, $recipe);
                }
                    
            }
            
            //print_r($recipeArray);
            //echo("</br></br> decodifica: ".json_encode($exerciseArray));
            return json_encode($recipeArray);
            
        }
        else
        {
            //echo "no results";
            return false;
        }
    }
    
    
    /*does not check the validity of all data! Insert valid recipe data*/
    public static function addRecipe($recipeJSONstring)
    {
        $recipe= json_decode($recipeJSONstring);
        
        // Create connection
        $conn = new mysqli(DB_SERVER_NAME, DB_USERNAME, DB_PASSWORD, DB_NAME);
        // Check connection
        if ($conn->connect_error)
        {
//            echo("Connection failed: " . $conn->connect_error);
            $conn->close();
            return false;
        } 
        
        $sql = "INSERT INTO diet (image, title, ingredientList, foodTypeList, allergenList, instructions, prepareTime, kcal)"
                . " VALUES "
                . "(?,"
                . " '". $recipe->title ."',"
                . " '". json_encode($recipe->ingredientList) ."',"
                . " '". json_encode($recipe->foodTypeList) ."',"
                . " '". json_encode($recipe->allergenList) ."',"
                . " '". $recipe->instructions ."',"
                . " '". $recipe->prepareTime ."',"
                . " '". $recipe->kcal ."'"
            . ")";
        
        $stnt= $conn->prepare($sql);
        
        //send image as BLOB
        $null = NULL;
        $stnt->bind_param("b", $null);
        $stnt->send_long_data(0, file_get_contents($recipe->image));
        
        //execute query
        $result= $stnt->execute();
        
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


class Contact{
    
    public $contactName;
    public $telephoneNumber;
    public $contactRelationship;
    public $status;
    
    public function Contact($contactName, $telNumber,$contactRelationship, $status)
    {
        $this->contactName = $contactName;
        $this->telephoneNumber = $telNumber;
        $this->contactRelationship = $contactRelationship;
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
                        $row['contactRelationship'],
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
    
    public static function addContact($userID, $contactName, $phone, $contactRelationship)
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
        
        $sql = "INSERT INTO usercontacts (userID, contactName, telephoneNumber,contactRelationship, status)"
                . " VALUES "
                . "('". $userID ."',"
                . " '". $contactName ."',"
                . " '". $phone ."',"
                . " '". $contactRelationship ."',"
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