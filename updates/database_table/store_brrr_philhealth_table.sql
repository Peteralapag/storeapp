/*
Navicat MySQL Data Transfer

Source Server         : prj
Source Server Version : 50505
Source Host           : 192.168.1.4:3306
Source Database       : storeapp_data

Target Server Type    : MYSQL
Target Server Version : 50505
File Encoding         : 65001

Date: 2025-06-30 10:16:29
*/

SET FOREIGN_KEY_CHECKS=0;

-- ----------------------------
-- Table structure for `store_brrr_philhealth_table`
-- ----------------------------
DROP TABLE IF EXISTS `store_brrr_philhealth_table`;
CREATE TABLE `store_brrr_philhealth_table` (
  `id` int(11) NOT NULL AUTO_INCREMENT,
  `salary_from` double(11,2) DEFAULT NULL,
  `salary_to` double(11,2) DEFAULT NULL,
  `employee_share` double(11,2) DEFAULT NULL,
  `employer_share` double(11,2) DEFAULT NULL,
  `total_contribution` double(11,2) DEFAULT NULL,
  `created_date` varchar(20) DEFAULT NULL,
  `created_by` varchar(100) DEFAULT NULL,
  PRIMARY KEY (`id`)
) ENGINE=InnoDB AUTO_INCREMENT=7 DEFAULT CHARSET=utf8mb4;

-- ----------------------------
-- Records of store_brrr_philhealth_table
-- ----------------------------
INSERT INTO `store_brrr_philhealth_table` VALUES ('1', '10000.00', '14999.99', '312.50', '312.50', '625.00', null, null);
INSERT INTO `store_brrr_philhealth_table` VALUES ('2', '15000.00', '19999.99', '437.50', '437.50', '875.00', null, null);
INSERT INTO `store_brrr_philhealth_table` VALUES ('3', '10000.00', '14999.99', '312.50', '312.50', '625.00', null, null);
INSERT INTO `store_brrr_philhealth_table` VALUES ('4', '15000.00', '19999.99', '437.50', '437.50', '875.00', null, null);
INSERT INTO `store_brrr_philhealth_table` VALUES ('5', '95000.00', '99999.99', '2437.50', '2437.50', '4875.00', null, null);
INSERT INTO `store_brrr_philhealth_table` VALUES ('6', '100000.00', null, '2500.00', '2500.00', '5000.00', null, null);
