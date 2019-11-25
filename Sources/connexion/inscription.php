<html>
<head>
    <meta charset="utf-8" />
    <link rel="Stylesheet" href="Style/styleInscConn.css" />
    <title>WeDrink | Inscription</title>
</head>

<body>
    <!-- EN-TETE -->
    <header>
        <img class="picBandeau" src="Images/bandeau.png" alt="Bandeau">
    </header>
    
    <!-- CONTENU -->
    <section>
        <form action="inscription_post.php" method="POST">
            <h1>Inscription</h1>
            <p>Tous les champs sont obligatoires.<br />Nous vous informons que les mineurs ne peuvent pas créer de compte.</p>
            <p>
                <label for="pseudo">Pseudo :</label>
                <input type="text" name="pseudo" id="pseudo" autofocus required />
            </p>
            <p>
                <label for="mdp">Mot de passe :</label>
                <input type="password" name="mdp" id="mdp" required/>
            </p>
            <p>
                <label for="mdpConf">Confirmation du mot de passe :</label>
                <input type="password" name="mdpConf" id="mdpConf" required/>
            </p>
            <p>
                <label for="age">Age :</label>
                <input type="number" name="age" id="age" min="0" required />
            </p>
            <p><input type="submit"  value="S'inscrire" /></p>
        </form>
    </section>

    <!-- PIED DE PAGE -->
    <footer>

    </footer>
</body>
</html>