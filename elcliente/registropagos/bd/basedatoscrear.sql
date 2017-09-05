CREATE TABLE IF NOT EXISTS `usuarios_sistema`.`usuarios` (
  `ficha` VARCHAR(45) NOT NULL,
  `intranet` VARCHAR(45) NOT NULL,
  `clave` VARCHAR(45) NOT NULL,
  `codfondo` VARCHAR(45) NULL,
  `detallles` VARCHAR(45) NULL,
  `estado` VARCHAR(45) NULL,
  `tipousuario` VARCHAR(45) NULL,
  `codnivel` VARCHAR(45) NULL,
  `reservado1` VARCHAR(45) NULL,
  `reservado2` VARCHAR(45) NULL,
  `fechaultimavez` VARCHAR(45) NULL,
  `sesionflag` VARCHAR(45) NULL,
  `seeionfecha` VARCHAR(45) NULL,
  PRIMARY KEY (`ficha`))
ENGINE = InnoDB
DEFAULT CHARACTER SET = latin1
COMMENT = 'Tabla de usuarios '

CREATE TABLE IF NOT EXISTS `usuarios_sistema`.`tiendaxzona` (
  `codigomtsc` VARCHAR(45) NOT NULL,
  `detalles` VARCHAR(250) NULL,
  `ubicación` VARCHAR(45) NULL,
  `codencargado` VARCHAR(45) NOT NULL,
  PRIMARY KEY (`codigomtsc`))
ENGINE = InnoDB
DEFAULT CHARACTER SET = latin1
COMMENT = 'Tabla que da info de la tienda su ubicación y a quien tiene asignado como operador de ciclico '



CREATE TABLE IF NOT EXISTS `sysciclicos`.`tienda` (
  `codentidad` VARCHAR(45) NOT NULL,
  `abrentidad` VARCHAR(250) NULL,
  `abrzona` VARCHAR(45) NULL,
  `descripcion` VARCHAR(45) NOT NULL,
  `estatus` VARCHAR(45) NULL,
 `fichaoperador` VARCHAR(45) NULL,
 `codmsc` VARCHAR(45) NULL, 
 PRIMARY KEY (`codentidad`))
ENGINE = InnoDB
DEFAULT CHARACTER SET = latin1
COMMENT = 'Tabla que da info de la tienda su ubicación y a quien tiene asignado como operador de ciclico '


