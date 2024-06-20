CREATE DATABASE IF NOT EXISTS dpwsld DEFAULT CHARACTER SET latin1;

USE dpwsld;

CREATE TABLE usuarios (
	idusuario INT not null auto_increment primary key,
	nombreusuario varchar(100) not null unique,
	clave varchar(200) DEFAULT NULL,
	nombres varchar(100) not null,
	apellidos varchar(100) not null,
	carnetdocente char(6) unique,
	email varchar(150) DEFAULT NULL,
 	tel varchar(9) DEFAULT NULL,
 	celular varchar(9) DEFAULT NULL,
	esjurado TINYINT(1) default 1,
	esasesor TINYINT(1) default 1,
	accesosistema TINYINT(1) default 1,
	esadmin TINYINT(1) default 1
)engine=InnoDB;

DROP TABLE IF EXISTS grupos;

CREATE TABLE grupos (
  idgrupo INT unsigned NOT NULL auto_increment primary key,
  grupo varchar(20) NOT NULL
) ENGINE=InnoDB;

DROP TABLE IF EXISTS alumnos;

CREATE TABLE alumnos (
  idalumno INT unsigned not null auto_increment primary key,
  carnet char(6) default null,
  nombres varchar(100) DEFAULT NULL,
  apellidos varchar(20) DEFAULT NULL,
  sexo ENUM('masculino', 'femenino') default null,
  email varchar(150) DEFAULT NULL,
  jornada varchar(10) DEFAULT NULL,
  direccion varchar(200) DEFAULT NULL,
  telalumno char(9) DEFAULT NULL,
  responsable varchar(100) DEFAULT NULL,
  telresponsable char(9) DEFAULT NULL,
  clave varchar(255),
  estadoalumno varchar(2) NOT NULL DEFAULT 'H' COMMENT 'H alumno activo I alumno inactivo D alumno desertor',
  yearingreso int(4) DEFAULT null,
  idgrupo INT unsigned DEFAULT NULL,
  CONSTRAINT fk_alumnos_grupo FOREIGN KEY (idgrupo) REFERENCES grupos(idgrupo)
) ENGINE=InnoDB;

DROP TABLE IF EXISTS empresas;

CREATE TABLE empresas (
  idempresa INT unsigned NOT NULL auto_increment primary key,
  empresa varchar(150) DEFAULT NULL,
  contacto varchar(100) DEFAULT NULL,
  direccion varchar(200) DEFAULT NULL,
  email varchar(150) DEFAULT NULL,
  telefono char(9) DEFAULT NULL
) ENGINE=InnoDB;

DROP TABLE IF EXISTS proyectos;

CREATE TABLE proyectos (
  idproyecto INT unsigned NOT NULL auto_increment primary key,
  tema varchar(250) DEFAULT NULL,
  idempresa INT unsigned DEFAULT NULL,
  idasesor INT DEFAULT NULL,
  objetivos text,
  alcylim text,
  observaciones text,
  cd tinyint(1) DEFAULT NULL,
  estado varchar(30) DEFAULT 'Sin evaluar',
  motivo text,
  justificacion text,
  resultadoses text,
  fechapresentacion date DEFAULT NULL,
  doc varchar(200) DEFAULT NULL,
  CONSTRAINT fk_proyectos_empresas FOREIGN KEY (idempresa) REFERENCES empresas (idempresa),
  CONSTRAINT fk_proyectos_usuarios FOREIGN KEY (idasesor) REFERENCES usuarios (idusuario)
) ENGINE=InnoDB;

DROP TABLE IF EXISTS alumnosxproyecto;

CREATE TABLE alumnosxproyecto (
  idalumno INT unsigned NOT NULL,
  idproyecto INT unsigned NOT NULL,
  PRIMARY KEY (idalumno,idproyecto),
  CONSTRAINT fk_alumnosxproyecto_alumnos FOREIGN KEY (idalumno) REFERENCES alumnos (idalumno),
  CONSTRAINT fk_alumnosxproyecto_proyectos FOREIGN KEY (idproyecto) REFERENCES proyectos (idproyecto)
) ENGINE=InnoDB;

DROP TABLE IF EXISTS asistencias;

CREATE TABLE asistencias (
  idasistencia INT NOT NULL auto_increment primary key,
  idasesor INT DEFAULT NULL,
  idproyecto INT unsigned DEFAULT NULL,
  fecha date DEFAULT NULL,
  observacion text,
  CONSTRAINT fk_asistencias_asesores FOREIGN KEY (idasesor) REFERENCES usuarios (idusuario),
  CONSTRAINT fk_asistencias_proyectos FOREIGN KEY (idproyecto) REFERENCES proyectos (idproyecto)
) ENGINE=InnoDB DEFAULT CHARSET=latin1 ROW_FORMAT=DYNAMIC;

DROP TABLE IF EXISTS fases;

CREATE TABLE fases (
  idfase INT unsigned NOT NULL auto_increment primary key ,
  fase varchar(200) DEFAULT NULL,
  porcentaje int(3) DEFAULT NULL,
  porcentajeind int(3) DEFAULT NULL,
  porcentajegrup int(3) DEFAULT NULL,
  fechaini date DEFAULT NULL,
  fechafin date DEFAULT NULL,
  activo char(1) DEFAULT 's',
  tipo varchar(8) DEFAULT NULL,
  year int(4) DEFAULT NULL
) ENGINE=InnoDB;

DROP TABLE IF EXISTS criterios;

CREATE TABLE criterios (
  idcriterio INT unsigned NOT NULL AUTO_INCREMENT,
  idfase INT unsigned DEFAULT NULL,
  criterio varchar(250) DEFAULT NULL,
  porcentaje int(3) DEFAULT NULL,
  tipo int(1) DEFAULT NULL,
  estado varchar(20) DEFAULT NULL,
  PRIMARY KEY (idcriterio),
  CONSTRAINT fk_criterios_fases FOREIGN KEY (idfase) REFERENCES fases (idfase)
) ENGINE=InnoDB AUTO_INCREMENT=384 DEFAULT CHARSET=latin1 AVG_ROW_LENGTH=36 ROW_FORMAT=DYNAMIC;

DROP TABLE IF EXISTS evaluaciones;

CREATE TABLE evaluaciones (
  idevaluacion INT unsigned NOT NULL AUTO_INCREMENT,
  idproyecto INT unsigned DEFAULT NULL,
  idfase INT unsigned DEFAULT NULL,
  idjurado INT unsigned DEFAULT NULL,
  idalumno INT unsigned DEFAULT NULL,
  notaind decimal(5,2) DEFAULT NULL,
  notagrup decimal(5,2) DEFAULT NULL,
  notafin decimal(5,2) DEFAULT NULL,
  fecha date DEFAULT NULL,
  hora time DEFAULT NULL,
  observaciones text,
  PRIMARY KEY (idevaluacion),
  CONSTRAINT fk_evaluaciones_proyectos FOREIGN KEY (idproyecto) REFERENCES proyectos (idproyecto),
  CONSTRAINT fk_evaluaciones_fases FOREIGN KEY (idfase) REFERENCES fases (idfase)
) ENGINE=InnoDB;

DROP TABLE IF EXISTS juradosxproyecto;

CREATE TABLE juradosxproyecto (
  idproyecto INT unsigned not null,
  idjurado INT not null,
  PRIMARY KEY (idproyecto,idjurado),
  CONSTRAINT fk_juradosxproyecto_proyectos FOREIGN KEY (idproyecto) REFERENCES proyectos (idproyecto),
  CONSTRAINT fk_juradosxproyecto_usuarios FOREIGN KEY (idjurado) REFERENCES usuarios (idusuario)
) ENGINE=InnoDB;

DROP TABLE IF EXISTS observaciones;

CREATE TABLE observaciones (
  idobser INT unsigned NOT NULL auto_increment primary key,
  idasesor INT DEFAULT NULL,
  idproyecto INT unsigned DEFAULT NULL,
  fecha date DEFAULT NULL,
  observacion text,
  CONSTRAINT fk_observaciones_usuarios FOREIGN KEY (idasesor) REFERENCES usuarios (idusuario),
  CONSTRAINT fk_observaciones_proyectos FOREIGN KEY (idproyecto) REFERENCES proyectos (idproyecto)
) ENGINE=InnoDB;

DROP TABLE IF EXISTS proyectofase;

CREATE TABLE proyectofase (
  idproyectofase INT unsigned NOT NULL auto_increment primary key,
  idfase INT unsigned DEFAULT NULL,
  idproyecto INT unsigned DEFAULT NULL,
  year int(4) DEFAULT NULL,
  estadopf varchar(15) DEFAULT NULL,
  CONSTRAINT fk_proyectofase_fases FOREIGN KEY (idfase) REFERENCES fases (idfase),
  CONSTRAINT fk_proyectofase_proyectos FOREIGN KEY (idproyecto) REFERENCES proyectos (idproyecto)
) ENGINE=InnoDB;

DROP TABLE IF EXISTS supervisores;

CREATE TABLE supervisores (
  idsupervisor INT unsigned NOT NULL auto_increment primary key,
  nombres varchar(100) DEFAULT NULL,
  apellidos varchar(100) DEFAULT NULL,
  email varchar(150) DEFAULT NULL,
  tel char(9) DEFAULT NULL,
  celular char(9) DEFAULT NULL
) ENGINE=InnoDB;

