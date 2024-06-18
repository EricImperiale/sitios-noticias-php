SET @OLD_UNIQUE_CHECKS=@@UNIQUE_CHECKS, UNIQUE_CHECKS=0;
SET @OLD_FOREIGN_KEY_CHECKS=@@FOREIGN_KEY_CHECKS, FOREIGN_KEY_CHECKS=0;
SET @OLD_SQL_MODE=@@SQL_MODE, SQL_MODE='TRADITIONAL,ALLOW_INVALID_DATES';

CREATE SCHEMA IF NOT EXISTS `prog2_2022_2_m` DEFAULT CHARACTER SET utf8mb4;
USE `prog2_2022_2_m`;

CREATE TABLE IF NOT EXISTS `noticias` (
  `noticia_id` INT(10) UNSIGNED NOT NULL AUTO_INCREMENT,
  `usuario_fk` INT(10) UNSIGNED NOT NULL,
  `estado_publicacion_fk` TINYINT(3) UNSIGNED NOT NULL,
  `fecha_publicacion` DATETIME NOT NULL,
  `titulo` VARCHAR(100) NOT NULL,
  `sinopsis` VARCHAR(255) NOT NULL,
  `texto` TEXT NOT NULL,
  `imagen` VARCHAR(255) NULL DEFAULT NULL,
  `imagen_descripcion` VARCHAR(255) NULL DEFAULT NULL,
  PRIMARY KEY (`noticia_id`),
  INDEX `fecha_publicacion_idx` (`fecha_publicacion` ASC),
  INDEX `fk_noticias_usuarios_idx` (`usuario_fk` ASC),
  INDEX `fk_noticias_estados_publicacion1_idx` (`estado_publicacion_fk` ASC),
  CONSTRAINT `fk_noticias_usuarios`
    FOREIGN KEY (`usuario_fk`)
    REFERENCES `usuarios` (`usuario_id`)
    ON DELETE NO ACTION
    ON UPDATE NO ACTION,
  CONSTRAINT `fk_noticias_estados_publicacion1`
    FOREIGN KEY (`estado_publicacion_fk`)
    REFERENCES `estados_publicacion` (`estado_publicacion_id`)
    ON DELETE NO ACTION
    ON UPDATE NO ACTION)
ENGINE = InnoDB
DEFAULT CHARACTER SET = utf8mb4;

CREATE TABLE IF NOT EXISTS `usuarios` (
  `usuario_id` INT(10) UNSIGNED NOT NULL AUTO_INCREMENT,
  `rol_fk` TINYINT(3) UNSIGNED NOT NULL,
  `email` VARCHAR(255) NOT NULL,
  `password` VARCHAR(255) NOT NULL,
  `username` VARCHAR(60) NULL DEFAULT NULL,
  PRIMARY KEY (`usuario_id`),
  UNIQUE INDEX `email_UNIQUE` (`email` ASC),
  INDEX `fk_usuarios_roles1_idx` (`rol_fk` ASC),
  CONSTRAINT `fk_usuarios_roles1`
    FOREIGN KEY (`rol_fk`)
    REFERENCES `roles` (`rol_id`)
    ON DELETE NO ACTION
    ON UPDATE NO ACTION)
ENGINE = InnoDB
DEFAULT CHARACTER SET = utf8mb4;

CREATE TABLE IF NOT EXISTS `etiquetas` (
  `etiqueta_id` TINYINT(3) UNSIGNED NOT NULL AUTO_INCREMENT,
  `nombre` VARCHAR(25) NOT NULL,
  PRIMARY KEY (`etiqueta_id`))
ENGINE = InnoDB
DEFAULT CHARACTER SET = utf8mb4;

CREATE TABLE IF NOT EXISTS `noticias_tienen_etiquetas` (
  `noticia_fk` INT(10) UNSIGNED NULL DEFAULT NULL,
  `etiqueta_fk` TINYINT(3) UNSIGNED NULL DEFAULT NULL,
  PRIMARY KEY (`noticia_fk`, `etiqueta_fk`),
  INDEX `fk_noticias_has_etiquetas_etiquetas1_idx` (`etiqueta_fk` ASC),
  INDEX `fk_noticias_has_etiquetas_noticias1_idx` (`noticia_fk` ASC),
  CONSTRAINT `fk_noticias_has_etiquetas_noticias1`
    FOREIGN KEY (`noticia_fk`)
    REFERENCES `noticias` (`noticia_id`)
    ON DELETE NO ACTION
    ON UPDATE NO ACTION,
  CONSTRAINT `fk_noticias_has_etiquetas_etiquetas1`
    FOREIGN KEY (`etiqueta_fk`)
    REFERENCES `etiquetas` (`etiqueta_id`)
    ON DELETE NO ACTION
    ON UPDATE NO ACTION)
ENGINE = InnoDB
DEFAULT CHARACTER SET = utf8mb4;

CREATE TABLE IF NOT EXISTS `estados_publicacion` (
  `estado_publicacion_id` TINYINT(3) UNSIGNED NOT NULL AUTO_INCREMENT,
  `nombre` VARCHAR(20) NOT NULL,
  PRIMARY KEY (`estado_publicacion_id`))
ENGINE = InnoDB
DEFAULT CHARACTER SET = utf8mb4;

CREATE TABLE IF NOT EXISTS `roles` (
  `rol_id` TINYINT(3) UNSIGNED NOT NULL AUTO_INCREMENT,
  `nombre` VARCHAR(20) NOT NULL,
  PRIMARY KEY (`rol_id`))
ENGINE = InnoDB
DEFAULT CHARACTER SET = utf8mb4;

CREATE TABLE IF NOT EXISTS `restablecer_passwords` (
  `usuario_id` INT(10) UNSIGNED NOT NULL AUTO_INCREMENT,
  `token` CHAR(64) NOT NULL,
  `fecha_expiracion` DATETIME NOT NULL,
  PRIMARY KEY (`usuario_id`),
  CONSTRAINT `fk_restablecer_passwords_usuarios`
    FOREIGN KEY (`usuario_id`)
    REFERENCES `usuarios` (`usuario_id`)
    ON DELETE NO ACTION
    ON UPDATE NO ACTION)
ENGINE = InnoDB
DEFAULT CHARACTER SET = utf8mb4;

CREATE TABLE IF NOT EXISTS `comentarios` (
  `comentario_id` INT(10) UNSIGNED NOT NULL AUTO_INCREMENT,
  `usuario_fk` INT(10) UNSIGNED NOT NULL,
  `comentario` VARCHAR(255) NOT NULL,
  `fecha_publicacion` DATETIME NOT NULL,
  PRIMARY KEY (`comentario_id`),
  INDEX `fk_comentarios_usuarios1_idx` (`usuario_fk` ASC),
  CONSTRAINT `fk_comentarios_usuarios1`
    FOREIGN KEY (`usuario_fk`)
    REFERENCES `usuarios` (`usuario_id`)
    ON DELETE NO ACTION
    ON UPDATE NO ACTION)
ENGINE = InnoDB
DEFAULT CHARACTER SET = utf8mb4;

CREATE TABLE IF NOT EXISTS `noticias_tienen_comentarios` (
  `noticia_fk` INT(10) UNSIGNED NOT NULL,
  `comentario_fk` INT(10) UNSIGNED NOT NULL,
  PRIMARY KEY (`noticia_fk`, `comentario_fk`),
  INDEX `fk_noticias_has_comentarios_comentarios1_idx` (`comentario_fk` ASC),
  INDEX `fk_noticias_has_comentarios_noticias1_idx` (`noticia_fk` ASC),
  CONSTRAINT `fk_noticias_has_comentarios_noticias1`
    FOREIGN KEY (`noticia_fk`)
    REFERENCES `noticias` (`noticia_id`)
    ON DELETE NO ACTION
    ON UPDATE NO ACTION,
  CONSTRAINT `fk_noticias_has_comentarios_comentarios1`
    FOREIGN KEY (`comentario_fk`)
    REFERENCES `comentarios` (`comentario_id`)
    ON DELETE NO ACTION
    ON UPDATE NO ACTION)
ENGINE = InnoDB
DEFAULT CHARACTER SET = utf8mb4;

ALTER TABLE `prog2_2022_2_m`.`usuarios` 
CHANGE COLUMN `imagen` `avatar` VARCHAR(255) NULL DEFAULT NULL ,
CHANGE COLUMN `imagen_descripcion` `avatar_descripcion` VARCHAR(255) NULL DEFAULT NULL ;

-- Insert initial data for roles
INSERT INTO `roles` (`rol_id`, `nombre`) VALUES
(1, 'Administrador'),
(2, 'Usuario');

SELECT * FROM usuarios; 

-- Insert initial data for usuarios
INSERT INTO `usuarios` (`usuario_id`, `rol_fk`, `email`, `password`, `username`) VALUES
(1, 1, 'admin@saraza.com', '$2y$10$gmMnmbsch5UTTj1Q3QiNw.PjTpkweDiWKModFsBJrQBFTN8PT.pSq', 'Administrador'),
(2, 1, 'sara@za.com', '$2y$10$gmMnmbsch5UTTj1Q3QiNw.PjTpkweDiWKModFsBJrQBFTN8PT.pSq', 'Saraza'),
(3, 2, 'usu@rio.com', '$2y$10$gmMnmbsch5UTTj1Q3QiNw.PjTpkweDiWKModFsBJrQBFTN8PT.pSq', 'Pepe Trueno');

-- Insert initial data for estados_publicacion
INSERT INTO `estados_publicacion` (`estado_publicacion_id`, `nombre`) VALUES
(1, 'Borrador'),
(2, 'Publicado');

-- Insert initial data for etiquetas
INSERT INTO `etiquetas` (`etiqueta_id`, `nombre`) VALUES
(1, 'Temporada Regular'),
(2, 'Playoff'),
(3, 'Partidos'),
(4, 'Lesiones'),
(5, 'Récords'),
(6, 'Draft'),
(7, 'San Antonio Spurs'),
(8, 'Denver Nuggets'),
(9, 'Toronto Raptors'),
(10, 'Houston Rockets');

-- Insert initial data for noticias
INSERT INTO `noticias` (`noticia_id`, `usuario_fk`, `estado_publicacion_fk`, `fecha_publicacion`, `titulo`, `sinopsis`, `texto`, `imagen`, `imagen_descripcion`) VALUES
(1, 2, 2, '2022-01-02 11:23:51', 'Ginóbili sigue rompiendo récords', 'Emanuel \'Manu\' Ginóbili viene rompiendo algunos récords tanto de su equipo como de la liga.', 'Lorem ipsum dolor sit amet...', 'manu.jpg', 'Manu Ginóbili en medio de un partido'),
(3, 2, 2, '2022-01-03 12:02:23', 'Houston Rockets lidera la conferencia', 'De la mano de James Harden, los Rockets se apuntan como candidatos para ganar los playoff.', 'Lorem ipsum dolor sit amet...', 'rockets-logo.jpg', 'Logo de los Houston Rockets'),
(4, 1, 2, '2022-01-03 19:53:19', 'Toronto Raptors queda primero en el Este', 'Los Raptors de Lowry y DeRozan se quedan con el primer lugar de su conferencia.', 'Lorem ipsum dolor sit amet...', 'raptors-logo.jpg', 'Logo de los Toronto Raptors'),
(5, 1, 2, '2022-01-03 22:01:47', 'Denver se queda corto por un partido', 'Quedó a una victoria y media de clasificar a los playoff.', 'Lorem ipsum dolor sit amet...', 'nuggets-logo.jpg', 'Logo de los Denver Nuggets');

-- Insert initial data for noticias_tienen_etiquetas
INSERT INTO `noticias_tienen_etiquetas` (`noticia_fk`, `etiqueta_fk`) VALUES
(1, 1),
(1, 2),
(1, 5),
(1, 7),
(3, 1),
(3, 2),
(3, 10),
(4, 1),
(4, 9),
(5, 1),
(5, 2),
(5, 8);


-- Insert initial data for comentarios
INSERT INTO `comentarios` (`comentario_id`, `usuario_fk`, `comentario`, `fecha_publicacion`) VALUES
(1, 3, '¡Gran noticia! Ginóbili es una leyenda viviente.', '2022-01-02 12:00:00'),
(2, 4, '¡Increíble lo que está logrando! Manu para presidente.', '2022-01-02 12:05:00');

-- Insert initial data for noticias_tienen_comentarios
INSERT INTO `noticias_tienen_comentarios` (`noticia_fk`, `comentario_fk`) VALUES
(1, 1),
(1, 2);

select * from USUARIOS;

SET SQL_MODE=@OLD_SQL_MODE;
SET FOREIGN_KEY_CHECKS=@OLD_FOREIGN_KEY_CHECKS;
SET UNIQUE_CHECKS=@OLD_UNIQUE_CHECKS;
