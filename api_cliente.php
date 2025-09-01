<?php
require_once 'config.php';
$pdo = db();
$codigo = $_GET['codigo'] ?? '';
if(!$codigo){ echo json_encode(['found'=>false]); exit; }
$st = $pdo->prepare('SELECT codigo,nombre,cedula_ruc,vendedor_codigo FROM clientes WHERE codigo=?');
$st->execute([$codigo]);
$r = $st->fetch();
if($r) echo json_encode(['found'=>true,'nombre'=>$r['nombre'],'cedula_ruc'=>$r['cedula_ruc'],'vendedor_codigo'=>$r['vendedor_codigo']]);
else echo json_encode(['found'=>false]);
?>