<!DOCTYPE html>
<!--
To change this license header, choose License Headers in Project Properties.
To change this template file, choose Tools | Templates
and open the template in the editor.
-->
<?php

include 'miscLib.php';
include 'DButils.php';


//REDIRECT SU HTTPS
//if(!isset($_SERVER['HTTPS']) || $_SERVER['HTTPS'] == "")
//    HTTPtoHTTPS();

if(!isCookieEnabled())
{
    //TODO handle disabled cookie error
    //myRedirect("error.php?err=DISABLED_COOKIE", TRUE);
}


//SESSIONE
session_start();
setLanguage();

//verifico se è stato effettuato il login
if (isset($_SESSION['personAAL_user']) && $_SESSION['personAAL_user'] != "")
{
    $t=time();
    $diff=0;
    $new=FALSE;
    
    //VERIFICO SE LA SESSIONE E' SCADUTA
    if (isset($_SESSION['personAAL_time']))
    {
	$t0=$_SESSION['personAAL_time'];
	$diff=($t-$t0); // inactivity period
    }
    else
	$new=TRUE;
        
    if ($new || ($diff > SESSION_TIMEOUT))
    { 
	//DISTRUGGO LA SESSIONE
	mySessionDestroy();
	myRedirect("login.php?notify=".SESSION_EXPIRED, TRUE);
    }
    else
	$_SESSION['personAAL_time']=time();  //update time 
    
}
else
    myRedirect("login.php", TRUE);

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
<!--        <link rel="stylesheet" href="https://code.getmdl.io/1.2.1/material.min.css">
        <script defer src="https://code.getmdl.io/1.2.1/material.min.js"></script>-->
        
      
        
        
        <!--  UI CSS & JS-->
        <link rel="stylesheet" href="css/material.css">
        <script src="js/plugins/material_design/material.min.js"></script>
        <script src="js/plugins/Jquery/jquery-1.9.1.min.js"></script>
        <script src="js/plugins/datatables/datatables.js"></script>
        
        
        <link rel="stylesheet" href="css/custom.css">
        
        
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
        <script src="./js/plugins/adaptation/sockjs-1.1.1.js"></script>
        <script src="./js/plugins/adaptation/stomp.js"></script>
        <script src="./js/plugins/adaptation/websocket-connection.js"></script>		
        <script src="./js/plugins/adaptation/adaptation-script.js"></script>		
        <script src="./js/plugins/adaptation/delegate.js"></script>
        

<!--        script for tables-->
        <script>
            var socialTable;
            var removeMode;
            
            
//            rules table
            $(document).ready(function() {
            
            removeMode= false;
                
            //VELOCITY ANIMATIONS
            $('.social-card').velocity('transition.slideUpBigIn', {stagger: 250, display: 'flex'});    
                
	    var pageLength= 6;
	    if($(window).width() <= 479)
		pageLength= 5;
		
            //social table
            var socialDomString= '<"#social-card__title.mdl-card__title">'+
                            '<"mdl-card__supporting-text mdl-card--expand"t>';
            socialTable= $('#socialTable').DataTable( {
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
                          } );
                    
                    
//                            columnDefs: [
//                                { width: "200px", targets: 0},
//                                { width: "10px", targets: 1},
//                                { width: "20px", targets: 2}
//                            ]
            var socialCardTitleInnerHTML= '<h6 class="mdl-card__title-text">Contacts</h6>'+
                                    '<div class="mdl-layout-spacer"></div>'+
                                    '<div class="mdl-textfield mdl-js-textfield mdl-textfield--expandable'+
                                            'mdl-textfield--floating-label mdl-textfield--align-right">'+
                                  '<label class="mdl-button mdl-js-button mdl-button--icon"'+
                                         'for="social-search-header">'+
                                    '<i class="material-icons">search</i>'+
                                  '</label>'+
                                  '<div class="mdl-textfield__expandable-holder">'+
                                    '<input class="mdl-textfield__input" type="text" name="sample"'+
                                           'id="social-search-header" aria-control="socialTable">'+
                                  '</div>'+
                                '</div>';
        
            var socialTableCardTitleBox= document.getElementById("social-card__title");
            socialTableCardTitleBox.innerHTML= socialCardTitleInnerHTML;
            
            $('#social-search-header').on('keyup change', function () {
                socialTable.column(0).search(this.value).draw();
            });
			    
            
            
            //custom next/prev controls
            $('#social-table-next').on( 'click', function () {
                socialTable.page( 'next' ).draw( 'page' );
            } );
            
            $('#social-table-prev').on( 'click', function () {
                socialTable.page( 'previous' ).draw( 'page' );
            } );
        
        
            //check remove buttons, next/prev controls behaviour on page change
//            $('#socialTable').on( 'page.dt', function () {
//                adjustPageControls();
//            } );
            
            //check remove buttons, next/prev controls behaviour on talbe redraw (page change, deleted/added row, etc...)
            $('#socialTable').on( 'draw.dt', function () {
                console.log( 'table redraw' );
                adjustPageControls();
            } );
            
            //initial prev/next controls adjustement
            adjustPageControls();
        
            //onDismiss callback for add contact modal (reset input text field)
            $('#add-contact-modal').on('hidden.bs.modal', function (e) {
                document.getElementById("add-contact-name").value= "";
                document.getElementById("add-contact-phone").value= "";
            });
            
            
        
        } );
        
        function adjustPageControls(){
            
            var actualPage= socialTable.page.info().page;
            var pages= socialTable.page.info().pages;
            
            console.log(actualPage);
            console.log(pages);
            
            document.getElementById("social-table-prev").disabled= false;
            document.getElementById("social-table-next").disabled= false;
            
            if(pages <= 1)
            {
                document.getElementById("social-table-prev").disabled= true;
                document.getElementById("social-table-next").disabled= true;
            }
            else
            {
                if(actualPage <= 0)
                    document.getElementById("social-table-prev").disabled= true;
                    
                if(actualPage >= pages-1)
                    document.getElementById("social-table-next").disabled= true;
            }
            

            if(removeMode === true)
                $('.remove-button').show();
            else
                $('.remove-button').hide();
                
        }
        
        function showRemoveButtons()
        {
            if(socialTable.data().count() !== 0 && removeMode === false)
            {
                $('.remove-button').velocity('transition.fadeIn');
                
                document.getElementById("remove-contact-button").textContent= "DONE";
                document.getElementById("remove-contact-button").onclick= function(){ hideRemoveButtons(); };
                
                removeMode= true;
            }
            
        }
        
        function hideRemoveButtons()
        {
            if(removeMode === true)
            {
                $('.remove-button').velocity('transition.fadeOut');
                
                document.getElementById("remove-contact-button").textContent= "REMOVE";
                document.getElementById("remove-contact-button").onclick= function(){ showRemoveButtons(); };
                
                removeMode= false;
            }
            
        }
        
	function deleteContactConfirm(element)
	{
            var row= element.parentNode.parentNode;
	    var contactName= row.getElementsByClassName("contactName")[0].innerHTML;
	    contactName= contactName.replace(/\s+/g, '');
	    console.log('||' + contactName + '||');
	    
	    deleteUserContact(contactName, row, deleteContact, null);
	}
	
        function deleteContact(row)
        {
//            console.log(element.parentNode.parentNode);
//            var row= element.parentNode.parentNode;
//	    var contactName= row.getElementsByClassName("contactName")[0].innerHTML;
//	    console.log(contactName);
            socialTable.row(row).remove().draw(false);
            
            //restore remove button if rows count goes to 0
            if(socialTable.data().count() === 0 && removeMode === true)
            {   
                document.getElementById("remove-contact-button").textContent= "REMOVE";
                document.getElementById("remove-contact-button").onclick= function(){ showRemoveButtons(); };
                
                removeMode= false;
            }
        }
        
	function addContactConfirm()
	{
	    var contactName= document.getElementById("add-contact-name").value;
	    var phoneName= document.getElementById("add-contact-phone").value;
	    
	    addUserContact(contactName, phoneName, addContact, null);
	}
	
        function addContact(contactName)
        {
            if(contactName.length !== 0)
            {
                var td1= '<button class="mdl-button mdl-js-button mdl-button--icon mdl-button--colored remove-button" onclick="deleteContactConfirm(this)">'+
                                '<i class="material-icons red">remove_circle</i>'+
                            '</button>'+
			    '<span class="contactName">'+ contactName +'</span>';
                var td2= '<span class="mdl-chip mdl-chip-offline">'+
                                '<span class="mdl-chip__text">offline</span>'+
                            '</span>';
                var td3= '<button class="mdl-button mdl-js-button mdl-button--icon mdl-button--colored" disabled>'+
                                                '<i class="material-icons">call</i>'+
                                            '</button>'+
                                            '<button class="mdl-button mdl-js-button mdl-button--icon mdl-button--colored" disabled>'+
                                                '<i class="material-icons">video_call</i>'+
                                            '</button>'+
                                            '<button class="mdl-button mdl-js-button mdl-button--icon mdl-button--colored">'+
                                                '<i class="material-icons">message</i>'+
                                            '</button>';
                
                //add row
                socialTable.row.add([
                    td1,
                    td2,
                    td3
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
<!--                    <a class="mdl-navigation__link" href="fitness.php"><i class="material-icons">fitness_center</i><?php echo(ENTRY_FITNESS);?></a>
                    <a class="mdl-navigation__link" href="diet.php"><i class="material-icons">restaurant</i><?php echo(ENTRY_DIET);?></a>
                    <a class="mdl-navigation__link" href="services.php"><i class="material-icons">local_grocery_store</i><?php echo(ENTRY_SERVICES);?></a>-->
		    <a class="mdl-navigation__link" href="profile.php"><i class="material-icons">info</i><?php echo(ENTRY_PROFILE);?></a>
		    <a class="mdl-navigation__link mdl-navigation__link-selected" href="contacts.php"><i class="material-icons">group</i><?php echo(ENTRY_CONTACTS);?></a>
                    <a class="mdl-navigation__link" href="login.php?notify=LOGOUT"><i class="material-icons">power_settings_new</i><?php echo(ENTRY_LOGOUT);?></a>
                </nav>
            </div>
            <main class="mdl-layout__content">
                <div class="page-content">
                <!-- Your content goes here -->
                    <div class="mdl-grid">


                        <div id="social-card" class="social-card mdl-card mdl-shadow--4dp mdl-cell mdl-cell--8-col-desktop mdl-cell--4-col-phone mdl-cell--8-col-tablet">
                            <table id="socialTable" class="mdl-data-table" cellspacing="0" width="100%">
                                <thead>
                                    <tr>
                                        <th><?php echo(CONTACTS_CONTACTSCARD_HEADER_NAME);?></th>
                                        <th><?php echo(CONTACTS_CONTACTSCARD_HEADER_STATUS);?></th>
                                        <th><?php echo(CONTACTS_CONTACTSCARD_HEADER_ACTIONS);?></th>
                                    </tr>
                                </thead>
                                <tbody>
                                    <?php
                                    
                                    $contactList= UserContacts::getContacts($_SESSION['personAAL_user']);
                                    
                                    if($contactList != null && count($contactList) > 0)
                                    {
                                        //print all contacts in the table
                                        foreach($contactList as $contact)
                                        {
                                            echo('<tr>
                                                    <td>
                                                        <button class="mdl-button mdl-js-button mdl-button--icon mdl-button--colored remove-button" onclick="deleteContactConfirm(this)">
                                                            <i class="material-icons red">remove_circle</i>
                                                        </button>
							<span class="contactName">
                                                        '. $contact->contactName .'
							</span>
                                                    </td>'
                                            );
                                        
                                        
                                            //contact status related variables
                                            $callStatus= null;
                                            $videoCallStatus= null;
                                            $statusChipClass= null;
                                            $statusString= null;
                                            
                                            switch($contact->status)
                                            {
                                                case "online":
                                                    $statusChipClass= "mdl-chip-online";
                                                    $statusString= CONTACTS_CONTACTSCARD_STATUS_ONLINE;
                                                    break;
                                                
                                                case "offline":
                                                    $callStatus= "disabled";
                                                    $videoCallStatus= "disabled";
                                                    $statusChipClass= "mdl-chip-offline";
                                                    $statusString= CONTACTS_CONTACTSCARD_STATUS_OFFLINE;
                                                    break;
                                                
                                                case "busy":
                                                    $videoCallStatus= "disabled";
                                                    $statusChipClass= "mdl-chip-busy";
                                                    $statusString= CONTACTS_CONTACTSCARD_STATUS_BUSY;
                                                    break;
                                            }
                                            
                                            echo('  <td>
                                                        <span class="mdl-chip '. $statusChipClass .'">
                                                            <span class="mdl-chip__text">'.$statusString.'</span>
                                                        </span>
                                                    </td>'
                                            );
                                            echo('  <td>
                                                        <button class="mdl-button mdl-js-button mdl-button--icon mdl-button--colored" '. $callStatus .'>
                                                            <i class="material-icons">call</i>
                                                        </button>
                                                        <button class="mdl-button mdl-js-button mdl-button--icon mdl-button--colored" '. $videoCallStatus .'>
                                                            <i class="material-icons">video_call</i>
                                                        </button>
                                                        <button class="mdl-button mdl-js-button mdl-button--icon mdl-button--colored">
                                                            <i class="material-icons">message</i>
                                                        </button>
                                                    </td>
                                                </tr>'
                                            );
                                        }
                                        
                                                
                                    }
                                    
                                    ?>
                                    
<!--                                <tr>
                                        <td>
                                            <button class="mdl-button mdl-js-button mdl-button--icon mdl-button--colored remove-button" onclick="deleteContact(this)">
                                                <i class="material-icons red">remove_circle</i>
                                            </button>
                                            Son
                                        </td>
                                        <td>
                                            <span class="mdl-chip mdl-chip-online">
                                                <span class="mdl-chip__text">active</span>
                                            </span>
                                        </td>
                                        <td>
                                            <button class="mdl-button mdl-js-button mdl-button--icon mdl-button--colored">
                                                <i class="material-icons">call</i>
                                            </button>
                                            <button class="mdl-button mdl-js-button mdl-button--icon mdl-button--colored">
                                                <i class="material-icons">video_call</i>
                                            </button>
                                            <button class="mdl-button mdl-js-button mdl-button--icon mdl-button--colored">
                                                <i class="material-icons">message</i>
                                            </button>
                                        </td>
                                    </tr>
                                    <tr>
                                        <td>
                                            <button class="mdl-button mdl-js-button mdl-button--icon mdl-button--colored remove-button" onclick="deleteContact(this)">
                                                <i class="material-icons red">remove_circle</i>
                                            </button>
                                            Medic
                                        </td>
                                        <td>
                                            <span class="mdl-chip mdl-chip-busy">
                                                <span class="mdl-chip__text">busy</span>
                                            </span>
                                        </td>
                                        <td>
                                            <button class="mdl-button mdl-js-button mdl-button--icon mdl-button--colored" disabled>
                                                <i class="material-icons">call</i>
                                            </button>
                                            <button class="mdl-button mdl-js-button mdl-button--icon mdl-button--colored" disabled>
                                                <i class="material-icons">video_call</i>
                                            </button>
                                            <button class="mdl-button mdl-js-button mdl-button--icon mdl-button--colored">
                                                <i class="material-icons">message</i>
                                            </button>
                                        </td>
                                    </tr>
                                    <tr>
                                        <td>
                                            <button class="mdl-button mdl-js-button mdl-button--icon mdl-button--colored remove-button" onclick="deleteContact(this)">
                                                <i class="material-icons red">remove_circle</i>
                                            </button>
                                            Psychologist
                                        </td>
                                        <td>
                                            <span class="mdl-chip mdl-chip-offline">
                                                <span class="mdl-chip__text">offline</span>
                                            </span>
                                        </td>
                                        <td>
                                            <button class="mdl-button mdl-js-button mdl-button--icon mdl-button--colored" disabled>
                                                <i class="material-icons">call</i>
                                            </button>
                                            <button class="mdl-button mdl-js-button mdl-button--icon mdl-button--colored" disabled>
                                                <i class="material-icons">video_call</i>
                                            </button>
                                            <button class="mdl-button mdl-js-button mdl-button--icon mdl-button--colored">
                                                <i class="material-icons">message</i>
                                            </button>
                                        </td>
                                        
                                    </tr>-->
                                    
                                    
                                    
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
                                <input class="mdl-textfield__input" type="text" pattern="-?[0-9]*(\.[0-9]+)?" id="add-contact-phone" value="">
                                <label class="mdl-textfield__label" for="add-contact-phone">
                                    <?php echo(CONTACTS_FORM_PHONE);?>
                                </label>
                                <span class="mdl-textfield__error">
                                    <?php echo(NUMBER_INPUT_ERROR);?>
                                </span>
                            </div>
                            
			</div>
			<div class="mdl-card__actions mdl-card--border">
                            <a id="add-contact-modal-done" class="mdl-button mdl-button--colored mdl-js-button mdl-js-ripple-effect" data-dismiss="modal">
				<?php echo(CLOSE_BUTTON);?>
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
				<?php echo(SEND_MESSAGE_BUTTON);?>
			    </a>
			</div>
		    </div>
                    
                </div>

            </div>
        </div>
        
    </body>
    
</html>
