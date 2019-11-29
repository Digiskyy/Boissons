<?php
/**
 * Cette page affiche les résultat de la recherche de l'utilisateur (à intégrer dans la page index.php plus tard).
 * L'utilisateur peut rechercher :
 *  - un nom de coktail => pour voir avoir sa recette, sa composition, son image s'il y en a une, (d'autres recettes avec les mêmes aliments), ...
 *  - un aliment => pour obtenir ses sous-catégories et ses super-catégories (ex: orange : sous = orange sanguine, ... et super = agrumes, ...)
 *               => pour obtenir les recettes où cet aliment est présent
 */

 if(isset($_GET['recherche'])) // Si l'utilisateur a fait une recherche
 {
     $recherche = $_GET['recherche'];
    try
    {
        /* ===== Connexion à la base de données ===== */
        $bdd = new PDO('mysql:host=localhost;dbname=projet_boissons;charset=utf8;', 'root', '', array(PDO::ATTR_ERRMODE => PDO::ERRMODE_EXCEPTION));

        /* ===== Recherche dans la table Recettes ===== */
        $rechercheRecettes = 'SELECT * FROM Recettes WHERE LOWER(titre) LIKE LOWER(?);';
        $rechercheRecettesRequete = $bdd->prepare($rechercheRecettes);
        $rechercheRecettesRequete->execute(array('%' . $recherche . '%')); // % dans une clause LIKE veut dire 0 ou 1 ou plusieurs caractères (LIKE ne prend pas de regex)

        if($donnees = $rechercheRecettesRequete->fetch()) // Si la recherche n'est pas vide
        {
            do
            {
                echo 'Résultat dans la table Recettes :<br />';
                echo 'idRecette = ' . $donnees['idRecette'] . ' | titre = ' . $donnees['titre'] . '<br />';
            } while($donnees = $rechercheRecettesRequete->fetch());
        }
        else // Pas de résultat dans la table Recettes
        {
            echo 'Pas de résultat dans la table Recettes<br />';
        }

        /* ===== Recherche dans la table Aliments ===== */
        // Faire recherche pour les sous-catégories et une autre pour les autres super-catégories

        // Si les deux recherches ne donnent aucun résultat, afficher un message sur la page
    }
    catch(PDOException $e)
    {
        die('Erreur : ' . $e->getMessage());
    }

 }
 else
 {
    // TO DO : Afficher la 1ère page normale et le resultat de la recherche s'il y en a en-dessous ou inclure ce fichier dans la partie <main> du index.html
    echo 'Pas de recherche effectuée';
 }
?>