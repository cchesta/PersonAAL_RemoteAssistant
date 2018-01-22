<!DOCTYPE html>

<?php
    include 'miscLib.php';
    include 'DButils.php';
    
    
    //filter POST vars from html tags and delete whitespaces at start/end of the string
    foreach($_REQUEST as &$value)
    {
        $value= strip_tags($value);
        $value= trim($value);
    }
        
    
//    if(!isset($_REQUEST['username']) || strlen($_REQUEST['username']) <= 0){echo('errore username<br>');}
//    if(!isset($_REQUEST['password']) || strlen($_REQUEST['password']) <= 0){echo('errore password<br>');}
//    if(!isset($_REQUEST['name']) || strlen($_REQUEST['name']) <= 0){echo('errore name<br>');}
//    if(!isset($_REQUEST['surname']) || strlen($_REQUEST['surname']) <= 0){echo('errore surname<br>');}
//    if(!isset($_REQUEST['birth_day']) || !is_numeric($_REQUEST['birth_day'])){echo('errore birth_day<br>');}
//    if(!isset($_REQUEST['birth_month']) || !is_numeric($_REQUEST['birth_month'])){echo('errore birth_month<br>');}
//    if(!isset($_REQUEST['birth_year']) || !is_numeric($_REQUEST['birth_year'])){echo('errore birth_year<br>');}
//    if(!isset($_REQUEST['gender']) || ($_REQUEST['gender'] != 'male' && $_REQUEST['gender'] != 'female')){echo('errore gender<br>');}
//    if(!isset($_REQUEST['state']) || strlen($_REQUEST['state']) <= 0){echo('errore state<br>');}
//    if(!isset($_REQUEST['city']) || strlen($_REQUEST['city']) <= 0){echo('errore city<br>');}
//    if(!isset($_REQUEST['cap']) || strlen($_REQUEST['cap']) <= 0){echo('errore cap<br>');}
//    if(!isset($_REQUEST['address']) || strlen($_REQUEST['address']) <= 0){echo('errore address<br>');}
        
    //check validity of all registration fields
    if( !isset($_REQUEST['username']) || strlen($_REQUEST['username']) <= 0 ||
        !isset($_REQUEST['password']) || strlen($_REQUEST['password']) <= 0 ||
        !isset($_REQUEST['name']) || strlen($_REQUEST['name']) <= 0 ||
        !isset($_REQUEST['surname']) || strlen($_REQUEST['surname']) <= 0 ||
        !isset($_REQUEST['birth_day']) || !is_numeric($_REQUEST['birth_day']) || 
        !isset($_REQUEST['birth_month']) || !is_numeric($_REQUEST['birth_month']) || 
        !isset($_REQUEST['birth_year']) || !is_numeric($_REQUEST['birth_year']) || 
        !isset($_REQUEST['gender']) || ($_REQUEST['gender'] != 'male' && $_REQUEST['gender'] != 'female') ||
        !isset($_REQUEST['state']) || strlen($_REQUEST['state']) <= 0 ||
        !isset($_REQUEST['city']) || strlen($_REQUEST['city']) <= 0 ||
        !isset($_REQUEST['cap']) || strlen($_REQUEST['cap']) <= 0 ||
        !isset($_REQUEST['address']) || strlen($_REQUEST['address']) <= 0 )
    {
        //invalid information
        //TODO handle error
        //echo("invalid information");
    }
    else
    {
        //TODO better checks on date
        //last check on date values
        $actualYear= date('Y');
        if( $_REQUEST['birth_day'] < 1 || $_REQUEST['birth_day'] > 31 ||
            $_REQUEST['birth_month'] < 1 || $_REQUEST['birth_month'] > 12 ||
            $_REQUEST['birth_year'] < 1900 || $_REQUEST['birth_year'] > $actualYear)
        {
            //invalid birth date
            //TODO handle error
            //echo("invalid date");
        }
        else
        {
            //all information are valid
//            echo($_REQUEST['username']);
//            echo($_REQUEST['password']);
            
            
            //create timestamp string
            $date= new DateTime();
            $date->setDate($_REQUEST['birth_year'], $_REQUEST['birth_month'], $_REQUEST['birth_day']);
            $birthDate = $date->format('Y-m-d');
            
            //register user on DB
            $result= register(   $_REQUEST['username'], $_REQUEST['password'], $_REQUEST['name'], $_REQUEST['surname'],
                        $birthDate, $_REQUEST['gender'], $_REQUEST['state'], $_REQUEST['city'], $_REQUEST['cap'], $_REQUEST['address']);
            
//            echo($result);
            if($result === -1)
                sendDataToPage("login.php", "notify", REGISTRATION_ERROR, TRUE);
            else if($result === TRUE)
                sendDataToPage("login.php", "notify", REGISTRATION_SUCCESSFUL, TRUE);
        }
    }
        
            
?>






<html>
    <head>
        <title>PersonAAL</title>
        <meta charset="UTF-8">
        <meta name="viewport" content="width=device-width, initial-scale=1.0">
        <meta http-equiv="Cache-Control" content="no-cache, no-store, must-revalidate" />
        <meta http-equiv="Pragma" content="no-cache" />
        <meta http-equiv="Expires" content="0" />
        
        <link rel="stylesheet" href="https://fonts.googleapis.com/icon?family=Material+Icons">
        
      
        
        
        <!--  UI CSS & JS-->
        
        <link rel="stylesheet" href="css/material.css">
        <script src="js/plugins/material_design/material.min.js"></script>
        <script src="js/plugins/Jquery/jquery-1.9.1.min.js"></script>
        
        
        <link rel="stylesheet" href="css/custom.css">
        
        <script src='js/plugins/Jquery/jquery.ui.touch-punch.min.js'></script>
        
        <script>
        
        function validateRegistration()
        {
            var validForm= true;
            
            var username= $('#username').val();
            var password= $('#password').val();
            var name= $('#name').val();
            var surname= $('#surname').val();
            var birth_day= $('#birth_day').val();
            var birth_month= $('#birth_month').val();
            var birth_year= $('#birth_year').val();
            var male= $('#gender-male').prop('checked');
            var female= $('#gender-female').prop('checked');
            var genderTitle= $('#gender_title');
            var state= $('#state').val();
            var city= $('#city').val();
            var cap= $('#cap').val();
            var address= $('#address').val();
            
            //debug print
            console.log(username);
            console.log(password);
            console.log(name);
            console.log(surname);
            console.log(birth_day);
            console.log(birth_month);
            console.log(birth_year);
            console.log(male);
            console.log(female);
            console.log(state);
            console.log(city);
            console.log(cap);
            console.log(address);
            
            //TODO controls on username and password length (for preliminary user tests password and username must be longer than 0)
            //check all form values
            if(username == null || !isValidCredential(username))
            {
                //console.log($('#username').parent().children('.mdl-textfield__label'));
                $('#username').parent().children('.mdl-textfield__label').css('color', 'red');
                validForm= false;
            }
            else
                $('#username').parent().children('.mdl-textfield__label').css('color', 'rgb(63,81,181)');
            
            if(password == null || !isValidCredential(password))
            {
                //console.log($('#password').parent().children('.mdl-textfield__label'));
                $('#password').parent().children('.mdl-textfield__label').css('color', 'red');
                validForm= false;
            }
            else
                $('#password').parent().children('.mdl-textfield__label').css('color', 'rgb(63,81,181)');
            
            if(name == null || name.length <= 0)
            {
                //console.log($('#password').parent().children('.mdl-textfield__label'));
                $('#name').parent().children('.mdl-textfield__label').css('color', 'red');
                validForm= false;
            }
            else
                $('#name').parent().children('.mdl-textfield__label').css('color', 'rgb(63,81,181)');
            
            if(surname == null || surname.length <= 0)
            {
                //console.log($('#password').parent().children('.mdl-textfield__label'));
                $('#surname').parent().children('.mdl-textfield__label').css('color', 'red');
                validForm= false;
            }
            else
                $('#surname').parent().children('.mdl-textfield__label').css('color', 'rgb(63,81,181)');
            
            //TODO better controls on date
            if(birth_day == null || birth_day.length <= 0 || $.isNumeric(birth_day) === false ||  birth_day < 1 || birth_day > 31)
            {
                //console.log($('#password').parent().children('.mdl-textfield__label'));
                $('#birth_day').parent().children('.mdl-textfield__label').css('color', 'red');
                validForm= false;
            }
            else
                $('#birth_day').parent().children('.mdl-textfield__label').css('color', 'rgb(63,81,181)');
            
            if(birth_month == null || birth_month.length <= 0 || $.isNumeric(birth_month) === false ||  birth_month < 1 || birth_month > 12)
            {
                //console.log($('#password').parent().children('.mdl-textfield__label'));
                $('#birth_month').parent().children('.mdl-textfield__label').css('color', 'red');
                validForm= false;
            }
            else
                $('#birth_month').parent().children('.mdl-textfield__label').css('color', 'rgb(63,81,181)');
            
            var actualYear= new Date().getFullYear();
            console.log(actualYear);
            if(birth_year == null || birth_year.length <= 0 || $.isNumeric(birth_year) === false || birth_year < 1900 || birth_year > actualYear)
            {
                //console.log($('#password').parent().children('.mdl-textfield__label'));
                $('#birth_year').parent().children('.mdl-textfield__label').css('color', 'red');
                validForm= false;
            }
            else
                $('#birth_year').parent().children('.mdl-textfield__label').css('color', 'rgb(63,81,181)');
            
            if(male === false && female === false)
            {
                genderTitle.css('color', 'red');
                validForm= false;
            }
            else
                genderTitle.css('color', 'rgb(63,81,181)');
            
            if(state == null || state.length <= 0)
            {
                //console.log($('#password').parent().children('.mdl-textfield__label'));
                $('#state').parent().children('.mdl-textfield__label').css('color', 'red');
                validForm= false;
            }
            else
                $('#state').parent().children('.mdl-textfield__label').css('color', 'rgb(63,81,181)');
            
            if(city == null || city.length <= 0)
            {
                //console.log($('#password').parent().children('.mdl-textfield__label'));
                $('#city').parent().children('.mdl-textfield__label').css('color', 'red');
                validForm= false;
            }
            else
                $('#city').parent().children('.mdl-textfield__label').css('color', 'rgb(63,81,181)');
            
            if(cap == null || cap.length <= 0)
            {
                //console.log($('#password').parent().children('.mdl-textfield__label'));
                $('#cap').parent().children('.mdl-textfield__label').css('color', 'red');
                validForm= false;
            }
            else
                $('#cap').parent().children('.mdl-textfield__label').css('color', 'rgb(63,81,181)');
            
            if(address == null || address.length <= 0)
            {
                //console.log($('#password').parent().children('.mdl-textfield__label'));
                $('#address').parent().children('.mdl-textfield__label').css('color', 'red');
                validForm= false;
            }
            else
                $('#address').parent().children('.mdl-textfield__label').css('color', 'rgb(63,81,181)');
                
            
            return validForm;
        }
        
        function isValidCredential(string)
        {
            return /^\w{2,16}$/.test(string);
        }
        </script>
    </head>
    
    
   <body>

        
        
        <!-- The drawer is always open in large screens. The header is always shown, even in small screens. -->
        <div class="mdl-layout mdl-js-layout mdl-layout--fixed-header">
            <header class="mdl-layout__header">
                <a class="mdl-layout-icon hide-desktop" href="login.php"><i class="material-icons">arrow_back</i></a>
                <div class="mdl-layout__header-row">
                    <span class="mdl-layout-title">PersonAAL</span>
                </div>
            </header>
            <main class="mdl-layout__content">
                <div class="page-content">
                <!-- Your content goes here -->
                <div class="mdl-grid">
                    
                
                    <div class="registration-card mdl-card mdl-shadow--4dp mdl-cell--3-offset-desktop mdl-cell--6-col-desktop mdl-cell--1-offset-tablet mdl-cell--6-col-tablet mdl-cell--4-col-phone">

                        <form role="form" action="register.php" method="POST" onsubmit="return validateRegistration();">

                            <div class="mdl-card__title">
                                <div class="mdl-card__title-text">
                                <?php echo(REGISTRATION_FORM_TITLE);?>  
                                </div>
                            </div>
                            <div class="mdl-card__supporting-text mdl-card--expand mdl-grid">
                                
                                <div class="mdl-cell mdl-cell--6-col-desktop mdl-cell--8-col-tablet mdl-cell--4-col-phone">
                                    <div class="card-choice-textfield">
                                        <div class="mdl-textfield mdl-js-textfield mdl-textfield--floating-label">
                                            <input class="mdl-textfield__input" type="text" name="username" id="username">
                                            <label class="mdl-textfield__label" for="username"><?php echo(REGISTRATION_FORM_USERNAME);?>*</label>
                                        </div>
                                    </div>
                                    <div class="card-choice-textfield">
                                        <div class="mdl-textfield mdl-js-textfield mdl-textfield--floating-label">
                                            <input class="mdl-textfield__input" type="password" name="password" id="password">
                                            <label class="mdl-textfield__label" for="password"><?php echo(REGISTRATION_FORM_PASSWORD);?>*</label>
                                        </div>
                                    </div>
                                    <div class="card-choice-textfield">
                                        <div class="mdl-textfield mdl-js-textfield mdl-textfield--floating-label">
                                            <input class="mdl-textfield__input" type="text" name="name" id="name">
                                            <label class="mdl-textfield__label" for="name"><?php echo(REGISTRATION_FORM_NAME);?>*</label>
                                        </div>
                                    </div>
                                    <div class="card-choice-textfield">
                                        <div class="mdl-textfield mdl-js-textfield mdl-textfield--floating-label">
                                            <input class="mdl-textfield__input" type="text" name="surname" id="surname">
                                            <label class="mdl-textfield__label" for="surname"><?php echo(REGISTRATION_FORM_SURNAME);?>*</label>
                                        </div>
                                    </div>
                                    <div class="birth-date-form">
                                        <div class="title">
                                            <?php echo(REGISTRATION_FORM_BIRTHDATE);?> *
                                        </div>
                                        <div class="form">
                                            <div class="mdl-textfield mdl-js-textfield mdl-textfield--floating-label">
                                                <input class="mdl-textfield__input" type="text" name="birth_day" id="birth_day" maxlength="2">
                                                <label class="mdl-textfield__label" for="birth_day"><?php echo(REGISTRATION_FORM_BIRTHDATE_DAY);?></label>
                                            </div>
                                            <div class="mdl-textfield mdl-js-textfield mdl-textfield--floating-label">
                                                <input class="mdl-textfield__input" type="text" name="birth_month" id="birth_month" maxlength="2">
                                                <label class="mdl-textfield__label" for="birth_month"><?php echo(REGISTRATION_FORM_BIRTHDATE_MONTH);?></label>
                                            </div>
                                            <div class="mdl-textfield mdl-js-textfield mdl-textfield--floating-label year">
                                                <input class="mdl-textfield__input" type="text" name="birth_year" id="birth_year" maxlength="4">
                                                <label class="mdl-textfield__label" for="birth_year"><?php echo(REGISTRATION_FORM_BIRTHDATE_YEAR);?></label>
                                            </div>
                                        </div>
                                    </div>
                                    <div class="card-choice-group">
                                        <div class="card-group-label" id="gender_title"><?php echo(REGISTRATION_FORM_GENDER);?> *</div>
                                        <label class="mdl-radio mdl-js-radio mdl-js-ripple-effect first-radio" for="gender-male">
                                            <input type="radio" id="gender-male" class="mdl-radio__button" name="gender" value="male">
                                            <span class="mdl-radio__label"><?php echo(REGISTRATION_FORM_GENDER_MALE);?></span>
                                        </label>
                                        <label class="mdl-radio mdl-js-radio mdl-js-ripple-effect" for="gender-female">
                                            <input type="radio" id="gender-female" class="mdl-radio__button" name="gender" value="female">
                                            <span class="mdl-radio__label"><?php echo(REGISTRATION_FORM_GENDER_FEMALE);?></span>
                                        </label>
                                    </div>
                                </div>
                                <div class="mdl-cell mdl-cell--6-col-desktop mdl-cell--8-col-tablet mdl-cell--4-col-phone">
                                    <div class="card-choice-textfield">
                                        <div class="mdl-textfield mdl-js-textfield mdl-textfield--floating-label">
                                            <input class="mdl-textfield__input" type="text" name="state" id="state">
                                            <label class="mdl-textfield__label" for="state"><?php echo(REGISTRATION_FORM_STATE);?>*</label>
                                        </div>
                                    </div>
                                    <div class="card-choice-textfield">
                                        <div class="mdl-textfield mdl-js-textfield mdl-textfield--floating-label">
                                            <input class="mdl-textfield__input" type="text" name="city" id="city">
                                            <label class="mdl-textfield__label" for="city"><?php echo(REGISTRATION_FORM_CITY);?>*</label>
                                        </div>
                                    </div>
                                    <div class="card-choice-textfield">
                                        <div class="mdl-textfield mdl-js-textfield mdl-textfield--floating-label">
                                            <input class="mdl-textfield__input" type="text" name="cap" id="cap">
                                            <label class="mdl-textfield__label" for="cap"><?php echo(REGISTRATION_FORM_POSTALCODE);?>*</label>
                                        </div>
                                    </div>
                                    <div class="card-choice-textfield">
                                        <div class="mdl-textfield mdl-js-textfield mdl-textfield--floating-label">
                                            <input class="mdl-textfield__input" type="text" name="address" id="address">
                                            <label class="mdl-textfield__label" for="address"><?php echo(REGISTRATION_FORM_ADDRESS);?>*</label>
                                        </div>
                                    </div>

                                </div>
                                <div class="mdl-cell mdl-cell--12-col">
                                    <?php echo(REGISTRATION_FORM_INSTRUCTIONS
                                            .'<br>'
                                            . REGISTRATION_FORM_REGEX_INSTRUCTIONS);
                                    ?>
                                </div>
                            </div>
                            <div class="mdl-card__actions mdl-card--border">
                                <input class="mdl-button mdl-button--colored mdl-js-button mdl-js-ripple-effect" data-dismiss="modal" type="submit" name ="submit"
                                       value="<?php echo(CONFIRM_BUTTON);?>">
                            </div>
                        </form>
                    </div>
                
                </div>
                 
                </div>
                
            </main>
        </div>   

        

        
        
    </body>
    

</html>
