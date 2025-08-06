/*
Navicat MySQL Data Transfer

Source Server         : PROJECT SRV
Source Server Version : 50738
Source Host           : 192.168.1.4:3306
Source Database       : storeapps_deyta

Target Server Type    : MYSQL
Target Server Version : 50738
File Encoding         : 65001

Date: 2023-02-10 10:13:51
*/

SET FOREIGN_KEY_CHECKS=0;

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
INSERT INTO `store_navigation` VALUES ('1', '1', '100', 'Dashboard', 'dashboard', 'glyphicon glyphicon-home', 'icon-color-orange', '', '1');
INSERT INTO `store_navigation` VALUES ('3', '2', '100', 'Finish Goods Transfer', 'fgts', 'fa-solid fa-utensils', 'text-primary', '', '1');
INSERT INTO `store_navigation` VALUES ('5', '3', '100', 'Transfer In/Out', 'transfer', 'fa-solid fa-right-left', 'text-primary', '', '1');
INSERT INTO `store_navigation` VALUES ('6', '4', '100', 'Charges', 'charges', 'fa-solid fa-file-invoice-dollar', 'text-primary', '', '1');
INSERT INTO `store_navigation` VALUES ('7', '5', '100', 'Snacks', 'snacks', 'fa-solid fa-popcorn', 'text-primary', '', '1');
INSERT INTO `store_navigation` VALUES ('8', '6', '100', 'Bad Order', 'badorder', 'fa-solid fa-send-back', 'text-primary', '', '1');
INSERT INTO `store_navigation` VALUES ('9', '7', '100', 'Damage', 'damage', 'fa-solid fa-wine-glass-crack', 'text-primary', '', '1');
INSERT INTO `store_navigation` VALUES ('10', '8', '100', 'Complimentary', 'complimentary', 'fa-sharp fa-solid fa-burger-soda', 'text-primary', '', '1');
INSERT INTO `store_navigation` VALUES ('11', '9', '100', 'Request', 'request', 'fa-solid fa-paper-plane-top', 'text-primary', '', '0');
INSERT INTO `store_navigation` VALUES ('12', '10', '100', 'Receiving Report', 'receiving', 'fa-solid fa-inbox-in', 'text-primary', '', '1');
INSERT INTO `store_navigation` VALUES ('13', '11', '100', 'Cash Count', 'cashcount', 'fa-solid fa-treasure-chest', 'text-primary', '', '1');
INSERT INTO `store_navigation` VALUES ('14', '16', '1100', 'Summary Report', 'summary', 'fa-solid fa-file-spreadsheet', 'text-danger', '', '1');
INSERT INTO `store_navigation` VALUES ('15', '17', '1100', 'Submit To Server', 'submitserver', 'fa-solid fa-cloud', 'text-primary', '', '1');
INSERT INTO `store_navigation` VALUES ('16', '1', '102', 'Receiving Report', 'rm_receiving', 'fa-solid fa-inbox-in', 'text-primary', '', '1');
INSERT INTO `store_navigation` VALUES ('17', '2', '102', 'Transfer In/Out', 'rm_transfer', 'fa-solid fa-right-left', 'text-primary', '', '1');
INSERT INTO `store_navigation` VALUES ('18', '4', '102', 'Phsysical Count', 'rm_pcount', 'fa-solid fa-tally', 'text-primary', '', '1');
INSERT INTO `store_navigation` VALUES ('19', '1', '103', 'Summary Report', 'rm_summary', 'fa-solid fa-file-spreadsheet', 'text-danger', '', '1');
INSERT INTO `store_navigation` VALUES ('20', '3', '103', 'Submit To Server', 'rm_submitserver', 'fa-solid fa-cloud', 'text-primary', '', '1');
INSERT INTO `store_navigation` VALUES ('21', '0', '102', 'Dashboard', 'dashboard', 'glyphicon glyphicon-home', 'icon-color-orange', '', '1');
INSERT INTO `store_navigation` VALUES ('22', '0', '104', 'Dashboard', 'dashboard', 'fa-home', 'text-primary', '', '1');
INSERT INTO `store_navigation` VALUES ('23', '15', '1100', 'Production Report', 'production', 'fa-solid fa-hammer', 'text-primary', '', '1');
INSERT INTO `store_navigation` VALUES ('24', '0', '103', 'Daily Usage Report', 'dum', 'fa-solid fa-fill-drip', 'text-primary', '', '1');
INSERT INTO `store_navigation` VALUES ('26', '3', '102', 'Bad Order', 'rm_badorder', 'fa-solid fa-send-back', 'text-primary', '', '1');
INSERT INTO `store_navigation` VALUES ('27', '13', '101', 'Production Report', 'production', 'fa-book', 'text-primary', '', '1');
INSERT INTO `store_navigation` VALUES ('28', '12', '100', 'Frozen Dough', 'frozendough', 'fa-solid fa-icicles', 'text-primary', '', '1');
INSERT INTO `store_navigation` VALUES ('30', '13', '100', 'Psysical Count', 'pcount', 'fa-solid fa-tally', 'text-primary', '', '1');
INSERT INTO `store_navigation` VALUES ('31', '14', '100', 'Store Discount', 'discount', 'fa-solid fa-tags', 'text-primary', '', '1');
