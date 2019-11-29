<html>
<head>
    <meta charset="utf-8" />
    <link rel="Stylesheet" href="../Style/styleInscConn.css" />
    <title>WeDrink | Inscription</title>
</head>

<body>
    <!-- EN-TETE -->
    <header>
        <img id="picBandeau" src="../Images/bandeau.png" alt="Bandeau">
    </header>
    
    <!-- CONTENU -->
    <section>
        <form action="inscription_post.php" method="POST">
            <h1>Inscription</h1>
            <p id=indication>Les champs <em>Pseudo</em> et <em>Mot de passe</em> sont obligatoires.<br />Nous vous informons que les mineurs ne peuvent pas créer de compte.</p>
            <p>
                <label for="pseudo">Pseudo</label>
                <input type="text" name="pseudo" id="pseudo" class="inputEcrivable" autofocus required />
            </p>
            <p>
                <label for="mdp">Mot de passe</label>
                <input type="password" name="mdp" id="mdp" class="inputEcrivable" required/>
            </p>
            <p>
                <label for="mdpConf">Confirmation du mot de passe</label>
                <input type="password" name="mdpConf" id="mdpConf" class="inputEcrivable" required/>
            </p>
            <fieldset>
                <legend>Apprenons à se connaître</legend>
                <p>Votre sexe
                    <div class="radioBouton">
                        <label for="femme">Femme</label><input type="radio" name="sexe" value="F" id="femme" />
                        <label for="homme">Homme</label><input type="radio" name="sexe" value="H" id="homme" />
                    </div>
                </p>
                <p>
                    <label for="prenom">Prénom</label>
                    <input type="text" name="prenom" id="prenom" class="inputEcrivable" />
                </p>
                <p>
                    <label for="nom">Nom</label>
                    <input type="text" name="nom" id="nom" class="inputEcrivable" />
                </p>
                <p>
                    <label for="age">Age</label>
                    <input type="number" name="age" id="age" class="inputEcrivable" min="0" />
                </p>
                <p>
                    <label for="email">Email</label>
                    <input type="email" name="email" id="email" class="inputEcrivable" />
                </p>
                <p>
                    <label for="tel">Téléphone</label>
                    <input type="tel" name="tel" id="tel" class="inputEcrivable" />
                </p>
                <p>
                    <label for="adresse">Adresse</label>
                    <input type="text" name="adresse" id="adresse" class="inputEcrivable" />
                    <label for="codePostal">Code postal</label>
                    <input type="number" name="codePostal" id="codePostal" class="inputEcrivable" />
                    <label for="ville">Ville</label>
                    <input type="text" name="ville" id="ville" class="inputEcrivable" />
                </p>
            </fieldset>
            <p><input type="submit"  value="S'inscrire" /></p>
        </form>
    </section>

    <!-- PIED DE PAGE -->
    <footer>

    </footer>
</body>
</html>