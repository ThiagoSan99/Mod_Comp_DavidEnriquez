<?php
$hostDB = 'db'; 
$nameDB = 'mod_comp';
$userDB = 'user';
$pwDB = 'password';

try {
    $hostPDO = "mysql:host=$hostDB;dbname=$nameDB;charset=utf8";
    $myPDO = new PDO($hostPDO, $userDB, $pwDB);
    $myPDO->setAttribute(PDO::ATTR_ERRMODE, PDO::ERRMODE_EXCEPTION);
} catch (PDOException $e) {
    die("Error de conexión: " . $e->getMessage());
}
?>