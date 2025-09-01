<?php
require_once 'config.php';
require_once 'functions.php';
$pdo = db();
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

$actas = $pdo->prepare('SELECT * FROM actas '.$sql_where.' ORDER BY fecha DESC');
$actas->execute($params);
$actas = $actas->fetchAll();

$totV = $pdo->prepare('SELECT vendedor_codigo, SUM(cantidad) total FROM actas '.$sql_where.' GROUP BY vendedor_codigo');
$totV->execute($params);
$sumVend = $totV->fetchAll();

$totP = $pdo->prepare('SELECT producto, SUM(cantidad) total FROM actas '.$sql_where.' GROUP BY producto');
$totP->execute($params);
$sumProd = $totP->fetchAll();

$vendedores = $pdo->query('SELECT codigo,nombre FROM vendedores ORDER BY codigo')->fetchAll();
$inventario = $pdo->query('SELECT producto FROM inventario ORDER BY producto')->fetchAll();
?>
<!doctype html><html><head><meta charset="utf-8"><title>Reporte</title><link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0/dist/css/bootstrap.min.css" rel="stylesheet"></head><body class="p-3">
<div class="container"><a href="index.php" class="btn btn-secondary mb-2">Volver</a>
<h3>Reporte Detalle</h3>
<form class="row g-2 mb-3">
  <div class="col-md-3"><select name="vendedor" class="form-select"><option value="">Todos</option><?php foreach($vendedores as $v) echo '<option value="'.$v['codigo'].'"'.($f_vendedor===$v['codigo']?' selected':'').'>'.htmlspecialchars($v['codigo'].' - '.$v['nombre']).'</option>'; ?></select></div>
  <div class="col-md-3"><select name="producto" class="form-select"><option value="">Todos</option><?php foreach($inventario as $p) echo '<option'.($f_producto===$p['producto']?' selected':'').'>'.htmlspecialchars($p['producto']).'</option>'; ?></select></div>
  <div class="col-md-2"><input type="date" name="desde" class="form-control" value="<?= htmlspecialchars($f_desde) ?>"></div>
  <div class="col-md-2"><input type="date" name="hasta" class="form-control" value="<?= htmlspecialchars($f_hasta) ?>"></div>
  <div class="col-md-2"><button class="btn btn-primary">Filtrar</button> <a target="_blank" class="btn btn-outline-secondary" href="export_pdf.php?scope=detalle&<?= http_build_query(['vendedor'=>$f_vendedor,'producto'=>$f_producto,'desde'=>$f_desde,'hasta'=>$f_hasta]) ?>">Exportar PDF</a></div>
</form>

<table class="table table-striped"><thead><tr><th>N°</th><th>Fecha</th><th>Cliente</th><th>Vendedor</th><th>Producto</th><th>Cantidad</th><th>Acciones</th></tr></thead><tbody>
<?php foreach($actas as $a): ?>
<tr><td><?= htmlspecialchars($a['numero']) ?></td><td><?= htmlspecialchars($a['fecha']) ?></td><td><?= htmlspecialchars($a['cliente_nombre'].' ('.$a['cliente_codigo'].')') ?></td><td><?= htmlspecialchars($a['vendedor_codigo']) ?></td><td><?= htmlspecialchars($a['producto']) ?></td><td><?= $a['cantidad'] ?></td>
<td><a class="btn btn-sm btn-primary" href="print_acta.php?id=<?= $a['id'] ?>" target="_blank">Imprimir</a>
<a class="btn btn-sm btn-warning" href="index.php?edit=<?= $a['id'] ?>">Editar</a>
<a class="btn btn-sm btn-danger" href="delete_acta.php?id=<?= $a['id'] ?>" onclick="return confirm('¿Eliminar acta? Esta acción resecuenciará numeración y recalculará inventario.')">Eliminar</a>
</td></tr>
<?php endforeach; ?>
</tbody></table>

<h4>Resumen por vendedor</h4>
<table class="table"><thead><tr><th>Vendedor</th><th>Total</th></tr></thead><tbody>
<?php foreach($sumVend as $r): ?><tr><td><?= htmlspecialchars($r['vendedor_codigo']) ?></td><td><?= $r['total'] ?></td></tr><?php endforeach; ?>
</tbody></table>

<h4>Resumen por producto</h4>
<table class="table"><thead><tr><th>Producto</th><th>Total</th></tr></thead><tbody>
<?php foreach($sumProd as $r): ?><tr><td><?= htmlspecialchars($r['producto']) ?></td><td><?= $r['total'] ?></td></tr><?php endforeach; ?>
</tbody></table>
</div></body></html>
