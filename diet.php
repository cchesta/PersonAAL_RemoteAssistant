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
        
      
        
        
        <!--  UI CSS & JS-->
        
        <link rel="stylesheet" href="css/material.css">
        <script src="js/plugins/material_design/material.min.js"></script>
        <script src="js/plugins/Jquery/jquery-1.9.1.min.js"></script>
        
        
        <link rel="stylesheet" href="css/custom.css">
        
        
<!--        <script src='js/jquery-ui.min.js'></script>-->
        <script src='js/plugins/Jquery/jquery.ui.touch-punch.min.js'></script>
        
        <!-- MODALS -->
        <link rel="stylesheet" href="css/bootstrap_modals.css">
        <script src="js/plugins/bootstrap/bootstrap_modals.js"></script>
        
        <!-- javascript functions for DB (ajax requests)-->
        <script src="js/DBinterface.js"></script>
        
        <!-- VELOCITY -->
        <script src="js/plugins/velocity/velocity.min.js"></script>
        <script src="js/plugins/velocity/velocity.ui.min.js"></script>

        <!-- ADAPTATION SCRIPTS -->
        <script src="./js/plugins/adaptation/sockjs-1.1.1.js"></script>
        <script src="./js/plugins/adaptation/stomp.js"></script>
        <script src="./js/plugins/adaptation/websocket-connection.js"></script>		
        <script src="./js/plugins/adaptation/adaptation-script.js"></script>		
        <script src="./js/plugins/adaptation/delegate.js"></script>
        
        <script>
            var snackbar;
            var recipes= [];
	    
	    //search filters
	    var foodTypevalues;
	    var ingredientValues;
	    var minKcal;
	    var maxKcal;
            
            $(document).ready(function() {
                document.getElementById("no-result").style.display= "none";
                
                if($(window).width() <= 479)
                {
                    //remove ripple effect from cards (performance boost on mobile)
                    $('.mdl-card').removeClass("mdl-js-ripple-effect mdl-js-button");
                    
                    //delete all desktop/tablet elements if in phone mode
                    var toRemoveElements= document.querySelectorAll('._delete-phone_');
                    for(var i= 0; i < toRemoveElements.length; i++)
                        toRemoveElements[i].parentNode.removeChild(toRemoveElements[i]);
                    
                     $(window).on( "orientationchange", function( event ) { 
                        document.getElementById("search").className= document.getElementById("search").className.replace("hide-desktop_tablet", "");
                        document.getElementById("search").style.display= "block";
                    } );
                }
                else
                {
                    //delete all phone elements if in desktop/tablet mode
                    var toRemoveElements= document.querySelectorAll('._delete-desktop_');
                    for(var i= 0; i < toRemoveElements.length; i++)
                        toRemoveElements[i].parentNode.removeChild(toRemoveElements[i]);
                }
                
                /*var recipe1= {
                    id: 'recipe1',
                    image: 'img/food1.jpg',
                    title: 'Mixed salad',
                    ingredients: [
                        {
                            ingredient: 'lattuce',
                            quantity: '100gr'
                        },
                        {
                            ingredient: 'olive',
                            quantity: '100gr'
                        },
                        {
                            ingredient: 'oil',
                            quantity: '20gr'
                        }
                    ],
                    foodTypes: [
                        'salad',
                        'vegan'
                    ],
                    instructions: 'Cut all ingredients and add some oil and salt.',
                    prepareTime: 6000,
                    kcal: 100
                };
                var recipe2= {
                    id: 'recipe2',
                    image: 'img/food2.jpg',
                    title: 'Carrots salad',
                    ingredients: [
                        {
                            ingredient: 'carrots',
                            quantity: '100gr'
                        },
                        {
                            ingredient: 'oil',
                            quantity: '20gr'
                        }
                    ],
                    foodType: [
                        'salad',
                        'vegan'
                    ],
                    instructions: 'Cut all ingredients and add some oil and salt.',
                    prepareTime: 6000,
                    kcal: 150
                };
                var recipe3= {
                    id: 'recipe3',
                    image: 'img/food3.jpg',
                    title: 'Fruit salad',
                    ingredients: [
                        {
                            ingredient: 'strawberry',
                            quantity: '50gr'
                        },
                        {
                            ingredient: 'ananas',
                            quantity: '50gr'
                        },
                        {
                            ingredient: 'sugar',
                            quantity: '20gr'
                        }
                    ],
                    foodType: [
                        'fruit',
                        'vegan'
                    ],
                    instructions: 'Cut all ingredients and add the sugar.',
                    prepareTime: 6000,
                    kcal: 150
                };
                var recipe4= {
                    id: 'recipe4',
                    image: 'img/food4.jpg',
                    title: 'Sandwich',
                    ingredients: [
                        {
                            ingredient: 'sandwich',
                            quantity: '100gr',
                            risky: 'gluten'
                        },
                        {
                            ingredient: 'lattuce',
                            quantity: '1 leafe'
                        },
                        {
                            ingredient: 'tomatoe',
                            quantity: '1'
                        },
                        {
                            ingredient: 'cheese',
                            quantity: '20gr'
                        },
                        {
                            ingredient: 'cheese',
                            quantity: '20gr',
                            risky: 'lactose'
                        },
                        {
                            ingredient: 'rocket salad',
                            quantity: '20gr'
                        }
                    ],
                    foodType: [
                        'snack'
                    ],
                    instructions: 'TODO.',
                    prepareTime: 6000,
                    kcal: 250
                };
                var recipe5= {
                    id: 'recipe5',
                    image: 'img/food5.jpg',
                    title: 'Spaghetti with tomatoes and basil',
                    ingredients: [
                        {
                            ingredient: 'spaghetti',
                            quantity: '100gr',
                            risky: 'gluten'
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
                    foodType: [
                        'snack'
                    ],
                    instructions: 'TODO.',
                    prepareTime: 6000,
                    kcal: 300
                };
                var recipe6= {
                    id: 'recipe6',
                    image: 'img/food6.jpg',
                    title: 'Fiorentina steak',
                    ingredients: [
                        {
                            ingredient: 'beef',
                            quantity: '800gr'
                        }
                    ],
                    foodType: [
                        'meat'
                    ],
                    instructions: 'TODO.',
                    prepareTime: 6000,
                    kcal: 400
                };
                var recipe7= {
                    id: 'recipe7',
                    image: 'img/food7.jpg',
                    title: 'Mooncake',
                    ingredients: [
                        {
                            ingredient: 'milk',
                            quantity: '1 lt',
                            risky: 'lactose'
                        },
                        {
                            ingredient: 'chocolate',
                            quantity: '100gr',
                            risky: 'lactose'
                        },
                        {
                            ingredient: 'flour',
                            quantity: '200gr',
                            risky: 'gluten'
                        },
                        {
                            ingredient: 'butter',
                            quantity: '200gr',
                            risky: 'lactose'
                        },
                        {
                            ingredient: 'eggs',
                            quantity: '5'
                        }
                    ],
                    foodType: [
                        'dessert'
                    ],
                    instructions: 'TODO.',
                    prepareTime: 6000,
                    kcal: 600
                };
                
                recipes.push(recipe1);
                recipes.push(recipe2);
                recipes.push(recipe3);
                recipes.push(recipe4);
                recipes.push(recipe5);
                recipes.push(recipe6);
                recipes.push(recipe7);*/
                
                
                //get all diet recipes and create relative cards
                getDietRecipes(drawDietCards, false, null, null, null, null);
                
                //snackbar
                snackbar= document.getElementById("snackbar-alert");
                
                //VELOCITY ANIMATIONS
                if($(window).width() <= 479)
                    $('.recipe-card').velocity('transition.slideUpBigIn', {stagger: 250, display: 'flex'});
                else
                    $('.search-filters-card, .recipe-card').velocity('transition.slideUpBigIn', {stagger: 250, display: 'flex'});
                
                
            });
            
            
            function drawDietCards(dietRecipeList, fromSearch)
            {
                var recipeCardGrid = document.getElementById('recipe-cards-grid');
                $('.recipe-card').remove();
                
                //save exercise list
                recipes= dietRecipeList;
                
                //hide no-result card
                document.getElementById("no-result").style.display= "none";
                
                
                if(recipes === false || recipes.length <= 0)
                {
                    //show no result card 
                    console.log("Nessun risultato!!!!!");
		    
		    document.getElementById('no-result-text').innerHTML= "No results";
                    $('#no-result').velocity('transition.slideUpBigIn', {stagger: 250, display: 'flex'});
                    
                    return;
                }
		
                
                
                for(var i=0; i < dietRecipeList.length; i++)
                    recipeCardGrid.appendChild(createDietCard(dietRecipeList[i]));
                
		//VELOCITY ANIMATIONS
		if($(window).width() <= 479 && fromSearch) //mobile
                {
		    var resultCardText= document.getElementById('no-result-text');
		    resultCardText.innerHTML= "";
		    
		    //create div to show search filters
		    var filterTextDiv= document.createElement("div");
		    filterTextDiv.className="search-filters-text";
		    filterTextDiv.innerHTML= '<b><?php echo(SEARCHRESULT_TITLE);?>:</b>';
		    filterTextDiv.innerHTML+= '<br>Kcal: ' + minKcal + ' - ' + maxKcal;
		    
		    //insert food type list
		    if(foodTypevalues.length > 0)
		    {
			filterTextDiv.innerHTML+= '<br><?php echo(DIET_SEARCHRESULT_FOODCATEGORY);?>:';
			for(var i=0; i < foodTypevalues.length; i++)
			    filterTextDiv.innerHTML+= " " + translateFoodType(foodTypevalues[i]);
		    }
		    
		    //insert allergene list
		    if(ingredientValues.length > 0)
		    {
			filterTextDiv.innerHTML+= '<br><?php echo(DIET_SEARCHRESULT_ALLERGENES);?>:';
			for(var i=0; i < ingredientValues.length; i++)
			    filterTextDiv.innerHTML+= " " + translateAllergene(ingredientValues[i]);
		    }
		    
		    resultCardText.appendChild(filterTextDiv);
		    $('#no-result, .recipe-card').velocity('transition.slideUpBigIn', {stagger: 250, display: 'flex'});
		}
		else
		    $('.recipe-card').velocity('transition.slideUpBigIn', {stagger: 250, display: 'flex'});
		
                
                
            }
            
            
            /*create a fitness card*/
            function createDietCard(recipe)
            {

                //prepare card element
                var card= document.createElement('div');
                card.className= "recipe-card mdl-card mdl-shadow--4dp mdl-js-button mdl-js-ripple-effect mdl-cell mdl-cell--6-col-desktop mdl-cell--2-col-phone mdl-cell--4-col-tablet";
                card.onclick= function() { showRecipeModal(recipe); };
                
                //prepare card title
                var cardTitle= document.createElement('div');
                cardTitle.className= "mdl-card__title mdl-card--expand";
                getRecipeImage(setBackground, cardTitle, recipe.id);
                
                //prepare card supporting text
                var cardSupportingText= document.createElement('div');
                cardSupportingText.className= "mdl-card__supporting-text";
                cardSupportingText.innerHTML= recipe.title;
                
                //prepare card actinos 
                var cardActions= document.createElement('div');
                cardActions.className= "mdl-card__actions mdl-card--border";
                
                //prepare card actinos 
                var cardActionsButton= document.createElement('a');
                cardActionsButton.innerHTML= "<?php echo(SHOW_RECIPE_BUTTON);?>";
                cardActionsButton.className= "mdl-button mdl-button--colored mdl-js-button mdl-js-ripple-effect";
                
                //assemble card
                cardActions.appendChild(cardActionsButton);
                card.appendChild(cardTitle);
                card.appendChild(cardSupportingText);
                card.appendChild(cardActions);
                
                return card;
                
            }
            
            function setBackground(HTMLelement, imageBase64)
            {
                HTMLelement.style.background= 'url(data:image/gif;base64,' + imageBase64 + ') center / cover';
                HTMLelement.style.backgroundRepeat= 'no-repeat';
            }
            
            
            function recipeSearch(){
                //savew min and max calories
                minKcal= parseInt(document.getElementById("minKcal").value);
                maxKcal= parseInt(document.getElementById("maxKcal").value);
                
                //kcal values integrity check
                if(minKcal > maxKcal)
                {
                    var data = {message: '"Max kcal" must be greater then "Min kcal"!'};
                    snackbar.MaterialSnackbar.showSnackbar(data);
                    return;
                }
                    
                //hide no-result card
                document.getElementById("no-result").style.display= "none";
                
                //save food types
                var foodTypeCheckboxes= $("[name=foot-type-options]");
                foodTypevalues= [];
                
                for(var i=0; i < foodTypeCheckboxes.length; i++)
                {
                    if(foodTypeCheckboxes[i].checked)
                        foodTypevalues.push(foodTypeCheckboxes[i].value);
                }
                console.log(foodTypevalues);
                
                
                //save allergies/intolerances ingredients
                var ingredientCheckboxes= $("[name=ingredient-options]");
                ingredientValues= [];
                
                for(var i=0; i < ingredientCheckboxes.length; i++)
                {
                    if(ingredientCheckboxes[i].checked)
                        ingredientValues.push(ingredientCheckboxes[i].value);
                }
                console.log(ingredientValues);
                
                console.log("minKcal: "+ minKcal +" maxKcal: " + maxKcal);
                
                //TODO add robustness controls (undefined attrivutes or null strings
                //get filtered recipes and create relative cards
                getDietRecipes(drawDietCards, true, minKcal, maxKcal, foodTypevalues, ingredientValues);
                
                //dismiss search modal if in mobile version
                if($(window).width() <= 479)
                {
                    $("#search-modal").modal('hide');
                }
            }
            
            
            function showRecipeModal(recipe)
            {
                //console.log(recipe);
                setupRecipeModal(recipe);
                
                $("#recipe-modal").modal(); 
            }
            
            function setupRecipeModal(recipe)
            {
                //set modal background image 
                var modalImageElement= document.getElementById("recipe-modal-image");
                getRecipeImage(setBackground, modalImageElement, recipe.id);
                
                
                //ingredient list
                var ingredientList= document.getElementById("recipe-modal-list");
                ingredientList.innerHTML= "";
                
                for(var i=0; i < recipe.ingredientList.length; i++)
                {
                    var insgredientListElement= document.createElement("li");
                    insgredientListElement.className= "mdl-list__item";
                    
                    var firstSpan= document.createElement("span");
                    firstSpan.className= "mdl-list__item-primary-content";
                    firstSpan.textContent=  recipe.ingredientList[i].ingredient + ' (' + recipe.ingredientList[i].quantity + ')';
                    
                    insgredientListElement.appendChild(firstSpan);
                    
                    ingredientList.appendChild(insgredientListElement);
                }
                
                //recipe title(kcal) and instructions
		document.getElementById("recipe-modal-instruction").innerHTML= "<b>" + recipe.title + " (" + recipe.kcal + " kcal)</b><br>";
                document.getElementById("recipe-modal-instruction").innerHTML+= recipe.instructions;
            }
            
            function translateFoodType(foodType)
            {
                switch(foodType)
                {
                    case 'pasta':
                        return "<?php echo(DIET_SEARCH_FOODTYPE_PASTA);?>";
                        
                    case 'meat':
                        return "<?php echo(DIET_SEARCH_FOODTYPE_MEAT);?>";

                    case 'vegan':
                        return "<?php echo(DIET_SEARCH_FOODTYPE_VEGAN);?>";

                    case 'dessert':
                        return "<?php echo(DIET_SEARCH_FOODTYPE_DESSERT);?>";
                }
            }
            
            function translateAllergene(allergene)
            {
                switch(allergene)
                {
                    case 'gluten':
                        return "<?php echo(DIET_SEARCH_ALLERGIES_GLUTEN);?>";
                        
                    case 'lactose':
                        return "<?php echo(DIET_SEARCH_ALLERGIES_LACTOSE);?>";
                }
            }
        </script>

    </head>
    <body>

        
        
        <!-- The drawer is always open in large screens. The header is always shown, even in small screens. -->
        <div class="mdl-layout mdl-js-layout mdl-layout--fixed-header mdl-layout--fixed-drawer">
            <header class="mdl-layout__header">
                <div class="mdl-layout__header-row">
                    <span class="mdl-layout-title"><?php echo(ENTRY_DIET);?></span>
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
                    <a class="mdl-navigation__link" href="fitness.php"><i class="material-icons">fitness_center</i><?php echo(ENTRY_FITNESS);?></a>
                    <a class="mdl-navigation__link mdl-navigation__link-selected" href="diet.php"><i class="material-icons">restaurant</i><?php echo(ENTRY_DIET);?></a>
                    <a class="mdl-navigation__link" href="services.php"><i class="material-icons">local_grocery_store</i><?php echo(ENTRY_SERVICES);?></a>
		    <a class="mdl-navigation__link" href="profile.php"><i class="material-icons">info</i><?php echo(ENTRY_PROFILE);?></a>
		    <a class="mdl-navigation__link" href="contacts.php"><i class="material-icons">group</i><?php echo(ENTRY_CONTACTS);?></a>
                    <a class="mdl-navigation__link" href="login.php?notify=LOGOUT"><i class="material-icons">power_settings_new</i><?php echo(ENTRY_LOGOUT);?></a>
                </nav>
            </div>
            <main class="mdl-layout__content">
                <div class="page-content">
                    <!-- Your content goes here -->
                    <div class="mdl-grid">

                        <div class="search-filters-card mdl-card mdl-shadow--4dp mdl-cell mdl-cell--4-col-desktop mdl-cell--4-col-phone mdl-cell--3-col-tablet no-stretch _delete-phone_">
                            <div class="mdl-card__title">
                                <h6 class="mdl-card__title-text"><?php echo(SEARCH_TITLE);?></h6>
                            </div>
                            <div class="mdl-card__supporting-text mdl-card--expand">
                                <div class="card-choice-group">
                                    <div class="card-group-label"><?php echo(DIET_SEARCH_FOODTYPE);?>:</div>
                                    <label class="mdl-checkbox mdl-js-checkbox mdl-js-ripple-effect" for="checkbox-pasta">
                                        <input type="checkbox" id="checkbox-pasta" class="mdl-checkbox__input" value="pasta" name="foot-type-options">
                                        <span class="mdl-checkbox__label">
                                            <?php echo(DIET_SEARCH_FOODTYPE_PASTA);?>
                                        </span>
                                    </label>
                                    <label class="mdl-checkbox mdl-js-checkbox mdl-js-ripple-effect" for="checkbox-meat">
                                        <input type="checkbox" id="checkbox-meat" class="mdl-checkbox__input" value="meat" name="foot-type-options">
                                        <span class="mdl-checkbox__label">
                                            <?php echo(DIET_SEARCH_FOODTYPE_MEAT);?>
                                        </span>
                                    </label>
                                    <label class="mdl-checkbox mdl-js-checkbox mdl-js-ripple-effect" for="checkbox-vegan">
                                        <input type="checkbox" id="checkbox-vegan" class="mdl-checkbox__input" value="vegan" name="foot-type-options">
                                        <span class="mdl-checkbox__label">
                                            <?php echo(DIET_SEARCH_FOODTYPE_VEGAN);?>
                                        </span>
                                    </label>
                                    <label class="mdl-checkbox mdl-js-checkbox mdl-js-ripple-effect" for="checkbox-dessert">
                                        <input type="checkbox" id="checkbox-dessert" class="mdl-checkbox__input" value="dessert" name="foot-type-options">
                                        <span class="mdl-checkbox__label">
                                            <?php echo(DIET_SEARCH_FOODTYPE_DESSERT);?>
                                        </span>
                                    </label>
                                </div>
<!--                            MIN-MAX KCAL WITH TEXTFIELD-->
                                <div class="card-choice-textfield">
                                    <div class="mdl-textfield mdl-js-textfield mdl-textfield--floating-label">
                                        <input class="mdl-textfield__input" type="text" pattern="-?[0-9]*(\.[0-9]+)?" id="minKcal" value="100">
                                        <label class="mdl-textfield__label" for="minKcal">Min kcal...</label>
                                        <span class="mdl-textfield__error">
                                            <?php echo(NUMBER_INPUT_ERROR);?>
                                        </span>
                                    </div>
                                </div>
                                <div class="card-choice-textfield">
                                    <div class="mdl-textfield mdl-js-textfield mdl-textfield--floating-label">
                                        <input class="mdl-textfield__input" type="text" pattern="-?[0-9]*(\.[0-9]+)?" id="maxKcal" value="600">
                                        <label class="mdl-textfield__label" for="maxKcal">Max kcal...</label>
                                        <span class="mdl-textfield__error">
                                            <?php echo(NUMBER_INPUT_ERROR);?>
                                        </span>
                                    </div>
                                </div>

                                <div class="card-choice-group">
                                    <div class="card-group-label">
                                        <?php echo(DIET_SEARCH_ALLERGIES);?>
                                    </div>
                                    <label class="mdl-checkbox mdl-js-checkbox mdl-js-ripple-effect" for="checkbox-lactose">
                                        <input type="checkbox" id="checkbox-lactose" class="mdl-checkbox__input" value="lactose" name="ingredient-options">
                                        <span class="mdl-checkbox__label">
                                            <?php echo(DIET_SEARCH_ALLERGIES_LACTOSE);?>
                                        </span>
                                    </label>
                                    <label class="mdl-checkbox mdl-js-checkbox mdl-js-ripple-effect" for="checkbox-gluten">
                                        <input type="checkbox" id="checkbox-gluten" class="mdl-checkbox__input" value="gluten" name="ingredient-options">
                                        <span class="mdl-checkbox__label">
                                            <?php echo(DIET_SEARCH_ALLERGIES_GLUTEN);?>
                                        </span>
                                    </label>
                                </div>
                            </div>
                            <div class="mdl-card__actions mdl-card--border">
                                <a class="mdl-button mdl-button--colored mdl-js-button mdl-js-ripple-effect simple-button" onclick="recipeSearch()">
                                <?php echo(SEARCH_BUTTON);?>
                                </a>

                            </div>
                        </div>


                        <!-- RECIPE -->
                        <div id="recipe-cards-grid" class="mdl-grid mdl-cell no-stretch grid-no-padding mdl-cell mdl-cell--8-col-desktop mdl-cell--4-col-phone mdl-cell--5-col-tablet">

<!--                            <div id="recipe-1" class="recipe-card mdl-card mdl-shadow--4dp mdl-js-button mdl-js-ripple-effect mdl-cell mdl-cell--6-col-desktop mdl-cell--2-col-phone mdl-cell--4-col-tablet"
                            onclick="showRecipeModal(0)" data-food-types="salad vegan" data-ingredients="lettuce olive oil" data-kcal="100">
                                <div class="mdl-card__title recipe1 mdl-card--expand">
                                </div>
                                <div class="mdl-card__supporting-text">
                                    Mixed salad
                                </div>
                                <div class="mdl-card__actions mdl-card--border">
                                    <a class="mdl-button mdl-button--colored mdl-js-button mdl-js-ripple-effect">
                                        SHOW RECIPE
                                    </a>
                                </div>
                            </div>

                            <div id="recipe-2" class="recipe-card mdl-card mdl-shadow--4dp mdl-js-button mdl-js-ripple-effect mdl-cell mdl-cell--6-col-desktop mdl-cell--2-col-phone mdl-cell--4-col-tablet"
                            onclick="showRecipeModal(1)" data-food-types="salad vegan" data-ingredients="carrot oil" data-kcal="150">
                                <div class="mdl-card__title recipe2 mdl-card--expand">
                                </div>
                                <div class="mdl-card__supporting-text">
                                    Carrots salad
                                </div>
                                <div class="mdl-card__actions mdl-card--border">
                                    <a class="mdl-button mdl-button--colored mdl-js-button mdl-js-ripple-effect">
                                        SHOW RECIPE
                                    </a>
                                </div>
                            </div>


                            <div id="recipe-3" class="recipe-card mdl-card mdl-shadow--4dp mdl-js-button mdl-js-ripple-effect mdl-cell mdl-cell--6-col-desktop mdl-cell--2-col-phone mdl-cell--4-col-tablet"
                            onclick="showRecipeModal(2)" data-food-types="fruit vegan" data-ingredients="strawberry ananas sugar" data-kcal="150">
                                <div class="mdl-card__title recipe3 mdl-card--expand" >
                                </div>
                                <div class="mdl-card__supporting-text">
                                    Fruit salad
                                </div>
                                <div class="mdl-card__actions mdl-card--border">
                                    <a class="mdl-button mdl-button--colored mdl-js-button mdl-js-ripple-effect">
                                        SHOW RECIPE
                                    </a>
                                </div>
                            </div>


                            <div id="recipe-4" class="recipe-card mdl-card mdl-shadow--4dp mdl-js-button mdl-js-ripple-effect mdl-cell mdl-cell--6-col-desktop mdl-cell--2-col-phone mdl-cell--4-col-tablet"
                            onclick="showRecipeModal(3)" data-food-types="snack" data-ingredients="sandwich lattuce tomatoe cheese lactose gluten rocket_salad" data-kcal="250">
                                <div class="mdl-card__title recipe4 mdl-card--expand">
                                </div>
                                <div class="mdl-card__supporting-text">
                                    Sandwich
                                </div>
                                <div class="mdl-card__actions mdl-card--border">
                                    <a class="mdl-button mdl-button--colored mdl-js-button mdl-js-ripple-effect">
                                        SHOW RECIPE
                                    </a>
                                </div>
                            </div>
                            
                            <div id="recipe-5" class="recipe-card mdl-card mdl-shadow--4dp mdl-js-button mdl-js-ripple-effect mdl-cell mdl-cell--6-col-desktop mdl-cell--2-col-phone mdl-cell--4-col-tablet"
                            onclick="showRecipeModal(4)" data-food-types="pasta" data-ingredients="spaghetti gluten tomatoe basil" data-kcal="300">
                                <div class="mdl-card__title recipe5 mdl-card--expand">
                                </div>
                                <div class="mdl-card__supporting-text">
                                    Spaghetti with tomatoes and basil
                                </div>
                                <div class="mdl-card__actions mdl-card--border">
                                    <a class="mdl-button mdl-button--colored mdl-js-button mdl-js-ripple-effect">
                                        SHOW RECIPE
                                    </a>
                                </div>
                            </div>
                            
                            <div id="recipe-6" class="recipe-card mdl-card mdl-shadow--4dp mdl-js-button mdl-js-ripple-effect mdl-cell mdl-cell--6-col-desktop mdl-cell--2-col-phone mdl-cell--4-col-tablet"
                            onclick="showRecipeModal(5)" data-food-types="meat" data-ingredients="agnus" data-kcal="400">
                                <div class="mdl-card__title recipe6 mdl-card--expand">
                                </div>
                                <div class="mdl-card__supporting-text">
                                    Fiorentina steak
                                </div>
                                <div class="mdl-card__actions mdl-card--border">
                                    <a class="mdl-button mdl-button--colored mdl-js-button mdl-js-ripple-effect">
                                        SHOW RECIPE
                                    </a>
                                </div>
                            </div>
                            
                            <div id="recipe-7" class="recipe-card mdl-card mdl-shadow--4dp mdl-js-button mdl-js-ripple-effect mdl-cell mdl-cell--6-col-desktop mdl-cell--2-col-phone mdl-cell--4-col-tablet"
                            onclick="showRecipeModal(6)" data-food-types="dessert" data-ingredients="milk chocolate flour butter eggs gluten lactose chocolate" data-kcal="600">
                                <div class="mdl-card__title recipe7 mdl-card--expand">
                                </div>
                                <div class="mdl-card__supporting-text">
                                    Mooncake
                                </div>
                                <div class="mdl-card__actions mdl-card--border">
                                    <a class="mdl-button mdl-button--colored mdl-js-button mdl-js-ripple-effect"">
                                        SHOW RECIPE
                                    </a>
                                </div>
                            </div>-->
                            
                            <div id="no-result" class="simple-card mdl-card mdl-shadow--4dp mdl-cell mdl-cell--12-col-desktop mdl-cell--4-col-phone  mdl-cell--8-col-tablet no-stretch"
                            onclick="window.location='#';">
                                <div id="no-result-text" class="mdl-card__supporting-text mdl-card--expand">
                                    
                                </div>
                            </div>

                        </div>


                        <div class="mdl-cell mdl-cell--12-col-desktop mdl-cell--8-col-tablet mdl-cell--4-col-phone floating-button-fix-cell"></div>
                    </div>
                 
                </div>
            </main>
        </div>   

        
        <!-- FITNESS MODAL -->
        <div id="recipe-modal" class="modal fade" role="dialog">
            <div class="modal-dialog">

                <!-- Modal content-->
                <div class="modal-content">
                    
                    <div class="recipe-modal mdl-card">
<!--			<div class="mdl-card__title">
			    <div id="recipe-modal-title" class="mdl-card__title-text">Exercise of 04/10/2016</div>
			</div>-->
                        <div class="mdl-card__supporting-text mdl-card--expand">
                            
                            
                            <div class="recipe-modal-description">
                                <div id="recipe-modal-image" class="recipe-modal-image mdl-shadow--2dp">
                                </div>
                                <div id="recipe-modal-instruction" class="recipe-modal-instruction">
<!--                                    instruction ajsgduhaus dkuhau akshdkah kushdkauhd kauhskudh kauhskduhaku shkduhaskudh kaushd kuahskudh aksuh kuh kudhaskuhdkua hkushkduha 
                                    instruction ajsgduhaus dkuhau akshdkah kushdkauhd kauhskudh kauhskduhaku shkduhaskudh kaushd kuahskudh aksuh kuh aksuhdka hsdkuhasku
                                    instruction ajsgduhaus dkuhau akshdkah kushdkauhd kauhskudh kauhskduhaku shkduhaskudh kaushd kuahskudh aksuh kuh
                                    instruction ajsgduhaus dkuhau akshdkah kushdkauhd kauhskudh kauhskduhaku shkduhaskudh kaushd kuahskudh aksuh kuh
                                    instruction ajsgduhaus dkuhau akshdkah kushdkauhd kauhskudh kauhskduhaku shkduhaskudh kaushd kuahskudh aksuh kuh-->
                                    
                                </div>
                            </div>
                            <ul id="recipe-modal-list" class="mdl-list mdl-shadow--2dp">
<!--                                <li class="mdl-list__item">
                                    <span class="mdl-list__item-primary-content">
                                        Bryan Cranston
                                    </span>
                                </li>-->
                            </ul>
                            
                            
			</div>
			<div class="mdl-card__actions mdl-card--border">
                            <a id="recipe-modal-done" class="mdl-button mdl-button--colored mdl-js-button mdl-js-ripple-effect" data-dismiss="modal">
				<?php echo(CLOSE_BUTTON);?>
			    </a>
			</div>
		    </div>
                    
                </div>

            </div>
        </div>
        
        
        <!-- SEARCH MODAL FOR MOBILE-->
        <div id="search-modal" class="modal fade _delete-desktop_" role="dialog">
            <div class="modal-dialog">

                <!-- Modal content-->
                <div class="modal-content">
                    
                    <div class="search-filters-card mdl-card expand-in-modal">
                        <div class="mdl-card__title">
                            <h6 class="mdl-card__title-text">
                                <?php echo(SEARCH_TITLE);?>
                            </h6>
                        </div>
                        <div class="mdl-card__supporting-text mdl-card--expand">
                            <div class="card-choice-group">
                                <div class="card-group-label"><?php echo(DIET_SEARCH_FOODTYPE);?>:</div>
                                <label class="mdl-checkbox mdl-js-checkbox mdl-js-ripple-effect" for="checkbox-pasta">
                                    <input type="checkbox" id="checkbox-pasta" class="mdl-checkbox__input" value="pasta" name="foot-type-options">
                                    <span class="mdl-checkbox__label">
                                        <?php echo(DIET_SEARCH_FOODTYPE_PASTA);?>
                                    </span>
                                </label>
                                <label class="mdl-checkbox mdl-js-checkbox mdl-js-ripple-effect" for="checkbox-meat">
                                    <input type="checkbox" id="checkbox-meat" class="mdl-checkbox__input" value="meat" name="foot-type-options">
                                    <span class="mdl-checkbox__label">
                                        <?php echo(DIET_SEARCH_FOODTYPE_MEAT);?>
                                    </span>
                                </label>
                                <label class="mdl-checkbox mdl-js-checkbox mdl-js-ripple-effect" for="checkbox-vegan">
                                    <input type="checkbox" id="checkbox-vegan" class="mdl-checkbox__input" value="vegan" name="foot-type-options">
                                    <span class="mdl-checkbox__label">
                                        <?php echo(DIET_SEARCH_FOODTYPE_VEGAN);?>
                                    </span>
                                </label>
                                <label class="mdl-checkbox mdl-js-checkbox mdl-js-ripple-effect" for="checkbox-dessert">
                                    <input type="checkbox" id="checkbox-dessert" class="mdl-checkbox__input" value="dessert" name="foot-type-options">
                                    <span class="mdl-checkbox__label">
                                        <?php echo(DIET_SEARCH_FOODTYPE_DESSERT);?>
                                    </span>
                                </label>
                            </div>
<!--                            MIN-MAX KCAL WITH TEXTFIELD-->
                            <div class="card-choice-textfield">
                                <div class="mdl-textfield mdl-js-textfield mdl-textfield--floating-label">
                                    <input class="mdl-textfield__input" type="text" pattern="-?[0-9]*(\.[0-9]+)?" id="minKcal" value="100">
                                    <label class="mdl-textfield__label" for="minKcal">Min kcal...</label>
                                    <span class="mdl-textfield__error">
                                        <?php echo(NUMBER_INPUT_ERROR);?>
                                    </span>
                                </div>
                            </div>
                            <div class="card-choice-textfield">
                                <div class="mdl-textfield mdl-js-textfield mdl-textfield--floating-label">
                                    <input class="mdl-textfield__input" type="text" pattern="-?[0-9]*(\.[0-9]+)?" id="maxKcal" value="600">
                                    <label class="mdl-textfield__label" for="maxKcal">Max kcal...</label>
                                    <span class="mdl-textfield__error">
                                        <?php echo(NUMBER_INPUT_ERROR);?>
                                    </span>
                                </div>
                            </div>

                            <div class="card-choice-group">
                                <div class="card-group-label">
                                    <?php echo(DIET_SEARCH_ALLERGIES);?>
                                </div>
                                <label class="mdl-checkbox mdl-js-checkbox mdl-js-ripple-effect" for="checkbox-lactose">
                                    <input type="checkbox" id="checkbox-lactose" class="mdl-checkbox__input" value="lactose" name="ingredient-options">
                                    <span class="mdl-checkbox__label">
                                        <?php echo(DIET_SEARCH_ALLERGIES_LACTOSE);?>
                                    </span>
                                </label>
                                <label class="mdl-checkbox mdl-js-checkbox mdl-js-ripple-effect" for="checkbox-gluten">
                                    <input type="checkbox" id="checkbox-gluten" class="mdl-checkbox__input" value="gluten" name="ingredient-options">
                                    <span class="mdl-checkbox__label">
                                        <?php echo(DIET_SEARCH_ALLERGIES_GLUTEN);?>
                                    </span>
                                </label>
                            </div>
                        </div>
                        <div class="mdl-card__actions mdl-card--border">
                            <a class="mdl-button mdl-button--colored mdl-js-button mdl-js-ripple-effect simple-button" onclick="recipeSearch()">
                            <?php echo(SEARCH_BUTTON);?>
                            </a>

                        </div>
                    </div>
                    
                </div>

            </div>
        </div>
        
        <button id="search" class="mdl-button mdl-shadow--4dp mdl-js-button mdl-button--fab mdl-js-ripple-effect mdl-button--colored floating-button hide-desktop_tablet" data-toggle="modal" data-target="#search-modal">
            <i class="material-icons">search</i>
        </button>
        
        <div id="snackbar-alert" class="mdl-js-snackbar mdl-snackbar">
            <div class="mdl-snackbar__text"></div>
            <button class="mdl-snackbar__action" type="button"></button>
        </div>
        
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
