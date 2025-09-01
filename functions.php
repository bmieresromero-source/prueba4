<?php
require_once 'config.php';

function numero_acta_for_index($i){
    return sprintf('ACTA-%03d', $i+1);
}

function resecuenciar_actas(){
    $pdo = db();
    $rows = $pdo->query("SELECT id FROM actas ORDER BY id ASC")->fetchAll();
    $pdo->beginTransaction();
    try{
        foreach($rows as $i=>$r){
            $num = numero_acta_for_index($i);
            $st = $pdo->prepare("UPDATE actas SET numero=? WHERE id=?");
            $st->execute([$num, $r['id']]);
        }
        $pdo->commit();
    } catch(Exception $e){
        $pdo->rollBack();
        throw $e;
    }
}

function recompute_inventario(){
    $pdo = db();
    $pdo->exec("UPDATE inventario SET salidas=0");
    $rows = $pdo->query("SELECT producto, SUM(cantidad) as total FROM actas GROUP BY producto")->fetchAll();
    $upd = $pdo->prepare("UPDATE inventario SET salidas=? WHERE producto=?");
    foreach($rows as $r){
        $upd->execute([(int)$r['total'], $r['producto']]);
    }
}

function vendedores_map(){
    $pdo = db();
    $map = [];
    foreach($pdo->query("SELECT codigo, nombre FROM vendedores") as $v){
        $map[$v['codigo']] = $v['nombre'];
    }
    return $map;
}
?>