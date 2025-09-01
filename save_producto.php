<?php
require_once 'config.php';
$pdo = db();
$id = $_POST['id'] ?? '';
$producto = trim($_POST['producto'] ?? '');
$stock = intval($_POST['stock_inicial'] ?? 0);
$active = intval($_POST['active'] ?? 1);
if(!$producto){ header('Location: inventario_admin.php'); exit; }
if($id){
    $pdo->prepare('UPDATE inventario SET producto=?, stock_inicial=?, active=? WHERE id=?')->execute([$producto,$stock,$active,$id]);
} else {
    $pdo->prepare('INSERT INTO inventario (producto, stock_inicial, entradas, salidas, active) VALUES (?, ?, 0, 0, ?)')->execute([$producto,$stock,$active]);
}
header('Location: inventario_admin.php');
exit;
?>