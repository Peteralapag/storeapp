/*
Navicat MySQL Data Transfer

Source Server         : prj
Source Server Version : 50505
Source Host           : 192.168.1.4:3306
Source Database       : storeapp_data

Target Server Type    : MYSQL
Target Server Version : 50505
File Encoding         : 65001

Date: 2025-06-30 10:15:43
*/

SET FOREIGN_KEY_CHECKS=0;

-- ----------------------------
-- Table structure for `store_brrr_category`
-- ----------------------------
DROP TABLE IF EXISTS `store_brrr_category`;
CREATE TABLE `store_brrr_category` (
  `id` int(11) NOT NULL AUTO_INCREMENT,
  `category_name` varchar(150) NOT NULL,
  `default_ratio` decimal(50,3) DEFAULT NULL,
  `default_ratio_new` decimal(50,3) DEFAULT NULL,
  `date_effective` date DEFAULT NULL,
  `is_active` tinyint(1) DEFAULT 1,
  `type` int(1) DEFAULT 1,
  PRIMARY KEY (`id`)
) ENGINE=InnoDB AUTO_INCREMENT=21 DEFAULT CHARSET=utf8mb4;

-- ----------------------------
-- Records of store_brrr_category
-- ----------------------------
INSERT INTO `store_brrr_category` VALUES ('1', 'Cost od Non Conformance (CONCS)', '1.000', null, null, '1', '1');
INSERT INTO `store_brrr_category` VALUES ('2', 'Packaging', '2.000', null, null, '1', '1');
INSERT INTO `store_brrr_category` VALUES ('3', 'LPG', '2.000', null, null, '1', '1');
INSERT INTO `store_brrr_category` VALUES ('4', 'Light and Water', '1.500', null, null, '1', '1');
INSERT INTO `store_brrr_category` VALUES ('5', 'Repair and Maintenance', '0.200', null, null, '1', '2');
INSERT INTO `store_brrr_category` VALUES ('6', 'Office Supplies', '0.100', null, null, '1', '1');
INSERT INTO `store_brrr_category` VALUES ('7', 'Janitorial Supplies', '0.080', null, null, '1', '1');
INSERT INTO `store_brrr_category` VALUES ('8', 'Communacation/Internet', '0.020', null, null, '1', '2');
INSERT INTO `store_brrr_category` VALUES ('9', 'Bakery Tools and Utensils', '0.001', null, null, '1', '2');
INSERT INTO `store_brrr_category` VALUES ('10', 'Representation', '0.010', null, null, '1', '2');
INSERT INTO `store_brrr_category` VALUES ('11', 'Drinking Water', '0.002', null, null, '1', '2');
INSERT INTO `store_brrr_category` VALUES ('12', 'Meal Allowance', '0.002', null, null, '1', '2');
INSERT INTO `store_brrr_category` VALUES ('13', 'Meeting and Trainings', '0.001', null, null, '1', '2');
INSERT INTO `store_brrr_category` VALUES ('14', 'Transportation', '0.130', null, null, '1', '2');
INSERT INTO `store_brrr_category` VALUES ('15', 'Remittence/Charges', '0.010', null, null, '1', '2');
INSERT INTO `store_brrr_category` VALUES ('16', 'Fuel (Logistcs)', '0.200', null, null, '1', '1');
INSERT INTO `store_brrr_category` VALUES ('17', 'Marketing Collaterals', '1.000', null, null, '1', '2');
INSERT INTO `store_brrr_category` VALUES ('18', 'Freight and Handling', '0.070', null, null, '1', '2');
INSERT INTO `store_brrr_category` VALUES ('19', 'Hotel and Accomodation', '0.001', null, null, '1', '1');
INSERT INTO `store_brrr_category` VALUES ('20', 'Penalties and Charges', '0.020', null, null, '1', '1');
