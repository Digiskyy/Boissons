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
        $infoUtilisateurBdd = $bdd->prepare('SELECT * FROM Utilisateurs WHERE pseudo = ?');
        $infoUtilisateurBdd->execute(array($_SESSION['pseudo']));
        $infoUtilisateur = $infoUtilisateurBdd->fetch(); // Qu'un seul résultat car pseudo unique donc pas de boucle while

        /* ===== Requête pour les recettes préférées de l'utilisateur ===== */
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
	<link rel="Stylesheet" href="../Style/styleCompte.css" />
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
            echo '<h2>' . $infoUtilisateur['pseudo'] . '</h2>';
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

            // TODO: Vérifier qu'il n'y a pas déjà la recette préférées dans la base de données quand un utilisateur veut en ajouter une
            // TODO: Quand une recette est ajoutée dans les recettes préférées d'un utilisateur, il ne faut plus afficher le plus à côté de la recette
            // TODO: Quand un utilisateur est connecté, on ajout dans son panier toutes les recettes préférées qu'il a enregistrées

            echo '</p></article>';

            echo '<article>';
            echo '<h2>Mes recettes préférées</h2>';
            echo '<p>';


            echo '</p></article>';
        }
        else
        {
            echo '<p id="erreur">Veuillez vous connecter pour accéder à votre compte</p>';
        }
        ?>
    </section>
</body>