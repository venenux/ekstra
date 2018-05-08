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
  `userclave` VARCHAR(40) NULL DEFAULT NULL ,
  `cod_nivel` VARCHAR(40) NULL DEFAULT NULL COMMENT 'separado por comas cantidad de poder de acceso' ,
  `cod_entidad` VARCHAR(40) NULL DEFAULT NULL COMMENT 'separado por comas, almacenes donde puede operar' ,
  `ses_cookie` VARCHAR(400) NULL DEFAULT NULL COMMENT 'nombre de cookie del session codeigniter' ,
  `ses_ip` VARCHAR(40) NULL DEFAULT NULL COMMENT 'ip que tiene logeado , usada para identificacion' ,
  `sessionuser` VARCHAR(40) NULL DEFAULT NULL COMMENT 'YYYYMMDDhhmmss.entidad session actual' ,
  `sessionlast` VARCHAR(40) NULL DEFAULT NULL COMMENT 'YYYYMMDDhhmmss.entidad ultima session que paso' ,
  `sessionflag` VARCHAR(40) NULL DEFAULT NULL COMMENT 'YYYYMMDDhhmmss.entidad.username quien modifico' ,
  `sessionficha` VARCHAR(40) NULL DEFAULT NULL COMMENT 'YYYYMMDDhhmmss.entidad.username quien creo este registro' ,
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
  `cod_modulo` VARCHAR(40) NOT NULL COMMENT 'YYYYMMDDhhmmss id unico de cada directorop' ,
  `directorio` VARCHAR(40) NOT NULL DEFAULT '/' COMMENT 'modulo a que puede acceder, subdirectoro controller' ,
  `controlers` VARCHAR(40) NULL DEFAULT NULL COMMENT 'separado comas, controladores que puede operar' ,
  `funciones` VARCHAR(40) NULL DEFAULT NULL COMMENT 'sep comas, metodos a los que puede ejecutar' ,
  `sessionflag` VARCHAR(40) NULL DEFAULT NULL COMMENT 'YYYYMMDDhhmmss . entidad . username - quien altero este regisro y cuando' ,
  `sessionficha` VARCHAR(40) NULL DEFAULT NULL COMMENT 'YYYYMMDDhhmmss . entidad . username - quien creo este regisro y cuando' ,
  PRIMARY KEY (`cod_modulo`) )
ENGINE = InnoDB
COMMENT = 'directorio en controller y que controllers puede ejecutar el' /* comment truncated */;


-- -----------------------------------------------------
-- Table `elalmacenwebdb`.`esk_modulo_usuario`
-- -----------------------------------------------------
DROP TABLE IF EXISTS `elalmacenwebdb`.`esk_modulo_usuario` ;

CREATE  TABLE IF NOT EXISTS `elalmacenwebdb`.`esk_modulo_usuario` (
  `cod_indicador` VARCHAR(40) NOT NULL COMMENT 'YYYYMMDDhhmmss' ,
  `username` VARCHAR(40) NULL COMMENT 'usuario relacionado a modulo operar' ,
  `cod_modulo` VARCHAR(40) NULL ,
  `sessionflag` VARCHAR(40) NULL DEFAULT NULL COMMENT 'YYYYMMDDhhmmss . entidad . username - quien altero este regisro y cuando' ,
  PRIMARY KEY (`cod_indicador`) )
ENGINE = InnoDB
COMMENT = 'que usuario puede usar que modulo o subdirectorio de control' /* comment truncated */;


-- -----------------------------------------------------
-- Table `elalmacenwebdb`.`esk_productos`
-- -----------------------------------------------------
DROP TABLE IF EXISTS `elalmacenwebdb`.`esk_productos` ;

CREATE  TABLE IF NOT EXISTS `elalmacenwebdb`.`esk_productos` (
  `cod_producto` VARCHAR(40) NOT NULL COMMENT 'YYYYMMDDhhmmss id unico de cada producto' ,
  `cod_codigo` VARCHAR(40) NOT NULL COMMENT 'codigo del producto definido por usuario' ,
  `des_producto` VARCHAR(40) NOT NULL COMMENT 'descripcion del producto' ,
  `sessionflag` VARCHAR(40) NULL DEFAULT NULL COMMENT 'YYYYMMDDhhmmss.entidad.username quien modifico' ,
  `sessionficha` VARCHAR(40) NULL DEFAULT NULL COMMENT 'YYYYMMDDhhmmss.entidad.username quien lo creo' ,
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
-- Table `elalmacenwebdb`.`esk_almacen_ubicacion`
-- -----------------------------------------------------
DROP TABLE IF EXISTS `elalmacenwebdb`.`esk_almacen_ubicacion` ;

CREATE  TABLE IF NOT EXISTS `elalmacenwebdb`.`esk_almacen_ubicacion` (
  `cod_ubicacion` VARCHAR(40) NOT NULL COMMENT 'YYYYMMDDhhmmss id unico de cada posicion no importa de que almacen' ,
  `cod_posicion` VARCHAR(40) NOT NULL DEFAULT '000-000-A-9999999999999-000-ALM' COMMENT 'alm-pas-lad-mod-alt-are dado no es fiable, es la suma separada por \"-\" de valores de columnas \'000-000-A-9999999999999-000-ALM\'' ,
  `estado_ubicacion` VARCHAR(40) NULL DEFAULT 'ACTIVA' COMMENT 'ACTIVA,INACTIVA - inactiva es que se elimino o cambio, pero no se puede borrar nunca para que se conserve historico en productos' ,
  `cod_entidad` VARCHAR(40) NULL DEFAULT '000' COMMENT 'en que cod_almacen esta esta posicion' ,
  `cod_pasillo` VARCHAR(40) NULL DEFAULT '000' COMMENT 'identificacion del pasillo' ,
  `cod_lado` VARCHAR(40) NULL DEFAULT 'A' COMMENT 'identificacion o lado de el pasillo' ,
  `cod_modulo` VARCHAR(40) NULL DEFAULT '9999999999999' COMMENT 'identificacion del andamio o mueble o modulo, 13 digitos' ,
  `cod_altura` VARCHAR(40) NULL DEFAULT '000' COMMENT 'en que nivel del modulo' ,
  `cod_area` VARCHAR(40) NULL DEFAULT 'ALM' COMMENT 'NOR,SUR,EST,OES,ALM - hacia que zona del almacen, ALM si no se define' ,
  `cor_mapa_x` VARCHAR(40) NULL DEFAULT NULL COMMENT 'del mapa visto arriba eje x' ,
  `cor_mapa_y` VARCHAR(40) NULL DEFAULT NULL COMMENT 'del mapa visto arriba eje y' ,
  `cor_mapa_z` VARCHAR(40) NULL DEFAULT NULL COMMENT 'en mayor casos es el piso' ,
  `ind_reparar` VARCHAR(40) NULL DEFAULT 'NO' COMMENT 'SI|NO - indicador si hay que corregir esta posicion por datos incorrectos' ,
  `sessionflag` VARCHAR(40) NULL DEFAULT NULL COMMENT 'YYYYMMDDhhmmss.entidad.username quien altero' ,
  `sessionficha` VARCHAR(40) NULL DEFAULT NULL COMMENT 'YYYYMMDDhhmmss.entidad.username quien lo creo' ,
  PRIMARY KEY (`cod_ubicacion`) )
ENGINE = InnoDB
COMMENT = 'las pociciones o ubicaciones del alamacen';


-- -----------------------------------------------------
-- Table `elalmacenwebdb`.`esk_almacen_mapa`
-- -----------------------------------------------------
DROP TABLE IF EXISTS `elalmacenwebdb`.`esk_almacen_mapa` ;

CREATE  TABLE IF NOT EXISTS `elalmacenwebdb`.`esk_almacen_mapa` (
  `cod_entidad` VARCHAR(40) NOT NULL COMMENT 'YYYYMMDDhhmmss id unico' ,
  `cod_almacen` VARCHAR(40) NULL DEFAULT NULL COMMENT 'clave unica de 3 digitos alfanumerica' ,
  `map_almacen` TEXT NULL COMMENT 'ruta de la imagen del mapa, incluyendo todos los pisos, imagen grande' ,
  `des_almacen` VARCHAR(40) NULL DEFAULT 'sin nombre' COMMENT 'nombre del almacen' ,
  `sessionflag` VARCHAR(40) NULL DEFAULT NULL COMMENT 'YYYYMMDDhhmmss . entidad . username - quien altero este regisro y cuando' ,
  `sessionficha` VARCHAR(40) NULL DEFAULT NULL COMMENT 'YYYYMMDDhhmmss . entidad . username - quien creo este regisro y cuando' ,
  PRIMARY KEY (`cod_entidad`) )
COMMENT = 'de el almacen, cada mapa de cada piso.. ';


-- -----------------------------------------------------
-- Table `elalmacenwebdb`.`esk_movimiento`
-- -----------------------------------------------------
DROP TABLE IF EXISTS `elalmacenwebdb`.`esk_movimiento` ;

CREATE  TABLE IF NOT EXISTS `elalmacenwebdb`.`esk_movimiento` (
  `cod_movimiento` VARCHAR(40) NOT NULL COMMENT 'YYYYMMDDhhmmss id unico subrgate key' ,
  `cod_producto` VARCHAR(40) NOT NULL COMMENT 'producto al que se le hizo movimiento' ,
  `cod_correlativo` VARCHAR(40) NOT NULL COMMENT 'id identificador del movimiento para ver por ser humano' ,
  `cod_tipo_movimiento` VARCHAR(40) NULL DEFAULT NULL COMMENT 'CORRECION|AJUSTE|MUDANZA|OTRO' ,
  `cod_signo_movimiento` VARCHAR(40) NULL DEFAULT NULL COMMENT 'P|N positivo o negativo' ,
  `cod_ubicacion_origen` VARCHAR(40) NULL DEFAULT NULL COMMENT 'id unico del origen ya que incluye el almacen' ,
  `cod_ubicacion_destino` VARCHAR(40) NULL DEFAULT NULL COMMENT 'id unico de el destino, ya que incluye el almacen' ,
  `fecha_inicia` VARCHAR(40) NULL DEFAULT NULL COMMENT 'YYYYMMDD fecha que se comenzo mover o alterar' ,
  `fecha_ejecuto` VARCHAR(40) NULL DEFAULT NULL COMMENT 'YYYYMMDD fecha que se termino mover' ,
  `cod_medio` VARCHAR(40) NULL DEFAULT NULL COMMENT 'CARRO|AVION|OBRERO|NINGUNO' ,
  `estado` VARCHAR(40) NULL DEFAULT 'EMITIDO' COMMENT 'EMITIDO|TRANSITO|RECIBIDO' ,
  `resultado` VARCHAR(40) NULL DEFAULT 'INCOMPLETO' COMMENT 'COMPLETO|INCOMPLETO' ,
  `sessionflag` VARCHAR(40) NULL DEFAULT NULL COMMENT 'YYYYMMDDhhmmss . entidad . username - ultimo que altero este regisro y cuando' ,
  PRIMARY KEY (`cod_movimiento`) )
COMMENT = 'relacion de movimientos, diminutovos de flecha';


-- -----------------------------------------------------
-- Table `elalmacenwebdb`.`esk_despacho`
-- -----------------------------------------------------
DROP TABLE IF EXISTS `elalmacenwebdb`.`esk_despacho` ;

CREATE  TABLE IF NOT EXISTS `elalmacenwebdb`.`esk_despacho` (
  `cod_despacho` VARCHAR(40) NOT NULL COMMENT 'YYYYMMDDhhmmss : identificador del despacho llave subrogada' ,
  `cod_ejecuta` VARCHAR(40) NOT NULL COMMENT 'codigo de entidad o almacen donde se creo esto' ,
  `num_despacho` VARCHAR(40) NULL DEFAULT '9999999999999' COMMENT 'este es el numero de despacho humano a mostrar' ,
  `fecha` VARCHAR(40) NULL DEFAULT NULL COMMENT 'fecha que se coloco en el camion y se fue' ,
  `estado` VARCHAR(40) NULL DEFAULT 'JAULA' COMMENT 'JAULA|TRANSITO|RECIBIDO' ,
  `cod_orden` VARCHAR(40) NULL DEFAULT NULL COMMENT 'cuando se completa se asocia el pedido correspondiente' ,
  `cod_medio` VARCHAR(40) NULL DEFAULT NULL COMMENT 'identificacion del medio y quien responsable del transporte' ,
  `tipo_despacho` VARCHAR(40) NULL DEFAULT 'INTERNO' COMMENT 'TRASLADO|INTERNO|EXTERNO' ,
  `cod_facturacion` VARCHAR(40) NULL DEFAULT NULL COMMENT 'codigo que usara el facturador para asociarse al sistema y el despacho' ,
  `sessionflag` VARCHAR(40) NULL DEFAULT NULL COMMENT 'YYYYMMDDhhmmss . entidad . username - quien altero este regisro y cuando' ,
  `sessionficha` VARCHAR(40) NULL DEFAULT NULL COMMENT 'YYYYMMDDhhmmss . entidad . username - quien creo este regisro y cuando' ,
  PRIMARY KEY (`cod_despacho`) )
COMMENT = 'despacho maestro: definicion de documento para transito y re' /* comment truncated */;


-- -----------------------------------------------------
-- Table `elalmacenwebdb`.`esk_despacho_producto`
-- -----------------------------------------------------
DROP TABLE IF EXISTS `elalmacenwebdb`.`esk_despacho_producto` ;

CREATE  TABLE IF NOT EXISTS `elalmacenwebdb`.`esk_despacho_producto` (
  `cod_despacho` VARCHAR(40) NOT NULL COMMENT 'YYYYMMDDhhmmss : id de que despacho es este detalle' ,
  `cod_producto` VARCHAR(40) NOT NULL COMMENT 'cada codigo producto del despacho' ,
  `can_producto` DECIMAL(40,2) NULL DEFAULT NULL COMMENT 'cantidad de este producto en el despacho relacionado' ,
  `cod_bulto` VARCHAR(40) NULL DEFAULT NULL COMMENT 'identificador del bulto o caja en donde se coloca producto' ,
  `sessionficha` VARCHAR(40) NULL DEFAULT NULL COMMENT 'YYYYMMDDhhmmss . entidad . username - quien creo este regisro y cuando' ,
  PRIMARY KEY (`cod_despacho`, `cod_producto`) )
COMMENT = 'despacho detalle : que productos son los que se despacharon';


-- -----------------------------------------------------
-- Table `elalmacenwebdb`.`esk_orden`
-- -----------------------------------------------------
DROP TABLE IF EXISTS `elalmacenwebdb`.`esk_orden` ;

CREATE  TABLE IF NOT EXISTS `elalmacenwebdb`.`esk_orden` (
  `cod_orden` VARCHAR(40) NOT NULL COMMENT 'YYYYMMDDhhmmss : identificador de la orden unica llave subrogada' ,
  `cod_ejecuta` VARCHAR(40) NOT NULL COMMENT 'codigo de entidad o almacen donde se creo esto' ,
  `num_orden` VARCHAR(40) NULL DEFAULT '9999999999999' COMMENT 'este es el numero de orden humano a mostrar' ,
  `fecha` VARCHAR(40) NULL DEFAULT NULL COMMENT 'YYYYMMDD fecha limite ejecutese al despacho' ,
  `estado` VARCHAR(40) NULL DEFAULT 'INICIADO' COMMENT 'ABIERTO|PROCESO|CERRADO' ,
  `cod_origen` VARCHAR(40) NULL DEFAULT NULL COMMENT 'entidad origen sello heredado de pedido y opcion alterarse' ,
  `cod_destino` VARCHAR(40) NULL DEFAULT NULL COMMENT 'entidad destino sello hereddo de pedido y opcion alterarse' ,
  `cod_juridico` VARCHAR(40) NULL DEFAULT NULL COMMENT 'proveedor que se desea despache, si la entidad origen tiene se toma de alli' ,
  `cod_pedido` VARCHAR(40) NULL DEFAULT NULL COMMENT 'codigo pedido asociado que origino la orden y su picking' ,
  `sessionflag` VARCHAR(40) NULL DEFAULT NULL COMMENT 'YYYYMMDDhhmmss . entidad . username - quien altero este regisro y cuando' ,
  `sessionficha` VARCHAR(40) NULL DEFAULT NULL COMMENT 'YYYYMMDDhhmmss . entidad . username - quien creo este regisro y cuando' ,
  PRIMARY KEY (`cod_orden`) )
COMMENT = 'orden maestro: orden para despachar el pedido realizado';


-- -----------------------------------------------------
-- Table `elalmacenwebdb`.`esk_orden_producto`
-- -----------------------------------------------------
DROP TABLE IF EXISTS `elalmacenwebdb`.`esk_orden_producto` ;

CREATE  TABLE IF NOT EXISTS `elalmacenwebdb`.`esk_orden_producto` (
  `cod_orden` VARCHAR(40) NOT NULL COMMENT 'YYYYMMDDhhmmss : id de que orden es este detalle' ,
  `cod_producto` VARCHAR(40) NOT NULL COMMENT 'cada codigo producto por orden' ,
  `can_producto` DECIMAL(40,2) NULL DEFAULT 0 COMMENT 'cantidad de este producto de esta orden por contenido' ,
  `cod_bulto` VARCHAR(40) NULL DEFAULT NULL COMMENT 'identificador del bulto en donde se coloca agrupados los producto' ,
  `sessionficha` VARCHAR(40) NULL DEFAULT NULL COMMENT 'YYYYMMDDhhmmss . entidad . username - quien creo este regisro y cuando' ,
  PRIMARY KEY (`cod_orden`, `cod_producto`) )
COMMENT = 'orden detalle: productos contenidos en la orden especifica';


-- -----------------------------------------------------
-- Table `elalmacenwebdb`.`esk_pedido_producto`
-- -----------------------------------------------------
DROP TABLE IF EXISTS `elalmacenwebdb`.`esk_pedido_producto` ;

CREATE  TABLE IF NOT EXISTS `elalmacenwebdb`.`esk_pedido_producto` (
  `cod_pedido` VARCHAR(40) NOT NULL COMMENT 'YYYYMMDDhhmmss : id de que pedido es este detalle' ,
  `cod_producto` VARCHAR(40) NOT NULL COMMENT 'cada codigo de producto por pedido' ,
  `can_producto` DECIMAL(40,2) NULL DEFAULT 0 COMMENT 'cantidad de este producto' ,
  `cod_unidad` VARCHAR(40) NULL DEFAULT NULL COMMENT 'unidad de cantidad de este producto' ,
  `sessionficha` VARCHAR(40) NULL DEFAULT NULL COMMENT 'YYYYMMDDhhmmss . entidad . username - quien creo este regisro y cuando' ,
  PRIMARY KEY (`cod_producto`, `cod_pedido`) )
COMMENT = 'pedido detalle : productos contenidos en el pedido especific' /* comment truncated */;


-- -----------------------------------------------------
-- Table `elalmacenwebdb`.`esk_pedido`
-- -----------------------------------------------------
DROP TABLE IF EXISTS `elalmacenwebdb`.`esk_pedido` ;

CREATE  TABLE IF NOT EXISTS `elalmacenwebdb`.`esk_pedido` (
  `cod_pedido` VARCHAR(40) NOT NULL COMMENT 'YYYYMMDDhhmmss : identificador del pedido unico llave subrogada' ,
  `cod_ejecuta` VARCHAR(40) NOT NULL COMMENT 'codigo de entidad o almacen donde se creo esto' ,
  `num_pedido` VARCHAR(40) NULL DEFAULT '9999999999999' COMMENT 'este es el numero de pedido humano a mostrar' ,
  `fecha` VARCHAR(40) NULL DEFAULT NULL COMMENT 'YYYYMMDD fecha limite ejecutese AL galpon la fecha creacion esta en sessionficha' ,
  `estado` VARCHAR(40) NULL DEFAULT 'ABIERTO' COMMENT 'ABIERTO|PROCESO|CERRADO' ,
  `cod_origen` VARCHAR(40) NULL DEFAULT NULL COMMENT 'entidad origen id o almacen sello que emita o despache a quien se pide' ,
  `cod_destino` VARCHAR(40) NULL DEFAULT NULL COMMENT 'la enttidad destino id almacen o sello a donde se pide' ,
  `cod_juridico` VARCHAR(40) NULL DEFAULT NULL COMMENT 'proveedor que se desea despache, si la entidad origen tiene se toma de alli' ,
  `cod_reserv` VARCHAR(40) NULL DEFAULT NULL COMMENT 'codigo si el pedido es de un sistema externo' ,
  `sessionflag` VARCHAR(40) NULL DEFAULT NULL COMMENT 'YYYYMMDDhhmmss . entidad . username - quien altero este regisro y cuando' ,
  `sessionficha` VARCHAR(40) NULL DEFAULT NULL COMMENT 'YYYYMMDDhhmmss . entidad . username - quien creo este regisro y cuando' ,
  PRIMARY KEY (`cod_pedido`) )
COMMENT = 'solicitud maestra: pedidos de productos para las ordenes';



SET SQL_MODE=@OLD_SQL_MODE;
SET FOREIGN_KEY_CHECKS=@OLD_FOREIGN_KEY_CHECKS;
SET UNIQUE_CHECKS=@OLD_UNIQUE_CHECKS;

-- -----------------------------------------------------
-- Data for table `elalmacenwebdb`.`esk_usuario`
-- -----------------------------------------------------
START TRANSACTION;
USE `elalmacenwebdb`;
INSERT INTO `elalmacenwebdb`.`esk_usuario` (`ficha`, `username`, `userclave`, `cod_nivel`, `cod_entidad`, `ses_cookie`, `ses_ip`, `sessionuser`, `sessionlast`, `sessionflag`, `sessionficha`) VALUES ('99999990', 'admin', 'c4ca4238a0b923820dcc509a6f75849b', '1', NULL, NULL, NULL, NULL, NULL, NULL, NULL);

COMMIT;

-- -----------------------------------------------------
-- Data for table `elalmacenwebdb`.`esk_productos`
-- -----------------------------------------------------
START TRANSACTION;
USE `elalmacenwebdb`;
INSERT INTO `elalmacenwebdb`.`esk_productos` (`cod_producto`, `cod_codigo`, `des_producto`, `sessionflag`, `sessionficha`) VALUES ('20180101000000', '0000000000', 'producto inicial dummy', NULL, NULL);

COMMIT;

-- -----------------------------------------------------
-- Data for table `elalmacenwebdb`.`esk_almacen_producto`
-- -----------------------------------------------------
START TRANSACTION;
USE `elalmacenwebdb`;
INSERT INTO `elalmacenwebdb`.`esk_almacen_producto` (`cod_ubicacion`, `cod_producto`, `can_producto`, `sessionflag`, `sessionficha`) VALUES ('20180101000002', '20180101000000', 4, NULL, NULL);
INSERT INTO `elalmacenwebdb`.`esk_almacen_producto` (`cod_ubicacion`, `cod_producto`, `can_producto`, `sessionflag`, `sessionficha`) VALUES ('20180401210000', '20180101000000', 3, NULL, NULL);

COMMIT;

-- -----------------------------------------------------
-- Data for table `elalmacenwebdb`.`esk_almacen_ubicacion`
-- -----------------------------------------------------
START TRANSACTION;
USE `elalmacenwebdb`;
INSERT INTO `elalmacenwebdb`.`esk_almacen_ubicacion` (`cod_ubicacion`, `cod_posicion`, `estado_ubicacion`, `cod_entidad`, `cod_pasillo`, `cod_lado`, `cod_modulo`, `cod_altura`, `cod_area`, `cor_mapa_x`, `cor_mapa_y`, `cor_mapa_z`, `ind_reparar`, `sessionflag`, `sessionficha`) VALUES ('20180101000002', '000-000-A-0000000000000-000-ALM', 'ACTIVA', '20180101010001', '000', 'A', '0000000000000', '000', 'ALM', NULL, NULL, NULL, 'NO', NULL, NULL);
INSERT INTO `elalmacenwebdb`.`esk_almacen_ubicacion` (`cod_ubicacion`, `cod_posicion`, `estado_ubicacion`, `cod_entidad`, `cod_pasillo`, `cod_lado`, `cod_modulo`, `cod_altura`, `cod_area`, `cor_mapa_x`, `cor_mapa_y`, `cor_mapa_z`, `ind_reparar`, `sessionflag`, `sessionficha`) VALUES ('20180401210000', '000-000-B-0000000000000-000-ALM', 'ACTIVA', '20180101010001', '000', 'B', '0000000000000', '000', 'ALM', NULL, NULL, NULL, NULL, NULL, NULL);

COMMIT;

-- -----------------------------------------------------
-- Data for table `elalmacenwebdb`.`esk_almacen_mapa`
-- -----------------------------------------------------
START TRANSACTION;
USE `elalmacenwebdb`;
INSERT INTO `elalmacenwebdb`.`esk_almacen_mapa` (`cod_entidad`, `cod_almacen`, `map_almacen`, `des_almacen`, `sessionflag`, `sessionficha`) VALUES ('20180101010001', '000', NULL, 'Almacen prueba 1', NULL, NULL);

COMMIT;
