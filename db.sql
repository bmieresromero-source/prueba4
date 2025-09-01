-- DB schema for Gestión Documental COILE
CREATE DATABASE IF NOT EXISTS `coile_gestion` DEFAULT CHARACTER SET utf8mb4 COLLATE utf8mb4_unicode_ci;
USE `coile_gestion`;

-- Vendedores
CREATE TABLE IF NOT EXISTS vendedores (
  id INT AUTO_INCREMENT PRIMARY KEY,
  codigo VARCHAR(50) NOT NULL UNIQUE,
  nombre VARCHAR(255) NOT NULL
);

-- Clientes
CREATE TABLE IF NOT EXISTS clientes (
  id INT AUTO_INCREMENT PRIMARY KEY,
  codigo VARCHAR(50) NOT NULL UNIQUE,
  nombre VARCHAR(255) NOT NULL,
  cedula_ruc VARCHAR(50),
  vendedor_codigo VARCHAR(50)
);

-- Inventario y movimientos
CREATE TABLE IF NOT EXISTS inventario (
  id INT AUTO_INCREMENT PRIMARY KEY,
  producto VARCHAR(255) NOT NULL UNIQUE,
  stock_inicial INT NOT NULL DEFAULT 0,
  entradas INT NOT NULL DEFAULT 0,
  salidas INT NOT NULL DEFAULT 0,
  active TINYINT(1) NOT NULL DEFAULT 1
);

CREATE TABLE IF NOT EXISTS inventario_movimientos (
  id INT AUTO_INCREMENT PRIMARY KEY,
  inventario_id INT NOT NULL,
  tipo ENUM('entrada','salida') NOT NULL,
  cantidad INT NOT NULL,
  comentario TEXT,
  fecha DATE NOT NULL,
  FOREIGN KEY (inventario_id) REFERENCES inventario(id) ON DELETE CASCADE
);

-- Actas
CREATE TABLE IF NOT EXISTS actas (
  id INT AUTO_INCREMENT PRIMARY KEY,
  numero VARCHAR(20) NOT NULL,
  fecha DATE NOT NULL,
  cliente_codigo VARCHAR(100),
  cliente_nombre VARCHAR(255),
  cliente_cedula VARCHAR(50),
  vendedor_codigo VARCHAR(50),
  producto VARCHAR(255),
  cantidad INT NOT NULL DEFAULT 0,
  descripcion TEXT,
  recibido_por VARCHAR(255)
);

-- Índices
CREATE INDEX idx_actas_numero ON actas(numero);
CREATE INDEX idx_actas_fecha ON actas(fecha);
CREATE INDEX idx_actas_vendedor ON actas(vendedor_codigo);

-- Datos de ejemplo
INSERT INTO vendedores (codigo, nombre) VALUES ('V001','Carlos Pérez'), ('V002','María López')
ON DUPLICATE KEY UPDATE nombre=VALUES(nombre);

INSERT INTO inventario (producto, stock_inicial, entradas, salidas, active) VALUES
('Producto A', 100, 0, 0, 1),
('Producto B', 50, 0, 0, 1)
ON DUPLICATE KEY UPDATE stock_inicial=VALUES(stock_inicial);
