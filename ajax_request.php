<?php

/* 
 * To change this license header, choose License Headers in Project Properties.
 * To change this template file, choose Tools | Templates
 * and open the template in the editor.
 */
include 'miscLib.php';
include 'DButils.php';

//TODO check if user is logged in
session_start();


//*****************************************************************************************************************************************************************************
//                                  AVAILABLE FUNCTIONS
//                          (CONSTANT , javascript_function_name)


/*******    WEIGHT   ********/
/* Function to retreive the data in a format compatible with flot
 * and draw the weight plot using function drawWeightChart2(..)
 * @returns {array[]}
 */
define("GET_WEIGHT_DATA", "getWeightPlotData"); 

/* NOT TESTED
 * add weight data directly on database
 * @param {number} timestamp
 * @param {number} weight
 * @returns {undefined}
 */
define("ADD_WEIGHT_DATA", "addWeightData"); 


/*******    FIND   ********/
/* Function to retreive the data in a format compatible with flot
 * and draw the score plot using function drawScoreChart2(..)
 * @returns {array[]}
 */
define("GET_FIND_DATA", "getFindPlotData"); 

/* NOT TESTED
 * add find data directly on database
 * @param {number} timestamp
 * @param {number} score
 * @returns {undefined}
 */
define("ADD_FIND_DATA", "addFindData"); 

/*******    SURVEY   ********/
/* add survey data on database
 * 
 * @param {type} weight
 * @param {type} height
 * @param {type} age
 * @param {type} motivation
 * @returns {undefined}
 */
define("ADD_SURVEY_DATA", "addSurveyData");


/*************  PLAN  **************/
/* set week goals on DB
 * 
 * @param {type} walkGoal
 * @param {type} exerciseGoal
 * @param {type} meetGoal
 * @returns {undefined}
 */
define("SET_WEEK_GOALS", "setWeekGoals");

/* add new event to DB
 * 
 * @param {type} event
 * @returns {null}
 */
define("ADD_EVENT", "addEvent");

/* add new event to DB
 * 
 * @param {string} event in JSON string format
 * @returns {null}
 */
define("GET_EVENTS", "getEvents");

/* save all the calendar events to DB (should use this at window.onbeforeunload)
 * 
 * @param {calendar} fullcalendar object
 * @returns {null}
 */
define("SAVE_EVENT_LIST", "saveEventList");




/*************  FITNESS  **************/

/* add exercise record on DB
 * 
 * @param {type} exercise obj
 * @returns {null}
 */
define("ADD_FITNESS_EXERCISE", "addFitnessExercise");

/* get exercise information using chosen filters
 * 
 * @param {function} callback
 * @param {bool} bodyPartUpperFilter
 * @param {bool} bodyPartLowerFilter
 * @param {bool} bodyPartAbdominalFilter
 * @param {string} difficultyFilter "easy", "medium" or "hard"
 * @returns {string} array of exercise objects, false if something goes wrong
 */
define("GET_FITNESS_EXERCISE", "getFitnessExercise");

/* get the image related to the exercise and print it on the screen
 * 
 * @param {function} callback 
 * @param {object} HTMLelement
 * @param {int} exerciseID
 * @returns {null}
 */
define("GET_FITNESS_EXERCISE_IMAGE", "getExerciseImage");





/*************  DIET  **************/

/* insert diet recipe in DB
 * 
 * @param {type} recipe
 * @returns {null}
 */
define("ADD_DIET_RECIPE", "addDietRecipe");

/* get all the recipes with filters
 * 
 * @param {function} callback
 * @param {int} minKcal
 * @param {int} maxKcal
 * @param {array} foodTypeList
 * @param {array} allergenList
 * @returns {undefined}
 */
define("GET_DIET_RECIPE", "getDietRecipes");

/* get the image related to the recipe and print it on the screen (using a callback)
 * 
 * @param {function} callback 
 * @param {object} HTMLelement
 * @param {int} recipeID
 * @returns {null}
 */
define("GET_DIET_RECIPE_IMAGE", "getRecipeImage");



/*************  CONTACTS  **************/
/* function that add a contact to DB
 * 
 * @param {string} contactName
 * @param {string} phoneNumber
 * @param {function} confirmCallback
 * @param {function} errorCallback
 * @returns {null}
 */
define("ADD_USER_CONTACT", "addUserContact");

/* delete contact on DB
 * 
 * @param {string} contactName
 * @param {function} confirmCallback
 * @param {function} errorCallback
 * @returns {undefined}
 */
define("DELETE_USER_CONTACT", "deleteUserContact");




/*************  OTHERS  **************/
/*
 * 
 * @returns {string} string of the content language requested by client browser
 */
define("GET_USER_LANGUAGE", "getUserLanguage");

/*  function to retreive the user data passing throw the login operation
 * 
 * @param {string} username
 * @param {string} password
 * @param {function(userData)} callback
 * @returns {undefined}
 */
define("GET_USER_DATA", "getUserData");
//************************************************************************************************************************************************************************************

    header('Content-Type: application/json');

    $aResult = array();

    if( !isset($_POST['functionname']) ) { $aResult['error'] = 'No function name!'; }

    //no function arguments
    if( !isset($_POST['arguments']) ) {
        switch($_POST['functionname'])
        {
            case GET_WEIGHT_DATA:
                break;
            
            case GET_FIND_DATA:
                break;
            
            case GET_EVENTS:
                break;
            
            case GET_USER_LANGUAGE:
                break;
            
            default:
                $aResult['error'] = 'No function arguments!';
                break;
        }
        
    }

    if( !isset($aResult['error']) ) {

        switch($_POST['functionname']) {
            //function that retreive weight data for the weight plot
            case GET_WEIGHT_DATA:
                   $weightdata= new WeightData((string)$_SESSION['personAAL_user']);
//                   $weightdata= new WeightData("ajeiie");
                   $aResult['result']= $weightdata->getData();
               break;
           
           //function that ADD weight data on DB
            case ADD_WEIGHT_DATA:
                if( !is_array($_POST['arguments']) || (count($_POST['arguments']) < 2) )
                   $aResult['error'] = 'Error in arguments!';
                else
                {
                    $weightdata= new WeightData($_SESSION['personAAL_user']);
                    $weightdata->addDataOnDB($_POST['arguments'][0], $_POST['arguments'][1]);

                    $aResult['result'] = true;
                }
                break;
                
            //function that retreive find score data for the score plot
            case GET_FIND_DATA:
                   $finddata= new FindData((string)$_SESSION['personAAL_user']);
                   $aResult['result']= $finddata->getData();
               break;
           
           //function that ADD find score data on DB
            case ADD_FIND_DATA:
                if( !is_array($_POST['arguments']) || (count($_POST['arguments']) < 2) )
                   $aResult['error'] = 'Error in arguments!';
                else
                {
                    $finddata= new FindData($_SESSION['personAAL_user']);
                    $finddata->addDataOnDB($_POST['arguments'][0], $_POST['arguments'][1]);
                    $aResult['result'] = true;
                }
                break;  
               
            //function that set survey data on DB
            case ADD_SURVEY_DATA:
                if( !is_array($_POST['arguments']) || (count($_POST['arguments']) < 4) )
                   $aResult['error'] = 'Error in arguments!';
                else
                {
                    $surveyinfo = new SurveyData($_SESSION['personAAL_user']);
                    $surveyinfo->setWeight($_POST['arguments'][0]);
                    $surveyinfo->setHeight($_POST['arguments'][1]);
                    $surveyinfo->setAge($_POST['arguments'][2]);
                    $surveyinfo->setMotivation($_POST['arguments'][3]);
                    
                    $aResult['result'] = true;
                }
                break;
                
            //function that set goals on DB
            case SET_WEEK_GOALS:
                if( !is_array($_POST['arguments']) || (count($_POST['arguments']) < 6) )
                   $aResult['error'] = 'Error in arguments!';
                else
                {
                    $plan = new Plan($_SESSION['personAAL_user']);
                    $plan->setGoals($_POST['arguments'][0], $_POST['arguments'][1], $_POST['arguments'][2], $_POST['arguments'][3], $_POST['arguments'][4], $_POST['arguments'][5]);

                    $aResult['result'] = true;
                }
                break;
            
            //function that add event on DB    
            case ADD_EVENT:
                if( !is_array($_POST['arguments']) || (count($_POST['arguments']) < 1) )
                   $aResult['error'] = 'Error in arguments!';
                else
                {
                    $plan = new Plan($_SESSION['personAAL_user']);
                    $plan->addEvent($_POST['arguments'][0]);

                    $aResult['result'] = json_decode($_POST['arguments'][0]);
                }
                break;
                
            //function that get the event list from DB    
            case GET_EVENTS:
                    $plan = new Plan($_SESSION['personAAL_user']);
                    
                    $aResult['result'] = $plan->getEvents();
                break;
               
            //function that save the event list on DB  
            case SAVE_EVENT_LIST:
                if( !is_array($_POST['arguments']) || (count($_POST['arguments']) < 1) )
                   $aResult['error'] = 'Error in arguments!';
                else
                {
                    $plan = new Plan($_SESSION['personAAL_user']);
                    $plan->saveEventList($_POST['arguments'][0]);

                    $aResult['result'] = true;
                }
                break;
            
            case ADD_FITNESS_EXERCISE:
                if( !is_array($_POST['arguments']) || (count($_POST['arguments']) < 1) )
                   $aResult['error'] = 'Error in arguments!';
                else
                {
                    $aResult['result']= Fitness::addExercise($_POST['arguments'][0]);

//                    $aResult['result'] = $_POST['arguments'][0];
                }
                break;
            
            case GET_FITNESS_EXERCISE:
                if( !is_array($_POST['arguments']) || (count($_POST['arguments']) < 4) )
                   $aResult['error'] = 'Error in arguments!';
                else
                {
                    $aResult['result']= Fitness::getExercises(JStoPHPbool($_POST['arguments'][0]), JStoPHPbool($_POST['arguments'][1]), JStoPHPbool($_POST['arguments'][2]), $_POST['arguments'][3]);

                    //$aResult['result'] = "".$_POST['arguments'][0]. $_POST['arguments'][1]. $_POST['arguments'][2]. $_POST['arguments'][3];
                }
                break;
                
            case GET_FITNESS_EXERCISE_IMAGE:
                if( !is_array($_POST['arguments']) || (count($_POST['arguments']) < 1) )
                   $aResult['error'] = 'Error in arguments!';
                else
                {
                    $aResult['result']= Exercise::getImage($_POST['arguments'][0]);

                    //$aResult['result'] = "".$_POST['arguments'][0]. $_POST['arguments'][1]. $_POST['arguments'][2]. $_POST['arguments'][3];
                }
                break;
               
            case ADD_DIET_RECIPE:
                if( !is_array($_POST['arguments']) || (count($_POST['arguments']) < 1) )
                   $aResult['error'] = 'Error in arguments!';
                else
                {
                    $aResult['result']= Diet::addRecipe($_POST['arguments'][0]);

//                    $aResult['result'] = $_POST['arguments'][0];
                }
                break;    
                
            case GET_DIET_RECIPE:
                if( !is_array($_POST['arguments']) || (count($_POST['arguments']) < 4) )
                   $aResult['error'] = 'Error in arguments!';
                else
                {
                    $aResult['result']= Diet::getRecipes($_POST['arguments'][0], $_POST['arguments'][1], $_POST['arguments'][2], $_POST['arguments'][3]);
//                    $aResult['result'] = "".$_POST['arguments'][0]. $_POST['arguments'][1]. $_POST['arguments'][2]. $_POST['arguments'][3];
                }
                break;
                
            case GET_DIET_RECIPE_IMAGE:
                if( !is_array($_POST['arguments']) || (count($_POST['arguments']) < 1) )
                   $aResult['error'] = 'Error in arguments!';
                else
                {
                    $aResult['result']= Recipe::getImage($_POST['arguments'][0]);

                    //$aResult['result'] = "".$_POST['arguments'][0]. $_POST['arguments'][1]. $_POST['arguments'][2]. $_POST['arguments'][3];
                }
                break;
            
            case ADD_USER_CONTACT:
                if( !is_array($_POST['arguments']) || (count($_POST['arguments']) < 2) )
                   $aResult['error'] = 'Error in arguments!';
                else
                {
                    $aResult['result']= UserContacts::addContact($_SESSION['personAAL_user'], $_POST['arguments'][0], $_POST['arguments'][1]);

//                    $aResult['result'] = $_POST['arguments'][0];
                }
                break;     
	    
	    case DELETE_USER_CONTACT:
		if( !is_array($_POST['arguments']) || (count($_POST['arguments']) < 1) )
                   $aResult['error'] = 'Error in arguments!';
                else
                {
                    $aResult['result']= UserContacts::deleteContact($_SESSION['personAAL_user'], $_POST['arguments'][0]);
                }
                break; 
                
                
            case GET_USER_LANGUAGE:
                   $aResult['result']= substr($_SERVER['HTTP_ACCEPT_LANGUAGE'], 0, 2);
               break;
           
            case GET_USER_DATA:
                if( !is_array($_POST['arguments']) || (count($_POST['arguments']) < 2) )
                   $aResult['error'] = 'Error in arguments!';
                else
                {
                    $userData= login($_POST['arguments'][0], $_POST['arguments'][1], TRUE);
                    
                    //return the user data in JSON string format for JavaScript
                    $aResult['result'] = json_encode($userData);
                }
                break;
		
            default:
               $aResult['error'] = 'Not found function '.$_POST['functionname'].'!';
               break;
        }

    }

    echo json_encode($aResult);
    
?>

