<?php
/* Page qui permet d'enregistrer les informations de l'utilisateur dans la base de données et qui redirigera vers la page de connexion */

if(isset($_POST['pseudo']) 
    AND isset($_POST['mdp']) 
    AND isset($_POST['mdpConf']))
{
    //echo 'pseudo : ' . $_POST['pseudo'] . ' | mdp : ' . $_POST['mdp'] . ' | mdpConf : ' . $_POST['mdpConf'] . ' | age : ' . $_POST['age'] . '<br />';

    $mdp = $_POST['mdp'];
    $mdpConf = $_POST['mdpConf'];
    if(strcmp($mdp, $mdpConf) == 0) // Si les mots de passe correspondent (comparaison sensible à la casse)
    {
        /* Envoi des requêtes dans la base de données */
        try
        {
            $bdd = new PDO('mysql:host=localhost;dbname=projet_boissons;charset=utf8;', 'root', '', array(PDO::ATTR_ERRMODE => PDO::ERRMODE_EXCEPTION));

            $insertionUser = 'INSERT INTO Utilisateurs(pseudo, mdp, sexe, prenom, nom, age, email, telephone, adresse, codePostal, ville, dateCreation) 
                                VALUES (:pseudo, :mdp, :sexe, :prenom, :nom, :age, :email, :tel, :adresse, :codePostal, :ville, NOW());';
            $insertionUserRequete = $bdd->prepare($insertionUser);

            /* Insertion des données utilisateurs en fonction de leur remplissement ou pas */
            $donnees = array('pseudo' => $_POST['pseudo'], 'mdp' => $mdp);
            remplirTableau($donnees, 'sexe');
            remplirTableau($donnees, 'prenom');
            remplirTableau($donnees, 'nom');
            remplirTableau($donnees, 'age');
            remplirTableau($donnees, 'email');
            remplirTableau($donnees, 'tel');
            remplirTableau($donnees, 'adresse');
            remplirTableau($donnees, 'codePostal');
            remplirTableau($donnees, 'ville');

            $insertionUserRequete->execute($donnees);
            $insertionUserRequete->closeCursor();
        }
        catch(PDOException $e)
        {
            die('Erreur : ' . $e->getMessage());
        }

        /* Redirection vers la page de connexion */
        header('Location: connexion.php'); // TO DO : Ecrire une ligne sur la page de connexion comme quoi le compte est bien enregistré
    }
    else
    {
        echo 'Les deux mots de passe ne correspondent pas.<br />'; // TO DO : Recharger la page
    }
}
else
    echo 'Une des valeurs du formulaire n\'existe pas.<br />'; // TO DO : Faire une page d'erreur


function remplirTableau(&$tableau, $cle) // Passage du tableau par référence
{
    if(isset($_POST[$cle]))
        $tableau[$cle] = $_POST[$cle]; // Rajoute une valeur dans le tableau avec sa clé sans effacer le tableau
    else
        $tableau[$cle] = NULL;
}

?>