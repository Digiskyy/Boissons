<?php  
     session_start();
?>

<!DOCTYPE html>
<html>
<head>
    <meta charset="utf-8" />
    <title>WeDrink</title>
    <link rel="Stylesheet" href="Style/styleIndex.css" />
    <script src="https://kit.fontawesome.com/a076d05399.js"></script>
</head>

<body>
    <header>
        <div class="conteneur">
            <div class="bandeau">
                <img class="picBandeau" src="Images/bandeau.png" alt="Bandeau">
                <!-- MENU -->
                <ul class="menu">
                    <li><a href="Connexion_Inscription/deconnexion.php">Déconnexion</a></li>
                    <li><a class="favoris" href="favoris.php">Favoris</a></li>
                </ul>
            </div>
            <div class="contenu">
                <img class="picTitle" src="Images/LogoWeDrink.png" alt="Logo WeDrink">
                <p id="sous-titre">Emerveillez vos papilles et vos invités avec nos délicieuses recettes de cocktails !</p>
                <!-- CONTENU -->
                <section>
                    <form action="recherche.php" method="GET">
                        <input type="search" name="recherche" id="recherche" placeholder="Que voulez-vous boire ?" pattern="[a-zA-Zéèàùâêûîôç]*" autofocus required />
                        <button type="submit" class="searchButton"><i class="fas fa-search"></i></button>
                    </form>
                </section>
            </div>
        </div>
    </header>

    <main>
        <div class="cover">
        <h1>Recherche avancée</h1>
        <!-- Voir l'énoncé du projet, notamment le point 1 et 4 qu'on fera sur cette page
        - Faire la complétion dans le champ recherche
        - Choisir des aliments qu'on veut et d'autres qu'on veut pas -->
        </div>
        <p>
            Lorem, ipsum dolor sit amet consectetur adipisicing elit. Possimus modi ut pariatur aliquam necessitatibus mollitia delectus fugit autem saepe quo, 
            inventore molestiae vero sint hic voluptates deserunt adipisci dolorem ipsa?
        </p>
        <p>
            Lorem, ipsum dolor sit amet consectetur adipisicing elit. Possimus modi ut pariatur aliquam necessitatibus mollitia delectus fugit autem saepe quo, 
            inventore molestiae vero sint hic voluptates deserunt adipisci dolorem ipsa?
            Lorem ipsum dolor sit amet consectetur, adipisicing elit. 
            Ad alias eligendi placeat reiciendis id fugit fugiat, enim voluptates ut consequuntur necessitatibus illum at mollitia delectus debitis, sunt labore sed deserunt.
        </p>
        <p>
            Lorem, ipsum dolor sit amet consectetur adipisicing elit. Possimus modi ut pariatur aliquam necessitatibus mollitia delectus fugit autem saepe quo, 
            inventore molestiae vero sint hic voluptates deserunt adipisci dolorem ipsa?
            Lorem ipsum dolor sit amet consectetur, adipisicing elit. 
            Ad alias eligendi placeat reiciendis id fugit fugiat, enim voluptates ut consequuntur necessitatibus illum at mollitia delectus debitis, sunt labore sed deserunt.
        </p>
    </main>
</body>
</html>