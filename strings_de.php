<?php

// LOGIN PAGE
define("LOGIN_WELCOME", "Willkommen!");
define("LOGIN_USERNAME_HINT", "Benutzername");
define("LOGIN_PASSWORD_HINT", "Passwort");
define("LOGIN_LOGIN_BUTTON", "Anmelden");
define("LOGIN_REGISTRATION_TEXT", "Noch nicht registriert? Melden Sie sich hier an!");
define("DISABLED_COOKIE_MESSAGE","Cookies ausgestellt.");
define("SESSION_EXPIRED_MESSAGE", "Sitzung abgelaufen.");
define("WRONG_USERNAME_OR_PASSWORD_MESSAGE", "falscher Benutzername oder Passwort.");
define("DB_CONNECTION_ERROR_MESSAGE", "Verbindung zur Datenbank fehlgeschlagen.");
define("EMPTY_CREDENTIAL_MESSAGE", "fehlende Zugangsdaten.");
define("INVALID_CREDENTIAL_MESSAGE", "ungültige Zugangsdaten.");
define("REGISTRATION_SUCCESSFUL_MESSAGE", "Anmeldung erfolgreich, bitte geben Sie die Zugangsdaten ein.");
define("REGISTRATION_ERROR_MESSAGE", "Fehler: Registrierung kann nicht abgeschlossen werden (Vielleicht bereits benutzter Benutzername)"); //trad
define("LOGIN_WELCOME", "Velkommen!");
define("LOGIN_USERNAME_HINT", "Brukernavn");
define("LOGIN_PASSWORD_HINT", "Passord");
define("LOGIN_LOGIN_BUTTON", "Logg inn");
define("LOGIN_REGISTRATION_TEXT", "Er du ikke registrert? Registrer deg her");
define("DISABLED_COOKIE_MESSAGE","Deaktiverte informasjonskapsler/ cookies");
define("SESSION_EXPIRED_MESSAGE", "Tidsavbrudd.");
define("WRONG_USERNAME_OR_PASSWORD_MESSAGE", "Feil brukernavn eller passord");
define("DB_CONNECTION_ERROR_MESSAGE", "Kan ikke koble til databasen.");
define("EMPTY_CREDENTIAL_MESSAGE", "Dette feltet må fylles ut.");
define("INVALID_CREDENTIAL_MESSAGE", "Ugyldig informasjon.");
define("REGISTRATION_SUCCESSFUL_MESSAGE", "Registreringen er ferdig. Vennligst logg inn.");
define("REGISTRATION_ERROR_MESSAGE", "Feilmelding: Registreringen kan ikke gjennomføres (kanskje brukernavnet allerede er i bruk)"); //trad

//REGISTRATION PAGE
define("REGISTRATION_FORM_TITLE", "Registrering");
define("REGISTRATION_FORM_USERNAME", "Brukernavn");
define("REGISTRATION_FORM_PASSWORD", "Passord");
define("REGISTRATION_FORM_NAME", "Fornavn");
define("REGISTRATION_FORM_SURNAME", "Etternavn");
define("REGISTRATION_FORM_BIRTHDATE", "Bursdag");
define("REGISTRATION_FORM_BIRTHDATE_DAY", "Dag");
define("REGISTRATION_FORM_BIRTHDATE_MONTH", "Måned"); 
define("REGISTRATION_FORM_BIRTHDATE_YEAR", "År"); 
define("REGISTRATION_FORM_GENDER", "Kjønn");
define("REGISTRATION_FORM_GENDER_MALE", "Mann");
define("REGISTRATION_FORM_GENDER_FEMALE", "Kvinne");
define("REGISTRATION_FORM_STATE", "Land");
define("REGISTRATION_FORM_POSTALCODE", "Postnummer");
define("REGISTRATION_FORM_CITY", "Poststed");
define("REGISTRATION_FORM_ADDRESS", "Adresse");
define("REGISTRATION_FORM_INSTRUCTIONS", "* obligatorisk");
define("REGISTRATION_FORM_REGEX_INSTRUCTIONS", "Brukernavn og passord med minimum 2 bokstaver og/eller nummer og/eller '_'");
define("CONFIRM_BUTTON", "Bekreft");

//LEFT MENU AND PAGE TITLES
define("MENU_TITLE", "Meny"); 
define("ENTRY_HOME", "Startside");
define("ENTRY_HEALTH", "Helse");
define("ENTRY_PLAN", "Kalender");


define("ENTRY_PROFILE", "Profil");
define("ENTRY_CONTACTS", "Kontakt");
define("ENTRY_HUELIGHTS", "Hue Lights");
define("ENTRY_LOGOUT", "Abmelden");

//INDEX PAGE
define("INDEX_SURVEYCARD_TITLE", "Wie geht es Ihnen heute?");
define("INDEX_SURVEYCARD_TEXT", "Mit wenigen Fragen zu einer gesünderen Lebensweise.");
define("INDEX_SURVEYCARD_BUTTON", "zur Umfrage");

define("INDEX_SURVEY_TITLE", "Für eine gesünderen Lebensweise!");
define("INDEX_SURVEY_QUESTION1", "Wie viel wiegen Sie heute?");
define("INDEX_SURVEY_HINT1", "Gewicht (kg)");
define("INDEX_SURVEY_QUESTION2", "Bitte geben Sie Ihre Grösse an"); 
define("INDEX_SURVEY_HINT2", "Grösse (cm)"); 
define("INDEX_SURVEY_QUESTION3", "Bitte geben Sie Ihr Alter an"); 
define("INDEX_SURVEY_HINT3", "Alter"); 
define("INDEX_SURVEY_QUESTION4", "Was motiviert Sie Sport zu treiben?"); 

define("INDEX_SURVEY_MOTIVATION1", "Es macht mir Spass und ich fühle mich gut danach"); 
define("INDEX_SURVEY_MOTIVATION2", "Es hilft gesund zu bleiben und Krankheiten vorzubeugen"); 
define("INDEX_SURVEY_MOTIVATION3", "Gewichtsverlust und ein besseres Erscheinungsbild"); 
define("INDEX_SURVEY_MOTIVATION4", "Ich messe mich gerne mit anderen und verbringe Zeit mit Freunden"); 

define("CANCEL_BUTTON", "abbrechen");
define("INDEX_STEPSCARD_STEPS", "Schritte");
define("INDEX_STEPSCARD_STEPS_GOAL", "geplante Schritte");

define("INDEX_STEPSCARD_EXERCISE", "min");
define("INDEX_STEPSCARD_EXERCISEGOAL", "Mål for treningen din");
define("STEPS_PERFORMED", "antall skritt gjennomført i dag");


define("INDEX_NEWS1_TITLE", "Nyheter");
define("INDEX_NEWS2_TITLE", "Sportsnyheter");

define("INDEX_INFOCARD_TITLE", "INFORMASJON"); 
define("SEND_MESSAGE_BUTTON", "Melding sendt");

define("WEIGHT_CARD_TITLE", "Gewicht"); 
define("HEART_CARD_TITLE", "Herzfrequenz"); 
define("BREATH_CARD_TITLE", "Atemfrequenz"); 
define("BMI_CARD_TITLE", "BMI"); 
define("TEMPERATURE_CARD_TITLE", "Körpertemperatur"); 
define("WEATHER_CARD_TITLE", "Wettervorhersage"); 
define("MESSAGE_CARD_TITLE", "Nachricht");
define("MEDICATION_CARD_TITLE", "Medikationstagebuch"); 
define("MEDICATION_PLANNED", "Medikation"); 
define("MEDICATION_PLANNED_DOSAGE", "Dosierung"); 
define("MEDICATION_PLANNED_TIME", "Uhrzeit"); 


//HEALTH PAGE
define("HEALTH_WEIGHTPLOT_TITLE", "Vekt");
define("HEALTH_SCOREPLOT_TITLE", "Skåring"); 
define("HEALTH_ECGPLOT_TITLE", "EKG");
define("HEALTH_HRPLOT_TITLE", "HERZFREQUENZ");
define("HEALTH_WEIGHTPLOT_TITLE", "GEWICHT");
define("HEALTH_BMIPLOT_TITLE", "BMI");
define("HEALTH_SCOREPLOT_TITLE", "Wert"); 
define("HEALTH_WEIGHTPLOT_DATE_HINT", "Datum");

define("HEALTH_ACCPLOT_TITLE", "Beschleunigungsmessung");
define("HEALTH_TEMPPLOT_TITLE", "TEMPERATUR");
define("HEALTH_BREATHPLOT_TITLE", "ATEMFREQUENZ");

define("HEALTH_DATABUTTON_START", "START UNDERSØKELSEN");
define("HEALTH_DATABUTTON_STOP", "STOP UNDERSØKELSEN");

//NOT USED - SEE js/health.js
define("HEALTH_SNACKBAR_CONNECTING", "Kobler opp til BITalino.");
define("HEALTH_SNACKBAR_ERROR", "Feilmelding: Undersøk forbindelsen til BITalino.");
define("HEALTH_SNACKBAR_STOP", "Kobler opp til BITalino.");

define("HEALTH_WEIGHTPLOT_DATE_HINT", "Dato");

//PLAN PAGE
define("PLAN_SETGOALS_TITLE", "Wochenziele setzen");
define("PLAN_SETGOALS_EXERCISE", "Fitness�bungen (minuten)");
define("PLAN_SETGOALS_WALK", "Bewegung (minuten)");
define("PLAN_SETGOALS_MEET", "Verabredungen (Personen)");
define("PLAN_SETGOALS_ATLEAST", "mindestes");
define("PLAN_SETGOALS_MORETHAN", "mehr als");
define("PLAN_SNACKBAR", "Bitte zuerst Zielwert angeben!");
define("PLAN_GOALS_TITLE", "Wochenziele");
define("PLAN_GOALS_EXERCISE", "Fitnessübungen");
define("PLAN_GOALS_WALK", "Bewegung");
define("PLAN_GOALS_MEET", "Verabredungen");
define("PLAN_GOALS_MIN", "min");
define("PLAN_GOALS_STEPS", "Skritt");
define("PLAN_GOALS_PERSONS", "Personer");
define("PLAN_GOALS_COOK", "Lage mat");
define("PLAN_GOALS_MODIFY", "ENDRE");
define("PLAN_CALENDAR_TITLE", "Kalender");
define("PLAN_CALENDAR_INSTRUCTIONS", "Dra en hendelse inn i kalenderen for å planlegge for fremtidig aktivitet eller rapportere hva du allerede har gjort.");
define("PLAN_CALENDAR_EVENT_EXERCISE", "Trening");
define("PLAN_CALENDAR_EVENT_WALK", "Gå tur");
define("PLAN_CALENDAR_EVENT_MEET", "Sosialt");
define("PLAN_CALENDAR_EVENT_COOK", "Lage mat");
define("PLAN_SETEVENT_HOUR", "Tidspunkt");
define("PLAN_SETEVENT_MIN", "min");

define("REMOVE_EVENT_BUTTON", "FJERN HENDELSE");
define("DONE_BUTTON", "FERDIG");
define("UNDONE_BUTTON", "ANGRE");

//NEW
//invite a friend to join activity
define("INVITE_FRIEND_BUTTON", "INVITE FRIEND");
define("ACCEPT_INVITATION_BUTTON", "ACCEPT");
define("DECLINE_INVITATION_BUTTON", "DECLINE");
//NEW SET GOALS CARD 
define("PLAN_GOALS_SOCIAL_ACTIVITY", "Soziale Aktivitäten");
define("PLAN_SETGOALS_SOCIAL_ACTIVITY", "Soziale Aktivitäten");
define("PLAN_GOALS_ACTIVITY", "aktivitäten");

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

define("CALENDAR_NEXT_BUTTON", "NÄCHSTER");
define("CALENDAR_PREVIOUS_BUTTON", "BISHERIGE");
define("CALENDAR_CANCEL_BUTTON", "STORNIEREN");
define("CALENDAR_SUBMIT_BUTTON", "EINREICHEN")
define("CALENDAR_ADVANCED_SETTINGS_BUTTON", "ERWEITERTE EINSTELLUNGEN");

define("PLAN_ACTIVITY_STEP1", "Wählen Sie Start und Endzeit");
define("PLAN_ACTIVITY_STEP2", "Wählen Sie Aktivität");
define("PLAN_ACTIVITY_STEP3", "Erweiterte Einstellungen");


//PROFILE PAGE
define("PROFILE_PROFILECARD_TITLE", "Om meg");
define("PROFILE_PROFILECARD_NAME", "Fornavn");
define("PROFILE_PROFILECARD_SURNAME", "Etternavn");
define("PROFILE_PROFILECARD_BIRTHDATE", "Bursdag");
define("PROFILE_PROFILECARD_GENDER", "Kjønn");
define("PROFILE_PROFILECARD_GENDER_MALE", "Mann");
define("PROFILE_PROFILECARD_GENDER_FEMALE", "Kvinne");
define("PROFILE_PROFILECARD_STATE", "Land");
define("PROFILE_PROFILECARD_POSTALCODE", "Postnummer");
define("PROFILE_PROFILECARD_CITY", "Poststed");
define("PROFILE_PROFILECARD_ADDRESS", "Adresse");

define("PROFILE_INTERESTS_TITLE", "Interesser");
define("PROFILE_ADDINTERESTS_TITLE", "Legg til interesser");
define("PROFILE_ADDINTERESTS_SPORTS", "Sport");
define("PROFILE_ADDINTERESTS_PROGRAMS", "TV programmer");
define("PROFILE_ADDINTERESTS_OTHERS", "Annet");
define("PROFILE_ADDINTERESTS_SPORTS_1", "Baseball");
define("PROFILE_ADDINTERESTS_SPORTS_2", "Basketball");
define("PROFILE_ADDINTERESTS_SPORTS_3", "Fotball");
define("PROFILE_ADDINTERESTS_SPORTS_4", "Svømme");
define("PROFILE_ADDINTERESTS_SPORTS_5", "Tennis");
define("PROFILE_ADDINTERESTS_SPORTS_6", "Volleyball");
define("PROFILE_ADDINTERESTS_PROGRAMS_1", "Dokumentarer");
define("PROFILE_ADDINTERESTS_PROGRAMS_2", "Nyheter");
define("PROFILE_ADDINTERESTS_PROGRAMS_3", "Talkshow");
define("PROFILE_ADDINTERESTS_OTHERS_1", "Kino");
define("PROFILE_ADDINTERESTS_OTHERS_2", "Lage mat");
define("PROFILE_ADDINTERESTS_OTHERS_3", "Severdigheter");
define("PROFILE_ADDINTERESTS_OTHERS_4", "Museum");
define("PROFILE_ADDINTERESTS_OTHERS_5", "Teater");


//CONTACTS PAGE
define("CONTACTS_CONTACTSCARD_TITLE", "Kontakter");
define("CONTACTS_CONTACTSCARD_HEADER_NAME", "Navn");
define("CONTACTS_CONTACTSCARD_HEADER_STATUS", "Status");
define("CONTACTS_CONTACTSCARD_HEADER_EMAIL", "Email");
define("CONTACTS_CONTACTSCARD_HEADER_PHONE", "Phone");
define("CONTACTS_CONTACTSCARD_HEADER_ACTIONS", "Handling");
define("REMOVE_BUTTON", "FJERN");
define("CONTACTS_CONTACTSCARD_STATUS_ONLINE", "Pålogget");
define("CONTACTS_CONTACTSCARD_STATUS_OFFLINE", "Avlogget");
define("CONTACTS_CONTACTSCARD_STATUS_BUSY", "Opptatt");
define("CONTACTS_FORM_TITLE", "Legg til kontakt");
define("CONTACTS_FORM_NAME", "Navn");
define("CONTACTS_FORM_EMAIL", "Email");
define("CONTACTS_FORM_PHONE", "Telefonnummer");
define("ADD_BUTTON", "Legg til");

//types of relationships
define("CONTACTS_FROM_RELATIONSHIP_CLOSE_FAMILY", "Nahes Familienmitglied");
define("CONTACTS_FROM_RELATIONSHIP_OTHER_FAMILY", "Andere Familienmitglieder");
define("CONTACTS_FROM_RELATIONSHIP_FRIEND", "Freund");
define("CONTACTS_FROM_RELATIONSHIP_NEIGHBOUR", "Nachbar");

    
?>
