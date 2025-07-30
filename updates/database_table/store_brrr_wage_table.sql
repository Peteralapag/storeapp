/*
Navicat MySQL Data Transfer

Source Server         : prj
Source Server Version : 50505
Source Host           : 192.168.1.4:3306
Source Database       : storeapp_data

Target Server Type    : MYSQL
Target Server Version : 50505
File Encoding         : 65001

Date: 2025-06-30 10:16:48
*/

SET FOREIGN_KEY_CHECKS=0;

-- ----------------------------
-- Table structure for `store_brrr_wage_table`
-- ----------------------------
DROP TABLE IF EXISTS `store_brrr_wage_table`;
CREATE TABLE `store_brrr_wage_table` (
  `id` int(11) NOT NULL AUTO_INCREMENT,
  `status` int(1) DEFAULT 1,
  `min_wage` decimal(50,4) DEFAULT 0.0000,
  `monthly_wage` decimal(50,4) DEFAULT NULL,
  PRIMARY KEY (`id`)
) ENGINE=InnoDB AUTO_INCREMENT=2 DEFAULT CHARSET=utf8mb4;

-- ----------------------------
-- Records of store_brrr_wage_table
-- ----------------------------
INSERT INTO `store_brrr_wage_table` VALUES ('1', '1', '510.0000', '13260.0000');
