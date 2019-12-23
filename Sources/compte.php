<?php
session_start();
$noError = true;
if(isset($_SESSION['pseudo']))
{
    try
    {
        /* ===== Connexion à la base de données ===== */
        $bdd = new PDO('mysql:host=127.0.0.1;dbname=projet_boissons;charset=utf8;', 'root', '', array(PDO::ATTR_ERRMODE => PDO::ERRMODE_EXCEPTION));

        /* ===== Requête pour les informations de l'utilisateur ===== */
        $infoUtilisateurBdd = $bdd->prepare('SELECT * FROM Utilisateurs WHERE pseudo = ?;');
        $infoUtilisateurBdd->execute(array($_SESSION['pseudo']));
        $infoUtilisateur = $infoUtilisateurBdd->fetch(); // Qu'un seul résultat car pseudo unique donc pas de boucle while

        /* ===== Requête pour les recettes préférées de l'utilisateur ===== */
        $recettesPrefBdd = $bdd->prepare('SELECT * 
                                            FROM Recettes
                                            INNER JOIN RecettesPreferees ON Recettes.idRecette = RecettesPreferees.idRecette
                                            WHERE idUtilisateur = ?;');
        $recettesPrefBdd->execute(array($infoUtilisateur['idUtilisateur']));
    }
    catch(PDOException $e)
    {
        die('Erreur : ' . $e->getMessage());
    }
}
else
{
    $noError = false;
}
?>

<!DOCTYPE html>
<html>
<head>
	<meta charset="utf-8" />
	<link rel="Stylesheet" href="Style/styleCompte.css" />
	<title>WeDrink | Mon compte</title>
</head>
<body>
    <!-- EN-TETE -->
    <header>
        <!-- TODO: Mettre logo du site, menu : mode connecté sinon connexion, inscription -->
    </header>

    <!-- CONTENU -->
    <section>
        <?php
        if($noError)
        {
            echo '<h1>Mon compte</h1>';

            echo '<article>';
            echo '<h2 id="pseudo">' . $infoUtilisateur['pseudo'] . '</h2>';
            echo '<p>Sexe : ' . $infoUtilisateur['sexe'] . '<br />';
            echo 'Prénom : ' . $infoUtilisateur['prenom'] . '<br />';
            echo 'Nom : ' . $infoUtilisateur['nom'] . '<br />';
            echo 'Age : ' . $infoUtilisateur['age'] . '<br />';
            echo 'Email : ' . $infoUtilisateur['email'] . '<br />';
            echo 'Téléphone : ' . $infoUtilisateur['telephone'] . '<br />';
            echo 'adresse : ' . $infoUtilisateur['adresse'] . '<br />';
            echo 'Code postal : ' . $infoUtilisateur['codePostal'] . '<br />';
            echo 'Ville : ' . $infoUtilisateur['ville'] . '<br />';
            echo 'Date de création du compte : ' . $infoUtilisateur['dateCreation'] . '<br />'; // TODO: Mettre la date en format français

            // TODO: Quand un utilisateur est connecté, on ajoute dans son panier toutes les recettes préférées qu'il a enregistrées

            echo '</p></article>';

            echo '<article><p>';
            echo '<h2>Mes recettes préférées</h2>';

            if($recettesPref = $recettesPrefBdd->fetch()) // Si la recherche n'est pas vide
            {
                echo '<ul id="recettesPref">';
                do
                {
                    echo '<li><a href="recette.php?idRecette=' . $recettesPref['idRecette'] . '" title="Aller à la recette">' . $recettesPref['titre'] . '</a></li>';
                } while($recettesPref = $recettesPrefBdd->fetch());
                echo '</ul>';
            }
            else // Pas de recettes préférées enregistrées pour cet utilisateur
            {
                echo 'Pas de recette préférées enregistrées.';
            }
            echo '</p></article>';
        }
        else
        {
            echo '<p id="erreur">Veuillez vous connecter pour accéder à votre compte</p>';
        }
        ?>
    </section>

    <!-- PIED DE PAGE -->
    <footer>
        <p><a href="index.php" id="lien_page_principale">Revenir à la page principale</a></p>
    </footer>
</body>