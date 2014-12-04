<?php require_once("config.php");

//Création des variables, ce qui va permettre de les récupèrer dans le HTML.
$messages= [];
 
$prenom = null;
$nom = null;
$email = null;
$mdp = null;
$check_mdp = null;
 
//Empty vérifie si la variable existe 	mais elle doit être vide, ! devant un booléen l'inverse.
if(!empty($_POST))
	{
  	//Isset vérifie qu'une ou plusieurs variables existe et ne sont pas null/false. 
    if(isset($_POST["prenom"], $_POST["nom"], $_POST["email"], $_POST["mdp"], $_POST["check_mdp"]))
    	{
     	//Récupération des valeurs, ça permet de mettre à jour les variables par défaut et réafficher le formulaire avec les bonnes valeurs, attention à TOUJOURS filtrer ce qui vient d'un utilisateur pour éviter des failles.
    	$prenom = $_POST['prenom'];
    	$nom = $_POST['nom'];
    	$email = $_POST['email'];
	    $password = $_POST['mdp'];
	    $check_password = $_POST['check_mdp'];
 
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
    	if(mb_strlen($mdp) < 8)
    	    $messages[] = "Erreur mot de passe (8 caractères minimum).";

    	if(mb_strlen($check_mdp) < 8)
    	    $messages[] = "Erreur vérification du mot de passe (8 caractères minimum).";

    	//Vérifie que les deux mots de passe concordent.
    	if($mdp != $check_mdp)
    		$messages[] = "Les deux mots de passe sont différents.";
 
    	//Check fini, si l'array $message est vide, aucun problème, sinon j'en ai une ou plusieurs.
    	if(count($messages) === 0)
    		{
    	    $mdp = sha1($mdp);
    
			try
                {
                $register = $bdd->prepare("INSERT INTO membres (prenom, nom, email, mdp) VALUES (:prenom, :nom, :email, :mdp)");

                $register->execute([
                    ":prenom" => $prenom,
                    ":nom" => $nom,
                    ":email" => $email,
                    ":mdp" => $mdp
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

			<h3 class="register-title">INFORMATIONS PERSONNELLES</h3>
			
			<form method="post" class="register">

				<div class="register-left-grid">
					<div>
						<label for="prenom">Prénom *</label>
						<input type="text" id="prenom" name="prenom" value="<?= escape($prenom); ?>"> 
					</div>

					<div>
						<label for="nom">Nom *</label>
						<input type="text" id="nom" name="nom" value="<?= escape($nom); ?>"> 
					</div>

					<div>
						<label for="email">Email *</label>
						<input type="text" id="email" name="email" value="<?= escape($email); ?>"> 
					</div>
				</div>

				<div class="register-right-grid">
					<div>
						<label for="mdp">Mot de passe *</label>
						<input type="password" id="mdp" name="mdp" value="<?= escape($mdp); ?>">
					</div>	

					<div>
						<label for="check_mdp">Confirmez votre mot de passe *</label>
						<input type="password" id="check_mdp" name="check_mdp" value="<?= escape($check_mdp); ?>">
					</div>
				</div>

				<input type="submit" class="submit_button" value="Envoyer">
			</form>	

			<div class="register_errors">
				<?php showErrors($messages) ?>
			</div>

		</div>
		
		<div id="footer">
		    <?php include("footer.php"); ?>
		</div>

	</body>
</html>