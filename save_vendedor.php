<?php
require_once 'config.php';
$pdo = db();
$codigo = trim($_POST['codigo'] ?? ''); $nombre = trim($_POST['nombre'] ?? '');
if($codigo && $nombre){
    $pdo->prepare('INSERT INTO vendedores (codigo,nombre) VALUES (?,?) ON DUPLICATE KEY UPDATE nombre=VALUES(nombre)')->execute([$codigo,$nombre]);
}
header('Location: vendedores_admin.php');
exit;
?>