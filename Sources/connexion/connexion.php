<html>
	<head>
	    <meta charset="utf-8" />
	    <link rel="Stylesheet" href="../Style/styleInscConn.css" />
	    <title>WeDrink | Connexion</title>
	</head>

	<body>
	    <!-- EN-TETE -->
	    <header>
	        <img class="picBandeau" src="../Images/bandeau.png" alt="Bandeau">
	    </header>
	    
	    <!-- CONTENU -->
	    <section>
	        <form action="connexion_post.php" method="POST">
	        	<h1>Connexion</h1>
	            <p>
	                <label for="pseudo">Pseudo :</label>
	                <input type="text" name="pseudo" id="pseudo" autofocus required />
	            </p>
	            <p>
	                <label for="mdp">Mot de passe :</label>
	                <input type="password" name="mdp" id="mdp" required/>
	            </p>
	            <p><input type="submit"  value="Se connecter" /></p>
	        </form>
	    </section>

	    <!-- PIED DE PAGE -->
	    <footer>

	    </footer>
	</body>
</html>
