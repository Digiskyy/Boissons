<?php
session_start();

include_once('panier_fonctions.php'); // On inclut nos fonctions pour la gestion du panier
	
$erreur = false;

$action = isset($_GET['action']) ? $_GET['action'] : null;
if($action !== null) // S'il y a une action à effectuer
{
	if(!in_array($action, array('ajout', 'suppression'))) // Si l'action est soit un ajout soit une suppression
		$erreur = true;

	$idRecette = isset($_GET['idRecette']) ? htmlspecialchars($_GET['idRecette']) : null;
	$titre = isset($_GET['titre']) ? htmlspecialchars($_GET['titre']) : null;
}

if(!$erreur)
{
	switch($action)
	{
		case 'ajout' :
			ajouter_recette($idRecette, $titre);
			break;

		case 'suppression' :
			supprimer_recette($idRecette);
			break;

		default :
			break;
	}
}

if(isset($_SESSION['idUtilisateur']))
{
	try
	{
		/* ===== Connexion à la base de données ===== */
		$bdd = new PDO('mysql:host=127.0.0.1;dbname=projet_boissons;charset=utf8;', 'root', '', array(PDO::ATTR_ERRMODE => PDO::ERRMODE_EXCEPTION));
	
		/* ===== Requête pour savoir si une recette est déjà ajoutée dans les recettes préférées d'un utilisateur connecté pour ne pas lui afficher le + à côté de la recette ===== */
		$recettesPrefBdd = $bdd->prepare('SELECT * 
											FROM Recettes
											INNER JOIN RecettesPreferees ON Recettes.idRecette = RecettesPreferees.idRecette
											WHERE RecettesPreferees.idUtilisateur = :idUtilisateur AND RecettesPreferees.idRecette = :idRecette;');
	}
	catch(PDOException $e)
	{
		die('Erreur : ' . $e->getMessage());
	}
}
?>

<!DOCTYPE html>
<html>
<head>
	<meta charset="utf-8" />
	<link rel="Stylesheet" href="Style/stylePanier.css" />
	<title>WeDrink | Panier de recettes</title>
</head>

<body>
	<!-- EN-TETE -->
	<header>
		
	</header>
	
	<!-- CONTENU -->
	<section>
		<h1>Votre panier de recettes</h1>
		<article>
			<table>
				<?php
				$pasRecettePref = true;
				if(creation_panier())
				{
					$nbRecettes = count($_SESSION['panier']['idRecette']); // Nombre de recettes dans le panier
					if($nbRecettes <= 0)
						echo 'Vous n\'avez aucune recette enregistrée.';
					else
					{
						for($i = 0; $i < $nbRecettes; $i++)
						{
							echo '<tr>';
							echo '<td><a href="recette.php?idRecette='. htmlspecialchars($_SESSION['panier']['idRecette'][$i]) . '" title="Aller sur la page de la recette">' . htmlspecialchars($_SESSION['panier']['titre'][$i]) . '</a></td>';
							echo '<td><a href="panier.php?action=suppression&idRecette=' . htmlspecialchars($_SESSION['panier']['idRecette'][$i]) . '" title="Supprimer du panier de recette" class="supprimer"> X </a></td>';

							// SI connecté ET SI recette pas dans la table des recettes préférées de l'utilisateur connecté, alors afficher un lien pour ajouter dans la bdd
							if(isset($_SESSION['pseudo']) && isset($_SESSION['idUtilisateur'])) // Si connecté
							{
								
								$recettesPrefBdd->execute(array('idUtilisateur' => $_SESSION['idUtilisateur'], 'idRecette' => $_SESSION['panier']['idRecette'][$i]));
								
								if($recettesPref = $recettesPrefBdd->fetch())
								{
									// TODO: Vérifier si la recette est déjà dans la table RecettesPreferees ou gérer le retour de recettesPref_bdd.php avec les variables de session pour savoir laquelle vient d'être ajoutée
									
								}
								else
									echo '<td><a href="recettesPref_bdd.php?action=ajout&idRecette=' . htmlspecialchars($_SESSION['panier']['idRecette'][$i]) . '&idUtilisateur=' . htmlspecialchars($_SESSION['idUtilisateur']) . '" title="Ajouter dans mes recettes préférées" class="ajouterRecettePref"> + </a></td>';
								
								$recettesPrefBdd->closeCursor();
							}
							echo '</tr>';
						}
					}
				}
				?>
			</table>
		</article>
	</section>

	<!-- PIED DE PAGE -->
	<footer>
		<p><a href="index.php" id="lien_page_principale">Revenir à la page principale</a></p>
	</footer>
</body>
</html>
