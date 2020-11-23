/*
 Navicat Premium Data Transfer

 Source Server         : localhost
 Source Server Type    : MySQL
 Source Server Version : 100416
 Source Host           : localhost:3306
 Source Schema         : gamescore

 Target Server Type    : MySQL
 Target Server Version : 100416
 File Encoding         : 65001

 Date: 23/11/2020 20:03:26
*/

SET NAMES utf8mb4;
SET FOREIGN_KEY_CHECKS = 0;

-- ----------------------------
-- Table structure for fichajuego
-- ----------------------------
DROP TABLE IF EXISTS `fichajuego`;
CREATE TABLE `fichajuego`  (
  `id` int(10) UNSIGNED NOT NULL AUTO_INCREMENT,
  `idJuego` int(255) UNSIGNED NOT NULL,
  `idPlataforma` tinyint(255) UNSIGNED NOT NULL,
  `idGenero` tinyint(255) UNSIGNED NOT NULL,
  `anyoLanzamiento` int(4) UNSIGNED NOT NULL,
  `nota` decimal(2, 1) NOT NULL,
  PRIMARY KEY (`id`) USING BTREE,
  UNIQUE INDEX `K_juegoPlataforma`(`idJuego`, `idPlataforma`) USING BTREE,
  INDEX `FK_plataforma`(`idPlataforma`) USING BTREE,
  INDEX `FK_genero`(`idGenero`) USING BTREE,
  CONSTRAINT `FK_genero` FOREIGN KEY (`idGenero`) REFERENCES `generos` (`id`) ON DELETE CASCADE ON UPDATE CASCADE,
  CONSTRAINT `FK_juego` FOREIGN KEY (`idJuego`) REFERENCES `juegos` (`id`) ON DELETE CASCADE ON UPDATE CASCADE,
  CONSTRAINT `FK_plataforma` FOREIGN KEY (`idPlataforma`) REFERENCES `plataformas` (`id`) ON DELETE CASCADE ON UPDATE CASCADE
) ENGINE = InnoDB AUTO_INCREMENT = 13 CHARACTER SET = utf8mb4 COLLATE = utf8mb4_general_ci ROW_FORMAT = Dynamic;

-- ----------------------------
-- Table structure for generos
-- ----------------------------
DROP TABLE IF EXISTS `generos`;
CREATE TABLE `generos`  (
  `id` tinyint(3) UNSIGNED NOT NULL AUTO_INCREMENT,
  `genero` varchar(100) CHARACTER SET utf8mb4 COLLATE utf8mb4_general_ci NOT NULL,
  PRIMARY KEY (`id`) USING BTREE,
  UNIQUE INDEX `K_genero`(`genero`) USING BTREE
) ENGINE = InnoDB AUTO_INCREMENT = 17 CHARACTER SET = utf8mb4 COLLATE = utf8mb4_general_ci ROW_FORMAT = Dynamic;

-- ----------------------------
-- Table structure for juegos
-- ----------------------------
DROP TABLE IF EXISTS `juegos`;
CREATE TABLE `juegos`  (
  `id` int(10) UNSIGNED NOT NULL AUTO_INCREMENT,
  `nombre` varchar(100) CHARACTER SET utf8mb4 COLLATE utf8mb4_general_ci NOT NULL,
  PRIMARY KEY (`id`) USING BTREE,
  UNIQUE INDEX `K_nombre`(`nombre`) USING BTREE
) ENGINE = InnoDB AUTO_INCREMENT = 18 CHARACTER SET = utf8mb4 COLLATE = utf8mb4_general_ci ROW_FORMAT = Dynamic;

-- ----------------------------
-- Table structure for plataformas
-- ----------------------------
DROP TABLE IF EXISTS `plataformas`;
CREATE TABLE `plataformas`  (
  `id` tinyint(10) UNSIGNED NOT NULL AUTO_INCREMENT,
  `plataforma` varchar(100) CHARACTER SET utf8mb4 COLLATE utf8mb4_general_ci NOT NULL,
  PRIMARY KEY (`id`) USING BTREE,
  UNIQUE INDEX `K_plataforma`(`plataforma`) USING BTREE
) ENGINE = InnoDB AUTO_INCREMENT = 11 CHARACTER SET = utf8mb4 COLLATE = utf8mb4_general_ci ROW_FORMAT = Dynamic;

-- ----------------------------
-- Table structure for usuariojuego
-- ----------------------------
DROP TABLE IF EXISTS `usuariojuego`;
CREATE TABLE `usuariojuego`  (
  `id` int(10) UNSIGNED NOT NULL AUTO_INCREMENT,
  `idFichaJuego` int(255) UNSIGNED NOT NULL,
  `usuario` varchar(45) CHARACTER SET utf8mb4 COLLATE utf8mb4_general_ci NOT NULL,
  `notaUsuario` tinyint(255) UNSIGNED NULL DEFAULT NULL,
  PRIMARY KEY (`id`) USING BTREE,
  UNIQUE INDEX `K_usuarioJuego`(`idFichaJuego`, `usuario`) USING BTREE,
  INDEX `FK_usuarioJuego`(`usuario`) USING BTREE,
  CONSTRAINT `FK_fichaJuego` FOREIGN KEY (`idFichaJuego`) REFERENCES `fichajuego` (`id`) ON DELETE CASCADE ON UPDATE CASCADE,
  CONSTRAINT `FK_usuarioJuego` FOREIGN KEY (`usuario`) REFERENCES `usuarios` (`usuario`) ON DELETE CASCADE ON UPDATE CASCADE
) ENGINE = InnoDB AUTO_INCREMENT = 9 CHARACTER SET = utf8mb4 COLLATE = utf8mb4_general_ci ROW_FORMAT = Dynamic;

-- ----------------------------
-- Table structure for usuarios
-- ----------------------------
DROP TABLE IF EXISTS `usuarios`;
CREATE TABLE `usuarios`  (
  `id` int(11) UNSIGNED NOT NULL AUTO_INCREMENT,
  `usuario` varchar(45) CHARACTER SET utf8mb4 COLLATE utf8mb4_general_ci NOT NULL DEFAULT '',
  `password` varchar(255) CHARACTER SET utf8mb4 COLLATE utf8mb4_general_ci NOT NULL DEFAULT '',
  `email` varchar(45) CHARACTER SET utf8mb4 COLLATE utf8mb4_general_ci NOT NULL DEFAULT '',
  `alta` datetime(0) NOT NULL DEFAULT current_timestamp(),
  `nivel` varchar(45) CHARACTER SET utf8mb4 COLLATE utf8mb4_general_ci NOT NULL DEFAULT '',
  `estado` tinyint(1) NOT NULL DEFAULT 0,
  PRIMARY KEY (`id`) USING BTREE,
  UNIQUE INDEX `H_usuario`(`usuario`) USING BTREE
) ENGINE = InnoDB AUTO_INCREMENT = 21 CHARACTER SET = utf8mb4 COLLATE = utf8mb4_general_ci ROW_FORMAT = Dynamic;

SET FOREIGN_KEY_CHECKS = 1;
