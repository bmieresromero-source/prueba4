<?php
require_once 'config.php';
$pdo = db();
$vendedores = $pdo->query('SELECT * FROM vendedores ORDER BY codigo')->fetchAll();
?>
<!doctype html><html><head><meta charset="utf-8"><title>Vendedores</title><link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0/dist/css/bootstrap.min.css" rel="stylesheet"></head><body class="p-3">
<div class="container">
<h3>Vendedores</h3>
<a class="btn btn-secondary mb-2" href="index.php">Volver</a>
<table class="table"><thead><tr><th>Código</th><th>Nombre</th><th>Acciones</th></tr></thead><tbody>
<?php foreach($vendedores as $v): ?>
<tr><td><?= htmlspecialchars($v['codigo']) ?></td><td><?= htmlspecialchars($v['nombre']) ?></td><td><a class="btn btn-sm btn-warning" href="edit_vendedor.php?codigo=<?= urlencode($v['codigo']) ?>">Editar</a></td></tr>
<?php endforeach; ?>
</tbody></table>
<form method="post" action="save_vendedor.php" class="row g-2">
  <div class="col-md-3"><input name="codigo" class="form-control" placeholder="Código (V003)"></div>
  <div class="col-md-6"><input name="nombre" class="form-control" placeholder="Nombre"></div>
  <div class="col-md-3"><button class="btn btn-primary">Agregar / Actualizar</button></div>
</form>
</div></body></html>
