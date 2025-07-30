/*
Navicat MySQL Data Transfer

Source Server         : prj
Source Server Version : 50505
Source Host           : 192.168.1.4:3306
Source Database       : storeapp_data

Target Server Type    : MYSQL
Target Server Version : 50505
File Encoding         : 65001

Date: 2025-06-30 10:16:41
*/

SET FOREIGN_KEY_CHECKS=0;

-- ----------------------------
-- Table structure for `store_brrr_summary_data`
-- ----------------------------
DROP TABLE IF EXISTS `store_brrr_summary_data`;
CREATE TABLE `store_brrr_summary_data` (
  `id` bigint(255) NOT NULL AUTO_INCREMENT,
  `branch` varchar(100) DEFAULT NULL,
  `report_date` varchar(50) DEFAULT NULL,
  `report_month` date DEFAULT NULL,
  `baker_headcount` int(11) DEFAULT NULL,
  `selling_headcount` int(11) DEFAULT NULL,
  `total_headcount` int(11) DEFAULT NULL,
  `category_json` text DEFAULT NULL,
  `amount_json` text DEFAULT NULL,
  `actual_amount_total` decimal(12,2) DEFAULT NULL,
  `created_date` varchar(50) DEFAULT '',
  `created_by` varchar(100) DEFAULT NULL,
  PRIMARY KEY (`id`)
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4;

-- ----------------------------
-- Records of store_brrr_summary_data
-- ----------------------------
