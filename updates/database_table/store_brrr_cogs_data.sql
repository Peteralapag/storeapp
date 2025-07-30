/*
Navicat MySQL Data Transfer

Source Server         : prj
Source Server Version : 50505
Source Host           : 192.168.1.4:3306
Source Database       : storeapp_data

Target Server Type    : MYSQL
Target Server Version : 50505
File Encoding         : 65001

Date: 2025-06-30 10:15:51
*/

SET FOREIGN_KEY_CHECKS=0;

-- ----------------------------
-- Table structure for `store_brrr_cogs_data`
-- ----------------------------
DROP TABLE IF EXISTS `store_brrr_cogs_data`;
CREATE TABLE `store_brrr_cogs_data` (
  `id` bigint(255) NOT NULL AUTO_INCREMENT,
  `hid` bigint(255) DEFAULT NULL,
  `date_from` date DEFAULT NULL,
  `date_to` date DEFAULT NULL,
  `item_name` varchar(123) DEFAULT NULL,
  `item_id` int(11) DEFAULT NULL,
  `category` varchar(123) DEFAULT NULL,
  `cost_pc` double(11,2) DEFAULT 0.00,
  `created_by` varchar(123) DEFAULT NULL,
  `updated_by` varchar(123) DEFAULT NULL,
  `date_created` timestamp NULL DEFAULT NULL,
  `date_updated` timestamp NULL DEFAULT NULL ON UPDATE current_timestamp(),
  PRIMARY KEY (`id`)
) ENGINE=InnoDB DEFAULT CHARSET=latin1;

-- ----------------------------
-- Records of store_brrr_cogs_data
-- ----------------------------
