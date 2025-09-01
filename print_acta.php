<?php
require_once 'config.php';
$pdo = db();
$id = intval($_GET['id'] ?? 0);
$st = $pdo->prepare('SELECT * FROM actas WHERE id=?'); $st->execute([$id]); $a = $st->fetch();
if(!$a){ header('Location: index.php'); exit; }
ob_start();
?>
<!doctype html><html><head><meta charset="utf-8"><title><?= htmlspecialchars($a['numero']) ?></title><style>body{font-family:DejaVu Sans, Arial, sans-serif}</style></head><body>
<img src="assets/coile.webp" style="height:60px"><h2>ACTA DE ENTREGA</h2>
<div><strong><?= htmlspecialchars($a['numero']) ?></strong> - <?= htmlspecialchars($a['fecha']) ?></div>
<table cellpadding="6"><tr><td><strong>Cliente</strong></td><td><?= htmlspecialchars($a['cliente_nombre'].' ('.$a['cliente_codigo'].')') ?></td></tr>
<tr><td><strong>Cédula/RUC</strong></td><td><?= htmlspecialchars($a['cliente_cedula']) ?></td></tr>
<tr><td><strong>Vendedor</strong></td><td><?= htmlspecialchars($a['vendedor_codigo']) ?></td></tr></table>
<div><strong>Producto:</strong> <?= htmlspecialchars($a['producto']) ?> - <strong>Cantidad:</strong> <?= $a['cantidad'] ?></div>
<div style="margin-top:20px"><strong>Entregado por:</strong> Comercializadora COILE S.A</div>
<div><strong>Recibido por:</strong> <?= htmlspecialchars($a['recibido_por']) ?></div>
</body></html>
<?php
$html = ob_get_clean();
if(has_dompdf()){
    $dompdf = new \Dompdf\Dompdf();
    $dompdf->loadHtml($html); $dompdf->setPaper('A4','portrait'); $dompdf->render(); $dompdf->stream($a['numero'].'.pdf'); exit;
} else {
    echo $html; echo '<script>window.print()</script>';
}
?>