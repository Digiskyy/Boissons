<?php

	$term = $_GET['term'];

	/* ===== Connexion à la base de données ===== */
	$bdd = new PDO('mysql:host=127.0.0.1;dbname=projet_boissons;charset=utf8;', 'root', '', array(PDO::ATTR_ERRMODE => PDO::ERRMODE_EXCEPTION));

	$rechercheRecettes = 'SELECT * 
								FROM Recettes 
								WHERE LOWER(titre) LIKE LOWER(:term) ;' ;

	$requete = $bdd->prepare($rechercheRecettes);
	$requete->execute(array('term' => '%'.$term.'%'));
	$array = array(); // on crée le tableau
	while($donnee = $requete->fetch()) // on effectue une boucle pour obtenir les données
	{
	    array_push($array, $donnee['titre']); // et on ajoute celles-ci à notre tableau
	}


	$rechercheAliments = 'SELECT * 
                                FROM Aliments
                                WHERE LOWER(nomAliment) LIKE LOWER(:term) ;' ;
    $requete = $bdd->prepare($rechercheAliments);
	$requete->execute(array('term' => '%'.$term.'%'));
	$array = array();
	while($donnee = $requete->fetch())
	{
	    array_push($array, $donnee['nomAliment']);
	}                   

	echo json_encode($array); // il n'y a plus qu'à convertir en JSON

?>