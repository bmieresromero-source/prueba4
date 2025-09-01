# Gestión Documental - Comercializadora COILE S.A

Versión funcional en PHP + MySQL con:
- Actas (numeración ACTA-001, resecuenciación al eliminar).
- Inventario con movimientos (entradas/salidas) y control activo/inactivo.
- Clientes con historial e impresión.
- Vendedores (CRUD).
- Reporte Detalle + Resumen with filters and export to PDF (Dompdf).
- Eliminación de actas: borra, resecuencia y recalcula inventario.

## Instalación rápida

1. Copia los archivos al directorio de tu servidor (ej. `/var/www/html/coile`).
2. Edita `config.php` y ajusta credenciales MySQL.
3. Importa la base de datos:
   ```bash
   mysql -u root -p < db.sql
   ```
4. Instala dependencias (Dompdf) con Composer (recomendado):
   ```bash
   composer install
   ```
   Esto creará la carpeta `vendor/` necesaria para exportar PDFs desde el servidor.

5. Asegura permisos de lectura para `assets/` y acceso web al proyecto.
6. Abre en tu navegador: `http://tu-servidor/coile/index.php`

## Subir a GitHub

Inicializa repo y sube:

```bash
git init
git add .
git commit -m "Initial commit - Gestión Documental COILE"
git remote add origin git@github.com:TU_USUARIO/gestion-documental-coile.git
git push -u origin main
```

