<?php
session_start();

//get data from data base 
include "config.php";

?>
<html>
    <head>
        <link rel="stylesheet" type="text/css" href="aceuil.css">
        <link rel="shortout icon" type="image/x-icon" href="logo.png">
        <link rel="stylesheet" type="text/css" href="aceuil-res.css">
        <title>Doctor</title>
        <meta charset="utf-8">
        <meta name="viewport" content="width=device-width , initial-scale=1.0">
    </head>
    <body >
        <div id="cont">
            <img src="logo.png" alt="logo" id="logo">
            <h1 id="name">DOCTOR</h1>
            <p id="nn">medical clinic management</p>



            <p id="welcome">Entrer votre informations :</p>



            <form id="form" name="logf" method="POST"  onsubmit="return validateForm()" action="aceuil.php" >

                <input name="user" id="user" placeholder="nom d' utilisateur" type="text" required>
                <input name="pass" id="pass" placeholder="mot de pass" type="password" required>
                <input style="height: 40px;
                background-color:#00cccc;
                color:white;
                border-radius:5px;
                font-size:20px;"  id="submit" name="se_connecter" type="submit" value="se connecter">

                <?php

        /* Check if login form has been submitted */
        if(isset($_POST['se_connecter']) ){
            $bool=0;

            if (isset($_POST['user'])and isset($_POST['pass'])){

                $Username=$_POST['user']; 
                $Password=$_POST['pass'];
                
                
                $reponse= $conn->query('select * from user');
                while ($donne = $reponse->fetch()) {
                    if ($donne['username']==$Username and $donne['password']==$Password){
                        $bool=1;
                        $job=$donne['job'];
                    }
            }
            if ($bool==1 ) {
                $_SESSION['Active'] = true;
                if ( $job=="doctor") {
                    # code...
                    header("location:doctor.php");
                    exit;
                } else {
                    # code...
                    header("location:secretair.php");
                    exit;
                }
                
               



            }
            else{
                ?>
                
                <script>
                    document.getElementById("welcome").innerHTML= "Incorrect informations !";
                    document.getElementById("welcome").style.color = 'red';
                </script>
                <?php
                
            }

          

            }
        }
    
        ?>


            </form> 
</div>

    </body>
</html>