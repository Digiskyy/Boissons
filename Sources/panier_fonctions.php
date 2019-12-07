<?php

/**
 * Crée le panier de recette dans une variable de session s'il n'existe déjà pas
 */
function creation_panier()
{
    if(!isset($_SESSION['panier']))
    {
        $_SESSION['panier'] = array();
        $_SESSION['panier']['idRecette'] = array();
        $_SESSION['panier']['titre'] = array();
    }
    return true; // Utilisée dans les tests if pour savoir si le panier est créé
}

/**
 * Ajoute dans le panier la recette s'il n'y est pas déjà
 */
function ajouter_recette($idRecette, $titre)
{
    if(creation_panier())
    {
        $existe = array_search($idRecette, $_SESSION['panier']['idRecette']);
        if($existe === false) // Si la recette n'y est pas
        {
            array_push($_SESSION['panier']['idRecette'], $idRecette);
            array_push($_SESSION['panier']['titre'], $titre);
        }
    }
    else
        echo 'Un problème est survenu';
}

/**
 * Enlève / supprime une recette du panier si elle y est.
 * On va créer un panier temporaire sur lequel on va ajouter toutes les recettes du panier sauf celle à enlever.
 * Puis, on réaffecte notre panier de session via les valeurs du panier tampon que l'on supprime par la suite.
 */
function supprimer_recette($idRecette)
{
    if(creation_panier()) // Si le panier existe
    {
        $tmp = array();
        $tmp['idRecette'] = array();
        $tmp['titre'] = array();

        for($i = 0; $i < count($_SESSION['panier']['idRecette']); $i++)
        {
            if($_SESSION['panier']['idRecette'][$i] !== $idRecette)
            {
                array_push($tmp['idRecette'], $_SESSION['panier']['idRecette'][$i]);
                array_push($tmp['titre'], $_SESSION['panier']['titre'][$i]);
            }
        }
        $_SESSION['panier'] = $tmp; //On remplace le panier en session par notre panier temporaire à jour
        unset($tmp);
    }
}

/**
 * Supprime le panier complet
 */
function supprimer_panier()
{
    unset($_SESSION['panier']);
}

?>