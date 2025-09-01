<?php
require_once 'config.php';
$pdo = db();
$codigo = $_GET['codigo'] ?? '';
if(!$codigo){ header('Location: clientes_admin.php'); exit; }
$cliente = $pdo->prepare('SELECT * FROM clientes WHERE codigo=?'); $cliente->execute([$codigo]); $cliente = $cliente->fetch();
$actas = $pdo->prepare('SELECT * FROM actas WHERE cliente_codigo=? ORDER BY fecha DESC'); $actas->execute([$codigo]); $actas = $actas->fetchAll();
?>
<!doctype html><html><head><meta charset="utf-8"><title>Historial - <?= htmlspecialchars($codigo) ?></title><link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0/dist/css/bootstrap.min.css" rel="stylesheet"></head><body class="p-3">
<div class="container">
<a href="clientes_admin.php" class="btn btn-secondary mb-2">Volver</a>
<h3>Historial de <?= htmlspecialchars($cliente['nombre'] ?? $codigo) ?></h3>
<table class="table"><thead><tr><th>N°</th><th>Fecha</th><th>Producto</th><th>Cantidad</th><th>Vendedor</th></tr></thead><tbody>
<?php foreach($actas as $a): ?>
<tr><td><?= htmlspecialchars($a['numero']) ?></td><td><?= htmlspecialchars($a['fecha']) ?></td><td><?= htmlspecialchars($a['producto']) ?></td><td><?= $a['cantidad'] ?></td><td><?= htmlspecialchars($a['vendedor_codigo']) ?></td></tr>
<?php endforeach; ?>
</tbody></table>
<button class="btn btn-secondary" onclick="window.print()">Imprimir historial</button>
</div></body></html>
