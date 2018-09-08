<?php


/* veillez bien à vous connecter à votre base de données */

$bdd = new PDO('mysql:host=localhost;dbname=stockmanager', 'root', '');

$term = $_GET['term'];

$requete = $bdd->prepare('SELECT * FROM equipment WHERE serial LIKE :term'); // j'effectue ma requête SQL grâce au mot-clé LIKE
$requete->execute(array('term' => '%'.$term.'%'));

$array = array(); // on créé le tableau

while($donnee = $requete->fetch()) // on effectue une boucle pour obtenir les données
{
    array_push($array, $donnee['pseudo']); // et on ajoute celles-ci à notre tableau
}

echo json_encode($array); // il n'y a plus qu'à convertir en JSON

