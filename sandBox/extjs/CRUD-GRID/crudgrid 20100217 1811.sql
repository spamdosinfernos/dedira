-- MySQL Administrator dump 1.4
--
-- ------------------------------------------------------
-- Server version	5.1.43-community


/*!40101 SET @OLD_CHARACTER_SET_CLIENT=@@CHARACTER_SET_CLIENT */;
/*!40101 SET @OLD_CHARACTER_SET_RESULTS=@@CHARACTER_SET_RESULTS */;
/*!40101 SET @OLD_COLLATION_CONNECTION=@@COLLATION_CONNECTION */;
/*!40101 SET NAMES utf8 */;

/*!40014 SET @OLD_UNIQUE_CHECKS=@@UNIQUE_CHECKS, UNIQUE_CHECKS=0 */;
/*!40014 SET @OLD_FOREIGN_KEY_CHECKS=@@FOREIGN_KEY_CHECKS, FOREIGN_KEY_CHECKS=0 */;
/*!40101 SET @OLD_SQL_MODE=@@SQL_MODE, SQL_MODE='NO_AUTO_VALUE_ON_ZERO,MYSQL323' */;


--
-- Create schema crudgrid
--

CREATE DATABASE IF NOT EXISTS crudgrid;
USE crudgrid;

--
-- Definition of table `categorias`
--

DROP TABLE IF EXISTS `categorias`;
CREATE TABLE `categorias` (
  `cat_id` int(10) unsigned NOT NULL AUTO_INCREMENT,
  `cat_descricao` varchar(45) NOT NULL,
  PRIMARY KEY (`cat_id`)
) TYPE=InnoDB AUTO_INCREMENT=3;

--
-- Dumping data for table `categorias`
--

/*!40000 ALTER TABLE `categorias` DISABLE KEYS */;
INSERT INTO `categorias` (`cat_id`,`cat_descricao`) VALUES 
 (1,'Usuário'),
 (2,'Administrador');
/*!40000 ALTER TABLE `categorias` ENABLE KEYS */;


--
-- Definition of table `usuarios`
--

DROP TABLE IF EXISTS `usuarios`;
CREATE TABLE `usuarios` (
  `usu_login` varchar(20) NOT NULL,
  `usu_nome` varchar(45) NOT NULL,
  `usu_senha` varchar(20) NOT NULL,
  `usu_email` varchar(45) DEFAULT NULL,
  `usu_data_nascimento` datetime NOT NULL,
  `cat_id` int(10) unsigned NOT NULL,
  PRIMARY KEY (`usu_login`),
  KEY `FK_usuarios_cat_id` (`cat_id`),
  CONSTRAINT `FK_usuarios_cat_id` FOREIGN KEY (`cat_id`) REFERENCES `categorias` (`cat_id`) ON DELETE NO ACTION ON UPDATE CASCADE
) TYPE=InnoDB;

--
-- Dumping data for table `usuarios`
--

/*!40000 ALTER TABLE `usuarios` DISABLE KEYS */;
INSERT INTO `usuarios` (`usu_login`,`usu_nome`,`usu_senha`,`usu_email`,`usu_data_nascimento`,`cat_id`) VALUES 
 ('usu1','Usuario 1','usu1','','2010-02-16 00:00:00',1),
 ('usu2','Usuario 2','usu2','usu2@gmail.com','2010-02-16 00:00:00',1),
 ('usu3','Usuario 3','usu3','','2010-02-16 00:00:00',1),
 ('usu4','Usuario 4','usu4','','2010-02-16 00:00:00',2),
 ('usu5','Usuario 5','usu5','','2010-02-16 00:00:00',1),
 ('usu6','Usuario 6','usu6','','2010-02-16 00:00:00',1),
 ('usu7','Usuario 7','usu7','','2010-02-16 00:00:00',1);
/*!40000 ALTER TABLE `usuarios` ENABLE KEYS */;




/*!40101 SET SQL_MODE=@OLD_SQL_MODE */;
/*!40014 SET FOREIGN_KEY_CHECKS=@OLD_FOREIGN_KEY_CHECKS */;
/*!40014 SET UNIQUE_CHECKS=@OLD_UNIQUE_CHECKS */;
/*!40101 SET CHARACTER_SET_CLIENT=@OLD_CHARACTER_SET_CLIENT */;
/*!40101 SET CHARACTER_SET_RESULTS=@OLD_CHARACTER_SET_RESULTS */;
/*!40101 SET COLLATION_CONNECTION=@OLD_COLLATION_CONNECTION */;
/*!40101 SET CHARACTER_SET_CLIENT=@OLD_CHARACTER_SET_CLIENT */;
