<?php session_start()  ?>
<!DOCTYPE html>
<html>
<head>
    <meta charset="utf-8" />
    <link rel="Stylesheet" href="../Style/styleInscConn.css" />
    <title>WeDrink | Inscription</title>
</head>

<body>
    <!-- EN-TETE -->
    <header>
        <a href="../index.php" title="Revenir à la page principale"><img id="picBandeau" src="../Images/bandeau.png" alt="Bandeau"></a>
        <!-- TODO: Faire en sorte que l'image soit juste le logo et pas tout le bandeau et créer le bandeau en CSS plutôt -->
    </header>
    
    <!-- CONTENU -->
    <section>
        <form action="inscription_post.php" method="POST">
            <h1>Inscription</h1>
            <p id=indication>Les champs <em>Pseudo</em> et <em>Mot de passe</em> sont obligatoires.<br />Nous vous informons que les mineurs ne peuvent pas créer de compte.</p>
            <?php
            if(isset($_SESSION['erreurInscription']) && isset($_SESSION['erreurInscription_type']))
            {
                if($_SESSION['erreurInscription'] === true)  // === est le symbole de comparaison de l'égalité comme == mais n'accepte pas le transtypage (il faut que les deux variables soient du même type)
                {
                    if($_SESSION['erreurInscription_type'] === 'mdp')
                        echo '<p class="erreur">Les deux mots de passe ne correspondent pas.</p>';
                    else if($_SESSION['erreurInscription_type'] === 'pseudo')
                        echo '<p class="erreur">Ce pseudo est déjà utilisé.</p>';
                    else if($_SESSION['erreurInscription_type'] === 'pseudo_mdp')
                        echo '<p class="erreur">Le pseudo et le mot de passe doivent contenir au minimum 2 lettres.</p>';  // Normalement jamais écrit car les attributs en HTML l'en empêche
                    else if($_SESSION['erreurInscription_type'] === 'champs_obligatoires')
                        echo '<p class="erreur">Le pseudo, le mot de passe et sa confirmation sont à remplir obligatoirement.</p>';  // Normalement jamais écrit car les attributs en HTML l'en empêche
                    else
                        echo '<p class="erreur">Une erreur est survenue, veuillez réessayer.</p>';

                    /* On réinitialise les erreurs => plus d'erreur */
                    $_SESSION['erreurInscription'] = false;
                    $_SESSION['erreurInscription_type'] = '';
                }
            }
            ?>
            <p>
                <label for="pseudo">Pseudo</label>
                <input type="text" name="pseudo" id="pseudo" class="inputEcrivable" pattern="^[A-Za-z0-9_éèàùâêôîâ']{2,}$" title="Pseudo : 2 lettres ou chiffres minimum, _ autorisé" maxlength="20" autofocus required /> <!-- pattern permet de mettre une regex que doit respecter l'utilisateur pour que le champ soit valide (ici, pseudo de 2 lettres ou chiffres minimum, _ autorisé) -->
            </p>
            <p>
                <label for="mdp">Mot de passe</label>
                <input type="password" name="mdp" id="mdp" class="inputEcrivable" pattern="^.{2,}$" required/>
            </p>
            <p>
                <label for="mdpConf">Confirmation du mot de passe</label>
                <input type="password" name="mdpConf" id="mdpConf" class="inputEcrivable" pattern="^.{2,}$" required/>
            </p>
            <fieldset>
                <legend>Apprenons à se connaître</legend>
                <p>Votre sexe
                    <div class="radioBouton">
                        <span class="label_radioBouton"><label for="femme">Femme</label><input type="radio" name="sexe" value="F" id="femme" /></span>
                        <span class="label_radioBouton"><label for="homme">Homme</label><input type="radio" name="sexe" value="H" id="homme" /></span>
                    </div>
                </p>
                <p>
                    <label for="prenom">Prénom</label>
                    <input type="text" name="prenom" id="prenom" class="inputEcrivable" pattern="^[A-Za-z-]{2,}$" />
                </p>
                <p>
                    <label for="nom">Nom</label>
                    <input type="text" name="nom" id="nom" class="inputEcrivable" pattern="[A-Za-z-]{2,}" />
                </p>
                <p>
                    <label for="age">Age</label>
                    <input type="number" name="age" id="age" class="inputEcrivable" min="0" pattern="^[1-9]{2}$" />
                </p>
                <p>
                    <label for="email">Email</label>
                    <input type="email" name="email" id="email" class="inputEcrivable" pattern="^[a-z0-9éèàùêîôâ_.-]+@[a-z0-9_.-]{2,}\.[a-z]{2,}$" />
                </p>
                <p>
                    <label for="tel">Téléphone</label>
                    <input type="tel" name="tel" id="tel" class="inputEcrivable" pattern="^0[1-8]([ .-]?[0-9]{2}){4}$" />
                </p>
                <p>
                    <label for="adresse">Adresse</label>
                    <input type="text" name="adresse" id="adresse" class="inputEcrivable" />
                    <label for="codePostal">Code postal</label>
                    <input type="number" name="codePostal" id="codePostal" class="inputEcrivable" pattern="^[1-9]{2}[ ]?[1-9]{4}$" />
                    <label for="ville">Ville</label>
                    <input type="text" name="ville" id="ville" class="inputEcrivable" pattern="^[a-zA-Z]+$" />
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