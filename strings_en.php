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
define("ENTRY_PROFILE", "Profile");
define("ENTRY_CONTACTS", "Contacts");
define("ENTRY_HUELIGHTS", "Hue Lights");
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
define("HOMETEMPERATURE_CARD_TITLE", "Home Temperature");
define("HOMEHUMIDITY_CARD_TITLE", "Home Humidity");
define("MOTION_CARD_TITLE", "Motion");
define("TEMPERATURE_CARD_TITLE", "Internal Temperature");
define("WEATHER_CARD_TITLE", "Weather Forecast");
define("MESSAGE_CARD_TITLE", "Messages");
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
define("PLAN_SETGOALS_EXERCISE", "Exercise (hours)");
define("PLAN_SETGOALS_WALK", "Walk (hours)");
define("PLAN_SETGOALS_MEET", "Meet (persons)");
define("PLAN_SETGOALS_ATLEAST", "at least");
define("PLAN_SETGOALS_MORETHAN", "more than");

define("PLAN_SNACKBAR", "Set your goal first!");

define("PLAN_GOALS_TITLE", "Weekly goals");
define("PLAN_GOALS_EXERCISE", "Exercise");
define("PLAN_GOALS_WALK", "Walk");
define("PLAN_GOALS_MEET", "Meet");
define("PLAN_GOALS_MIN", "min");
define("PLAN_GOALS_HOURS", "hours");
define("PLAN_GOALS_STEPS", "steps");
define("PLAN_GOALS_PERSONS", "persons");
define("PLAN_GOALS_COOK", "cook");
define("PLAN_GOALS_MODIFY", "MODIFY");

define("PLAN_CALENDAR_TITLE", "Calendar");
define("PLAN_CALENDAR_INSTRUCTIONS", "Click on the calendar to start scheduling an activity.");
define("PLAN_CALENDAR_EVENT_EXERCISE", "Exercise");
define("PLAN_CALENDAR_EVENT_WALK", "Walk");
define("PLAN_CALENDAR_EVENT_MEET", "Meet");
define("PLAN_CALENDAR_EVENT_COOK", "Cook");

define("PLAN_SETEVENT_HOUR", "hour");
define("PLAN_SETEVENT_MIN", "min");

define("REMOVE_EVENT_BUTTON", "REMOVE EVENT");
define("DONE_BUTTON", "DONE");
define("UNDONE_BUTTON", "UNDONE");

define("STEPPER_STEP1", "Confirm date and time");
define("STEPPER_STEP1_ALLDAY", "All day");
define("STEPPER_STEP1_START", "Start time");
define("STEPPER_STEP1_END", "End time");
define("STEPPER_STEP1_ERROR", "Start date and time should be earlier than End date and time");
define("STEPPER_STEP2", "Enter activity name and chose type of activity");
define("STEPPER_STEP2_ACTIVITYNAME", "Activity name");
define("STEPPER_STEP2_ACTIVITYTYPE", "Activity type");
define("STEPPER_STEP2_ACTIVITYWALK", "Walking");
define("STEPPER_STEP2_ACTIVITYEXERCISE", "Exercise");
define("STEPPER_STEP2_ACTIVITYSOCIAL", "Social activity");
define("STEPPER_STEP3", "Enter activity details");
define("STEPPER_STEP3_EXERCISEINTENSITY", "Intensity Of Exercise : ");
define("STEPPER_STEP3_EXERCISEINTENSITYHIGH", "High");
define("STEPPER_STEP3_EXERCISEINTENSITYHIGH_INFO", "Vigorous (intense) physical activities refer to activities that take hard physical effort and make you breathe much harder than normal. Schedule only physical activities that are done for at least 10 minutes at a time.<br>Example of vigorous physical activities is heavy lifting, digging, aerobics, or fast bicycling. (Or you could use the persons pulse: Vigorous activity: pulse at 75% or more of the persons maximal pulse.)");
define("STEPPER_STEP3_EXERCISEINTENSITYMODERATE", "Moderate");
define("STEPPER_STEP3_EXERCISEINTENSITYMODERATE_INFO", "Moderate activities refer to activities that take moderate physical effort and make you breathe somewhat harder than normal. Schedule only those physical activities that are done for at least 10 minutes at a time.<br>Examples of moderate physical activities is carrying light loads, bicycling at a regular pace, or doubles tennis. Do not include walking. (Moderate activity: 70-75% of maximal pulse.)");
define("STEPPER_STEP3_EXERCISEINTENSITYLOW", "Low");
define("STEPPER_STEP3_EXERCISEINTENSITYLOW_INFO", "Low physical activities refer to activities that take low physical effort and make you breath normal. Schedule only those physical activities that are done for at least 10 minutes at a time.<br>Example of low physical activities is bicycling at slow pace. Do not include walking.");
define("STEPPER_STEP3_WALKSTEPS", "Number of Steps : ");
define("STEPPER_STEP3_SOCIALTYPE", "Type of Social Activity : ");
define("STEPPER_STEP3_SOCIALTYPERECEIVE", "Receive guest");
define("STEPPER_STEP3_SOCIALTYPECALL", "Call someone");
define("STEPPER_STEP3_SOCIALTYPEVISIT", "Visit someone");
define("STEPPER_STEP3_SOCIALTYPECINEMA", "Cinema");
define("STEPPER_STEP3_SOCIALTYPETHEATRE", "Theatre");
define("STEPPER_STEP3_SOCIALTYPERESTAURANT", "Restaurant");
define("STEPPER_STEP3_SOCIALTYPEPUB", "Pub");
define("STEPPER_STEP3_SOCIALTYPERELIGIOUS", "Religious");
define("STEPPER_STEP3_SOCIALTYPEOTHER", "Other");
define("STEPPER_STEP3_SOCIALTYPEOTHERNAME", "Enter the activity name");
define("STEPPER_CONTINUE", "CONTINUE");
define("STEPPER_BACK", "BACK");
define("STEPPER_SUBMIT", "Submit");
define("STEPPER_CANCEL", "Cancel");

define("COMPLETED_TITLE", "Did you complete this activity?");
define("COMPLETED_YES", "YES");
define("COMPLETED_NO", "NO");

define("ACTIVITY_NAME", "Name");
define("ACTIVITY_TYPE", "Description");
define("ACTIVITY_START", "Start");
define("ACTIVITY_END", "End");
define("ACTIVITY_DELETE", "Delete");
define("ACTIVITY_EDIT", "Edit");

//NEW
//invite a friend to join activity
define("INVITE_FRIEND_BUTTON", "INVITE FRIEND");
define("ACCEPT_INVITATION_BUTTON", "ACCEPT");
define("DECLINE_INVITATION_BUTTON", "DECLINE");
//NEW SET GOALS CARD
define("PLAN_GOALS_SOCIAL_ACTIVITY", "Social Activities");
define("PLAN_SETGOALS_SOCIAL_ACTIVITY", "Social Activities (number of events)");
define("PLAN_GOALS_ACTIVITY", "activities");

//set a start and end time for an activity in the plan
define("START_TIME", "Start");
define("END_TIME", "End");
//NEW CALENDAR

define("PLAN_ACTIVITY_ADD", "Add event");
define("PLAN_ACTIVITY_TITLE", "Title");
define("PLAN_ACTIVITY_TYPE", "Type of activity");
define("PLAN_ACTIVITY_TYPE_RECEIVE_GEST", "Receive guest");
define("PLAN_ACTIVITY_TYPE_CALL_SOMEONE", "Call someone");
define("PLAN_ACTIVITY_TYPE_VISIT_SOMEONE", "Visit Someone");    
define("PLAN_ACTIVITY_TYPE_CINEMA", "Go to the cinema");
define("PLAN_ACTIVITY_TYPE_THEATRE", "Go to the theatre");
define("PLAN_ACTIVITY_TYPE_RESTAURANT", "Go to a restaurant");
define("PLAN_ACTIVITY_TYPE_PUB", "Go to a pub");      
define("PLAN_ACTIVITY_TYPE_RELIGIOUS_ACTIVITY", "Religious activity");
define("PLAN_ACTIVITY_OTHER", "Other", "Other activity");
define("PLAN_ACTIVITY_START_TIME", "Start Time");
define("PLAN_ACTIVITY_END_TIME", "End time");

define("CALENDAR_NEXT_BUTTON", "NEXT");
define("CALENDAR_PREVIOUS_BUTTON", "PREVIOUS");
define("CALENDAR_CANCEL_BUTTON", "CANCEL");
define("CALENDAR_SUBMIT_BUTTON", "SUBMIT");
define("CALENDAR_ADVANCED_SETTINGS_BUTTON", "ADVANCED SETTINGS");

define("PLAN_ACTIVITY_STEP1", "Choose Start and End Time");
define("PLAN_ACTIVITY_STEP2", "Choose Activity");
define("PLAN_ACTIVITY_STEP3", "Advanced Settings");

define("CLOSE_BUTTON", "Close");


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

define("PROFILE_EDIT", "EDIT PROFILE");
define("PROFILE_SAVE", "SAVE CHANGES");

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
define("PROFILE_INTERESTS_CLOSE", "CLOSE");


//CONTACTS PAGE
define("CONTACTS_CONTACTSCARD_TITLE", "Contacts");
define("CONTACTS_CONTACTSCARD_HEADER_NAME", "Name");
define("CONTACTS_CONTACTSCARD_HEADER_STATUS", "Status");
define("CONTACTS_CONTACTSCARD_HEADER_EMAIL", "Email");
define("CONTACTS_CONTACTSCARD_HEADER_PHONE", "Phone");
define("CONTACTS_CONTACTSCARD_HEADER_ACTIONS", "Actions");
define("REMOVE_BUTTON", "REMOVE");
define("PROFILE_REMOVE_DONE", "DONE");

define("CONTACTS_CONTACTSCARD_STATUS_ONLINE", "Online");
define("CONTACTS_CONTACTSCARD_STATUS_OFFLINE", "Offline");
define("CONTACTS_CONTACTSCARD_STATUS_BUSY", "Busy");

define("CONTACTS_FORM_TITLE", "Add contact");
define("CONTACTS_FORM_NAME", "Name");
define("CONTACTS_FORM_EMAIL", "Email");
define("CONTACTS_FORM_PHONE", "Phone Number");
define("CONTACTS_FORM_RELATIONSHIP", "Relationship");
define("ADD_BUTTON", "ADD");

//types of relationships
define("CONTACTS_FROM_RELATIONSHIP_CLOSE_FAMILY", "Close Family");
define("CONTACTS_FROM_RELATIONSHIP_OTHER_FAMILY", "Other Family");
define("CONTACTS_FROM_RELATIONSHIP_FRIEND", "Friend");
define("CONTACTS_FROM_RELATIONSHIP_NEIGHBOUR", "Neighbour");

//HUE LIGHTS PAGE
define("DISCOVER_BRIDGE","Discover Bridge");
define("SET_USERNAME", "Set Username");
define("GET_LIGHT_STATE", "Get Light State");
define("TURN_ON_AND_CHANGE_COLOR","Turn on and change color");
define("TURN_ON","Turn on");
define("TURN_OFF","Turn off");
define("SET_COLOR","Set color");
define("SATURATION","Saturation");
define("BRIGHTNESS","Brightness");
define("LIGHT","Light");
define("LIVING_ROOM","Living Room");
define("KITCHEN","Kitchen");
define("ENTRANCE","Entrance");
?>