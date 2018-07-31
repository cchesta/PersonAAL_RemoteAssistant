<?php

include 'miscLib.php';
include 'DButils.php';

// Require composer autoloader
 require __DIR__ . DIRECTORY_SEPARATOR . 'login' . DIRECTORY_SEPARATOR . 'vendor' . DIRECTORY_SEPARATOR . 'autoload.php';
 require __DIR__ . DIRECTORY_SEPARATOR . 'login' . DIRECTORY_SEPARATOR . 'dotenv-loader.php';

 use Auth0\SDK\Auth0;

 $domain        = getenv('AUTH0_DOMAIN');
 $client_id     = getenv('AUTH0_CLIENT_ID');
 $client_secret = getenv('AUTH0_CLIENT_SECRET');
 $redirect_uri  = getenv('AUTH0_CALLBACK_URL');

 $auth0 = new Auth0([
   'domain' => $domain,
   'client_id' => $client_id,
   'client_secret' => $client_secret,
   'redirect_uri' => $redirect_uri,
   'audience' => 'https://' . $domain . '/userinfo',
   'persist_id_token' => true,
   'persist_access_token' => true,
   'persist_refresh_token' => true,
 ]);

 $userInfo = $auth0->getUser();
 $idtoken = $auth0->getIdToken();

 if(!$userInfo)
 {
    echo("<script>console.log('index: No user info');</script>");
    myRedirect("login.php", TRUE);
 }
 else
 {
     echo("<script>console.log('index user_id: ".$userInfo['sub']."');</script>");
     echo("<script>console.log('index nickname: ".$userInfo['nickname']."');</script>");
     $user = $userInfo['sub'];

     //SET Language
//     session_start();
//     $_SESSION['personAAL_user'] = $user;
     setLanguage();
 }

//REDIRECT SU HTTPS
//if(!isset($_SERVER['HTTPS']) || $_SERVER['HTTPS'] == "")
//    HTTPtoHTTPS();

//if(!isCookieEnabled())
//{
//    //TODO handle disabled cookie error
//    //myRedirect("error.php?err=DISABLED_COOKIE", TRUE);
//}
//
//
////SESSIONE
//session_start();
//setLanguage();
//
////verifico se è stato effettuato il login
//if (isset($_SESSION['personAAL_user']) && $_SESSION['personAAL_user'] != "")
//{
//    $t=time();
//    $diff=0;
//    $new=FALSE;
//    
//    //VERIFICO SE LA SESSIONE E' SCADUTA
//    if (isset($_SESSION['personAAL_time']))
//    {
//	$t0=$_SESSION['personAAL_time'];
//	$diff=($t-$t0); // inactivity period
//    }
//    else
//	$new=TRUE;
//        
//    if ($new || ($diff > SESSION_TIMEOUT))
//    { 
//	//DISTRUGGO LA SESSIONE
//	mySessionDestroy();
//	myRedirect("login.php?notify=".SESSION_EXPIRED, TRUE);
//    }
//    else
//	$_SESSION['personAAL_time']=time();  //update time 
//    
//}
//else
//    myRedirect("login.php", TRUE);

?>

    <!DOCTYPE html>
    <!--
To change this license header, choose License Headers in Project Properties.
To change this template file, choose Tools | Templates
and open the template in the editor.
-->
    <html>

    <head>
        <title>PersonAAL</title>
        <meta charset="UTF-8">
        <meta name="viewport" content="width=device-width, initial-scale=1.0">
        <meta http-equiv="Cache-Control" content="no-cache, no-store, must-revalidate" />
        <meta http-equiv="Pragma" content="no-cache" />
        <meta http-equiv="Expires" content="0" />

        <link rel="stylesheet" href="https://fonts.googleapis.com/icon?family=Material+Icons">
        <link rel="stylesheet" href="https://code.getmdl.io/1.2.1/material.min.css">
        <script defer src="https://code.getmdl.io/1.2.1/material.min.js"></script>




        <!--  UI CSS & JS-->
        <link rel="stylesheet" href="css/material.css">
        <script src="js/plugins/material_design/material.min.js"></script>
        <script src="js/plugins/Jquery/jquery-1.9.1.min.js"></script>
        <script src="js/plugins/datatables/datatables.js"></script>


        <link rel="stylesheet" href="css/custom.css">

        <!-- SELECT MENU -->
        <link rel="stylesheet" href="getmdl-select/getmdl-select.min.css">
        <script defer src="getmdl-select/getmdl-select.min.js"></script>


        <!-- MODALS -->
        <link rel="stylesheet" href="css/bootstrap_modals.css">
        <script src="js/plugins/bootstrap/bootstrap_modals.js"></script>

        <!-- javascript functions for DB (ajax requests)-->
        <script src="js/DBinterface.js"></script>

        <!--        <script src='js/jquery-ui.min.js'></script>-->
        <script src='js/plugins/Jquery/jquery.ui.touch-punch.min.js'></script>

        <!-- VELOCITY -->
        <script src="js/plugins/velocity/velocity.min.js"></script>
        <script src="js/plugins/velocity/velocity.ui.min.js"></script>

        <!-- ADAPTATION SCRIPTS -->
        <script type="text/javascript">
            var userName = "<?php echo $_SESSION['personAAL_user']?>";
            var token = "<?php echo $idtoken ?>";
            var userId = "<?php echo $userInfo['sub']?>";
        </script>
        <script src="./js/plugins/adaptation/sockjs-1.1.1.js"></script>
        <script src="./js/plugins/adaptation/stomp.js"></script>
        <script src="./js/plugins/adaptation/websocket-connection.js"></script>
        <script src="./js/plugins/adaptation/adaptation-script.js"></script>
        <script src="./js/plugins/adaptation/delegate.js"></script>
        <script src="./js/plugins/adaptation/jshue.js"></script>
        <script src="./js/plugins/adaptation/command.js"></script>

        <script src="./js/contacts.js"></script>

        <!--        script for tables-->
        <script>
            var socialTable;
            var removeMode;
            var contactList;

            //            rules table
            $(document).ready(function() {

                getContactsFromContextManager(addReceivedContacts);
                removeMode = false;

                //VELOCITY ANIMATIONS
                $('.social-card').velocity('transition.slideUpBigIn', {
                    stagger: 250,
                    display: 'flex'
                });

                var pageLength = 6;
                if ($(window).width() <= 479)
                    pageLength = 5;

                //social table
                var socialDomString = '<"#social-card__title.mdl-card__title">' +
                    '<"mdl-card__supporting-text mdl-card--expand"t>';
                socialTable = $('#socialTable').DataTable({
                    dom: socialDomString,
                    language: {
                        emptyTable: 'asdasd'
                    },
                    oLanguage: {
                        oPaginate: {
                            sNext: '<i class="material-icons">navigate_next</i>',
                            sPrevious: '<i class="material-icons">navigate_before</i>'
                        },
                        sSearch: '<i class="material-icons">search</i>',
                    },
                    pagingType: "simple",
                    pageLength: pageLength
                });


                //                            columnDefs: [
                //                                { width: "200px", targets: 0},
                //                                { width: "10px", targets: 1},
                //                                { width: "20px", targets: 2}
                //                            ]
                var socialCardTitleInnerHTML = '<h6 class="mdl-card__title-text"><?php echo(CONTACTS_CONTACTSCARD_TITLE);?></h6>' +
                    '<div class="mdl-layout-spacer"></div>' +
                    '<div class="mdl-textfield mdl-js-textfield mdl-textfield--expandable' +
                    'mdl-textfield--floating-label mdl-textfield--align-right">' +
                    '<label class="mdl-button mdl-js-button mdl-button--icon"' +
                    'for="social-search-header">' +
                    '<i class="material-icons">search</i>' +
                    '</label>' +
                    '<div class="mdl-textfield__expandable-holder">' +
                    '<input class="mdl-textfield__input" type="text" name="sample"' +
                    'id="social-search-header" aria-control="socialTable">' +
                    '</div>' +
                    '</div>';

                var socialTableCardTitleBox = document.getElementById("social-card__title");
                socialTableCardTitleBox.innerHTML = socialCardTitleInnerHTML;

                $('#social-search-header').on('keyup change', function() {
                    socialTable.column(0).search(this.value).draw();
                });



                //custom next/prev controls
                $('#social-table-next').on('click', function() {
                    socialTable.page('next').draw('page');
                });

                $('#social-table-prev').on('click', function() {
                    socialTable.page('previous').draw('page');
                });


                //check remove buttons, next/prev controls behaviour on page change
                //            $('#socialTable').on( 'page.dt', function () {
                //                adjustPageControls();
                //            } );

                //check remove buttons, next/prev controls behaviour on talbe redraw (page change, deleted/added row, etc...)
                $('#socialTable').on('draw.dt', function() {
                    //console.log('table redraw');
                    adjustPageControls();
                });

                //initial prev/next controls adjustement
                adjustPageControls();

                //onDismiss callback for add contact modal (reset input text field)
                $('#add-contact-modal').on('hidden.bs.modal', function(e) {
                    document.getElementById("add-contact-name").value = "";
                    document.getElementById("add-contact-email").value = "";
                    document.getElementById("add-contact-phone").value = "";
                    document.querySelector('#check1').MaterialCheckbox.uncheck();
                    document.querySelector('#check2').MaterialCheckbox.uncheck();
                    document.querySelector('#check3').MaterialCheckbox.uncheck();
                    document.querySelector('#check4').MaterialCheckbox.uncheck();
                });



            });

            function adjustPageControls() {

                var actualPage = socialTable.page.info().page;
                var pages = socialTable.page.info().pages;

                //console.log(actualPage);
                //console.log(pages);

                document.getElementById("social-table-prev").disabled = false;
                document.getElementById("social-table-next").disabled = false;

                if (pages <= 1) {
                    document.getElementById("social-table-prev").disabled = true;
                    document.getElementById("social-table-next").disabled = true;
                } else {
                    if (actualPage <= 0)
                        document.getElementById("social-table-prev").disabled = true;

                    if (actualPage >= pages - 1)
                        document.getElementById("social-table-next").disabled = true;
                }


                if (removeMode === true)
                    $('.remove-button').show();
                else
                    $('.remove-button').hide();

            }

            function showRemoveButtons() {
                if (socialTable.data().count() !== 0 && removeMode === false) {
                    $('.remove-button').velocity('transition.fadeIn');

                    document.getElementById("remove-contact-button").textContent = "<?php echo(PROFILE_REMOVE_DONE);?>";
                    document.getElementById("remove-contact-button").onclick = function() {
                        hideRemoveButtons();
                    };

                    removeMode = true;
                }

            }

            function hideRemoveButtons() {
                if (removeMode === true) {
                    $('.remove-button').velocity('transition.fadeOut');

                    document.getElementById("remove-contact-button").textContent = "REMOVE";
                    document.getElementById("remove-contact-button").onclick = function() {
                        showRemoveButtons();
                    };

                    removeMode = false;
                }

            }


            function formatAndSendContactList() {
                var contactListFormated = {
                    contacts_list: []
                };

                for (var i in contactList) {
                    var contact = contactList[i];
                    contactListFormated.contacts_list.push({
                        "name": contact.name,
                        "surname": contact.surname,
                        "phone_number": contact.phone_number,
                        "email": contact.email,
                        "relationship_type": contact.relationship_type
                    });
                }

                //console.log(contactListFormated);
                //console.log(JSON.stringify(contactListFormated));

                sendContactsToContextManager(contactListFormated);
            }

            function removeContactByEmail(email) {
                var index = -1;
                for (var i in contactList) {
                    var contact = contactList[i];
                    if (contact.email == email) {
                        index = i;
                        break;
                    }
                }
                if (index > -1)
                    contactList.splice(index, 1);
            }

            function deleteContactConfirm(element) {
                var row = element.parentNode.parentNode;
                var contactEmail = row.getElementsByClassName("contactEmail")[0].innerHTML;
                console.log(contactEmail);

                removeContactByEmail(contactEmail);
                formatAndSendContactList();
                deleteContact(row);
            }

            function deleteContact(row) {
                socialTable.row(row).remove().draw(false);

                //restore remove button if rows count goes to 0
                if (socialTable.data().count() === 0 && removeMode === true) {
                    document.getElementById("remove-contact-button").textContent = "REMOVE";
                    document.getElementById("remove-contact-button").onclick = function() {
                        showRemoveButtons();
                    };

                    removeMode = false;
                }
            }


            function buildRelationshipName() {
                var output = (document.getElementById("checkbox1").checked) ? "1" : "0";
                output += (document.getElementById("checkbox2").checked) ? "1" : "0";
                output += (document.getElementById("checkbox3").checked) ? "1" : "0";
                output += (document.getElementById("checkbox4").checked) ? "1" : "0";

                return output;
            }

            function addContactConfirm() {
                var name = document.getElementById("add-contact-name").value;
                var contactName = name.split(/[\s]+/);
                var contactSurname = contactName.pop(); // gets the last name from contactName and removes it from contactName
                contactName = contactName.toString().split(',').join(' ');
                var email = document.getElementById("add-contact-email").value;
                var phoneNumber = document.getElementById("add-contact-phone").value;
                var relationshipName = buildRelationshipName();

                var newContact = {
                    name: '',
                    surname: '',
                    phone_number: '',
                    email: '',
                    relationship_type: ''
                };
                newContact.name = contactName;
                newContact.surname = contactSurname;
                newContact.phone_number = phoneNumber;
                newContact.email = email;
                newContact.relationship_type = relationshipName;

                contactList.push(newContact);

                addContact(newContact);

                formatAndSendContactList();
            }

            function addReceivedContacts() {
                if (contactList.length > 0 && contactList[0].name.length > 1) {
                    for (var i = 0, len = contactList.length; i < len; i++) {
                        addContact(contactList[i]);
                    }
                } else {
                    contactList = [];
                }
            }

            function addContact(contact) {
                if (contact.name.length !== 0) {
                    var td1 = '<button class="mdl-button mdl-js-button mdl-button--icon mdl-button--colored remove-button" onclick="deleteContactConfirm(this)">' +
                        '<i class="material-icons red">remove_circle</i>' +
                        '</button>' +
                        '<span class="contactName">' + contact.name + ' ' + contact.surname + '</span>';
                    var td2 = '<span class="contactEmail mdl-chip__text">' + contact.email + '</span>';
                    var td3 = '<span class="contactEmail mdl-chip__text">' + contact.phone_number + '</span>';
                    var td4 = '<button class="mdl-button mdl-js-button mdl-button--icon mdl-button--colored">' +
                        '<i class="material-icons">message</i>' +
                        '</button>';

                    //add row
                    socialTable.row.add([
                        td1,
                        td2,
                        td3,
                        td4
                    ]).draw(false);

                }
            }
        </script>




    </head>

    <body>



        <!-- The drawer is always open in large screens. The header is always shown, even in small screens. -->
        <div class="mdl-layout mdl-js-layout mdl-layout--fixed-header mdl-layout--fixed-drawer">
            <header class="mdl-layout__header">
                <div class="mdl-layout__header-row">
                    <span class="mdl-layout-title"><?php echo(ENTRY_CONTACTS);?></span>
                    <div class="mdl-layout-spacer"></div>
                    <button class="mdl-button mdl-button--icon mdl-js-button mdl-js-ripple-effect" onclick="window.location='index.php';">
                        <i class="material-icons">home</i>
                    </button>
                </div>
            </header>
            <div class="mdl-layout__drawer">
                <span class="mdl-layout-title"><?php echo(MENU_TITLE);?></span>
                <nav class="mdl-navigation">
                    <a class="mdl-navigation__link" href="index.php"><i class="material-icons">home</i><?php echo(ENTRY_HOME);?></a>
                    <a class="mdl-navigation__link" href="health.php"><i class="material-icons">local_hospital</i><?php echo(ENTRY_HEALTH);?></a>
                    <a class="mdl-navigation__link" href="plan.php"><i class="material-icons">date_range</i><?php echo(ENTRY_PLAN);?></a>
                    <a class="mdl-navigation__link" href="profile.php"><i class="material-icons">info</i><?php echo(ENTRY_PROFILE);?></a>
                    <a class="mdl-navigation__link mdl-navigation__link-selected" href="contacts.php"><i class="material-icons">group</i><?php echo(ENTRY_CONTACTS);?></a>
                    <a class="mdl-navigation__link" href="huelights.php"><i class="material-icons">flare</i><?php echo(ENTRY_HUELIGHTS);?></a>
                    <a class="mdl-navigation__link" href="logout.php"><i class="material-icons">power_settings_new</i><?php echo(ENTRY_LOGOUT);?></a>

                </nav>
            </div>
            <main class="mdl-layout__content">
                <div class="page-content">
                    <!-- Your content goes here -->
                    <div class="mdl-grid">


                        <div id="social-card" class="social-card mdl-card mdl-shadow--4dp mdl-cell mdl-cell--12-col-desktop mdl-cell--4-col-phone mdl-cell--8-col-tablet">
                            <table id="socialTable" class="mdl-data-table" cellspacing="0" width="100%">
                                <thead>
                                    <tr>
                                        <th>
                                            <?php echo(CONTACTS_CONTACTSCARD_HEADER_NAME);?>
                                        </th>
                                        <th>
                                            <?php echo(CONTACTS_CONTACTSCARD_HEADER_EMAIL);?>
                                        </th>
                                        <th>
                                            <?php echo(CONTACTS_CONTACTSCARD_HEADER_PHONE);?>
                                        </th>
                                        <th>
                                            <?php echo(CONTACTS_CONTACTSCARD_HEADER_ACTIONS);?>
                                        </th>
                                    </tr>
                                </thead>

                                <tbody id="contact_table">

                                </tbody>
                            </table>

                            <div class="mdl-card__actions">
                                <a id="remove-contact-button" class="mdl-button mdl-button--colored mdl-js-button mdl-js-ripple-effect" onclick="showRemoveButtons()">
                                    <?php echo(REMOVE_BUTTON);?>
                                </a>
                                <div class="mdl-layout-spacer"></div>
                                <button id="social-table-prev" class="mdl-button mdl-js-button mdl-button--icon">
                                    <i class="material-icons">keyboard_arrow_left</i>
                                </button>
                                <button id="social-table-next" class="mdl-button mdl-js-button mdl-button--icon">
                                    <i class="material-icons">keyboard_arrow_right</i>
                                </button>
                            </div>
                        </div>

                    </div>

                </div>
            </main>
        </div>


        <div id="add-contact-modal" class="modal fade" role="dialog">
            <div class="modal-dialog">

                <!-- Modal content-->
                <div class="modal-content">

                    <div class="add-contact-modal mdl-card">
                        <div class="mdl-card__title">
                            <div class="mdl-card__title-text">
                                <?php echo(CONTACTS_FORM_TITLE);?>
                            </div>
                        </div>
                        <div class="mdl-card__supporting-text mdl-card--expand">

                            <div class="mdl-textfield mdl-js-textfield mdl-textfield--floating-label">
                                <input class="mdl-textfield__input" type="text" id="add-contact-name" value="">
                                <label class="mdl-textfield__label" for="add-contact-name">
                                    <?php echo(CONTACTS_FORM_NAME);?>
                                </label>
                            </div>

                            <div class="mdl-textfield mdl-js-textfield mdl-textfield--floating-label">
                                <input class="mdl-textfield__input" type="email" id="add-contact-email" value="">
                                <label class="mdl-textfield__label" for="add-contact-email">
                                    <?php echo(CONTACTS_FORM_EMAIL);?>
                                </label>
                            </div>

                            <div class="mdl-textfield mdl-js-textfield mdl-textfield--floating-label">
                                <input class="mdl-textfield__input" type="text" pattern="-?[0-9]*(\.[0-9]+)?" id="add-contact-phone" value="">
                                <label class="mdl-textfield__label" for="add-contact-phone">
                                    <?php echo(CONTACTS_FORM_PHONE);?>
                                </label>
                                <span class="mdl-textfield__error">
                                    <?php echo(NUMBER_INPUT_ERROR);?>
                                </span>
                            </div>


                            <!--Choose relationship type -->
                            <div class="mdl-textfield mdl-js-textfield mdl-textfield--floating-label">
                                <h5>
                                    <?php echo(CONTACTS_FORM_RELATIONSHIP);?>
                                </h5>
                                <label id="check1" class="mdl-checkbox mdl-js-checkbox mdl-js-ripple-effect" for="checkbox1">
                                    <input type="checkbox" id="checkbox1" class="mdl-checkbox__input">
                                    <span class="mdl-checkbox__label"><?php echo(CONTACTS_FROM_RELATIONSHIP_CLOSE_FAMILY);?></span>
                                </label>
                                <label id="check2" class="mdl-checkbox mdl-js-checkbox mdl-js-ripple-effect" for="checkbox2">
                                    <input type="checkbox" id="checkbox2" class="mdl-checkbox__input">
                                    <span class="mdl-checkbox__label"><?php echo(CONTACTS_FROM_RELATIONSHIP_OTHER_FAMILY);?></span>
                                </label>
                                <label id="check3" class="mdl-checkbox mdl-js-checkbox mdl-js-ripple-effect" for="checkbox3">
                                    <input type="checkbox" id="checkbox3" class="mdl-checkbox__input">
                                    <span class="mdl-checkbox__label"><?php echo(CONTACTS_FROM_RELATIONSHIP_FRIEND);?></span>
                                </label>
                                <label id="check4" class="mdl-checkbox mdl-js-checkbox mdl-js-ripple-effect" for="checkbox4">
                                    <input type="checkbox" id="checkbox4" class="mdl-checkbox__input" >
                                    <span class="mdl-checkbox__label"><?php echo(CONTACTS_FROM_RELATIONSHIP_NEIGHBOUR);?></span>
                                </label>
                            </div>


                        </div>
                        <div class="mdl-card__actions mdl-card--border">

                            <a id="add-contact-modal-done" class="mdl-button mdl-button--colored mdl-js-button mdl-js-ripple-effect" data-dismiss="modal">
                                <?php echo(CANCEL_BUTTON);?>
                            </a>
                            <a id="add-contact-modal-done" class="mdl-button mdl-button--colored mdl-js-button mdl-js-ripple-effect" data-dismiss="modal" onclick="addContactConfirm()">
                                <?php echo(ADD_BUTTON);?>
                            </a>
                        </div>
                    </div>

                </div>

            </div>
        </div>



        <button id="add-contact" class="mdl-button mdl-shadow--4dp mdl-js-button mdl-button--fab mdl-js-ripple-effect mdl-button--colored floating-button" data-toggle="modal" data-target="#add-contact-modal" onclick="hideRemoveButtons()">
            <i class="material-icons">add</i>
        </button>




        <!-- ALERT MODAL -->
        <div id="alert-modal" class="modal fade" role="dialog">
            <div class="modal-dialog">

                <!-- Modal content-->
                <div class="modal-content">

                    <div class="alert-modal-card mdl-card">
                        <div class="mdl-card__supporting-text mdl-card--expand">
                            <i class="material-icons">warning</i>
                            <div id="modal-alert-text">
                            </div>
                        </div>
                        <div class="mdl-card__actions mdl-card--border">
                            <a class="mdl-button mdl-button--colored mdl-js-button mdl-js-ripple-effect" data-dismiss="modal">
                                <?php echo(CLOSE_BUTTON);?>
                            </a>
                        </div>
                    </div>

                </div>

            </div>
        </div>

    </body>

    </html>