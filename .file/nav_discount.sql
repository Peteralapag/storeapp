/*
Navicat MySQL Data Transfer

Source Server         : PROJECT DATABASE
Source Server Version : 50738
Source Host           : 192.168.1.4:3306
Source Database       : storeapps

Target Server Type    : MYSQL
Target Server Version : 50738
File Encoding         : 65001

Date: 2023-02-08 09:31:40
*/

SET FOREIGN_KEY_CHECKS=0;

-- ----------------------------
-- Table structure for `store_discount_data`
-- ----------------------------
DROP TABLE IF EXISTS `store_discount_data`;
CREATE TABLE `store_discount_data` (
  `id` int(20) NOT NULL AUTO_INCREMENT,
  `branch` varchar(30) DEFAULT NULL,
  `report_date` date DEFAULT NULL,
  `shift` varchar(20) DEFAULT '',
  `supervisor` varchar(123) DEFAULT NULL,
  `discount` double(11,2) DEFAULT '0.00',
  `date_created` timestamp NULL DEFAULT '0000-00-00 00:00:00',
  `date_updated` datetime DEFAULT NULL,
  `updated_by` varchar(50) DEFAULT NULL,
  `posted` varchar(6) DEFAULT 'No',
  `status` varchar(6) DEFAULT 'Open',
  `audit_mode` varchar(1) DEFAULT '0',
  PRIMARY KEY (`id`)
) ENGINE=InnoDB AUTO_INCREMENT=6 DEFAULT CHARSET=latin1;

-- ----------------------------
-- Records of store_discount_data
-- ----------------------------
INSERT INTO `store_discount_data` VALUES ('4', 'AGDAO 2 LAPU LAPU', '2023-01-29', 'FIRST SHIFT', 'System Admin', '50.00', '2023-01-23 14:27:12', null, null, 'Posted', 'Closed', '0');
INSERT INTO `store_discount_data` VALUES ('5', 'AGDAO 2 LAPU LAPU', '2023-01-23', 'FIRST SHIFT', 'ROTCIN LECHIDO', '50.00', '2023-02-02 17:19:03', null, null, 'No', 'Open', '0');

-- ----------------------------
-- Table structure for `store_navigation`
-- ----------------------------
DROP TABLE IF EXISTS `store_navigation`;
CREATE TABLE `store_navigation` (
  `id` bigint(20) NOT NULL AUTO_INCREMENT,
  `sorting` int(6) DEFAULT '0',
  `category_id` int(20) DEFAULT NULL,
  `menu_name` varchar(100) DEFAULT NULL,
  `page_name` varchar(100) DEFAULT NULL,
  `display_icon` varchar(100) DEFAULT NULL,
  `icon_color` varchar(100) DEFAULT NULL,
  `table_columns` longtext,
  `active` int(1) DEFAULT '1',
  PRIMARY KEY (`id`)
) ENGINE=InnoDB AUTO_INCREMENT=32 DEFAULT CHARSET=latin1;

-- ----------------------------
-- Records of store_navigation
-- ----------------------------
INSERT INTO `store_navigation` VALUES ('1', '1', '100', 'Dashboard', 'dashboard', 'glyphicon glyphicon-home', 'icon-color-orange', null, '1');
INSERT INTO `store_navigation` VALUES ('3', '2', '100', 'Finish Goods Transfer', 'fgts', 'fa-solid fa-utensils', 'text-primary', null, '1');
INSERT INTO `store_navigation` VALUES ('5', '3', '100', 'Transfer In/Out', 'transfer', 'fa-solid fa-right-left', 'text-primary', null, '1');
INSERT INTO `store_navigation` VALUES ('6', '4', '100', 'Charges', 'charges', 'fa-solid fa-file-invoice-dollar', 'text-primary', null, '1');
INSERT INTO `store_navigation` VALUES ('7', '5', '100', 'Snacks', 'snacks', 'fa-solid fa-popcorn', 'text-primary', null, '1');
INSERT INTO `store_navigation` VALUES ('8', '6', '100', 'Bad Order', 'badorder', 'fa-solid fa-send-back', 'text-primary', null, '1');
INSERT INTO `store_navigation` VALUES ('9', '7', '100', 'Damage', 'damage', 'fa-solid fa-wine-glass-crack', 'text-primary', null, '1');
INSERT INTO `store_navigation` VALUES ('10', '8', '100', 'Complimentary', 'complimentary', 'fa-sharp fa-solid fa-burger-soda', 'text-primary', null, '1');
INSERT INTO `store_navigation` VALUES ('11', '9', '100', 'Request', 'request', 'fa-solid fa-paper-plane-top', 'text-primary', null, '0');
INSERT INTO `store_navigation` VALUES ('12', '10', '100', 'Receiving Report', 'receiving', 'fa-solid fa-inbox-in', 'text-primary', null, '1');
INSERT INTO `store_navigation` VALUES ('13', '11', '100', 'Cash Count', 'cashcount', 'fa-solid fa-treasure-chest', 'text-primary', null, '1');
INSERT INTO `store_navigation` VALUES ('14', '16', '1100', 'Summary Report', 'summary', 'fa-solid fa-file-spreadsheet', 'text-danger', null, '1');
INSERT INTO `store_navigation` VALUES ('15', '17', '1100', 'Submit To Server', 'submitserver', 'fa-solid fa-cloud', 'text-primary', null, '1');
INSERT INTO `store_navigation` VALUES ('16', '1', '102', 'Receiving Report', 'rm_receiving', 'fa-solid fa-inbox-in', 'text-primary', null, '1');
INSERT INTO `store_navigation` VALUES ('17', '2', '102', 'Transfer In/Out', 'rm_transfer', 'fa-solid fa-right-left', 'text-primary', null, '1');
INSERT INTO `store_navigation` VALUES ('18', '4', '102', 'Phsysical Count', 'rm_pcount', 'fa-solid fa-tally', 'text-primary', null, '1');
INSERT INTO `store_navigation` VALUES ('19', '1', '103', 'Summary Report', 'rm_summary', 'fa-solid fa-file-spreadsheet', 'text-danger', null, '1');
INSERT INTO `store_navigation` VALUES ('20', '3', '103', 'Submit To Server', 'rm_submitserver', 'fa-solid fa-cloud', 'text-primary', null, '1');
INSERT INTO `store_navigation` VALUES ('21', '0', '102', 'Dashboard', 'dashboard', 'glyphicon glyphicon-home', 'icon-color-orange', null, '1');
INSERT INTO `store_navigation` VALUES ('22', '0', '104', 'Dashboard', 'dashboard', 'fa-home', 'text-primary', null, '1');
INSERT INTO `store_navigation` VALUES ('23', '15', '1100', 'Production Report', 'production', 'fa-solid fa-hammer', 'text-primary', null, '1');
INSERT INTO `store_navigation` VALUES ('24', '0', '103', 'Daily Usage Report', 'dum', 'fa-solid fa-fill-drip', 'text-primary', null, '1');
INSERT INTO `store_navigation` VALUES ('26', '3', '102', 'Bad Order', 'rm_badorder', 'fa-solid fa-send-back', 'text-primary', null, '1');
INSERT INTO `store_navigation` VALUES ('27', '13', '101', 'Production Report', 'production', 'fa-book', 'text-primary', null, '1');
INSERT INTO `store_navigation` VALUES ('28', '12', '100', 'Frozen Dough', 'frozendough', 'fa-solid fa-icicles', 'text-primary', null, '1');
INSERT INTO `store_navigation` VALUES ('30', '13', '100', 'Psysical Count', 'pcount', 'fa-solid fa-tally', 'text-primary', null, '1');
INSERT INTO `store_navigation` VALUES ('31', '14', '100', 'Store Discount', 'discount', 'fa-solid fa-tags', 'text-primary', null, '1');
