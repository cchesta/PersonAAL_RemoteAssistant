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

//REGISTRATION PAGE
define("REGISTRATION_FORM_TITLE", "registrieren");
define("REGISTRATION_FORM_USERNAME", "Benutzername");
define("REGISTRATION_FORM_PASSWORD", "Passwort");
define("REGISTRATION_FORM_NAME", "Vorname");
define("REGISTRATION_FORM_SURNAME", "Familienname");
define("REGISTRATION_FORM_BIRTHDATE", "Geburtstag");
define("REGISTRATION_FORM_BIRTHDATE_DAY", "Tag");
define("REGISTRATION_FORM_BIRTHDATE_MONTH", "Monat"); 
define("REGISTRATION_FORM_BIRTHDATE_YEAR", "Jahr"); 
define("REGISTRATION_FORM_GENDER", "Geschlecht");
define("REGISTRATION_FORM_GENDER_MALE", "männlich");
define("REGISTRATION_FORM_GENDER_FEMALE", "weiblich");
define("REGISTRATION_FORM_STATE", "Land");
define("REGISTRATION_FORM_POSTALCODE", "Postleitzahl");
define("REGISTRATION_FORM_CITY", "Stadt");
define("REGISTRATION_FORM_ADDRESS", "Strasse und Hausnummer");
define("REGISTRATION_FORM_INSTRUCTIONS", "* verpflichtende Angabe");
define("REGISTRATION_FORM_REGEX_INSTRUCTIONS", "Benutzername und Passwort mit mindestens 2 Buchstaben und/oder Nummern und/oder '_'");
define("CONFIRM_BUTTON", "bestätigen");

//LEFT MENU AND PAGE TITLES
define("MENU_TITLE", "Menü"); 
define("ENTRY_HOME", "Startseite");
define("ENTRY_HEALTH", "Gesundheit");
define("ENTRY_PLAN", "Kalender");
define("ENTRY_PROFILE", "Profil");
define("ENTRY_CONTACTS", "Kontakt");
define("ENTRY_HUELIGHTS", "Hue Leuchtmittel");
define("ENTRY_LOGOUT", "Abmelden");

//INDEX PAGE
define("INDEX_SURVEYCARD_TITLE", "Wie geht es Ihnen heute?");
define("INDEX_SURVEYCARD_TEXT", "Mit wenigen Fragen zu einer gesünderen Lebensweise.");
define("INDEX_SURVEYCARD_BUTTON", "zur Umfrage");
define("INDEX_SURVEY_TITLE", "Für eine gesündere Lebensweise!");
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
define("INDEX_STEPSCARD_EXERCISEGOAL", "geplante Übung");

define("INDEX_NEWS1_TITLE", "Schlagzeilen");
define("INDEX_NEWS2_TITLE", "Sport");

define("INDEX_INFOCARD_TITLE", "INFO"); 
define("SEND_MESSAGE_BUTTON", "Nachricht senden");

define("WEIGHT_CARD_TITLE", "Gewicht"); 
define("HEART_CARD_TITLE", "Herzfrequenz"); 
define("BREATH_CARD_TITLE", "Atemfrequenz"); 
define("BMI_CARD_TITLE", "BMI");
define("HOMETEMPERATURE_CARD_TITLE", "Haustemperatur");
define("HOMEHUMIDITY_CARD_TITLE", "Luftfeuchtigkeit in der Wohnung");
define("MOTION_CARD_TITLE", "Bewegung");
define("TEMPERATURE_CARD_TITLE", "Körpertemperatur");
define("POSITION_CARD_TITLE", "Position");
define("WEATHER_CARD_TITLE", "Wettervorhersage"); 
define("MESSAGE_CARD_TITLE", "Nachricht");
define("MEDICATION_CARD_TITLE", "Medikationstagebuch"); 
define("MEDICATION_PLANNED", "Medikation"); 
define("MEDICATION_PLANNED_DOSAGE", "Dosierung"); 
define("MEDICATION_PLANNED_TIME", "Uhrzeit"); 

//HEALTH PAGE
define("HEALTH_ECGPLOT_TITLE", "EKG");
define("HEALTH_WEIGHTPLOT_TITLE", "GEWICHT");
define("HEALTH_BMIPLOT_TITLE", "QKI");
define("HEALTH_SCOREPLOT_TITLE", "Wert"); 
define("HEALTH_WEIGHTPLOT_DATE_HINT", "Datum");

define("HEALTH_ACCPLOT_TITLE", "Beschleunigungsmessung");
define("HEALTH_TEMPPLOT_TITLE", "Temperatur °C");
define("HEALTH_HRPLOT_TITLE", "PULS");
define("HEALTH_BREATHPLOT_TITLE", "ATEMFREQUENZ");

define("HEALTH_DATABUTTON_START", "Erfassung starten");
define("HEALTH_DATABUTTON_STOP", "Erfassung beenden");

//da modificare nel js?
define("HEALTH_SNACKBAR_CONNECTING", "Verbindung zu BITalino…");
define("HEALTH_SNACKBAR_ERROR", "Fehler: Bitte prüfen Sie Ihre Verbindung zu BITalino.");
define("HEALTH_SNACKBAR_STOP", "Getrennt von BITalino.");

//PLAN PAGE
define("PLAN_SETGOALS_TITLE", "Wochenziele setzen");
define("PLAN_SETGOALS_EXERCISE", "Fitnessübungen (Stunden)");
define("PLAN_SETGOALS_WALK", "Bewegung (Stunden)");
define("PLAN_SETGOALS_MEET", "Verabredungen (Personen)");
define("PLAN_SETGOALS_ATLEAST", "mindestes");
define("PLAN_SETGOALS_MORETHAN", "mehr als");

define("PLAN_SNACKBAR", "Bitte zuerst Zielwert angeben!");

define("PLAN_GOALS_TITLE", "Wochenziele");
define("PLAN_GOALS_EXERCISE", "Fitnessübungen");
define("PLAN_GOALS_WALK", "Bewegung");
define("PLAN_GOALS_MEET", "Verabredungen");
define("PLAN_GOALS_MIN", "min");
define("PLAN_GOALS_HOURS", "Stunden");
define("PLAN_GOALS_STEPS", "Schritte");
define("PLAN_GOALS_PERSONS", "Personen");
define("PLAN_GOALS_COOK", "Kochen");
define("PLAN_GOALS_MODIFY", "ändern");

define("PLAN_CALENDAR_TITLE", "Kalender");
define("PLAN_CALENDAR_INSTRUCTIONS", "Klicken Sie auf den Kalender, um mit der Planung einer Aktivität zu beginnen");
define("PLAN_CALENDAR_EVENT_EXERCISE", "Fitnessübungen");
define("PLAN_CALENDAR_EVENT_WALK", "Bewegung");
define("PLAN_CALENDAR_EVENT_MEET", "Verabredungen");
define("PLAN_CALENDAR_EVENT_COOK", "Kochen");
define("PLAN_SETEVENT_HOUR", "Stunde");
define("PLAN_SETEVENT_MIN", "min");

define("REMOVE_EVENT_BUTTON", "Ereignis entfernen");
define("DONE_BUTTON", "erledigt");
define("UNDONE_BUTTON", "unerledigt");

define("STEPPER_STEP1", "Bestätigen Sie Datum und Uhrzeit");
define("STEPPER_STEP1_ALLDAY", "Den ganzen Tag");
define("STEPPER_STEP1_START", "Startzeit");
define("STEPPER_STEP1_END", "Endzeit");
define("STEPPER_STEP1_ERROR", "Startdatum und -uhrzeit sollten früher als Enddatum und -uhrzeit sein");
define("STEPPER_STEP2", "Geben Sie den Namen der Aktivität ein und wählen Sie den Aktivitätstyp");
define("STEPPER_STEP2_ACTIVITYNAME", "Aktivitätsname");
define("STEPPER_STEP2_ACTIVITYTYPE", "Aktivitätsart");
define("STEPPER_STEP2_ACTIVITYWALK", "Gehen");
define("STEPPER_STEP2_ACTIVITYEXERCISE", "Übung");
define("STEPPER_STEP2_ACTIVITYSOCIAL", "Soziale Aktivität");
define("STEPPER_STEP3", "Geben Sie Aktivitätsdetails ein");
define("STEPPER_STEP3_EXERCISEINTENSITY", "Intensität der Übung:");
define("STEPPER_STEP3_EXERCISEINTENSITYHIGH", "Hoch");
define("STEPPER_STEP3_EXERCISEINTENSITYHIGH_INFO", "Kräftige (intensive) körperliche Aktivitäten beziehen sich auf Aktivitäten, die harte körperliche Anstrengung erfordern und dich viel stärker atmen lassen als normal. Planen Sie nur physische Aktivitäten ein, die mindestens 10 Minuten gleichzeitig ausgeführt werden. <br> Beispiele für anstrengende körperliche Aktivitäten sind schweres Heben, Graben, Aerobic oder schnelles Radfahren. (Oder Sie könnten den Puls der Person verwenden: Kräftige Aktivität: Puls bei 75% oder mehr des maximalen Pulses der Person.)");
define("STEPPER_STEP3_EXERCISEINTENSITYMODERATE", "Mäßig");
define("STEPPER_STEP3_EXERCISEINTENSITYMODERATE_INFO", "Moderate Aktivitäten beziehen sich auf Aktivitäten, die mäßige körperliche Anstrengung erfordern und Sie etwas härter als normal atmen lassen. Planen Sie nur jene körperlichen Aktivitäten ein, die mindestens 10 Minuten lang gleichzeitig ausgeführt werden. <br> Beispiele für moderate körperliche Aktivitäten sind leichte Lasten, Radfahren in regelmäßigen Abständen oder Tennis im Doppel. Geh nicht mit einbeziehen. (Moderate Aktivität: 70-75% des maximalen Pulses.)");
define("STEPPER_STEP3_EXERCISEINTENSITYLOW", "Niedrig");
define("STEPPER_STEP3_EXERCISEINTENSITYLOW_INFO", "Niedrige körperliche Aktivitäten beziehen sich auf Aktivitäten, die wenig körperliche Anstrengung erfordern und Ihnen das Atmen normal machen. Planen Sie nur die physischen Aktivitäten ein, die mindestens 10 Minuten gleichzeitig ausgeführt werden. <br> Beispiel für niedrige körperliche Aktivitäten ist Radfahren in langsamer Geschwindigkeit. Geh nicht mit einbeziehen.");
define("STEPPER_STEP3_WALKSTEPS", "Anzahl der Schritte:");
define("STEPPER_STEP3_SOCIALTYPE", "Art der sozialen Aktivität:");
define("STEPPER_STEP3_SOCIALTYPERECEIVE", "Erhalten Sie Besuch?");
define("STEPPER_STEP3_SOCIALTYPECALL", "Jemanden anrufen");
define("STEPPER_STEP3_SOCIALTYPEVISIT", "Besuche jemanden");
define("STEPPER_STEP3_SOCIALTYPECINEMA", "Kino");
define("STEPPER_STEP3_SOCIALTYPETHEATRE", "Theater");
define("STEPPER_STEP3_SOCIALTYPERESTAURANT", "Restaurant");
define("STEPPER_STEP3_SOCIALTYPEPUB", "Kneipe");
define("STEPPER_STEP3_SOCIALTYPERELIGIOUS", "Religiös");
define("STEPPER_STEP3_SOCIALTYPEOTHER", "Andere");
define("STEPPER_STEP3_SOCIALTYPEOTHERNAME", "Geben Sie den Namen der Aktivität ein");
define("STEPPER_CONTINUE", "FORTSETZEN");
define("STEPPER_BACK", "ZURÜCK");
define("STEPPER_SUBMIT", "Einreichen");
define("STEPPER_CANCEL", "Stornieren");

define("COMPLETED_TITLE", "Hast du diese Aktivität abgeschlossen?");
define("COMPLETED_YES", "JA");
define("COMPLETED_NO", "NEIN");

define("ACTIVITY_NAME", "Name");
define("ACTIVITY_TYPE", "Beschreibung");
define("ACTIVITY_START", "Anfang");
define("ACTIVITY_END", "Ende");
define("ACTIVITY_DELETE", "Löschen");
define("ACTIVITY_EDIT", "Bearbeiten");


//PROFILE PAGE
define("PROFILE_PROFILECARD_TITLE", "Persönliche Angaben");
define("PROFILE_PROFILECARD_NAME", "Vorname");
define("PROFILE_PROFILECARD_SURNAME", "Familienname");
define("PROFILE_PROFILECARD_BIRTHDATE", "Geburtstag");
define("PROFILE_PROFILECARD_GENDER", "Geschlecht");
define("PROFILE_PROFILECARD_GENDER_MALE", "männlich");
define("PROFILE_PROFILECARD_GENDER_FEMALE", "weiblich");
define("PROFILE_PROFILECARD_STATE", "Land");
define("PROFILE_PROFILECARD_POSTALCODE", "Postleitzahl");
define("PROFILE_PROFILECARD_CITY", "Stadt");
define("PROFILE_PROFILECARD_ADDRESS", "Strasse und Hausnummer");

define("PROFILE_EDIT", "PROFIL BEARBEITEN");
define("PROFILE_SAVE", "PROFIL SICHERN");

define("PROFILE_INTERESTS_TITLE", "Interessen");
define("PROFILE_ADDINTERESTS_TITLE", "Interessen hinzufügen");
define("PROFILE_ADDINTERESTS_SPORTS", "Sport");
define("PROFILE_ADDINTERESTS_PROGRAMS", "Fernsehen");
define("PROFILE_ADDINTERESTS_OTHERS", "Sonstiges");
define("PROFILE_ADDINTERESTS_SPORTS_1", "Baseball");
define("PROFILE_ADDINTERESTS_SPORTS_2", "Basketball");
define("PROFILE_ADDINTERESTS_SPORTS_3", "Fussball");
define("PROFILE_ADDINTERESTS_SPORTS_4", "Schwimmen");
define("PROFILE_ADDINTERESTS_SPORTS_5", "Tennis");
define("PROFILE_ADDINTERESTS_SPORTS_6", "Volleyball");
define("PROFILE_ADDINTERESTS_PROGRAMS_1", "Dokumentationen");
define("PROFILE_ADDINTERESTS_PROGRAMS_2", "Nachrichtensendungen");
define("PROFILE_ADDINTERESTS_PROGRAMS_3", "Talkshow");
define("PROFILE_ADDINTERESTS_OTHERS_1", "Kino");
define("PROFILE_ADDINTERESTS_OTHERS_2", "Kochen");
define("PROFILE_ADDINTERESTS_OTHERS_3", "Sehenswürdigkeiten");
define("PROFILE_ADDINTERESTS_OTHERS_4", "Museen");
define("PROFILE_ADDINTERESTS_OTHERS_5", "Theater");
define("PROFILE_INTERESTS_CLOSE", "SCHLIESSEN");


//NEW
//invite a friend to join activity, "einen Freund zu gemeinsamen Aktivitäten einladen"
define("INVITE_FRIEND_BUTTON", "Freund einladen");
define("ACCEPT_INVITATION_BUTTON", "Annehmen");
define("DECLINE_INVITATION_BUTTON", "Ablehnen");
//NEW SET GOALS CARD 
define("PLAN_GOALS_SOCIAL_ACTIVITY", "Soziale Aktivitäten");
define("PLAN_SETGOALS_SOCIAL_ACTIVITY", "Soziale Aktivitäten");
define("PLAN_GOALS_ACTIVITY", "Aktivitäten");

//NEW CALENDAR
define("PLAN_ACTIVITY_ADD", "Vorhaben hinzufügen");
define("PLAN_ACTIVITY_TITLE", "Bezeichnung");
define("PLAN_ACTIVITY_TYPE", "Art des Vorhabens");
define("PLAN_ACTIVITY_TYPE_RECEIVE_GEST", "Gast einladen");
define("PLAN_ACTIVITY_TYPE_CALL_SOMEONE", "Jemanden anrufen");
define("PLAN_ACTIVITY_TYPE_VISIT_SOMEONE", "Jemanden besuchen");    
define("PLAN_ACTIVITY_TYPE_CINEMA", "Ins Kino gehen");
define("PLAN_ACTIVITY_TYPE_THEATRE", "Ins Theater gehen");
define("PLAN_ACTIVITY_TYPE_RESTAURANT", "Essen gehen");
define("PLAN_ACTIVITY_TYPE_PUB", "In eine Beiz gehen");      
define("PLAN_ACTIVITY_TYPE_RELIGIOUS_ACTIVITY", "Gemeindearbeit");
define("PLAN_ACTIVITY_OTHER", "Anderes Vorhaben");
define("PLAN_ACTIVITY_START_TIME", "Beginn");
define("PLAN_ACTIVITY_END_TIME", "Schluss");

define("CALENDAR_NEXT_BUTTON", "NÄCHSTER");
define("CALENDAR_PREVIOUS_BUTTON", "BISHERIGE");
define("CALENDAR_CANCEL_BUTTON", "STORNIEREN");
define("CALENDAR_SUBMIT_BUTTON", "EINREICHEN");
define("CALENDAR_ADVANCED_SETTINGS_BUTTON", "ERWEITERTE EINSTELLUNGEN");

define("PLAN_ACTIVITY_STEP1", "Wählen Sie Start und Endzeit");
define("PLAN_ACTIVITY_STEP2", "Wählen Sie Aktivität");
define("PLAN_ACTIVITY_STEP3", "Erweiterte Einstellungen");


//CONTACTS PAGE

define("CONTACTS_CONTACTSCARD_TITLE", "Kontakte");
define("CONTACTS_CONTACTSCARD_HEADER_NAME", "Name");
define("CONTACTS_CONTACTSCARD_HEADER_STATUS", "Status");
define("CONTACTS_CONTACTSCARD_HEADER_EMAIL", "Email");
define("CONTACTS_CONTACTSCARD_HEADER_PHONE", "Telefon");
define("CONTACTS_CONTACTSCARD_HEADER_ACTIONS", "Aktionen");
define("REMOVE_BUTTON", "LÖSCHEN");
define("PROFILE_REMOVE_DONE", "SCHLIESSEN");

define("CONTACTS_CONTACTSCARD_STATUS_ONLINE", "Online");
define("CONTACTS_CONTACTSCARD_STATUS_OFFLINE", "Offline");
define("CONTACTS_CONTACTSCARD_STATUS_BUSY", "Beschäftigt");

define("CONTACTS_FORM_TITLE", "Kontakt hinzufügen");
define("CONTACTS_FORM_NAME", "Name");
define("CONTACTS_FORM_EMAIL", "Email");
define("CONTACTS_FORM_PHONE", "Telefonnummer");
define("CONTACTS_FORM_RELATIONSHIP", "Beziehung");
define("ADD_BUTTON", "HINZUFÜGEN");

//types of relationships
define("CONTACTS_FROM_RELATIONSHIP_CLOSE_FAMILY", "Enger Familienkreis");
define("CONTACTS_FROM_RELATIONSHIP_OTHER_FAMILY", "Andere Familie");
define("CONTACTS_FROM_RELATIONSHIP_FRIEND", "Freund");
define("CONTACTS_FROM_RELATIONSHIP_NEIGHBOUR", "Nachbar");

//HUE LIGHTS PAGE
define("DISCOVER_BRIDGE", "Bridge finden");
define("SET_USERNAME", "Benutzername angeben");
define("GET_LIGHT_STATE", "Zustand Beleuchtung");
define("TURN_ON_AND_CHANGE_COLOR", "Einschalten und Farbe wechseln");
define("TURN_ON", "Einschalten");
define("TURN_OFF", "Ausschalten");
define("SET_COLOR", "Farbspektrum");
define("SATURATION", "Farbsättigung");
define("BRIGHTNESS", "Helligkeit");
define("LIGHT", "Licht");
define("LIVING_ROOM", "Wohnzimmer");
define("KITCHEN", "Küche");
define("ENTRANCE", "Eingang");

?>
