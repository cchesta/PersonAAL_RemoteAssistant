<?php

// LOGIN PAGE
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
define("REGISTRATION_ERROR_MESSAGE", "Feilmelding: Registreringen kan ikke gjennomføres. Brukernavnet er allerede i bruk."); //trad

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
define("ENTRY_CONTACTS", "Kontakter");
define("ENTRY_HUELIGHTS", "Hue Lights");
define("ENTRY_LOGOUT", "Logg ut");

//INDEX PAGE
define("INDEX_SURVEYCARD_TITLE", "Hva motiverer deg?");
define("INDEX_SURVEYCARD_TEXT", "Besvar noen enkle spørsmål for hjelp til en sunnere livsstil.");
define("INDEX_SURVEYCARD_BUTTON", "Svar på spørsmå");

define("INDEX_SURVEY_TITLE", "Vennligst besvar spørsmålene under for hjelp til å utvikle en sunnere livsstil.");
define("INDEX_SURVEY_QUESTION1", "Hvor mye veier du?");
define("INDEX_SURVEY_HINT1", "Vekt (kg)");
define("INDEX_SURVEY_QUESTION2", "Hvor høy er du?"); 
define("INDEX_SURVEY_HINT2", "Høyde (cm)"); 
define("INDEX_SURVEY_QUESTION3", "Hva er din alder?"); 
define("INDEX_SURVEY_HINT3", "Alder"); 
define("INDEX_SURVEY_QUESTION4", "Hva motiverer deg til å trene?"); 

define("INDEX_SURVEY_MOTIVATION1", "Jeg liker å trene. Det gjør at jeg føler meg bra."); 
define("INDEX_SURVEY_MOTIVATION2", "For å opprettholde en god helse og forebygge sykdom"); 
define("INDEX_SURVEY_MOTIVATION3", "For vektreduksjon og forbedring av utseende"); 
define("INDEX_SURVEY_MOTIVATION4", "Fordi jeg liker å konkurrere og bruke tid med venner"); 

define("CANCEL_BUTTON", "Avbryt");
define("INDEX_STEPSCARD_STEPS", "Skritt");
define("INDEX_STEPSCARD_STEPS_GOAL", "Skrittmål");
define("INDEX_STEPSCARD_EXERCISE", "min");
define("INDEX_STEPSCARD_EXERCISEGOAL", "Mål for treningen din");
define("STEPS_PERFORMED", "antall skritt gjennomført i dag");



define("INDEX_NEWS1_TITLE", "Nyheter");
define("INDEX_NEWS2_TITLE", "Sportsnyheter");

define("INDEX_INFOCARD_TITLE", "INFORMASJON"); 
define("SEND_MESSAGE_BUTTON", "Melding sendt");

define("WEIGHT_CARD_TITLE", "Vekt"); 
define("HEART_CARD_TITLE", "Puls"); 
define("BREATH_CARD_TITLE", "Respirasjonsfrekvens"); 
define("BMI_CARD_TITLE", "BMI");
define("HOMETEMPERATURE_CARD_TITLE", "Hjemmetemperatur");
define("HOMEHUMIDITY_CARD_TITLE", "Luftfuktighet hjemme");
define("MOTION_CARD_TITLE", "Bevegelse");
define("TEMPERATURE_CARD_TITLE", "Kroppstemperatur");
define("WEATHER_CARD_TITLE", "Værmelding"); 
define("MESSAGE_CARD_TITLE", "Meldinger til deg");
define("MEDICATION_CARD_TITLE", "Medisinoversikt"); 
define("MEDICATION_PLANNED", "Medisinering"); 
define("MEDICATION_PLANNED_DOSAGE", "Dosering"); 
define("MEDICATION_PLANNED_TIME", "Tid"); 

//HEALTH PAGE
define("HEALTH_WEIGHTPLOT_TITLE", "Vekt");
define("HEALTH_BMIPLOT_TITLE", "BMI");
define("HEALTH_SCOREPLOT_TITLE", "Skåring"); 
define("HEALTH_ECGPLOT_TITLE", "EKG");
define("HEALTH_HRPLOT_TITLE", "PULS");
define("HEALTH_ACCPLOT_TITLE", "Skritteller");
define("HEALTH_TEMPPLOT_TITLE", "TEMPERATUR");
define("HEALTH_BREATHPLOT_TITLE", "RESPIRASJONSFREKVENS");

define("HEALTH_DATABUTTON_START", "START UNDERSØKELSEN");
define("HEALTH_DATABUTTON_STOP", "STOP UNDERSØKELSEN");

//NOT USED - SEE js/health.js
define("HEALTH_SNACKBAR_CONNECTING", "Kobler opp til BITalino.");
define("HEALTH_SNACKBAR_ERROR", "Feilmelding: Undersøk forbindelsen til BITalino.");
define("HEALTH_SNACKBAR_STOP", "Kobler opp til BITalino.");

define("HEALTH_WEIGHTPLOT_DATE_HINT", "Dato");

//PLAN PAGE
define("PLAN_SETGOALS_TITLE", "Lag ditt ukentlige mål");
define("PLAN_SETGOALS_EXERCISE", "Trening (timer)");
define("PLAN_SETGOALS_WALK", "Gå tur (timer)");
define("PLAN_SETGOALS_MEET", "Sosialt (møte personer)");
define("PLAN_SETGOALS_ATLEAST", "minst");
define("PLAN_SETGOALS_MORETHAN", "mer enn");

define("PLAN_SNACKBAR", "Sett opp mål først!");

define("PLAN_GOALS_TITLE", "Ukesmål");
define("PLAN_GOALS_EXERCISE", "Trening");
define("PLAN_GOALS_WALK", "Gå tur");
define("PLAN_GOALS_MEET", "Sosialt");
define("PLAN_GOALS_MIN", "min");
define("PLAN_GOALS_HOURS", "timer");
define("PLAN_GOALS_STEPS", "Skritt");
define("PLAN_GOALS_PERSONS", "Personer");
define("PLAN_GOALS_COOK", "Lage mat");
define("PLAN_GOALS_MODIFY", "ENDRE");

define("PLAN_CALENDAR_TITLE", "Kalender");
define("PLAN_CALENDAR_INSTRUCTIONS", "Klikk på kalenderen for å planlegge av en aktivitet");
define("PLAN_CALENDAR_EVENT_EXERCISE", "Trening");
define("PLAN_CALENDAR_EVENT_WALK", "Gå tur");
define("PLAN_CALENDAR_EVENT_MEET", "Sosialt");
define("PLAN_CALENDAR_EVENT_COOK", "Lage mat");
define("PLAN_SETEVENT_HOUR", "Tidspunkt");
define("PLAN_SETEVENT_MIN", "min");

define("REMOVE_EVENT_BUTTON", "FJERN HENDELSE");
define("DONE_BUTTON", "FERDIG");
define("UNDONE_BUTTON", "ANGRE");

define("STEPPER_STEP1", "Bekreft dato og klokkeslett");
define("STEPPER_STEP1_ALLDAY", "Hele dagen");
define("STEPPER_STEP1_START", "Starttid");
define("STEPPER_STEP1_END", "Sluttid");
define("STEPPER_STEP1_ERROR", "Startdato og klokkeslett skal være tidligere enn sluttdato og klokkeslett");
define("STEPPER_STEP2", "Skriv in navnet på aktiviteten og velg type aktivitet");
define("STEPPER_STEP2_ACTIVITYNAME", "Aktivitetsnavn");
define("STEPPER_STEP2_ACTIVITYTYPE", "Aktivitetstype");
define("STEPPER_STEP2_ACTIVITYWALK", "Gå");
define("STEPPER_STEP2_ACTIVITYEXERCISE", "Trening");
define("STEPPER_STEP2_ACTIVITYSOCIAL", "Sosial aktivitet");
define("STEPPER_STEP3", "Skriv inn aktivitetsdetaljer");
define("STEPPER_STEP3_EXERCISEINTENSITY", "Hvor intensiv skal treningen være?");
define("STEPPER_STEP3_EXERCISEINTENSITYHIGH", "Høy");
define("STEPPER_STEP3_EXERCISEINTENSITYMODERATE", "Moderat");
define("STEPPER_STEP3_EXERCISEINTENSITYLOW", "Lav");
define("STEPPER_STEP3_WALKSTEPS", "Antall skritt : ");
define("STEPPER_STEP3_SOCIALTYPE", "Hva slags sosial aktivitet vil du planlegge?");
define("STEPPER_STEP3_SOCIALTYPERECEIVE", "Få gjest(er)");
define("STEPPER_STEP3_SOCIALTYPECALL", "Telefonsamtale");
define("STEPPER_STEP3_SOCIALTYPEVISIT", "Besøk(e) venner/ familie/ bekjente");
define("STEPPER_STEP3_SOCIALTYPECINEMA", "Kino");
define("STEPPER_STEP3_SOCIALTYPETHEATRE", "Teater");
define("STEPPER_STEP3_SOCIALTYPERESTAURANT", "Restaurantbesøk");
define("STEPPER_STEP3_SOCIALTYPEPUB", "Pub");
define("STEPPER_STEP3_SOCIALTYPERELIGIOUS", "Religiøs aktivitet");
define("STEPPER_STEP3_SOCIALTYPEOTHER", "Annen");
define("STEPPER_STEP3_SOCIALTYPEOTHERNAME", "Skriv inn aktivitetsnavnet");
define("STEPPER_CONTINUE", "Fortsett");
define("STEPPER_BACK", "TILBAKE");
define("STEPPER_SUBMIT", "Send inn");
define("STEPPER_CANCEL", "Avbryt");

define("COMPLETED_TITLE", "Har du fullført denne aktiviteten?");
define("COMPLETED_YES", "JA");
define("COMPLETED_NO", "NEI");

define("ACTIVITY_NAME", "Navn");
define("ACTIVITY_TYPE", "Beskrivelse");
define("ACTIVITY_START", "Start");
define("ACTIVITY_END", "Slutt");
define("ACTIVITY_DELETE", "Slett");
define("ACTIVITY_EDIT", "Redigere");

//NEW
//invite a friend to join activity ("inviter en venn til å bli med på aktivitet")
define("INVITE_FRIEND_BUTTON", "INVITER EN VENN");
define("ACCEPT_INVITATION_BUTTON", "AKSEPTERER");
define("DECLINE_INVITATION_BUTTON", "AVSLÅ");

//NEW SET GOALS CARD
define("PLAN_GOALS_SOCIAL_ACTIVITY", "Sosiale Aktiviteter");
define("PLAN_SETGOALS_SOCIAL_ACTIVITY", "Sosiale Aktiviteter (antall hendelser)");
define("PLAN_GOALS_ACTIVITY","aktiviteter");

//set a start and end time for an activity in the plan
define("START_TIME", "Start");
define("END_TIME", "Slutt");
//NEW CALENDAR

define("PLAN_ACTIVITY_ADD", "Legg til en begivenhet");
define("PLAN_ACTIVITY_TITLE", "Titel");  
define("PLAN_ACTIVITY_TYPE", "Type aktivitet");
define("PLAN_ACTIVITY_TYPE_RECEIVE_GEST", "Motta gjest(er)");
define("PLAN_ACTIVITY_TYPE_CALL_SOMEONE", "Ringe noen");
define("PLAN_ACTIVITY_TYPE_VISIT_SOMEONE", "Besøke noen");    
define("PLAN_ACTIVITY_TYPE_CINEMA", "Gå på kino");
define("PLAN_ACTIVITY_TYPE_THEATRE", "Gå på teater");
define("PLAN_ACTIVITY_TYPE_RESTAURANT", "Gå på en restaurant");
define("PLAN_ACTIVITY_TYPE_PUB", "Gå på pub/ bar");      
define("PLAN_ACTIVITY_TYPE_RELIGIOUS_ACTIVITY", "Aktivitet knyttet til religion eller livssyn");
define("PLAN_ACTIVITY_OTHER", "Annen aktivitet");
define("PLAN_ACTIVITY_START_TIME", "Tidspunkt for start"); 
define("PLAN_ACTIVITY_END_TIME", "Tidspunkt for slutt");

define("CALENDAR_NEXT_BUTTON", "NESTE");
define("CALENDAR_PREVIOUS_BUTTON", "TIDLIGERE");
define("CALENDAR_CANCEL_BUTTON", "AVBRYT");
define("CALENDAR_SUBMIT_BUTTON", "SENDE INN");
define("CALENDAR_ADVANCED_SETTINGS_BUTTON", "AVANSERTE INNSTILLINGER");

define("PLAN_ACTIVITY_STEP1", "Velg Start og sluttid");
define("PLAN_ACTIVITY_STEP2", "Velg aktivitet");
define("PLAN_ACTIVITY_STEP3", "Avanserte innstillinger");


//PROFILE PAGE
define("PROFILE_PROFILECARD_TITLE", "Om meg");
define("PROFILE_PROFILECARD_NAME", "Fornavn");
define("PROFILE_PROFILECARD_SURNAME", "Etternavn");
define("PROFILE_PROFILECARD_BIRTHDATE", "Bursdag");
define("PROFILE_PROFILECARD_GENDER", "Kjønn");
define("PROFILE_PROFILECARD_GENDER_MALE", "Han");
define("PROFILE_PROFILECARD_GENDER_FEMALE", "Hun");
define("PROFILE_PROFILECARD_STATE", "Land");
define("PROFILE_PROFILECARD_POSTALCODE", "Postnummer");
define("PROFILE_PROFILECARD_CITY", "Poststed");
define("PROFILE_PROFILECARD_ADDRESS", "Adresse");

define("PROFILE_EDIT", "REDIGER PROFIL");
define("PROFILE_SAVE", "LAGRE PROFIL");

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
define("PROFILE_INTERESTS_CLOSE", "LUKK");


//CONTACTS PAGE
define("CONTACTS_CONTACTSCARD_TITLE", "Kontakter");
define("CONTACTS_CONTACTSCARD_HEADER_NAME", "Navn");
define("CONTACTS_CONTACTSCARD_HEADER_STATUS", "Status");
define("CONTACTS_CONTACTSCARD_HEADER_EMAIL", "Email");
define("CONTACTS_CONTACTSCARD_HEADER_PHONE", "Telefonnummer");
define("CONTACTS_CONTACTSCARD_HEADER_ACTIONS", "Handling");
define("REMOVE_BUTTON", "FJERN");
define("PROFILE_REMOVE_DONE", "LUKK");

define("CONTACTS_CONTACTSCARD_STATUS_ONLINE", "Pålogget");
define("CONTACTS_CONTACTSCARD_STATUS_OFFLINE", "Avlogget");
define("CONTACTS_CONTACTSCARD_STATUS_BUSY", "Opptatt");
define("CONTACTS_FORM_TITLE", "Legg til kontakt");
define("CONTACTS_FORM_NAME", "Navn");
define("CONTACTS_FORM_EMAIL", "Email");
define("CONTACTS_FORM_PHONE", "Telefonnummer");
define("CONTACTS_FORM_RELATIONSHIP", "Forhold");
define("ADD_BUTTON", "Legg til");

//types of relationships
define("CONTACTS_FROM_RELATIONSHIP_CLOSE_FAMILY", "Nært Familiemedlem");
define("CONTACTS_FROM_RELATIONSHIP_OTHER_FAMILY", "Annet Familiemedlem");
define("CONTACTS_FROM_RELATIONSHIP_FRIEND", "Venn");
define("CONTACTS_FROM_RELATIONSHIP_NEIGHBOUR", "Nabo");

//HUE LIGHTS PAGE

define("DISCOVER_BRIDGE","Søker opp bro (Bridge)");
define("SET_USERNAME", "Sett inn brukernavn");
define("GET_LIGHT_STATE", "Finn status for lys");
define("TURN_ON_AND_CHANGE_COLOR","Slå på og endre fargen på lyset");
define("TURN_ON","Slå på");
define("TURN_OFF","Slå av");
define("SET_COLOR","Velg farge");
define("SATURATION","Velg metning");
define("BRIGHTNESS","Lysstyrke");
define("LIGHT","Lys");
define("LIVING_ROOM","Stue");
define("KITCHEN","Kjøkken");
define("ENTRANCE","Inngang");
    
?>