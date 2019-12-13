<?php
session_start();

if(isset($_POST['pseudo']) AND isset($_POST['mdp']))
{
    try
    {
        $pseudoConnexion = securise_chaine($_POST['pseudo']);
        $mdpConnexion = securise_chaine($_POST['mdp']);

        /* Connexion à la base de données */
        $bdd = new PDO('mysql:host=127.0.0.1;dbname=projet_boissons;charset=utf8;', 'root', '', array(PDO::ATTR_ERRMODE => PDO::ERRMODE_EXCEPTION));

        /* Récupération du mot de passe dans la BDD correspondant au pseudo écrit par l'utilisateur */
        $requeteIdMdp = $bdd->prepare('SELECT idUtilisateur, mdp FROM Utilisateurs WHERE pseudo = ?;'); // Une seule ligne renvoyée au max. car pseudo unique dans la bdd
        $requeteIdMdp->execute(array($pseudoConnexion));
        
        /* Vérification si le pseudo et le mot de passe inscrits dans la BDD correspondent à ceux écrits par l'utilisateur */
        if($id_mdp = $requeteIdMdp->fetch()) // Si requête non vide
        {
            if(strcmp($id_mdp['mdp'], $mdpConnexion) == 0) // Si les mots de passe correspondent
            {
                $_SESSION['pseudo'] = $pseudoConnexion; // Variable pseudo qui sera vérifiée au moment d'ouvrir la page d'accueil
                $_SESSION['idUtilisateur'] = $id_mdp['idUtilisateur']; // Variable id qui sera nécessaire pour ajouter une recette préférée dans la bdd
                header('Location: ../index.php');
            }
            else
            {
                $_SESSION['erreurConnexion'] = true;
                $_SESSION['erreurConnexion_type'] = 'pseudo_mdp';
                header('Location: connexion.php');
            }
        }
        else
        {
            $_SESSION['erreurConnexion'] = true;
            $_SESSION['erreurConnexion_type'] = 'compte';
            header('Location: connexion.php');
        }

        $requeteIdMdp->closeCursor();
    }
    catch(PDOException $e)
    {
        die('Erreur : ' . $e->getMessage());
    }
}
else
{
    $_SESSION['erreurConnexion'] = true;
    $_SESSION['erreurConnexion_type'] = '';
    header('Location: connexion.php');
}

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