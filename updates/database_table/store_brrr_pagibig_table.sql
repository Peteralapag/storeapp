/*
Navicat MySQL Data Transfer

Source Server         : prj
Source Server Version : 50505
Source Host           : 192.168.1.4:3306
Source Database       : storeapp_data

Target Server Type    : MYSQL
Target Server Version : 50505
File Encoding         : 65001

Date: 2025-06-30 10:16:22
*/

SET FOREIGN_KEY_CHECKS=0;

-- ----------------------------
-- Table structure for `store_brrr_pagibig_table`
-- ----------------------------
DROP TABLE IF EXISTS `store_brrr_pagibig_table`;
CREATE TABLE `store_brrr_pagibig_table` (
  `id` int(11) NOT NULL AUTO_INCREMENT,
  `salary_from` double(11,2) DEFAULT NULL,
  `salary_to` double(11,2) DEFAULT NULL,
  `employee_share` double(11,2) DEFAULT NULL,
  `employer_share` double(11,2) DEFAULT NULL,
  `total_contribution` double(11,2) DEFAULT NULL,
  `created_date` varchar(20) DEFAULT NULL,
  `created_by` varchar(100) DEFAULT NULL,
  PRIMARY KEY (`id`)
) ENGINE=InnoDB AUTO_INCREMENT=4 DEFAULT CHARSET=utf8mb4;

-- ----------------------------
-- Records of store_brrr_pagibig_table
-- ----------------------------
INSERT INTO `store_brrr_pagibig_table` VALUES ('1', '0.00', '1500.00', '15.00', '30.00', '45.00', null, null);
INSERT INTO `store_brrr_pagibig_table` VALUES ('2', '1500.01', '4999.99', '100.00', '100.00', '200.00', null, null);
INSERT INTO `store_brrr_pagibig_table` VALUES ('3', '5000.00', null, '100.00', '100.00', '200.00', null, null);
