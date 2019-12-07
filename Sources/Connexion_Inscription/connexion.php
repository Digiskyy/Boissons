<html>
	<head>
	    <meta charset="utf-8" />
	    <link rel="Stylesheet" href="../Style/styleInscConn.css" />
	    <title>WeDrink | Connexion</title>
	</head>

	<body>
	    <!-- EN-TETE -->
	    <header>
		<a href="../index.php" title="Revenir à la page principale"><img class="picBandeau" src="../Images/bandeau.png" alt="Bandeau"></a>
			<!-- TODO: Faire en sorte que l'image soit juste le log et pas tout le bandeau et créer le bandeau en CSS plutôt -->
	    </header>
	    
	    <!-- CONTENU -->
	    <section>
	        <form action="connexion_post.php" method="POST">
	        	<h1>Connexion</h1>
	            <p>
	                <label for="pseudo">Pseudo :</label>
	                <input type="text" name="pseudo" id="pseudo" class="inputEcrivable" autofocus required />
	            </p>
	            <p>
	                <label for="mdp">Mot de passe :</label>
	                <input type="password" name="mdp" id="mdp" class="inputEcrivable" required/>
	            </p>
	            <p><input type="submit"  value="Se connecter" /></p>
	        </form>
	    </section>

	    <!-- PIED DE PAGE -->
	    <footer>

	    </footer>
	</body>
</html>
