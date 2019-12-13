<?php
session_start();

$erreur = false;

$action = isset($_GET['action']) ? $_GET['action'] : null;
if($action !== null) // S'il y a une action à effectuer
{
    if(!in_array($action, array('ajout', 'suppression'))) // Si l'action est soit un ajout soit une suppression
        $erreur = true;
        
    $idRecette = isset($_GET['idRecette']) ? securise_chaine($_GET['idRecette']) : null;
    $idUtilisateur = isset($_GET['idUtilisateur']) ? securise_chaine($_GET['idUtilisateur']) : null;
}

if(!$erreur)
{
    try
    {
        /* ===== Connexion à la base de données ===== */
        $bdd = new PDO('mysql:host=127.0.0.1;dbname=projet_boissons;charset=utf8;', 'root', '', array(PDO::ATTR_ERRMODE => PDO::ERRMODE_EXCEPTION));

        /* ===== Insertion dans la table RecettesPreferees ===== */
        $recettePref = 'INSERT INTO RecettesPreferees (idUtilisateur, idRecette) VALUES (:idUtilisateur, :idRecette);';
        $recettePrefRequete = $bdd->prepare($recettePref);
        $recettePrefRequete->execute(array('idUtilisateur' => $idUtilisateur, 'idRecette' => $idRecette));
        $recettePrefRequete->closeCursor();
    }
    catch(PDOException $e)
    {
        die('Erreur : ' . $e->getMessage());
    }
}

header('Location : panier.php');


/**
 * Retourne la chaîne sécurisée en minuscule correspondante à celle passée en paramètre
 */
function securise_chaine($chaine)
{
    $chaineSecurisee = trim($chaine); // trim : supprime les espaces avant et après
    $chaineSecurisee = strtolower($chaineSecurisee); // strtolower : met en minuscule
    $chaineSecurisee = stripslashes($chaineSecurisee); // stripslashes : enlève les \
    $chaineSecurisee = htmlspecialchars($chaineSecurisee); // htmlspecialchars : enlève le risque d'exécution de script JavaScript ou HTML en transformant les < en &lt
    return $chaineSecurisee;
}

?>