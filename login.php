<?php require_once("config.php"); 

$messages= [];

$email = null;
$password = null;
$bdd_email = null;
$bdd_password = null;
$id_user = null;

if(!empty($_POST))
    {
    if(isset($_POST["email"], $_POST["password"]))
        {
        $email = $_POST['email'];
        $password = $_POST['password'];

        if(!filter_var($email, FILTER_VALIDATE_EMAIL))
            $messages[] = "Erreur email (mauvais format).";

        if(mb_strlen($password) < 8)
            $messages[] = "Erreur mot de passe (8 caractères minimum).";

        $bdd_email = $bdd->query('SELECT email FROM membres');

        if($email != $bdd_email)
            $messages[] = "Cet email n'est pas inscrit.";

        $password = sha1($password);
        $bdd_password = $bdd->query('SELECT password FROM membres');

        if($password != $bdd_password)
            $messages[] = "Mot de passe incorrect.";

        if(count($messages) === 0)
            {
            try
                {
                $id_user = $bdd->query('SELECT id FROM membres WHERE "$email" == "$bdd_email"');

                $_SESSION("logged") = $id_user;
                header(Location:"index.php");
                }

            catch
                {
                $messages = 'Nous sommes incapables de procéder à votre demande. Veuillez réessayer plus tard.';
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

            <section class="container">
                <div class="login">
                    <h1>Connexion</h1>

                    <form method="post" action="login.php">
                        <p><input type="text" name="login" value="" placeholder="Username or Email"></p>
                        <p><input type="password" name="password" value="" placeholder="Password"></p>
                        <p class="remember_me">
                            <label>
                            <input type="checkbox" name="remember_me" id="remember_me">
                                Se rappeler de cet ordinateur
                            </label>
                        </p>
                        <p class="submit"><input type="submit" name="commit" value="Login"></p>
                    </form>
                </div>

                <div class="login-help">
                    <a href="index.html">Vous avez oublié votre mot de passe ?</a>
                </div>
  	        </section>
        </div>

        <div id="footer">
	          <?php //include("footer.php"); ?>
        </div>

    </body>

</html>