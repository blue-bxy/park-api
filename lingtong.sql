/*
 Navicat Premium Data Transfer

 Source Server         : lingtong
 Source Server Type    : MySQL
 Source Server Version : 100322
 Source Host           : 47.101.34.229:3306
 Source Schema         : lingtong

 Target Server Type    : MySQL
 Target Server Version : 100322
 File Encoding         : 65001

 Date: 22/12/2020 15:32:35
*/

SET NAMES utf8mb4;
SET FOREIGN_KEY_CHECKS = 0;

-- ----------------------------
-- Table structure for account_manages
-- ----------------------------
DROP TABLE IF EXISTS `account_manages`;
CREATE TABLE `account_manages`  (
  `id` bigint(20) UNSIGNED NOT NULL AUTO_INCREMENT,
  `park_id` bigint(20) UNSIGNED NOT NULL COMMENT '停车场id',
  `property_id` bigint(20) UNSIGNED NOT NULL COMMENT '物业id',
  `account_name` varchar(255) CHARACTER SET utf8mb4 COLLATE utf8mb4_unicode_ci NOT NULL COMMENT '账号的用户名',
  `account` varchar(255) CHARACTER SET utf8mb4 COLLATE utf8mb4_unicode_ci NOT NULL COMMENT '账号',
  `account_type` tinyint(3) UNSIGNED NOT NULL COMMENT '账户类型:1-对公，2-对私',
  `account_province` varchar(255) CHARACTER SET utf8mb4 COLLATE utf8mb4_unicode_ci NOT NULL COMMENT '账号所在的省份',
  `account_city` varchar(255) CHARACTER SET utf8mb4 COLLATE utf8mb4_unicode_ci NOT NULL COMMENT '账号所在城市',
  `account_area` varchar(255) CHARACTER SET utf8mb4 COLLATE utf8mb4_unicode_ci NOT NULL COMMENT '账号所在的区',
  `bank_name` varchar(255) CHARACTER SET utf8mb4 COLLATE utf8mb4_unicode_ci NOT NULL COMMENT '开户行',
  `bank_code` varchar(50) CHARACTER SET utf8mb4 COLLATE utf8mb4_unicode_ci NOT NULL COMMENT '银行编号',
  `sub_branch` varchar(255) CHARACTER SET utf8mb4 COLLATE utf8mb4_unicode_ci NOT NULL COMMENT '支行',
  `contract_id` bigint(20) UNSIGNED NOT NULL COMMENT '合同编号的id',
  `synchronization_type` int(10) UNSIGNED NOT NULL DEFAULT 1 COMMENT '同步状态:1-未同步，2-已同步',
  `audit_status` int(10) UNSIGNED NOT NULL DEFAULT 1 COMMENT '审核状态:1-未审核，2-已审核',
  `banned_withdraw` timestamp(0) NULL DEFAULT NULL COMMENT '冻结提现',
  `created_at` timestamp(0) NULL DEFAULT NULL,
  `updated_at` timestamp(0) NULL DEFAULT NULL,
  `deleted_at` timestamp(0) NULL DEFAULT NULL,
  PRIMARY KEY (`id`) USING BTREE
) ENGINE = InnoDB AUTO_INCREMENT = 38 CHARACTER SET = utf8mb4 COLLATE = utf8mb4_unicode_ci ROW_FORMAT = Dynamic;

-- ----------------------------
-- Table structure for activity_logs
-- ----------------------------
DROP TABLE IF EXISTS `activity_logs`;
CREATE TABLE `activity_logs`  (
  `id` bigint(20) UNSIGNED NOT NULL AUTO_INCREMENT,
  `log_name` varchar(255) CHARACTER SET utf8mb4 COLLATE utf8mb4_unicode_ci NULL DEFAULT NULL,
  `description` text CHARACTER SET utf8mb4 COLLATE utf8mb4_unicode_ci NOT NULL,
  `last_ip` varchar(45) CHARACTER SET utf8mb4 COLLATE utf8mb4_unicode_ci NULL DEFAULT NULL,
  `subject_type` varchar(255) CHARACTER SET utf8mb4 COLLATE utf8mb4_unicode_ci NULL DEFAULT NULL,
  `subject_id` bigint(20) UNSIGNED NULL DEFAULT NULL,
  `causer_type` varchar(255) CHARACTER SET utf8mb4 COLLATE utf8mb4_unicode_ci NULL DEFAULT NULL,
  `causer_id` bigint(20) UNSIGNED NULL DEFAULT NULL,
  `properties` longtext CHARACTER SET utf8mb4 COLLATE utf8mb4_bin NULL,
  `created_at` timestamp(0) NULL DEFAULT NULL,
  `updated_at` timestamp(0) NULL DEFAULT NULL,
  PRIMARY KEY (`id`) USING BTREE,
  INDEX `activity_logs_subject_type_subject_id_index`(`subject_type`, `subject_id`) USING BTREE,
  INDEX `activity_logs_causer_type_causer_id_index`(`causer_type`, `causer_id`) USING BTREE,
  INDEX `activity_logs_log_name_index`(`log_name`) USING BTREE
) ENGINE = InnoDB AUTO_INCREMENT = 14953649 CHARACTER SET = utf8mb4 COLLATE = utf8mb4_unicode_ci ROW_FORMAT = Dynamic;

-- ----------------------------
-- Table structure for admins
-- ----------------------------
DROP TABLE IF EXISTS `admins`;
CREATE TABLE `admins`  (
  `id` bigint(20) UNSIGNED NOT NULL AUTO_INCREMENT,
  `name` varchar(191) CHARACTER SET utf8mb4 COLLATE utf8mb4_unicode_ci NOT NULL,
  `account_name` varchar(255) CHARACTER SET utf8mb4 COLLATE utf8mb4_unicode_ci NULL DEFAULT NULL COMMENT '账号名称',
  `code` varchar(191) CHARACTER SET utf8mb4 COLLATE utf8mb4_unicode_ci NULL DEFAULT NULL,
  `mobile` varchar(191) CHARACTER SET utf8mb4 COLLATE utf8mb4_unicode_ci NOT NULL,
  `email` varchar(191) CHARACTER SET utf8mb4 COLLATE utf8mb4_unicode_ci NOT NULL,
  `email_verified_at` timestamp(0) NULL DEFAULT NULL,
  `password` varchar(191) CHARACTER SET utf8mb4 COLLATE utf8mb4_unicode_ci NOT NULL,
  `banned_login` timestamp(0) NULL DEFAULT NULL,
  `remember_token` varchar(100) CHARACTER SET utf8mb4 COLLATE utf8mb4_unicode_ci NULL DEFAULT NULL,
  `deleted_at` timestamp(0) NULL DEFAULT NULL,
  `created_at` timestamp(0) NULL DEFAULT NULL,
  `updated_at` timestamp(0) NULL DEFAULT NULL,
  PRIMARY KEY (`id`) USING BTREE,
  UNIQUE INDEX `admins_mobile_unique`(`mobile`) USING BTREE,
  UNIQUE INDEX `admins_email_unique`(`email`) USING BTREE
) ENGINE = InnoDB AUTO_INCREMENT = 41 CHARACTER SET = utf8mb4 COLLATE = utf8mb4_unicode_ci ROW_FORMAT = Dynamic;

-- ----------------------------
-- Table structure for applies
-- ----------------------------
DROP TABLE IF EXISTS `applies`;
CREATE TABLE `applies`  (
  `id` bigint(20) UNSIGNED NOT NULL AUTO_INCREMENT,
  `no` varchar(255) CHARACTER SET utf8mb4 COLLATE utf8mb4_unicode_ci NOT NULL COMMENT '付款批次号',
  `amount` int(11) NOT NULL COMMENT '申请付款金额',
  `payment_number` int(11) NOT NULL COMMENT '申请付款笔数',
  `success_number` int(11) NULL DEFAULT NULL COMMENT '成功付款笔数',
  `business_type` int(11) NOT NULL COMMENT '业务类型 1-提现 2-退款',
  `person_type` int(11) NOT NULL COMMENT '付款对象类型 1-物业 2-用户',
  `submit` int(11) NOT NULL DEFAULT 1 COMMENT '提交结果 1-待提交 2-已提交 3-已拒绝',
  `status` int(11) NOT NULL DEFAULT 1 COMMENT '处理状态 1-待处理 2-处理中 3-已处理',
  `apply_time` timestamp(0) NULL DEFAULT NULL COMMENT '申请时间',
  `payment_time` timestamp(0) NULL DEFAULT NULL COMMENT '付款时间',
  `complete_time` timestamp(0) NULL DEFAULT NULL COMMENT '完成日期',
  `agent` varchar(30) CHARACTER SET utf8mb4 COLLATE utf8mb4_unicode_ci NULL DEFAULT NULL COMMENT '经办人',
  `channel` varchar(30) CHARACTER SET utf8mb4 COLLATE utf8mb4_unicode_ci NULL DEFAULT NULL COMMENT '付款通道',
  `deleted_at` timestamp(0) NULL DEFAULT NULL,
  `created_at` timestamp(0) NULL DEFAULT NULL,
  `updated_at` timestamp(0) NULL DEFAULT NULL,
  PRIMARY KEY (`id`) USING BTREE,
  UNIQUE INDEX `applies_no_unique`(`no`) USING BTREE
) ENGINE = InnoDB AUTO_INCREMENT = 48 CHARACTER SET = utf8mb4 COLLATE = utf8mb4_unicode_ci ROW_FORMAT = Dynamic;

-- ----------------------------
-- Table structure for apply_middles
-- ----------------------------
DROP TABLE IF EXISTS `apply_middles`;
CREATE TABLE `apply_middles`  (
  `id` bigint(20) UNSIGNED NOT NULL AUTO_INCREMENT,
  `order_type` varchar(255) CHARACTER SET utf8mb4 COLLATE utf8mb4_unicode_ci NOT NULL,
  `order_id` bigint(20) UNSIGNED NOT NULL,
  `apply_id` bigint(20) UNSIGNED NOT NULL,
  `created_at` timestamp(0) NULL DEFAULT NULL,
  `updated_at` timestamp(0) NULL DEFAULT NULL,
  PRIMARY KEY (`id`) USING BTREE,
  INDEX `apply_middles_order_type_order_id_index`(`order_type`, `order_id`) USING BTREE,
  INDEX `apply_middles_apply_id_foreign`(`apply_id`) USING BTREE,
  CONSTRAINT `apply_middles_apply_id_foreign` FOREIGN KEY (`apply_id`) REFERENCES `applies` (`id`) ON DELETE CASCADE ON UPDATE RESTRICT
) ENGINE = InnoDB AUTO_INCREMENT = 55 CHARACTER SET = utf8mb4 COLLATE = utf8mb4_unicode_ci ROW_FORMAT = Dynamic;

-- ----------------------------
-- Table structure for apt_order_days
-- ----------------------------
DROP TABLE IF EXISTS `apt_order_days`;
CREATE TABLE `apt_order_days`  (
  `id` bigint(20) UNSIGNED NOT NULL AUTO_INCREMENT,
  `park_id` int(10) NOT NULL,
  `no` varchar(255) CHARACTER SET utf8mb4 COLLATE utf8mb4_unicode_ci NOT NULL COMMENT '结算单号',
  `type` int(11) NOT NULL COMMENT '结算类型 1-正常结算收入 2-延时结算收入 3-退款',
  `amount` int(11) NOT NULL COMMENT '总金额',
  `time` timestamp(0) NOT NULL DEFAULT current_timestamp(0) ON UPDATE CURRENT_TIMESTAMP(0) COMMENT '结算时间',
  `created_at` timestamp(0) NULL DEFAULT NULL,
  `updated_at` timestamp(0) NULL DEFAULT NULL,
  PRIMARY KEY (`id`) USING BTREE
) ENGINE = InnoDB AUTO_INCREMENT = 180 CHARACTER SET = utf8mb4 COLLATE = utf8mb4_unicode_ci ROW_FORMAT = Dynamic;

-- ----------------------------
-- Table structure for bad_credits
-- ----------------------------
DROP TABLE IF EXISTS `bad_credits`;
CREATE TABLE `bad_credits`  (
  `id` bigint(20) UNSIGNED NOT NULL AUTO_INCREMENT,
  `order_id` bigint(20) UNSIGNED NULL DEFAULT NULL,
  `bad_amount` int(11) NOT NULL COMMENT '坏账金额',
  `already_amount` int(11) NULL DEFAULT NULL COMMENT '已补金额',
  `is_payment` int(11) NOT NULL DEFAULT 1 COMMENT '是否需补缴 1-否 2-是',
  `bad_results` varchar(30) CHARACTER SET utf8mb4 COLLATE utf8mb4_unicode_ci NULL DEFAULT NULL COMMENT '坏账结果',
  `bad_source` varchar(30) CHARACTER SET utf8mb4 COLLATE utf8mb4_unicode_ci NOT NULL COMMENT '坏账来源',
  `deleted_at` timestamp(0) NULL DEFAULT NULL,
  `created_at` timestamp(0) NULL DEFAULT NULL,
  `updated_at` timestamp(0) NULL DEFAULT NULL,
  PRIMARY KEY (`id`) USING BTREE,
  INDEX `bad_credits_order_id_foreign`(`order_id`) USING BTREE,
  CONSTRAINT `bad_credits_order_id_foreign` FOREIGN KEY (`order_id`) REFERENCES `user_orders` (`id`) ON DELETE CASCADE ON UPDATE RESTRICT
) ENGINE = InnoDB AUTO_INCREMENT = 1 CHARACTER SET = utf8mb4 COLLATE = utf8mb4_unicode_ci ROW_FORMAT = Dynamic;

-- ----------------------------
-- Table structure for booking_fees
-- ----------------------------
DROP TABLE IF EXISTS `booking_fees`;
CREATE TABLE `booking_fees`  (
  `id` bigint(20) UNSIGNED NOT NULL AUTO_INCREMENT,
  `park_id` int(10) UNSIGNED NULL DEFAULT NULL COMMENT '车场id',
  `apt` longtext CHARACTER SET utf8mb4 COLLATE utf8mb4_bin NOT NULL COMMENT '预约分成费率：platfotm-平台,park-车场,owner-业主,type[1-业主，2-物业]',
  `stop` longtext CHARACTER SET utf8mb4 COLLATE utf8mb4_bin NOT NULL COMMENT '停车分成费率：platfotm-平台,park-车场,owner-业主,type[1-业主，2-物业]',
  `status` int(10) UNSIGNED NOT NULL DEFAULT 1 COMMENT '分成费率的状态，1-停用，2-启用',
  `scope` longtext CHARACTER SET utf8mb4 COLLATE utf8mb4_bin NULL COMMENT '金额范围设置',
  `user_id` bigint(20) UNSIGNED NOT NULL COMMENT '设置人员',
  `created_at` timestamp(0) NULL DEFAULT NULL,
  `updated_at` timestamp(0) NULL DEFAULT NULL,
  `deleted_at` timestamp(0) NULL DEFAULT NULL,
  PRIMARY KEY (`id`) USING BTREE
) ENGINE = InnoDB AUTO_INCREMENT = 36 CHARACTER SET = utf8mb4 COLLATE = utf8mb4_unicode_ci ROW_FORMAT = Dynamic;

-- ----------------------------
-- Table structure for brand_models
-- ----------------------------
DROP TABLE IF EXISTS `brand_models`;
CREATE TABLE `brand_models`  (
  `id` bigint(20) UNSIGNED NOT NULL AUTO_INCREMENT,
  `brand_id` bigint(20) UNSIGNED NOT NULL,
  `name` varchar(255) CHARACTER SET utf8mb4 COLLATE utf8mb4_unicode_ci NOT NULL COMMENT '型号',
  `deleted_at` timestamp(0) NULL DEFAULT NULL,
  `created_at` timestamp(0) NULL DEFAULT NULL,
  `updated_at` timestamp(0) NULL DEFAULT NULL,
  PRIMARY KEY (`id`) USING BTREE,
  INDEX `brand_models_brand_id_index`(`brand_id`) USING BTREE
) ENGINE = InnoDB AUTO_INCREMENT = 86 CHARACTER SET = utf8mb4 COLLATE = utf8mb4_unicode_ci ROW_FORMAT = Dynamic;

-- ----------------------------
-- Table structure for brands
-- ----------------------------
DROP TABLE IF EXISTS `brands`;
CREATE TABLE `brands`  (
  `id` bigint(20) UNSIGNED NOT NULL AUTO_INCREMENT,
  `name` varchar(255) CHARACTER SET utf8mb4 COLLATE utf8mb4_unicode_ci NOT NULL COMMENT '品牌',
  `type` varchar(255) CHARACTER SET utf8mb4 COLLATE utf8mb4_unicode_ci NOT NULL COMMENT '品牌类型，1摄像头，2地锁，3蓝牙，4汽车品牌',
  `deleted_at` timestamp(0) NULL DEFAULT NULL,
  `created_at` timestamp(0) NULL DEFAULT NULL,
  `updated_at` timestamp(0) NULL DEFAULT NULL,
  PRIMARY KEY (`id`) USING BTREE
) ENGINE = InnoDB AUTO_INCREMENT = 22 CHARACTER SET = utf8mb4 COLLATE = utf8mb4_unicode_ci ROW_FORMAT = Dynamic;

-- ----------------------------
-- Table structure for car_approves
-- ----------------------------
DROP TABLE IF EXISTS `car_approves`;
CREATE TABLE `car_approves`  (
  `id` bigint(20) UNSIGNED NOT NULL AUTO_INCREMENT,
  `created_at` timestamp(0) NULL DEFAULT NULL,
  `updated_at` timestamp(0) NULL DEFAULT NULL,
  PRIMARY KEY (`id`) USING BTREE
) ENGINE = InnoDB AUTO_INCREMENT = 1 CHARACTER SET = utf8mb4 COLLATE = utf8mb4_unicode_ci ROW_FORMAT = Dynamic;

-- ----------------------------
-- Table structure for car_apt_orders
-- ----------------------------
DROP TABLE IF EXISTS `car_apt_orders`;
CREATE TABLE `car_apt_orders`  (
  `id` bigint(20) UNSIGNED NOT NULL AUTO_INCREMENT,
  `user_id` bigint(20) UNSIGNED NOT NULL,
  `car_apt_id` bigint(20) UNSIGNED NOT NULL COMMENT '预约表的id',
  `user_order_id` bigint(20) UNSIGNED NULL DEFAULT NULL,
  `amount` int(10) UNSIGNED NOT NULL COMMENT '预约金额',
  `service_charge` int(10) UNSIGNED NOT NULL DEFAULT 0 COMMENT '平台收取的手续费',
  `subscribe_time` int(10) UNSIGNED NOT NULL COMMENT '预约时长',
  `no` varchar(255) CHARACTER SET utf8mb4 COLLATE utf8mb4_unicode_ci NOT NULL COMMENT '预约订单号',
  `coupon_id` bigint(20) UNSIGNED NULL DEFAULT NULL,
  `payment_gateway` varchar(255) CHARACTER SET utf8mb4 COLLATE utf8mb4_unicode_ci NULL DEFAULT NULL COMMENT '支付方式：支付宝、微信、钱包、免密（支付宝、微信）、现金',
  `transaction_id` varchar(255) CHARACTER SET utf8mb4 COLLATE utf8mb4_unicode_ci NULL DEFAULT NULL COMMENT '流水id',
  `currency` varchar(255) CHARACTER SET utf8mb4 COLLATE utf8mb4_unicode_ci NOT NULL DEFAULT 'CNY',
  `status` varchar(255) CHARACTER SET utf8mb4 COLLATE utf8mb4_unicode_ci NOT NULL DEFAULT 'pending' COMMENT 'pending/paid/cancelled/failed/refunded/finished',
  `is_renewal` tinyint(1) NOT NULL DEFAULT 0,
  `refund_amount` int(10) UNSIGNED NOT NULL DEFAULT 0 COMMENT '退款金额',
  `refund_no` varchar(255) CHARACTER SET utf8mb4 COLLATE utf8mb4_unicode_ci NULL DEFAULT NULL COMMENT '预约退款订单号',
  `refund_id` varchar(255) CHARACTER SET utf8mb4 COLLATE utf8mb4_unicode_ci NULL DEFAULT NULL COMMENT '预约退款交易号',
  `expired_at` timestamp(0) NULL DEFAULT NULL COMMENT '订单失效时间，超过创建订单时间+30分钟订单失效',
  `paid_at` timestamp(0) NULL DEFAULT NULL,
  `cancelled_at` timestamp(0) NULL DEFAULT NULL,
  `refunded_at` timestamp(0) NULL DEFAULT NULL,
  `finished_at` timestamp(0) NULL DEFAULT NULL,
  `failed_at` timestamp(0) NULL DEFAULT NULL,
  `commented_at` timestamp(0) NULL DEFAULT NULL,
  `deleted_at` timestamp(0) NULL DEFAULT NULL,
  `created_at` timestamp(0) NULL DEFAULT NULL,
  `updated_at` timestamp(0) NULL DEFAULT NULL,
  PRIMARY KEY (`id`) USING BTREE,
  UNIQUE INDEX `car_apt_orders_no_unique`(`no`) USING BTREE,
  INDEX `car_apt_orders_car_apt_id_foreign`(`car_apt_id`) USING BTREE,
  INDEX `car_apt_orders_transaction_id_index`(`transaction_id`) USING BTREE,
  INDEX `car_apt_orders_currency_index`(`currency`) USING BTREE,
  INDEX `car_apt_orders_status_index`(`status`) USING BTREE,
  INDEX `car_apt_orders_refund_no_index`(`refund_no`) USING BTREE,
  INDEX `car_apt_orders_refund_id_index`(`refund_id`) USING BTREE,
  INDEX `car_apt_orders_user_id_index`(`user_id`) USING BTREE,
  INDEX `car_apt_orders_user_order_id_index`(`user_order_id`) USING BTREE,
  CONSTRAINT `car_apt_orders_car_apt_id_foreign` FOREIGN KEY (`car_apt_id`) REFERENCES `car_apts` (`id`) ON DELETE CASCADE ON UPDATE RESTRICT
) ENGINE = InnoDB AUTO_INCREMENT = 1377 CHARACTER SET = utf8mb4 COLLATE = utf8mb4_unicode_ci ROW_FORMAT = Dynamic;

-- ----------------------------
-- Table structure for car_apts
-- ----------------------------
DROP TABLE IF EXISTS `car_apts`;
CREATE TABLE `car_apts`  (
  `id` bigint(20) UNSIGNED NOT NULL AUTO_INCREMENT COMMENT '主键ID',
  `user_id` bigint(20) UNSIGNED NOT NULL COMMENT '用户ID',
  `user_order_id` bigint(20) UNSIGNED NOT NULL COMMENT '外键，关联user_order表id',
  `park_id` bigint(20) UNSIGNED NOT NULL COMMENT '停车场ID',
  `park_space_id` bigint(20) UNSIGNED NOT NULL COMMENT '车位id',
  `user_car_id` bigint(20) UNSIGNED NOT NULL COMMENT '用户车辆id',
  `amount` int(10) UNSIGNED NOT NULL COMMENT '首次支付费用',
  `total_amount` int(10) UNSIGNED NOT NULL COMMENT '总费用',
  `deduct_amount` int(10) UNSIGNED NOT NULL DEFAULT 0 COMMENT '实际扣款',
  `service_charge` int(10) UNSIGNED NOT NULL DEFAULT 0 COMMENT '平台收取的手续费',
  `refund_total_amount` int(10) UNSIGNED NOT NULL DEFAULT 0 COMMENT '退款费用',
  `car_rent_id` bigint(20) UNSIGNED NULL DEFAULT NULL COMMENT '出租车位的id',
  `car_stop_id` bigint(20) UNSIGNED NULL DEFAULT NULL COMMENT '停车记录',
  `apt_start_time` timestamp(0) NULL DEFAULT NULL COMMENT '预约开始时间',
  `apt_end_time` timestamp(0) NULL DEFAULT NULL COMMENT '预约结束时间',
  `apt_time` varchar(255) CHARACTER SET utf8mb4 COLLATE utf8mb4_unicode_ci NULL DEFAULT NULL COMMENT '预约总时间',
  `created_at` timestamp(0) NULL DEFAULT NULL,
  `updated_at` timestamp(0) NULL DEFAULT NULL,
  `deleted_at` timestamp(0) NULL DEFAULT NULL,
  `rate_cache` longtext CHARACTER SET utf8mb4 COLLATE utf8mb4_bin NULL COMMENT '停车费用缓存',
  `renewed_at` timestamp(0) NULL DEFAULT NULL COMMENT '续费时间',
  `barrier_gate_no` varchar(255) CHARACTER SET utf8mb4 COLLATE utf8mb4_unicode_ci NULL DEFAULT NULL COMMENT '道闸返回的预约订单号',
  PRIMARY KEY (`id`) USING BTREE,
  INDEX `car_apts_user_id_foreign`(`user_id`) USING BTREE,
  INDEX `car_apts_park_id_foreign`(`park_id`) USING BTREE,
  INDEX `car_apts_user_order_id_index`(`user_order_id`) USING BTREE,
  INDEX `car_apts_user_car_id_index`(`user_car_id`) USING BTREE,
  INDEX `car_apts_car_rent_id_index`(`car_rent_id`) USING BTREE,
  INDEX `car_apts_car_stop_id_index`(`car_stop_id`) USING BTREE,
  CONSTRAINT `car_apts_park_id_foreign` FOREIGN KEY (`park_id`) REFERENCES `parks` (`id`) ON DELETE CASCADE ON UPDATE RESTRICT,
  CONSTRAINT `car_apts_user_id_foreign` FOREIGN KEY (`user_id`) REFERENCES `users` (`id`) ON DELETE CASCADE ON UPDATE RESTRICT
) ENGINE = InnoDB AUTO_INCREMENT = 1365 CHARACTER SET = utf8mb4 COLLATE = utf8mb4_unicode_ci ROW_FORMAT = Dynamic;

-- ----------------------------
-- Table structure for car_flow_records
-- ----------------------------
DROP TABLE IF EXISTS `car_flow_records`;
CREATE TABLE `car_flow_records`  (
  `id` bigint(20) UNSIGNED NOT NULL AUTO_INCREMENT,
  `type` varchar(255) CHARACTER SET utf8mb4 COLLATE utf8mb4_unicode_ci NOT NULL DEFAULT 'in' COMMENT '出入类型：in:入，out:出',
  `park_id` bigint(20) UNSIGNED NULL DEFAULT NULL,
  `code` varchar(255) CHARACTER SET utf8mb4 COLLATE utf8mb4_unicode_ci NULL DEFAULT NULL COMMENT '停车场唯一值unique_code',
  `result` longtext CHARACTER SET utf8mb4 COLLATE utf8mb4_bin NULL,
  `created_at` timestamp(0) NULL DEFAULT NULL,
  `updated_at` timestamp(0) NULL DEFAULT NULL,
  PRIMARY KEY (`id`) USING BTREE,
  INDEX `car_flow_records_park_id_index`(`park_id`) USING BTREE,
  INDEX `car_flow_records_code_index`(`code`) USING BTREE
) ENGINE = InnoDB AUTO_INCREMENT = 5200 CHARACTER SET = utf8mb4 COLLATE = utf8mb4_unicode_ci ROW_FORMAT = Dynamic;

-- ----------------------------
-- Table structure for car_rents
-- ----------------------------
DROP TABLE IF EXISTS `car_rents`;
CREATE TABLE `car_rents`  (
  `id` bigint(20) UNSIGNED NOT NULL AUTO_INCREMENT,
  `user_id` bigint(20) UNSIGNED NOT NULL COMMENT '发布车位车主的ID',
  `user_type` varchar(255) CHARACTER SET utf8mb4 COLLATE utf8mb4_unicode_ci NULL DEFAULT NULL,
  `park_id` bigint(20) UNSIGNED NOT NULL COMMENT '项目ID',
  `park_space_id` bigint(20) UNSIGNED NOT NULL COMMENT '外键，关联park_spaces的id',
  `park_rate_id` bigint(20) UNSIGNED NOT NULL DEFAULT 0 COMMENT '外键，关联park_rates表id',
  `user_space_id` bigint(20) UNSIGNED NULL DEFAULT NULL,
  `car_num` varchar(255) CHARACTER SET utf8mb4 COLLATE utf8mb4_unicode_ci NULL DEFAULT NULL COMMENT '停车的车牌号',
  `rent_num` varchar(255) CHARACTER SET utf8mb4 COLLATE utf8mb4_unicode_ci NULL DEFAULT NULL COMMENT '出租车位编号',
  `pics` longtext CHARACTER SET utf8mb4 COLLATE utf8mb4_bin NULL COMMENT '车位照片',
  `rent_price` int(10) UNSIGNED NOT NULL DEFAULT 0 COMMENT '出租单价',
  `time_unit` int(10) UNSIGNED NOT NULL DEFAULT 60 COMMENT '出租时长单位',
  `down_payments` int(10) UNSIGNED NOT NULL DEFAULT 0 COMMENT '首付金额 单位分',
  `down_payments_time` int(10) UNSIGNED NOT NULL DEFAULT 0 COMMENT '首付金额时长单位 分钟',
  `is_workday` tinyint(4) NOT NULL DEFAULT 2 COMMENT '时间类型，0-非工作日，1-工作日，2-全部',
  `start` varchar(255) CHARACTER SET utf8mb4 COLLATE utf8mb4_unicode_ci NULL DEFAULT NULL COMMENT '出租时间段的起始时间',
  `stop` varchar(255) CHARACTER SET utf8mb4 COLLATE utf8mb4_unicode_ci NULL DEFAULT NULL COMMENT '出租时间段的结束时间',
  `rent_start_time` timestamp(0) NULL DEFAULT NULL COMMENT '出租开始时间',
  `rent_end_time` timestamp(0) NULL DEFAULT NULL COMMENT '出租结束时间',
  `rent_status` tinyint(1) NOT NULL DEFAULT 0 COMMENT '出租车位状态，0表示停用，1表示启用',
  `rent_type_id` int(10) UNSIGNED NOT NULL DEFAULT 1 COMMENT '出租车位的类型，1表示物业发布，2表示业主发布，3表示云端发布',
  `rent_no` varchar(255) CHARACTER SET utf8mb4 COLLATE utf8mb4_unicode_ci NOT NULL COMMENT '出租车位唯一标识号',
  `rent_time` int(10) UNSIGNED NOT NULL DEFAULT 0 COMMENT '出租总时长',
  `rent_all_price` int(10) UNSIGNED NOT NULL DEFAULT 0 COMMENT '出租总价格',
  `created_at` timestamp(0) NULL DEFAULT NULL,
  `updated_at` timestamp(0) NULL DEFAULT NULL,
  `deleted_at` timestamp(0) NULL DEFAULT NULL,
  PRIMARY KEY (`id`) USING BTREE,
  INDEX `car_rents_park_id_index`(`park_id`) USING BTREE,
  INDEX `car_rents_park_space_id_index`(`park_space_id`) USING BTREE,
  INDEX `car_rents_rent_no_index`(`rent_no`) USING BTREE,
  INDEX `car_rents_park_rate_id_index`(`park_rate_id`) USING BTREE
) ENGINE = InnoDB AUTO_INCREMENT = 2459 CHARACTER SET = utf8mb4 COLLATE = utf8mb4_unicode_ci ROW_FORMAT = Dynamic;

-- ----------------------------
-- Table structure for car_stop_orders
-- ----------------------------
DROP TABLE IF EXISTS `car_stop_orders`;
CREATE TABLE `car_stop_orders`  (
  `id` bigint(20) UNSIGNED NOT NULL AUTO_INCREMENT,
  `user_id` bigint(20) UNSIGNED NULL DEFAULT NULL,
  `car_stop_id` bigint(20) UNSIGNED NOT NULL COMMENT '停车表的id',
  `amount` int(10) UNSIGNED NOT NULL COMMENT '停车金额',
  `service_charge` int(10) UNSIGNED NOT NULL DEFAULT 0 COMMENT '平台收取的手续费',
  `stop_time` int(10) UNSIGNED NOT NULL COMMENT '停车时长',
  `no` varchar(255) CHARACTER SET utf8mb4 COLLATE utf8mb4_unicode_ci NOT NULL COMMENT '停车订单号',
  `coupon_id` bigint(20) UNSIGNED NULL DEFAULT NULL COMMENT '优免券id',
  `payment_gateway` varchar(255) CHARACTER SET utf8mb4 COLLATE utf8mb4_unicode_ci NULL DEFAULT NULL COMMENT '支付方式：支付宝、微信、钱包、免密（支付宝、微信）、现金',
  `transaction_id` varchar(255) CHARACTER SET utf8mb4 COLLATE utf8mb4_unicode_ci NULL DEFAULT NULL COMMENT '流水id',
  `currency` varchar(255) CHARACTER SET utf8mb4 COLLATE utf8mb4_unicode_ci NOT NULL DEFAULT 'CNY',
  `status` varchar(255) CHARACTER SET utf8mb4 COLLATE utf8mb4_unicode_ci NOT NULL DEFAULT 'pending' COMMENT 'pending/paid/cancelled/failed/refunded/finished',
  `is_renewal` tinyint(1) NOT NULL DEFAULT 0,
  `refund_amount` int(10) UNSIGNED NOT NULL DEFAULT 0 COMMENT '退款金额',
  `refund_no` varchar(255) CHARACTER SET utf8mb4 COLLATE utf8mb4_unicode_ci NULL DEFAULT NULL COMMENT '停车退款订单号',
  `refund_id` varchar(255) CHARACTER SET utf8mb4 COLLATE utf8mb4_unicode_ci NULL DEFAULT NULL COMMENT '停车退款交易号',
  `expired_at` timestamp(0) NULL DEFAULT NULL COMMENT '订单失效时间，超过创建订单时间+30分钟订单失效',
  `paid_at` timestamp(0) NULL DEFAULT NULL,
  `cancelled_at` timestamp(0) NULL DEFAULT NULL,
  `refunded_at` timestamp(0) NULL DEFAULT NULL,
  `finished_at` timestamp(0) NULL DEFAULT NULL,
  `failed_at` timestamp(0) NULL DEFAULT NULL,
  `commented_at` timestamp(0) NULL DEFAULT NULL,
  `deleted_at` timestamp(0) NULL DEFAULT NULL,
  `created_at` timestamp(0) NULL DEFAULT NULL,
  `updated_at` timestamp(0) NULL DEFAULT NULL,
  PRIMARY KEY (`id`) USING BTREE,
  UNIQUE INDEX `car_stop_orders_no_unique`(`no`) USING BTREE,
  INDEX `car_stop_orders_car_stop_id_foreign`(`car_stop_id`) USING BTREE,
  INDEX `car_stop_orders_transaction_id_index`(`transaction_id`) USING BTREE,
  INDEX `car_stop_orders_currency_index`(`currency`) USING BTREE,
  INDEX `car_stop_orders_status_index`(`status`) USING BTREE,
  INDEX `car_stop_orders_refund_no_index`(`refund_no`) USING BTREE,
  INDEX `car_stop_orders_refund_id_index`(`refund_id`) USING BTREE,
  CONSTRAINT `car_stop_orders_car_stop_id_foreign` FOREIGN KEY (`car_stop_id`) REFERENCES `car_stops` (`id`) ON DELETE CASCADE ON UPDATE RESTRICT
) ENGINE = InnoDB AUTO_INCREMENT = 24 CHARACTER SET = utf8mb4 COLLATE = utf8mb4_unicode_ci ROW_FORMAT = Dynamic;

-- ----------------------------
-- Table structure for car_stops
-- ----------------------------
DROP TABLE IF EXISTS `car_stops`;
CREATE TABLE `car_stops`  (
  `id` bigint(20) UNSIGNED NOT NULL AUTO_INCREMENT COMMENT '主键ID',
  `user_id` bigint(20) UNSIGNED NULL DEFAULT NULL COMMENT '用户ID',
  `user_car_id` bigint(20) UNSIGNED NULL DEFAULT NULL,
  `car_num` varchar(255) CHARACTER SET utf8mb4 COLLATE utf8mb4_unicode_ci NOT NULL COMMENT '停车的车牌号',
  `park_space_id` bigint(20) UNSIGNED NULL DEFAULT NULL,
  `sold_id` varchar(255) CHARACTER SET utf8mb4 COLLATE utf8mb4_unicode_ci NULL DEFAULT NULL COMMENT '推送方的唯一id',
  `user_order_id` bigint(20) UNSIGNED NULL DEFAULT NULL,
  `park_id` bigint(20) UNSIGNED NOT NULL COMMENT '停车场ID',
  `stop_price` int(10) UNSIGNED NOT NULL DEFAULT 0 COMMENT '实际停车金额',
  `pay_amount` int(10) UNSIGNED NOT NULL COMMENT '向app展示支付的费用',
  `discount_amount` int(10) UNSIGNED NOT NULL COMMENT '优惠费用',
  `deduct_amount` int(10) UNSIGNED NOT NULL COMMENT '实际支付停车费用',
  `car_stop_type` int(11) NOT NULL DEFAULT 1 COMMENT '预约停车类型，1表示暂时停车，2表示长租车位，3表示出租暂停',
  `special_price` int(10) UNSIGNED NOT NULL DEFAULT 0 COMMENT '特殊处理损失',
  `washed_price` int(10) UNSIGNED NOT NULL DEFAULT 0 COMMENT '被冲车辆损失',
  `car_in_img` varchar(255) CHARACTER SET utf8mb4 COLLATE utf8mb4_unicode_ci NULL DEFAULT NULL COMMENT '汽车入库图片',
  `car_out_img` varchar(255) CHARACTER SET utf8mb4 COLLATE utf8mb4_unicode_ci NULL DEFAULT NULL COMMENT '汽车出库图片',
  `car_in_time` timestamp(0) NULL DEFAULT NULL COMMENT '汽车入库时间，空表示未识别到',
  `car_stop_time` timestamp(0) NULL DEFAULT NULL COMMENT '车辆停车时间，由摄像头通知',
  `car_out_time` timestamp(0) NULL DEFAULT NULL COMMENT '汽车出库时间，空表示未识别到',
  `stop_time` int(10) UNSIGNED NOT NULL DEFAULT 0 COMMENT '停车总时长，单位分',
  `has_find_car` tinyint(1) NOT NULL DEFAULT 0 COMMENT '是否寻车流程结束',
  `pay_stop_type` int(11) NOT NULL DEFAULT 1 COMMENT '支付方式：1-支付宝、2-微信、3-钱包、4-免密（支付宝、微信）、5-现金',
  `pay_stop_time` timestamp(0) NULL DEFAULT NULL COMMENT '停车费用的支付时间',
  `created_at` timestamp(0) NULL DEFAULT NULL,
  `updated_at` timestamp(0) NULL DEFAULT NULL,
  `deleted_at` timestamp(0) NULL DEFAULT NULL,
  `coupon_id` int(10) UNSIGNED NULL DEFAULT NULL COMMENT '优免券id',
  `car_type` int(10) UNSIGNED NOT NULL DEFAULT 0 COMMENT '车辆类型，0-临时车，1-月租车，2-VIP，7-特殊车辆',
  PRIMARY KEY (`id`) USING BTREE,
  INDEX `car_stops_park_id_foreign`(`park_id`) USING BTREE,
  INDEX `car_in_time`(`car_in_time`) USING BTREE,
  INDEX `car_out_time`(`car_out_time`) USING BTREE,
  INDEX `car_stops_user_id_user_car_id_user_order_id_park_space_id_index`(`user_id`, `user_car_id`, `user_order_id`, `park_space_id`) USING BTREE,
  INDEX `car_stops_sold_id_index`(`sold_id`) USING BTREE,
  CONSTRAINT `car_stops_park_id_foreign` FOREIGN KEY (`park_id`) REFERENCES `parks` (`id`) ON DELETE CASCADE ON UPDATE RESTRICT
) ENGINE = InnoDB AUTO_INCREMENT = 9183 CHARACTER SET = utf8mb4 COLLATE = utf8mb4_unicode_ci ROW_FORMAT = Dynamic;

-- ----------------------------
-- Table structure for carport_maps
-- ----------------------------
DROP TABLE IF EXISTS `carport_maps`;
CREATE TABLE `carport_maps`  (
  `id` bigint(20) UNSIGNED NOT NULL AUTO_INCREMENT,
  `park_id` bigint(20) UNSIGNED NOT NULL,
  `map_id` varchar(255) CHARACTER SET utf8mb4 COLLATE utf8mb4_unicode_ci NOT NULL,
  `map_key` varchar(255) CHARACTER SET utf8mb4 COLLATE utf8mb4_unicode_ci NOT NULL,
  `created_at` timestamp(0) NULL DEFAULT NULL,
  `updated_at` timestamp(0) NULL DEFAULT NULL,
  PRIMARY KEY (`id`) USING BTREE,
  INDEX `carport_maps_park_id_foreign`(`park_id`) USING BTREE,
  CONSTRAINT `carport_maps_park_id_foreign` FOREIGN KEY (`park_id`) REFERENCES `parks` (`id`) ON DELETE CASCADE ON UPDATE RESTRICT
) ENGINE = InnoDB AUTO_INCREMENT = 2 CHARACTER SET = utf8mb4 COLLATE = utf8mb4_unicode_ci ROW_FORMAT = Dynamic;

-- ----------------------------
-- Table structure for cities
-- ----------------------------
DROP TABLE IF EXISTS `cities`;
CREATE TABLE `cities`  (
  `id` bigint(20) UNSIGNED NOT NULL AUTO_INCREMENT,
  `name` varchar(191) CHARACTER SET utf8mb4 COLLATE utf8mb4_unicode_ci NOT NULL,
  `city_id` varchar(191) CHARACTER SET utf8mb4 COLLATE utf8mb4_unicode_ci NOT NULL,
  `province_id` varchar(191) CHARACTER SET utf8mb4 COLLATE utf8mb4_unicode_ci NOT NULL,
  `created_at` timestamp(0) NULL DEFAULT NULL,
  `updated_at` timestamp(0) NULL DEFAULT NULL,
  PRIMARY KEY (`id`) USING BTREE,
  INDEX `cities_city_id_index`(`city_id`) USING BTREE,
  INDEX `cities_province_id_index`(`province_id`) USING BTREE
) ENGINE = InnoDB AUTO_INCREMENT = 344 CHARACTER SET = utf8mb4 COLLATE = utf8mb4_unicode_ci ROW_FORMAT = Dynamic;

-- ----------------------------
-- Table structure for countries
-- ----------------------------
DROP TABLE IF EXISTS `countries`;
CREATE TABLE `countries`  (
  `id` bigint(20) UNSIGNED NOT NULL AUTO_INCREMENT,
  `name` varchar(191) CHARACTER SET utf8mb4 COLLATE utf8mb4_unicode_ci NOT NULL,
  `country_id` varchar(191) CHARACTER SET utf8mb4 COLLATE utf8mb4_unicode_ci NOT NULL,
  `city_id` varchar(191) CHARACTER SET utf8mb4 COLLATE utf8mb4_unicode_ci NOT NULL,
  `created_at` timestamp(0) NULL DEFAULT NULL,
  `updated_at` timestamp(0) NULL DEFAULT NULL,
  PRIMARY KEY (`id`) USING BTREE,
  INDEX `countries_country_id_index`(`country_id`) USING BTREE,
  INDEX `countries_city_id_index`(`city_id`) USING BTREE
) ENGINE = InnoDB AUTO_INCREMENT = 3283 CHARACTER SET = utf8mb4 COLLATE = utf8mb4_unicode_ci ROW_FORMAT = Dynamic;

-- ----------------------------
-- Table structure for coupon_park_rules
-- ----------------------------
DROP TABLE IF EXISTS `coupon_park_rules`;
CREATE TABLE `coupon_park_rules`  (
  `id` bigint(20) UNSIGNED NOT NULL AUTO_INCREMENT,
  `title` varchar(255) CHARACTER SET utf8mb4 COLLATE utf8mb4_unicode_ci NOT NULL,
  `turnover_rate` varchar(255) CHARACTER SET utf8mb4 COLLATE utf8mb4_unicode_ci NOT NULL COMMENT '车位周转率',
  `province_id` varchar(255) CHARACTER SET utf8mb4 COLLATE utf8mb4_unicode_ci NULL DEFAULT NULL,
  `city_id` varchar(255) CHARACTER SET utf8mb4 COLLATE utf8mb4_unicode_ci NULL DEFAULT NULL,
  `district_id` varchar(255) CHARACTER SET utf8mb4 COLLATE utf8mb4_unicode_ci NULL DEFAULT NULL COMMENT '区编码',
  `cooperate_days` int(10) UNSIGNED NOT NULL COMMENT '合作天数',
  `park_property` int(11) NOT NULL DEFAULT 0 COMMENT '停车场属性，0-全部，1-商业综合体，2-商业写字楼，3-商务酒店，4-公共场馆，5-医院，6-产业园，\n                7-住宅，8-旅游景点，9-物流园，10-建材市场，11-学校，12-交通枢纽',
  `is_active` tinyint(1) NOT NULL DEFAULT 1 COMMENT '启用状态：0-停用，1-启用',
  `desc` text CHARACTER SET utf8mb4 COLLATE utf8mb4_unicode_ci NULL,
  `user_type` varchar(255) CHARACTER SET utf8mb4 COLLATE utf8mb4_unicode_ci NOT NULL,
  `user_id` bigint(20) UNSIGNED NOT NULL,
  `deleted_at` timestamp(0) NULL DEFAULT NULL,
  `created_at` timestamp(0) NULL DEFAULT NULL,
  `updated_at` timestamp(0) NULL DEFAULT NULL,
  PRIMARY KEY (`id`) USING BTREE,
  INDEX `coupon_park_rules_user_type_user_id_index`(`user_type`, `user_id`) USING BTREE,
  INDEX `coupon_park_rules_province_id_index`(`province_id`) USING BTREE,
  INDEX `coupon_park_rules_city_id_index`(`city_id`) USING BTREE,
  INDEX `coupon_park_rules_district_id_index`(`district_id`) USING BTREE
) ENGINE = InnoDB AUTO_INCREMENT = 36 CHARACTER SET = utf8mb4 COLLATE = utf8mb4_unicode_ci ROW_FORMAT = Dynamic;

-- ----------------------------
-- Table structure for coupon_rules
-- ----------------------------
DROP TABLE IF EXISTS `coupon_rules`;
CREATE TABLE `coupon_rules`  (
  `id` bigint(20) UNSIGNED NOT NULL AUTO_INCREMENT,
  `title` varchar(255) CHARACTER SET utf8mb4 COLLATE utf8mb4_unicode_ci NOT NULL COMMENT '模板名称',
  `amount` int(10) UNSIGNED NOT NULL,
  `use_scene` int(10) UNSIGNED NOT NULL DEFAULT 1 COMMENT '使用场景:1通用，2预约费，3停车费',
  `desc` text CHARACTER SET utf8mb4 COLLATE utf8mb4_unicode_ci NULL COMMENT '描述',
  `type` tinyint(1) NOT NULL DEFAULT 4 COMMENT '类型，1-小时券，2-现金券，3-折扣券，4-全免券',
  `value` varchar(255) CHARACTER SET utf8mb4 COLLATE utf8mb4_unicode_ci NULL DEFAULT NULL COMMENT '规则,n小时，n元，折扣n折,全免',
  `is_active` tinyint(1) NOT NULL DEFAULT 1 COMMENT '启用状态：0-停用，1-启用',
  `user_type` varchar(255) CHARACTER SET utf8mb4 COLLATE utf8mb4_unicode_ci NOT NULL,
  `user_id` bigint(20) UNSIGNED NOT NULL,
  `deleted_at` timestamp(0) NULL DEFAULT NULL,
  `created_at` timestamp(0) NULL DEFAULT NULL,
  `updated_at` timestamp(0) NULL DEFAULT NULL,
  PRIMARY KEY (`id`) USING BTREE,
  INDEX `coupon_rules_user_type_user_id_index`(`user_type`, `user_id`) USING BTREE
) ENGINE = InnoDB AUTO_INCREMENT = 48 CHARACTER SET = utf8mb4 COLLATE = utf8mb4_unicode_ci ROW_FORMAT = Dynamic;

-- ----------------------------
-- Table structure for coupon_user_rules
-- ----------------------------
DROP TABLE IF EXISTS `coupon_user_rules`;
CREATE TABLE `coupon_user_rules`  (
  `id` bigint(20) UNSIGNED NOT NULL AUTO_INCREMENT,
  `title` varchar(255) CHARACTER SET utf8mb4 COLLATE utf8mb4_unicode_ci NOT NULL,
  `is_activity_active` tinyint(1) NOT NULL DEFAULT 1 COMMENT '活跃度启用状态：0-不启用，1-启用',
  `active_days` int(10) UNSIGNED NOT NULL COMMENT '活跃天数',
  `activity_setting_days` int(10) UNSIGNED NOT NULL DEFAULT 0 COMMENT '活跃度设置天数',
  `is_regression_active` tinyint(1) NOT NULL DEFAULT 1 COMMENT '回归启用状态：0-不启用，1-启用',
  `regression_days` int(10) UNSIGNED NOT NULL DEFAULT 0 COMMENT '回归天数',
  `is_new_user` tinyint(1) NOT NULL DEFAULT 1 COMMENT '是否新用户',
  `is_active` tinyint(1) NOT NULL DEFAULT 1 COMMENT '启用状态：0-停用，1-启用',
  `desc` text CHARACTER SET utf8mb4 COLLATE utf8mb4_unicode_ci NULL,
  `user_type` varchar(255) CHARACTER SET utf8mb4 COLLATE utf8mb4_unicode_ci NOT NULL,
  `user_id` bigint(20) UNSIGNED NOT NULL,
  `deleted_at` timestamp(0) NULL DEFAULT NULL,
  `created_at` timestamp(0) NULL DEFAULT NULL,
  `updated_at` timestamp(0) NULL DEFAULT NULL,
  PRIMARY KEY (`id`) USING BTREE,
  INDEX `coupon_user_rules_user_type_user_id_index`(`user_type`, `user_id`) USING BTREE
) ENGINE = InnoDB AUTO_INCREMENT = 30 CHARACTER SET = utf8mb4 COLLATE = utf8mb4_unicode_ci ROW_FORMAT = Dynamic;

-- ----------------------------
-- Table structure for coupons
-- ----------------------------
DROP TABLE IF EXISTS `coupons`;
CREATE TABLE `coupons`  (
  `id` bigint(20) UNSIGNED NOT NULL AUTO_INCREMENT,
  `no` varchar(255) CHARACTER SET utf8mb4 COLLATE utf8mb4_unicode_ci NOT NULL COMMENT '编号',
  `title` varchar(255) CHARACTER SET utf8mb4 COLLATE utf8mb4_unicode_ci NOT NULL COMMENT '优惠券标题',
  `desc` varchar(255) CHARACTER SET utf8mb4 COLLATE utf8mb4_unicode_ci NULL DEFAULT NULL COMMENT '描述',
  `icon` varchar(255) CHARACTER SET utf8mb4 COLLATE utf8mb4_unicode_ci NULL DEFAULT NULL COMMENT '图片',
  `coupon_rule_id` bigint(20) UNSIGNED NOT NULL,
  `coupon_park_rule_id` bigint(20) UNSIGNED NULL DEFAULT NULL,
  `coupon_user_rule_id` bigint(20) UNSIGNED NULL DEFAULT NULL,
  `status` int(11) NOT NULL DEFAULT 0 COMMENT '0-未使用 1-生效 2-失效 3-已结束',
  `is_valid` tinyint(1) NOT NULL DEFAULT 1 COMMENT '生效状态：0-作废，1-生效',
  `use_scene` int(10) UNSIGNED NOT NULL DEFAULT 1 COMMENT '使用场景:1通用，2预约费，3停车费',
  `used_amount` int(10) UNSIGNED NOT NULL COMMENT '用券金额',
  `quota` int(10) UNSIGNED NOT NULL COMMENT '配额：发券数量',
  `max_receive_num` int(10) UNSIGNED NOT NULL COMMENT '单个用户领取上限',
  `take_count` int(11) NOT NULL COMMENT '已领取的优惠券数量',
  `used_count` int(11) NOT NULL COMMENT '已使用的优惠券数量',
  `need_integral_amount` int(10) UNSIGNED NOT NULL DEFAULT 0 COMMENT '兑换该优惠券所需积分数',
  `start_time` timestamp(0) NULL DEFAULT NULL COMMENT '发放开始时间',
  `end_time` timestamp(0) NULL DEFAULT NULL COMMENT '发放结束时间',
  `distribution_method` int(11) NOT NULL DEFAULT 0 COMMENT '发放方式：1-平台推送，2-app二维码，3-微信/支付宝二维码，4-分享链接',
  `valid_start_time` timestamp(0) NULL DEFAULT NULL COMMENT '生效开始时间',
  `valid_end_time` timestamp(0) NULL DEFAULT NULL COMMENT '生效结束时间',
  `expired_at` timestamp(0) NULL DEFAULT NULL COMMENT '过期时间,有效时间',
  `publisher_type` varchar(255) CHARACTER SET utf8mb4 COLLATE utf8mb4_unicode_ci NOT NULL,
  `publisher_id` bigint(20) UNSIGNED NOT NULL,
  `deleted_at` timestamp(0) NULL DEFAULT NULL,
  `created_at` timestamp(0) NULL DEFAULT NULL,
  `updated_at` timestamp(0) NULL DEFAULT NULL,
  `park_id` bigint(20) UNSIGNED NULL DEFAULT NULL,
  `coupon_rule_type` varchar(255) CHARACTER SET utf8mb4 COLLATE utf8mb4_unicode_ci NULL DEFAULT NULL,
  `coupon_rule_value` varchar(255) CHARACTER SET utf8mb4 COLLATE utf8mb4_unicode_ci NULL DEFAULT NULL,
  `qrcode_data` longtext CHARACTER SET utf8mb4 COLLATE utf8mb4_unicode_ci NULL COMMENT '二维码数据',
  `assign_user` longtext CHARACTER SET utf8mb4 COLLATE utf8mb4_bin NULL COMMENT '指定发放用户',
  `rules` longtext CHARACTER SET utf8mb4 COLLATE utf8mb4_bin NULL COMMENT '规则集合：{折扣规则，停车场属性，用户属性}',
  PRIMARY KEY (`id`) USING BTREE,
  UNIQUE INDEX `coupons_no_unique`(`no`) USING BTREE,
  INDEX `coupons_publisher_type_publisher_id_index`(`publisher_type`, `publisher_id`) USING BTREE,
  INDEX `coupons_coupon_rule_id_index`(`coupon_rule_id`) USING BTREE,
  INDEX `coupons_coupon_park_rule_id_index`(`coupon_park_rule_id`) USING BTREE,
  INDEX `coupons_coupon_user_rule_id_index`(`coupon_user_rule_id`) USING BTREE
) ENGINE = InnoDB AUTO_INCREMENT = 134 CHARACTER SET = utf8mb4 COLLATE = utf8mb4_unicode_ci ROW_FORMAT = Dynamic;

-- ----------------------------
-- Table structure for departments
-- ----------------------------
DROP TABLE IF EXISTS `departments`;
CREATE TABLE `departments`  (
  `id` bigint(20) UNSIGNED NOT NULL AUTO_INCREMENT,
  `name` varchar(255) CHARACTER SET utf8mb4 COLLATE utf8mb4_unicode_ci NOT NULL,
  `guard_name` varchar(255) CHARACTER SET utf8mb4 COLLATE utf8mb4_unicode_ci NOT NULL,
  `created_at` timestamp(0) NULL DEFAULT NULL,
  `updated_at` timestamp(0) NULL DEFAULT NULL,
  PRIMARY KEY (`id`) USING BTREE
) ENGINE = InnoDB AUTO_INCREMENT = 11 CHARACTER SET = utf8mb4 COLLATE = utf8mb4_unicode_ci ROW_FORMAT = Dynamic;

-- ----------------------------
-- Table structure for detail_accounts
-- ----------------------------
DROP TABLE IF EXISTS `detail_accounts`;
CREATE TABLE `detail_accounts`  (
  `id` bigint(20) UNSIGNED NOT NULL AUTO_INCREMENT,
  `mobile` varchar(11) CHARACTER SET utf8mb4 COLLATE utf8mb4_unicode_ci NOT NULL,
  `initial_amount` int(11) NOT NULL COMMENT '初期余额',
  `amount` int(11) NOT NULL COMMENT '交易金额',
  `end_amount` int(11) NOT NULL COMMENT '期末余额',
  `business_type` int(11) NOT NULL COMMENT '业务类型',
  `direction` int(11) NOT NULL COMMENT '收支方向',
  `no` varchar(255) CHARACTER SET utf8mb4 COLLATE utf8mb4_unicode_ci NOT NULL COMMENT '我方单号',
  `third_no` varchar(255) CHARACTER SET utf8mb4 COLLATE utf8mb4_unicode_ci NOT NULL COMMENT '第三方单号',
  `status` int(11) NOT NULL COMMENT '支付状态',
  `trading_time` timestamp(0) NOT NULL DEFAULT current_timestamp(0) ON UPDATE CURRENT_TIMESTAMP(0) COMMENT '交易时间',
  `deleted_at` timestamp(0) NULL DEFAULT NULL,
  `created_at` timestamp(0) NULL DEFAULT NULL,
  `updated_at` timestamp(0) NULL DEFAULT NULL,
  PRIMARY KEY (`id`) USING BTREE,
  UNIQUE INDEX `detail_accounts_no_unique`(`no`) USING BTREE,
  UNIQUE INDEX `detail_accounts_third_no_unique`(`third_no`) USING BTREE
) ENGINE = InnoDB AUTO_INCREMENT = 1 CHARACTER SET = utf8mb4 COLLATE = utf8mb4_unicode_ci ROW_FORMAT = Dynamic;

-- ----------------------------
-- Table structure for device_callbacks
-- ----------------------------
DROP TABLE IF EXISTS `device_callbacks`;
CREATE TABLE `device_callbacks`  (
  `id` bigint(20) UNSIGNED NOT NULL AUTO_INCREMENT,
  `park_number` varchar(255) CHARACTER SET utf8mb4 COLLATE utf8mb4_unicode_ci NULL DEFAULT NULL,
  `gateway` varchar(255) CHARACTER SET utf8mb4 COLLATE utf8mb4_unicode_ci NOT NULL,
  `type` varchar(255) CHARACTER SET utf8mb4 COLLATE utf8mb4_unicode_ci NULL DEFAULT NULL COMMENT '业务类型：车位变化、地锁、出入场等',
  `result` longtext CHARACTER SET utf8mb4 COLLATE utf8mb4_bin NULL,
  `created_at` timestamp(0) NULL DEFAULT NULL,
  `updated_at` timestamp(0) NULL DEFAULT NULL,
  PRIMARY KEY (`id`) USING BTREE
) ENGINE = InnoDB AUTO_INCREMENT = 1074922 CHARACTER SET = utf8mb4 COLLATE = utf8mb4_unicode_ci ROW_FORMAT = Dynamic;

-- ----------------------------
-- Table structure for device_synchronize_logs
-- ----------------------------
DROP TABLE IF EXISTS `device_synchronize_logs`;
CREATE TABLE `device_synchronize_logs`  (
  `id` bigint(20) UNSIGNED NOT NULL AUTO_INCREMENT,
  `park_number` varchar(255) CHARACTER SET utf8mb4 COLLATE utf8mb4_unicode_ci NULL DEFAULT NULL,
  `gateway` varchar(255) CHARACTER SET utf8mb4 COLLATE utf8mb4_unicode_ci NOT NULL,
  `type` tinyint(4) NOT NULL DEFAULT 0 COMMENT '设备类型：0-无，1-摄像头，2-地锁，3-蓝牙',
  `result` longtext CHARACTER SET utf8mb4 COLLATE utf8mb4_bin NULL,
  `created_at` timestamp(0) NULL DEFAULT NULL,
  `updated_at` timestamp(0) NULL DEFAULT NULL,
  PRIMARY KEY (`id`) USING BTREE
) ENGINE = InnoDB AUTO_INCREMENT = 5943 CHARACTER SET = utf8mb4 COLLATE = utf8mb4_unicode_ci ROW_FORMAT = Dynamic;

-- ----------------------------
-- Table structure for discounts
-- ----------------------------
DROP TABLE IF EXISTS `discounts`;
CREATE TABLE `discounts`  (
  `id` bigint(20) UNSIGNED NOT NULL AUTO_INCREMENT,
  `project_name` varchar(255) CHARACTER SET utf8mb4 COLLATE utf8mb4_unicode_ci NOT NULL COMMENT '项目名称',
  `discount_coupon` varchar(100) CHARACTER SET utf8mb4 COLLATE utf8mb4_unicode_ci NOT NULL COMMENT '优惠券名称',
  `discount_type` tinyint(4) NOT NULL DEFAULT 1 COMMENT '优免类型：1小时优免券，2现金优免券，3折扣优免券，4全免券',
  `rule_hour` varchar(255) CHARACTER SET utf8mb4 COLLATE utf8mb4_unicode_ci NULL DEFAULT NULL COMMENT '优免小时',
  `rule_money` varchar(255) CHARACTER SET utf8mb4 COLLATE utf8mb4_unicode_ci NULL DEFAULT NULL COMMENT '优免元',
  `rule_discount` varchar(255) CHARACTER SET utf8mb4 COLLATE utf8mb4_unicode_ci NULL DEFAULT NULL COMMENT '优免折',
  `merchant_type` tinyint(4) NOT NULL DEFAULT 1 COMMENT '商家类型',
  `select_profile_type` tinyint(4) NOT NULL DEFAULT 1 COMMENT '选择用户类型',
  `issue_form` tinyint(4) NOT NULL DEFAULT 1 COMMENT '发放形式: 1电子券',
  `user_issue_count` tinyint(4) NOT NULL COMMENT '单个用户发放张数',
  `issue_start_date` date NOT NULL COMMENT '优惠券发放起始时间',
  `issue_end_date` date NOT NULL COMMENT '优惠券发放起止时间',
  `discount_coupon_number` varchar(255) CHARACTER SET utf8mb4 COLLATE utf8mb4_unicode_ci NOT NULL COMMENT '优惠券编号',
  `use_state` tinyint(4) NULL DEFAULT NULL COMMENT '使用状态',
  `created_at` timestamp(0) NULL DEFAULT NULL,
  `updated_at` timestamp(0) NULL DEFAULT NULL,
  `deleted_at` timestamp(0) NULL DEFAULT NULL,
  PRIMARY KEY (`id`) USING BTREE
) ENGINE = InnoDB AUTO_INCREMENT = 1 CHARACTER SET = utf8mb4 COLLATE = utf8mb4_unicode_ci ROW_FORMAT = Dynamic;

-- ----------------------------
-- Table structure for export_excels
-- ----------------------------
DROP TABLE IF EXISTS `export_excels`;
CREATE TABLE `export_excels`  (
  `id` bigint(20) UNSIGNED NOT NULL AUTO_INCREMENT,
  `excel_name` varchar(60) CHARACTER SET utf8mb4 COLLATE utf8mb4_unicode_ci NOT NULL DEFAULT '' COMMENT '导出表的名称',
  `excel_type` varchar(255) CHARACTER SET utf8mb4 COLLATE utf8mb4_unicode_ci NOT NULL DEFAULT 'Excel' COMMENT '导出表的类型，默认为Excel格式',
  `excel_src` varchar(255) CHARACTER SET utf8mb4 COLLATE utf8mb4_unicode_ci NOT NULL DEFAULT '' COMMENT '报表存放的路径',
  `excel_size` bigint(20) UNSIGNED NOT NULL DEFAULT 0 COMMENT '报表文件的大小(kb)',
  `load_type_id` tinyint(1) NOT NULL DEFAULT 0 COMMENT '是否下载',
  `create_excel_time` timestamp(0) NULL DEFAULT NULL COMMENT '报表的创建时间',
  `load_excel_time` timestamp(0) NULL DEFAULT NULL COMMENT '报表的导出时间',
  `created_at` timestamp(0) NULL DEFAULT NULL,
  `updated_at` timestamp(0) NULL DEFAULT NULL,
  `deleted_at` timestamp(0) NULL DEFAULT NULL COMMENT '软删除',
  PRIMARY KEY (`id`) USING BTREE,
  INDEX `excel_name`(`excel_name`) USING BTREE,
  INDEX `excel_type`(`excel_type`) USING BTREE,
  INDEX `create_excel_time`(`create_excel_time`) USING BTREE,
  INDEX `out_excel_time`(`load_excel_time`) USING BTREE
) ENGINE = InnoDB AUTO_INCREMENT = 567 CHARACTER SET = utf8mb4 COLLATE = utf8mb4_unicode_ci ROW_FORMAT = Dynamic;

-- ----------------------------
-- Table structure for failed_jobs
-- ----------------------------
DROP TABLE IF EXISTS `failed_jobs`;
CREATE TABLE `failed_jobs`  (
  `id` bigint(20) UNSIGNED NOT NULL AUTO_INCREMENT,
  `uuid` varchar(255) CHARACTER SET utf8mb4 COLLATE utf8mb4_unicode_ci NULL DEFAULT NULL,
  `connection` text CHARACTER SET utf8mb4 COLLATE utf8mb4_unicode_ci NOT NULL,
  `queue` text CHARACTER SET utf8mb4 COLLATE utf8mb4_unicode_ci NOT NULL,
  `payload` longtext CHARACTER SET utf8mb4 COLLATE utf8mb4_unicode_ci NOT NULL,
  `exception` longtext CHARACTER SET utf8mb4 COLLATE utf8mb4_unicode_ci NOT NULL,
  `failed_at` timestamp(0) NOT NULL DEFAULT current_timestamp(0),
  PRIMARY KEY (`id`) USING BTREE,
  UNIQUE INDEX `failed_jobs_uuid_unique`(`uuid`) USING BTREE
) ENGINE = InnoDB AUTO_INCREMENT = 4956 CHARACTER SET = utf8mb4 COLLATE = utf8mb4_unicode_ci ROW_FORMAT = Dynamic;

-- ----------------------------
-- Table structure for message_codes
-- ----------------------------
DROP TABLE IF EXISTS `message_codes`;
CREATE TABLE `message_codes`  (
  `id` bigint(20) UNSIGNED NOT NULL AUTO_INCREMENT,
  `action_type` varchar(255) CHARACTER SET utf8mb4 COLLATE utf8mb4_unicode_ci NOT NULL COMMENT '操作类型：login:注册，bind:绑定手机号，confirm:验证手机号，forget:忘记密码',
  `phone` varchar(255) CHARACTER SET utf8mb4 COLLATE utf8mb4_unicode_ci NOT NULL COMMENT '手机号',
  `code` varchar(255) CHARACTER SET utf8mb4 COLLATE utf8mb4_unicode_ci NULL DEFAULT NULL COMMENT '验证码',
  `ip` varchar(45) CHARACTER SET utf8mb4 COLLATE utf8mb4_unicode_ci NULL DEFAULT NULL,
  `sid` varchar(255) CHARACTER SET utf8mb4 COLLATE utf8mb4_unicode_ci NULL DEFAULT NULL COMMENT '发送标识 id',
  `send_time` timestamp(0) NULL DEFAULT NULL COMMENT '转发时间',
  `report_status` tinyint(1) NOT NULL DEFAULT 1 COMMENT '短信接收状态，true（成功）、false（失败）',
  `report_time` timestamp(0) NULL DEFAULT NULL COMMENT '用户接收时间',
  `report` longtext CHARACTER SET utf8mb4 COLLATE utf8mb4_bin NULL COMMENT '回调内容',
  `created_at` timestamp(0) NULL DEFAULT NULL,
  `updated_at` timestamp(0) NULL DEFAULT NULL,
  PRIMARY KEY (`id`) USING BTREE
) ENGINE = InnoDB AUTO_INCREMENT = 578 CHARACTER SET = utf8mb4 COLLATE = utf8mb4_unicode_ci ROW_FORMAT = Dynamic;

-- ----------------------------
-- Table structure for messages
-- ----------------------------
DROP TABLE IF EXISTS `messages`;
CREATE TABLE `messages`  (
  `id` bigint(20) UNSIGNED NOT NULL AUTO_INCREMENT,
  `admin_id` int(10) UNSIGNED NOT NULL COMMENT '推送消息管理人员id',
  `no` varchar(255) CHARACTER SET utf8mb4 COLLATE utf8mb4_unicode_ci NOT NULL COMMENT '消息编号，自动生成',
  `send_type` tinyint(4) NOT NULL DEFAULT 0 COMMENT '发送类型：0:站内系统通知，1:App通知, 2:都发',
  `type` tinyint(4) NOT NULL DEFAULT 0 COMMENT '消息类型：优惠券4、系统0',
  `title` varchar(255) CHARACTER SET utf8mb4 COLLATE utf8mb4_unicode_ci NULL DEFAULT NULL COMMENT '通知标题, 可选',
  `content` varchar(255) CHARACTER SET utf8mb4 COLLATE utf8mb4_unicode_ci NOT NULL COMMENT '通知内容',
  `platform` varchar(255) CHARACTER SET utf8mb4 COLLATE utf8mb4_unicode_ci NOT NULL DEFAULT 'all' COMMENT '推送平台：全部all, 苹果ios, 安卓android',
  `send_time` timestamp(0) NULL DEFAULT NULL COMMENT '定时发送时间, 非空时定时，反之立即发送',
  `extras` longtext CHARACTER SET utf8mb4 COLLATE utf8mb4_bin NULL COMMENT '拓展字段',
  `created_at` timestamp(0) NULL DEFAULT NULL,
  `updated_at` timestamp(0) NULL DEFAULT NULL,
  `deleted_at` timestamp(0) NULL DEFAULT NULL,
  PRIMARY KEY (`id`) USING BTREE,
  UNIQUE INDEX `messages_no_unique`(`no`) USING BTREE
) ENGINE = InnoDB AUTO_INCREMENT = 36 CHARACTER SET = utf8mb4 COLLATE = utf8mb4_unicode_ci ROW_FORMAT = Dynamic;

-- ----------------------------
-- Table structure for migrations
-- ----------------------------
DROP TABLE IF EXISTS `migrations`;
CREATE TABLE `migrations`  (
  `id` int(10) UNSIGNED NOT NULL AUTO_INCREMENT,
  `migration` varchar(191) CHARACTER SET utf8mb4 COLLATE utf8mb4_unicode_ci NOT NULL,
  `batch` int(11) NOT NULL,
  PRIMARY KEY (`id`) USING BTREE
) ENGINE = InnoDB AUTO_INCREMENT = 956 CHARACTER SET = utf8mb4 COLLATE = utf8mb4_unicode_ci ROW_FORMAT = Dynamic;

-- ----------------------------
-- Table structure for model_has_department_positions
-- ----------------------------
DROP TABLE IF EXISTS `model_has_department_positions`;
CREATE TABLE `model_has_department_positions`  (
  `user_type` varchar(255) CHARACTER SET utf8mb4 COLLATE utf8mb4_unicode_ci NOT NULL,
  `user_id` bigint(20) UNSIGNED NOT NULL,
  `department_id` bigint(20) UNSIGNED NOT NULL,
  `position_id` bigint(20) UNSIGNED NOT NULL,
  INDEX `model_has_department_positions_user_type_user_id_index`(`user_type`, `user_id`) USING BTREE,
  INDEX `model_has_department_positions_department_id_foreign`(`department_id`) USING BTREE,
  INDEX `model_has_department_positions_position_id_foreign`(`position_id`) USING BTREE,
  CONSTRAINT `model_has_department_positions_department_id_foreign` FOREIGN KEY (`department_id`) REFERENCES `departments` (`id`) ON DELETE RESTRICT ON UPDATE RESTRICT,
  CONSTRAINT `model_has_department_positions_position_id_foreign` FOREIGN KEY (`position_id`) REFERENCES `positions` (`id`) ON DELETE RESTRICT ON UPDATE RESTRICT
) ENGINE = InnoDB CHARACTER SET = utf8mb4 COLLATE = utf8mb4_unicode_ci ROW_FORMAT = Dynamic;

-- ----------------------------
-- Table structure for model_has_departments
-- ----------------------------
DROP TABLE IF EXISTS `model_has_departments`;
CREATE TABLE `model_has_departments`  (
  `user_type` varchar(255) CHARACTER SET utf8mb4 COLLATE utf8mb4_unicode_ci NOT NULL,
  `user_id` bigint(20) UNSIGNED NOT NULL,
  `department_id` bigint(20) UNSIGNED NOT NULL,
  INDEX `model_has_departments_user_type_user_id_index`(`user_type`, `user_id`) USING BTREE,
  INDEX `model_has_departments_department_id_foreign`(`department_id`) USING BTREE,
  CONSTRAINT `model_has_departments_department_id_foreign` FOREIGN KEY (`department_id`) REFERENCES `departments` (`id`) ON DELETE RESTRICT ON UPDATE RESTRICT
) ENGINE = InnoDB CHARACTER SET = utf8mb4 COLLATE = utf8mb4_unicode_ci ROW_FORMAT = Dynamic;

-- ----------------------------
-- Table structure for model_has_park_rates
-- ----------------------------
DROP TABLE IF EXISTS `model_has_park_rates`;
CREATE TABLE `model_has_park_rates`  (
  `model_type` varchar(255) CHARACTER SET utf8mb4 COLLATE utf8mb4_unicode_ci NOT NULL,
  `model_id` bigint(20) UNSIGNED NOT NULL,
  `park_rate_id` bigint(20) UNSIGNED NOT NULL,
  INDEX `model_has_park_rates_model_type_model_id_index`(`model_type`, `model_id`) USING BTREE,
  INDEX `model_has_park_rates_park_rate_id_foreign`(`park_rate_id`) USING BTREE,
  CONSTRAINT `model_has_park_rates_park_rate_id_foreign` FOREIGN KEY (`park_rate_id`) REFERENCES `park_rates` (`id`) ON DELETE CASCADE ON UPDATE RESTRICT
) ENGINE = InnoDB CHARACTER SET = utf8mb4 COLLATE = utf8mb4_unicode_ci ROW_FORMAT = Dynamic;

-- ----------------------------
-- Table structure for model_has_permissions
-- ----------------------------
DROP TABLE IF EXISTS `model_has_permissions`;
CREATE TABLE `model_has_permissions`  (
  `permission_id` bigint(20) UNSIGNED NOT NULL,
  `model_type` varchar(191) CHARACTER SET utf8mb4 COLLATE utf8mb4_unicode_ci NOT NULL,
  `model_id` bigint(20) UNSIGNED NOT NULL,
  PRIMARY KEY (`permission_id`, `model_id`, `model_type`) USING BTREE,
  INDEX `model_has_permissions_model_id_model_type_index`(`model_id`, `model_type`) USING BTREE,
  CONSTRAINT `model_has_permissions_permission_id_foreign` FOREIGN KEY (`permission_id`) REFERENCES `permissions` (`id`) ON DELETE CASCADE ON UPDATE RESTRICT
) ENGINE = InnoDB CHARACTER SET = utf8mb4 COLLATE = utf8mb4_unicode_ci ROW_FORMAT = Dynamic;

-- ----------------------------
-- Table structure for model_has_roles
-- ----------------------------
DROP TABLE IF EXISTS `model_has_roles`;
CREATE TABLE `model_has_roles`  (
  `role_id` bigint(20) UNSIGNED NOT NULL,
  `model_type` varchar(191) CHARACTER SET utf8mb4 COLLATE utf8mb4_unicode_ci NOT NULL,
  `model_id` bigint(20) UNSIGNED NOT NULL,
  PRIMARY KEY (`role_id`, `model_id`, `model_type`) USING BTREE,
  INDEX `model_has_roles_model_id_model_type_index`(`model_id`, `model_type`) USING BTREE,
  CONSTRAINT `model_has_roles_role_id_foreign` FOREIGN KEY (`role_id`) REFERENCES `roles` (`id`) ON DELETE CASCADE ON UPDATE RESTRICT
) ENGINE = InnoDB CHARACTER SET = utf8mb4 COLLATE = utf8mb4_unicode_ci ROW_FORMAT = Dynamic;

-- ----------------------------
-- Table structure for order_amount_divides
-- ----------------------------
DROP TABLE IF EXISTS `order_amount_divides`;
CREATE TABLE `order_amount_divides`  (
  `id` bigint(20) UNSIGNED NOT NULL AUTO_INCREMENT,
  `park_id` bigint(20) UNSIGNED NOT NULL,
  `user_id` bigint(20) UNSIGNED NOT NULL,
  `model_type` varchar(255) CHARACTER SET utf8mb4 COLLATE utf8mb4_unicode_ci NOT NULL,
  `model_id` bigint(20) UNSIGNED NOT NULL,
  `total_amount` int(10) UNSIGNED NOT NULL COMMENT '订单金额',
  `fee` int(10) UNSIGNED NOT NULL DEFAULT 0 COMMENT '预约费/停车费，实际金额',
  `fee_type` tinyint(1) NOT NULL DEFAULT 0 COMMENT '费用类型：0预约费，1停车费',
  `platform_rate` decimal(5, 2) NOT NULL DEFAULT 0 COMMENT '物业抽成比例',
  `park_rate` decimal(5, 2) NOT NULL DEFAULT 0 COMMENT '物业抽成比例',
  `owner_rate` decimal(5, 2) NOT NULL DEFAULT 0 COMMENT '业主抽成比例',
  `platform_fee` int(10) UNSIGNED NOT NULL DEFAULT 0 COMMENT '平台收益',
  `park_fee` int(10) UNSIGNED NOT NULL DEFAULT 0 COMMENT '物业收益',
  `owner_fee` int(10) UNSIGNED NOT NULL DEFAULT 0 COMMENT '业主收益',
  `created_at` timestamp(0) NULL DEFAULT NULL,
  `updated_at` timestamp(0) NULL DEFAULT NULL,
  PRIMARY KEY (`id`) USING BTREE,
  INDEX `order_amount_divides_park_id_foreign`(`park_id`) USING BTREE,
  INDEX `order_amount_divides_user_id_foreign`(`user_id`) USING BTREE,
  INDEX `order_amount_divides_model_type_model_id_index`(`model_type`, `model_id`) USING BTREE,
  CONSTRAINT `order_amount_divides_park_id_foreign` FOREIGN KEY (`park_id`) REFERENCES `parks` (`id`) ON DELETE CASCADE ON UPDATE RESTRICT,
  CONSTRAINT `order_amount_divides_user_id_foreign` FOREIGN KEY (`user_id`) REFERENCES `users` (`id`) ON DELETE CASCADE ON UPDATE RESTRICT
) ENGINE = InnoDB AUTO_INCREMENT = 1455 CHARACTER SET = utf8mb4 COLLATE = utf8mb4_unicode_ci ROW_FORMAT = Dynamic;

-- ----------------------------
-- Table structure for park_area
-- ----------------------------
DROP TABLE IF EXISTS `park_area`;
CREATE TABLE `park_area`  (
  `id` bigint(20) UNSIGNED NOT NULL AUTO_INCREMENT,
  `park_id` bigint(20) UNSIGNED NOT NULL COMMENT '外键，关联parks表id',
  `name` varchar(50) CHARACTER SET utf8mb4 COLLATE utf8mb4_unicode_ci NOT NULL COMMENT '区域名称',
  `code` varchar(255) CHARACTER SET utf8mb4 COLLATE utf8mb4_unicode_ci NOT NULL COMMENT '编号：如A,B',
  `floor` int(11) NOT NULL DEFAULT 1 COMMENT '所属楼层',
  `manufacturing_mode` tinyint(1) NOT NULL DEFAULT 0 COMMENT '0-无对接，1-道闸，2-道闸+室内导航，3-道闸+车位摄像头+室内导航',
  `parking_places_count` int(10) UNSIGNED NOT NULL DEFAULT 0 COMMENT '车位总数',
  `fixed_parking_places_count` int(10) UNSIGNED NOT NULL DEFAULT 0 COMMENT '固定车位数',
  `temp_parking_places_count` int(10) UNSIGNED NOT NULL DEFAULT 0 COMMENT '临时车位数',
  `deleted_at` timestamp(0) NULL DEFAULT NULL,
  `created_at` timestamp(0) NULL DEFAULT NULL,
  `updated_at` timestamp(0) NULL DEFAULT NULL,
  `attribute` int(11) NOT NULL COMMENT '区域属性，1-临停，2-固定，3-固定+临停',
  `status` tinyint(1) NOT NULL COMMENT '区域状态，1-启用，0-停用',
  `car_model` int(11) NOT NULL COMMENT '车型，1-小型车，2-中大型车，3-大型车，4-超大型车',
  `charging_pile_parking_places_count` int(10) UNSIGNED NOT NULL DEFAULT 0 COMMENT '充电桩车位数',
  `garage_height_limit` int(11) NOT NULL COMMENT '车库限高（cm)',
  `can_publish_spaces` tinyint(1) NOT NULL DEFAULT 1 COMMENT '是否允许发布出租车位，0-禁止，1-允许',
  `defaulted_at` timestamp(0) NULL DEFAULT NULL,
  PRIMARY KEY (`id`) USING BTREE,
  INDEX `park_area_park_id_foreign`(`park_id`) USING BTREE,
  CONSTRAINT `park_area_park_id_foreign` FOREIGN KEY (`park_id`) REFERENCES `parks` (`id`) ON DELETE CASCADE ON UPDATE RESTRICT
) ENGINE = InnoDB AUTO_INCREMENT = 3377 CHARACTER SET = utf8mb4 COLLATE = utf8mb4_unicode_ci ROW_FORMAT = Dynamic;

-- ----------------------------
-- Table structure for park_area_brands
-- ----------------------------
DROP TABLE IF EXISTS `park_area_brands`;
CREATE TABLE `park_area_brands`  (
  `park_area_id` bigint(20) UNSIGNED NOT NULL,
  `brand_id` bigint(20) UNSIGNED NOT NULL,
  INDEX `park_area_brands_park_area_id_foreign`(`park_area_id`) USING BTREE,
  INDEX `park_area_brands_brand_id_index`(`brand_id`) USING BTREE,
  CONSTRAINT `park_area_brands_park_area_id_foreign` FOREIGN KEY (`park_area_id`) REFERENCES `park_area` (`id`) ON DELETE CASCADE ON UPDATE RESTRICT
) ENGINE = InnoDB CHARACTER SET = utf8mb4 COLLATE = utf8mb4_unicode_ci ROW_FORMAT = Dynamic;

-- ----------------------------
-- Table structure for park_bill_summaries
-- ----------------------------
DROP TABLE IF EXISTS `park_bill_summaries`;
CREATE TABLE `park_bill_summaries`  (
  `id` bigint(20) UNSIGNED NOT NULL AUTO_INCREMENT,
  `park_id` bigint(20) UNSIGNED NOT NULL,
  `date` varchar(255) CHARACTER SET utf8mb4 COLLATE utf8mb4_unicode_ci NOT NULL COMMENT '汇总日期：Y-m-d/Y-m',
  `type` varchar(255) CHARACTER SET utf8mb4 COLLATE utf8mb4_unicode_ci NOT NULL DEFAULT 'day' COMMENT '账单日期类型:天:day,月:month,年:year',
  `bill_type` varchar(255) CHARACTER SET utf8mb4 COLLATE utf8mb4_unicode_ci NOT NULL COMMENT '业务类型:汇总...',
  `amount` int(10) UNSIGNED NOT NULL DEFAULT 0 COMMENT '总额',
  `income` int(10) UNSIGNED NOT NULL DEFAULT 0 COMMENT '收入',
  `expenses` int(10) UNSIGNED NOT NULL DEFAULT 0 COMMENT '支出',
  `deleted_at` timestamp(0) NULL DEFAULT NULL,
  `created_at` timestamp(0) NULL DEFAULT NULL,
  `updated_at` timestamp(0) NULL DEFAULT NULL,
  PRIMARY KEY (`id`) USING BTREE,
  INDEX `park_bill_summaries_park_id_foreign`(`park_id`) USING BTREE,
  CONSTRAINT `park_bill_summaries_park_id_foreign` FOREIGN KEY (`park_id`) REFERENCES `parks` (`id`) ON DELETE CASCADE ON UPDATE RESTRICT
) ENGINE = InnoDB AUTO_INCREMENT = 238 CHARACTER SET = utf8mb4 COLLATE = utf8mb4_unicode_ci ROW_FORMAT = Dynamic;

-- ----------------------------
-- Table structure for park_bluetooth
-- ----------------------------
DROP TABLE IF EXISTS `park_bluetooth`;
CREATE TABLE `park_bluetooth`  (
  `id` bigint(20) UNSIGNED NOT NULL AUTO_INCREMENT,
  `number` varchar(64) CHARACTER SET utf8mb4 COLLATE utf8mb4_unicode_ci NOT NULL COMMENT '蓝牙编号',
  `brand_id` bigint(20) UNSIGNED NULL DEFAULT NULL COMMENT '品牌',
  `brand_model_id` bigint(20) UNSIGNED NULL DEFAULT NULL COMMENT '型号',
  `park_id` bigint(20) UNSIGNED NOT NULL COMMENT '外键，关联parks表id',
  `park_area_id` bigint(20) UNSIGNED NOT NULL COMMENT '外键，关联park_area表id',
  `ip` varchar(48) CHARACTER SET utf8mb4 COLLATE utf8mb4_unicode_ci NOT NULL COMMENT 'ip地址',
  `protocol` varchar(50) CHARACTER SET utf8mb4 COLLATE utf8mb4_unicode_ci NOT NULL COMMENT '通信协议',
  `gateway` varchar(48) CHARACTER SET utf8mb4 COLLATE utf8mb4_unicode_ci NOT NULL COMMENT '网关',
  `electric` decimal(6, 2) NULL DEFAULT NULL COMMENT '电量（百分比）',
  `major` int(11) NOT NULL COMMENT '蓝牙的major',
  `minor` int(11) NOT NULL COMMENT '蓝牙的minor',
  `uuid` char(36) CHARACTER SET utf8mb4 COLLATE utf8mb4_unicode_ci NOT NULL,
  `status` tinyint(1) NOT NULL DEFAULT 0 COMMENT '蓝牙状态，1-开启，0-关闭',
  `network_status` tinyint(1) NOT NULL DEFAULT 0 COMMENT '网络状态，1-开启，0-关闭',
  `error` varchar(255) CHARACTER SET utf8mb4 COLLATE utf8mb4_unicode_ci NOT NULL DEFAULT '正常' COMMENT '故障信息',
  `remark` varchar(255) CHARACTER SET utf8mb4 COLLATE utf8mb4_unicode_ci NULL DEFAULT NULL COMMENT '备注',
  `deleted_at` timestamp(0) NULL DEFAULT NULL,
  `created_at` timestamp(0) NULL DEFAULT NULL,
  `updated_at` timestamp(0) NULL DEFAULT NULL,
  PRIMARY KEY (`id`) USING BTREE,
  INDEX `park_bluetooth_park_id_foreign`(`park_id`) USING BTREE,
  INDEX `park_bluetooth_park_area_id_foreign`(`park_area_id`) USING BTREE,
  INDEX `park_bluetooth_brand_id_index`(`brand_id`) USING BTREE,
  INDEX `park_bluetooth_brand_model_id_index`(`brand_model_id`) USING BTREE,
  CONSTRAINT `park_bluetooth_park_area_id_foreign` FOREIGN KEY (`park_area_id`) REFERENCES `park_area` (`id`) ON DELETE CASCADE ON UPDATE RESTRICT,
  CONSTRAINT `park_bluetooth_park_id_foreign` FOREIGN KEY (`park_id`) REFERENCES `parks` (`id`) ON DELETE CASCADE ON UPDATE RESTRICT
) ENGINE = InnoDB AUTO_INCREMENT = 134 CHARACTER SET = utf8mb4 COLLATE = utf8mb4_unicode_ci ROW_FORMAT = Dynamic;

-- ----------------------------
-- Table structure for park_camera_groups
-- ----------------------------
DROP TABLE IF EXISTS `park_camera_groups`;
CREATE TABLE `park_camera_groups`  (
  `id` bigint(20) UNSIGNED NOT NULL AUTO_INCREMENT,
  `name` varchar(255) CHARACTER SET utf8mb4 COLLATE utf8mb4_unicode_ci NOT NULL COMMENT '编组名称',
  `unique_id` varchar(255) CHARACTER SET utf8mb4 COLLATE utf8mb4_unicode_ci NOT NULL COMMENT '唯一id',
  `total_count` int(11) NOT NULL COMMENT '组内车位总数',
  `available_count` int(11) NOT NULL COMMENT '组内可用车位数',
  `is_active` tinyint(1) NOT NULL DEFAULT 1 COMMENT '启用状态，0-停用，1-启用',
  `park_id` bigint(20) UNSIGNED NOT NULL COMMENT '外键，关联parks表id',
  `park_area_id` bigint(20) UNSIGNED NOT NULL COMMENT '外键，关联park_area表id',
  `deleted_at` timestamp(0) NULL DEFAULT NULL,
  `created_at` timestamp(0) NULL DEFAULT NULL,
  `updated_at` timestamp(0) NULL DEFAULT NULL,
  PRIMARY KEY (`id`) USING BTREE,
  INDEX `park_camera_groups_park_id_foreign`(`park_id`) USING BTREE,
  INDEX `park_camera_groups_park_area_id_foreign`(`park_area_id`) USING BTREE,
  CONSTRAINT `park_camera_groups_park_area_id_foreign` FOREIGN KEY (`park_area_id`) REFERENCES `park_area` (`id`) ON DELETE CASCADE ON UPDATE RESTRICT,
  CONSTRAINT `park_camera_groups_park_id_foreign` FOREIGN KEY (`park_id`) REFERENCES `parks` (`id`) ON DELETE CASCADE ON UPDATE RESTRICT
) ENGINE = InnoDB AUTO_INCREMENT = 42 CHARACTER SET = utf8mb4 COLLATE = utf8mb4_unicode_ci ROW_FORMAT = Dynamic;

-- ----------------------------
-- Table structure for park_cameras
-- ----------------------------
DROP TABLE IF EXISTS `park_cameras`;
CREATE TABLE `park_cameras`  (
  `id` bigint(20) UNSIGNED NOT NULL AUTO_INCREMENT,
  `number` varchar(64) CHARACTER SET utf8mb4 COLLATE utf8mb4_unicode_ci NOT NULL COMMENT '地锁编号',
  `brand_id` bigint(20) UNSIGNED NULL DEFAULT NULL COMMENT '品牌',
  `brand_model_id` bigint(20) UNSIGNED NULL DEFAULT NULL COMMENT '型号',
  `ip` varchar(48) CHARACTER SET utf8mb4 COLLATE utf8mb4_unicode_ci NOT NULL COMMENT 'ip地址',
  `protocol` varchar(50) CHARACTER SET utf8mb4 COLLATE utf8mb4_unicode_ci NOT NULL COMMENT '通信协议',
  `gateway` varchar(48) CHARACTER SET utf8mb4 COLLATE utf8mb4_unicode_ci NOT NULL COMMENT '网关',
  `electric` decimal(6, 2) NULL DEFAULT NULL COMMENT '电量（百分比）',
  `status` tinyint(1) NOT NULL DEFAULT 0 COMMENT '摄像头状态，true表示开启，false表示关闭',
  `network_status` tinyint(1) NOT NULL DEFAULT 0 COMMENT '网络状态，true表示开启，false表示关闭',
  `error` varchar(255) CHARACTER SET utf8mb4 COLLATE utf8mb4_unicode_ci NOT NULL DEFAULT '正常' COMMENT '故障信息',
  `remark` varchar(255) CHARACTER SET utf8mb4 COLLATE utf8mb4_unicode_ci NULL DEFAULT NULL COMMENT '备注',
  `rank` int(11) NOT NULL DEFAULT 0 COMMENT '用于排序',
  `park_id` bigint(20) UNSIGNED NOT NULL COMMENT '外键，关联parks表id',
  `group_id` bigint(20) UNSIGNED NOT NULL DEFAULT 0 COMMENT '外键，关联park_camera_groups表id',
  `deleted_at` timestamp(0) NULL DEFAULT NULL,
  `created_at` timestamp(0) NULL DEFAULT NULL,
  `updated_at` timestamp(0) NULL DEFAULT NULL,
  `park_area_id` bigint(20) UNSIGNED NOT NULL COMMENT '外键，关联park_area表id',
  `monitor_type` tinyint(1) NOT NULL DEFAULT 1 COMMENT '监控类型，0-出入口，1-车位',
  PRIMARY KEY (`id`) USING BTREE,
  INDEX `park_cameras_park_id_foreign`(`park_id`) USING BTREE,
  INDEX `park_cameras_park_area_id_foreign`(`park_area_id`) USING BTREE,
  INDEX `park_cameras_brand_id_index`(`brand_id`) USING BTREE,
  INDEX `park_cameras_brand_model_id_index`(`brand_model_id`) USING BTREE,
  INDEX `park_cameras_group_id_index`(`group_id`) USING BTREE,
  CONSTRAINT `park_cameras_park_area_id_foreign` FOREIGN KEY (`park_area_id`) REFERENCES `park_area` (`id`) ON DELETE CASCADE ON UPDATE RESTRICT,
  CONSTRAINT `park_cameras_park_id_foreign` FOREIGN KEY (`park_id`) REFERENCES `parks` (`id`) ON DELETE CASCADE ON UPDATE RESTRICT
) ENGINE = InnoDB AUTO_INCREMENT = 160 CHARACTER SET = utf8mb4 COLLATE = utf8mb4_unicode_ci ROW_FORMAT = Dynamic;

-- ----------------------------
-- Table structure for park_gates
-- ----------------------------
DROP TABLE IF EXISTS `park_gates`;
CREATE TABLE `park_gates`  (
  `id` bigint(20) UNSIGNED NOT NULL AUTO_INCREMENT,
  `park_id` bigint(20) UNSIGNED NOT NULL,
  `programme` int(11) NOT NULL DEFAULT 0 COMMENT '控制方案：1-科拓云，2-杰停云，3-科拓场库',
  `brand` varchar(255) CHARACTER SET utf8mb4 COLLATE utf8mb4_unicode_ci NOT NULL COMMENT '道闸系统品牌',
  `version` varchar(255) CHARACTER SET utf8mb4 COLLATE utf8mb4_unicode_ci NOT NULL COMMENT '软件版本',
  `mode` int(11) NOT NULL DEFAULT 0 COMMENT '对接方式：1-云端转发，2-场库直发',
  `payment_mode` int(11) NOT NULL DEFAULT 0 COMMENT '停车费电子支付模式：1-场库自收，2-平台代收，3-预约订单平台代收，4-共享车位订单平台代收',
  `is_active` tinyint(1) NOT NULL COMMENT '状态：0-停用，1-启用',
  `deleted_at` timestamp(0) NULL DEFAULT NULL,
  `created_at` timestamp(0) NULL DEFAULT NULL,
  `updated_at` timestamp(0) NULL DEFAULT NULL,
  PRIMARY KEY (`id`) USING BTREE,
  INDEX `park_gates_park_id_foreign`(`park_id`) USING BTREE,
  CONSTRAINT `park_gates_park_id_foreign` FOREIGN KEY (`park_id`) REFERENCES `parks` (`id`) ON DELETE RESTRICT ON UPDATE RESTRICT
) ENGINE = InnoDB AUTO_INCREMENT = 19 CHARACTER SET = utf8mb4 COLLATE = utf8mb4_unicode_ci ROW_FORMAT = Dynamic;

-- ----------------------------
-- Table structure for park_map_spaces
-- ----------------------------
DROP TABLE IF EXISTS `park_map_spaces`;
CREATE TABLE `park_map_spaces`  (
  `id` bigint(20) UNSIGNED NOT NULL AUTO_INCREMENT,
  `unique_id` varchar(64) CHARACTER SET utf8mb4 COLLATE utf8mb4_unicode_ci NOT NULL COMMENT '唯一编号',
  `number` varchar(255) CHARACTER SET utf8mb4 COLLATE utf8mb4_unicode_ci NOT NULL COMMENT '车位编号',
  `quantity` tinyint(4) NOT NULL DEFAULT 1 COMMENT '车位数',
  `num` int(10) UNSIGNED NOT NULL DEFAULT 1 COMMENT '车位容量',
  `floor` tinyint(4) NOT NULL COMMENT '楼层',
  `area_code` varchar(255) CHARACTER SET utf8mb4 COLLATE utf8mb4_unicode_ci NOT NULL COMMENT '区域编号',
  `park_id` bigint(20) UNSIGNED NOT NULL COMMENT '关联parks表id',
  `park_area_id` bigint(20) UNSIGNED NOT NULL COMMENT '关联park_area表id',
  `created_at` timestamp(0) NULL DEFAULT NULL,
  `updated_at` timestamp(0) NULL DEFAULT NULL,
  PRIMARY KEY (`id`) USING BTREE,
  INDEX `park_map_spaces_park_id_foreign`(`park_id`) USING BTREE,
  INDEX `park_map_spaces_park_area_id_foreign`(`park_area_id`) USING BTREE,
  CONSTRAINT `park_map_spaces_park_area_id_foreign` FOREIGN KEY (`park_area_id`) REFERENCES `park_area` (`id`) ON DELETE CASCADE ON UPDATE RESTRICT,
  CONSTRAINT `park_map_spaces_park_id_foreign` FOREIGN KEY (`park_id`) REFERENCES `parks` (`id`) ON DELETE CASCADE ON UPDATE RESTRICT
) ENGINE = InnoDB AUTO_INCREMENT = 81 CHARACTER SET = utf8mb4 COLLATE = utf8mb4_unicode_ci ROW_FORMAT = Dynamic;

-- ----------------------------
-- Table structure for park_rate_rent_spaces
-- ----------------------------
DROP TABLE IF EXISTS `park_rate_rent_spaces`;
CREATE TABLE `park_rate_rent_spaces`  (
  `park_space_id` bigint(20) UNSIGNED NOT NULL,
  `park_rate_id` bigint(20) UNSIGNED NOT NULL,
  `car_rent_id` bigint(20) UNSIGNED NOT NULL,
  INDEX `park_space_id_park_rate_id_car_rent_id_index`(`park_space_id`, `park_rate_id`, `car_rent_id`) USING BTREE
) ENGINE = InnoDB CHARACTER SET = utf8mb4 COLLATE = utf8mb4_unicode_ci ROW_FORMAT = Dynamic;

-- ----------------------------
-- Table structure for park_rates
-- ----------------------------
DROP TABLE IF EXISTS `park_rates`;
CREATE TABLE `park_rates`  (
  `id` bigint(20) UNSIGNED NOT NULL AUTO_INCREMENT,
  `park_id` bigint(20) UNSIGNED NOT NULL,
  `no` varchar(255) CHARACTER SET utf8mb4 COLLATE utf8mb4_unicode_ci NOT NULL COMMENT '编号',
  `name` varchar(255) CHARACTER SET utf8mb4 COLLATE utf8mb4_unicode_ci NOT NULL COMMENT '费率名称',
  `is_workday` int(10) UNSIGNED NOT NULL COMMENT '时间类型，0-非工作日，1-工作日，2-全部',
  `start_period` int(10) UNSIGNED NOT NULL COMMENT '（每天）开始时间段',
  `end_period` int(10) UNSIGNED NOT NULL COMMENT '（每天）结束时间段',
  `down_payments` int(10) UNSIGNED NOT NULL COMMENT '首付金额（分）',
  `down_payments_time` int(10) UNSIGNED NOT NULL COMMENT '首付时长（分钟）',
  `time_unit` int(10) UNSIGNED NOT NULL COMMENT '单位时长（分钟）',
  `payments_per_unit` int(10) UNSIGNED NOT NULL COMMENT '单位金额（分）',
  `first_day_limit_payments` int(10) UNSIGNED NOT NULL COMMENT '24小时限额（分）',
  `is_active` tinyint(1) NOT NULL DEFAULT 1 COMMENT '启用状态，0-停用，1-启用',
  `parking_spaces_count` int(10) UNSIGNED NOT NULL COMMENT '车位数',
  `publisher_type` varchar(255) CHARACTER SET utf8mb4 COLLATE utf8mb4_unicode_ci NOT NULL,
  `publisher_id` bigint(20) UNSIGNED NOT NULL,
  `deleted_at` timestamp(0) NULL DEFAULT NULL,
  `created_at` timestamp(0) NULL DEFAULT NULL,
  `updated_at` timestamp(0) NULL DEFAULT NULL,
  `park_area_id` bigint(20) UNSIGNED NOT NULL,
  `type` tinyint(1) NOT NULL DEFAULT 0 COMMENT '费率类型：0-车场，1-区域，2-车位',
  PRIMARY KEY (`id`) USING BTREE,
  INDEX `park_rates_park_id_foreign`(`park_id`) USING BTREE,
  INDEX `park_rates_publisher_type_publisher_id_index`(`publisher_type`, `publisher_id`) USING BTREE,
  INDEX `park_rates_park_area_id_index`(`park_area_id`) USING BTREE,
  CONSTRAINT `park_rates_park_id_foreign` FOREIGN KEY (`park_id`) REFERENCES `parks` (`id`) ON DELETE CASCADE ON UPDATE RESTRICT
) ENGINE = InnoDB AUTO_INCREMENT = 90 CHARACTER SET = utf8mb4 COLLATE = utf8mb4_unicode_ci ROW_FORMAT = Dynamic;

-- ----------------------------
-- Table structure for park_service_callbacks
-- ----------------------------
DROP TABLE IF EXISTS `park_service_callbacks`;
CREATE TABLE `park_service_callbacks`  (
  `id` bigint(20) UNSIGNED NOT NULL AUTO_INCREMENT,
  `park_id` bigint(20) UNSIGNED NULL DEFAULT NULL,
  `url` varchar(255) CHARACTER SET utf8mb4 COLLATE utf8mb4_unicode_ci NULL DEFAULT NULL,
  `params` longtext CHARACTER SET utf8mb4 COLLATE utf8mb4_bin NULL,
  `result` longtext CHARACTER SET utf8mb4 COLLATE utf8mb4_bin NULL,
  `created_at` timestamp(0) NULL DEFAULT NULL,
  `updated_at` timestamp(0) NULL DEFAULT NULL,
  PRIMARY KEY (`id`) USING BTREE
) ENGINE = InnoDB AUTO_INCREMENT = 520597 CHARACTER SET = utf8mb4 COLLATE = utf8mb4_unicode_ci ROW_FORMAT = Dynamic;

-- ----------------------------
-- Table structure for park_services
-- ----------------------------
DROP TABLE IF EXISTS `park_services`;
CREATE TABLE `park_services`  (
  `id` bigint(20) UNSIGNED NOT NULL AUTO_INCREMENT,
  `park_id` bigint(20) UNSIGNED NOT NULL,
  `salesman_number` varchar(30) CHARACTER SET utf8mb4 COLLATE utf8mb4_unicode_ci NOT NULL COMMENT '业务员工号',
  `sales_name` varchar(20) CHARACTER SET utf8mb4 COLLATE utf8mb4_unicode_ci NOT NULL COMMENT '业务员姓名',
  `sales_phone` varchar(11) CHARACTER SET utf8mb4 COLLATE utf8mb4_unicode_ci NOT NULL COMMENT '业务员联系电话',
  `contract_no` varchar(30) CHARACTER SET utf8mb4 COLLATE utf8mb4_unicode_ci NOT NULL COMMENT '合同编号',
  `activation_code` varchar(255) CHARACTER SET utf8mb4 COLLATE utf8mb4_unicode_ci NOT NULL COMMENT '激活码',
  `contract_start_period` timestamp(0) NULL DEFAULT NULL COMMENT '合同开始日期',
  `contract_end_period` timestamp(0) NULL DEFAULT NULL COMMENT '合同结束日期',
  `contract_period` timestamp(0) NULL DEFAULT NULL COMMENT '合同期限',
  `created_at` timestamp(0) NULL DEFAULT NULL,
  `updated_at` timestamp(0) NULL DEFAULT NULL,
  PRIMARY KEY (`id`) USING BTREE,
  INDEX `park_services_park_id_foreign`(`park_id`) USING BTREE,
  CONSTRAINT `park_services_park_id_foreign` FOREIGN KEY (`park_id`) REFERENCES `parks` (`id`) ON DELETE CASCADE ON UPDATE RESTRICT
) ENGINE = InnoDB AUTO_INCREMENT = 30 CHARACTER SET = utf8mb4 COLLATE = utf8mb4_unicode_ci ROW_FORMAT = Dynamic;

-- ----------------------------
-- Table structure for park_settings
-- ----------------------------
DROP TABLE IF EXISTS `park_settings`;
CREATE TABLE `park_settings`  (
  `id` bigint(20) UNSIGNED NOT NULL AUTO_INCREMENT,
  `park_id` bigint(20) UNSIGNED NOT NULL,
  `map_id` varchar(255) CHARACTER SET utf8mb4 COLLATE utf8mb4_unicode_ci NULL DEFAULT NULL,
  `map_key` varchar(255) CHARACTER SET utf8mb4 COLLATE utf8mb4_unicode_ci NULL DEFAULT NULL,
  `map_find_car_url` varchar(255) CHARACTER SET utf8mb4 COLLATE utf8mb4_unicode_ci NULL DEFAULT NULL,
  `map_find_parking_url` varchar(255) CHARACTER SET utf8mb4 COLLATE utf8mb4_unicode_ci NULL DEFAULT NULL,
  `request_url` varchar(255) CHARACTER SET utf8mb4 COLLATE utf8mb4_unicode_ci NULL DEFAULT NULL COMMENT '停车场接口地址',
  `params` longtext CHARACTER SET utf8mb4 COLLATE utf8mb4_bin NULL,
  `callback_url` varchar(255) CHARACTER SET utf8mb4 COLLATE utf8mb4_unicode_ci NULL DEFAULT NULL COMMENT '停车场数据接收地址',
  `created_at` timestamp(0) NULL DEFAULT NULL,
  `updated_at` timestamp(0) NULL DEFAULT NULL,
  PRIMARY KEY (`id`) USING BTREE,
  INDEX `park_settings_park_id_foreign`(`park_id`) USING BTREE,
  CONSTRAINT `park_settings_park_id_foreign` FOREIGN KEY (`park_id`) REFERENCES `parks` (`id`) ON DELETE CASCADE ON UPDATE RESTRICT
) ENGINE = InnoDB AUTO_INCREMENT = 6 CHARACTER SET = utf8mb4 COLLATE = utf8mb4_unicode_ci ROW_FORMAT = Dynamic;

-- ----------------------------
-- Table structure for park_space_has_devices
-- ----------------------------
DROP TABLE IF EXISTS `park_space_has_devices`;
CREATE TABLE `park_space_has_devices`  (
  `park_space_id` bigint(20) UNSIGNED NOT NULL,
  `device_type` varchar(255) CHARACTER SET utf8mb4 COLLATE utf8mb4_unicode_ci NOT NULL,
  `device_id` bigint(20) UNSIGNED NOT NULL,
  INDEX `park_space_has_devices_park_space_id_foreign`(`park_space_id`) USING BTREE,
  INDEX `park_space_has_devices_device_type_device_id_index`(`device_type`, `device_id`) USING BTREE,
  CONSTRAINT `park_space_has_devices_park_space_id_foreign` FOREIGN KEY (`park_space_id`) REFERENCES `park_spaces` (`id`) ON DELETE CASCADE ON UPDATE RESTRICT
) ENGINE = InnoDB CHARACTER SET = utf8mb4 COLLATE = utf8mb4_unicode_ci ROW_FORMAT = Dynamic;

-- ----------------------------
-- Table structure for park_space_locks
-- ----------------------------
DROP TABLE IF EXISTS `park_space_locks`;
CREATE TABLE `park_space_locks`  (
  `id` bigint(20) UNSIGNED NOT NULL AUTO_INCREMENT,
  `park_space_id` bigint(20) UNSIGNED NULL DEFAULT NULL,
  `park_id` bigint(20) UNSIGNED NOT NULL COMMENT '外键，关联parks表id',
  `park_area_id` bigint(20) UNSIGNED NOT NULL COMMENT '外键，关联park_area表id',
  `number` varchar(50) CHARACTER SET utf8mb4 COLLATE utf8mb4_unicode_ci NOT NULL COMMENT '地锁名称',
  `brand_id` bigint(20) UNSIGNED NULL DEFAULT NULL COMMENT '品牌',
  `brand_model_id` bigint(20) UNSIGNED NULL DEFAULT NULL COMMENT '型号',
  `ip` varchar(48) CHARACTER SET utf8mb4 COLLATE utf8mb4_unicode_ci NOT NULL COMMENT 'ip地址',
  `protocol` varchar(50) CHARACTER SET utf8mb4 COLLATE utf8mb4_unicode_ci NOT NULL COMMENT '通信协议',
  `gateway` varchar(48) CHARACTER SET utf8mb4 COLLATE utf8mb4_unicode_ci NOT NULL COMMENT '网关',
  `electric` decimal(6, 2) NULL DEFAULT NULL COMMENT '电量（百分比）',
  `status` tinyint(1) NOT NULL DEFAULT 0 COMMENT '蓝牙状态，1-开启，0-关闭',
  `network_status` tinyint(1) NOT NULL DEFAULT 0 COMMENT '网络状态，1-开启，0-关闭',
  `error` varchar(255) CHARACTER SET utf8mb4 COLLATE utf8mb4_unicode_ci NOT NULL DEFAULT '正常' COMMENT '故障信息',
  `remark` varchar(255) CHARACTER SET utf8mb4 COLLATE utf8mb4_unicode_ci NULL DEFAULT NULL COMMENT '备注',
  `deleted_at` timestamp(0) NULL DEFAULT NULL,
  `created_at` timestamp(0) NULL DEFAULT NULL,
  `updated_at` timestamp(0) NULL DEFAULT NULL,
  PRIMARY KEY (`id`) USING BTREE,
  INDEX `park_space_locks_park_id_foreign`(`park_id`) USING BTREE,
  INDEX `park_space_locks_park_area_id_foreign`(`park_area_id`) USING BTREE,
  INDEX `park_space_locks_brand_id_index`(`brand_id`) USING BTREE,
  INDEX `park_space_locks_brand_model_id_index`(`brand_model_id`) USING BTREE,
  INDEX `park_space_locks_park_space_id_index`(`park_space_id`) USING BTREE,
  CONSTRAINT `park_space_locks_park_area_id_foreign` FOREIGN KEY (`park_area_id`) REFERENCES `park_area` (`id`) ON DELETE CASCADE ON UPDATE RESTRICT,
  CONSTRAINT `park_space_locks_park_id_foreign` FOREIGN KEY (`park_id`) REFERENCES `parks` (`id`) ON DELETE CASCADE ON UPDATE RESTRICT
) ENGINE = InnoDB AUTO_INCREMENT = 269 CHARACTER SET = utf8mb4 COLLATE = utf8mb4_unicode_ci ROW_FORMAT = Dynamic;

-- ----------------------------
-- Table structure for park_spaces
-- ----------------------------
DROP TABLE IF EXISTS `park_spaces`;
CREATE TABLE `park_spaces`  (
  `id` bigint(20) UNSIGNED NOT NULL AUTO_INCREMENT,
  `park_id` bigint(20) UNSIGNED NOT NULL,
  `park_area_id` bigint(20) UNSIGNED NOT NULL,
  `number` varchar(255) CHARACTER SET utf8mb4 COLLATE utf8mb4_unicode_ci NOT NULL COMMENT '车位编号',
  `device_unique_id` varchar(255) CHARACTER SET utf8mb4 COLLATE utf8mb4_unicode_ci NULL DEFAULT NULL COMMENT '设备方提供的唯一编号',
  `map_unique_id` varchar(255) CHARACTER SET utf8mb4 COLLATE utf8mb4_unicode_ci NOT NULL COMMENT '唯一编号',
  `area_code` varchar(255) CHARACTER SET utf8mb4 COLLATE utf8mb4_unicode_ci NULL DEFAULT NULL COMMENT '区域编号',
  `longitude` varchar(255) CHARACTER SET utf8mb4 COLLATE utf8mb4_unicode_ci NULL DEFAULT NULL,
  `latitude` varchar(255) CHARACTER SET utf8mb4 COLLATE utf8mb4_unicode_ci NULL DEFAULT NULL,
  `type` tinyint(1) NOT NULL DEFAULT 0 COMMENT '车位类型，0-临停+固定，1-固定，2-临停',
  `category` tinyint(1) NOT NULL DEFAULT 0 COMMENT '车位类别: 0小轿车，1充电桩车位，2代步车，3长厢车',
  `rent_type` int(11) NOT NULL COMMENT '出租类型，0-临时，1-长租，2-不可出租',
  `is_reserved_type` tinyint(1) NOT NULL COMMENT '是否可以预约，0-不能预约，1-可以预约',
  `status` int(11) NOT NULL DEFAULT 0 COMMENT '车位状态，0-未发布，1-已发布，2-停用，3-预约中,\n                4-已预约，5-已停车',
  `floor` int(11) NOT NULL DEFAULT 1 COMMENT '冗余区域floor',
  `remark` varchar(255) CHARACTER SET utf8mb4 COLLATE utf8mb4_unicode_ci NULL DEFAULT NULL COMMENT '备注',
  `is_stop` tinyint(1) NOT NULL DEFAULT 0 COMMENT '车位上是否有车',
  `stop_id` bigint(20) UNSIGNED NULL DEFAULT NULL,
  `car_num` varchar(255) CHARACTER SET utf8mb4 COLLATE utf8mb4_unicode_ci NULL DEFAULT NULL COMMENT '车牌号',
  `pic` varchar(255) CHARACTER SET utf8mb4 COLLATE utf8mb4_unicode_ci NULL DEFAULT NULL COMMENT '车位照片',
  `deleted_at` timestamp(0) NULL DEFAULT NULL,
  `created_at` timestamp(0) NULL DEFAULT NULL,
  `updated_at` timestamp(0) NULL DEFAULT NULL,
  PRIMARY KEY (`id`) USING BTREE,
  INDEX `park_spaces_park_area_id_foreign`(`park_area_id`) USING BTREE,
  INDEX `park_spaces_park_id_index`(`park_id`) USING BTREE,
  INDEX `park_spaces_stop_id_index`(`stop_id`) USING BTREE,
  CONSTRAINT `park_spaces_park_area_id_foreign` FOREIGN KEY (`park_area_id`) REFERENCES `park_area` (`id`) ON DELETE CASCADE ON UPDATE RESTRICT
) ENGINE = InnoDB AUTO_INCREMENT = 2220 CHARACTER SET = utf8mb4 COLLATE = utf8mb4_unicode_ci ROW_FORMAT = Dynamic;

-- ----------------------------
-- Table structure for park_stalls
-- ----------------------------
DROP TABLE IF EXISTS `park_stalls`;
CREATE TABLE `park_stalls`  (
  `id` bigint(20) UNSIGNED NOT NULL AUTO_INCREMENT,
  `park_id` bigint(20) UNSIGNED NOT NULL,
  `carport_count` int(11) NOT NULL COMMENT '总车位数',
  `fixed_carport_count` int(11) NOT NULL COMMENT '长租车位数',
  `charging_pile_carport` int(11) NOT NULL COMMENT '充电桩车位',
  `order_carport` int(11) NOT NULL COMMENT '预约车位',
  `temporary_carport_count` int(11) NOT NULL COMMENT '临停车位数',
  `lanes_count` int(11) NOT NULL COMMENT '总车道数',
  `reserved_spaces_count` int(10) UNSIGNED NOT NULL DEFAULT 0 COMMENT '空余位',
  `free_time` int(10) UNSIGNED NOT NULL DEFAULT 0 COMMENT '免费时长（分钟）',
  `expect_temporary_parking_count` int(11) NOT NULL COMMENT '预计日临停量',
  `park_operation_time` timestamp(0) NULL DEFAULT NULL COMMENT '停车场运营时间',
  `do_business_time` varchar(255) CHARACTER SET utf8mb4 COLLATE utf8mb4_unicode_ci NULL DEFAULT NULL COMMENT '营业时间',
  `fee_string` varchar(255) CHARACTER SET utf8mb4 COLLATE utf8mb4_unicode_ci NOT NULL COMMENT '文字版费率',
  `map_fee` varchar(255) CHARACTER SET utf8mb4 COLLATE utf8mb4_unicode_ci NULL DEFAULT NULL COMMENT '地图费率',
  `created_at` timestamp(0) NULL DEFAULT NULL,
  `updated_at` timestamp(0) NULL DEFAULT NULL,
  PRIMARY KEY (`id`) USING BTREE,
  INDEX `park_stalls_park_id_foreign`(`park_id`) USING BTREE,
  CONSTRAINT `park_stalls_park_id_foreign` FOREIGN KEY (`park_id`) REFERENCES `parks` (`id`) ON DELETE CASCADE ON UPDATE RESTRICT
) ENGINE = InnoDB AUTO_INCREMENT = 3331 CHARACTER SET = utf8mb4 COLLATE = utf8mb4_unicode_ci ROW_FORMAT = Dynamic;

-- ----------------------------
-- Table structure for park_synchronize_logs
-- ----------------------------
DROP TABLE IF EXISTS `park_synchronize_logs`;
CREATE TABLE `park_synchronize_logs`  (
  `id` bigint(20) UNSIGNED NOT NULL AUTO_INCREMENT,
  `park_number` varchar(255) CHARACTER SET utf8mb4 COLLATE utf8mb4_unicode_ci NULL DEFAULT NULL,
  `gateway` varchar(255) CHARACTER SET utf8mb4 COLLATE utf8mb4_unicode_ci NOT NULL,
  `result` longtext CHARACTER SET utf8mb4 COLLATE utf8mb4_bin NULL,
  `created_at` timestamp(0) NULL DEFAULT NULL,
  `updated_at` timestamp(0) NULL DEFAULT NULL,
  PRIMARY KEY (`id`) USING BTREE
) ENGINE = InnoDB AUTO_INCREMENT = 1302 CHARACTER SET = utf8mb4 COLLATE = utf8mb4_unicode_ci ROW_FORMAT = Dynamic;

-- ----------------------------
-- Table structure for park_virtual_spaces
-- ----------------------------
DROP TABLE IF EXISTS `park_virtual_spaces`;
CREATE TABLE `park_virtual_spaces`  (
  `id` bigint(20) UNSIGNED NOT NULL AUTO_INCREMENT,
  `code` varchar(255) CHARACTER SET utf8mb4 COLLATE utf8mb4_unicode_ci NOT NULL COMMENT '唯一编号',
  `number` varchar(255) CHARACTER SET utf8mb4 COLLATE utf8mb4_unicode_ci NOT NULL COMMENT '车位编号',
  `pic` varchar(255) CHARACTER SET utf8mb4 COLLATE utf8mb4_unicode_ci NULL DEFAULT NULL COMMENT '车位图片',
  `floor` int(11) NOT NULL DEFAULT 1 COMMENT '冗余区域floor',
  `is_stop` tinyint(1) NOT NULL DEFAULT 0 COMMENT '车位上是否停车，0-无车，1-有车',
  `stop_id` bigint(20) UNSIGNED NULL DEFAULT NULL,
  `car_num` varchar(255) CHARACTER SET utf8mb4 COLLATE utf8mb4_unicode_ci NULL DEFAULT NULL COMMENT '车牌号',
  `park_camera_id` int(10) UNSIGNED NOT NULL COMMENT '外键，关联park_cameras表id',
  `park_space_id` bigint(20) UNSIGNED NULL DEFAULT NULL COMMENT '外键，关联park_spaces表id',
  `park_area_id` bigint(20) UNSIGNED NOT NULL COMMENT '外键，关联park_area表id',
  `park_id` bigint(20) UNSIGNED NOT NULL COMMENT '外键，关联parks表id',
  `deleted_at` timestamp(0) NULL DEFAULT NULL,
  `created_at` timestamp(0) NULL DEFAULT NULL,
  `updated_at` timestamp(0) NULL DEFAULT NULL,
  PRIMARY KEY (`id`) USING BTREE,
  INDEX `park_virtual_spaces_park_space_id_foreign`(`park_space_id`) USING BTREE,
  INDEX `park_virtual_spaces_park_area_id_foreign`(`park_area_id`) USING BTREE,
  INDEX `park_virtual_spaces_park_id_foreign`(`park_id`) USING BTREE,
  INDEX `park_virtual_spaces_stop_id_index`(`stop_id`) USING BTREE,
  INDEX `park_virtual_spaces_park_camera_id_index`(`park_camera_id`) USING BTREE,
  CONSTRAINT `park_virtual_spaces_park_area_id_foreign` FOREIGN KEY (`park_area_id`) REFERENCES `park_area` (`id`) ON DELETE CASCADE ON UPDATE RESTRICT,
  CONSTRAINT `park_virtual_spaces_park_id_foreign` FOREIGN KEY (`park_id`) REFERENCES `parks` (`id`) ON DELETE CASCADE ON UPDATE RESTRICT,
  CONSTRAINT `park_virtual_spaces_park_space_id_foreign` FOREIGN KEY (`park_space_id`) REFERENCES `park_spaces` (`id`) ON DELETE CASCADE ON UPDATE RESTRICT
) ENGINE = InnoDB AUTO_INCREMENT = 118 CHARACTER SET = utf8mb4 COLLATE = utf8mb4_unicode_ci ROW_FORMAT = Dynamic;

-- ----------------------------
-- Table structure for park_wallet_balances
-- ----------------------------
DROP TABLE IF EXISTS `park_wallet_balances`;
CREATE TABLE `park_wallet_balances`  (
  `id` bigint(20) UNSIGNED NOT NULL AUTO_INCREMENT,
  `park_id` bigint(20) UNSIGNED NOT NULL,
  `amount` int(10) UNSIGNED NOT NULL DEFAULT 0,
  `type` varchar(255) CHARACTER SET utf8mb4 COLLATE utf8mb4_unicode_ci NOT NULL COMMENT '费用类型：停车费、预约费、提现',
  `trade_type` tinyint(4) NOT NULL COMMENT '交易方式:1收入,2支出',
  `balance` int(10) UNSIGNED NOT NULL DEFAULT 0 COMMENT '余额',
  `order_no` varchar(255) CHARACTER SET utf8mb4 COLLATE utf8mb4_unicode_ci NULL DEFAULT NULL COMMENT '订单交易号',
  `order_type` varchar(255) CHARACTER SET utf8mb4 COLLATE utf8mb4_unicode_ci NULL DEFAULT NULL,
  `order_id` bigint(20) UNSIGNED NULL DEFAULT NULL,
  `deleted_at` timestamp(0) NULL DEFAULT NULL,
  `created_at` timestamp(0) NULL DEFAULT NULL,
  `updated_at` timestamp(0) NULL DEFAULT NULL,
  PRIMARY KEY (`id`) USING BTREE,
  INDEX `park_wallet_balances_park_id_foreign`(`park_id`) USING BTREE,
  INDEX `park_wallet_balances_order_type_order_id_index`(`order_type`, `order_id`) USING BTREE,
  CONSTRAINT `park_wallet_balances_park_id_foreign` FOREIGN KEY (`park_id`) REFERENCES `parks` (`id`) ON DELETE CASCADE ON UPDATE RESTRICT
) ENGINE = InnoDB AUTO_INCREMENT = 498 CHARACTER SET = utf8mb4 COLLATE = utf8mb4_unicode_ci ROW_FORMAT = Dynamic;

-- ----------------------------
-- Table structure for park_wallets
-- ----------------------------
DROP TABLE IF EXISTS `park_wallets`;
CREATE TABLE `park_wallets`  (
  `id` bigint(20) UNSIGNED NOT NULL AUTO_INCREMENT,
  `park_id` bigint(20) UNSIGNED NOT NULL,
  `amount` int(10) UNSIGNED NOT NULL DEFAULT 0 COMMENT '总金额:预约费+停车费',
  `reserve_fee` int(10) UNSIGNED NOT NULL DEFAULT 0 COMMENT '预约费',
  `parking_fee` int(10) UNSIGNED NOT NULL DEFAULT 0 COMMENT '停车费',
  `withdrawal` int(10) UNSIGNED NOT NULL DEFAULT 0 COMMENT '提现总金额',
  `deleted_at` timestamp(0) NULL DEFAULT NULL,
  `created_at` timestamp(0) NULL DEFAULT NULL,
  `updated_at` timestamp(0) NULL DEFAULT NULL,
  PRIMARY KEY (`id`) USING BTREE,
  INDEX `park_wallets_park_id_foreign`(`park_id`) USING BTREE,
  CONSTRAINT `park_wallets_park_id_foreign` FOREIGN KEY (`park_id`) REFERENCES `parks` (`id`) ON DELETE CASCADE ON UPDATE RESTRICT
) ENGINE = InnoDB AUTO_INCREMENT = 6 CHARACTER SET = utf8mb4 COLLATE = utf8mb4_unicode_ci ROW_FORMAT = Dynamic;

-- ----------------------------
-- Table structure for parking_fees
-- ----------------------------
DROP TABLE IF EXISTS `parking_fees`;
CREATE TABLE `parking_fees`  (
  `id` bigint(20) UNSIGNED NOT NULL AUTO_INCREMENT,
  `park_id` bigint(20) UNSIGNED NOT NULL,
  `fee` double(8, 2) NOT NULL COMMENT '结算费率百分比',
  `user_id` bigint(20) UNSIGNED NOT NULL COMMENT '设置人员',
  `deleted_at` timestamp(0) NULL DEFAULT NULL,
  `created_at` timestamp(0) NULL DEFAULT NULL,
  `updated_at` timestamp(0) NULL DEFAULT NULL,
  PRIMARY KEY (`id`) USING BTREE,
  INDEX `parking_fees_park_id_index`(`park_id`) USING BTREE
) ENGINE = InnoDB AUTO_INCREMENT = 6 CHARACTER SET = utf8mb4 COLLATE = utf8mb4_unicode_ci ROW_FORMAT = Dynamic;

-- ----------------------------
-- Table structure for parking_lot_open_applies
-- ----------------------------
DROP TABLE IF EXISTS `parking_lot_open_applies`;
CREATE TABLE `parking_lot_open_applies`  (
  `id` bigint(20) UNSIGNED NOT NULL AUTO_INCREMENT,
  `user_id` bigint(20) UNSIGNED NULL DEFAULT NULL,
  `nickname` varchar(255) CHARACTER SET utf8mb4 COLLATE utf8mb4_unicode_ci NOT NULL,
  `telephone` varchar(255) CHARACTER SET utf8mb4 COLLATE utf8mb4_unicode_ci NOT NULL,
  `village_name` varchar(255) CHARACTER SET utf8mb4 COLLATE utf8mb4_unicode_ci NOT NULL COMMENT '小区名称',
  `village_province` varchar(255) CHARACTER SET utf8mb4 COLLATE utf8mb4_unicode_ci NOT NULL COMMENT '省',
  `village_city` varchar(255) CHARACTER SET utf8mb4 COLLATE utf8mb4_unicode_ci NOT NULL COMMENT '市',
  `village_country` varchar(255) CHARACTER SET utf8mb4 COLLATE utf8mb4_unicode_ci NOT NULL COMMENT '区',
  `village_address` varchar(255) CHARACTER SET utf8mb4 COLLATE utf8mb4_unicode_ci NOT NULL COMMENT '详细地址',
  `village_telephone` varchar(255) CHARACTER SET utf8mb4 COLLATE utf8mb4_unicode_ci NULL DEFAULT NULL COMMENT '小区联系方式',
  `longitude` varchar(255) CHARACTER SET utf8mb4 COLLATE utf8mb4_unicode_ci NULL DEFAULT NULL,
  `latitude` varchar(255) CHARACTER SET utf8mb4 COLLATE utf8mb4_unicode_ci NULL DEFAULT NULL,
  `park_id` bigint(20) UNSIGNED NULL DEFAULT NULL,
  `status` varchar(255) CHARACTER SET utf8mb4 COLLATE utf8mb4_unicode_ci NOT NULL DEFAULT 'pending' COMMENT '状态',
  `remark` text CHARACTER SET utf8mb4 COLLATE utf8mb4_unicode_ci NULL,
  `admin_id` bigint(20) UNSIGNED NULL DEFAULT NULL,
  `processed_at` timestamp(0) NULL DEFAULT NULL COMMENT '受理时间',
  `finished_at` timestamp(0) NULL DEFAULT NULL,
  `deleted_at` timestamp(0) NULL DEFAULT NULL,
  `created_at` timestamp(0) NULL DEFAULT NULL,
  `updated_at` timestamp(0) NULL DEFAULT NULL,
  PRIMARY KEY (`id`) USING BTREE,
  INDEX `parking_lot_open_applies_user_id_index`(`user_id`) USING BTREE,
  INDEX `parking_lot_open_applies_park_id_index`(`park_id`) USING BTREE,
  INDEX `parking_lot_open_applies_admin_id_index`(`admin_id`) USING BTREE
) ENGINE = InnoDB AUTO_INCREMENT = 7 CHARACTER SET = utf8mb4 COLLATE = utf8mb4_unicode_ci ROW_FORMAT = Dynamic;

-- ----------------------------
-- Table structure for parking_space_rental_bills
-- ----------------------------
DROP TABLE IF EXISTS `parking_space_rental_bills`;
CREATE TABLE `parking_space_rental_bills`  (
  `id` bigint(20) UNSIGNED NOT NULL AUTO_INCREMENT,
  `user_id` bigint(20) UNSIGNED NOT NULL,
  `park_id` bigint(20) UNSIGNED NULL DEFAULT NULL,
  `no` varchar(255) CHARACTER SET utf8mb4 COLLATE utf8mb4_unicode_ci NOT NULL,
  `body` varchar(255) CHARACTER SET utf8mb4 COLLATE utf8mb4_unicode_ci NOT NULL,
  `amount` int(10) UNSIGNED NOT NULL,
  `fee` int(10) UNSIGNED NOT NULL DEFAULT 0 COMMENT '手续费',
  `rental_amount` int(10) UNSIGNED NOT NULL COMMENT '更新后的金额',
  `type` tinyint(1) NOT NULL DEFAULT 0 COMMENT '增加，减少',
  `order_type` varchar(255) CHARACTER SET utf8mb4 COLLATE utf8mb4_unicode_ci NOT NULL,
  `order_id` bigint(20) UNSIGNED NOT NULL,
  `deleted_at` timestamp(0) NULL DEFAULT NULL,
  `created_at` timestamp(0) NULL DEFAULT NULL,
  `updated_at` timestamp(0) NULL DEFAULT NULL,
  PRIMARY KEY (`id`) USING BTREE,
  INDEX `parking_space_rental_bills_order_type_order_id_index`(`order_type`, `order_id`) USING BTREE,
  INDEX `parking_space_rental_bills_user_id_index`(`user_id`) USING BTREE,
  INDEX `parking_space_rental_bills_park_id_foreign`(`park_id`) USING BTREE,
  CONSTRAINT `parking_space_rental_bills_park_id_foreign` FOREIGN KEY (`park_id`) REFERENCES `parks` (`id`) ON DELETE CASCADE ON UPDATE RESTRICT
) ENGINE = InnoDB AUTO_INCREMENT = 303 CHARACTER SET = utf8mb4 COLLATE = utf8mb4_unicode_ci ROW_FORMAT = Dynamic;

-- ----------------------------
-- Table structure for parking_space_rental_records
-- ----------------------------
DROP TABLE IF EXISTS `parking_space_rental_records`;
CREATE TABLE `parking_space_rental_records`  (
  `id` bigint(20) UNSIGNED NOT NULL AUTO_INCREMENT,
  `rental_user_id` bigint(20) UNSIGNED NOT NULL,
  `rental_user_type` varchar(255) CHARACTER SET utf8mb4 COLLATE utf8mb4_unicode_ci NOT NULL,
  `user_id` bigint(20) UNSIGNED NOT NULL,
  `car_rent_id` bigint(20) UNSIGNED NOT NULL,
  `car_apt_id` bigint(20) UNSIGNED NOT NULL,
  `user_car_id` bigint(20) UNSIGNED NULL DEFAULT NULL COMMENT '已租车位用户车辆表的id',
  `stop_id` bigint(20) UNSIGNED NULL DEFAULT NULL COMMENT '停车记录',
  `rent_time` int(10) UNSIGNED NOT NULL COMMENT '已租时长',
  `amount` int(10) UNSIGNED NOT NULL COMMENT '总金额',
  `subscribe_amount` int(10) UNSIGNED NOT NULL DEFAULT 0 COMMENT '预约费',
  `stop_amount` int(10) UNSIGNED NOT NULL DEFAULT 0 COMMENT '停车费',
  `status` varchar(255) CHARACTER SET utf8mb4 COLLATE utf8mb4_unicode_ci NOT NULL DEFAULT 'pending' COMMENT '状态：取决于订单状态，仅保留 进行中、已完成2种',
  `expect_amount` int(10) UNSIGNED NOT NULL DEFAULT 0 COMMENT '预期金额',
  `fee` int(10) UNSIGNED NOT NULL COMMENT '手续费',
  `start_time` timestamp(0) NULL DEFAULT NULL COMMENT '开始时间',
  `end_time` timestamp(0) NULL DEFAULT NULL COMMENT '结束时间',
  `subscribe_end_time` timestamp(0) NULL DEFAULT NULL COMMENT '预约结束时间',
  `finished_at` timestamp(0) NULL DEFAULT NULL COMMENT '完成时间',
  `deleted_at` timestamp(0) NULL DEFAULT NULL,
  `created_at` timestamp(0) NULL DEFAULT NULL,
  `updated_at` timestamp(0) NULL DEFAULT NULL,
  PRIMARY KEY (`id`) USING BTREE,
  INDEX `parking_space_rental_records_rental_user_id_index`(`rental_user_id`) USING BTREE,
  INDEX `parking_space_rental_records_user_id_index`(`user_id`) USING BTREE,
  INDEX `parking_space_rental_records_car_rent_id_index`(`car_rent_id`) USING BTREE,
  INDEX `parking_space_rental_records_stop_id_foreign`(`stop_id`) USING BTREE,
  INDEX `car_apt_id`(`car_apt_id`) USING BTREE,
  CONSTRAINT `car_apt_id` FOREIGN KEY (`car_apt_id`) REFERENCES `car_apts` (`id`) ON DELETE CASCADE ON UPDATE RESTRICT,
  CONSTRAINT `parking_space_rental_records_stop_id_foreign` FOREIGN KEY (`stop_id`) REFERENCES `car_stops` (`id`) ON DELETE CASCADE ON UPDATE RESTRICT
) ENGINE = InnoDB AUTO_INCREMENT = 395 CHARACTER SET = utf8mb4 COLLATE = utf8mb4_unicode_ci ROW_FORMAT = Dynamic;

-- ----------------------------
-- Table structure for parks
-- ----------------------------
DROP TABLE IF EXISTS `parks`;
CREATE TABLE `parks`  (
  `id` bigint(20) UNSIGNED NOT NULL AUTO_INCREMENT,
  `project_name` varchar(255) CHARACTER SET utf8mb4 COLLATE utf8mb4_unicode_ci NOT NULL COMMENT '项目名称',
  `park_name` varchar(255) CHARACTER SET utf8mb4 COLLATE utf8mb4_unicode_ci NOT NULL COMMENT '停车场名称',
  `park_number` varchar(32) CHARACTER SET utf8mb4 COLLATE utf8mb4_unicode_ci NULL DEFAULT NULL COMMENT '停车场编号',
  `unique_code` varchar(255) CHARACTER SET utf8mb4 COLLATE utf8mb4_unicode_ci NOT NULL COMMENT '停车场唯一编号',
  `company` varchar(255) CHARACTER SET utf8mb4 COLLATE utf8mb4_unicode_ci NULL DEFAULT NULL COMMENT '公司',
  `property_id` bigint(20) UNSIGNED NULL DEFAULT NULL COMMENT '公司名称',
  `project_group_id` bigint(20) UNSIGNED NULL DEFAULT NULL COMMENT '集团名称',
  `park_province` varchar(20) CHARACTER SET utf8mb4 COLLATE utf8mb4_unicode_ci NOT NULL COMMENT '停车场所在省',
  `park_city` varchar(20) CHARACTER SET utf8mb4 COLLATE utf8mb4_unicode_ci NOT NULL COMMENT '停车场所在市',
  `park_area` varchar(20) CHARACTER SET utf8mb4 COLLATE utf8mb4_unicode_ci NOT NULL DEFAULT '' COMMENT '停车场所在区',
  `project_address` varchar(255) CHARACTER SET utf8mb4 COLLATE utf8mb4_unicode_ci NOT NULL COMMENT '项目地址',
  `longitude` varchar(255) CHARACTER SET utf8mb4 COLLATE utf8mb4_unicode_ci NULL DEFAULT NULL COMMENT '经度',
  `latitude` varchar(255) CHARACTER SET utf8mb4 COLLATE utf8mb4_unicode_ci NULL DEFAULT NULL COMMENT '纬度',
  `entrance_coordinate` varchar(100) CHARACTER SET utf8mb4 COLLATE utf8mb4_unicode_ci NULL DEFAULT NULL COMMENT '入口坐标',
  `exit_coordinate` varchar(100) CHARACTER SET utf8mb4 COLLATE utf8mb4_unicode_ci NULL DEFAULT NULL COMMENT '出口口坐标',
  `park_type` int(11) NOT NULL DEFAULT 1 COMMENT '停车场类型:1室内，2室外，3室内+室外，4其他',
  `park_cooperation_type` int(11) NOT NULL DEFAULT 1 COMMENT '停车场合作类型，0-免费，1-销售',
  `park_client_type` int(11) NOT NULL DEFAULT 1 COMMENT '停车场客户端类型：1车牌识别',
  `park_property` int(11) NOT NULL DEFAULT 1 COMMENT '停车场属性，1-商业综合体，2-商业写字楼，3-商务酒店，4-公共场馆，5-医院，6-产业园，\n                7-住宅，8-旅游景点，9-物流园，10-建材市场，11-学校，12-交通枢纽',
  `park_operation_state` int(11) NOT NULL DEFAULT 1 COMMENT '停车场运营状态，1-运营，2-施工，3-异常运营，4-账户取消，5-取消运营，6-拆除',
  `park_device_type` int(11) NOT NULL DEFAULT 1 COMMENT '停车场设备类型:',
  `park_state` int(11) NOT NULL DEFAULT 1 COMMENT '车场状态：1启用   0停用',
  `park_height_permitted` int(11) NULL DEFAULT NULL COMMENT '车库限高（cm)',
  `score` int(11) NOT NULL DEFAULT 5 COMMENT '综合评分：1-5',
  `created_at` timestamp(0) NULL DEFAULT NULL,
  `updated_at` timestamp(0) NULL DEFAULT NULL,
  `deleted_at` timestamp(0) NULL DEFAULT NULL,
  PRIMARY KEY (`id`) USING BTREE,
  INDEX `parks_property_id_index`(`property_id`) USING BTREE,
  INDEX `parks_project_group_id_index`(`project_group_id`) USING BTREE
) ENGINE = InnoDB AUTO_INCREMENT = 3324 CHARACTER SET = utf8mb4 COLLATE = utf8mb4_unicode_ci ROW_FORMAT = Dynamic;

-- ----------------------------
-- Table structure for payment_gateways
-- ----------------------------
DROP TABLE IF EXISTS `payment_gateways`;
CREATE TABLE `payment_gateways`  (
  `id` bigint(20) UNSIGNED NOT NULL AUTO_INCREMENT,
  `gateway_name` varchar(255) CHARACTER SET utf8mb4 COLLATE utf8mb4_unicode_ci NOT NULL COMMENT '支付方式',
  `gateway` varchar(255) CHARACTER SET utf8mb4 COLLATE utf8mb4_unicode_ci NOT NULL,
  `icon` varchar(255) CHARACTER SET utf8mb4 COLLATE utf8mb4_unicode_ci NOT NULL,
  `desc` varchar(255) CHARACTER SET utf8mb4 COLLATE utf8mb4_unicode_ci NULL DEFAULT NULL,
  `sort` int(11) NOT NULL DEFAULT 0,
  `max_money` int(10) UNSIGNED NOT NULL DEFAULT 0 COMMENT '最大额度',
  `platform` longtext CHARACTER SET utf8mb4 COLLATE utf8mb4_bin NULL COMMENT '适用场景',
  `enabled` tinyint(1) NOT NULL DEFAULT 1 COMMENT '是否开启',
  `created_at` timestamp(0) NULL DEFAULT NULL,
  `updated_at` timestamp(0) NULL DEFAULT NULL,
  PRIMARY KEY (`id`) USING BTREE
) ENGINE = InnoDB AUTO_INCREMENT = 4 CHARACTER SET = utf8mb4 COLLATE = utf8mb4_unicode_ci ROW_FORMAT = Dynamic;

-- ----------------------------
-- Table structure for payments
-- ----------------------------
DROP TABLE IF EXISTS `payments`;
CREATE TABLE `payments`  (
  `id` bigint(20) UNSIGNED NOT NULL AUTO_INCREMENT,
  `user_id` bigint(20) UNSIGNED NOT NULL,
  `no` varchar(255) CHARACTER SET utf8mb4 COLLATE utf8mb4_unicode_ci NOT NULL,
  `payable_type` varchar(255) CHARACTER SET utf8mb4 COLLATE utf8mb4_unicode_ci NOT NULL,
  `payable_id` bigint(20) UNSIGNED NOT NULL,
  `amount` int(10) UNSIGNED NOT NULL DEFAULT 0 COMMENT '订单金额',
  `paid_amount` int(10) UNSIGNED NOT NULL DEFAULT 0 COMMENT '付款金额',
  `description` varchar(255) CHARACTER SET utf8mb4 COLLATE utf8mb4_unicode_ci NOT NULL,
  `transaction_id` varchar(255) CHARACTER SET utf8mb4 COLLATE utf8mb4_unicode_ci NOT NULL COMMENT '流水id',
  `currency` varchar(255) CHARACTER SET utf8mb4 COLLATE utf8mb4_unicode_ci NOT NULL DEFAULT 'CNY',
  `status` varchar(255) CHARACTER SET utf8mb4 COLLATE utf8mb4_unicode_ci NOT NULL DEFAULT 'pending' COMMENT 'pending/paid/cancelled/failed/refunded',
  `refund_no` varchar(255) CHARACTER SET utf8mb4 COLLATE utf8mb4_unicode_ci NULL DEFAULT NULL COMMENT '退款订单号',
  `refund_id` varchar(255) CHARACTER SET utf8mb4 COLLATE utf8mb4_unicode_ci NULL DEFAULT NULL COMMENT '服务商退款单号',
  `refund_amount` varchar(255) CHARACTER SET utf8mb4 COLLATE utf8mb4_unicode_ci NOT NULL DEFAULT '0' COMMENT '退款金额',
  `refunded_at` timestamp(0) NULL DEFAULT NULL,
  `gateway` varchar(255) CHARACTER SET utf8mb4 COLLATE utf8mb4_unicode_ci NULL DEFAULT NULL,
  `type` varchar(255) CHARACTER SET utf8mb4 COLLATE utf8mb4_unicode_ci NULL DEFAULT NULL,
  `gateway_order` longtext CHARACTER SET utf8mb4 COLLATE utf8mb4_bin NULL,
  `context` longtext CHARACTER SET utf8mb4 COLLATE utf8mb4_bin NULL,
  `original_result` longtext CHARACTER SET utf8mb4 COLLATE utf8mb4_bin NULL,
  `paid_at` timestamp(0) NULL DEFAULT NULL,
  `expired_at` timestamp(0) NULL DEFAULT NULL,
  `failed_at` timestamp(0) NULL DEFAULT NULL,
  `created_at` timestamp(0) NULL DEFAULT NULL,
  `updated_at` timestamp(0) NULL DEFAULT NULL,
  `deleted_at` timestamp(0) NULL DEFAULT NULL,
  PRIMARY KEY (`id`) USING BTREE,
  UNIQUE INDEX `payments_no_unique`(`no`) USING BTREE,
  INDEX `payments_user_id_foreign`(`user_id`) USING BTREE,
  INDEX `payments_payable_type_payable_id_index`(`payable_type`, `payable_id`) USING BTREE,
  INDEX `payments_transaction_id_index`(`transaction_id`) USING BTREE,
  INDEX `payments_currency_index`(`currency`) USING BTREE,
  INDEX `payments_status_index`(`status`) USING BTREE,
  INDEX `payments_refund_no_index`(`refund_no`) USING BTREE,
  INDEX `payments_refund_id_index`(`refund_id`) USING BTREE,
  INDEX `payments_gateway_index`(`gateway`) USING BTREE,
  CONSTRAINT `payments_user_id_foreign` FOREIGN KEY (`user_id`) REFERENCES `users` (`id`) ON DELETE CASCADE ON UPDATE RESTRICT
) ENGINE = InnoDB AUTO_INCREMENT = 1477 CHARACTER SET = utf8mb4 COLLATE = utf8mb4_unicode_ci ROW_FORMAT = Dynamic;

-- ----------------------------
-- Table structure for permissions
-- ----------------------------
DROP TABLE IF EXISTS `permissions`;
CREATE TABLE `permissions`  (
  `id` bigint(20) UNSIGNED NOT NULL AUTO_INCREMENT,
  `parent_id` bigint(20) UNSIGNED NOT NULL DEFAULT 0,
  `path` varchar(255) CHARACTER SET utf8mb4 COLLATE utf8mb4_unicode_ci NULL DEFAULT NULL,
  `name` varchar(191) CHARACTER SET utf8mb4 COLLATE utf8mb4_unicode_ci NOT NULL COMMENT '权限名称',
  `display_name` varchar(191) CHARACTER SET utf8mb4 COLLATE utf8mb4_unicode_ci NOT NULL COMMENT '权限描述',
  `guard_name` varchar(191) CHARACTER SET utf8mb4 COLLATE utf8mb4_unicode_ci NOT NULL,
  `sort` int(11) NOT NULL DEFAULT 0 COMMENT '排序',
  `is_menu` tinyint(1) NOT NULL DEFAULT 0 COMMENT '菜单',
  `level` int(11) NOT NULL DEFAULT 0 COMMENT '菜单层级',
  `created_at` timestamp(0) NULL DEFAULT NULL,
  `updated_at` timestamp(0) NULL DEFAULT NULL,
  PRIMARY KEY (`id`) USING BTREE
) ENGINE = InnoDB AUTO_INCREMENT = 136 CHARACTER SET = utf8mb4 COLLATE = utf8mb4_unicode_ci ROW_FORMAT = Dynamic;

-- ----------------------------
-- Table structure for personal_access_tokens
-- ----------------------------
DROP TABLE IF EXISTS `personal_access_tokens`;
CREATE TABLE `personal_access_tokens`  (
  `id` bigint(20) UNSIGNED NOT NULL AUTO_INCREMENT,
  `tokenable_type` varchar(191) CHARACTER SET utf8mb4 COLLATE utf8mb4_unicode_ci NOT NULL,
  `tokenable_id` bigint(20) UNSIGNED NOT NULL,
  `name` varchar(191) CHARACTER SET utf8mb4 COLLATE utf8mb4_unicode_ci NOT NULL,
  `token` varchar(64) CHARACTER SET utf8mb4 COLLATE utf8mb4_unicode_ci NOT NULL,
  `abilities` text CHARACTER SET utf8mb4 COLLATE utf8mb4_unicode_ci NULL,
  `last_used_at` timestamp(0) NULL DEFAULT NULL,
  `created_at` timestamp(0) NULL DEFAULT NULL,
  `updated_at` timestamp(0) NULL DEFAULT NULL,
  PRIMARY KEY (`id`) USING BTREE,
  UNIQUE INDEX `personal_access_tokens_token_unique`(`token`) USING BTREE,
  INDEX `personal_access_tokens_tokenable_type_tokenable_id_index`(`tokenable_type`, `tokenable_id`) USING BTREE
) ENGINE = InnoDB AUTO_INCREMENT = 4507 CHARACTER SET = utf8mb4 COLLATE = utf8mb4_unicode_ci ROW_FORMAT = Dynamic;

-- ----------------------------
-- Table structure for platform_bill_summaries
-- ----------------------------
DROP TABLE IF EXISTS `platform_bill_summaries`;
CREATE TABLE `platform_bill_summaries`  (
  `id` bigint(20) UNSIGNED NOT NULL AUTO_INCREMENT,
  `date` varchar(255) CHARACTER SET utf8mb4 COLLATE utf8mb4_unicode_ci NOT NULL COMMENT '汇总日期：Y-m-d/Y-m',
  `type` varchar(255) CHARACTER SET utf8mb4 COLLATE utf8mb4_unicode_ci NOT NULL DEFAULT 'day' COMMENT '账单日期类型:天:day,月:month,年:year',
  `bill_type` varchar(255) CHARACTER SET utf8mb4 COLLATE utf8mb4_unicode_ci NOT NULL COMMENT '业务类型:汇总...',
  `amount` int(10) UNSIGNED NOT NULL DEFAULT 0 COMMENT '总额',
  `income` int(10) UNSIGNED NOT NULL DEFAULT 0 COMMENT '收入',
  `expenses` int(10) UNSIGNED NOT NULL DEFAULT 0 COMMENT '支出',
  `deleted_at` timestamp(0) NULL DEFAULT NULL,
  `created_at` timestamp(0) NULL DEFAULT NULL,
  `updated_at` timestamp(0) NULL DEFAULT NULL,
  PRIMARY KEY (`id`) USING BTREE
) ENGINE = InnoDB AUTO_INCREMENT = 146 CHARACTER SET = utf8mb4 COLLATE = utf8mb4_unicode_ci ROW_FORMAT = Dynamic;

-- ----------------------------
-- Table structure for platform_financial_records
-- ----------------------------
DROP TABLE IF EXISTS `platform_financial_records`;
CREATE TABLE `platform_financial_records`  (
  `id` bigint(20) UNSIGNED NOT NULL AUTO_INCREMENT,
  `platform` varchar(30) CHARACTER SET utf8mb4 COLLATE utf8mb4_unicode_ci NULL DEFAULT NULL COMMENT '平台',
  `business` int(11) NOT NULL COMMENT '业务名称 1-电子预约费',
  `type` int(11) NOT NULL COMMENT '交易类型 1-正常预约费结算 2-延迟预约费结算 3-当天停车费退款 4-车场提现',
  `income` int(11) NOT NULL COMMENT '收入',
  `spending` int(11) NOT NULL COMMENT '支出',
  `balance` int(11) NOT NULL COMMENT '余额',
  `date` timestamp(0) NOT NULL DEFAULT current_timestamp(0) ON UPDATE CURRENT_TIMESTAMP(0) COMMENT '账单日期',
  `state` varchar(255) CHARACTER SET utf8mb4 COLLATE utf8mb4_unicode_ci NOT NULL COMMENT 'day-日汇总 month-月汇总',
  `created_at` timestamp(0) NULL DEFAULT NULL,
  `updated_at` timestamp(0) NULL DEFAULT NULL,
  PRIMARY KEY (`id`) USING BTREE
) ENGINE = InnoDB AUTO_INCREMENT = 21 CHARACTER SET = utf8mb4 COLLATE = utf8mb4_unicode_ci ROW_FORMAT = Dynamic;

-- ----------------------------
-- Table structure for positions
-- ----------------------------
DROP TABLE IF EXISTS `positions`;
CREATE TABLE `positions`  (
  `id` bigint(20) UNSIGNED NOT NULL AUTO_INCREMENT,
  `department_id` bigint(20) UNSIGNED NULL DEFAULT NULL,
  `name` varchar(255) CHARACTER SET utf8mb4 COLLATE utf8mb4_unicode_ci NOT NULL,
  `guard_name` varchar(255) CHARACTER SET utf8mb4 COLLATE utf8mb4_unicode_ci NOT NULL,
  `created_at` timestamp(0) NULL DEFAULT NULL,
  `updated_at` timestamp(0) NULL DEFAULT NULL,
  PRIMARY KEY (`id`) USING BTREE,
  INDEX `positions_department_id_foreign`(`department_id`) USING BTREE,
  CONSTRAINT `positions_department_id_foreign` FOREIGN KEY (`department_id`) REFERENCES `departments` (`id`) ON DELETE RESTRICT ON UPDATE RESTRICT
) ENGINE = InnoDB AUTO_INCREMENT = 1 CHARACTER SET = utf8mb4 COLLATE = utf8mb4_unicode_ci ROW_FORMAT = Dynamic;

-- ----------------------------
-- Table structure for project_groups
-- ----------------------------
DROP TABLE IF EXISTS `project_groups`;
CREATE TABLE `project_groups`  (
  `id` bigint(20) UNSIGNED NOT NULL AUTO_INCREMENT,
  `group_name` varchar(20) CHARACTER SET utf8mb4 COLLATE utf8mb4_unicode_ci NOT NULL COMMENT '集团名称',
  `created_at` timestamp(0) NULL DEFAULT NULL,
  `updated_at` timestamp(0) NULL DEFAULT NULL,
  PRIMARY KEY (`id`) USING BTREE
) ENGINE = InnoDB AUTO_INCREMENT = 1015 CHARACTER SET = utf8mb4 COLLATE = utf8mb4_unicode_ci ROW_FORMAT = Dynamic;

-- ----------------------------
-- Table structure for properties
-- ----------------------------
DROP TABLE IF EXISTS `properties`;
CREATE TABLE `properties`  (
  `id` bigint(20) UNSIGNED NOT NULL AUTO_INCREMENT,
  `name` varchar(191) CHARACTER SET utf8mb4 COLLATE utf8mb4_unicode_ci NOT NULL,
  `account_name` varchar(255) CHARACTER SET utf8mb4 COLLATE utf8mb4_unicode_ci NULL DEFAULT NULL COMMENT '账号名称',
  `code` varchar(191) CHARACTER SET utf8mb4 COLLATE utf8mb4_unicode_ci NULL DEFAULT NULL,
  `mobile` varchar(191) CHARACTER SET utf8mb4 COLLATE utf8mb4_unicode_ci NOT NULL,
  `email` varchar(191) CHARACTER SET utf8mb4 COLLATE utf8mb4_unicode_ci NOT NULL,
  `email_verified_at` timestamp(0) NULL DEFAULT NULL,
  `password` varchar(191) CHARACTER SET utf8mb4 COLLATE utf8mb4_unicode_ci NOT NULL,
  `banned_withdraw` timestamp(0) NULL DEFAULT NULL COMMENT '冻结提现',
  `banned_login` timestamp(0) NULL DEFAULT NULL,
  `remember_token` varchar(100) CHARACTER SET utf8mb4 COLLATE utf8mb4_unicode_ci NULL DEFAULT NULL,
  `deleted_at` timestamp(0) NULL DEFAULT NULL,
  `created_at` timestamp(0) NULL DEFAULT NULL,
  `updated_at` timestamp(0) NULL DEFAULT NULL,
  `park_id` bigint(20) UNSIGNED NULL DEFAULT NULL,
  PRIMARY KEY (`id`) USING BTREE,
  UNIQUE INDEX `properties_email_unique`(`email`) USING BTREE,
  UNIQUE INDEX `properties_mobile_unique`(`mobile`) USING BTREE,
  INDEX `properties_park_id_index`(`park_id`) USING BTREE
) ENGINE = InnoDB AUTO_INCREMENT = 668 CHARACTER SET = utf8mb4 COLLATE = utf8mb4_unicode_ci ROW_FORMAT = Dynamic;

-- ----------------------------
-- Table structure for property_messages
-- ----------------------------
DROP TABLE IF EXISTS `property_messages`;
CREATE TABLE `property_messages`  (
  `id` bigint(20) UNSIGNED NOT NULL AUTO_INCREMENT,
  `user_id` int(10) UNSIGNED NOT NULL COMMENT '推送人员的id',
  `title` varchar(255) CHARACTER SET utf8mb4 COLLATE utf8mb4_unicode_ci NULL DEFAULT NULL COMMENT '标题',
  `content` varchar(255) CHARACTER SET utf8mb4 COLLATE utf8mb4_unicode_ci NULL DEFAULT NULL COMMENT '内容',
  `park_type` int(10) UNSIGNED NULL DEFAULT NULL COMMENT '车场类型',
  `park_property` int(10) UNSIGNED NULL DEFAULT NULL COMMENT '车场属性',
  `created_at` timestamp(0) NULL DEFAULT NULL,
  `updated_at` timestamp(0) NULL DEFAULT NULL,
  `deleted_at` timestamp(0) NULL DEFAULT NULL,
  PRIMARY KEY (`id`) USING BTREE
) ENGINE = InnoDB AUTO_INCREMENT = 14 CHARACTER SET = utf8mb4 COLLATE = utf8mb4_unicode_ci ROW_FORMAT = Dynamic;

-- ----------------------------
-- Table structure for provinces
-- ----------------------------
DROP TABLE IF EXISTS `provinces`;
CREATE TABLE `provinces`  (
  `id` bigint(20) UNSIGNED NOT NULL AUTO_INCREMENT,
  `name` varchar(191) CHARACTER SET utf8mb4 COLLATE utf8mb4_unicode_ci NOT NULL,
  `province_id` varchar(191) CHARACTER SET utf8mb4 COLLATE utf8mb4_unicode_ci NOT NULL,
  `created_at` timestamp(0) NULL DEFAULT NULL,
  `updated_at` timestamp(0) NULL DEFAULT NULL,
  PRIMARY KEY (`id`) USING BTREE,
  INDEX `provinces_province_id_index`(`province_id`) USING BTREE
) ENGINE = InnoDB AUTO_INCREMENT = 32 CHARACTER SET = utf8mb4 COLLATE = utf8mb4_unicode_ci ROW_FORMAT = Dynamic;

-- ----------------------------
-- Table structure for recharges
-- ----------------------------
DROP TABLE IF EXISTS `recharges`;
CREATE TABLE `recharges`  (
  `id` bigint(20) UNSIGNED NOT NULL AUTO_INCREMENT,
  `user_id` bigint(20) UNSIGNED NOT NULL,
  `no` varchar(255) CHARACTER SET utf8mb4 COLLATE utf8mb4_unicode_ci NOT NULL,
  `amount` int(10) UNSIGNED NOT NULL,
  `paid_amount` int(10) UNSIGNED NOT NULL DEFAULT 0 COMMENT '付款金额',
  `transaction_id` varchar(255) CHARACTER SET utf8mb4 COLLATE utf8mb4_unicode_ci NOT NULL COMMENT '流水id',
  `gateway` varchar(255) CHARACTER SET utf8mb4 COLLATE utf8mb4_unicode_ci NULL DEFAULT NULL,
  `refund_no` varchar(255) CHARACTER SET utf8mb4 COLLATE utf8mb4_unicode_ci NULL DEFAULT NULL COMMENT '退款订单号',
  `refund_id` varchar(255) CHARACTER SET utf8mb4 COLLATE utf8mb4_unicode_ci NULL DEFAULT NULL COMMENT '服务商退款单号',
  `refund_amount` varchar(255) CHARACTER SET utf8mb4 COLLATE utf8mb4_unicode_ci NOT NULL DEFAULT '0' COMMENT '退款金额',
  `refunded_at` timestamp(0) NULL DEFAULT NULL,
  `status` varchar(255) CHARACTER SET utf8mb4 COLLATE utf8mb4_unicode_ci NOT NULL DEFAULT 'pending' COMMENT 'pending/paid/cancelled/failed/refunded',
  `paid_at` timestamp(0) NULL DEFAULT NULL,
  `expired_at` timestamp(0) NULL DEFAULT NULL,
  `failed_at` timestamp(0) NULL DEFAULT NULL,
  `created_at` timestamp(0) NULL DEFAULT NULL,
  `updated_at` timestamp(0) NULL DEFAULT NULL,
  `deleted_at` timestamp(0) NULL DEFAULT NULL,
  PRIMARY KEY (`id`) USING BTREE,
  UNIQUE INDEX `recharges_no_unique`(`no`) USING BTREE,
  INDEX `recharges_user_id_foreign`(`user_id`) USING BTREE,
  INDEX `recharges_transaction_id_index`(`transaction_id`) USING BTREE,
  INDEX `recharges_gateway_index`(`gateway`) USING BTREE,
  INDEX `recharges_refund_no_index`(`refund_no`) USING BTREE,
  INDEX `recharges_refund_id_index`(`refund_id`) USING BTREE,
  INDEX `recharges_status_index`(`status`) USING BTREE,
  CONSTRAINT `recharges_user_id_foreign` FOREIGN KEY (`user_id`) REFERENCES `users` (`id`) ON DELETE CASCADE ON UPDATE RESTRICT
) ENGINE = InnoDB AUTO_INCREMENT = 103 CHARACTER SET = utf8mb4 COLLATE = utf8mb4_unicode_ci ROW_FORMAT = Dynamic;

-- ----------------------------
-- Table structure for records
-- ----------------------------
DROP TABLE IF EXISTS `records`;
CREATE TABLE `records`  (
  `id` bigint(20) UNSIGNED NOT NULL AUTO_INCREMENT,
  `record_no` varchar(255) CHARACTER SET utf8mb4 COLLATE utf8mb4_unicode_ci NOT NULL COMMENT '调整单号',
  `withdrawal_id` bigint(20) UNSIGNED NOT NULL,
  `adjust_amount` int(11) NOT NULL COMMENT '调整金额',
  `adjust_type` int(11) NOT NULL DEFAULT 1 COMMENT '调整类型 1-结算扣款 2-结算补款',
  `reason` varchar(30) CHARACTER SET utf8mb4 COLLATE utf8mb4_unicode_ci NULL DEFAULT NULL COMMENT '调整原因',
  `is_loss` int(11) NOT NULL DEFAULT 1 COMMENT '公司是否亏损 1-否 2-是',
  `operator` varchar(20) CHARACTER SET utf8mb4 COLLATE utf8mb4_unicode_ci NOT NULL COMMENT '操作员',
  `deleted_at` timestamp(0) NULL DEFAULT NULL,
  `created_at` timestamp(0) NULL DEFAULT NULL,
  `updated_at` timestamp(0) NULL DEFAULT NULL,
  PRIMARY KEY (`id`) USING BTREE,
  UNIQUE INDEX `records_record_no_unique`(`record_no`) USING BTREE,
  INDEX `records_withdrawal_id_foreign`(`withdrawal_id`) USING BTREE,
  CONSTRAINT `records_withdrawal_id_foreign` FOREIGN KEY (`withdrawal_id`) REFERENCES `withdrawals` (`id`) ON DELETE CASCADE ON UPDATE RESTRICT
) ENGINE = InnoDB AUTO_INCREMENT = 63 CHARACTER SET = utf8mb4 COLLATE = utf8mb4_unicode_ci ROW_FORMAT = Dynamic;

-- ----------------------------
-- Table structure for reminder_records
-- ----------------------------
DROP TABLE IF EXISTS `reminder_records`;
CREATE TABLE `reminder_records`  (
  `id` bigint(20) UNSIGNED NOT NULL AUTO_INCREMENT,
  `reminder_id` bigint(20) UNSIGNED NOT NULL COMMENT 'reminder的id',
  `admin_id` bigint(20) UNSIGNED NULL DEFAULT NULL COMMENT '操作人员，系统自动通知就为空',
  `state` varchar(255) CHARACTER SET utf8mb4 COLLATE utf8mb4_unicode_ci NOT NULL DEFAULT '' COMMENT '2-推送通知，3-短信，4-人工催收',
  `feedback` varchar(255) CHARACTER SET utf8mb4 COLLATE utf8mb4_unicode_ci NULL DEFAULT NULL COMMENT '催收反馈信息',
  `created_at` timestamp(0) NULL DEFAULT NULL,
  `updated_at` timestamp(0) NULL DEFAULT NULL,
  `deleted_at` timestamp(0) NULL DEFAULT NULL,
  PRIMARY KEY (`id`) USING BTREE
) ENGINE = InnoDB AUTO_INCREMENT = 97 CHARACTER SET = utf8mb4 COLLATE = utf8mb4_unicode_ci ROW_FORMAT = Dynamic;

-- ----------------------------
-- Table structure for reminders
-- ----------------------------
DROP TABLE IF EXISTS `reminders`;
CREATE TABLE `reminders`  (
  `id` bigint(20) UNSIGNED NOT NULL AUTO_INCREMENT,
  `user_order_id` bigint(20) UNSIGNED NOT NULL COMMENT 'user_order表的id',
  `park_id` bigint(20) UNSIGNED NULL DEFAULT NULL COMMENT '车场id',
  `park_space_id` bigint(20) UNSIGNED NULL DEFAULT NULL COMMENT '车位id',
  `car_stop_id` bigint(20) UNSIGNED NULL DEFAULT NULL COMMENT '停车记录的id',
  `user_id` bigint(20) UNSIGNED NULL DEFAULT NULL COMMENT '该订单用户的id',
  `user_car_id` bigint(20) UNSIGNED NULL DEFAULT NULL COMMENT '用户车辆id',
  `order_no` varchar(255) CHARACTER SET utf8mb4 COLLATE utf8mb4_unicode_ci NOT NULL DEFAULT '' COMMENT '订单号',
  `phone` varchar(255) CHARACTER SET utf8mb4 COLLATE utf8mb4_unicode_ci NOT NULL DEFAULT '' COMMENT '用户注册手机号',
  `car_num` varchar(255) CHARACTER SET utf8mb4 COLLATE utf8mb4_unicode_ci NOT NULL DEFAULT '' COMMENT '车牌号',
  `car_in_time` timestamp(0) NULL DEFAULT NULL COMMENT '车辆进场时间',
  `car_out_time` timestamp(0) NULL DEFAULT NULL COMMENT '车辆出场时间',
  `stop_time` int(10) UNSIGNED NOT NULL DEFAULT 0 COMMENT '停车时长',
  `amount` int(10) UNSIGNED NOT NULL DEFAULT 0 COMMENT '停车金额',
  `deduct_amount` int(10) UNSIGNED NOT NULL DEFAULT 0 COMMENT '实收金额',
  `days_overdue` int(10) UNSIGNED NOT NULL DEFAULT 0 COMMENT '逾期天数',
  `state` varchar(255) CHARACTER SET utf8mb4 COLLATE utf8mb4_unicode_ci NOT NULL DEFAULT '1' COMMENT '1-未催收，2-推送通知，3-短信，4-人工催收',
  `pay_status` varchar(255) CHARACTER SET utf8mb4 COLLATE utf8mb4_unicode_ci NOT NULL DEFAULT 'pending' COMMENT '支付状态：pending-未支付；paid-已经支付',
  `created_at` timestamp(0) NULL DEFAULT NULL,
  `updated_at` timestamp(0) NULL DEFAULT NULL,
  `deleted_at` timestamp(0) NULL DEFAULT NULL,
  PRIMARY KEY (`id`) USING BTREE
) ENGINE = InnoDB AUTO_INCREMENT = 31 CHARACTER SET = utf8mb4 COLLATE = utf8mb4_unicode_ci ROW_FORMAT = Dynamic;

-- ----------------------------
-- Table structure for reviews
-- ----------------------------
DROP TABLE IF EXISTS `reviews`;
CREATE TABLE `reviews`  (
  `id` bigint(20) UNSIGNED NOT NULL AUTO_INCREMENT,
  `model_type` varchar(255) CHARACTER SET utf8mb4 COLLATE utf8mb4_unicode_ci NOT NULL,
  `model_id` bigint(20) UNSIGNED NOT NULL,
  `type` varchar(255) CHARACTER SET utf8mb4 COLLATE utf8mb4_unicode_ci NOT NULL COMMENT '审核内容类型:text,img',
  `value` varchar(255) CHARACTER SET utf8mb4 COLLATE utf8mb4_unicode_ci NULL DEFAULT '',
  `suggestion` varchar(255) CHARACTER SET utf8mb4 COLLATE utf8mb4_unicode_ci NULL DEFAULT NULL COMMENT '审核建议：pass:正常，review:需要人工审核,block:文本违规',
  `label` varchar(255) CHARACTER SET utf8mb4 COLLATE utf8mb4_unicode_ci NULL DEFAULT NULL COMMENT '文本垃圾检测结果的分类',
  `response` longtext CHARACTER SET utf8mb4 COLLATE utf8mb4_bin NULL,
  `deleted_at` timestamp(0) NULL DEFAULT NULL,
  `created_at` timestamp(0) NULL DEFAULT NULL,
  `updated_at` timestamp(0) NULL DEFAULT NULL,
  PRIMARY KEY (`id`) USING BTREE,
  INDEX `reviews_model_type_model_id_index`(`model_type`, `model_id`) USING BTREE
) ENGINE = InnoDB AUTO_INCREMENT = 35 CHARACTER SET = utf8mb4 COLLATE = utf8mb4_unicode_ci COMMENT = '阿里云审核记录' ROW_FORMAT = Dynamic;

-- ----------------------------
-- Table structure for role_has_permissions
-- ----------------------------
DROP TABLE IF EXISTS `role_has_permissions`;
CREATE TABLE `role_has_permissions`  (
  `permission_id` bigint(20) UNSIGNED NOT NULL,
  `role_id` bigint(20) UNSIGNED NOT NULL,
  PRIMARY KEY (`permission_id`, `role_id`) USING BTREE,
  INDEX `role_has_permissions_role_id_foreign`(`role_id`) USING BTREE,
  CONSTRAINT `role_has_permissions_permission_id_foreign` FOREIGN KEY (`permission_id`) REFERENCES `permissions` (`id`) ON DELETE CASCADE ON UPDATE RESTRICT,
  CONSTRAINT `role_has_permissions_role_id_foreign` FOREIGN KEY (`role_id`) REFERENCES `roles` (`id`) ON DELETE CASCADE ON UPDATE RESTRICT
) ENGINE = InnoDB CHARACTER SET = utf8mb4 COLLATE = utf8mb4_unicode_ci ROW_FORMAT = Dynamic;

-- ----------------------------
-- Table structure for roles
-- ----------------------------
DROP TABLE IF EXISTS `roles`;
CREATE TABLE `roles`  (
  `id` bigint(20) UNSIGNED NOT NULL AUTO_INCREMENT,
  `name` varchar(191) CHARACTER SET utf8mb4 COLLATE utf8mb4_unicode_ci NOT NULL,
  `display_name` varchar(191) CHARACTER SET utf8mb4 COLLATE utf8mb4_unicode_ci NOT NULL,
  `guard_name` varchar(191) CHARACTER SET utf8mb4 COLLATE utf8mb4_unicode_ci NOT NULL,
  `created_at` timestamp(0) NULL DEFAULT NULL,
  `updated_at` timestamp(0) NULL DEFAULT NULL,
  PRIMARY KEY (`id`) USING BTREE
) ENGINE = InnoDB AUTO_INCREMENT = 32 CHARACTER SET = utf8mb4 COLLATE = utf8mb4_unicode_ci ROW_FORMAT = Dynamic;

-- ----------------------------
-- Table structure for settings
-- ----------------------------
DROP TABLE IF EXISTS `settings`;
CREATE TABLE `settings`  (
  `key` varchar(191) CHARACTER SET utf8mb4 COLLATE utf8mb4_unicode_ci NOT NULL,
  `value` text CHARACTER SET utf8mb4 COLLATE utf8mb4_unicode_ci NOT NULL
) ENGINE = InnoDB CHARACTER SET = utf8mb4 COLLATE = utf8mb4_unicode_ci ROW_FORMAT = Dynamic;

-- ----------------------------
-- Table structure for user_accounts
-- ----------------------------
DROP TABLE IF EXISTS `user_accounts`;
CREATE TABLE `user_accounts`  (
  `id` bigint(20) UNSIGNED NOT NULL AUTO_INCREMENT,
  `user_id` bigint(20) UNSIGNED NOT NULL,
  `account` varchar(255) CHARACTER SET utf8mb4 COLLATE utf8mb4_unicode_ci NULL DEFAULT NULL COMMENT '支付宝账户',
  `openid` varchar(255) CHARACTER SET utf8mb4 COLLATE utf8mb4_unicode_ci NULL DEFAULT NULL COMMENT '微信账户',
  `created_at` timestamp(0) NULL DEFAULT NULL,
  `updated_at` timestamp(0) NULL DEFAULT NULL,
  PRIMARY KEY (`id`) USING BTREE,
  INDEX `user_accounts_user_id_index`(`user_id`) USING BTREE
) ENGINE = InnoDB AUTO_INCREMENT = 1 CHARACTER SET = utf8mb4 COLLATE = utf8mb4_unicode_ci ROW_FORMAT = Dynamic;

-- ----------------------------
-- Table structure for user_auth_accounts
-- ----------------------------
DROP TABLE IF EXISTS `user_auth_accounts`;
CREATE TABLE `user_auth_accounts`  (
  `id` bigint(20) UNSIGNED NOT NULL AUTO_INCREMENT,
  `user_id` bigint(20) UNSIGNED NULL DEFAULT NULL,
  `from` varchar(255) CHARACTER SET utf8mb4 COLLATE utf8mb4_unicode_ci NOT NULL COMMENT 'wx,qq,ali',
  `openid` varchar(255) CHARACTER SET utf8mb4 COLLATE utf8mb4_unicode_ci NOT NULL,
  `unionid` varchar(255) CHARACTER SET utf8mb4 COLLATE utf8mb4_unicode_ci NULL DEFAULT NULL,
  `nickname` varchar(255) CHARACTER SET utf8mb4 COLLATE utf8mb4_unicode_ci NOT NULL,
  `avatar` varchar(255) CHARACTER SET utf8mb4 COLLATE utf8mb4_unicode_ci NOT NULL,
  `sex` int(11) NOT NULL DEFAULT 0 COMMENT '1男性，2女性，0未知',
  `province` varchar(255) CHARACTER SET utf8mb4 COLLATE utf8mb4_unicode_ci NULL DEFAULT NULL COMMENT '省份',
  `city` varchar(255) CHARACTER SET utf8mb4 COLLATE utf8mb4_unicode_ci NULL DEFAULT NULL COMMENT '市',
  `access_token` varchar(255) CHARACTER SET utf8mb4 COLLATE utf8mb4_unicode_ci NULL DEFAULT NULL,
  `access_token_expired_at` varchar(255) CHARACTER SET utf8mb4 COLLATE utf8mb4_unicode_ci NULL DEFAULT NULL,
  `refresh_token` varchar(255) CHARACTER SET utf8mb4 COLLATE utf8mb4_unicode_ci NULL DEFAULT NULL,
  `raw` longtext CHARACTER SET utf8mb4 COLLATE utf8mb4_bin NULL,
  `created_at` timestamp(0) NULL DEFAULT NULL,
  `updated_at` timestamp(0) NULL DEFAULT NULL,
  `deleted_at` timestamp(0) NULL DEFAULT NULL,
  PRIMARY KEY (`id`) USING BTREE,
  INDEX `user_auth_accounts_user_id_index`(`user_id`) USING BTREE
) ENGINE = InnoDB AUTO_INCREMENT = 77 CHARACTER SET = utf8mb4 COLLATE = utf8mb4_unicode_ci ROW_FORMAT = Dynamic;

-- ----------------------------
-- Table structure for user_balances
-- ----------------------------
DROP TABLE IF EXISTS `user_balances`;
CREATE TABLE `user_balances`  (
  `id` bigint(20) UNSIGNED NOT NULL AUTO_INCREMENT,
  `user_id` bigint(20) UNSIGNED NOT NULL,
  `order_no` varchar(255) CHARACTER SET utf8mb4 COLLATE utf8mb4_unicode_ci NOT NULL,
  `trade_no` varchar(255) CHARACTER SET utf8mb4 COLLATE utf8mb4_unicode_ci NULL DEFAULT NULL,
  `trade_type` int(11) NOT NULL COMMENT '交易方式:收入1，支出2',
  `amount` int(10) UNSIGNED NOT NULL DEFAULT 0 COMMENT '操作金额',
  `balance` int(10) UNSIGNED NOT NULL DEFAULT 0 COMMENT '余额（实时）',
  `fee` int(10) UNSIGNED NOT NULL DEFAULT 0 COMMENT '手续费',
  `type` varchar(255) CHARACTER SET utf8mb4 COLLATE utf8mb4_unicode_ci NULL DEFAULT NULL COMMENT '业务类型',
  `body` varchar(255) CHARACTER SET utf8mb4 COLLATE utf8mb4_unicode_ci NULL DEFAULT NULL COMMENT '描述',
  `gateway` varchar(255) CHARACTER SET utf8mb4 COLLATE utf8mb4_unicode_ci NULL DEFAULT NULL COMMENT '付款方式',
  `status` int(10) UNSIGNED NOT NULL DEFAULT 1 COMMENT '状态：1成功,2失败，0申请中（针对提现）',
  `order_type` varchar(255) CHARACTER SET utf8mb4 COLLATE utf8mb4_unicode_ci NOT NULL,
  `order_id` bigint(20) UNSIGNED NOT NULL,
  `deleted_at` timestamp(0) NULL DEFAULT NULL,
  `created_at` timestamp(0) NULL DEFAULT NULL,
  `updated_at` timestamp(0) NULL DEFAULT NULL,
  PRIMARY KEY (`id`) USING BTREE,
  INDEX `user_balances_user_id_foreign`(`user_id`) USING BTREE,
  INDEX `user_balances_order_type_order_id_index`(`order_type`, `order_id`) USING BTREE,
  INDEX `user_balances_order_no_index`(`order_no`) USING BTREE,
  INDEX `user_balances_trade_no_index`(`trade_no`) USING BTREE,
  CONSTRAINT `user_balances_user_id_foreign` FOREIGN KEY (`user_id`) REFERENCES `users` (`id`) ON DELETE CASCADE ON UPDATE RESTRICT
) ENGINE = InnoDB AUTO_INCREMENT = 1421 CHARACTER SET = utf8mb4 COLLATE = utf8mb4_unicode_ci ROW_FORMAT = Dynamic;

-- ----------------------------
-- Table structure for user_cars
-- ----------------------------
DROP TABLE IF EXISTS `user_cars`;
CREATE TABLE `user_cars`  (
  `id` bigint(20) UNSIGNED NOT NULL AUTO_INCREMENT,
  `user_id` bigint(20) UNSIGNED NULL DEFAULT NULL COMMENT '用户ID',
  `owner_name` varchar(255) CHARACTER SET utf8mb4 COLLATE utf8mb4_unicode_ci NULL DEFAULT NULL COMMENT '车主姓名',
  `car_number` varchar(255) CHARACTER SET utf8mb4 COLLATE utf8mb4_unicode_ci NOT NULL COMMENT '车牌号',
  `frame_number` varchar(255) CHARACTER SET utf8mb4 COLLATE utf8mb4_unicode_ci NULL DEFAULT NULL COMMENT '车架号',
  `engine_number` varchar(255) CHARACTER SET utf8mb4 COLLATE utf8mb4_unicode_ci NULL DEFAULT NULL COMMENT '发动机号',
  `brand_model` varchar(255) CHARACTER SET utf8mb4 COLLATE utf8mb4_unicode_ci NULL DEFAULT NULL COMMENT '品牌型号',
  `face_license_imgurl` varchar(255) CHARACTER SET utf8mb4 COLLATE utf8mb4_unicode_ci NULL DEFAULT NULL COMMENT '驾驶证正面图片url',
  `back_license_imgurl` varchar(255) CHARACTER SET utf8mb4 COLLATE utf8mb4_unicode_ci NULL DEFAULT NULL COMMENT '驾驶证反面图片url',
  `is_default` int(11) NOT NULL DEFAULT 0 COMMENT '是否设置为默认车牌 1:默认车牌 0：不默认',
  `is_verify` tinyint(1) NOT NULL DEFAULT 0 COMMENT '是否认证 0未认证 1已认证',
  `verified_at` timestamp(0) NULL DEFAULT NULL COMMENT '认证时间',
  `deleted_at` timestamp(0) NULL DEFAULT NULL,
  `created_at` timestamp(0) NULL DEFAULT NULL,
  `updated_at` timestamp(0) NULL DEFAULT NULL,
  PRIMARY KEY (`id`) USING BTREE,
  INDEX `user_cars_user_id_foreign`(`user_id`) USING BTREE,
  CONSTRAINT `user_cars_user_id_foreign` FOREIGN KEY (`user_id`) REFERENCES `users` (`id`) ON DELETE CASCADE ON UPDATE RESTRICT
) ENGINE = InnoDB AUTO_INCREMENT = 219 CHARACTER SET = utf8mb4 COLLATE = utf8mb4_unicode_ci ROW_FORMAT = Dynamic;

-- ----------------------------
-- Table structure for user_collects
-- ----------------------------
DROP TABLE IF EXISTS `user_collects`;
CREATE TABLE `user_collects`  (
  `id` bigint(20) UNSIGNED NOT NULL AUTO_INCREMENT,
  `user_id` bigint(20) UNSIGNED NOT NULL COMMENT '用户ID',
  `park_id` bigint(20) UNSIGNED NOT NULL COMMENT '停车场ID',
  `deleted_at` timestamp(0) NULL DEFAULT NULL,
  `created_at` timestamp(0) NULL DEFAULT NULL,
  `updated_at` timestamp(0) NULL DEFAULT NULL,
  PRIMARY KEY (`id`) USING BTREE,
  INDEX `user_collects_user_id_foreign`(`user_id`) USING BTREE,
  INDEX `user_collects_park_id_foreign`(`park_id`) USING BTREE,
  CONSTRAINT `user_collects_park_id_foreign` FOREIGN KEY (`park_id`) REFERENCES `parks` (`id`) ON DELETE CASCADE ON UPDATE RESTRICT,
  CONSTRAINT `user_collects_user_id_foreign` FOREIGN KEY (`user_id`) REFERENCES `users` (`id`) ON DELETE CASCADE ON UPDATE RESTRICT
) ENGINE = InnoDB AUTO_INCREMENT = 50 CHARACTER SET = utf8mb4 COLLATE = utf8mb4_unicode_ci ROW_FORMAT = Dynamic;

-- ----------------------------
-- Table structure for user_comments
-- ----------------------------
DROP TABLE IF EXISTS `user_comments`;
CREATE TABLE `user_comments`  (
  `id` bigint(20) UNSIGNED NOT NULL AUTO_INCREMENT,
  `user_id` bigint(20) UNSIGNED NOT NULL COMMENT '用户ID',
  `order_id` bigint(20) UNSIGNED NOT NULL COMMENT '订单ID',
  `park_id` bigint(20) UNSIGNED NULL DEFAULT NULL,
  `content` text CHARACTER SET utf8mb4 COLLATE utf8mb4_unicode_ci NULL COMMENT '评价内容',
  `imgurl` varchar(255) CHARACTER SET utf8mb4 COLLATE utf8mb4_unicode_ci NULL DEFAULT NULL COMMENT '评论上传图片',
  `rate` int(11) NOT NULL DEFAULT 5 COMMENT '综合评价星级 1-5星',
  `is_display` int(11) NOT NULL DEFAULT 1 COMMENT '是否展示 0-不展示 1-展示',
  `deleted_at` timestamp(0) NULL DEFAULT NULL,
  `created_at` timestamp(0) NULL DEFAULT NULL,
  `updated_at` timestamp(0) NULL DEFAULT NULL,
  `audit_status` varchar(255) CHARACTER SET utf8mb4 COLLATE utf8mb4_unicode_ci NOT NULL DEFAULT '1' COMMENT '审核状态：1待审核 2已通过 3未通过 ',
  `auditor` varchar(255) CHARACTER SET utf8mb4 COLLATE utf8mb4_unicode_ci NULL DEFAULT NULL COMMENT '审核人员',
  `audit_time` varchar(255) CHARACTER SET utf8mb4 COLLATE utf8mb4_unicode_ci NULL DEFAULT NULL COMMENT '审核时间',
  `refuse_reason` varchar(255) CHARACTER SET utf8mb4 COLLATE utf8mb4_unicode_ci NULL DEFAULT NULL COMMENT '驳回理由',
  `suggestion` varchar(255) CHARACTER SET utf8mb4 COLLATE utf8mb4_unicode_ci NULL DEFAULT NULL COMMENT '审核建议：pass:正常，review:需要人工审核,block:文本违规',
  `label` varchar(255) CHARACTER SET utf8mb4 COLLATE utf8mb4_unicode_ci NULL DEFAULT NULL COMMENT '文本垃圾检测结果的分类',
  `response` longtext CHARACTER SET utf8mb4 COLLATE utf8mb4_bin NULL,
  PRIMARY KEY (`id`) USING BTREE,
  INDEX `user_comments_user_id_foreign`(`user_id`) USING BTREE,
  INDEX `user_comments_order_id_foreign`(`order_id`) USING BTREE,
  INDEX `user_comments_park_id_foreign`(`park_id`) USING BTREE,
  CONSTRAINT `user_comments_order_id_foreign` FOREIGN KEY (`order_id`) REFERENCES `user_orders` (`id`) ON DELETE CASCADE ON UPDATE RESTRICT,
  CONSTRAINT `user_comments_park_id_foreign` FOREIGN KEY (`park_id`) REFERENCES `parks` (`id`) ON DELETE CASCADE ON UPDATE RESTRICT,
  CONSTRAINT `user_comments_user_id_foreign` FOREIGN KEY (`user_id`) REFERENCES `users` (`id`) ON DELETE CASCADE ON UPDATE RESTRICT
) ENGINE = InnoDB AUTO_INCREMENT = 25 CHARACTER SET = utf8mb4 COLLATE = utf8mb4_unicode_ci ROW_FORMAT = Dynamic;

-- ----------------------------
-- Table structure for user_complaints
-- ----------------------------
DROP TABLE IF EXISTS `user_complaints`;
CREATE TABLE `user_complaints`  (
  `id` bigint(20) UNSIGNED NOT NULL AUTO_INCREMENT,
  `user_id` bigint(20) UNSIGNED NOT NULL COMMENT '用户ID',
  `order_id` bigint(20) UNSIGNED NULL DEFAULT NULL,
  `park_id` bigint(20) UNSIGNED NULL DEFAULT NULL,
  `title` varchar(255) CHARACTER SET utf8mb4 COLLATE utf8mb4_unicode_ci NULL DEFAULT NULL COMMENT '投诉主题',
  `content` text CHARACTER SET utf8mb4 COLLATE utf8mb4_unicode_ci NULL COMMENT '投诉内容',
  `imgurl` varchar(700) CHARACTER SET utf8mb4 COLLATE utf8mb4_unicode_ci NULL DEFAULT NULL COMMENT '投诉上传图片（多张使用逗号隔开）',
  `type` int(11) NOT NULL DEFAULT 0 COMMENT '投诉类型 0-投诉个人用户（占车位） 1-投诉商家、物业（服务态度）',
  `result` int(11) NOT NULL DEFAULT 0 COMMENT '处理结果： 0-未解决 1-已解决',
  `urgencydegree` int(11) NOT NULL DEFAULT 0 COMMENT '紧急程度： 0-默认 1-一般 2-紧急 3-非常紧急',
  `deleted_at` timestamp(0) NULL DEFAULT NULL,
  `created_at` timestamp(0) NULL DEFAULT NULL,
  `updated_at` timestamp(0) NULL DEFAULT NULL,
  `order_no` varchar(255) CHARACTER SET utf8mb4 COLLATE utf8mb4_unicode_ci NULL DEFAULT NULL COMMENT '订单号',
  `handling_state` varchar(255) CHARACTER SET utf8mb4 COLLATE utf8mb4_unicode_ci NOT NULL DEFAULT '1' COMMENT '状态:1未处理 2已处理',
  `handling_person` varchar(255) CHARACTER SET utf8mb4 COLLATE utf8mb4_unicode_ci NULL DEFAULT NULL COMMENT '处理人员',
  `handling_time` timestamp(0) NOT NULL DEFAULT current_timestamp(0) ON UPDATE CURRENT_TIMESTAMP(0) COMMENT '处理时间',
  `suggestion` varchar(255) CHARACTER SET utf8mb4 COLLATE utf8mb4_unicode_ci NULL DEFAULT NULL COMMENT '审核建议：pass:正常，review:需要人工审核,block:文本违规',
  `label` varchar(255) CHARACTER SET utf8mb4 COLLATE utf8mb4_unicode_ci NULL DEFAULT NULL COMMENT '文本垃圾检测结果的分类',
  `response` longtext CHARACTER SET utf8mb4 COLLATE utf8mb4_bin NULL,
  PRIMARY KEY (`id`) USING BTREE,
  INDEX `user_complaints_user_id_foreign`(`user_id`) USING BTREE,
  INDEX `user_complaints_park_id_foreign`(`park_id`) USING BTREE,
  INDEX `user_complaints_order_id_foreign`(`order_id`) USING BTREE,
  CONSTRAINT `user_complaints_order_id_foreign` FOREIGN KEY (`order_id`) REFERENCES `user_orders` (`id`) ON DELETE CASCADE ON UPDATE RESTRICT,
  CONSTRAINT `user_complaints_park_id_foreign` FOREIGN KEY (`park_id`) REFERENCES `parks` (`id`) ON DELETE CASCADE ON UPDATE RESTRICT,
  CONSTRAINT `user_complaints_user_id_foreign` FOREIGN KEY (`user_id`) REFERENCES `users` (`id`) ON DELETE CASCADE ON UPDATE RESTRICT
) ENGINE = InnoDB AUTO_INCREMENT = 34 CHARACTER SET = utf8mb4 COLLATE = utf8mb4_unicode_ci ROW_FORMAT = Dynamic;

-- ----------------------------
-- Table structure for user_consumption_recodes
-- ----------------------------
DROP TABLE IF EXISTS `user_consumption_recodes`;
CREATE TABLE `user_consumption_recodes`  (
  `id` bigint(20) UNSIGNED NOT NULL AUTO_INCREMENT COMMENT '主键ID',
  `serial_number` varchar(255) CHARACTER SET utf8mb4 COLLATE utf8mb4_unicode_ci NOT NULL COMMENT '流水号',
  `car_number` varchar(255) CHARACTER SET utf8mb4 COLLATE utf8mb4_unicode_ci NOT NULL COMMENT '车牌号',
  `park_name` varchar(255) CHARACTER SET utf8mb4 COLLATE utf8mb4_unicode_ci NOT NULL COMMENT '停车场',
  `amount` int(11) NOT NULL COMMENT '金额',
  `payment_channel` int(11) NOT NULL COMMENT '支付通道：1-余额 2-微信 3-支付宝',
  `payment_type` int(11) NOT NULL DEFAULT 0 COMMENT '支付类型：1-代扣 2-账户余额付款',
  `payment_account` varchar(255) CHARACTER SET utf8mb4 COLLATE utf8mb4_unicode_ci NULL DEFAULT NULL COMMENT '支付账户',
  `channel_transaction_no` varchar(255) CHARACTER SET utf8mb4 COLLATE utf8mb4_unicode_ci NULL DEFAULT NULL COMMENT '通道交易号',
  `business_type` int(11) NOT NULL COMMENT '业务类型： 1-支付 2-提现 3-退款',
  `status` varchar(255) CHARACTER SET utf8mb4 COLLATE utf8mb4_unicode_ci NOT NULL DEFAULT 'pending' COMMENT 'pending/paid/cancelled/failed/refunded',
  `user_type` varchar(255) CHARACTER SET utf8mb4 COLLATE utf8mb4_unicode_ci NOT NULL,
  `user_id` bigint(20) UNSIGNED NOT NULL,
  `order_type` varchar(255) CHARACTER SET utf8mb4 COLLATE utf8mb4_unicode_ci NOT NULL,
  `order_id` bigint(20) UNSIGNED NOT NULL,
  `deleted_at` timestamp(0) NULL DEFAULT NULL,
  `created_at` timestamp(0) NULL DEFAULT NULL,
  `updated_at` timestamp(0) NULL DEFAULT NULL,
  PRIMARY KEY (`id`) USING BTREE,
  INDEX `user_consumption_recodes_user_type_user_id_index`(`user_type`, `user_id`) USING BTREE,
  INDEX `user_consumption_recodes_order_type_order_id_index`(`order_type`, `order_id`) USING BTREE,
  INDEX `user_consumption_recodes_status_index`(`status`) USING BTREE
) ENGINE = InnoDB AUTO_INCREMENT = 1 CHARACTER SET = utf8mb4 COLLATE = utf8mb4_unicode_ci ROW_FORMAT = Dynamic;

-- ----------------------------
-- Table structure for user_coupons
-- ----------------------------
DROP TABLE IF EXISTS `user_coupons`;
CREATE TABLE `user_coupons`  (
  `id` bigint(20) UNSIGNED NOT NULL AUTO_INCREMENT,
  `no` varchar(255) CHARACTER SET utf8mb4 COLLATE utf8mb4_unicode_ci NOT NULL COMMENT '编号',
  `user_id` bigint(20) UNSIGNED NOT NULL COMMENT '用户ID',
  `coupon_id` bigint(20) UNSIGNED NOT NULL COMMENT '优惠券ID',
  `use_scene` int(11) NOT NULL DEFAULT 1 COMMENT '使用场景:1通用，2预约费，3停车费',
  `order_id` bigint(20) NOT NULL DEFAULT 0 COMMENT '外键，关联user_order表id',
  `title` varchar(255) CHARACTER SET utf8mb4 COLLATE utf8mb4_unicode_ci NOT NULL,
  `park_id` bigint(20) UNSIGNED NULL DEFAULT 0,
  `amount` int(10) UNSIGNED NOT NULL COMMENT '面额',
  `use_min_amount` int(10) UNSIGNED NOT NULL DEFAULT 0 COMMENT '满xx减xx',
  `use_time` timestamp(0) NULL DEFAULT NULL COMMENT '使用时间',
  `expiration_time` timestamp(0) NULL DEFAULT NULL COMMENT '过期时间',
  `deleted_at` timestamp(0) NULL DEFAULT NULL,
  `created_at` timestamp(0) NULL DEFAULT NULL,
  `updated_at` timestamp(0) NULL DEFAULT NULL,
  `start_time` timestamp(0) NULL DEFAULT NULL COMMENT '发放开始时间',
  `end_time` timestamp(0) NULL DEFAULT NULL COMMENT '发放结束时间',
  `distribution_method` int(11) NOT NULL DEFAULT 0 COMMENT '发放方式：1-平台推送，2-app二维码，3-微信/支付宝二维码，4-分享链接',
  `status` varchar(255) CHARACTER SET utf8mb4 COLLATE utf8mb4_unicode_ci NOT NULL DEFAULT 'pending' COMMENT '状态：pending正常，used已使用，expired失效，invalid作废',
  PRIMARY KEY (`id`) USING BTREE,
  UNIQUE INDEX `user_coupons_no_unique`(`no`) USING BTREE,
  INDEX `user_coupons_user_id_foreign`(`user_id`) USING BTREE,
  INDEX `user_coupons_coupon_id_foreign`(`coupon_id`) USING BTREE,
  INDEX `user_coupons_order_id_index`(`order_id`) USING BTREE,
  CONSTRAINT `user_coupons_coupon_id_foreign` FOREIGN KEY (`coupon_id`) REFERENCES `coupons` (`id`) ON DELETE CASCADE ON UPDATE RESTRICT,
  CONSTRAINT `user_coupons_user_id_foreign` FOREIGN KEY (`user_id`) REFERENCES `users` (`id`) ON DELETE CASCADE ON UPDATE RESTRICT
) ENGINE = InnoDB AUTO_INCREMENT = 143 CHARACTER SET = utf8mb4 COLLATE = utf8mb4_unicode_ci ROW_FORMAT = Dynamic;

-- ----------------------------
-- Table structure for user_devices
-- ----------------------------
DROP TABLE IF EXISTS `user_devices`;
CREATE TABLE `user_devices`  (
  `id` bigint(20) UNSIGNED NOT NULL AUTO_INCREMENT,
  `user_id` bigint(20) UNSIGNED NULL DEFAULT NULL,
  `platform` varchar(255) CHARACTER SET utf8mb4 COLLATE utf8mb4_unicode_ci NOT NULL COMMENT 'ios,android',
  `brand` varchar(255) CHARACTER SET utf8mb4 COLLATE utf8mb4_unicode_ci NULL DEFAULT NULL COMMENT '品牌',
  `model` varchar(255) CHARACTER SET utf8mb4 COLLATE utf8mb4_unicode_ci NULL DEFAULT NULL COMMENT '型号',
  `uid` varchar(255) CHARACTER SET utf8mb4 COLLATE utf8mb4_unicode_ci NULL DEFAULT NULL COMMENT '设备id',
  `version` varchar(255) CHARACTER SET utf8mb4 COLLATE utf8mb4_unicode_ci NULL DEFAULT NULL COMMENT '客户端版本',
  `jpush_id` varchar(255) CHARACTER SET utf8mb4 COLLATE utf8mb4_unicode_ci NULL DEFAULT NULL COMMENT '极光 用户id',
  `deleted_at` timestamp(0) NULL DEFAULT NULL,
  `created_at` timestamp(0) NULL DEFAULT NULL,
  `updated_at` timestamp(0) NULL DEFAULT NULL,
  PRIMARY KEY (`id`) USING BTREE,
  INDEX `user_devices_user_id_foreign`(`user_id`) USING BTREE,
  CONSTRAINT `user_devices_user_id_foreign` FOREIGN KEY (`user_id`) REFERENCES `users` (`id`) ON DELETE CASCADE ON UPDATE RESTRICT
) ENGINE = InnoDB AUTO_INCREMENT = 244 CHARACTER SET = utf8mb4 COLLATE = utf8mb4_unicode_ci ROW_FORMAT = Dynamic;

-- ----------------------------
-- Table structure for user_integrals
-- ----------------------------
DROP TABLE IF EXISTS `user_integrals`;
CREATE TABLE `user_integrals`  (
  `id` bigint(20) UNSIGNED NOT NULL AUTO_INCREMENT,
  `user_id` bigint(20) UNSIGNED NOT NULL,
  `operation` int(11) NOT NULL DEFAULT 0 COMMENT '操作',
  `integral_num` int(11) NULL DEFAULT NULL COMMENT '操作积分数',
  `balance` int(11) NULL DEFAULT NULL COMMENT '剩余积分数',
  `order_type` varchar(255) CHARACTER SET utf8mb4 COLLATE utf8mb4_unicode_ci NOT NULL,
  `order_id` bigint(20) UNSIGNED NOT NULL,
  `deleted_at` timestamp(0) NULL DEFAULT NULL,
  `created_at` timestamp(0) NULL DEFAULT NULL,
  `updated_at` timestamp(0) NULL DEFAULT NULL,
  PRIMARY KEY (`id`) USING BTREE,
  INDEX `user_integrals_user_id_foreign`(`user_id`) USING BTREE,
  INDEX `user_integrals_order_type_order_id_index`(`order_type`, `order_id`) USING BTREE,
  CONSTRAINT `user_integrals_user_id_foreign` FOREIGN KEY (`user_id`) REFERENCES `users` (`id`) ON DELETE CASCADE ON UPDATE RESTRICT
) ENGINE = InnoDB AUTO_INCREMENT = 1 CHARACTER SET = utf8mb4 COLLATE = utf8mb4_unicode_ci ROW_FORMAT = Dynamic;

-- ----------------------------
-- Table structure for user_login_logs
-- ----------------------------
DROP TABLE IF EXISTS `user_login_logs`;
CREATE TABLE `user_login_logs`  (
  `id` bigint(20) UNSIGNED NOT NULL AUTO_INCREMENT,
  `user_id` bigint(20) UNSIGNED NOT NULL,
  `last_ip` varchar(48) CHARACTER SET utf8mb4 COLLATE utf8mb4_unicode_ci NOT NULL,
  `created_at` timestamp(0) NULL DEFAULT NULL,
  `updated_at` timestamp(0) NULL DEFAULT NULL,
  PRIMARY KEY (`id`) USING BTREE,
  INDEX `user_login_logs_user_id_foreign`(`user_id`) USING BTREE,
  CONSTRAINT `user_login_logs_user_id_foreign` FOREIGN KEY (`user_id`) REFERENCES `users` (`id`) ON DELETE RESTRICT ON UPDATE RESTRICT
) ENGINE = InnoDB AUTO_INCREMENT = 2495 CHARACTER SET = utf8mb4 COLLATE = utf8mb4_unicode_ci ROW_FORMAT = Dynamic;

-- ----------------------------
-- Table structure for user_messages
-- ----------------------------
DROP TABLE IF EXISTS `user_messages`;
CREATE TABLE `user_messages`  (
  `id` bigint(20) UNSIGNED NOT NULL AUTO_INCREMENT,
  `user_id` bigint(20) UNSIGNED NOT NULL COMMENT '用户ID',
  `title` varchar(255) CHARACTER SET utf8mb4 COLLATE utf8mb4_unicode_ci NULL DEFAULT NULL COMMENT '消息主题',
  `content` text CHARACTER SET utf8mb4 COLLATE utf8mb4_unicode_ci NULL COMMENT '消息内容',
  `imgurl` varchar(255) CHARACTER SET utf8mb4 COLLATE utf8mb4_unicode_ci NULL DEFAULT NULL COMMENT '图片信息地址',
  `type` int(11) NOT NULL DEFAULT 0 COMMENT '消息类型：0-系统通知 1-订单通知 2-活动推广 3-充值提现',
  `read_time` timestamp(0) NULL DEFAULT NULL COMMENT '已读时间',
  `source_type` varchar(255) CHARACTER SET utf8mb4 COLLATE utf8mb4_unicode_ci NULL DEFAULT '',
  `source_id` bigint(20) UNSIGNED NULL DEFAULT NULL,
  `deleted_at` timestamp(0) NULL DEFAULT NULL,
  `created_at` timestamp(0) NULL DEFAULT NULL,
  `updated_at` timestamp(0) NULL DEFAULT NULL,
  `message_id` bigint(20) UNSIGNED NULL DEFAULT NULL,
  PRIMARY KEY (`id`) USING BTREE,
  INDEX `user_messages_user_id_foreign`(`user_id`) USING BTREE,
  INDEX `user_messages_source_type_source_id_index`(`source_type`, `source_id`) USING BTREE,
  INDEX `user_messages_message_id_foreign`(`message_id`) USING BTREE,
  CONSTRAINT `user_messages_message_id_foreign` FOREIGN KEY (`message_id`) REFERENCES `messages` (`id`) ON DELETE RESTRICT ON UPDATE RESTRICT,
  CONSTRAINT `user_messages_user_id_foreign` FOREIGN KEY (`user_id`) REFERENCES `users` (`id`) ON DELETE CASCADE ON UPDATE RESTRICT
) ENGINE = InnoDB AUTO_INCREMENT = 549 CHARACTER SET = utf8mb4 COLLATE = utf8mb4_unicode_ci ROW_FORMAT = Dynamic;

-- ----------------------------
-- Table structure for user_orders
-- ----------------------------
DROP TABLE IF EXISTS `user_orders`;
CREATE TABLE `user_orders`  (
  `id` bigint(20) UNSIGNED NOT NULL AUTO_INCREMENT,
  `order_no` varchar(255) CHARACTER SET utf8mb4 COLLATE utf8mb4_unicode_ci NOT NULL COMMENT '订单编号',
  `user_id` bigint(20) UNSIGNED NOT NULL,
  `park_id` bigint(20) UNSIGNED NOT NULL COMMENT '停车场ID',
  `coupon_id` bigint(20) UNSIGNED NULL DEFAULT NULL COMMENT '优惠券ID',
  `car_stop_id` bigint(20) UNSIGNED NULL DEFAULT NULL COMMENT '停车记录ID',
  `user_car_id` bigint(20) UNSIGNED NULL DEFAULT NULL,
  `car_rent_id` bigint(20) UNSIGNED NOT NULL,
  `car_apt_id` bigint(20) UNSIGNED NULL DEFAULT NULL,
  `subscribe_amount` int(10) UNSIGNED NOT NULL DEFAULT 0 COMMENT '预约定金',
  `amount` int(10) UNSIGNED NOT NULL COMMENT '停车费用',
  `parking_fee` int(10) UNSIGNED NOT NULL COMMENT '向app展示支付的停车费用',
  `discount_amount` int(10) UNSIGNED NOT NULL DEFAULT 0 COMMENT '优惠费用',
  `refund_amount` int(10) UNSIGNED NOT NULL DEFAULT 0 COMMENT '退款金额',
  `total_amount` int(10) UNSIGNED NOT NULL COMMENT '总金额，结算金额',
  `payment_no` varchar(255) CHARACTER SET utf8mb4 COLLATE utf8mb4_unicode_ci NULL DEFAULT NULL COMMENT '交易号',
  `payment_gateway` varchar(255) CHARACTER SET utf8mb4 COLLATE utf8mb4_unicode_ci NULL DEFAULT NULL COMMENT '支付方式：支付宝、微信、钱包、免密（支付宝、微信）、现金',
  `status` varchar(255) CHARACTER SET utf8mb4 COLLATE utf8mb4_unicode_ci NOT NULL DEFAULT 'pending' COMMENT 'pending/paid/cancelled/failed/refunded/finished/commented',
  `fail_status` varchar(255) CHARACTER SET utf8mb4 COLLATE utf8mb4_unicode_ci NULL DEFAULT NULL COMMENT '失败理由',
  `body` varchar(255) CHARACTER SET utf8mb4 COLLATE utf8mb4_unicode_ci NULL DEFAULT NULL COMMENT '订单描述',
  `explain` varchar(255) CHARACTER SET utf8mb4 COLLATE utf8mb4_unicode_ci NOT NULL DEFAULT '' COMMENT '操作说明',
  `order_type` int(11) NOT NULL DEFAULT 1 COMMENT '1-预约 2-停车',
  `paid_at` timestamp(0) NULL DEFAULT NULL,
  `cancelled_at` timestamp(0) NULL DEFAULT NULL,
  `refunded_at` timestamp(0) NULL DEFAULT NULL,
  `finished_at` timestamp(0) NULL DEFAULT NULL,
  `expired_at` timestamp(0) NULL DEFAULT NULL,
  `failed_at` timestamp(0) NULL DEFAULT NULL,
  `commented_at` timestamp(0) NULL DEFAULT NULL,
  `car_in_time` timestamp(0) NULL DEFAULT NULL COMMENT '进场时间',
  `car_out_time` timestamp(0) NULL DEFAULT NULL COMMENT '离场时间',
  `car_stop_time` timestamp(0) NULL DEFAULT NULL COMMENT '停车时间',
  `deleted_at` timestamp(0) NULL DEFAULT NULL,
  `created_at` timestamp(0) NULL DEFAULT NULL,
  `updated_at` timestamp(0) NULL DEFAULT NULL,
  `car_num` varchar(255) CHARACTER SET utf8mb4 COLLATE utf8mb4_unicode_ci NOT NULL COMMENT '停车的车牌号',
  `renewal_notice` tinyint(1) NOT NULL DEFAULT 0 COMMENT '续费提醒，0未取消，1取消 订单结束或手动取消',
  `cancel_renewal_notice` timestamp(0) NULL DEFAULT NULL,
  `final_amount` int(10) UNSIGNED NOT NULL DEFAULT 0 COMMENT '尾款,用户未支付金额',
  `final_paid_at` timestamp(0) NULL DEFAULT NULL COMMENT '尾款支付时间',
  PRIMARY KEY (`id`) USING BTREE,
  UNIQUE INDEX `user_orders_order_no_unique`(`order_no`) USING BTREE,
  INDEX `user_orders_user_id_foreign`(`user_id`) USING BTREE,
  INDEX `user_orders_park_id_foreign`(`park_id`) USING BTREE,
  INDEX `user_orders_coupon_id_foreign`(`coupon_id`) USING BTREE,
  INDEX `user_orders_car_stop_id_foreign`(`car_stop_id`) USING BTREE,
  INDEX `user_orders_user_car_id_index`(`user_car_id`) USING BTREE,
  INDEX `user_orders_car_apt_id_index`(`car_apt_id`) USING BTREE,
  INDEX `user_orders_status_index`(`status`) USING BTREE,
  CONSTRAINT `user_orders_car_stop_id_foreign` FOREIGN KEY (`car_stop_id`) REFERENCES `car_stops` (`id`) ON DELETE CASCADE ON UPDATE RESTRICT,
  CONSTRAINT `user_orders_coupon_id_foreign` FOREIGN KEY (`coupon_id`) REFERENCES `coupons` (`id`) ON DELETE CASCADE ON UPDATE RESTRICT,
  CONSTRAINT `user_orders_park_id_foreign` FOREIGN KEY (`park_id`) REFERENCES `parks` (`id`) ON DELETE CASCADE ON UPDATE RESTRICT,
  CONSTRAINT `user_orders_user_id_foreign` FOREIGN KEY (`user_id`) REFERENCES `users` (`id`) ON DELETE CASCADE ON UPDATE RESTRICT
) ENGINE = InnoDB AUTO_INCREMENT = 1387 CHARACTER SET = utf8mb4 COLLATE = utf8mb4_unicode_ci ROW_FORMAT = Dynamic;

-- ----------------------------
-- Table structure for user_parking_spaces
-- ----------------------------
DROP TABLE IF EXISTS `user_parking_spaces`;
CREATE TABLE `user_parking_spaces`  (
  `id` bigint(20) UNSIGNED NOT NULL AUTO_INCREMENT,
  `user_id` bigint(20) UNSIGNED NOT NULL,
  `park_id` bigint(20) UNSIGNED NOT NULL,
  `certificates` longtext CHARACTER SET utf8mb4 COLLATE utf8mb4_bin NOT NULL,
  `contracts` longtext CHARACTER SET utf8mb4 COLLATE utf8mb4_bin NULL,
  `number` varchar(255) CHARACTER SET utf8mb4 COLLATE utf8mb4_unicode_ci NOT NULL COMMENT '车位编号',
  `id_card_type` tinyint(4) NOT NULL DEFAULT 1 COMMENT '证件类型:1身份证，2驾驶证，3护照',
  `id_card_number` varchar(255) CHARACTER SET utf8mb4 COLLATE utf8mb4_unicode_ci NULL DEFAULT NULL COMMENT '身份证号码',
  `id_card_name` varchar(255) CHARACTER SET utf8mb4 COLLATE utf8mb4_unicode_ci NULL DEFAULT NULL COMMENT '身份证姓名',
  `park_space_id` bigint(20) UNSIGNED NULL DEFAULT NULL,
  `status` varchar(255) CHARACTER SET utf8mb4 COLLATE utf8mb4_unicode_ci NOT NULL DEFAULT 'pending' COMMENT '申请中、已审核、未通过',
  `remark` varchar(255) CHARACTER SET utf8mb4 COLLATE utf8mb4_unicode_ci NULL DEFAULT NULL,
  `property_id` bigint(20) UNSIGNED NULL DEFAULT NULL,
  `admin_id` bigint(20) UNSIGNED NULL DEFAULT NULL,
  `allowed_at` timestamp(0) NULL DEFAULT NULL,
  `opened_at` timestamp(0) NULL DEFAULT NULL,
  `finished_at` timestamp(0) NULL DEFAULT NULL,
  `failed_at` timestamp(0) NULL DEFAULT NULL,
  `deleted_at` timestamp(0) NULL DEFAULT NULL,
  `created_at` timestamp(0) NULL DEFAULT NULL,
  `updated_at` timestamp(0) NULL DEFAULT NULL,
  PRIMARY KEY (`id`) USING BTREE,
  INDEX `user_parking_spaces_user_id_index`(`user_id`) USING BTREE,
  INDEX `user_parking_spaces_park_id_index`(`park_id`) USING BTREE,
  INDEX `user_parking_spaces_park_space_id_index`(`park_space_id`) USING BTREE,
  INDEX `user_parking_spaces_property_id_index`(`property_id`) USING BTREE,
  INDEX `user_parking_spaces_admin_id_index`(`admin_id`) USING BTREE
) ENGINE = InnoDB AUTO_INCREMENT = 60 CHARACTER SET = utf8mb4 COLLATE = utf8mb4_unicode_ci ROW_FORMAT = Dynamic;

-- ----------------------------
-- Table structure for user_payment_logs
-- ----------------------------
DROP TABLE IF EXISTS `user_payment_logs`;
CREATE TABLE `user_payment_logs`  (
  `id` bigint(20) UNSIGNED NOT NULL AUTO_INCREMENT COMMENT '主键ID',
  `user_id` bigint(20) UNSIGNED NOT NULL COMMENT '用户ID',
  `order_no` bigint(20) UNSIGNED NOT NULL COMMENT '订单编号',
  `trade_no` varchar(255) CHARACTER SET utf8mb4 COLLATE utf8mb4_unicode_ci NULL DEFAULT NULL COMMENT '支付平台交易号',
  `buyer_account` varchar(255) CHARACTER SET utf8mb4 COLLATE utf8mb4_unicode_ci NULL DEFAULT NULL COMMENT '支付平台用户账号',
  `arrival_account` varchar(255) CHARACTER SET utf8mb4 COLLATE utf8mb4_unicode_ci NULL DEFAULT NULL COMMENT '用户到账账号',
  `money_amount` int(11) NOT NULL DEFAULT 0 COMMENT '金额',
  `request_info` varchar(255) CHARACTER SET utf8mb4 COLLATE utf8mb4_unicode_ci NULL DEFAULT NULL COMMENT '请求原始信息',
  `callback_info` varchar(255) CHARACTER SET utf8mb4 COLLATE utf8mb4_unicode_ci NULL DEFAULT NULL COMMENT '回调内容原始信息',
  `account_type` int(11) NOT NULL COMMENT '账户类型：1-余额 2-微信 3-支付宝',
  `business_type` int(11) NOT NULL DEFAULT 0 COMMENT '业务类型：0-默认 1-充值 2-支付 3-提现 4-退款',
  `order_type` varchar(255) CHARACTER SET utf8mb4 COLLATE utf8mb4_unicode_ci NOT NULL,
  `order_id` bigint(20) UNSIGNED NOT NULL,
  `deleted_at` timestamp(0) NULL DEFAULT NULL,
  `created_at` timestamp(0) NULL DEFAULT NULL,
  `updated_at` timestamp(0) NULL DEFAULT NULL,
  `pay_type` tinyint(3) UNSIGNED NOT NULL DEFAULT 1 COMMENT '支付类型:1-余额抵扣，2-第三方抵扣，3-积分抵扣',
  `fee` int(10) UNSIGNED NOT NULL DEFAULT 0,
  PRIMARY KEY (`id`) USING BTREE,
  INDEX `user_payment_logs_user_id_foreign`(`user_id`) USING BTREE,
  INDEX `user_payment_logs_order_type_order_id_index`(`order_type`, `order_id`) USING BTREE,
  CONSTRAINT `user_payment_logs_user_id_foreign` FOREIGN KEY (`user_id`) REFERENCES `users` (`id`) ON DELETE CASCADE ON UPDATE RESTRICT
) ENGINE = InnoDB AUTO_INCREMENT = 21 CHARACTER SET = utf8mb4 COLLATE = utf8mb4_unicode_ci ROW_FORMAT = Dynamic;

-- ----------------------------
-- Table structure for user_refunds
-- ----------------------------
DROP TABLE IF EXISTS `user_refunds`;
CREATE TABLE `user_refunds`  (
  `id` bigint(20) UNSIGNED NOT NULL AUTO_INCREMENT,
  `user_id` bigint(20) UNSIGNED NOT NULL,
  `order_id` bigint(20) UNSIGNED NOT NULL COMMENT '订单ID',
  `order_type` varchar(255) CHARACTER SET utf8mb4 COLLATE utf8mb4_unicode_ci NULL DEFAULT NULL,
  `amount` int(10) UNSIGNED NOT NULL DEFAULT 0 COMMENT '订单金额',
  `refunded_amount` int(10) UNSIGNED NOT NULL DEFAULT 0 COMMENT '退款金额',
  `transfer_account` varchar(255) CHARACTER SET utf8mb4 COLLATE utf8mb4_unicode_ci NULL DEFAULT NULL COMMENT '转账账户',
  `type` int(11) NOT NULL DEFAULT 1 COMMENT '退款类型：1-普通退款 2-赔付退款',
  `refund_no` varchar(255) CHARACTER SET utf8mb4 COLLATE utf8mb4_unicode_ci NULL DEFAULT NULL COMMENT '退款订单号',
  `refund_id` varchar(255) CHARACTER SET utf8mb4 COLLATE utf8mb4_unicode_ci NULL DEFAULT NULL COMMENT '服务商退款单号',
  `reason` text CHARACTER SET utf8mb4 COLLATE utf8mb4_unicode_ci NULL COMMENT '退款原因',
  `remarks` text CHARACTER SET utf8mb4 COLLATE utf8mb4_unicode_ci NULL COMMENT '备注',
  `operator` varchar(255) CHARACTER SET utf8mb4 COLLATE utf8mb4_unicode_ci NULL DEFAULT NULL COMMENT '操作员',
  `refunded_at` timestamp(0) NULL DEFAULT NULL,
  `failed_at` timestamp(0) NULL DEFAULT NULL,
  `created_at` timestamp(0) NULL DEFAULT NULL,
  `updated_at` timestamp(0) NULL DEFAULT NULL,
  `deleted_at` timestamp(0) NULL DEFAULT NULL,
  `refund_category` int(11) NULL DEFAULT NULL COMMENT '退款类别：1-预约退款 2-停车多出费用退款',
  `refund_way` int(11) NULL DEFAULT NULL COMMENT '退款方式：1-原路退还 2-转账退款',
  `refund_channels` int(11) NULL DEFAULT NULL COMMENT '退款渠道：1-微信 2-支付宝',
  `status` smallint(5) UNSIGNED NOT NULL DEFAULT 1 COMMENT '退款状态，1-未退款，2-申请中，3-已退款，4-已拒绝',
  PRIMARY KEY (`id`) USING BTREE,
  INDEX `user_refunds_user_id_foreign`(`user_id`) USING BTREE,
  INDEX `user_refunds_refund_no_index`(`refund_no`) USING BTREE,
  INDEX `user_refunds_refund_id_index`(`refund_id`) USING BTREE,
  INDEX `user_refunds_order_type_order_id_index`(`order_id`, `order_type`) USING BTREE,
  CONSTRAINT `user_refunds_user_id_foreign` FOREIGN KEY (`user_id`) REFERENCES `users` (`id`) ON DELETE CASCADE ON UPDATE RESTRICT
) ENGINE = InnoDB AUTO_INCREMENT = 97 CHARACTER SET = utf8mb4 COLLATE = utf8mb4_unicode_ci ROW_FORMAT = Dynamic;

-- ----------------------------
-- Table structure for user_searches
-- ----------------------------
DROP TABLE IF EXISTS `user_searches`;
CREATE TABLE `user_searches`  (
  `id` bigint(20) UNSIGNED NOT NULL AUTO_INCREMENT,
  `user_id` bigint(20) UNSIGNED NOT NULL,
  `keyword` varchar(255) CHARACTER SET utf8mb4 COLLATE utf8mb4_unicode_ci NOT NULL,
  `click_num` int(10) UNSIGNED NOT NULL DEFAULT 0,
  `longitude` varchar(255) CHARACTER SET utf8mb4 COLLATE utf8mb4_unicode_ci NULL DEFAULT NULL,
  `latitude` varchar(255) CHARACTER SET utf8mb4 COLLATE utf8mb4_unicode_ci NULL DEFAULT NULL,
  `deleted_at` timestamp(0) NULL DEFAULT NULL,
  `created_at` timestamp(0) NULL DEFAULT NULL,
  `updated_at` timestamp(0) NULL DEFAULT NULL,
  PRIMARY KEY (`id`) USING BTREE,
  INDEX `user_searches_user_id_foreign`(`user_id`) USING BTREE,
  CONSTRAINT `user_searches_user_id_foreign` FOREIGN KEY (`user_id`) REFERENCES `users` (`id`) ON DELETE CASCADE ON UPDATE RESTRICT
) ENGINE = InnoDB AUTO_INCREMENT = 125 CHARACTER SET = utf8mb4 COLLATE = utf8mb4_unicode_ci ROW_FORMAT = Dynamic;

-- ----------------------------
-- Table structure for users
-- ----------------------------
DROP TABLE IF EXISTS `users`;
CREATE TABLE `users`  (
  `id` bigint(20) UNSIGNED NOT NULL AUTO_INCREMENT,
  `nickname` varchar(191) CHARACTER SET utf8mb4 COLLATE utf8mb4_unicode_ci NOT NULL DEFAULT '' COMMENT '昵称',
  `mobile` varchar(191) CHARACTER SET utf8mb4 COLLATE utf8mb4_unicode_ci NOT NULL,
  `password` varchar(191) CHARACTER SET utf8mb4 COLLATE utf8mb4_unicode_ci NOT NULL,
  `headimgurl` varchar(191) CHARACTER SET utf8mb4 COLLATE utf8mb4_unicode_ci NULL DEFAULT NULL COMMENT '头像',
  `email` varchar(191) CHARACTER SET utf8mb4 COLLATE utf8mb4_unicode_ci NULL DEFAULT NULL COMMENT '邮箱',
  `address` varchar(191) CHARACTER SET utf8mb4 COLLATE utf8mb4_unicode_ci NULL DEFAULT NULL COMMENT '常用地址',
  `sex` int(11) NOT NULL DEFAULT 0 COMMENT '性别：0-默认 1-男 2-女',
  `integral` int(11) NOT NULL DEFAULT 0 COMMENT '积分',
  `balance` int(11) NOT NULL DEFAULT 0 COMMENT '余额',
  `rental_amount` int(10) UNSIGNED NOT NULL DEFAULT 0 COMMENT '车位出租收益',
  `cache` longtext CHARACTER SET utf8mb4 COLLATE utf8mb4_bin NULL,
  `is_verify` int(1) NULL DEFAULT 0,
  `verified_at` timestamp(0) NULL DEFAULT NULL,
  `banned_withdraw` timestamp(0) NULL DEFAULT NULL COMMENT '冻结提现',
  `banned_login` timestamp(0) NULL DEFAULT NULL,
  `remember_token` varchar(100) CHARACTER SET utf8mb4 COLLATE utf8mb4_unicode_ci NULL DEFAULT NULL,
  `deleted_at` timestamp(0) NULL DEFAULT NULL,
  `created_at` timestamp(0) NULL DEFAULT NULL,
  `updated_at` timestamp(0) NULL DEFAULT NULL,
  `user_type` varchar(255) CHARACTER SET utf8mb4 COLLATE utf8mb4_unicode_ci NULL DEFAULT NULL COMMENT '用户类型:1普通用户 2认证车主 3vip用户',
  PRIMARY KEY (`id`) USING BTREE,
  UNIQUE INDEX `users_mobile_unique`(`mobile`) USING BTREE
) ENGINE = InnoDB AUTO_INCREMENT = 2066 CHARACTER SET = utf8mb4 COLLATE = utf8mb4_unicode_ci ROW_FORMAT = Dynamic;

-- ----------------------------
-- Table structure for versions
-- ----------------------------
DROP TABLE IF EXISTS `versions`;
CREATE TABLE `versions`  (
  `id` bigint(20) UNSIGNED NOT NULL AUTO_INCREMENT,
  `version_no` varchar(255) CHARACTER SET utf8mb4 COLLATE utf8mb4_unicode_ci NOT NULL COMMENT '版本号',
  `platform` varchar(255) CHARACTER SET utf8mb4 COLLATE utf8mb4_unicode_ci NOT NULL COMMENT 'App平台,ios,huawei,xiaomi...',
  `update_description` varchar(255) CHARACTER SET utf8mb4 COLLATE utf8mb4_unicode_ci NULL DEFAULT NULL COMMENT '更新说明',
  `resource_url` text CHARACTER SET utf8mb4 COLLATE utf8mb4_unicode_ci NULL COMMENT '下载资源链接',
  `is_force` int(11) NOT NULL DEFAULT 0 COMMENT '是否强制更新：0-不强制 1-强制',
  `user_id` int(11) NULL DEFAULT NULL COMMENT '发布人员的id',
  `deleted_at` timestamp(0) NULL DEFAULT NULL,
  `created_at` timestamp(0) NULL DEFAULT NULL,
  `updated_at` timestamp(0) NULL DEFAULT NULL,
  PRIMARY KEY (`id`) USING BTREE
) ENGINE = InnoDB AUTO_INCREMENT = 3 CHARACTER SET = utf8mb4 COLLATE = utf8mb4_unicode_ci ROW_FORMAT = Dynamic;

-- ----------------------------
-- Table structure for withdrawals
-- ----------------------------
DROP TABLE IF EXISTS `withdrawals`;
CREATE TABLE `withdrawals`  (
  `id` bigint(20) UNSIGNED NOT NULL AUTO_INCREMENT,
  `withdrawal_no` varchar(255) CHARACTER SET utf8mb4 COLLATE utf8mb4_unicode_ci NOT NULL COMMENT '提现单编号',
  `person_type` int(11) NOT NULL COMMENT '提取人员类型 1-物业提现 2-车主提现',
  `apply_time` timestamp(0) NULL DEFAULT NULL COMMENT '申请时间',
  `apply_money` int(10) UNSIGNED NOT NULL COMMENT '申请金额',
  `status` int(11) NOT NULL DEFAULT 1 COMMENT '1-待处理 2-汇款中 3-已完成',
  `gateway` varchar(255) CHARACTER SET utf8mb4 COLLATE utf8mb4_unicode_ci NOT NULL COMMENT '提现平台: 微信、支付宝',
  `account` varchar(255) CHARACTER SET utf8mb4 COLLATE utf8mb4_unicode_ci NULL DEFAULT NULL COMMENT '账户',
  `account_name` varchar(255) CHARACTER SET utf8mb4 COLLATE utf8mb4_unicode_ci NULL DEFAULT NULL,
  `park_id` bigint(20) UNSIGNED NULL DEFAULT NULL,
  `user_type` varchar(255) CHARACTER SET utf8mb4 COLLATE utf8mb4_unicode_ci NOT NULL,
  `user_id` bigint(20) UNSIGNED NOT NULL,
  `admin_id` bigint(20) UNSIGNED NULL DEFAULT NULL COMMENT '审核人',
  `audit_time` timestamp(0) NULL DEFAULT NULL COMMENT '审核时间',
  `remark` varchar(255) CHARACTER SET utf8mb4 COLLATE utf8mb4_unicode_ci NULL DEFAULT NULL COMMENT '备注',
  `completion_time` timestamp(0) NULL DEFAULT NULL COMMENT '完成时间',
  `deleted_at` timestamp(0) NULL DEFAULT NULL,
  `created_at` timestamp(0) NULL DEFAULT NULL,
  `updated_at` timestamp(0) NULL DEFAULT NULL,
  `business_type` int(11) NULL DEFAULT NULL COMMENT '1-物业贷款 2-车位出租收益',
  PRIMARY KEY (`id`) USING BTREE,
  UNIQUE INDEX `withdrawals_withdrawal_no_unique`(`withdrawal_no`) USING BTREE,
  INDEX `withdrawals_park_id_foreign`(`park_id`) USING BTREE,
  INDEX `withdrawals_user_type_user_id_index`(`user_type`, `user_id`) USING BTREE,
  CONSTRAINT `withdrawals_park_id_foreign` FOREIGN KEY (`park_id`) REFERENCES `parks` (`id`) ON DELETE CASCADE ON UPDATE RESTRICT
) ENGINE = InnoDB AUTO_INCREMENT = 83 CHARACTER SET = utf8mb4 COLLATE = utf8mb4_unicode_ci ROW_FORMAT = Dynamic;

SET FOREIGN_KEY_CHECKS = 1;
