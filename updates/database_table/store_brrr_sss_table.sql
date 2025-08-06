/*
Navicat MySQL Data Transfer

Source Server         : prj
Source Server Version : 50505
Source Host           : 192.168.1.4:3306
Source Database       : storeapp_data

Target Server Type    : MYSQL
Target Server Version : 50505
File Encoding         : 65001

Date: 2025-06-30 10:16:35
*/

SET FOREIGN_KEY_CHECKS=0;

-- ----------------------------
-- Table structure for `store_brrr_sss_table`
-- ----------------------------
DROP TABLE IF EXISTS `store_brrr_sss_table`;
CREATE TABLE `store_brrr_sss_table` (
  `id` int(11) NOT NULL AUTO_INCREMENT,
  `salary_from` double(11,2) DEFAULT NULL,
  `salary_to` double(11,2) DEFAULT NULL,
  `employee_share` double(11,2) DEFAULT NULL,
  `employer_share` double(11,2) DEFAULT NULL,
  `total_contribution` double(11,2) DEFAULT NULL,
  `created_date` varchar(20) DEFAULT NULL,
  `created_by` varchar(100) DEFAULT NULL,
  PRIMARY KEY (`id`)
) ENGINE=InnoDB AUTO_INCREMENT=32 DEFAULT CHARSET=utf8mb4;

-- ----------------------------
-- Records of store_brrr_sss_table
-- ----------------------------
INSERT INTO `store_brrr_sss_table` VALUES ('1', '5250.00', '5749.99', '250.00', '500.00', '750.00', null, null);
INSERT INTO `store_brrr_sss_table` VALUES ('2', '5750.00', '6249.99', '275.00', '550.00', '825.00', null, null);
INSERT INTO `store_brrr_sss_table` VALUES ('3', '6250.00', '6749.99', '300.00', '600.00', '900.00', null, null);
INSERT INTO `store_brrr_sss_table` VALUES ('4', '6750.00', '7249.99', '325.00', '650.00', '975.00', null, null);
INSERT INTO `store_brrr_sss_table` VALUES ('5', '7250.00', '7749.99', '350.00', '700.00', '1050.00', null, null);
INSERT INTO `store_brrr_sss_table` VALUES ('6', '7750.00', '8249.99', '375.00', '750.00', '1125.00', null, null);
INSERT INTO `store_brrr_sss_table` VALUES ('7', '8250.00', '8749.99', '400.00', '800.00', '1200.00', null, null);
INSERT INTO `store_brrr_sss_table` VALUES ('8', '8750.00', '9249.99', '425.00', '850.00', '1275.00', null, null);
INSERT INTO `store_brrr_sss_table` VALUES ('9', '9250.00', '9749.99', '450.00', '900.00', '1350.00', null, null);
INSERT INTO `store_brrr_sss_table` VALUES ('10', '9750.00', '10249.99', '475.00', '950.00', '1425.00', null, null);
INSERT INTO `store_brrr_sss_table` VALUES ('11', '10250.00', '10749.99', '500.00', '1000.00', '1500.00', null, null);
INSERT INTO `store_brrr_sss_table` VALUES ('12', '10750.00', '11249.99', '525.00', '1050.00', '1575.00', null, null);
INSERT INTO `store_brrr_sss_table` VALUES ('13', '11250.00', '11749.99', '550.00', '1100.00', '1650.00', null, null);
INSERT INTO `store_brrr_sss_table` VALUES ('14', '11750.00', '12249.99', '575.00', '1150.00', '1725.00', null, null);
INSERT INTO `store_brrr_sss_table` VALUES ('15', '12250.00', '12749.99', '600.00', '1200.00', '1800.00', null, null);
INSERT INTO `store_brrr_sss_table` VALUES ('16', '12750.00', '13249.99', '625.00', '1250.00', '1875.00', null, null);
INSERT INTO `store_brrr_sss_table` VALUES ('17', '13250.00', '13749.99', '650.00', '1300.00', '1950.00', null, null);
INSERT INTO `store_brrr_sss_table` VALUES ('18', '13750.00', '14249.99', '675.00', '1350.00', '2025.00', null, null);
INSERT INTO `store_brrr_sss_table` VALUES ('19', '14250.00', '14749.99', '700.00', '1400.00', '2100.00', null, null);
INSERT INTO `store_brrr_sss_table` VALUES ('20', '14750.00', '15249.99', '725.00', '1450.00', '2175.00', null, null);
INSERT INTO `store_brrr_sss_table` VALUES ('21', '15250.00', '15749.99', '750.00', '1500.00', '2250.00', null, null);
INSERT INTO `store_brrr_sss_table` VALUES ('22', '15750.00', '16249.99', '775.00', '1550.00', '2325.00', null, null);
INSERT INTO `store_brrr_sss_table` VALUES ('23', '16250.00', '16749.99', '800.00', '1600.00', '2400.00', null, null);
INSERT INTO `store_brrr_sss_table` VALUES ('24', '16750.00', '17249.99', '825.00', '1650.00', '2475.00', null, null);
INSERT INTO `store_brrr_sss_table` VALUES ('25', '17250.00', '17749.99', '850.00', '1700.00', '2550.00', null, null);
INSERT INTO `store_brrr_sss_table` VALUES ('26', '17750.00', '18249.99', '875.00', '1750.00', '2625.00', null, null);
INSERT INTO `store_brrr_sss_table` VALUES ('27', '18250.00', '18749.99', '900.00', '1800.00', '2700.00', null, null);
INSERT INTO `store_brrr_sss_table` VALUES ('28', '18750.00', '19249.99', '925.00', '1850.00', '2775.00', null, null);
INSERT INTO `store_brrr_sss_table` VALUES ('29', '19250.00', '19749.99', '950.00', '1900.00', '2850.00', null, null);
INSERT INTO `store_brrr_sss_table` VALUES ('30', '19750.00', '20249.99', '975.00', '1950.00', '2925.00', null, null);
INSERT INTO `store_brrr_sss_table` VALUES ('31', '20250.00', '20749.99', '1000.00', '2000.00', '3000.00', null, null);
