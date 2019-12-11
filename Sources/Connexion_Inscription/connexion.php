<?php session_start()  ?>
<!DOCTYPE html>
<html>
<head>
	<meta charset="utf-8" />
	<link rel="Stylesheet" href="../Style/styleInscConn.css" />
	<title>WeDrink | Connexion</title>
</head>

<body>
	<!-- EN-TETE -->
	<header>
		<a href="../index.php" title="Revenir à la page principale"><img class="picBandeau" src="../Images/bandeau.png" alt="Bandeau"></a>
		<!-- TODO: Faire en sorte que l'image soit juste le logo et pas tout le bandeau et créer le bandeau en CSS plutôt -->
	</header>
	
	<!-- CONTENU -->
	<section>
		<form action="connexion_post.php" method="POST">
			<h1>Connexion</h1>
			<?php
			if(isset($_SESSION['erreurConnexion']) && isset($_SESSION['erreurConnexion_type']))
			{
				if($_SESSION['erreurConnexion'] === true)  // === est le symbole de comparaison de l'égalité comme == mais n'accepte pas le transtypage (il faut que les deux variables soient du même type)
				{
					if(strcmp($_SESSION['erreurConnexion_type'], 'pseudo_mdp') == 0) // On peut comparer les string de façon binaire avec strcmp qui retroune la différence entre les deux string
						echo '<p class="erreur">Le mot de passe et le pseudonyme ne correspondent pas.</p>';
					else if($_SESSION['erreurConnexion_type'] === 'compte') // On peut comparer les string avec == ou ===
						echo '<p class="erreur">Le pseudo ne correspond à aucun compte enregistré.</p>';
					else
						echo '<p class="erreur">Une erreur est survenue, veuillez réessayer.</p>';

					/* On réinitialise les erreurs => plus d'erreur */
					$_SESSION['erreurConnexion'] = false;
					$_SESSION['erreurConnexion_type'] = '';
				}
				else if(isset($_SESSION['confirmation']))
				{
					if($_SESSION['confirmation'] === true)
					{
						echo '<p id="confirmation">Votre compte a bien été enregistré.</p>';
						$_SESSION['confirmation'] = false;
					}
				}
			}
			?>
			<p>
				<label for="pseudo">Pseudo :</label>
				<input type="text" name="pseudo" id="pseudo" class="inputEcrivable" autofocus required />
			</p>
			<p>
				<label for="mdp">Mot de passe :</label>
				<input type="password" name="mdp" id="mdp" class="inputEcrivable" required/>
			</p>
			<p><input type="submit"  value="Se connecter" /></p>
		</form>
	</section>

	<!-- PIED DE PAGE -->
	<footer>

	</footer>
</body>
</html>
