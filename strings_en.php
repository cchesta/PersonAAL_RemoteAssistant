<?php

// LOGIN PAGE
define("LOGIN_WELCOME", "Welcome!");
define("LOGIN_USERNAME_HINT", "Username");
define("LOGIN_PASSWORD_HINT", "Password");
define("LOGIN_LOGIN_BUTTON", "Login");

define("LOGIN_REGISTRATION_TEXT", "Not registered yet? Sign up here!");
define("DISABLED_COOKIE_MESSAGE","Disabled cookies.");
define("SESSION_EXPIRED_MESSAGE", "Session expired.");
define("WRONG_USERNAME_OR_PASSWORD_MESSAGE", "Wrong username or password.");
define("DB_CONNECTION_ERROR_MESSAGE", "Cannot connect to database.");
define("EMPTY_CREDENTIAL_MESSAGE", "Empty credentials.");
define("INVALID_CREDENTIAL_MESSAGE", "Invalid credentials.");
define("REGISTRATION_SUCCESSFUL_MESSAGE", "Registration successful, please log in.");
define("REGISTRATION_ERROR_MESSAGE", "Error: cannot complete registration (maybe already used username)");


//REGISTRATION PAGE
define("REGISTRATION_FORM_TITLE", "Sign up");
define("REGISTRATION_FORM_USERNAME", "Username");
define("REGISTRATION_FORM_PASSWORD", "Password");
define("REGISTRATION_FORM_NAME", "Name");
define("REGISTRATION_FORM_SURNAME", "Surname");
define("REGISTRATION_FORM_BIRTHDATE", "Birth date");
define("REGISTRATION_FORM_BIRTHDATE_DAY", "Day");
define("REGISTRATION_FORM_BIRTHDATE_MONTH", "Month");
define("REGISTRATION_FORM_BIRTHDATE_YEAR", "Year");
define("REGISTRATION_FORM_GENDER", "Gender");
define("REGISTRATION_FORM_GENDER_MALE", "Male");
define("REGISTRATION_FORM_GENDER_FEMALE", "Female");
define("REGISTRATION_FORM_STATE", "State");
define("REGISTRATION_FORM_POSTALCODE", "Postal code");
define("REGISTRATION_FORM_CITY", "City");
define("REGISTRATION_FORM_ADDRESS", "Address");
define("REGISTRATION_FORM_INSTRUCTIONS", "* Obligatory");
define("REGISTRATION_FORM_REGEX_INSTRUCTIONS", "Username and password with at least 2 letters and/or digits  and/or '_'");
define("CONFIRM_BUTTON", "Confirm");

//LEFT MENU AND PAGE TITLES
define("MENU_TITLE", "Menu");
define("ENTRY_HOME", "Home");
define("ENTRY_HEALTH", "Health");
define("ENTRY_PLAN", "Plan");
define("ENTRY_FITNESS", "Fitness");
define("ENTRY_DIET", "Diet");
define("ENTRY_SERVICES", "Services");
define("ENTRY_PROFILE", "Profile");
define("ENTRY_CONTACTS", "Contacts");
define("ENTRY_LOGOUT", "Logout");


//INDEX PAGE
define("INDEX_SURVEYCARD_TITLE", "How do you feel today?");
define("INDEX_SURVEYCARD_TEXT", "Let us improve your life style answering some simple questions.");
define("INDEX_SURVEYCARD_BUTTON", "Go to survey");

define("INDEX_SURVEY_TITLE", "Help us improving your life!");
define("INDEX_SURVEY_QUESTION1", "Please enter your weight");
define("INDEX_SURVEY_HINT1", "Weight (kg)");
define("INDEX_SURVEY_QUESTION2", "Please enter your height");
define("INDEX_SURVEY_HINT2", "Height (cm)");
define("INDEX_SURVEY_QUESTION3", "Please enter your age");
define("INDEX_SURVEY_HINT3", "Age");
define("INDEX_SURVEY_QUESTION4", "What motivates you more to exercise?");
define("INDEX_SURVEY_MOTIVATION1", "Because I enjoy it and it makes me feel good");
define("INDEX_SURVEY_MOTIVATION2", "To maintain good health and prevent illness");
define("INDEX_SURVEY_MOTIVATION3", "To lose weight and improve my appearance");
define("INDEX_SURVEY_MOTIVATION4", "Because I enjoy competing and spend time with friends");
define("INDEX_SURVEY_QUESTION5", "Have you any difficulties at walking 400 meters?");
define("ANSWER_5a", "No or some difficulties");
define("ANSWER_5b", "A lot of difficulties or unable");
define("INDEX_SURVEY_QUESTION6", "Have you any difficulties at climbing up a flight of stairs?");
define("ANSWER_6a", "No or some difficulties");
define("ANSWER_6b", "A lot of difficulties or unable");
define("INDEX_SURVEY_QUESTION7", "During the last year, have you involuntarily lost more than 4.5 kg");
define("ANSWER_7a", "No");
define("ANSWER_7b", "Yes");
define("INDEX_SURVEY_QUESTION8", "How often in the last week did you feel than everything you did was an effort or that you could not get going?");
define("ANSWER_8a", "Rarely or sometimes (2 times or less/week)");
define("ANSWER_8b", "Often or almost always (3 or more times per week)");
define("INDEX_SURVEY_QUESTION9", "Which is your level of physical activity?");
define("ANSWER_9a", "Regular physical activity (at least 2-4 hours per week)");
define("ANSWER_9b", "None or mainly sedentary");

define("CANCEL_BUTTON", "Cancel");

define("INDEX_STEPSCARD_STEPS", "steps");
define("INDEX_STEPSCARD_STEPS_GOAL", "STEPS GOAL");
define("INDEX_STEPSCARD_EXERCISE", "min");
define("INDEX_STEPSCARD_EXERCISEGOAL", "EXERCISE GOAL");
define("STEPS_PERFORMED", "steps performed today");

define("INDEX_NEWS1_TITLE", "Today's news");
define("INDEX_NEWS2_TITLE", "Sport news");

define("INDEX_INFOCARD_TITLE", "INFO");

define("SEND_MESSAGE_BUTTON", "SEND MESSAGE");

define("WEIGHT_CARD_TITLE", "Weight");
define("HEART_CARD_TITLE", "Heart Rate");
define("BREATH_CARD_TITLE", "Respiration Rate");
define("BMI_CARD_TITLE", "BMI");
define("TEMPERATURE_CARD_TITLE", "Internal Temperature");
define("WEATHER_CARD_TITLE", "Weather Forecast");
define("MEDICATION_CARD_TITLE", "Medication Diary");
define("MEDICATION_PLANNED", "Medication");
define("MEDICATION_PLANNED_DOSAGE", "Dosage");
define("MEDICATION_PLANNED_TIME", "Time");


//HEALTH PAGE
define("HEALTH_WEIGHTPLOT_TITLE", "WEIGHT");
define("HEALTH_BMIPLOT_TITLE", "BMI");
define("HEALTH_SCOREPLOT_TITLE", "SCORE");

define("HEALTH_ECGPLOT_TITLE", "ECG");
define("HEALTH_HRPLOT_TITLE", "HEART RATE");
define("HEALTH_ACCPLOT_TITLE", "Accelerometer");
define("HEALTH_TEMPPLOT_TITLE", "TEMPERATURE");
define("HEALTH_BREATHPLOT_TITLE", "RESPIRATION RATE");

define("HEALTH_DATABUTTON_START", "START ACQUISITION");
define("HEALTH_DATABUTTON_STOP", "STOP ACQUISITION");

//NOT USED - SEE js/health.js
define("HEALTH_SNACKBAR_CONNECTING", "Connecting to BITalino...");
define("HEALTH_SNACKBAR_ERROR", "Error: check BITalino connection");
define("HEALTH_SNACKBAR_STOP", "Connecting to BITalino...");
define("HEALTH_WEIGHTPLOT_DATE_HINT", "Date"); 

//PLAN PAGE
define("PLAN_SETGOALS_TITLE", "Set weekly goals");
define("PLAN_SETGOALS_EXERCISE", "Exercise");
define("PLAN_SETGOALS_WALK", "Walk (steps)");
define("PLAN_SETGOALS_MEET", "Meet (persons)");
define("PLAN_SETGOALS_ATLEAST", "at least");
define("PLAN_SETGOALS_MORETHAN", "more than");

define("PLAN_SNACKBAR", "Set your goal first!");

define("PLAN_GOALS_TITLE", "Weekly goals");
define("PLAN_GOALS_EXERCISE", "Exercise");
define("PLAN_GOALS_WALK", "Walk");
define("PLAN_GOALS_MEET", "Meet");
define("PLAN_GOALS_MIN", "min");
define("PLAN_GOALS_STEPS", "steps");
define("PLAN_GOALS_PERSONS", "persons");
define("PLAN_GOALS_COOK", "cook");
define("PLAN_GOALS_MODIFY", "MODIFY");

define("PLAN_CALENDAR_TITLE", "Calendar");
define("PLAN_CALENDAR_INSTRUCTIONS", "Drag an event to schedule it for the future or to report what you have already done.");
define("PLAN_CALENDAR_EVENT_EXERCISE", "Exercise");
define("PLAN_CALENDAR_EVENT_WALK", "Walk");
define("PLAN_CALENDAR_EVENT_MEET", "Meet");
define("PLAN_CALENDAR_EVENT_COOK", "Cook");

define("PLAN_SETEVENT_HOUR", "Hour");
define("PLAN_SETEVENT_MIN", "min");

define("REMOVE_EVENT_BUTTON", "REMOVE EVENT");
define("DONE_BUTTON", "DONE");
define("UNDONE_BUTTON", "UNDONE");

//NEW
//invite a friend to join activity
define("INVITE_FRIEND_BUTTON", "INVITE FRIEND");
define("ACCEPT_INVITATION_BUTTON", "ACCEPT");
define("DECLINE_INVITATION_BUTTON", "DECLINE");

//set a start and end time for an activity in the plan
define("START_TIME", "Start");
define("END_TIME", "End");





//FITNESS PAGE
define("FITNESS_ALERT_TITLE", "Be careful and stop working out when you experience some pain!");
define("CLOSE_BUTTON", "CLOSE");

define("SEARCH_TITLE", "Search filters");
define("FITNESS_SEARCH_BODYPARTS", "Training body part");
define("FITNESS_SEARCH_BODYPARTS_UPPER", "Upper");
define("FITNESS_SEARCH_BODYPARTS_LOWER", "Lower");
define("FITNESS_SEARCH_BODYPARTS_ABDOMINAL", "Abdominal");
define("FITNESS_SEARCH_DIFFICULTY", "Difficulty");
define("FITNESS_SEARCH_DIFFICULTY_EASY", "easy");
define("FITNESS_SEARCH_DIFFICULTY_MEDIUM", "medium");
define("FITNESS_SEARCH_DIFFICULTY_HARD", "hard");
define("SEARCH_BUTTON", "SEARCH");

define("FITNESS_SHOWEXERCISE_BUTTON", "SHOW EXERCISE");
define("FITNESS_SHOWEXERCISE_TRAINEDPARTS", "Trained body parts");

define("SEARCHRESULT_NORESULTS", "No results");
define("SEARCHRESULT_TITLE", "Results for");
define("FITNESS_SEARCHRESULT_DIFFICULTY", "Difficulty");
define("FITNESS_SEARCHRESULT_BODYPARTS", "Body parts");


//DIET PAGE
define("DIET_SEARCH_FOODTYPE", "Food type");
define("DIET_SEARCH_FOODTYPE_PASTA", "pasta");
define("DIET_SEARCH_FOODTYPE_MEAT", "meat");
define("DIET_SEARCH_FOODTYPE_VEGAN", "vegan");
define("DIET_SEARCH_FOODTYPE_DESSERT", "dessert");
define("NUMBER_INPUT_ERROR", "Input is not a number!");
define("DIET_SEARCH_ALLERGIES", "Allergies/intolerances");
define("DIET_SEARCH_ALLERGIES_LACTOSE", "lactose");
define("DIET_SEARCH_ALLERGIES_GLUTEN", "gluten");

define("DIET_SNACKBAR", '"Max kcal" must be greater than "Min kcal"!');

define("DIET_SEARCHRESULT_FOODCATEGORY", "Food category");
define("DIET_SEARCHRESULT_ALLERGENES", "Allergenes");

define("SHOW_RECIPE_BUTTON", "SHOW RECIPE");

//SHOPPING PAGE
define("SERVICES_SHOPPING_TITLE", "Shopping");
define("SERVICES_SHOPPING_DRINKS", "Drinks");
define("SERVICES_SHOPPING_DRINKS_1", "Water");
define("SERVICES_SHOPPING_DRINKS_2", "Tea");
define("SERVICES_SHOPPING_DRINKS_3", "Milk");
define("SERVICES_SHOPPING_FROZENFOODS", "Frozen Foods");
define("SERVICES_SHOPPING_FROZENFOODS_1", "Pizza");
define("SERVICES_SHOPPING_FROZENFOODS_2", "Potatoes");
define("SERVICES_SHOPPING_FROZENFOODS_3", "Spinach");
define("SERVICES_SHOPPING_FROZENFOODS_4", "Ice cream");
define("SERVICES_SHOPPING_PASTA", "Pasta");
define("SERVICES_SHOPPING_MEAT", "Meat");
define("SERVICES_SHOPPING_MEAT_1", "Beef");
define("SERVICES_SHOPPING_MEAT_2", "Pig");
define("SERVICES_SHOPPING_MEAT_3", "Chicken");
define("DESELECT_BUTTON", "DESELECT ALL");
define("BUY_BUTTON", "BUY");
define("SERVICES_SHOPPING_CONFIRMED_TITLE", "Order confirmed");

define("SERVICES_SERVICES_TITLE", "Services");
define("SERVICES_SERVICES_ASSISTANCE", "Assistance");
define("SERVICES_SERVICES_ELECTRIC", "Electrical problem");
define("SERVICES_SERVICES_COMMUNICATION", "Communication problem");
define("SERVICES_SERVICES_REPAIRS", "General repairs");
define("SERVICES_SERVICES_CLEANING", "Cleaning service");
define("SERVICES_SERVICES_FORM_HINT_TITLE", "Title");
define("SERVICES_SERVICES_FORM_HINT_MESSAGE", "Message");
define("SEND_BUTTON", "SEND");

define("SERVICES_SNACKBAR_MESSAGE", "Message");
define("SERVICES_SNACKBAR_SENT", "sent!");
    

//PROFILE PAGE
define("PROFILE_PROFILECARD_TITLE", "My Info");
define("PROFILE_PROFILECARD_NAME", "Name");
define("PROFILE_PROFILECARD_SURNAME", "Surname");
define("PROFILE_PROFILECARD_BIRTHDATE", "Birth date");
define("PROFILE_PROFILECARD_GENDER", "Gender");
define("PROFILE_PROFILECARD_GENDER_MALE", "Male");
define("PROFILE_PROFILECARD_GENDER_FEMALE", "Female");
define("PROFILE_PROFILECARD_STATE", "State");
define("PROFILE_PROFILECARD_POSTALCODE", "Postal code");
define("PROFILE_PROFILECARD_CITY", "City");
define("PROFILE_PROFILECARD_ADDRESS", "Address");

define("PROFILE_INTERESTS_TITLE", "Interests");
define("PROFILE_ADDINTERESTS_TITLE", "Add interest");
define("PROFILE_ADDINTERESTS_SPORTS", "Sports");
define("PROFILE_ADDINTERESTS_PROGRAMS", "Programs");
define("PROFILE_ADDINTERESTS_OTHERS", "Others");
define("PROFILE_ADDINTERESTS_SPORTS_1", "Baseball");
define("PROFILE_ADDINTERESTS_SPORTS_2", "Basketball");
define("PROFILE_ADDINTERESTS_SPORTS_3", "Football");
define("PROFILE_ADDINTERESTS_SPORTS_4", "Swim");
define("PROFILE_ADDINTERESTS_SPORTS_5", "Tennis");
define("PROFILE_ADDINTERESTS_SPORTS_6", "Volleyball");
define("PROFILE_ADDINTERESTS_PROGRAMS_1", "Documentary");
define("PROFILE_ADDINTERESTS_PROGRAMS_2", "TV news");
define("PROFILE_ADDINTERESTS_PROGRAMS_3", "Talk show");
define("PROFILE_ADDINTERESTS_OTHERS_1", "Cinema");
define("PROFILE_ADDINTERESTS_OTHERS_2", "Cooking");
define("PROFILE_ADDINTERESTS_OTHERS_3", "Monuments");
define("PROFILE_ADDINTERESTS_OTHERS_4", "Museums");
define("PROFILE_ADDINTERESTS_OTHERS_5", "Theater");


//CONTACTS PAGE
define("CONTACTS_CONTACTSCARD_TITLE", "Contacts");
define("CONTACTS_CONTACTSCARD_HEADER_NAME", "Name");
define("CONTACTS_CONTACTSCARD_HEADER_STATUS", "Status");
define("CONTACTS_CONTACTSCARD_HEADER_EMAIL", "Email");
define("CONTACTS_CONTACTSCARD_HEADER_PHONE", "Phone");
define("CONTACTS_CONTACTSCARD_HEADER_ACTIONS", "Actions");
define("REMOVE_BUTTON", "REMOVE");

define("CONTACTS_CONTACTSCARD_STATUS_ONLINE", "Online");
define("CONTACTS_CONTACTSCARD_STATUS_OFFLINE", "Offline");
define("CONTACTS_CONTACTSCARD_STATUS_BUSY", "Busy");

define("CONTACTS_FORM_TITLE", "Add contact");
define("CONTACTS_FORM_NAME", "Name");
define("CONTACTS_FORM_EMAIL", "Email");
define("CONTACTS_FORM_PHONE", "Phone Number");
define("CONTACTS_FROM_RELATIONSHIP", "Relationship");
define("ADD_BUTTON", "ADD");

//types of relationships
define("CONTACTS_FROM_RELATIONSHIP_CLOSE_FAMILY", "Close Family");
define("CONTACTS_FROM_RELATIONSHIP_OTHER_FAMILY", "Other Family");
define("CONTACTS_FROM_RELATIONSHIP_FRIEND", "Friend");
define("CONTACTS_FROM_RELATIONSHIP_NEIGHBOUR", "Neighbour");





//TODO inserire traduzioni per il testo del modal mostrato in caso di heart rate troppo alta

?>