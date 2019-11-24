<?php
	define('BDD_NOM', 'projet_boisson');
	define('USER', 'root');

	/* =============== Création de la base de données =============== */
	echo 'Création de la base de données et de ses tables<br />';
	try
	{
		$bdd= new PDO('mysql:host=localhost;charset=utf8', USER, '', array(PDO::ATTR_ERRMODE => PDO::ERRMODE_EXCEPTION));
		$creation = 'DROP DATABASE IF EXISTS projet_boisson ;
					CREATE DATABASE IF NOT EXISTS projet_boisson ;
					USE projet_boisson ;

					CREATE TABLE Recettes (
						idRecette INT(6) UNSIGNED AUTO_INCREMENT PRIMARY KEY,
						titre VARCHAR(100) NOT NULL,
						composition TEXT NOT NULL,
						preparation TEXT NOT NULL
					) ;

					CREATE TABLE Aliments (
						idAliment INT(6) UNSIGNED AUTO_INCREMENT PRIMARY KEY,
						nomAliment VARCHAR(30) NOT NULL
					) ;

					CREATE TABLE Supercategories (
						idSuperCat INT(6) UNSIGNED AUTO_INCREMENT PRIMARY KEY,
						superCategorie VARCHAR(30) NOT NULL
					) ;

					CREATE TABLE Constitution (
						idConst INT(6) UNSIGNED AUTO_INCREMENT PRIMARY KEY,
						idRecette INT(6) UNSIGNED,
						idAliment INT(6) UNSIGNED,
						CONSTRAINT FOREIGN KEY (idAliment) REFERENCES Aliments(idAliment) ON DELETE CASCADE,
						CONSTRAINT FOREIGN KEY (idRecette) REFERENCES Recettes(idRecette) ON DELETE CASCADE
					) ;
					
					CREATE TABLE Organisation (
						idOrga INT(6) UNSIGNED AUTO_INCREMENT PRIMARY KEY,
						idAliment INT(6) UNSIGNED,
						idSuperCat INT(6) UNSIGNED,
						CONSTRAINT FOREIGN KEY (idAliment) REFERENCES Aliments(idAliment) ON DELETE CASCADE,
						CONSTRAINT FOREIGN KEY (idSuperCat) REFERENCES Supercategories(idSuperCat) ON DELETE CASCADE
					) ;';

		foreach (explode(';',$creation) as $requete)
		{
			if(!empty($requete)) // Si la requête n'est pas vide
				$bdd->prepare($requete)->execute();
			else
				echo 'Vide<br />';
    	}
		echo 'Base de données créée<br />';
	}
	catch(Exception $e)
	{
		die('Erreur : ' . $e->getMessage());
	}

	/* =============== Insertion des données =============== */
	echo '<br />Insertion des données<br />';

	include('Donnees.inc.php'); // On inclut le script PHP qui contient les tableaux $Recettes et $Hierarchie
	
	if(!empty($Recettes)) // Si tableau $Recettes non vide et non nul
	{
		/* Insertion des recettes */
		foreach($Recettes as $cocktail)
		{
			try
			{
				$insertionRecettes = 'INSERT INTO recettes (titre, composition, preparation) VALUES (:titre, :composition, :preparation)';
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
		echo 'Table recettes remplie<br />';

		/* Insertion des aliments et de leurs super-catégories */
		foreach($Hierarchie as $nomAliment => $aliment)
		{
			try
			{
				/* Insertion des aliments */
				$insertionAliments = 'INSERT INTO aliments (nomAliment) VALUES (:nomAliment)';
				$insertionAlimentsRequete = $bdd->prepare($insertionAliments);
				$insertionAlimentsRequete->execute(array('nomAliment' => $nomAliment));
				$insertionAlimentsRequete->closeCursor();

				/* Insertion des super-catégories */
				$insertionSupCat = 'INSERT INTO Supercategories (superCategorie) VALUES (:superCategorie)';
				$insertionSupCatRequete = $bdd->prepare($insertionSupCat);
				if(!empty($aliment['super-categorie'])) // Si l'aliment a des super-catégories (tous sauf Aliment)
				{
					foreach($aliment['super-categorie'] as $superCategorie)
					{
						$insertionSupCatRequete->execute(array('superCategorie'=> $superCategorie));
						$insertionSupCatRequete->closeCursor();
					}
				}
				else
				{
					echo 'Pas de super-catégorie pour ' . $nomAliment . '<br />';
				}
				
			}
			catch(PDOException $pdoErr)
			{
				die('Erreur : ' . $pdoErr->getMessage());
			}
		}
		echo 'Table aliments et Supercategories remplie<br />';
		
	}
	else
	{
		echo '$Recettes n\'existe pas ou est vide<br />';
	}
?>
