<?php
$hostDB = '127.0.0.1';
$nameDB = 'mod_comp';
$userDB = 'Sant_Admin';
$pwDB = '12345';

try {
    $hostPDO = "mysql:host=$hostDB;dbname=$nameDB;charset=utf8";
    $myPDO = new PDO($hostPDO, $userDB, $pwDB);
    $myPDO->setAttribute(PDO::ATTR_ERRMODE, PDO::ERRMODE_EXCEPTION);
} catch (PDOException $e) {
    die("Error de conexión: " . $e->getMessage());
}
?>
