-- Base de datos: radiomed_db
-- Plataforma Radiomed - Tamizaje preliminar por exposición a sustancias radiactivas y químicas peligrosas
-- NOTA: Los umbrales incluidos son de DEMOSTRACIÓN ACADÉMICA. Para uso real deben ser validados por especialista y normativa vigente.

CREATE DATABASE IF NOT EXISTS radiomed_db CHARACTER SET utf8mb4 COLLATE utf8mb4_unicode_ci;
USE radiomed_db;

SET FOREIGN_KEY_CHECKS = 0;
DROP TABLE IF EXISTS auditoria;
DROP TABLE IF EXISTS alertas;
DROP TABLE IF EXISTS casos;
DROP TABLE IF EXISTS personas;
DROP TABLE IF EXISTS escalas_riesgo;
DROP TABLE IF EXISTS sustancia_vias;
DROP TABLE IF EXISTS sustancia_unidades;
DROP TABLE IF EXISTS vias_exposicion;
DROP TABLE IF EXISTS unidades;
DROP TABLE IF EXISTS normativas;
DROP TABLE IF EXISTS sustancias;
DROP TABLE IF EXISTS usuarios;
DROP TABLE IF EXISTS roles;
SET FOREIGN_KEY_CHECKS = 1;

CREATE TABLE roles (
  id INT AUTO_INCREMENT PRIMARY KEY,
  nombre VARCHAR(40) NOT NULL UNIQUE
) ENGINE=InnoDB;

CREATE TABLE usuarios (
  id INT AUTO_INCREMENT PRIMARY KEY,
  nombre VARCHAR(120) NOT NULL,
  correo VARCHAR(150) NOT NULL UNIQUE,
  password_hash VARCHAR(255) NOT NULL,
  rol_id INT NOT NULL,
  estado ENUM('ACTIVO','INACTIVO') NOT NULL DEFAULT 'ACTIVO',
  created_at TIMESTAMP DEFAULT CURRENT_TIMESTAMP,
  FOREIGN KEY (rol_id) REFERENCES roles(id)
) ENGINE=InnoDB;

CREATE TABLE sustancias (
  id INT AUTO_INCREMENT PRIMARY KEY,
  nombre VARCHAR(120) NOT NULL UNIQUE,
  tipo ENUM('RADIACTIVA','QUIMICA') NOT NULL,
  descripcion TEXT NOT NULL,
  caso_mundial VARCHAR(180) DEFAULT NULL,
  antecedente_peru VARCHAR(180) DEFAULT NULL,
  estado ENUM('ACTIVO','INACTIVO') NOT NULL DEFAULT 'ACTIVO'
) ENGINE=InnoDB;

CREATE TABLE unidades (
  id INT AUTO_INCREMENT PRIMARY KEY,
  simbolo VARCHAR(20) NOT NULL UNIQUE,
  nombre VARCHAR(120) NOT NULL,
  tipo_contexto ENUM('RADIACTIVA','QUIMICA','MIXTA') NOT NULL
) ENGINE=InnoDB;

CREATE TABLE sustancia_unidades (
  sustancia_id INT NOT NULL,
  unidad_id INT NOT NULL,
  PRIMARY KEY (sustancia_id, unidad_id),
  FOREIGN KEY (sustancia_id) REFERENCES sustancias(id) ON DELETE CASCADE,
  FOREIGN KEY (unidad_id) REFERENCES unidades(id) ON DELETE CASCADE
) ENGINE=InnoDB;

CREATE TABLE vias_exposicion (
  id INT AUTO_INCREMENT PRIMARY KEY,
  nombre VARCHAR(80) NOT NULL UNIQUE
) ENGINE=InnoDB;

CREATE TABLE sustancia_vias (
  sustancia_id INT NOT NULL,
  via_id INT NOT NULL,
  PRIMARY KEY (sustancia_id, via_id),
  FOREIGN KEY (sustancia_id) REFERENCES sustancias(id) ON DELETE CASCADE,
  FOREIGN KEY (via_id) REFERENCES vias_exposicion(id) ON DELETE CASCADE
) ENGINE=InnoDB;

CREATE TABLE normativas (
  id INT AUTO_INCREMENT PRIMARY KEY,
  nombre VARCHAR(180) NOT NULL,
  pais VARCHAR(80) NOT NULL,
  institucion VARCHAR(120) NOT NULL,
  descripcion TEXT,
  estado ENUM('ACTIVO','INACTIVO') NOT NULL DEFAULT 'ACTIVO'
) ENGINE=InnoDB;

CREATE TABLE escalas_riesgo (
  id INT AUTO_INCREMENT PRIMARY KEY,
  sustancia_id INT NOT NULL,
  normativa_id INT NOT NULL,
  unidad_id INT NOT NULL,
  nivel ENUM('BAJO','MODERADO','ALTO','CRITICO') NOT NULL,
  desde DECIMAL(14,4) NOT NULL,
  hasta DECIMAL(14,4) DEFAULT NULL,
  accion TEXT NOT NULL,
  estado ENUM('ACTIVO','INACTIVO') NOT NULL DEFAULT 'ACTIVO',
  FOREIGN KEY (sustancia_id) REFERENCES sustancias(id),
  FOREIGN KEY (normativa_id) REFERENCES normativas(id),
  FOREIGN KEY (unidad_id) REFERENCES unidades(id),
  INDEX idx_escala_busqueda (sustancia_id, normativa_id, unidad_id, estado)
) ENGINE=InnoDB;

CREATE TABLE personas (
  id INT AUTO_INCREMENT PRIMARY KEY,
  codigo VARCHAR(30) NOT NULL UNIQUE,
  nombres VARCHAR(150) NOT NULL,
  edad INT NOT NULL,
  sexo ENUM('MASCULINO','FEMENINO','NO_ESPECIFICA') NOT NULL,
  ocupacion VARCHAR(120) DEFAULT NULL,
  condicion_vulnerable ENUM('NINGUNA','NIÑO','GESTANTE','ADULTO_MAYOR','TRABAJADOR_EXPUSTO','OTRA') NOT NULL DEFAULT 'NINGUNA',
  antecedentes TEXT,
  created_by INT NOT NULL,
  created_at TIMESTAMP DEFAULT CURRENT_TIMESTAMP,
  FOREIGN KEY (created_by) REFERENCES usuarios(id)
) ENGINE=InnoDB;

CREATE TABLE casos (
  id INT AUTO_INCREMENT PRIMARY KEY,
  persona_id INT NOT NULL,
  sustancia_id INT NOT NULL,
  normativa_id INT NOT NULL,
  unidad_id INT NOT NULL,
  via_id INT NOT NULL,
  tipo_evento ENUM('LABORAL','AMBIENTAL','INDUSTRIAL','MEDICO','DOMESTICO','DESCONOCIDO') NOT NULL,
  fecha_exposicion DATETIME NOT NULL,
  lugar_evento VARCHAR(180) NOT NULL,
  tiempo_exposicion_horas DECIMAL(10,2) NOT NULL,
  valor_medido DECIMAL(14,4) NOT NULL,
  tipo_muestra ENUM('SANGRE','ORINA','CABELLO','AGUA','AIRE','SUELO','MEDICION_RADIOLOGICA','OTRA') NOT NULL,
  sintomas TEXT NOT NULL,
  zona_corporal VARCHAR(120) DEFAULT NULL,
  equipo_utilizado VARCHAR(160) DEFAULT NULL,
  nivel_riesgo ENUM('BAJO','MODERADO','ALTO','CRITICO','NO_CLASIFICADO') NOT NULL DEFAULT 'NO_CLASIFICADO',
  interpretacion TEXT NOT NULL,
  estado ENUM('REGISTRADO','ALERTA_GENERADA','EN_REVISION','DERIVADO','CERRADO') NOT NULL DEFAULT 'REGISTRADO',
  created_by INT NOT NULL,
  reviewed_by INT DEFAULT NULL,
  observacion_especialista TEXT DEFAULT NULL,
  created_at TIMESTAMP DEFAULT CURRENT_TIMESTAMP,
  updated_at TIMESTAMP DEFAULT CURRENT_TIMESTAMP ON UPDATE CURRENT_TIMESTAMP,
  FOREIGN KEY (persona_id) REFERENCES personas(id),
  FOREIGN KEY (sustancia_id) REFERENCES sustancias(id),
  FOREIGN KEY (normativa_id) REFERENCES normativas(id),
  FOREIGN KEY (unidad_id) REFERENCES unidades(id),
  FOREIGN KEY (via_id) REFERENCES vias_exposicion(id),
  FOREIGN KEY (created_by) REFERENCES usuarios(id),
  FOREIGN KEY (reviewed_by) REFERENCES usuarios(id),
  INDEX idx_casos_riesgo (nivel_riesgo, estado),
  INDEX idx_casos_fecha (created_at)
) ENGINE=InnoDB;

CREATE TABLE alertas (
  id INT AUTO_INCREMENT PRIMARY KEY,
  caso_id INT NOT NULL,
  nivel ENUM('MODERADO','ALTO','CRITICO') NOT NULL,
  mensaje TEXT NOT NULL,
  estado ENUM('PENDIENTE','REVISADA','CERRADA') NOT NULL DEFAULT 'PENDIENTE',
  created_at TIMESTAMP DEFAULT CURRENT_TIMESTAMP,
  FOREIGN KEY (caso_id) REFERENCES casos(id) ON DELETE CASCADE
) ENGINE=InnoDB;

CREATE TABLE auditoria (
  id INT AUTO_INCREMENT PRIMARY KEY,
  usuario_id INT NOT NULL,
  accion VARCHAR(80) NOT NULL,
  tabla_afectada VARCHAR(80) NOT NULL,
  registro_id INT DEFAULT NULL,
  detalle TEXT,
  ip VARCHAR(60),
  created_at TIMESTAMP DEFAULT CURRENT_TIMESTAMP,
  FOREIGN KEY (usuario_id) REFERENCES usuarios(id),
  INDEX idx_auditoria_fecha (created_at)
) ENGINE=InnoDB;

INSERT INTO roles(nombre) VALUES
('ADMINISTRADOR'),('EVALUADOR'),('ESPECIALISTA'),('AUTORIDAD'),('CONSULTOR');

-- Usuario inicial:
-- correo: admin@radiomed.pe
-- clave: Admin123*
INSERT INTO usuarios(nombre, correo, password_hash, rol_id) VALUES
('Administrador Radiomed','admin@radiomed.pe','$2y$12$2sxaGmxzRLfkOARrkmVlheXC3a/EHXIfX1pwwZ..TuEOIAoAAzb42',1),
('Evaluador Demo','evaluador@radiomed.pe','$2y$12$W9lk7gJN8N4vlf5VV00AcubXvLnFB38DuSz7g7zeV4U/OnYmAKYy.',2);

INSERT INTO sustancias(nombre,tipo,descripcion,caso_mundial,antecedente_peru) VALUES
('Yodo-131','RADIACTIVA','Radionúclido asociado a exposición interna, especialmente relevante por su captación tiroidea.','Chernóbil','Uso médico regulado'),
('Cesio-137','RADIACTIVA','Radionúclido emisor gamma asociado a fuentes selladas y contaminación ambiental.','Goiânia, Brasil','Control de fuentes por autoridad competente'),
('Cobalto-60','RADIACTIVA','Fuente radiactiva usada en aplicaciones médicas e industriales bajo regulación.','Accidentes radiológicos industriales/médicos','Uso regulado en medicina e industria'),
('Estroncio-90','RADIACTIVA','Radionúclido relevante en contaminación ambiental y exposición interna.','Chernóbil/Fukushima','Riesgo potencial por fuentes reguladas'),
('Uranio','RADIACTIVA','Elemento con riesgo radiológico y químico, asociado a minería y contaminación ambiental.','Minería de uranio','Riesgo potencial minero/ambiental'),
('Mercurio','QUIMICA','Metal pesado de alta preocupación sanitaria por posibles efectos neurológicos, renales y sistémicos.','Minamata, Japón','Minería aurífera en Madre de Dios'),
('Plomo','QUIMICA','Metal pesado asociado a daño neurológico, hematológico y sistémico; especialmente sensible en niños.','Exposición industrial urbana','La Oroya'),
('Arsénico','QUIMICA','Metaloide tóxico asociado a exposición por agua contaminada, alimentos o ambiente laboral.','Bangladesh','Agua contaminada en zonas del Perú'),
('Cadmio','QUIMICA','Metal pesado asociado a daño renal, óseo y respiratorio; relevante en entornos industriales/mineros.','Itai-itai, Japón','Zonas mineras/industriales');

INSERT INTO unidades(simbolo,nombre,tipo_contexto) VALUES
('Bq','Becquerel','RADIACTIVA'),('Gy','Gray','RADIACTIVA'),('Sv','Sievert','RADIACTIVA'),('mSv','Milisievert','RADIACTIVA'),('uSv/h','Microsievert por hora','RADIACTIVA'),
('ug/dL','Microgramos por decilitro','QUIMICA'),('ug/L','Microgramos por litro','QUIMICA'),('ug/g','Microgramos por gramo','QUIMICA'),('mg/L','Miligramos por litro','QUIMICA'),('mg/kg','Miligramos por kilogramo','QUIMICA'),('mg/m3','Miligramos por metro cúbico','QUIMICA');

INSERT INTO sustancia_unidades(sustancia_id, unidad_id)
SELECT s.id, u.id FROM sustancias s JOIN unidades u
WHERE (s.tipo='RADIACTIVA' AND u.tipo_contexto='RADIACTIVA')
   OR (s.tipo='QUIMICA' AND u.tipo_contexto='QUIMICA');

INSERT INTO vias_exposicion(nombre) VALUES
('Exposición externa'),('Inhalación'),('Ingestión'),('Contacto con piel'),('Contacto ocular'),('Herida contaminada'),('Exposición ocupacional crónica'),('Desconocida');

INSERT INTO sustancia_vias(sustancia_id, via_id)
SELECT s.id, v.id FROM sustancias s JOIN vias_exposicion v
WHERE (s.tipo='RADIACTIVA' AND v.nombre IN ('Exposición externa','Inhalación','Ingestión','Herida contaminada','Desconocida'))
   OR (s.tipo='QUIMICA' AND v.nombre IN ('Inhalación','Ingestión','Contacto con piel','Contacto ocular','Exposición ocupacional crónica','Desconocida'));

INSERT INTO normativas(nombre,pais,institucion,descripcion) VALUES
('Escala demostrativa Radiomed - Radiológica','Internacional','Radiomed Demo','Escala académica para prototipo. Reemplazar por normativa validada antes de uso real.'),
('Escala demostrativa Radiomed - Química','Internacional','Radiomed Demo','Escala académica para prototipo. Reemplazar por normativa validada antes de uso real.');

-- Escalas DEMO: se crean rangos generales por tipo de unidad para demostrar el flujo. Deben validarse profesionalmente antes de uso real.
-- Radiológicas: uSv/h
INSERT INTO escalas_riesgo(sustancia_id,normativa_id,unidad_id,nivel,desde,hasta,accion)
SELECT s.id, 1, u.id, 'BAJO', 0, 0.50, 'Orientación preventiva y registro del caso.' FROM sustancias s JOIN unidades u ON u.simbolo='uSv/h' WHERE s.tipo='RADIACTIVA';
INSERT INTO escalas_riesgo(sustancia_id,normativa_id,unidad_id,nivel,desde,hasta,accion)
SELECT s.id, 1, u.id, 'MODERADO', 0.51, 2.00, 'Recomendar evaluación especializada y seguimiento.' FROM sustancias s JOIN unidades u ON u.simbolo='uSv/h' WHERE s.tipo='RADIACTIVA';
INSERT INTO escalas_riesgo(sustancia_id,normativa_id,unidad_id,nivel,desde,hasta,accion)
SELECT s.id, 1, u.id, 'ALTO', 2.01, 10.00, 'Generar alerta y derivar a especialista.' FROM sustancias s JOIN unidades u ON u.simbolo='uSv/h' WHERE s.tipo='RADIACTIVA';
INSERT INTO escalas_riesgo(sustancia_id,normativa_id,unidad_id,nivel,desde,hasta,accion)
SELECT s.id, 1, u.id, 'CRITICO', 10.01, NULL, 'Alerta crítica. Atención urgente por profesional competente.' FROM sustancias s JOIN unidades u ON u.simbolo='uSv/h' WHERE s.tipo='RADIACTIVA';

-- Químicas: ug/dL como escala demo para plomo/mercurio/arsénico/cadmio si se registra sangre
INSERT INTO escalas_riesgo(sustancia_id,normativa_id,unidad_id,nivel,desde,hasta,accion)
SELECT s.id, 2, u.id, 'BAJO', 0, 5, 'Orientación preventiva y registro del caso.' FROM sustancias s JOIN unidades u ON u.simbolo='ug/dL' WHERE s.tipo='QUIMICA';
INSERT INTO escalas_riesgo(sustancia_id,normativa_id,unidad_id,nivel,desde,hasta,accion)
SELECT s.id, 2, u.id, 'MODERADO', 5.01, 20, 'Recomendar evaluación médica/toxicológica y seguimiento.' FROM sustancias s JOIN unidades u ON u.simbolo='ug/dL' WHERE s.tipo='QUIMICA';
INSERT INTO escalas_riesgo(sustancia_id,normativa_id,unidad_id,nivel,desde,hasta,accion)
SELECT s.id, 2, u.id, 'ALTO', 20.01, 45, 'Generar alerta y derivar a especialista.' FROM sustancias s JOIN unidades u ON u.simbolo='ug/dL' WHERE s.tipo='QUIMICA';
INSERT INTO escalas_riesgo(sustancia_id,normativa_id,unidad_id,nivel,desde,hasta,accion)
SELECT s.id, 2, u.id, 'CRITICO', 45.01, NULL, 'Alerta crítica. Atención urgente por profesional competente.' FROM sustancias s JOIN unidades u ON u.simbolo='ug/dL' WHERE s.tipo='QUIMICA';
