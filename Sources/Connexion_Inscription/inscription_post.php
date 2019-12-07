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

    // TODO: Quand il y a des erreurs lors de la validation du formulaire, recharger la page et afficher les messages correspondants

    if(strlen($pseudo) > 1 // Pseudo doit avoir minimum 2 lettres
        AND strlen($mdp) > 1) // Mot de passe minimum 2 lettres
    {
        if(strcmp($mdp, $mdpConf) == 0) // Si les mots de passe correspondent (comparaison sensible à la casse)
        {
            /* Envoi des requêtes dans la base de données */
            try
            {
                $bdd = new PDO('mysql:host=127.0.0.1;dbname=projet_boissons;charset=utf8;', 'root', '', array(PDO::ATTR_ERRMODE => PDO::ERRMODE_EXCEPTION));

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

            /* Redirection vers la page de connexion */
            header('Location: connexion.php');
            
            // TODO: Afficher un message sur la page de connexion informant que le compte est bien enregistré
        }
        else
        {
            // echo 'Les deux mots de passe ne correspondent pas.<br />';

            /* Redirection vers la page d'inscription */
            header('Location: inscription.php');
            
            // TODO: Afficher un message expliquant l'erreur
        }
    }
    else
    {
        // echo 'Le pseudo et le mot de passe doivent contenir au minimum 2 lettres.<br />';

        /* Redirection vers la page d'inscription */
        header('Location: inscription.php');
        
        // TODO: Afficher un message expliquant l'erreur
    }
}
else
{
    //echo 'Le pseudo, le mot de passe et sa confirmation sont à remplir obligatoirement.<br />';

    /* Redirection vers la page d'inscription */
    header('Location: inscription.php');
    
    // TODO: Afficher un message expliquant l'erreur
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