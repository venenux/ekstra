SET @OLD_UNIQUE_CHECKS=@@UNIQUE_CHECKS, UNIQUE_CHECKS=0;
SET @OLD_FOREIGN_KEY_CHECKS=@@FOREIGN_KEY_CHECKS, FOREIGN_KEY_CHECKS=0;
SET @OLD_SQL_MODE=@@SQL_MODE, SQL_MODE='TRADITIONAL';

DROP SCHEMA IF EXISTS `elalmacenwebdb` ;
CREATE SCHEMA IF NOT EXISTS `elalmacenwebdb` DEFAULT CHARACTER SET utf8 COLLATE utf8_general_ci ;
USE `elalmacenwebdb` ;

-- -----------------------------------------------------
-- Table `elalmacenwebdb`.`esk_usuario`
-- -----------------------------------------------------
DROP TABLE IF EXISTS `elalmacenwebdb`.`esk_usuario` ;

CREATE  TABLE IF NOT EXISTS `elalmacenwebdb`.`esk_usuario` (
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
-- Table `elalmacenwebdb`.`esk_log`
-- -----------------------------------------------------
DROP TABLE IF EXISTS `elalmacenwebdb`.`esk_log` ;

CREATE  TABLE IF NOT EXISTS `elalmacenwebdb`.`esk_log` (
  `cod_fecha` VARCHAR(40) NOT NULL COMMENT 'YYYYMMDD' ,
  `username` VARCHAR(40) NULL ,
  `des_accion` VARCHAR(40) NULL ,
  `sessionflag` VARCHAR(40) NULL COMMENT 'YYYYMMDDhhmmss.entidad.username de este registro' ,
  PRIMARY KEY (`cod_fecha`) )
ENGINE = InnoDB;


-- -----------------------------------------------------
-- Table `elalmacenwebdb`.`esk_modulo`
-- -----------------------------------------------------
DROP TABLE IF EXISTS `elalmacenwebdb`.`esk_modulo` ;

CREATE  TABLE IF NOT EXISTS `elalmacenwebdb`.`esk_modulo` (
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
-- Table `elalmacenwebdb`.`esk_usuario_modulo`
-- -----------------------------------------------------
DROP TABLE IF EXISTS `elalmacenwebdb`.`esk_usuario_modulo` ;

CREATE  TABLE IF NOT EXISTS `elalmacenwebdb`.`esk_usuario_modulo` (
  `cod_indicador` VARCHAR(40) NOT NULL ,
  `username` VARCHAR(40) NULL ,
  `cod_modulo` VARCHAR(40) NULL ,
  `sessionflag` VARCHAR(40) NULL COMMENT 'YYYYMMDDhhmmss.entidad.username quien modifico' ,
  PRIMARY KEY (`cod_indicador`) )
ENGINE = InnoDB
COMMENT = 'que usuario puede usar que applicacion';


-- -----------------------------------------------------
-- Table `elalmacenwebdb`.`esk_productos`
-- -----------------------------------------------------
DROP TABLE IF EXISTS `elalmacenwebdb`.`esk_productos` ;

CREATE  TABLE IF NOT EXISTS `elalmacenwebdb`.`esk_productos` (
  `cod_producto` VARCHAR(40) NOT NULL COMMENT 'codigo de id del producto' ,
  `cod_alterno` VARCHAR(40) NOT NULL COMMENT 'producto relacionado' ,
  `des_producto` VARCHAR(40) NULL COMMENT 'descripcion del producto' ,
  `sessionflag` VARCHAR(40) NULL COMMENT 'YYYYMMDDhhmmss.entidad.username quien modifico' ,
  `sessionficha` VARCHAR(40) NULL COMMENT 'YYYYMMDDhhmmss.entidad.username quien lo creo' ,
  PRIMARY KEY (`cod_producto`) )
ENGINE = InnoDB
COMMENT = 'productos que estan en el almacen, es como uan copia del pro' /* comment truncated */;


-- -----------------------------------------------------
-- Table `elalmacenwebdb`.`esk_almacen_producto`
-- -----------------------------------------------------
DROP TABLE IF EXISTS `elalmacenwebdb`.`esk_almacen_producto` ;

CREATE  TABLE IF NOT EXISTS `elalmacenwebdb`.`esk_almacen_producto` (
  `cod_ubicacion` VARCHAR(40) NOT NULL COMMENT 'posicion en que anden o andamio o lugar del almacen' ,
  `cod_producto` VARCHAR(40) NOT NULL COMMENT 'codigo del producto segun el ajuste' ,
  `can_producto` DECIMAL(20,2) NOT NULL DEFAULT 0 COMMENT 'directa la existencia en cada posicion del cada producto' ,
  `sessionflag` VARCHAR(40) NULL COMMENT 'YYYYMMDDhhmmss.entidad.username quien altero' ,
  `sessionficha` VARCHAR(40) NULL COMMENT 'YYYYMMDDhhmmss.entidad.username quien lo creo' ,
  PRIMARY KEY (`cod_ubicacion`, `cod_producto`) )
ENGINE = InnoDB
COMMENT = 'existencia y localizacion del producto en una sola tabla';


-- -----------------------------------------------------
-- Table `elalmacenwebdb`.`esk_movimientos_tipo`
-- -----------------------------------------------------
DROP TABLE IF EXISTS `elalmacenwebdb`.`esk_movimientos_tipo` ;

CREATE  TABLE IF NOT EXISTS `elalmacenwebdb`.`esk_movimientos_tipo` (
  `cod_movimiento` VARCHAR(40) NOT NULL COMMENT 'YYYYMMDDhhmmss' ,
  `tipo_movimiento` VARCHAR(40) NOT NULL COMMENT 'tipo d emovimeinto directo' ,
  `des_movimiento` TEXT NULL COMMENT 'descriptivo de este movimiento' ,
  `sessionflag` VARCHAR(40) NULL COMMENT 'YYYYMMDDhhmmss.entidad.username quien modifico' ,
  `sessionficha` VARCHAR(40) NULL COMMENT 'YYYYMMDDhhmmss.entidad.username quien lo creo' ,
  PRIMARY KEY (`tipo_movimiento`) )
ENGINE = InnoDB
COMMENT = 'tipo de movimiento';


-- -----------------------------------------------------
-- Table `elalmacenwebdb`.`esk_almacen_ubicacion`
-- -----------------------------------------------------
DROP TABLE IF EXISTS `elalmacenwebdb`.`esk_almacen_ubicacion` ;

CREATE  TABLE IF NOT EXISTS `elalmacenwebdb`.`esk_almacen_ubicacion` (
  `cod_ubicacion` VARCHAR(40) NOT NULL COMMENT 'YYYYMMDDhhmmss el digo posicion es el de la suma de las columnas' ,
  `cod_posicion` VARCHAR(40) NULL COMMENT 'dado no es fiable, es la suma separada por \"-\" de valores de columnas' ,
  `estado_ubicacion` VARCHAR(40) NULL DEFAULT 'ACTIVA' COMMENT 'ACTIVA,INACTIVA - inactiva es que se elimino o cambio, pero no se puede borrar nunca para que se conserve historico en productos' ,
  `cod_almacen` VARCHAR(40) NULL DEFAULT '000' COMMENT 'en que almacen esta esta posicion' ,
  `cod_pasillo` VARCHAR(40) NULL DEFAULT '000' COMMENT 'identificacion del pasillo' ,
  `cod_lado` VARCHAR(40) NULL DEFAULT '000' COMMENT 'identificacion o lado de el pasillo' ,
  `cod_modulo` VARCHAR(40) NULL DEFAULT '9999999999999' COMMENT 'identificacion del andamio o mueble o modulo, 13 digitos' ,
  `cod_altura` VARCHAR(40) NULL DEFAULT '000' COMMENT 'en que nivel del modulo' ,
  `cod_area` VARCHAR(40) NULL DEFAULT 'ALM' COMMENT 'NOR,SUR,EST,OES,ALM - hacia que zona del almacen, ALM si no se define' ,
  `cor_mapa_x` VARCHAR(40) NULL COMMENT 'del mapa visto arriba eje x' ,
  `cor_mapa_y` VARCHAR(40) NULL COMMENT 'del mapa visto arriba eje y' ,
  `cor_mapa_z` VARCHAR(40) NULL COMMENT 'en mayor casos es el piso' ,
  `ind_reparar` VARCHAR(40) NULL DEFAULT 'NO' COMMENT 'SI|NO - indicador si hay que corregir esta posicion por datos incorrectos' ,
  `sessionflag` VARCHAR(40) NULL COMMENT 'YYYYMMDDhhmmss.entidad.username quien altero' ,
  `sessionficha` VARCHAR(40) NULL COMMENT 'YYYYMMDDhhmmss.entidad.username quien lo creo' ,
  PRIMARY KEY (`cod_ubicacion`) )
ENGINE = InnoDB
COMMENT = 'las pociciones o ubicaciones del alamacen';


-- -----------------------------------------------------
-- Table `elalmacenwebdb`.`esk_almacen_mapa`
-- -----------------------------------------------------
DROP TABLE IF EXISTS `elalmacenwebdb`.`esk_almacen_mapa` ;

CREATE  TABLE IF NOT EXISTS `elalmacenwebdb`.`esk_almacen_mapa` (
  `cod_almacen` VARCHAR(40) NOT NULL COMMENT 'id sello o que se yo, enlazar con la entidad correspondiente de clase ALMACEN' ,
  `map_almacen` TEXT NOT NULL DEFAULT NULL COMMENT 'ruta de la imagen del mapa, incluyendo todos los pisos, imagen grande' ,
  `des_almacen` VARCHAR(40) NULL COMMENT 'nombre del almacen' ,
  `sessionficha` VARCHAR(40) NULL DEFAULT NULL ,
  `sessionflag` VARCHAR(40) NULL DEFAULT NULL ,
  PRIMARY KEY (`cod_almacen`, `map_almacen`) )
COMMENT = 'de el almacen, cada mapa de cada piso.. ';


-- -----------------------------------------------------
-- Table `elalmacenwebdb`.`esk_movimiento`
-- -----------------------------------------------------
DROP TABLE IF EXISTS `elalmacenwebdb`.`esk_movimiento` ;

CREATE  TABLE IF NOT EXISTS `elalmacenwebdb`.`esk_movimiento` (
  `cod_movimiento` VARCHAR(40) NOT NULL COMMENT 'identificador del movimiento EL PRODUCTO' ,
  `fecha_llegada` VARCHAR(40) NULL DEFAULT NULL COMMENT 'fecha llegada del despacho' ,
  `cod_entidad` VARCHAR(40) NULL DEFAULT NULL COMMENT 'entidad que recibe este despacho' ,
  `cod_despacho` VARCHAR(40) NULL DEFAULT NULL COMMENT 'codigo del despacho recibido' ,
  `cod_medio` VARCHAR(40) NULL DEFAULT NULL COMMENT 'identificacion del medio, el objeto referenciado incluira placa etc chofer...' ,
  `estado` VARCHAR(40) NULL DEFAULT 'EMITIDO' COMMENT 'EMITIDO|TRANSITO|RECIBIDO|INVALIDO' ,
  `resultado` VARCHAR(40) NULL DEFAULT NULL COMMENT 'COMPLETO|INCOMPLETO' ,
  `sessionflag` VARCHAR(40) NULL COMMENT 'YYYYMMDDhhmmss.entidad.username de este registro' ,
  PRIMARY KEY (`cod_movimiento`) )
COMMENT = 'relacion de movimientos, diminutovos de flecha';


-- -----------------------------------------------------
-- Table `elalmacenwebdb`.`esk_despacho`
-- -----------------------------------------------------
DROP TABLE IF EXISTS `elalmacenwebdb`.`esk_despacho` ;

CREATE  TABLE IF NOT EXISTS `elalmacenwebdb`.`esk_despacho` (
  `cod_despacho` VARCHAR(40) NOT NULL COMMENT 'DESYYYYMMDDhhmmss : identificador del despacho realizado' ,
  `cod_ejecuta` VARCHAR(40) NOT NULL COMMENT 'sello donde se corre el sistema y estos datos' ,
  `fecha` VARCHAR(40) NULL DEFAULT NULL COMMENT 'fecha que se coloco en el camion y se fue' ,
  `estado` VARCHAR(40) NULL DEFAULT 'JAULA' COMMENT 'JAULA|TRANSITO|RECIBIDO' ,
  `cod_orden` VARCHAR(40) NULL DEFAULT NULL COMMENT 'cuando se completa se asocia el pedido correspondiente' ,
  `cod_medio` VARCHAR(40) NULL DEFAULT NULL COMMENT 'identificacion del medio y quien responsable del transporte' ,
  `tipo_despacho` VARCHAR(40) NULL DEFAULT 'INTERNO' COMMENT 'TRASLADO|INTERNO|EXTERNO' ,
  `cod_facturacion` VARCHAR(40) NULL DEFAULT NULL COMMENT 'codigo que usara el facturador para asociarse al sistema y el despacho' ,
  `sessionficha` VARCHAR(40) NULL DEFAULT NULL COMMENT 'YYYYMMDDhhmmss . entidad . intranet quien creo y el responsable de este despacho' ,
  `sessionflag` VARCHAR(40) NULL DEFAULT NULL COMMENT 'YYYYMMDDhhmmss . entidad . intranet quien altero o ultimo en tocar este despacho' ,
  PRIMARY KEY (`cod_despacho`, `cod_ejecuta`) )
COMMENT = 'despacho cabecera: definicion de documento para transito y r';


-- -----------------------------------------------------
-- Table `elalmacenwebdb`.`esk_despacho_producto`
-- -----------------------------------------------------
DROP TABLE IF EXISTS `elalmacenwebdb`.`esk_despacho_producto` ;

CREATE  TABLE IF NOT EXISTS `elalmacenwebdb`.`esk_despacho_producto` (
  `cod_despacho` VARCHAR(40) NOT NULL DEFAULT NULL COMMENT 'LOTEYYYYMMDDhhmmss id contenido del despacho' ,
  `cod_producto` VARCHAR(40) NOT NULL DEFAULT NULL COMMENT 'los productos de este despacho' ,
  `can_producto` DECIMAL(40,2) NULL DEFAULT NULL COMMENT 'cantidad de este producto en el despacho relacionado' ,
  `cod_bulto` VARCHAR(40) NULL DEFAULT NULL COMMENT 'identificador del bulto en donde se coloca producto' ,
  `mon_precio` DECIMAL(40,2) NULL DEFAULT NULL ,
  `sessionficha` VARCHAR(40) NULL DEFAULT NULL COMMENT 'YYYYMMDDhhmmss . entidad . intranet quien puso este producto en el despacho' ,
  PRIMARY KEY (`cod_despacho`, `cod_producto`) )
COMMENT = 'despacho detalle : que productos son los que se despachan de';


-- -----------------------------------------------------
-- Table `elalmacenwebdb`.`esk_orden`
-- -----------------------------------------------------
DROP TABLE IF EXISTS `elalmacenwebdb`.`esk_orden` ;

CREATE  TABLE IF NOT EXISTS `elalmacenwebdb`.`esk_orden` (
  `cod_orden` VARCHAR(40) NOT NULL COMMENT 'ORDYYYYMMDDhhmmss : identificador de la orden' ,
  `cod_ejecuta` VARCHAR(40) NOT NULL COMMENT 'sello donde se corre el sistema y estos datos' ,
  `fecha` VARCHAR(40) NULL DEFAULT NULL COMMENT 'YYYYMMDD fecha limite ejecutese al despacho la fecha creacion esta en sessionficha' ,
  `estado` VARCHAR(40) NULL DEFAULT 'PICKING' COMMENT 'PICKING|PREDESPACHO|CERRADO|ANULADO' ,
  `cod_origen` VARCHAR(40) NULL DEFAULT NULL COMMENT 'entidad origen sello heredado de pedido y opcion alterarse' ,
  `cod_destino` VARCHAR(40) NULL DEFAULT NULL COMMENT 'entidad destino sello hereddo de pedido y opcion alterarse' ,
  `cod_juridico` VARCHAR(40) NULL DEFAULT NULL COMMENT 'proveedor que se desea despache, si la entidad origen tiene se toma de alli' ,
  `cod_pedido` VARCHAR(40) NULL DEFAULT NULL COMMENT 'codigo pedido asociado que origino la orden y su picking' ,
  `sessionficha` VARCHAR(40) NULL DEFAULT NULL COMMENT 'YYYYMMDDhhmmss . entidad . intranet quien creo esta orden' ,
  `sessionflag` VARCHAR(40) NULL DEFAULT NULL COMMENT 'YYYYMMDDhhmmss . entidad . intranet quien altero esta orden' ,
  PRIMARY KEY (`cod_orden`, `cod_ejecuta`) )
COMMENT = 'orden maestro: orden para despachar el pedido y hacer el pic';


-- -----------------------------------------------------
-- Table `elalmacenwebdb`.`esk_orden_producto`
-- -----------------------------------------------------
DROP TABLE IF EXISTS `elalmacenwebdb`.`esk_orden_producto` ;

CREATE  TABLE IF NOT EXISTS `elalmacenwebdb`.`esk_orden_producto` (
  `cod_orden` VARCHAR(40) NOT NULL COMMENT 'LOTEYYYYMMDDhhmmss : id contenido de la orden' ,
  `cod_producto` VARCHAR(40) NOT NULL COMMENT 'cada codigo de producto de esta orden por contenido' ,
  `can_producto` DECIMAL(40,2) NOT NULL COMMENT 'cantidad de este producto de esta orden por contenido' ,
  `cod_bulto` VARCHAR(40) NULL DEFAULT NULL COMMENT 'identificador del bulto en donde se coloca agrupados los producto' ,
  `mon_precio` DECIMAL(40,2) NULL DEFAULT NULL ,
  `sessionficha` VARCHAR(40) NULL DEFAULT NULL COMMENT 'YYYYMMDDhhmmss . entidad . intranet quien puso este producto en la orden' ,
  PRIMARY KEY (`cod_orden`, `cod_producto`) )
COMMENT = 'orden detalle: productos contenidos en la orden especifica';


-- -----------------------------------------------------
-- Table `elalmacenwebdb`.`esk_pedido_producto`
-- -----------------------------------------------------
DROP TABLE IF EXISTS `elalmacenwebdb`.`esk_pedido_producto` ;

CREATE  TABLE IF NOT EXISTS `elalmacenwebdb`.`esk_pedido_producto` (
  `cod_pedido` VARCHAR(40) NOT NULL COMMENT 'LOTEYYYYMMDDhhmmss : id contenido de la solicitud' ,
  `cod_producto` VARCHAR(40) NOT NULL DEFAULT NULL COMMENT 'el productos de este pedido relacionado' ,
  `can_producto` DECIMAL(40,2) NULL DEFAULT NULL COMMENT 'cantidad de este producto' ,
  `cod_unidad` VARCHAR(40) NULL DEFAULT NULL COMMENT 'unidad de cantidad de este producto' ,
  `mon_precio` DECIMAL(40,2) NULL DEFAULT NULL COMMENT 'precio unitario de este producto la unidad es caracteristica del producto en si' ,
  `sessionficha` VARCHAR(40) NULL DEFAULT NULL COMMENT 'YYYYMMDDhhmmss . entidad . intranet quien puso este producto en el pedido' ,
  PRIMARY KEY (`cod_producto`, `cod_pedido`) )
COMMENT = 'pedido detalle : productos contenidos en el pedido especific';


-- -----------------------------------------------------
-- Table `elalmacenwebdb`.`esk_pedido`
-- -----------------------------------------------------
DROP TABLE IF EXISTS `elalmacenwebdb`.`esk_pedido` ;

CREATE  TABLE IF NOT EXISTS `elalmacenwebdb`.`esk_pedido` (
  `cod_pedido` VARCHAR(40) NOT NULL COMMENT 'PEDYYYYMMDDhhmmss : identificador del pedido en nuestro sistema' ,
  `cod_ejecuta` VARCHAR(40) NOT NULL COMMENT 'sello donde se corre el sistema y estos datos' ,
  `fecha` VARCHAR(40) NULL DEFAULT NULL COMMENT 'YYYYMMDD fecha limite ejecutese AL galpon la fecha creacion esta en sessionficha' ,
  `estado` VARCHAR(40) NULL DEFAULT 'ABIERTO' COMMENT 'ABIERTO|CERRADO' ,
  `cod_origen` VARCHAR(40) NULL DEFAULT NULL COMMENT 'entidad origen sello que emita o despache a quien se pide' ,
  `cod_destino` VARCHAR(40) NULL DEFAULT NULL COMMENT 'la enttidad destino o sello a donde se pide' ,
  `cod_juridico` VARCHAR(40) NULL DEFAULT NULL COMMENT 'proveedor que se desea despache, si la entidad origen tiene se toma de alli' ,
  `cod_reserv` VARCHAR(40) NULL DEFAULT NULL COMMENT 'codigo si el pedido es de un sistema externo' ,
  `sessionficha` VARCHAR(40) NULL DEFAULT NULL COMMENT 'YYYYMMDDhhmmss . entidad . intranet quien creo o importo este pedido' ,
  `sessionflag` VARCHAR(40) NULL DEFAULT NULL COMMENT 'YYYYMMDDhhmmss . entidad . intranet quien altero esta solicitud' ,
  PRIMARY KEY (`cod_pedido`, `cod_ejecuta`) )
COMMENT = 'solicitud maestra: tabla de referencia para llevar pedidos b';



SET SQL_MODE=@OLD_SQL_MODE;
SET FOREIGN_KEY_CHECKS=@OLD_FOREIGN_KEY_CHECKS;
SET UNIQUE_CHECKS=@OLD_UNIQUE_CHECKS;

-- -----------------------------------------------------
-- Data for table `elalmacenwebdb`.`esk_usuario`
-- -----------------------------------------------------
START TRANSACTION;
USE `elalmacenwebdb`;
INSERT INTO `elalmacenwebdb`.`esk_usuario` (`ficha`, `username`, `userclave`, `cod_nivel`, `cod_app`, `ses_cookie`, `ses_ip`, `sessionuser`, `sessionlast`, `sessionflag`, `sessionficha`) VALUES ('99999990', 'admin', 'c4ca4238a0b923820dcc509a6f75849b', '1', NULL, NULL, NULL, NULL, NULL, NULL, NULL);

COMMIT;
