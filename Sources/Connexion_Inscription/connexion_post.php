<?php
session_start();

if(isset($_POST['pseudo']) AND isset($_POST['mdp']))
{
    try
    {
        $pseudoConnexion = $_POST['pseudo'];
        $mdpConnexion = $_POST['mdp'];

        /* Connexion à la base de données */
        $bdd = new PDO('mysql:host=127.0.0.1;dbname=projet_boissons;charset=utf8;', 'root', '', array(PDO::ATTR_ERRMODE => PDO::ERRMODE_EXCEPTION));

        /* Récupération du mot de passe dans la BDD correspondant au pseudo écrit par l'utilisateur */
        $requeteId = $bdd->prepare('SELECT mdp FROM Utilisateurs WHERE pseudo = ?;'); // Une seule ligne renvoyée au max. car pseudo unique dans la bdd
        $requeteId->execute(array($pseudoConnexion));
        
        /* Vérification si le pseudo et le mot de passe inscrits dans la BDD correspondent à ceux écrits par l'utilisateur */
        if($mdpBDD = $requeteId->fetch()) // Si requête non vide
        {
            if(strcmp($mdpBDD['mdp'], $mdpConnexion) == 0) // Si les mots de passe correspondent
            {
                $_SESSION['pseudo'] = $pseudoConnexion; // Variable pseudo qui sera vérifiée au moment d'ouvrir la page d'accueil
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

        $requeteId->closeCursor();
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

?>