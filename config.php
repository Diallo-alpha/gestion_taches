<?php   
$servername = "localhost";
$db = "gestion_taches";
$username = "root";
$password = "";

try{
    $connexion = new PDO("mysql:host=$servername;dbname=$db",$username,$password);
    $connexion->setAttribut(PDO::ATTR_ERRMODE, PDO::ERRMODE_EXCEPTION);

}catch(PDOException $e)
    {
        die("Erreur de connexion " .$e->getMessage());
    }
?>