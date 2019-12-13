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
							// SI connecté ET SI recette pas dans la table des recettes préférées et lié à cet utilisateur, alors afficher un lien pour ajouter dans la bdd
							if(isset($_SESSION['pseudo']) && isset($_SESSION['idUtilisateur'])) // Si connecté
							{
								// TODO: Vérifier si la recette est déjà dans la table RecettesPreferees ou gérer le retour de recettesPref_bdd.php avec les variables de session pour savoir laquelle vient d'être ajoutée
								echo '<td><a href="recettesPref_bdd.php?action=ajout&idRecette=' . htmlspecialchars($_SESSION['panier']['idRecette'][$i]) . '&idUtilisateur=' . htmlspecialchars($_SESSION['idUtilisateur']) . '" title="Ajouter dans mes recettes préférées" class="ajouterRecettePref"> + </a></td>';
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
