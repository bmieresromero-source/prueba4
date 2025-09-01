<?php
require_once 'config.php';
require_once 'functions.php';
$pdo = db();
$productos = $pdo->query('SELECT * FROM inventario ORDER BY producto')->fetchAll();
?>
<!doctype html><html><head><meta charset="utf-8"><title>Inventario - Admin</title>
<link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0/dist/css/bootstrap.min.css" rel="stylesheet">
</head><body class="p-3">
<div class="container">
<h3>Inventario</h3>
<a class="btn btn-primary mb-2" href="index.php">Volver</a>
<table class="table table-bordered"><thead><tr><th>Producto</th><th>Stock Inicial</th><th>Entradas</th><th>Salidas</th><th>Actual</th><th>Activo</th><th>Acciones</th></tr></thead><tbody>
<?php foreach($productos as $p){ $actual = $p['stock_inicial'] + $p['entradas'] - $p['salidas']; ?>
  <tr>
    <td><?= htmlspecialchars($p['producto']) ?></td>
    <td><?= $p['stock_inicial'] ?></td>
    <td><?= $p['entradas'] ?></td>
    <td><?= $p['salidas'] ?></td>
    <td><?= $actual ?></td>
    <td><?= $p['active']? 'Sí':'No' ?></td>
    <td><a class="btn btn-sm btn-warning" href="edit_producto.php?id=<?= $p['id'] ?>">Editar</a></td>
  </tr>
<?php } ?>
</tbody></table>
</div></body></html>
