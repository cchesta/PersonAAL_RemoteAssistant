<!DOCTYPE html>
<?php
       
    include 'miscLib.php';
    include 'DButils.php';
    
?>
<html>
    <head>
        <meta charset="UTF-8">
        <title></title>
        
        <script src="js/plugins/Jquery/jquery-1.9.1.min.js"></script>
        <script src="js/DBinterface.js"></script>
        <script>
        
        $(document).ready(function() {
            
            console.log(getUserLanguage());
            
            getUserData("ajeiie", "123qwe123", function(userData){
                console.log(userData);
            });
        
        });
    
    
    </script>
    </head>
    <body>
        
        <?php
        
        $userdata= new UserData("ajeiie", "123qwe123");
//        $userdata->printUserData();
        echo(json_encode($userdata));
        ?>
    </body>
</html>
