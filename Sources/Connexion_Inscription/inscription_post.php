<?php
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
                $bdd = new PDO('mysql:host=localhost;dbname=id11916486_projet_boissons;charset=utf8;', 'id11916486_admin', 'admin', array(PDO::ATTR_ERRMODE => PDO::ERRMODE_EXCEPTION));

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
            }
            catch(PDOException $e)
            {
                die('Erreur : ' . $e->getMessage());
            }

            // TODO: Pas de messsage de confiramtion qui s'affiche car pas de création de variable dans SESSION, PQ ?
            /* Redirection vers la page de connexion avec affichage d'un message de confirmation */
            $_SESSION['confirmation'] = true;
            header('Location: connexion.php');
        }
        else
        {
            // TODO: Enlever les affichage de tableau de SESSION qui sont en commentaire et réglez l'affichage des messages des des erreurs d'inscription (pas de création de variable dans SESSION)
            //var_dump($_SESSION);
            //echo 'COUCOU';
            /* Redirection vers la page d'inscription avec affichage d'un message d'erreur */
            $_SESSION['erreurInscription'] = true;
            $_SESSION['erreurInscription_type'] = 'mdp';
            header('Location: inscription.php');
            //var_dump($_SESSION);
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