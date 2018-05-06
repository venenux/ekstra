SET @OLD_UNIQUE_CHECKS=@@UNIQUE_CHECKS, UNIQUE_CHECKS=0;
SET @OLD_FOREIGN_KEY_CHECKS=@@FOREIGN_KEY_CHECKS, FOREIGN_KEY_CHECKS=0;
SET @OLD_SQL_MODE=@@SQL_MODE, SQL_MODE='TRADITIONAL';

DROP SCHEMA IF EXISTS `elyanerodb` ;
CREATE SCHEMA IF NOT EXISTS `elyanerodb` DEFAULT CHARACTER SET utf8 COLLATE utf8_general_ci ;
USE `elyanerodb` ;

-- -----------------------------------------------------
-- Table `elyanerodb`.`esk_usuario`
-- -----------------------------------------------------
DROP TABLE IF EXISTS `elyanerodb`.`esk_usuario` ;

CREATE  TABLE IF NOT EXISTS `elyanerodb`.`esk_usuario` (
  `ficha` VARCHAR(40) NOT NULL COMMENT 'cedula o id del usuario, es unico' ,
  `username` VARCHAR(40) NOT NULL COMMENT 'nombre de usuario generalmente el correo sin el dominio' ,
  `userclave` VARCHAR(40) NULL ,
  `cod_nivel` VARCHAR(40) NULL COMMENT 'separado por comas cantidad de poder de acceso' ,
  `cod_app` VARCHAR(40) NULL COMMENT 'separado por comas directorio de controladores que tiene permiso ver' ,
  `ses_cookie` VARCHAR(400) NULL COMMENT 'nombre de cookie del session codeigniter' ,
  `ses_ip` VARCHAR(40) NULL COMMENT 'ip que tiene logeado , usada para identificacion' ,
  `sessionuser` VARCHAR(40) NULL COMMENT 'YYYYMMDDhhmmss.entidad session actual' ,
  `sessionlast` VARCHAR(40) NULL COMMENT 'YYYYMMDDhhmmss.entidad ultima session que paso' ,
  `sessionflag` VARCHAR(40) NULL COMMENT 'YYYYMMDDhhmmss.entidad.username quien modifico' ,
  `sessionficha` VARCHAR(40) NULL COMMENT 'YYYYMMDDhhmmss.entidad.username quien creo este registro' ,
  PRIMARY KEY (`ficha`, `username`) )
ENGINE = InnoDB
COMMENT = 'suaurios copia de sysdbadmin';


-- -----------------------------------------------------
-- Table `elyanerodb`.`esk_log`
-- -----------------------------------------------------
DROP TABLE IF EXISTS `elyanerodb`.`esk_log` ;

CREATE  TABLE IF NOT EXISTS `elyanerodb`.`esk_log` (
  `cod_fecha` VARCHAR(40) NOT NULL COMMENT 'YYYYMMDD' ,
  `username` VARCHAR(40) NULL ,
  `des_accion` VARCHAR(40) NULL ,
  `sessionflag` VARCHAR(40) NULL COMMENT 'YYYYMMDDhhmmss.entidad.username de este registro' ,
  PRIMARY KEY (`cod_fecha`) )
ENGINE = InnoDB;


-- -----------------------------------------------------
-- Table `elyanerodb`.`esk_modulo`
-- -----------------------------------------------------
DROP TABLE IF EXISTS `elyanerodb`.`esk_modulo` ;

CREATE  TABLE IF NOT EXISTS `elyanerodb`.`esk_modulo` (
  `cod_modulo` VARCHAR(40) NOT NULL COMMENT 'nombre unico sin espacio de la aplicacion migrada, max 12 caracteres' ,
  `estado_migracion` VARCHAR(40) NULL COMMENT 'de 1 a 5 a mayor mejor la compatiblidad, 1 es no sirve' ,
  `dir_controladores` VARCHAR(40) NULL COMMENT 'directorio de controladores en controller codeigniter' ,
  `fil_controladores` VARCHAR(40) NULL COMMENT 'separado por comas que controladores estan disponibles, use esto para menus' ,
  `sessionflag` VARCHAR(40) NULL COMMENT 'YYYYMMDDhhmmss.entidad.username quien modifico' ,
  `sessionficha` VARCHAR(40) NULL COMMENT 'YYYYMMDDhhmmss.entidad.username quien creo este registro' ,
  PRIMARY KEY (`cod_modulo`) )
ENGINE = InnoDB
COMMENT = 'tabla de que applicaciones estan migradas, y que ofrecen';


-- -----------------------------------------------------
-- Table `elyanerodb`.`esk_usuario_modulo`
-- -----------------------------------------------------
DROP TABLE IF EXISTS `elyanerodb`.`esk_usuario_modulo` ;

CREATE  TABLE IF NOT EXISTS `elyanerodb`.`esk_usuario_modulo` (
  `cod_indicador` VARCHAR(40) NOT NULL ,
  `username` VARCHAR(40) NULL ,
  `cod_modulo` VARCHAR(40) NULL ,
  `sessionflag` VARCHAR(40) NULL COMMENT 'YYYYMMDDhhmmss.entidad.username quien modifico' ,
  PRIMARY KEY (`cod_indicador`) )
ENGINE = InnoDB
COMMENT = 'que usuario puede usar que applicacion';


-- -----------------------------------------------------
-- Table `elyanerodb`.`esk_producto_almacen`
-- -----------------------------------------------------
DROP TABLE IF EXISTS `elyanerodb`.`esk_producto_almacen` ;

CREATE  TABLE IF NOT EXISTS `elyanerodb`.`esk_producto_almacen` (
  `cod_producto` VARCHAR(40) NOT NULL COMMENT 'codigo de id del producto' ,
  `cod_alterno` VARCHAR(40) NOT NULL COMMENT 'producto relacionado' ,
  `des_producto` VARCHAR(40) NULL COMMENT 'descripcion del producto' ,
  `sessionflag` VARCHAR(40) NULL COMMENT 'YYYYMMDDhhmmss.entidad.username quien modifico' ,
  `sessionficha` VARCHAR(40) NULL COMMENT 'YYYYMMDDhhmmss.entidad.username quien lo creo' ,
  PRIMARY KEY (`cod_producto`) )
ENGINE = InnoDB
COMMENT = 'productos que estan en el almacen, es como uan copia del pro' /* comment truncated */;


-- -----------------------------------------------------
-- Table `elyanerodb`.`esk_almacen_ubicaciones`
-- -----------------------------------------------------
DROP TABLE IF EXISTS `elyanerodb`.`esk_almacen_ubicaciones` ;

CREATE  TABLE IF NOT EXISTS `elyanerodb`.`esk_almacen_ubicaciones` (
  `cod_posicion` VARCHAR(40) NOT NULL COMMENT 'en que anden o andamio o lugar del almacen' ,
  `cod_producto` VARCHAR(40) NOT NULL COMMENT 'codigo del producto segun el ajuste' ,
  `des_posicion` VARCHAR(40) NULL COMMENT 'ientificacion humana de esta pposicion' ,
  `sessionflag` VARCHAR(40) NULL COMMENT 'YYYYMMDDhhmmss.entidad.username quien altero' ,
  `sessionficha` VARCHAR(40) NULL COMMENT 'YYYYMMDDhhmmss.entidad.username quien lo creo' ,
  PRIMARY KEY (`cod_posicion`, `cod_producto`) )
ENGINE = InnoDB
COMMENT = 'ubicaciones de posiciones del almacen';


-- -----------------------------------------------------
-- Table `elyanerodb`.`esk_movimientos_tipo`
-- -----------------------------------------------------
DROP TABLE IF EXISTS `elyanerodb`.`esk_movimientos_tipo` ;

CREATE  TABLE IF NOT EXISTS `elyanerodb`.`esk_movimientos_tipo` (
  `cod_movimiento` VARCHAR(40) NOT NULL COMMENT 'YYYYMMDDhhmmss' ,
  `tipo_movimiento` VARCHAR(40) NOT NULL COMMENT 'tipo d emovimeinto directo' ,
  `des_movimiento` TEXT NULL COMMENT 'descriptivo de este movimiento' ,
  `sessionflag` VARCHAR(40) NULL COMMENT 'YYYYMMDDhhmmss.entidad.username quien modifico' ,
  `sessionficha` VARCHAR(40) NULL COMMENT 'YYYYMMDDhhmmss.entidad.username quien lo creo' ,
  PRIMARY KEY (`tipo_movimiento`) )
ENGINE = InnoDB
COMMENT = 'tipo de movimiento';



SET SQL_MODE=@OLD_SQL_MODE;
SET FOREIGN_KEY_CHECKS=@OLD_FOREIGN_KEY_CHECKS;
SET UNIQUE_CHECKS=@OLD_UNIQUE_CHECKS;

-- -----------------------------------------------------
-- Data for table `elyanerodb`.`esk_usuario`
-- -----------------------------------------------------
START TRANSACTION;
USE `elyanerodb`;
INSERT INTO `elyanerodb`.`esk_usuario` (`ficha`, `username`, `userclave`, `cod_nivel`, `cod_app`, `ses_cookie`, `ses_ip`, `sessionuser`, `sessionlast`, `sessionflag`, `sessionficha`) VALUES ('99999990', 'admin', 'c4ca4238a0b923820dcc509a6f75849b', '1', NULL, NULL, NULL, NULL, NULL, NULL, NULL);

COMMIT;
