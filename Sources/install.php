<?php
	define('BDD_NOM', 'id11916486_projet_boissons');
	define('USER', 'id11916486_admin');

	/* =============== Création de la base de données =============== */
	echo 'Création de la base de données et de ses tables<br />';
	try
	{
		$bdd= new PDO('mysql:host=localhost;charset=utf8', USER, 'admin', array(PDO::ATTR_ERRMODE => PDO::ERRMODE_EXCEPTION));
		$creation = 'USE id11916486_projet_boissons ;
                    
                    DROP TABLE IF EXISTS RecettesPreferees ;
                    DROP TABLE IF EXISTS Utilisateurs ;
                    DROP TABLE IF EXISTS Constitution ;
                    DROP TABLE IF EXISTS Supercategories ;
                    DROP TABLE IF EXISTS Aliments ;
                    DROP TABLE IF EXISTS Recettes ;
                    
					CREATE TABLE Recettes (
						idRecette INT(6) UNSIGNED AUTO_INCREMENT PRIMARY KEY,
						titre VARCHAR(100) NOT NULL UNIQUE,
						composition TEXT NOT NULL,
						preparation TEXT NOT NULL
					) ;

					CREATE TABLE Aliments (
						idAliment INT(6) UNSIGNED AUTO_INCREMENT PRIMARY KEY,
						nomAliment VARCHAR(30) NOT NULL UNIQUE
					) ;

					CREATE TABLE Supercategories (
						idSuperCat INT(6) UNSIGNED AUTO_INCREMENT PRIMARY KEY,
						idAliment INT(6) UNSIGNED,
						idAlimentSuperCategorie INT(6) UNSIGNED,
						FOREIGN KEY (idAliment) REFERENCES Aliments(idAliment) ON UPDATE CASCADE ON DELETE CASCADE,
						FOREIGN KEY (idAlimentSuperCategorie) REFERENCES Aliments(idAliment) ON UPDATE CASCADE ON DELETE CASCADE
					) ;

					CREATE TABLE Constitution (
						idConst INT(6) UNSIGNED AUTO_INCREMENT PRIMARY KEY,
						idRecette INT(6) UNSIGNED,
						idAliment INT(6) UNSIGNED,
						FOREIGN KEY (idAliment) REFERENCES Aliments(idAliment) ON UPDATE CASCADE ON DELETE CASCADE,
						FOREIGN KEY (idRecette) REFERENCES Recettes(idRecette) ON UPDATE CASCADE ON DELETE CASCADE
					) ;
					
					CREATE TABLE Utilisateurs (
						idUtilisateur INT(6) UNSIGNED AUTO_INCREMENT PRIMARY KEY,
						pseudo VARCHAR(30) NOT NULL UNIQUE,
						mdp VARCHAR(30) NOT NULL,
						sexe VARCHAR(1),
						prenom VARCHAR(30),
						nom VARCHAR(30),
						age INT(6) UNSIGNED,
						email VARCHAR(30),
						telephone VARCHAR(30),
						adresse VARCHAR(100),
						codePostal VARCHAR(30),
						ville VARCHAR(30),
						dateCreation DATETIME NOT NULL
					) ;
					
					CREATE TABLE RecettesPreferees (
						idRP INT(6) UNSIGNED AUTO_INCREMENT PRIMARY KEY,
						idUtilisateur INT(6) UNSIGNED NOT NULL,
						idRecette INT(6) UNSIGNED NOT NULL,
						FOREIGN KEY (idUtilisateur) REFERENCES Utilisateurs(idUtilisateur) ON UPDATE CASCADE ON DELETE CASCADE,
						FOREIGN KEY (idRecette) REFERENCES Recettes(idRecette) ON UPDATE CASCADE ON DELETE CASCADE
					)';

		foreach (explode(';',$creation) as $requete)
		{
			$bdd->prepare($requete)->execute();
    	}
		echo '=> Base de données créée<br />';
	}
	catch(Exception $e)
	{
		die('Erreur : ' . $e->getMessage());
	}


	/* =============== Insertion des données =============== */
	echo '<br />Insertion des données<br />';

	include('Donnees.inc.php'); // On inclut le script PHP qui contient les tableaux $Recettes et $Hierarchie
	
	if(!empty($Recettes) && !empty($Hierarchie)) // Si tableaux $Recettes et $Hierarchie non vides et non nuls
	{
		/* ======== Insertion des recettes ======== */
		foreach($Recettes as $cocktail)
		{
			try
			{
				$insertionRecettes = 'INSERT INTO Recettes (titre, composition, preparation) VALUES (:titre, :composition, :preparation);';
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
		echo '=> Table recettes remplie<br />';

		/* ======== Insertion des aliments ======== */
		foreach($Hierarchie as $nomAliment => $aliment)
		{
			try
			{
				$insertionAliments = 'INSERT INTO Aliments (nomAliment) VALUES (:nomAliment);';
				$insertionAlimentsRequete = $bdd->prepare($insertionAliments);
				$insertionAlimentsRequete->execute(array('nomAliment' => $nomAliment));
				$insertionAlimentsRequete->closeCursor();
			}
			catch(PDOException $pdoErr)
			{
				die('Erreur : ' . $pdoErr->getMessage());
			}
		}
		echo '=> Table Aliments remplie<br />';

		/* ======== Insertion des super-catégories ======== */
		foreach($Hierarchie as $nomAliment => $aliment)
		{
			try
			{
				if(!empty($aliment['super-categorie'])) // Si l'aliment a des super-catégories (tous sauf Aliment)
				{
					/* Préparation de la requête */
					$insertionSupCat = 'INSERT INTO Supercategories (idAliment, idAlimentSuperCategorie)
										VALUES (
										(SELECT idAliment
											FROM Aliments
											WHERE nomAliment = :nomA),
										(SELECT idAliment
											FROM Aliments
											WHERE nomAliment = :nomSC));'; // Ne pas oublier de mettre un alias à la table créée par une sous-requête depuis un FROM
					$insertionSupCatRequete = $bdd->prepare($insertionSupCat);

					/* Exécution de la requête */
					foreach($aliment['super-categorie'] as $superCategorie)
					{
						//echo 'nomA : ' . $nomAliment . ' | nomSC : ' . $superCategorie . '<br />';
						$insertionSupCatRequete->execute(array('nomA' => $nomAliment, 'nomSC' => $superCategorie));
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
		echo '=> Table Supercategories remplie<br />';

		/* ======== Insertion de la table Constitution, qui fait le lien entre les recettes et leurs ingrédients ======== */
		foreach($Recettes as $cocktail) // Parcours de toutes les recettes
		{
			try
			{
				/* Préparation de la requête */
				$insertionConstitution = 'INSERT INTO Constitution (idRecette, idAliment)
											VALUES (
												(SELECT idRecette
													FROM Recettes
													WHERE titre = :nomRecette),
												(SELECT idAliment
													FROM Aliments
													WHERE nomAliment = :nomAliment));';
				$insertionConstitutionRequete = $bdd->prepare($insertionConstitution);

				/* Exécution de la requête */
				foreach($cocktail['index'] as $nomIndex) // Parcours de tous les ingrédients qui composent les recettes
				{
					$insertionConstitutionRequete->execute(array('nomRecette' => $cocktail['titre'], 'nomAliment' => $nomIndex));
					$insertionConstitutionRequete->closeCursor();
				}
			}
			catch(PDOException $pdoErr)
			{
				die('Erreur : ' . $pdoErr->getMessage());
			}
		}
	}
	else
	{
		echo '$Recettes et/ou $Hierarchie n\'existe pas ou est vide<br />';
		echo print_r($Recettes[0]);
		echo print_r($Hierarchie[0]);
	}
?>
