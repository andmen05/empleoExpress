-- Crear la base de datos (si no está creada)
CREATE DATABASE plataforma_empleo;
USE plataforma_empleo;

-- Crear la tabla de usuarios
CREATE TABLE usuarios (
    id INT AUTO_INCREMENT PRIMARY KEY,
    nombre VARCHAR(100) NOT NULL,
    email VARCHAR(100) NOT NULL UNIQUE,
    password VARCHAR(255) NOT NULL,
    tipo ENUM('empresa', 'postulante') NOT NULL
);

-- Crear la tabla de información adicional de los postulantes
CREATE TABLE postulantes_info (
    id INT AUTO_INCREMENT PRIMARY KEY,
    usuario_id INT NOT NULL,
    habilidades TEXT,
    experiencia TEXT,
    profesion VARCHAR(100),
    FOREIGN KEY (usuario_id) REFERENCES usuarios(id) ON DELETE CASCADE
);

-- Crear la tabla de empresas (información adicional)
CREATE TABLE empresas_info (
    id INT AUTO_INCREMENT PRIMARY KEY,
    usuario_id INT NOT NULL,
    direccion VARCHAR(255),
    correo_contacto VARCHAR(100),
    FOREIGN KEY (usuario_id) REFERENCES usuarios(id) ON DELETE CASCADE
);

-- Crear la tabla de ofertas laborales
CREATE TABLE ofertas_laborales (
    id INT AUTO_INCREMENT PRIMARY KEY,
    empresa_id INT NOT NULL,
    titulo VARCHAR(255) NOT NULL,
    descripcion TEXT NOT NULL,
    fecha_publicacion TIMESTAMP DEFAULT CURRENT_TIMESTAMP,
    FOREIGN KEY (empresa_id) REFERENCES usuarios(id) ON DELETE CASCADE
);

-- Crear la tabla de postulaciones
CREATE TABLE postulaciones (
    id INT AUTO_INCREMENT PRIMARY KEY,
    oferta_id INT NOT NULL,
    postulante_id INT NOT NULL,
    fecha_postulacion TIMESTAMP DEFAULT CURRENT_TIMESTAMP,
    ALTER TABLE postulaciones MODIFY estado ENUM('pendiente', 'aceptado', 'rechazado') DEFAULT 'pendiente';
    FOREIGN KEY (oferta_id) REFERENCES ofertas_laborales(id) ON DELETE CASCADE,
    FOREIGN KEY (postulante_id) REFERENCES usuarios(id) ON DELETE CASCADE
);
