/*
Navicat MySQL Data Transfer

Source Server         : prj
Source Server Version : 50505
Source Host           : 192.168.1.4:3306
Source Database       : storeapp_data

Target Server Type    : MYSQL
Target Server Version : 50505
File Encoding         : 65001

Date: 2025-06-30 13:18:07
*/

SET FOREIGN_KEY_CHECKS=0;

-- ----------------------------
-- Table structure for `tbl_employees_ho`
-- ----------------------------
DROP TABLE IF EXISTS `tbl_employees_ho`;
CREATE TABLE `tbl_employees_ho` (
  `id` int(11) NOT NULL AUTO_INCREMENT,
  `creator` varchar(30) DEFAULT '',
  `qrcode` varchar(50) DEFAULT NULL,
  `barcodeid` varchar(50) NOT NULL,
  `idcode` varchar(50) NOT NULL,
  `controlno` varchar(50) NOT NULL,
  `lastname` varchar(50) NOT NULL,
  `firstname` varchar(50) NOT NULL,
  `middlename` varchar(50) NOT NULL,
  `acctname` varchar(50) NOT NULL,
  `company` varchar(50) NOT NULL,
  `cluster` varchar(50) DEFAULT '',
  `branch` varchar(50) NOT NULL,
  `department` varchar(50) NOT NULL,
  `position` varchar(50) NOT NULL,
  `employment_status` varchar(50) NOT NULL,
  `date_applied` date NOT NULL,
  `date_hired` date NOT NULL,
  `date_regular` date NOT NULL,
  `date_resigned` date NOT NULL,
  `date_terminated` date DEFAULT '0000-00-00',
  `status` varchar(20) NOT NULL,
  `birth_date` date NOT NULL,
  `birth_place` varchar(100) NOT NULL,
  `gender` varchar(20) NOT NULL,
  `civil_status` varchar(20) NOT NULL,
  `country` varchar(20) NOT NULL,
  `nationality` varchar(20) NOT NULL,
  `religion` varchar(60) NOT NULL,
  `employee_photo` varchar(123) NOT NULL,
  `c_houseno` varchar(20) NOT NULL,
  `c_street` varchar(100) NOT NULL,
  `c_barangay` varchar(100) NOT NULL,
  `c_citytown` varchar(50) NOT NULL,
  `c_province_estate` varchar(50) NOT NULL,
  `p_houseno` varchar(20) NOT NULL,
  `p_street` varchar(100) NOT NULL,
  `p_barangay` varchar(100) NOT NULL,
  `p_citytown` varchar(50) NOT NULL,
  `p_province_estate` varchar(20) NOT NULL,
  `mobile` varchar(20) NOT NULL,
  `telephone` varchar(20) NOT NULL,
  `email_address` varchar(60) NOT NULL,
  `fb_address` varchar(100) NOT NULL,
  `incase_person` varchar(80) NOT NULL,
  `incase_contact` varchar(20) NOT NULL,
  `incase_address` varchar(100) NOT NULL,
  `fathername` varchar(80) DEFAULT '',
  `fatheroccupation` varchar(123) DEFAULT NULL,
  `fatheraddress` varchar(123) DEFAULT NULL,
  `fathercontact` varchar(123) DEFAULT NULL,
  `mothername` varchar(123) DEFAULT NULL,
  `motheroccupation` varchar(123) DEFAULT NULL,
  `motheraddress` varchar(123) DEFAULT NULL,
  `mothercontact` varchar(123) DEFAULT NULL,
  `noofsiblings` int(3) DEFAULT NULL,
  `noofdependents` int(3) DEFAULT NULL,
  `gov_sss_no` varchar(255) NOT NULL,
  `gov_pagibig_no` varchar(40) NOT NULL,
  `gov_philhealth_no` varchar(40) NOT NULL,
  `gov_tin_no` varchar(40) NOT NULL,
  `bank` varchar(40) NOT NULL,
  `acctno` varchar(40) NOT NULL,
  `included` int(1) NOT NULL,
  `date_created` datetime DEFAULT '0000-00-00 00:00:00',
  `created_by` varchar(60) DEFAULT '',
  `date_updated` datetime DEFAULT '0000-00-00 00:00:00',
  `updated_by` varchar(60) DEFAULT '',
  `salary_type` varchar(20) DEFAULT '',
  `salary_monthly` double(10,2) DEFAULT 0.00,
  `salary_daily` double(10,2) DEFAULT 0.00,
  `salary_hourly` double(10,2) DEFAULT 0.00,
  `night_diff` double(10,0) DEFAULT 0,
  `nightdiff_amount` double(10,2) DEFAULT 0.00,
  `allowance_daily` double(10,2) DEFAULT 0.00,
  `vacation_leave` double(6,2) DEFAULT 0.00,
  `vacation_leave_used` double(6,2) DEFAULT 0.00,
  `vacation_leave_balance` double(6,2) DEFAULT 0.00,
  `sick_leave` double(6,2) DEFAULT 0.00,
  `sick_leave_used` double(6,2) DEFAULT 0.00,
  `sick_leave_balance` double(6,2) DEFAULT 0.00,
  `emergency_leave` double(6,2) DEFAULT 0.00,
  `emergency_leave_balance` double(6,2) DEFAULT 0.00,
  `emergency_leave_used` double(6,2) DEFAULT 0.00,
  `collect_sss_every` int(6) DEFAULT NULL,
  `collect_pagibig_every` int(6) DEFAULT NULL,
  `collect_phic_every` int(6) DEFAULT NULL,
  `collect_ps_every` int(6) DEFAULT NULL,
  `collect_tax_every` int(6) DEFAULT NULL,
  `collect_hmo_every` int(6) DEFAULT NULL,
  `collect_sss` varchar(5) DEFAULT NULL,
  `collect_pagibig` varchar(5) DEFAULT NULL,
  `cola_perday` double(11,2) DEFAULT NULL,
  `collect_phic` varchar(5) DEFAULT NULL,
  `collect_ps` varchar(5) DEFAULT NULL,
  `collect_tax` varchar(5) DEFAULT NULL,
  `collect_hmo` varchar(5) DEFAULT NULL,
  `pagibig_extra` double(6,0) DEFAULT 0,
  `personal_savings` double(11,0) DEFAULT 0,
  `hmo_amount` double(11,0) DEFAULT 0,
  `username` varchar(25) DEFAULT NULL,
  `password` varchar(50) DEFAULT NULL,
  `allowance_load` double(10,2) DEFAULT 0.00,
  `collect_load` varchar(6) DEFAULT 'No',
  `collect_load_every` varchar(5) DEFAULT NULL,
  `allowance_mobility` double(10,2) DEFAULT 0.00,
  `collect_mobility` varchar(6) DEFAULT 'No',
  `collect_mobility_every` varchar(5) DEFAULT NULL,
  `mobile_user` tinyint(1) DEFAULT 0,
  `branch_reliever` tinyint(1) DEFAULT 0,
  `Administrator` tinyint(1) DEFAULT 0,
  `assigned_area` varchar(100) DEFAULT NULL,
  `area_branches` longtext DEFAULT NULL,
  `roaming` tinyint(1) DEFAULT 0,
  PRIMARY KEY (`id`)
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4;

-- ----------------------------
-- Records of tbl_employees_ho
-- ----------------------------
