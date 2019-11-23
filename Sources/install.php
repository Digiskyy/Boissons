<?php
	define('BDD_NOM', 'projet_boisson');
	define('USER', 'root');

	echo 'Bonjour';

	// Création de la base de données
	try
	{
		$bdd= new PDO('mysql:host=localhost;charset=utf8', USER, '', array(PDO::ATTR_ERRMODE => PDO::ERRMODE_EXCEPTION));
		$creation = 'CREATE DATABASE IF NOT EXISTS projet_boisson ;
					USE projet_boisson ;

					CREATE TABLE Recette (
						idRecette INT(6) UNSIGNED AUTO_INCREMENT PRIMARY KEY,
						titre VARCHAR(100) NOT NULL,
						composition TEXT NOT NULL,
						preparation TEXT NOT NULL
					) ;

					CREATE TABLE Aliment (
						idAliment INT(6) UNSIGNED AUTO_INCREMENT PRIMARY KEY,
						nom VARCHAR(30) NOT NULL,
						superCategorie TEXT NOT NULL
					) ;


					CREATE TABLE Constitution(
						idConst INT(6) UNSIGNED AUTO_INCREMENT PRIMARY KEY,
						idAliment INT(6) UNSIGNED,
						idRecette INT(6) UNSIGNED,
						FOREIGN KEY(idAliment) REFERENCES Aliment ON DELETE CASCADE,
						FOREIGN KEY(idRecette) REFERENCES Recette ON DELETE CASCADE
					) ;';

		foreach (explode(';',$creation) as $requete)
		{
			echo $requete . '<br />';
			if(!empty($requete)) // Si la requête n'est pas vide
				$bdd->prepare($requete)->execute();
			else
				echo 'Vide<br />';
    	}

		echo 'Base créée<br />';
	}
	catch(Exception $e)
	{
		die('Erreur : ' . $e->getMessage());
	}

	// Insertion les données
	echo 'Insertion des données';
?>
