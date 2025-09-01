<?php
require_once 'config.php';
require_once 'functions.php';
$pdo = db();

$vendedores = $pdo->query("SELECT codigo, nombre FROM vendedores ORDER BY codigo")->fetchAll();
$inventario = $pdo->query("SELECT * FROM inventario ORDER BY producto")->fetchAll();
$clientes = $pdo->query("SELECT codigo,nombre,cedula_ruc,vendedor_codigo FROM clientes ORDER BY codigo")->fetchAll();

$cnt = $pdo->query("SELECT COUNT(*) c FROM actas")->fetch()['c'];
$numero_sugerido = numero_acta_for_index($cnt);
?>
<!doctype html><html lang="es"><head><meta charset="utf-8"><meta name="viewport" content="width=device-width,initial-scale=1">
<title>COILE · Gestión Documental</title>
<link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0/dist/css/bootstrap.min.css" rel="stylesheet">
<style>body{font-family:Arial, sans-serif;background:#f6f7f9}header{background:#fff;padding:12px;border-bottom:1px solid #eee;display:flex;align-items:center;gap:12px}header img{height:56px}.container{max-width:1100px;margin:20px auto}.card{border-radius:10px;padding:12px;background:#fff;border:1px solid #e6e6e6}.small{color:#64748b}</style>
</head><body>
<header><img src="assets/coile.webp" alt="logo"><div><h3 style="margin:0">ACTA DE ENTREGA</h3><div class="small">COMERCIALIZADORA COILE S.A · AGENCIA SANTA ROSA</div></div></header>
<div class="container">
  <div class="card mb-3">
    <h5>Nueva Acta</h5>
    <form method="post" action="save_acta.php">
      <input type="hidden" name="id" value="">
      <div class="row g-2">
        <div class="col-md-3"><label>N° Acta</label><input class="form-control" value="<?= htmlspecialchars($numero_sugerido) ?>" readonly></div>
        <div class="col-md-3"><label>Fecha</label><input type="date" name="fecha" class="form-control" value="<?= date('Y-m-d') ?>"></div>
        <div class="col-md-3"><label>Código Cliente</label><input name="codigo_cliente" class="form-control" id="codigo_cliente"></div>
        <div class="col-md-3"><label>Vendedor</label>
          <select name="vendedor_codigo" id="vendedor_codigo" class="form-select">
            <option value="">Seleccione</option>
            <?php foreach($vendedores as $v): ?>
              <option value="<?= htmlspecialchars($v['codigo']) ?>"><?= htmlspecialchars($v['codigo'].' - '.$v['nombre']) ?></option>
            <?php endforeach; ?>
          </select>
        </div>
      </div>
      <div class="row g-2 mt-2">
        <div class="col-md-6"><label>Nombre Cliente</label><input name="nombre_cliente" id="nombre_cliente" class="form-control"></div>
        <div class="col-md-3"><label>Cédula / RUC</label><input name="cedula_ruc" id="cedula_ruc" class="form-control"></div>
        <div class="col-md-3"><label>Recibido por</label><input name="recibido_por" id="recibido_por" class="form-control"></div>
      </div>
      <div class="row g-2 mt-2">
        <div class="col-md-6"><label>Producto</label>
          <select name="producto" class="form-select" id="producto_select">
            <option value="">Seleccione</option>
            <?php foreach($inventario as $p): if($p['active']) echo '<option value="'.htmlspecialchars($p['producto']).'">'.htmlspecialchars($p['producto']).'</option>'; endforeach; ?>
          </select>
        </div>
        <div class="col-md-2"><label>Cantidad</label><input type="number" name="cantidad" class="form-control" value="1" min="1"></div>
        <div class="col-md-4"><label>Descripción</label><input name="descripcion" class="form-control"></div>
      </div>
      <div class="mt-3 d-flex justify-content-between align-items-center">
        <div class="small">Entregado por: Comercializadora COILE S.A</div>
        <div><button class="btn btn-success">Guardar Acta</button> <a class="btn btn-secondary" href="index.php">Limpiar</a></div>
      </div>
    </form>
  </div>

  <div class="card mb-3">
    <h5>Accesos rápidos</h5>
    <div class="d-flex gap-2">
      <a class="btn btn-outline-primary" href="reporte.php">Reporte General</a>
      <a class="btn btn-outline-primary" href="inventario_admin.php">Inventario</a>
      <a class="btn btn-outline-primary" href="clientes_admin.php">Clientes</a>
      <a class="btn btn-outline-primary" href="vendedores_admin.php">Vendedores</a>
    </div>
  </div>
</div>

<script>
document.getElementById('codigo_cliente').addEventListener('change', function(){
  var code = this.value;
  if(!code) return;
  fetch('api_cliente.php?codigo='+encodeURIComponent(code)).then(r=>r.json()).then(data=>{
    if(data.found){
      document.getElementById('nombre_cliente').value = data.nombre;
      document.getElementById('cedula_ruc').value = data.cedula_ruc;
      if(data.vendedor_codigo) document.getElementById('vendedor_codigo').value = data.vendedor_codigo;
      document.getElementById('recibido_por').value = data.nombre;
    }
  });
});
</script>
</body></html>
