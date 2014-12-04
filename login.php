    <?php require_once("config.php"); 

    $messages= [];

    $email = null;
    $mdp = null;
    $bdd_email = null;
    $user = null;

    if(!empty($_POST))
        {
        if(isset($_POST["email"], $_POST["mdp"]))
            {
            $email = $_POST['email'];
            $mdp = $_POST['mdp'];

            if(!filter_var($email, FILTER_VALIDATE_EMAIL))
                $messages[] = "Erreur email (mauvais format).";

            if(mb_strlen($mdp) < 8)
                $messages[] = "Erreur mot de passe (8 caractères minimum).";

            $bdd_email = $bdd -> prepare('SELECT id, email, mdp FROM membres WHERE email=:email');
            $bdd_email -> execute([":email" => "$email"]);

            if($bdd_email -> rowCount() != 1)
                $messages[] = "Cet email n'est pas inscrit.";

            $user = $bdd_email -> fetch(); 
            $mdp = sha1($mdp);

            if($mdp != $user["mdp"])
                $messages[] = "Mot de passe incorrect.";

            if(count($messages) === 0)
                {
                try
                    {   
                    $_SESSION["userID"] = $ligne["id"];
                    header('Location:index.php');
                    }

                catch(Exception $e)
                    {
                    $messages[] = 'Nous sommes incapables de procéder à votre demande. Veuillez réessayer plus tard.';
                    }
                }
            }
        }
    ?>

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

                <form method="post" class="login">

                    <h3 class="login-title">Connexion</h3>
                    <br/>

                    <div>
                        <label for="email">Email*</label>
                        <input type="text" id="email" name="email" value="<?= escape($email); ?>"> 
                    </div>

                    <div>
                        <label for="mdp">Mot de passe*</label>
                        <input type="password" id="mdp" name="mdp" value="<?= escape($mdp); ?>">
                    </div>

                    <br/>
                    <div class="remember_me">
                        <label for="remember_me">Se souvenir de cet ordinateur ?</label>
                        <input type="checkbox" id="remember_me" name="remember_me">
                    </div>

                    <input type="submit" class="login_submit_button" value="Envoyer">

                </form> 

                <div class="login_errors">
                    <?php showErrors($messages) ?>
                </div>

                <div class="login-help">
                    <a href="#">Vous avez oublié votre mot de passe ?</a>
                </div>

            </div>

            <div id="footer">
    	          <?php include("footer.php"); ?>
            </div>

        </body>

    </html>