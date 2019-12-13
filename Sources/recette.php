<?php
/**
 * Cette page affichera tous les éléments constituant la recette demandée par la méthode GET de la recherche
 */

 $noError = true;
if(isset($_GET['idRecette']))
{
    try
    {
        /* ===== Connexion à la base de données ===== */
        $bdd = new PDO('mysql:host=127.0.0.1;dbname=projet_boissons;charset=utf8;', 'root', '', array(PDO::ATTR_ERRMODE => PDO::ERRMODE_EXCEPTION));

        /* ===== Recherche dans la table Recettes ===== */
        $recetteBdd = 'SELECT * FROM Recettes WHERE idRecette = ?';
        $recetteBddRequete = $bdd->prepare($recetteBdd);
        $recetteBddRequete->execute(array($_GET['idRecette']));
        $recette = $recetteBddRequete->fetch();

        if(empty($recette)) // Si pas de recettes correspondantes à idRecette trouvée
            $noError = false;
    }
    catch(PDOException $e)
    {
        die('Erreur : ' . $e->getMessage());
    }
}
else
{
    $noError = false; // TODO: Afficher page erreur
}


/**
 * Enlève tous les accents d'une chaîne et remplace tous les espaces et les tirets par des underscores
 */
Function transforme_chaine($chaine)
{
    $string = strtr($chaine, ' -', '__'); // Remplacement des '-' et ' ' par des '_'
    $string = preg_replace("#(.*)'(.*)#", "$1$2", $string); // Suppression de la 1ère apostrophe ' | preg_replace(regex, autreChaîne, chaîne) permet chercher un motif dans un chaîne et de le remplacer par une autre chaîne
    $tableAccents =     array('À','Á','Â','à','Ä','Å','à','á','â','à','ä','å','Ò','Ó','Ô','Õ','Ö','Ø','ò','ó','ô','õ','ö','ø','È','É','Ê','Ë','è','é','ê','ë','Ç','ç','Ì','Í','Î','Ï','ì','í','î','ï','Ù','Ú','Û','Ü','ù','ú','û','ü','ÿ','Ñ','ñ');
    $tableSansAccents = array('a','a','a','a','a','a','a','a','a','a','a','a','o','o','o','o','o','o','o','o','o','o','o','o','e','e','e','e','e','e','e','e','c','c','i','i','i','i','i','i','i','i','u','u','u','u','u','u','u','u','y','n','n');
    $string = str_replace($tableAccents, $tableSansAccents, $string);
    /* str_replace fait en gros la même chose que strtr, mais strtr fait une seule passe sur la chaîne et ne change que octet par octet.
    Certains accents sont codés sur plusieurs octets donc on ne peut pas utiliser strtr. */
    $string = ucfirst(strtolower($string)); // Met toute la chaîne en minuscule, puis la première lettre en majuscule

    return $string;
}

/*Function save_favoris($recette)
{

}*/

?>

<!DOCTYPE html>
<html>
<head>
    <meta charset="utf-8" />
    <title>WeDrink | Recette<?php if($noError) echo ' : ' . $recette['titre']; ?></title>
    <link rel="Stylesheet" href="Style/styleRecette.css" />
</head>

<body>
    <!-- EN-TETE -->
    <header>
        <!-- TODO: Mettre logo du site, menu : mode connecté sinon connexion, inscription -->
    </header>

    <!-- CONTENU -->
    <section>
        <h1><?php   if($noError) 
                        echo $recette['titre'];
                    else echo 'Aucune recette trouvée'; ?>    
        </h1><!--TO DO Mettre titre de la recette -->

        <?php echo '<a href="panier.php?action=ajout&amp;idRecette=' . rawurlencode($recette['idRecette']) . '&amp;titre=' . rawurlencode($recette['titre']) .'" id="ajoutPanier">Ajouter au panier</a>'; /*   onclick="window.open(this.href, \'\', \'toolbar=no, location=no, directories=no, status=yes, scrollbars=yes, resizable=yes, copyhistory=no, width=600, height=350\'); return false;" */ ?>
        <!-- TODO: Faire en sorte que quand on ajoute une recette dans le panier, on ne soit pas rediriger vers le panier mais juste que la recette soit ajoutée
        et avoir un lien pour accéder au panier en haut de la page -->
        <!-- PHOTO DU COCKTAIL (s'il y en a une) -->
        <p>
            <?php
            if($noError)
            {
                /* Transformation du titre de la recette pour qu'il corresponde au format des noms des photos de cocktails 
                ex : Black Velvet => Black_velvet, Piña Colada => Pina_colada */
                $nomPhoto = transforme_chaine($recette['titre']); // On remplace toutes les occurences de ' ' et de '-' par un underscore '_' et on enlève les accents

                /* Recherche d'un fichier correspondant au titre transformé */
                $cheminDossierPhotos = 'Photos/';
                if(is_dir($cheminDossierPhotos)) // Si le dossier existe
                {
                    if($dossierPhotos = opendir($cheminDossierPhotos))
                    {
                        while(($element = readdir($dossierPhotos)) !== false) // On parcourt les éléments du dossier
                        {
                            $infoFichier = pathinfo($element);
                            if(strcmp($infoFichier['extension'], 'jpg') == 0 AND strcmp($infoFichier['filename'], $nomPhoto) == 0 AND strcmp($element, '.') != 0 AND strcmp($element, '..') != 0) // Si le fichier correspond à nomTransformé.jpg
                            {
                                echo '<img id="photoCocktail" src="' . $cheminDossierPhotos . $infoFichier['basename'] . '" alt="Photo du cocktail" title="' . $recette['titre'] . '" />';
                            }
                        }
                        closedir($dossierPhotos);
                    }
                }
            }
            ?>
        </p>

        <!-- RECETTE -->
        <article>
            <?php if($noError) {?>
                <div id=composition>
                    <h2>Composition</h2>
                    <ul id="listeIngredients">
                        <?php
                            foreach(explode('|', $recette['composition']) as $compoIngredient)
                            {
                                echo '<li>' . $compoIngredient . '</li>';
                            }
                        ?>
                    </ul>
                </div>

                <div id="preparation">
                    <h2>Préparation</h2>
                    <p>
                        <?php
                                echo nl2br($recette['preparation']); // nl2br remplace tous les passages à la ligne par des balise <br /> en HTML
                        ?>
                    </p>
                </div>
            <?php } ?>
        </article>

        <!-- Aside à voir si on met en fixe le titre, une photo en petit si y'a et la liste des ingrédients -->
    </section>

    <!-- PIED DE PAGE -->
    <footer>
        <p><a href="index.php" id="lien_page_principale">Revenir à la page principale</a></p>
    </footer>
</body>
</html>
