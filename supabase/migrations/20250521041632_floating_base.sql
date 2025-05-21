-- Crear la base de datos si no existe
CREATE DATABASE IF NOT EXISTS plataforma_empleo;
USE plataforma_empleo;

-- Tabla de usuarios (entidad principal)
CREATE TABLE usuarios (
    id INT AUTO_INCREMENT,
    nombre VARCHAR(100) NOT NULL,
    email VARCHAR(100) NOT NULL,
    password VARCHAR(255) NOT NULL,
    tipo ENUM('empresa', 'postulante') NOT NULL,
    fecha_registro TIMESTAMP DEFAULT CURRENT_TIMESTAMP,
    ultimo_acceso TIMESTAMP NULL,
    activo BOOLEAN DEFAULT TRUE,
    PRIMARY KEY (id),
    UNIQUE INDEX idx_email (email)
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;

-- Tabla de postulantes (información específica de postulantes)
CREATE TABLE postulantes_info (
    id INT AUTO_INCREMENT,
    usuario_id INT NOT NULL,
    documento_identidad VARCHAR(20) UNIQUE,
    fecha_nacimiento DATE,
    telefono VARCHAR(15),
    direccion VARCHAR(255),
    ciudad VARCHAR(100),
    pais VARCHAR(100),
    habilidades TEXT,
    experiencia TEXT,
    profesion VARCHAR(100),
    nivel_educativo ENUM('primaria', 'secundaria', 'tecnico', 'universitario', 'postgrado'),
    disponibilidad ENUM('inmediata', '15_dias', '30_dias', 'mas_30_dias'),
    cv_url VARCHAR(255),
    PRIMARY KEY (id),
    FOREIGN KEY (usuario_id) REFERENCES usuarios(id) 
        ON DELETE CASCADE 
        ON UPDATE CASCADE,
    INDEX idx_profesion (profesion)
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;

-- Tabla de empresas (información específica de empresas)
CREATE TABLE empresas_info (
    id INT AUTO_INCREMENT,
    usuario_id INT NOT NULL,
    ruc VARCHAR(20) UNIQUE,
    razon_social VARCHAR(200) NOT NULL,
    direccion VARCHAR(255),
    ciudad VARCHAR(100),
    pais VARCHAR(100),
    telefono VARCHAR(15),
    sitio_web VARCHAR(255),
    sector_industrial VARCHAR(100),
    descripcion TEXT,
    logo_url VARCHAR(255),
    correo_contacto VARCHAR(100),
    PRIMARY KEY (id),
    FOREIGN KEY (usuario_id) REFERENCES usuarios(id) 
        ON DELETE CASCADE 
        ON UPDATE CASCADE,
    INDEX idx_sector (sector_industrial)
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;

-- Tabla de categorías de trabajo
CREATE TABLE categorias_trabajo (
    id INT AUTO_INCREMENT,
    nombre VARCHAR(100) NOT NULL,
    descripcion TEXT,
    PRIMARY KEY (id),
    UNIQUE INDEX idx_nombre_categoria (nombre)
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;

-- Tabla de ofertas laborales
CREATE TABLE ofertas_laborales (
    id INT AUTO_INCREMENT,
    empresa_id INT NOT NULL,
    categoria_id INT NOT NULL,
    titulo VARCHAR(255) NOT NULL,
    descripcion TEXT NOT NULL,
    requisitos TEXT,
    profesion_solicitada VARCHAR(100),
    experiencia_requerida INT, -- en años
    salario_min DECIMAL(10,2),
    salario_max DECIMAL(10,2),
    tipo_contrato ENUM('tiempo_completo', 'medio_tiempo', 'temporal', 'freelance'),
    modalidad ENUM('presencial', 'remoto', 'hibrido'),
    ubicacion VARCHAR(255),
    fecha_publicacion TIMESTAMP DEFAULT CURRENT_TIMESTAMP,
    fecha_cierre DATE,
    estado ENUM('activa', 'cerrada', 'pausada') DEFAULT 'activa',
    beneficios TEXT,
    PRIMARY KEY (id),
    FOREIGN KEY (empresa_id) REFERENCES usuarios(id) 
        ON DELETE CASCADE 
        ON UPDATE CASCADE,
    FOREIGN KEY (categoria_id) REFERENCES categorias_trabajo(id) 
        ON DELETE RESTRICT 
        ON UPDATE CASCADE,
    INDEX idx_fecha_pub (fecha_publicacion),
    INDEX idx_estado (estado),
    INDEX idx_profesion (profesion_solicitada)
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;

-- Tabla de postulaciones
CREATE TABLE postulaciones (
    id INT AUTO_INCREMENT,
    oferta_id INT NOT NULL,
    postulante_id INT NOT NULL,
    fecha_postulacion TIMESTAMP DEFAULT CURRENT_TIMESTAMP,
    estado ENUM('pendiente', 'revisado', 'entrevista', 'aceptado', 'rechazado') DEFAULT 'pendiente',
    carta_presentacion TEXT,
    expectativa_salarial DECIMAL(10,2),
    fecha_disponibilidad DATE,
    ultima_actualizacion TIMESTAMP DEFAULT CURRENT_TIMESTAMP ON UPDATE CURRENT_TIMESTAMP,
    PRIMARY KEY (id),
    FOREIGN KEY (oferta_id) REFERENCES ofertas_laborales(id) 
        ON DELETE CASCADE 
        ON UPDATE CASCADE,
    FOREIGN KEY (postulante_id) REFERENCES usuarios(id) 
        ON DELETE CASCADE 
        ON UPDATE CASCADE,
    UNIQUE INDEX idx_postulacion_unica (oferta_id, postulante_id),
    INDEX idx_fecha_post (fecha_postulacion),
    INDEX idx_estado_post (estado)
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;

-- Tabla de experiencia laboral de postulantes
CREATE TABLE experiencia_laboral (
    id INT AUTO_INCREMENT,
    postulante_id INT NOT NULL,
    empresa VARCHAR(200) NOT NULL,
    cargo VARCHAR(150) NOT NULL,
    fecha_inicio DATE NOT NULL,
    fecha_fin DATE,
    descripcion TEXT,
    PRIMARY KEY (id),
    FOREIGN KEY (postulante_id) REFERENCES postulantes_info(id) 
        ON DELETE CASCADE 
        ON UPDATE CASCADE,
    INDEX idx_fecha_exp (fecha_inicio, fecha_fin)
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;

-- Tabla de educación de postulantes
CREATE TABLE educacion (
    id INT AUTO_INCREMENT,
    postulante_id INT NOT NULL,
    institucion VARCHAR(200) NOT NULL,
    titulo VARCHAR(150) NOT NULL,
    nivel ENUM('primaria', 'secundaria', 'tecnico', 'universitario', 'postgrado'),
    fecha_inicio DATE NOT NULL,
    fecha_fin DATE,
    PRIMARY KEY (id),
    FOREIGN KEY (postulante_id) REFERENCES postulantes_info(id) 
        ON DELETE CASCADE 
        ON UPDATE CASCADE,
    INDEX idx_nivel_edu (nivel)
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;

-- Tabla de habilidades (catálogo)
CREATE TABLE habilidades (
    id INT AUTO_INCREMENT,
    nombre VARCHAR(100) NOT NULL,
    tipo ENUM('tecnica', 'blanda') NOT NULL,
    PRIMARY KEY (id),
    UNIQUE INDEX idx_nombre_habilidad (nombre)
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;

-- Tabla de relación postulantes-habilidades
CREATE TABLE postulante_habilidades (
    postulante_id INT NOT NULL,
    habilidad_id INT NOT NULL,
    nivel ENUM('basico', 'intermedio', 'avanzado', 'experto'),
    PRIMARY KEY (postulante_id, habilidad_id),
    FOREIGN KEY (postulante_id) REFERENCES postulantes_info(id) 
        ON DELETE CASCADE 
        ON UPDATE CASCADE,
    FOREIGN KEY (habilidad_id) REFERENCES habilidades(id) 
        ON DELETE CASCADE 
        ON UPDATE CASCADE
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;

-- Procedimiento almacenado para registrar usuario
DELIMITER //
CREATE PROCEDURE sp_registrar_usuario(
    IN p_nombre VARCHAR(100),
    IN p_email VARCHAR(100),
    IN p_password VARCHAR(255),
    IN p_tipo ENUM('empresa', 'postulante')
)
BEGIN
    INSERT INTO usuarios (nombre, email, password, tipo)
    VALUES (p_nombre, p_email, p_password, p_tipo);
END //
DELIMITER ;

-- Trigger para actualizar último acceso
DELIMITER //
CREATE TRIGGER tr_actualizar_acceso
AFTER UPDATE ON usuarios
FOR EACH ROW
BEGIN
    IF NEW.ultimo_acceso != OLD.ultimo_acceso THEN
        UPDATE usuarios 
        SET ultimo_acceso = CURRENT_TIMESTAMP 
        WHERE id = NEW.id;
    END IF;
END //
DELIMITER ;

-- Vista para ofertas activas con información de empresa
CREATE VIEW v_ofertas_activas AS
SELECT 
    o.id,
    o.titulo,
    o.descripcion,
    o.profesion_solicitada,
    o.salario_min,
    o.salario_max,
    o.fecha_publicacion,
    e.razon_social as empresa,
    e.ciudad,
    c.nombre as categoria
FROM ofertas_laborales o
JOIN empresas_info e ON o.empresa_id = e.usuario_id
JOIN categorias_trabajo c ON o.categoria_id = c.id
WHERE o.estado = 'activa'
AND o.fecha_cierre >= CURRENT_DATE();