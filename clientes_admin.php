<?php
require_once 'config.php';
$pdo = db();
$clientes = $pdo->query('SELECT * FROM clientes ORDER BY codigo')->fetchAll();
?>
<!doctype html><html><head><meta charset="utf-8"><title>Clientes</title><link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0/dist/css/bootstrap.min.css" rel="stylesheet"></head><body class="p-3">
<div class="container">
<h3>Clientes</h3>
<a class="btn btn-secondary mb-2" href="index.php">Volver</a>
<table class="table"><thead><tr><th>Código</th><th>Nombre</th><th>Cédula</th><th>Vendedor</th><th>Acciones</th></tr></thead><tbody>
<?php foreach($clientes as $c): ?>
<tr><td><?= htmlspecialchars($c['codigo']) ?></td><td><?= htmlspecialchars($c['nombre']) ?></td><td><?= htmlspecialchars($c['cedula_ruc']) ?></td><td><?= htmlspecialchars($c['vendedor_codigo']) ?></td>
<td><a class="btn btn-sm btn-primary" href="cliente_hist.php?codigo=<?= urlencode($c['codigo']) ?>">Historial</a></td></tr>
<?php endforeach; ?>
</tbody></table>
</div></body></html>
