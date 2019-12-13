<?php
session_start();
/* Page qui permet de créer un compte pour un utilisateur en enregistrant ses informations dans la base de données et qui le redirigera vers la page de connexion */

if(isset($_POST['pseudo']) 
    AND isset($_POST['mdp']) 
    AND isset($_POST['mdpConf']))
{
    //echo 'pseudo : ' . $_POST['pseudo'] . ' | mdp : ' . $_POST['mdp'] . ' | mdpConf : ' . $_POST['mdpConf'] . ' | age : ' . $_POST['age'] . '<br />';

    $pseudo = securise_chaine($_POST['pseudo']);
    $mdp = securise_chaine($_POST['mdp']);
    $mdpConf = securise_chaine($_POST['mdpConf']);

    if(strlen($pseudo) > 1 // Pseudo doit avoir minimum 2 lettres
        AND strlen($mdp) > 1) // Mot de passe minimum 2 lettres
    {
        if(strcmp($mdp, $mdpConf) == 0) // Si les mots de passe correspondent (comparaison sensible à la casse)
        {
            /* Envoi des requêtes dans la base de données */
            try
            {
                $bdd = new PDO('mysql:host=127.0.0.1;dbname=projet_boissons;charset=utf8;', 'root', '', array(PDO::ATTR_ERRMODE => PDO::ERRMODE_EXCEPTION));

                /* On vérifie que le pseudonyme n'existe pas déjà dans la base de données */
                $rechercheUser = 'SELECT * FROM Utilisateurs WHERE pseudo = ?;';
                $rechercheUserRequete = $bdd->prepare($rechercheUser);
                $rechercheUserRequete->execute(array($pseudo));
                $user = $rechercheUserRequete->fetch(); // Qu'une seule ligne dans la requête car utilisation d'une fonction d'agrégation (COUNT) donc pas de boucle
                $rechercheUserRequete->closeCursor();

                if(empty($user)) // Si la requête est vide = si pseudo pas encore utilisé
                {
                    /* Insertion des données dans la base */
                    $insertionUser = 'INSERT INTO Utilisateurs(pseudo, mdp, sexe, prenom, nom, age, email, telephone, adresse, codePostal, ville, dateCreation) 
                    VALUES (:pseudo, :mdp, :sexe, :prenom, :nom, :age, :email, :tel, :adresse, :codePostal, :ville, NOW());';
                    $insertionUserRequete = $bdd->prepare($insertionUser);

                    /* Insertion des données utilisateurs en fonction de leur remplissement ou pas */
                    $donnees = array('pseudo' => $_POST['pseudo'], 'mdp' => $mdp);
                    remplir_tableau($donnees, 'sexe');
                    remplir_tableau($donnees, 'prenom');
                    remplir_tableau($donnees, 'nom');
                    remplir_tableau($donnees, 'age');
                    remplir_tableau($donnees, 'email');
                    remplir_tableau($donnees, 'tel');
                    remplir_tableau($donnees, 'adresse');
                    remplir_tableau($donnees, 'codePostal');
                    remplir_tableau($donnees, 'ville');

                    $insertionUserRequete->execute($donnees);
                    $insertionUserRequete->closeCursor();

                    /* Redirection vers la page de connexion avec affichage d'un message de confirmation */
                    $_SESSION['confirmation'] = true;
                    header('Location: connexion.php');
                }
                else
                {
                    /* Redirection vers la page d'inscription avec affichage d'un message d'erreur */
                    $_SESSION['erreurInscription'] = true;
                    $_SESSION['erreurInscription_type'] = 'pseudo';
                    header('Location: inscription.php');
                }
                
            }
            catch(PDOException $e)
            {
                die('Erreur : ' . $e->getMessage());
            }
        }
        else
        {
            /* Redirection vers la page d'inscription avec affichage d'un message d'erreur */
            $_SESSION['erreurInscription'] = true;
            $_SESSION['erreurInscription_type'] = 'mdp';
            header('Location: inscription.php');
        }
    }
    else
    {
        /* Redirection vers la page d'inscription avec affichage d'un message d'erreur */
        $_SESSION['erreurInscription'] = true;
        $_SESSION['erreurInscription_type'] = 'pseudo_mdp';
        header('Location: inscription.php');
    }
}
else
{
    /* Redirection vers la page d'inscription avec affichage d'un message d'erreur */
    $_SESSION['erreurInscription'] = true;
    $_SESSION['erreurInscription_type'] = 'champs_obligatoires';
    header('Location: inscription.php');
}



/**
 * Retourne la chaîne sécurisée en minuscule correspondante à celle passé en paramètre
 */
function securise_chaine($chaine)
{
    $chaineSecurisee = trim($chaine); // trim : supprime les espaces avant et après
    $chaineSecurisee = strtolower($chaineSecurisee); // strtolower : met en minuscule
    $chaineSecurisee = stripslashes($chaineSecurisee); // stripslashes : enlève les \
    $chaineSecurisee = htmlspecialchars($chaineSecurisee); // htmlspecialchars : enlève le risque d'exécution de script JavaScript ou HTML en transformant les < en &lt
    return $chaineSecurisee;
}


/**
 * Remplit le tableau associatif passé par référence en utilisant la même clé pour récupérer la valeur dans le tableau $_POST
 */
function remplir_tableau(&$tableau, $cle) // Passage du tableau par référence
{
    if(isset($_POST[$cle]) AND !empty($_POST[$cle]))
        $tableau[$cle] = securise_chaine($_POST[$cle]); // Rajoute une valeur dans le tableau avec sa clé sans effacer le tableau
    else
        $tableau[$cle] = NULL;
}

?>