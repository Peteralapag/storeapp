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
) ENGINE=InnoDB AUTO_INCREMENT=2 DEFAULT CHARSET=latin1;

-- ----------------------------
-- Records of store_discount_data
-- ----------------------------
