<?php require_once("config.php");

//Création des variables, ce qui va permettre de les récupèrer dans le HTML.
$messages= [];
 
$prenom = null;
$nom = null;
$email = null;
$password = null;
$check_password = null;
 
//Empty vérifie si la variable existe 	mais elle doit être vide, ! devant un booléen l'inverse.
if(!empty($_POST))
	{
  	//Isset vérifie qu'une ou plusieurs variables existe et ne sont pas null/false. 
    if(isset($_POST["prenom"], $_POST["nom"], $_POST["email"], $_POST["password"], $_POST["check_password"]))
    	{
     	//Récupération des valeurs, ça permet de mettre à jour les variables par défaut et réafficher le formulaire avec les bonnes valeurs, attention à TOUJOURS filtrer ce qui vient d'un utilisateur pour éviter des failles.
    	$prenom = $_POST['prenom'];
    	$nom = $_POST['nom'];
    	$email = $_POST['email'];
	    $password = $_POST['password'];
	    $check_password = $_POST['check_password'];
 
    	//Test des variables.
  		//Si pas alphanumérique ou si vide : erreur.
    	if(!ctype_alnum($prenom) || mb_strlen($prenom) < 1)
    	    $messages[] = "Erreur prénom (caractères alphanumériques) ou champ vide.";
              
    	if(!ctype_alnum($nom) || mb_strlen($nom) < 1)
    	    $messages[] = "Erreur nom (caractères alphanumériques) ou champ vide.";

		//Force un format email. 
    	if(!filter_var($email, FILTER_VALIDATE_EMAIL))
    	    $messages[] = "Erreur email (mauvais format).";
 		
 		//Force un minimum de 8 caractères.
    	if(mb_strlen($password) < 8)
    	    $messages[] = "Erreur mot de passe (8 caractères minimum).";

    	if(mb_strlen($check_password) < 8)
    	    $messages[] = "Erreur vérification du mot de passe (8 caractères minimum).";

    	//Vérifie que les deux mots de passe concordent.
    	if($password != $check_password)
    		$messages[] = "Les deux mots de passe sont différents.";
 
    	//Check fini, si l'array $message est vide, aucun problème, sinon j'en ai une ou plusieurs.
    	if(count($messages) === 0)
    		{
    	    $password = sha1($password);
    
			try
                {
                $register = $bdd->prepare("INSERT INTO membres (prenom, nom, email, password) VALUES (:prenom, :nom, :email, :password)");

                $register->execute([
                    ":prenom" => $prenom,
                    ":nom" => $nom,
                    ":email" => $email,
                    ":password" => $password
                    ]);
 
                $messages = 'Inscription réussie !';
                }    	

    		catch(Exception $e)
    			{
        		if($e->getCode() == 23000)
        			$messages = 'Ces identifiants existent déjà.';
        	
        		else
		        	{
        	    	$messages = 'Nous sommes incapables de procéder à votre demande. Veuillez réessayer plus tard.';
        			}
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
			
			<form method="post" class="register">

				<div class="register-top-grid">
					<h3 class="register-title">INFORMATIONS PERSONNELLES</h3>
					<div>
						<label for="prenom">Prénom*</label>
						<input type="text" id="prenom" name="prenom" value="<?= escape($prenom); ?>"> 
					</div>
					<div>
						<label for="nom">Nom*</label>
						<input type="text" id="nom" name="nom" value="<?= escape($nom); ?>"> 
					</div>
					<div>
						<label for="email">Email*</label>
						<input type="text" id="email" name="email" value="<?= escape($email); ?>"> 
					</div>
				</div>

				<div class="register-bottom-grid">
					<div>
						<label for="password">Mot de passe*</label>
						<input type="password" id="password" name="password" value="<?= escape($password); ?>">

						<label for="check_password">Confirmez votre mot de passe*</label>
						<input type="password" id="check_password" name="check_password" value="<?= escape($check_password); ?>">
					</div>
				</div>
				<input type="submit" class="submit_button" value="Envoyer">
			</form>	

			<div class="register_errors">
				<?php showErrors($messages) ?>
			</div>

		</div>
		
		<div id="footer">
		    <?php //include("footer.php"); ?>
		</div>

	</body>
</html>