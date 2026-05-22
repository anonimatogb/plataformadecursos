-- Patch para suporte ao cadastro de módulos (tabela modulo)
-- Execute no phpMyAdmin para criar a tabela 'modulo' se não existir.

CREATE TABLE IF NOT EXISTS `modulo` (
  `id` int(11) NOT NULL AUTO_INCREMENT,
  `titulo` varchar(255) NOT NULL,
  `cursos_id` int(11) NOT NULL,
  `video` varchar(255) NOT NULL,
  PRIMARY KEY (`id`),
  KEY `fk_modulo_cursos` (`cursos_id`),
  CONSTRAINT `fk_modulo_cursos`
    FOREIGN KEY (`cursos_id`) REFERENCES `cursos` (`id`)
    ON DELETE NO ACTION ON UPDATE NO ACTION
) ENGINE=InnoDB DEFAULT CHARSET=utf8;

