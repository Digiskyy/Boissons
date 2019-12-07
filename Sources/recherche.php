<?php
/**
 * Cette page affiche les résultat de la recherche de l'utilisateur (à intégrer dans la page index.php plus tard).
 * L'utilisateur peut rechercher :
 *  - un nom de coktail => pour voir avoir sa recette, sa composition, son image s'il y en a une, (d'autres recettes avec les mêmes aliments), ...
 *  - un aliment => pour obtenir ses sous-catégories et ses super-catégories (ex: orange : sous = orange sanguine, ... et super = agrumes, ...)
 *               => pour obtenir les recettes où cet aliment est présent
 */ 

$noError = true;
if(isset($_GET['recherche'])) // Si l'utilisateur a fait une recherche
{
    $recherche = $_GET['recherche'];
    try
    {
        /* ===== Connexion à la base de données ===== */
        $bdd = new PDO('mysql:host=127.0.0.1;dbname=projet_boissons;charset=utf8;', 'root', '', array(PDO::ATTR_ERRMODE => PDO::ERRMODE_EXCEPTION));

        /* ===== Recherche dans la table Recettes ===== */

        /* Recherche de toutes les recettes contenant le mot (ou une partie du mot) entré */
        $rechercheRecettes = 'SELECT *
                            FROM Recettes
                            WHERE LOWER(titre) LIKE LOWER(?)
                            ORDER BY titre ASC;';
        $rechercheRecettesRequete = $bdd->prepare($rechercheRecettes);
        $rechercheRecettesRequete->execute(array('%' . htmlspecialchars($recherche) . '%')); // % dans une clause LIKE veut dire 0 ou 1 ou plusieurs caractères (LIKE ne prend pas de regex)

        /* Recherche de toutes les recettes dont un ou plusieurs ingrédients correspondent au mot (ou une partie du mot) entré */
        $rechercheIngredients = 'SELECT *
                                FROM Recettes
                                INNER JOIN Constitution ON Recettes.idRecette = Constitution.idRecette
                                INNER JOIN Aliments ON Constitution.idAliment = Aliments.idAliment
                                WHERE LOWER(nomAliment) LIKE LOWER(?) AND LOWER(titre) NOT LIKE LOWER(?)
                                GROUP BY titre
                                ORDER BY titre ASC;'; // TODO: Mettre LIMIT x,x pour limiter le nombre de résultat et en faire plusieurs "pages" // On met la clause NOT LIKE pour ne pas remettre les recettes de la requête précédente
        $rechercheIngredientsRequete = $bdd->prepare($rechercheIngredients);
        $rechercheIngredientsRequete->execute(array('%' . htmlspecialchars($recherche) . '%', '%' . htmlspecialchars($recherche) . '%'));

        /* ===== Recherche dans la table Aliments ===== */

        /* Recherche de tous les aliments contenant le mot ou (la partie du mot) entré */
        $rechercheAliments = 'SELECT * 
                                FROM Aliments
                                WHERE LOWER(nomAliment) LIKE LOWER(?)
                                ORDER BY nomAliment ASC;';
        $rechercheAlimentsRequete = $bdd->prepare($rechercheAliments);
        $rechercheAlimentsRequete->execute(array('%' . htmlspecialchars($recherche) . '%'));

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
    $noError = false;
    // TODO: Afficher la 1ère page normale et le résultat de la recherche s'il y en a en-dessous ou inclure ce fichier dans la partie <main> du index.html
    echo 'Pas de recherche effectuée';
}
?>

<!DOCTYPE html>
<html>
<head>
    <meta charset="utf-8" />
    <title>WeDrink | Recherche</title>
    <link rel="Stylesheet" href="Style/styleRecherche.css" />
</head>

<body>
    <!-- EN-TETE -->
    <header>
        <!-- TODO: Mettre logo du site, menu : mode connecté sinon connexion, inscription -->
    </header>

    <!-- CONTENU -->
    <main>
        <section>
            <!-- Recettes -->
            <h1>Des recettes</h1>
            <article>
                <h2>Recettes correspondantes à votre recherche</h2>

                <?php
                if($recetteParRecette = $rechercheRecettesRequete->fetch()) // Si la recherche n'est pas vide
                {
                    echo '<ul class="listeResultats">';
                    do
                    {
                        echo '<li><a href="recette.php?idRecette=' . htmlspecialchars($recetteParRecette['idRecette']) . '" title="Aller à la recette ' . htmlspecialchars($recetteParRecette['titre']) . '">' . htmlspecialchars($recetteParRecette['titre']) . '</a></li>';
                    } while($recetteParRecette = $rechercheRecettesRequete->fetch());
                    echo '</ul>';
                }
                else // Pas de recettes trouvée dans la table Recettes
                {
                    echo '<p class="erreur">Pas de recettes dont le titre ressemble au mot entré</p>';
                }
                ?>
            </article>

            <article>
                <h2>Recettes comportants des aliments correspondants à votre recherche</h2>
                <?php
                if($recetteParIngredient = $rechercheIngredientsRequete->fetch()) // Si la recherche n'est pas vide
                {
                    echo '<ul class="listeResultats">';
                    do
                    {
                        echo '<li><a href="recette.php?idRecette=' . htmlspecialchars($recetteParIngredient['idRecette']) . '" title="Aller à la recette ' . htmlspecialchars($recetteParRecette['titre']) . '">' . htmlspecialchars($recetteParIngredient['titre']) . '</a></li>';
                    } while($recetteParIngredient = $rechercheIngredientsRequete->fetch());
                    echo '</ul>';
                }
                else // Pas de recette trouvée dans la table Recettes
                {
                    echo '<p class="erreur">Pas de recettes dont un ingrédient ressemble au mot entré</p>';
                }
                ?>
            </article>
        </section>
        <section>
            <!-- Aliments -->
            <h1>Des aliments</h1>
            <article>
                <h2>Aliments correspondants à votre recherche</h2>
                <?php
                if($aliments = $rechercheAlimentsRequete->fetch())
                {
                    echo '<ul class="listeResultats">';
                    do
                    {
                        echo '<li><a href="recherche.php?recherche=' . $aliments['nomAliment'] . '" title="Rechercher ' . $aliments['nomAliment'] . '">' . $aliments['nomAliment'] . '<a/></li>';
                    } while($aliments = $rechercheAlimentsRequete->fetch());
                    echo '<ul>';
                }
                else
                {
                    echo '<p class="erreur">Aucun aliment ne correspond à votre recherche</p>';
                }
                ?>
            </article>
        </section>
    </main>
    <footer>
        <p><a href="index.php" id="lien_page_principale">Revenir à la page principale</a></p>
    </footer>
</body>
</html>