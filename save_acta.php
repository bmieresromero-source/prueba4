<?php
require_once 'functions.php';
$pdo = db();
$id = $_POST['id'] ?? '';
$fecha = $_POST['fecha'] ?? date('Y-m-d');
$codigo_cliente = $_POST['codigo_cliente'] ?? '';
$nombre_cliente = $_POST['nombre_cliente'] ?? '';
$cedula_ruc = $_POST['cedula_ruc'] ?? '';
$vendedor_codigo = $_POST['vendedor_codigo'] ?? '';
$producto = $_POST['producto'] ?? '';
$cantidad = max(0,intval($_POST['cantidad'] ?? 0));
$descripcion = $_POST['descripcion'] ?? '';
$recibido_por = $_POST['recibido_por'] ?? '';

if(!$nombre_cliente || !$producto || $cantidad<=0){
    header('Location: index.php'); exit;
}

if($codigo_cliente){
    $st = $pdo->prepare('SELECT id FROM clientes WHERE codigo=?');
    $st->execute([$codigo_cliente]);
    if($st->fetch()){
        $pdo->prepare('UPDATE clientes SET nombre=?, cedula_ruc=?, vendedor_codigo=? WHERE codigo=?')->execute([$nombre_cliente,$cedula_ruc,$vendedor_codigo,$codigo_cliente]);
    } else {
        $pdo->prepare('INSERT INTO clientes (codigo,nombre,cedula_ruc,vendedor_codigo) VALUES (?,?,?,?)')->execute([$codigo_cliente,$nombre_cliente,$cedula_ruc,$vendedor_codigo]);
    }
}

if(empty($id)){
    $pdo->prepare('INSERT INTO actas (numero,fecha,cliente_codigo,cliente_nombre,cliente_cedula,vendedor_codigo,producto,cantidad,descripcion,recibido_por) VALUES (?,?,?,?,?,?,?,?,?,?)')
        ->execute(['PEND',$fecha,$codigo_cliente,$nombre_cliente,$cedula_ruc,$vendedor_codigo,$producto,$cantidad,$descripcion,$recibido_por]);
    resecuenciar_actas();
} else {
    $pdo->prepare('UPDATE actas SET fecha=?, cliente_codigo=?, cliente_nombre=?, cliente_cedula=?, vendedor_codigo=?, producto=?, cantidad=?, descripcion=?, recibido_por=? WHERE id=?')
        ->execute([$fecha,$codigo_cliente,$nombre_cliente,$cedula_ruc,$vendedor_codigo,$producto,$cantidad,$descripcion,$recibido_por,$id]);
}

recompute_inventario();
header('Location: index.php');
exit;
?>