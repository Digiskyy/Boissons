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

        /* Recherche de toutes les recettes contenant le mot (ou une partie du mot) entré */
        $rechercheRecettes = 'SELECT *
                            FROM Recettes
                            WHERE LOWER(titre) LIKE LOWER(?)
                            ORDER BY titre asc;';
        $rechercheRecettesRequete = $bdd->prepare($rechercheRecettes);
        $rechercheRecettesRequete->execute(array('%' . $recherche . '%')); // % dans une clause LIKE veut dire 0 ou 1 ou plusieurs caractères (LIKE ne prend pas de regex)

        echo 'Recettes correspondantes au motif entré :<br />';
        if($recetteParRecette = $rechercheRecettesRequete->fetch()) // Si la recherche n'est pas vide
        {
            do
            {
                echo 'idRecette = ' . $recetteParRecette['idRecette'] . ' | titre = ' . htmlspecialchars($recetteParRecette['titre']) . '<br />';
            } while($recetteParRecette = $rechercheRecettesRequete->fetch());
        }
        else // Pas de recettes trouvée dans la table Recettes
        {
            echo 'Pas de recettes dont le titre ressemble au mot entré<br />';
        }

        /* Recherche de toutes les recettes dont un ou plusieurs ingrédients correspondent au mot (ou une partie du mot) entré */
        $rechercheIngredients = 'SELECT *
                                FROM Recettes
                                INNER JOIN Constitution ON Recettes.idRecette = Constitution.idRecette
                                INNER JOIN Aliments ON Constitution.idAliment = Aliments.idAliment
                                WHERE LOWER(nomAliment) LIKE LOWER(?) AND LOWER(titre) NOT LIKE LOWER(?)
                                GROUP BY titre
                                ORDER BY titre ASC;'; // TO DO : Mettre LIMIT x,x pour limiter le nombre de résultat et en faire plusieurs "pages" // On met la clause NOT LIKE pour ne pas remettre les recettes de la requête précédente
        $rechercheIngredientsRequete = $bdd->prepare($rechercheIngredients);
        $rechercheIngredientsRequete->execute(array('%' . $recherche . '%', '%' . $recherche . '%'));

        echo '<br />Recettes dont les ingrédients correspondent au motif entré :<br />';
        if($recetteParIngredient = $rechercheIngredientsRequete->fetch()) // Si la recherche n'est pas vide
        {
            do
            {
                echo 'idRecette = ' . $recetteParIngredient['idRecette'] . ' | titre = ' . htmlspecialchars($recetteParIngredient['titre']) . '<br />';
            } while($recetteParIngredient = $rechercheIngredientsRequete->fetch());
        }
        else // Pas de recette trouvée dans la table Recettes
        {
            echo 'Pas de recettes dont un ingrédient ressemble au mot entré<br />';
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

<!DOCTYPE html>
<html>
<head>
    <meta charset="utf-8" />
    <title>WeDrink | Recherche</title>
    <link rel="Stylesheet" href="Style/styleRecette.css" />
</head>

<body>
    <!-- EN-TETE -->
    <header>
        <!-- TO DO : Mettre logo du site, menu : mode connecté sinon connexion, inscription -->
    </header>

    <!-- CONTENU -->
    <section>
        <!-- Recherche des recettes correspondant à la recherche -->
        <h1>Des recettes</h1>
        <article>
            
        </article>
    </section>
    <section>
        <!-- Recherche des recettes correspondant à la recherche -->
        <h1>Des aliments</h1>
        <article>
            
        </article>
    </section>
</body>
</html>