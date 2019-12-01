<?php

session_start();

//verification si session ouverte sinon ouverture de la page de connexion
if (!isset($_SESSION["newsession"]))
	header('Location: Connexion_Inscription/connexion.php');

exit();


?>

<html>
	<head>
	    <meta charset="utf-8" />
	    <link rel="Stylesheet" href="Style/styleFavoris.css" />
	    <title>WeDrink | Favoris</title>
	</head>

	<body>
	    <!-- EN-TETE -->
	    <header>
	        
	    </header>
	    
	    <!-- CONTENU -->
	    <section>
        	<h1>Vos Favoris</h1>
            
	    </section>

	    <!-- PIED DE PAGE -->
	    <footer>

	    </footer>
	</body>
</html>
