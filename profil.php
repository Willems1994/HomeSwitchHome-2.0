<?php require_once("config.php"); ?>

<!DOCTYPE html PUBLIC "-//W3C//DTD XHTML 1.0 Strict//EN" "http://www.w3.org/TR/xhtml1/DTD/xhtml1-strict.dtd">

<html xmlns="http://www.w3.org/1999/xhtml">

    <head>

        <meta name="description" content="" />
        <meta http-equiv="content-type" content="text/html; charset=utf-8" />
        <title>Home Switch Home</title>
        <link href="http://fonts.googleapis.com/css?family=Oxygen:400,700,300" rel="stylesheet" type="text/css" />
        <link href="style.css" rel="stylesheet" type="text/css" media="screen" />

    </head>

    <body>

        <div id="wrapper">

            <?php include("header.php"); ?>

            <div class="profil">

                <div id="avatar">

                    <img src="avatars/none.jpg"><br/>
                    <a href="#" class="avatarChange">Changer votre avatar ?</a>

                </div>

                <div id="infosPersoTitre">
        
                    <h1><strong>Informations personnelles</strong></h1>

                    <ul class="infosPerso">
                        
                        <li>Nom : ... </li>
                        <li>Prénom : ... </li>

                    </ul>

                </div>

            </div>

        </div>

        <?php include("footer.php"); ?>

    </body>

</html>