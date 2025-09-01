<?php
require_once 'config.php';
$pdo = db();
$id = intval($_GET['id'] ?? 0);
$prod = null;
if($id){
    $st = $pdo->prepare('SELECT * FROM inventario WHERE id=?');
    $st->execute([$id]);
    $prod = $st->fetch();
}
if(!$prod){ header('Location: inventario_admin.php'); exit; }
$movs = $pdo->prepare('SELECT * FROM inventario_movimientos WHERE inventario_id=? ORDER BY fecha DESC, id DESC');
$movs->execute([$id]);
$mrows = $movs->fetchAll();
?>
<!doctype html><html><head><meta charset="utf-8"><title>Editar Producto</title><link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0/dist/css/bootstrap.min.css" rel="stylesheet"></head><body class="p-3">
<div class="container">
  <h3>Editar Producto: <?= htmlspecialchars($prod['producto']) ?></h3>
  <a href="inventario_admin.php" class="btn btn-secondary mb-2">Volver</a>
  <form method="post" action="save_producto.php">
    <input type="hidden" name="id" value="<?= $prod['id'] ?>">
    <div class="mb-2"><label>Producto</label><input name="producto" class="form-control" value="<?= htmlspecialchars($prod['producto']) ?>"></div>
    <div class="mb-2"><label>Stock inicial</label><input type="number" name="stock_inicial" class="form-control" value="<?= $prod['stock_inicial'] ?>"></div>
    <div class="mb-2"><label>Activo</label><select name="active" class="form-select"><option value="1" <?= $prod['active']?'selected':'' ?>>Activo</option><option value="0" <?= !$prod['active']?'selected':'' ?>>Inactivo</option></select></div>
    <button class="btn btn-primary">Guardar</button>
  </form>
  <hr>
  <h5>Registrar movimiento</h5>
  <form method="post" action="save_movimiento.php">
    <input type="hidden" name="inventario_id" value="<?= $prod['id'] ?>">
    <div class="mb-2"><label>Tipo</label><select name="tipo" class="form-select"><option value="entrada">Entrada</option><option value="salida">Salida</option></select></div>
    <div class="mb-2"><label>Cantidad</label><input type="number" name="cantidad" class="form-control" value="1"></div>
    <div class="mb-2"><label>Comentario</label><input name="comentario" class="form-control"></div>
    <button class="btn btn-success">Registrar movimiento</button>
  </form>
  <hr>
  <h5>Historial de movimientos</h5>
  <table class="table"><thead><tr><th>Tipo</th><th>Cantidad</th><th>Comentario</th><th>Fecha</th></tr></thead><tbody>
    <?php foreach($mrows as $m): ?>
      <tr><td><?= $m['tipo'] ?></td><td><?= $m['cantidad'] ?></td><td><?= htmlspecialchars($m['comentario']) ?></td><td><?= $m['fecha'] ?></td></tr>
    <?php endforeach; ?>
  </tbody></table>
</div>
</body></html>
