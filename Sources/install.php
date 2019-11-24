<?php
	define('BDD_NOM', 'projet_boisson');
	define('USER', 'root');

	echo 'Bonjour';

	/* Création de la base de données */
	try
	{
		$bdd= new PDO('mysql:host=localhost;charset=utf8', USER, '', array(PDO::ATTR_ERRMODE => PDO::ERRMODE_EXCEPTION));
		$creation = 'DROP DATABASE IF EXISTS projet_boisson ;
					CREATE DATABASE IF NOT EXISTS projet_boisson ;
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
						idRecette INT(6) UNSIGNED,
						idAliment INT(6) UNSIGNED,
						CONSTRAINT FOREIGN KEY (idAliment) REFERENCES Aliment(idAliment) ON DELETE CASCADE,
						CONSTRAINT FOREIGN KEY (idRecette) REFERENCES Recette(idRecette) ON DELETE CASCADE
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

	/* Insertion des données */
	echo '<br />Insertion des données<br />';

	include('Donnees.inc.php'); // On inclut le script PHP qui contient les tableaux $Recettes et $Hiérarchie
	
	if(!empty($Recettes)) // Si tableau $Recettes vide ou nul
	{
		/* Insertion des recettes */
		foreach($Recettes as $cocktail)
		{
			try
			{
				$insertionRecettes = 'INSERT INTO recette (titre, composition, preparation) VALUES (:titre, :composition, :preparation)';
				$insertionRecettesRequete = $bdd->prepare($insertionRecettes);
				$insertionRecettesRequete->execute(array('titre' => $cocktail['titre'],
														'composition' => $cocktail['ingredients'],
														'preparation' => $cocktail['preparation']));
				$insertionRecettesRequete->closeCursor(); // On ferme la requête pour éviter des problèmes pour les prochaines requêtes
			}
			catch(PDOException $pdoErr)
			{
				die('Erreur : ' . $pdoErr->getMessage());
			}
			
		}
		echo 'Table recette remplie<br />';
	}
	else
	{
		echo print_r($Recettes[0]);
		echo '$Recettes n\'existe pas ou est vide<br />';
	}

	
?>
