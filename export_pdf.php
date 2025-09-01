<?php
require_once 'config.php';
$pdo = db();
$scope = $_GET['scope'] ?? 'detalle';
$f_vendedor = $_GET['vendedor'] ?? '';
$f_producto = $_GET['producto'] ?? '';
$f_desde = $_GET['desde'] ?? '';
$f_hasta = $_GET['hasta'] ?? '';

$where=[]; $params=[];
if($f_vendedor!==''){ $where[]='vendedor_codigo=?'; $params[]=$f_vendedor; }
if($f_producto!==''){ $where[]='producto=?'; $params[]=$f_producto; }
if($f_desde!==''){ $where[]='fecha>=?'; $params[]=$f_desde; }
if($f_hasta!==''){ $where[]='fecha<=?'; $params[]=$f_hasta; }
$sql_where = $where ? ('WHERE '.implode(' AND ', $where)) : '';

$actas = $pdo->prepare('SELECT * FROM actas '.$sql_where.' ORDER BY fecha DESC'); $actas->execute($params);
$sumV = $pdo->prepare('SELECT vendedor_codigo, SUM(cantidad) total FROM actas '.$sql_where.' GROUP BY vendedor_codigo'); $sumV->execute($params);
$sumV = $sumV->fetchAll();
$sumP = $pdo->prepare('SELECT producto, SUM(cantidad) total FROM actas '.$sql_where.' GROUP BY producto'); $sumP->execute($params);
$sumP = $sumP->fetchAll();

ob_start();
?>
<!doctype html><html><head><meta charset="utf-8"><title>Reporte</title><style>body{font-family:DejaVu Sans, Arial, sans-serif;}</style></head><body>
<h2>Reporte <?= htmlspecialchars($scope) ?></h2>
<table border="1" cellpadding="6" cellspacing="0"><thead><tr><th>N°</th><th>Fecha</th><th>Cliente</th><th>Vendedor</th><th>Producto</th><th>Cantidad</th></tr></thead><tbody>
<?php foreach($actas as $a): ?><tr><td><?= htmlspecialchars($a['numero']) ?></td><td><?= htmlspecialchars($a['fecha']) ?></td><td><?= htmlspecialchars($a['cliente_nombre'].' ('.$a['cliente_codigo'].')') ?></td><td><?= htmlspecialchars($a['vendedor_codigo']) ?></td><td><?= htmlspecialchars($a['producto']) ?></td><td><?= $a['cantidad'] ?></td></tr><?php endforeach; ?>
</tbody></table>
<h3>Totales por vendedor</h3>
<table border="1" cellpadding="6"><thead><tr><th>Vendedor</th><th>Total</th></tr></thead><tbody><?php foreach($sumV as $r): ?><tr><td><?= htmlspecialchars($r['vendedor_codigo']) ?></td><td><?= $r['total'] ?></td></tr><?php endforeach; ?></tbody></table>
<h3>Totales por producto</h3>
<table border="1" cellpadding="6"><thead><tr><th>Producto</th><th>Total</th></tr></thead><tbody><?php foreach($sumP as $r): ?><tr><td><?= htmlspecialchars($r['producto']) ?></td><td><?= $r['total'] ?></td></tr><?php endforeach; ?></tbody></table>
</body></html>
<?php
$html = ob_get_clean();
require_once 'config.php';
if(has_dompdf()){
    $dompdf = new \Dompdf\Dompdf();
    $dompdf->loadHtml($html);
    $dompdf->setPaper('A4','portrait');
    $dompdf->render();
    $dompdf->stream('reporte.pdf');
    exit;
} else {
    echo $html;
    echo '<script>window.print()</script>';
}
?>