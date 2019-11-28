<?php

if(isset($_POST['pseudo']) AND isset($_POST['mdp']))
{
    try
    {
        $pseudoConnexion = $_POST['pseudo'];
        $mdpConnexion = $_POST['mdp'];

        /* Connexion à la base de données */
        $bdd = new PDO('mysql:host=localhost;dbname=projet_boissons;charset=utf8;', 'root', '', array(PDO::ATTR_ERRMODE => PDO::ERRMODE_EXCEPTION));

        /* Récupération du mot de passe dans la BDD correspondant au pseudo écrit par l'utilisateur */
        $requeteId = $bdd->prepare('SELECT mdp FROM Utilisateurs WHERE pseudo = ?;'); // Une seule ligne renvoyée au max. car pseudo unique dans la bdd
        $requeteId->execute(array($pseudoConnexion));
        
        /* Vérification si le pseudo et le mot de passe inscrits dans la BDD correspondent à ceux écrits par l'utilisateur */
        if($mdpBDD = $requeteId->fetch()) // Si requête non vide
        {
            if(strcmp($mdpBDD['mdp'], $mdpConnexion) == 0) // Si les mots de passe correspondent
            {
                echo 'Vous êtes connecté.<br />';

                // TO DO : Rediriger sur la 1ère page mais en mode connecté (Compte, Recettes favorites, ... à la place de connexion Inscription)
            }
            else
            {
                echo 'Le mot de passe et le pseudo ne correspondent pas.<br />';
            }
        }
        else
        {
            echo 'Le pseudo ne correspond à aucun compte enregistré.<br />';
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
    echo 'Erreur : Pas de mot de passe ou de pseudo remplis<br />';
}

?>