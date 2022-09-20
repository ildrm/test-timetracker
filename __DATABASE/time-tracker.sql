/*
 Navicat Premium Data Transfer

 Source Server         : localhost
 Source Server Type    : MySQL
 Source Server Version : 100421
 Source Host           : localhost:3306
 Source Schema         : time-tracker

 Target Server Type    : MySQL
 Target Server Version : 100421
 File Encoding         : 65001

 Date: 03/01/2022 10:19:01
*/

SET NAMES utf8mb4;
SET FOREIGN_KEY_CHECKS = 0;

-- ----------------------------
-- Table structure for projects
-- ----------------------------
DROP TABLE IF EXISTS `projects`;
CREATE TABLE `projects`  (
  `id` bigint UNSIGNED NOT NULL AUTO_INCREMENT,
  `name` varchar(255) CHARACTER SET utf8mb4 COLLATE utf8mb4_general_ci NULL DEFAULT NULL,
  `user_id` bigint NULL DEFAULT NULL,
  `created` datetime NULL DEFAULT NULL,
  `modified` datetime NULL DEFAULT NULL,
  `status` tinyint NULL DEFAULT NULL,
  PRIMARY KEY (`id`) USING BTREE,
  UNIQUE INDEX `u_user_project`(`name`, `user_id`, `status`) USING BTREE
) ENGINE = InnoDB AUTO_INCREMENT = 40 CHARACTER SET = utf8mb4 COLLATE = utf8mb4_general_ci ROW_FORMAT = Dynamic;

-- ----------------------------
-- Records of projects
-- ----------------------------
INSERT INTO `projects` VALUES (1, 'test_project', 1, '2022-01-01 14:51:20', '2022-01-01 14:51:20', 1);
INSERT INTO `projects` VALUES (2, 'My Test Project 2', 1, '2022-01-01 14:51:20', '2022-01-01 14:51:20', 1);
INSERT INTO `projects` VALUES (10, 'project1', 1, '2022-01-02 15:47:32', '2022-01-02 15:47:32', 1);
INSERT INTO `projects` VALUES (11, 'project2', 1, '2022-01-02 15:47:32', '2022-01-02 15:47:32', 1);
INSERT INTO `projects` VALUES (12, 'project3', 1, '2022-01-02 15:47:32', '2022-01-02 15:47:32', 1);

-- ----------------------------
-- Table structure for times
-- ----------------------------
DROP TABLE IF EXISTS `times`;
CREATE TABLE `times`  (
  `id` bigint UNSIGNED NOT NULL AUTO_INCREMENT,
  `start_time` datetime NULL DEFAULT NULL,
  `end_time` datetime NULL DEFAULT NULL,
  `user_id` int NULL DEFAULT NULL,
  `project_id` int NULL DEFAULT NULL,
  `note` tinytext CHARACTER SET utf8mb4 COLLATE utf8mb4_general_ci NULL,
  `status` tinyint NULL DEFAULT NULL,
  PRIMARY KEY (`id`) USING BTREE,
  UNIQUE INDEX `u_records`(`start_time`, `end_time`, `user_id`, `project_id`, `status`) USING BTREE
) ENGINE = InnoDB AUTO_INCREMENT = 6 CHARACTER SET = utf8mb4 COLLATE = utf8mb4_general_ci ROW_FORMAT = Dynamic;

-- ----------------------------
-- Records of times
-- ----------------------------
INSERT INTO `times` VALUES (1, '2022-01-02 08:00:00', '2022-01-02 16:00:00', 2, 1, NULL, NULL);
INSERT INTO `times` VALUES (2, '2022-01-02 12:00:00', '2022-01-02 14:00:00', 1, 1, NULL, NULL);
INSERT INTO `times` VALUES (3, '2022-01-02 04:11:00', '2022-01-02 06:13:00', 1, 10, NULL, 1);
INSERT INTO `times` VALUES (4, '2022-01-02 06:11:00', '2022-01-02 08:13:00', 1, 11, NULL, 1);
INSERT INTO `times` VALUES (5, '2022-01-02 10:11:00', '2022-01-02 16:13:00', 1, 12, NULL, 1);

-- ----------------------------
-- Table structure for users
-- ----------------------------
DROP TABLE IF EXISTS `users`;
CREATE TABLE `users`  (
  `id` bigint UNSIGNED NOT NULL AUTO_INCREMENT,
  `email` varchar(255) CHARACTER SET utf8mb4 COLLATE utf8mb4_general_ci NULL DEFAULT NULL,
  `first_name` varchar(255) CHARACTER SET utf8mb4 COLLATE utf8mb4_general_ci NULL DEFAULT NULL,
  `last_name` varchar(255) CHARACTER SET utf8mb4 COLLATE utf8mb4_general_ci NULL DEFAULT NULL,
  `password` varchar(255) CHARACTER SET utf8mb4 COLLATE utf8mb4_general_ci NULL DEFAULT NULL,
  `created` datetime NULL DEFAULT NULL,
  `modified` datetime NULL DEFAULT NULL,
  `status` tinyint NULL DEFAULT NULL,
  `active_token` varchar(255) CHARACTER SET utf8mb4 COLLATE utf8mb4_general_ci NULL DEFAULT NULL,
  `last_login` datetime NULL DEFAULT NULL,
  PRIMARY KEY (`id`) USING BTREE,
  UNIQUE INDEX `u_email`(`email`) USING BTREE
) ENGINE = InnoDB AUTO_INCREMENT = 2 CHARACTER SET = utf8mb4 COLLATE = utf8mb4_general_ci ROW_FORMAT = Dynamic;

-- ----------------------------
-- Records of users
-- ----------------------------
INSERT INTO `users` VALUES (1, 'test@test.com', 'Masoud', 'Ilderemi Lotf-Abad', '40bd001563085fc35165329ea1ff5c5ecbdbbeef', '2022-01-01 14:50:02', '2022-01-01 14:50:02', 1, '', '2022-01-03 10:03:52');

SET FOREIGN_KEY_CHECKS = 1;
