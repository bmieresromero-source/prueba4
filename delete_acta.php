<?php
require_once 'functions.php';
$pdo = db();
$id = intval($_GET['id'] ?? 0);
if($id>0){
    // delete the acta
    $pdo->prepare('DELETE FROM actas WHERE id=?')->execute([$id]);
    // resequence and recompute inventory
    resecuenciar_actas();
    recompute_inventario();
}
header('Location: reporte.php');
exit;
?>