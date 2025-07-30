/*
Navicat MySQL Data Transfer

Source Server         : prj
Source Server Version : 50505
Source Host           : 192.168.1.4:3306
Source Database       : storeapp_data

Target Server Type    : MYSQL
Target Server Version : 50505
File Encoding         : 65001

Date: 2025-06-30 10:16:14
*/

SET FOREIGN_KEY_CHECKS=0;

-- ----------------------------
-- Table structure for `store_brrr_overhead_data`
-- ----------------------------
DROP TABLE IF EXISTS `store_brrr_overhead_data`;
CREATE TABLE `store_brrr_overhead_data` (
  `id` bigint(255) NOT NULL AUTO_INCREMENT,
  `branch` varchar(100) DEFAULT NULL,
  `report_date` varchar(50) DEFAULT NULL,
  `idcode` varchar(10) DEFAULT NULL,
  `acctname` varchar(100) DEFAULT NULL,
  `position` varchar(100) DEFAULT NULL,
  `baker` int(1) DEFAULT 0,
  `selling` int(1) DEFAULT 0,
  `created_date` varchar(50) DEFAULT '',
  `created_by` varchar(100) DEFAULT NULL,
  `status` int(1) DEFAULT 0,
  PRIMARY KEY (`id`)
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4;

-- ----------------------------
-- Records of store_brrr_overhead_data
-- ----------------------------
