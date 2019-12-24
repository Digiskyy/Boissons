<?php

    /* ===== Connexion à la base de données ===== */
    $bdd = new PDO('mysql:host=127.0.0.1;dbname=projet_boissons;charset=utf8;', 'root', '', array(PDO::ATTR_ERRMODE => PDO::ERRMODE_EXCEPTION));

    /* ===== Recherche dans la table Recettes ===== */
    $recetteBdd = 'SELECT * FROM Aliments ;';
    $recetteBddRequete = $bdd->prepare($recetteBdd);
    $recetteBddRequete->execute(array('nomAliment'));
    $recette = $recetteBddRequete->fetch();

?>

<!DOCTYPE html>
<html>
<head>
    <meta charset="utf-8" />
    <title>WeDrink | Recette<?php if($noError) echo ' : ' . $recette['titre']; ?></title>
</head>

<body>
    <!-- EN-TETE -->
    <header>
        <!-- TODO: Mettre logo du site, menu : mode connecté sinon connexion, inscription -->
    </header>

    <!-- CONTENU -->
    <section>

        <!-- Ingredient -->
        <article>
            <div id=ingredient>
                <h2>Ingredients</h2>
                <ul id="listeIngredients">
                    <?php
                        foreach(explode('|', $recette['nomAliment']) as $ingredient)
                        {
                            echo '<li>' . $ingredient . '</li>';
                        }
                    ?>
                </ul>
            </div>
        </article>

        <!-- Aside à voir si on met en fixe le titre, une photo en petit si y'a et la liste des ingrédients -->
    </section>

    <!-- PIED DE PAGE -->
    <footer>
        <p><a href="index.php" id="lien_page_principale">Revenir à la page principale</a></p>
    </footer>
</body>
</html>