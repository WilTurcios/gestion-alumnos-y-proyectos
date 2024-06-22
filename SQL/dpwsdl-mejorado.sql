-- use sys;
-- drop database dpwsld;
CREATE DATABASE IF NOT EXISTS dpwsld DEFAULT CHARACTER SET latin1;

USE dpwsld;

CREATE TABLE usuarios (
  id INT UNSIGNED NOT NULL AUTO_INCREMENT PRIMARY KEY,
  carnet_docente CHAR(6) NOT NULL UNIQUE,
  usuario VARCHAR(100) NOT NULL UNIQUE,
  clave VARCHAR(200) DEFAULT NULL,
  nombres VARCHAR(100) NOT NULL,
  apellidos VARCHAR(100) NOT NULL,
  email VARCHAR(150) DEFAULT NULL,
  telefono VARCHAR(9) DEFAULT NULL,
  celular VARCHAR(9) DEFAULT NULL,
  es_jurado TINYINT(1) DEFAULT '1',
  es_asesor TINYINT(1) DEFAULT '1',
  acceso_sistema TINYINT(1) DEFAULT '1',
  es_admin TINYINT(1) DEFAULT '1'
) ENGINE=InnoDB AUTO_INCREMENT=15 DEFAULT CHARSET=latin1;

DROP TABLE IF EXISTS grupos;

CREATE TABLE grupos (
  id INT unsigned NOT NULL auto_increment primary key,
  nombre varchar(20) NOT NULL
) ENGINE=InnoDB;

DROP TABLE IF EXISTS alumnos;

CREATE TABLE alumnos (
  id INT UNSIGNED NOT NULL AUTO_INCREMENT PRIMARY KEY,
  carnet CHAR(6) UNIQUE,
  nombres VARCHAR(100),
  apellidos VARCHAR(20),
  sexo ENUM('masculino', 'femenino'),
  email VARCHAR(150) UNIQUE,
  jornada VARCHAR(10),
  direccion VARCHAR(200),
  telefono_alumno CHAR(9) UNIQUE,
  responsable VARCHAR(100),
  telefono_responsable CHAR(9) UNIQUE,
  clave VARCHAR(255),
  estado_alumno VARCHAR(2) NOT NULL DEFAULT 'H' COMMENT 'H alumno activo I alumno inactivo D alumno desertor',
  year_ingreso INT,
  id_grupo INT UNSIGNED,
  CONSTRAINT fk_alumnos_grupo FOREIGN KEY (id_grupo) REFERENCES grupos (id)
) ENGINE=InnoDB AUTO_INCREMENT=12 DEFAULT CHARSET=latin1;

DROP TABLE IF EXISTS empresas;

CREATE TABLE empresas (
  id INT UNSIGNED NOT NULL AUTO_INCREMENT PRIMARY KEY,
  empresa VARCHAR(150) DEFAULT null UNIQUE,
  contacto VARCHAR(100) DEFAULT null,
  direccion VARCHAR(200) DEFAULT NULL,
  email VARCHAR(150) DEFAULT null UNIQUE,
  telefono CHAR(9) DEFAULT null UNIQUE,
  creado_por INT UNSIGNED NOT null references usuario(id),
) ENGINE=InnoDB AUTO_INCREMENT=6 DEFAULT CHARSET=latin1;

DROP TABLE IF EXISTS proyectos;

CREATE TABLE proyectos (
  id INT UNSIGNED NOT NULL AUTO_INCREMENT PRIMARY KEY,
  tema VARCHAR(250) DEFAULT NULL,
  id_empresa INT UNSIGNED DEFAULT NULL,
  id_asesor INT UNSIGNED DEFAULT NULL,
  objetivos TEXT,
  alcances_limitantes TEXT,
  observaciones TEXT,
  cd TINYINT(1) DEFAULT NULL,
  estado ENUM('Evaluado', 'Sin evaluar') DEFAULT 'Sin evaluar',
  motivo TEXT,
  justificacion TEXT,
  resultados_esperados TEXT,
  fecha_presentacion DATE DEFAULT NULL,
  doc VARCHAR(200) DEFAULT NULL,
  creado_por INT UNSIGNED NOT null references usuarios(id),
  CONSTRAINT fk_proyectos_empresas FOREIGN KEY (id_empresa) REFERENCES empresas (id),
  CONSTRAINT fk_proyectos_usuarios FOREIGN KEY (id_asesor) REFERENCES usuarios (id)
) ENGINE=InnoDB AUTO_INCREMENT=6 DEFAULT CHARSET=latin1;

DROP TABLE IF EXISTS alumnosxproyecto;

CREATE TABLE alumnosxproyecto (
  id INT UNSIGNED NOT NULL AUTO_INCREMENT PRIMARY KEY,
  id_alumno INT UNSIGNED NOT NULL,
  id_proyecto INT UNSIGNED NOT null,
  CONSTRAINT fk_alumnosxproyecto_alumnos FOREIGN KEY (id_alumno) REFERENCES alumnos (id),
  CONSTRAINT fk_alumnosxproyecto_proyectos FOREIGN KEY (id_proyecto) REFERENCES proyectos (id)
) ENGINE=InnoDB AUTO_INCREMENT=6 DEFAULT CHARSET=latin1;


DROP TABLE IF EXISTS asistencias;

CREATE TABLE asistencias (
  id INT NOT NULL AUTO_INCREMENT PRIMARY KEY,
  id_asesor INT UNSIGNED DEFAULT NULL,
  id_proyecto INT UNSIGNED DEFAULT NULL,
  fecha DATE DEFAULT NULL,
  observacion TEXT,
  CONSTRAINT fk_asistencias_asesores FOREIGN KEY (id_asesor) REFERENCES usuarios (id),
  CONSTRAINT fk_asistencias_proyectos FOREIGN KEY (id_proyecto) REFERENCES proyectos (id)
) ENGINE=InnoDB DEFAULT CHARSET=latin1 ROW_FORMAT=DYNAMIC;


DROP TABLE IF EXISTS materias;

CREATE TABLE materias (
  id INT UNSIGNED NOT NULL AUTO_INCREMENT PRIMARY KEY,
  nombre VARCHAR(200) DEFAULT NULL UNIQUE,
  porcentaje INT DEFAULT NULL,
  porcentaje_individual INT DEFAULT NULL,
  porcentaje_grupal INT DEFAULT NULL,
  fecha_inicio DATE DEFAULT NULL,
  fecha_fin DATE DEFAULT NULL,
  activo ENUM('S', 'N') DEFAULT 'S',
  tipo VARCHAR(8) DEFAULT NULL,
  year INT DEFAULT NULL,
  creado_por INT UNSIGNED NOT null references usuarios(id),
  CONSTRAINT fk_materias_usuarios FOREIGN KEY (creado_por) REFERENCES usuarios (id)
) ENGINE=InnoDB AUTO_INCREMENT=6 DEFAULT CHARSET=latin1;


DROP TABLE IF EXISTS criterios;

CREATE TABLE criterios (
  id INT UNSIGNED NOT NULL AUTO_INCREMENT PRIMARY KEY,
  id_materia INT UNSIGNED DEFAULT NULL,
  criterio VARCHAR(250) DEFAULT NULL,
  porcentaje INT DEFAULT NULL,
  tipo ENUM('individual', 'grupal') DEFAULT 'grupal',
  estado VARCHAR(20) DEFAULT NULL,
  creado_por INT UNSIGNED NOT NULL,
  CONSTRAINT fk_criterios_materia FOREIGN KEY (id_materia) REFERENCES materias (id)
) ENGINE=InnoDB AUTO_INCREMENT=399 DEFAULT CHARSET=latin1 AVG_ROW_LENGTH=36 ROW_FORMAT=DYNAMIC;


DROP TABLE IF EXISTS evaluaciones;

CREATE TABLE evaluaciones (
  id INT UNSIGNED NOT NULL AUTO_INCREMENT PRIMARY KEY,
  id_proyecto INT UNSIGNED DEFAULT NULL,
  id_materia INT UNSIGNED DEFAULT NULL,
  id_jurado INT UNSIGNED DEFAULT NULL,
  id_alumno INT UNSIGNED DEFAULT NULL,
  nota_individual DECIMAL(5,2) DEFAULT NULL,
  nota_grupal DECIMAL(5,2) DEFAULT NULL,
  nota_final DECIMAL(5,2) DEFAULT NULL,
  fecha DATE DEFAULT NULL,
  hora TIME DEFAULT NULL,
  observaciones TEXT,
  creado_por INT UNSIGNED NOT null references usuario(id),
  CONSTRAINT fk_evaluaciones_proyectos FOREIGN KEY (id_proyecto) REFERENCES proyectos (id),
  CONSTRAINT fk_evaluaciones_materias FOREIGN KEY (id_materia) REFERENCES materias (id)
) ENGINE=InnoDB AUTO_INCREMENT=2 DEFAULT CHARSET=latin1;

DROP TABLE IF EXISTS juradosxproyecto;

CREATE TABLE juradosxproyecto (
  id INT UNSIGNED NOT NULL AUTO_INCREMENT PRIMARY KEY,
  id_proyecto INT UNSIGNED NOT NULL,
  id_jurado INT UNSIGNED NOT NULL,
  CONSTRAINT fk_juradosxproyecto_proyectos FOREIGN KEY (id_proyecto) REFERENCES proyectos (id),
  CONSTRAINT fk_juradosxproyecto_usuarios FOREIGN KEY (id_jurado) REFERENCES usuarios (id)
) ENGINE=InnoDB DEFAULT CHARSET=latin1;

DROP TABLE IF EXISTS observaciones;

CREATE TABLE observaciones (
  id INT UNSIGNED NOT NULL AUTO_INCREMENT PRIMARY KEY,
  id_asesor INT UNSIGNED DEFAULT NULL,
  id_proyecto INT UNSIGNED DEFAULT NULL,
  fecha DATE DEFAULT NULL,
  observacion TEXT,
  CONSTRAINT fk_observaciones_usuarios FOREIGN KEY (id_asesor) REFERENCES usuarios (id),
  CONSTRAINT fk_observaciones_proyectos FOREIGN KEY (id_proyecto) REFERENCES proyectos (id)
) ENGINE=InnoDB DEFAULT CHARSET=latin1;


DROP TABLE IF EXISTS proyectoxmateria;

CREATE TABLE proyectoxmateria (
  id INT UNSIGNED NOT NULL AUTO_INCREMENT PRIMARY KEY,
  id_materia INT UNSIGNED DEFAULT NULL,
  id_proyecto INT UNSIGNED DEFAULT NULL,
  CONSTRAINT fk_proyectofase_fases FOREIGN KEY (id_materia) REFERENCES materias (id),
  CONSTRAINT fk_proyectofase_proyectos FOREIGN KEY (id_proyecto) REFERENCES proyectos (id)
) ENGINE=InnoDB AUTO_INCREMENT=2 DEFAULT CHARSET=latin1;


DELIMITER //

CREATE trigger before_insert_criterio 
BEFORE INSERT ON criterios 
FOR EACH ROW 
BEGIN
    DECLARE total_individual INT;
    DECLARE total_grupal INT;
    DECLARE porcentaje_materia INT;
    DECLARE porcentaje_individual_materia INT;
    DECLARE porcentaje_grupal_materia INT;

    -- Obtener los porcentajes de la materia relacionada
    SELECT porcentaje, porcentaje_individual, porcentaje_grupal 
    INTO porcentaje_materia, porcentaje_individual_materia, porcentaje_grupal_materia
    FROM materias 
    WHERE id = NEW.id_materia;

    -- Calcular la suma de los porcentajes individuales y grupales existentes para la materia
    SELECT 
        IFNULL(SUM(CASE WHEN tipo = 'individual' THEN porcentaje ELSE 0 END), 0),
        IFNULL(SUM(CASE WHEN tipo = 'grupal' THEN porcentaje ELSE 0 END), 0)
    INTO total_individual, total_grupal
    FROM criterios
    WHERE id_materia = NEW.id_materia;

    -- Validar que la suma de los porcentajes no supere los límites permitidos
    IF (NEW.tipo = 'individual' AND ((total_individual + NEW.porcentaje) > 100)) OR
       (NEW.tipo = 'grupal' AND ((total_grupal + NEW.porcentaje) > 100)) OR
       (((total_individual * porcentaje_individual_materia / 100) + (total_grupal * porcentaje_grupal_materia / 100) + NEW.porcentaje * (CASE WHEN NEW.tipo = 'individual' THEN porcentaje_individual_materia ELSE porcentaje_grupal_materia END) / 100) > porcentaje_materia) THEN
        SIGNAL SQLSTATE '45000' 
        SET MESSAGE_TEXT = 'Error en la inserción: El porcentaje del criterio supera el porcentaje asignado en la materia o el porcentaje restante permitido.',
            MYSQL_ERRNO = 1644;
    END IF;
END//

CREATE TRIGGER before_update_criterio 
BEFORE UPDATE ON criterios
FOR EACH ROW 
BEGIN
    DECLARE total_individual INT;
    DECLARE total_grupal INT;
    DECLARE porcentaje_materia INT;
    DECLARE porcentaje_individual_materia INT;
    DECLARE porcentaje_grupal_materia INT;

    -- Obtener los porcentajes de la materia relacionada
    SELECT porcentaje, porcentaje_individual, porcentaje_grupal 
    INTO porcentaje_materia, porcentaje_individual_materia, porcentaje_grupal_materia
    FROM materias 
    WHERE id = NEW.id_materia;

    -- Calcular la suma de los porcentajes individuales y grupales existentes para la materia, excluyendo el criterio que se está actualizando
    SELECT 
        IFNULL(SUM(CASE WHEN tipo = 'individual' THEN porcentaje ELSE 0 END), 0) - OLD.porcentaje,
        IFNULL(SUM(CASE WHEN tipo = 'grupal' THEN porcentaje ELSE 0 END), 0) - OLD.porcentaje
    INTO total_individual, total_grupal
    FROM criterios
    WHERE id_materia = NEW.id_materia;

    -- Validar que la suma de los porcentajes no supere los límites permitidos
    IF (NEW.tipo = 'individual' AND ((total_individual + NEW.porcentaje) > 100)) OR
       (NEW.tipo = 'grupal' AND ((total_grupal + NEW.porcentaje) > 100)) OR
       (((total_individual * porcentaje_individual_materia / 100) + (total_grupal * porcentaje_grupal_materia / 100) + NEW.porcentaje * (CASE WHEN NEW.tipo = 'individual' THEN porcentaje_individual_materia ELSE porcentaje_grupal_materia END) / 100) > porcentaje_materia) THEN
        SIGNAL SQLSTATE '45000' 
        SET MESSAGE_TEXT = 'Error en la actualización: El porcentaje del criterio supera el porcentaje asignado en la materia o el porcentaje restante permitido.',
            MYSQL_ERRNO = 1644;
    END IF;
END//

DELIMITER ;
