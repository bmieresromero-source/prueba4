<?php
require_once 'config.php';
$pdo = db();
$inventario_id = intval($_POST['inventario_id'] ?? 0);
$tipo = $_POST['tipo'] ?? 'entrada';
$cantidad = max(0,intval($_POST['cantidad'] ?? 0));
$comentario = $_POST['comentario'] ?? '';
$fecha = date('Y-m-d');
if($inventario_id && $cantidad>0){
    $pdo->prepare('INSERT INTO inventario_movimientos (inventario_id, tipo, cantidad, comentario, fecha) VALUES (?, ?, ?, ?, ?)')
        ->execute([$inventario_id, $tipo, $cantidad, $comentario, $fecha]);
    if($tipo==='entrada'){
        $pdo->prepare('UPDATE inventario SET entradas = entradas + ? WHERE id=?')->execute([$cantidad,$inventario_id]);
    } else {
        $pdo->prepare('UPDATE inventario SET salidas = salidas + ? WHERE id=?')->execute([$cantidad,$inventario_id]);
    }
}
header('Location: edit_producto.php?id='.$inventario_id);
exit;
?>