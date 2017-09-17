-- phpMyAdmin SQL Dump
-- version 4.5.4.1deb2ubuntu2
-- http://www.phpmyadmin.net
--
-- Host: 127.0.0.1
-- Generation Time: 2017-09-17 16:19:02
-- 服务器版本： 5.7.17-0ubuntu0.16.04.1
-- PHP Version: 7.0.13-0ubuntu0.16.04.1

SET SQL_MODE = "NO_AUTO_VALUE_ON_ZERO";
SET time_zone = "+00:00";


/*!40101 SET @OLD_CHARACTER_SET_CLIENT=@@CHARACTER_SET_CLIENT */;
/*!40101 SET @OLD_CHARACTER_SET_RESULTS=@@CHARACTER_SET_RESULTS */;
/*!40101 SET @OLD_COLLATION_CONNECTION=@@COLLATION_CONNECTION */;
/*!40101 SET NAMES utf8mb4 */;

--
-- Database: `yii_full_beginning`
--

-- --------------------------------------------------------

--
-- 表的结构 `authassignment`
--

CREATE TABLE IF NOT EXISTS `authassignment` (
  `itemname` varchar(64) NOT NULL COMMENT '角色名称',
  `userid` varchar(64) NOT NULL COMMENT '用户ID',
  `bizrule` text COMMENT '业务规则',
  `data` text COMMENT '业务数据',
  `createdby` char(15) DEFAULT NULL COMMENT '创建人',
  `createddate` timestamp NULL DEFAULT CURRENT_TIMESTAMP COMMENT '创建时间',
  `updatedby` char(15) DEFAULT NULL COMMENT '更新人',
  `updateddate` timestamp NULL DEFAULT NULL COMMENT '更新时间',
  PRIMARY KEY (`itemname`,`userid`)
) ENGINE=MyISAM DEFAULT CHARSET=utf8 COMMENT='用户角色指派数据表' ROW_FORMAT=DYNAMIC;

--
-- 转存表中的数据 `authassignment`
--

INSERT INTO `authassignment` (`itemname`, `userid`, `bizrule`, `data`, `createdby`, `createddate`, `updatedby`, `updateddate`) VALUES
('Guest', '1', '', 'N;', NULL, '2015-12-11 16:00:00', NULL, NULL),
('Authenticated', '1', NULL, 'N;', NULL, '2015-12-11 16:00:00', NULL, NULL),
('Admin', '1', NULL, 'N;', NULL, '2015-12-11 16:00:00', NULL, NULL);

-- --------------------------------------------------------

--
-- 表的结构 `authitem`
--

CREATE TABLE IF NOT EXISTS `authitem` (
  `name` varchar(64) NOT NULL COMMENT '角色名称',
  `type` int(11) NOT NULL COMMENT '类型',
  `description` text COMMENT '描述',
  `bizrule` text COMMENT '业务规则',
  `data` text COMMENT '业务数据',
  `createdby` char(15) DEFAULT NULL COMMENT '创建人',
  `createddate` timestamp NULL DEFAULT CURRENT_TIMESTAMP COMMENT '创建时间',
  `updatedby` char(15) DEFAULT NULL COMMENT '更新人',
  `updateddate` timestamp NULL DEFAULT NULL COMMENT '更新时间',
  PRIMARY KEY (`name`)
) ENGINE=MyISAM DEFAULT CHARSET=utf8 COMMENT='用户角色数据表' ROW_FORMAT=DYNAMIC;

--
-- 转存表中的数据 `authitem`
--

INSERT INTO `authitem` (`name`, `type`, `description`, `bizrule`, `data`, `createdby`, `createddate`, `updatedby`, `updateddate`) VALUES
('Admin', 2, '管理员', '', 'N;', NULL, '2015-12-11 16:00:00', NULL, NULL),
('Shop', 2, '经销商', NULL, 'N;', NULL, '2016-08-21 19:12:38', NULL, NULL),
('Apidoc.*', 1, NULL, NULL, 'N;', NULL, '2016-08-21 19:15:04', NULL, NULL),
('Default.*', 1, NULL, NULL, 'N;', NULL, '2016-08-21 19:15:04', NULL, NULL),
('System.*', 1, NULL, NULL, 'N;', NULL, '2016-08-21 19:15:04', NULL, NULL),
('User.*', 1, NULL, NULL, 'N;', NULL, '2016-08-21 19:15:04', NULL, NULL);

-- --------------------------------------------------------

--
-- 表的结构 `authitemchild`
--

CREATE TABLE IF NOT EXISTS `authitemchild` (
  `parent` varchar(64) NOT NULL,
  `child` varchar(64) NOT NULL,
  PRIMARY KEY (`parent`,`child`),
  KEY `child` (`child`)
) ENGINE=MyISAM DEFAULT CHARSET=utf8 ROW_FORMAT=DYNAMIC;

-- --------------------------------------------------------

--
-- 表的结构 `configs`
--

CREATE TABLE IF NOT EXISTS `configs` (
  `id` int(11) NOT NULL AUTO_INCREMENT,
  `key` varchar(50) NOT NULL COMMENT '参数项名称',
  `value` text COMMENT '参数项值',
  `remark` varchar(100) NOT NULL DEFAULT '' COMMENT '备注',
  PRIMARY KEY (`id`),
  UNIQUE KEY `key` (`key`),
  KEY `key_2` (`key`)
) ENGINE=MyISAM AUTO_INCREMENT=13 DEFAULT CHARSET=utf8 COMMENT='配置项目数据表' ROW_FORMAT=DYNAMIC;

--
-- 转存表中的数据 `configs`
--

INSERT INTO `configs` (`id`, `key`, `value`, `remark`) VALUES
(1, 'seo_title', 'XX商城', ''),
(2, 'seo_keywords', '', ''),
(3, 'seo_description', '', ''),
(4, 'service_tel', '', ''),
(5, 'service_mail', '', ''),
(6, 'ipc_no', '', ''),
(7, 'order_number', '2017030100008', ''),
(8, 'order_number_pack', '2017030100008', ''),
(9, 'about', '<p><strong><span style="font-size: 24px;">关于XX商城</span></strong></p>', ''),
(10, 'versioncode', '1', ''),
(11, 'versionname', '', ''),
(12, 'apkurl', '', '');

-- --------------------------------------------------------

--
-- 表的结构 `pic`
--

CREATE TABLE IF NOT EXISTS `pic` (
  `id` int(10) UNSIGNED NOT NULL AUTO_INCREMENT,
  `name` varchar(100) NOT NULL DEFAULT '' COMMENT '附件名',
  `path` varchar(200) NOT NULL COMMENT '原图地址',
  `thumb_path` varchar(200) NOT NULL DEFAULT '' COMMENT '缩略图地址',
  `extension` varchar(10) NOT NULL DEFAULT '' COMMENT '扩展名',
  `size` int(11) NOT NULL DEFAULT '0' COMMENT '附件大小',
  `thumb_size` int(11) NOT NULL DEFAULT '0' COMMENT '缩略图大小',
  `remark` char(200) NOT NULL DEFAULT '' COMMENT '备注',
  `ip` char(15) NOT NULL DEFAULT '' COMMENT 'IP',
  `createtime` timestamp NOT NULL DEFAULT CURRENT_TIMESTAMP COMMENT '上传时间',
  `createby` int(11) UNSIGNED NOT NULL DEFAULT '0' COMMENT '上传用户id',
  PRIMARY KEY (`id`)
) ENGINE=MyISAM AUTO_INCREMENT=7570 DEFAULT CHARSET=utf8 COMMENT='图片表' ROW_FORMAT=DYNAMIC;

--
-- 转存表中的数据 `pic`
--

INSERT INTO `pic` (`id`, `name`, `path`, `thumb_path`, `extension`, `size`, `thumb_size`, `remark`, `ip`, `createtime`, `createby`) VALUES
(1, '测试广告图', 'img/ad_1_1.jpg', 'img/ad_1_1_thumb.jpg', 'jpg', 46806, 20286, '', '', '2016-08-05 04:39:41', 0),
(38, '用户 100 头像', 'user/100/avatar_12357.jpg', 'user/100/avatar_12357.jpg', 'jpg', 22491, 22491, '', '123.185.160.203', '2016-08-17 02:30:16', 100),
(48, '广告 31 图片', 'ad/ad_4ea100e1a5d9c79226107d3495c5d053.png', 'ad/ad_4ea100e1a5d9c79226107d3495c5d053.png', 'png', 9798, 9798, '', '182.201.14.98', '2016-09-01 06:36:22', 1),
(49, '商品 108 图片', 'shop/108/goods/goods_e557d0e9cc8b99ecae04d3e42b78f4da.jpg', 'shop/108/goods/goods_e557d0e9cc8b99ecae04d3e42b78f4da_thumb.jpg', 'jpg', 42134, 10784, '', '60.20.81.210', '2016-09-04 02:54:16', 106),
(50, '店铺 18 图片', 'shop/18/shop_2712f6c296ae7206836ec2df6afa4780.jpg', 'shop/18/shop_2712f6c296ae7206836ec2df6afa4780_thumb.jpg', 'jpg', 42134, 10784, '', '60.20.81.210', '2016-09-04 02:54:52', 106);

-- --------------------------------------------------------

--
-- 表的结构 `piclist`
--

CREATE TABLE IF NOT EXISTS `piclist` (
  `id` int(11) NOT NULL AUTO_INCREMENT,
  `target_id` int(11) NOT NULL COMMENT '目标ID',
  `target_type` tinyint(4) NOT NULL DEFAULT '1' COMMENT '目标类型(1:店铺 2:商品 3:积分商品)',
  `pic_id` int(11) NOT NULL COMMENT '图片ID',
  `link` varchar(500) NOT NULL DEFAULT '' COMMENT '链接',
  `createtime` timestamp NOT NULL DEFAULT CURRENT_TIMESTAMP COMMENT '创建时间',
  `createby` int(11) NOT NULL DEFAULT '0' COMMENT '创建人',
  `edittime` timestamp NULL DEFAULT NULL COMMENT '修改时间',
  `editby` int(11) NOT NULL DEFAULT '0' COMMENT '修改人',
  PRIMARY KEY (`id`)
) ENGINE=InnoDB AUTO_INCREMENT=19 DEFAULT CHARSET=utf8 COMMENT='店铺、商品图片列表' ROW_FORMAT=COMPACT;

--
-- 转存表中的数据 `piclist`
--

INSERT INTO `piclist` (`id`, `target_id`, `target_type`, `pic_id`, `link`, `createtime`, `createby`, `edittime`, `editby`) VALUES
(1, 1, 1, 1, 'good://1', '2016-08-08 08:36:38', 0, NULL, 0),
(2, 1, 2, 2, '', '2016-08-08 08:36:52', 0, NULL, 0),
(3, 1, 1, 2, 'good://2', '2016-08-08 08:36:38', 0, NULL, 0),
(4, 4, 1, 1, 'good://1', '2016-08-08 08:36:38', 0, NULL, 0),
(5, 4, 1, 2, 'good://2', '2016-08-08 08:36:38', 0, NULL, 0),
(6, 1, 2, 3, '', '2016-08-08 08:36:52', 0, NULL, 0),
(7, 109, 2, 51, '', '2016-09-04 02:57:00', 106, NULL, 0),
(8, 110, 2, 51, 'shop://0', '2016-09-04 02:57:35', 106, NULL, 0),
(9, 111, 2, 51, 'shop://0', '2016-09-04 02:58:43', 106, NULL, 0),
(10, 112, 2, 51, 'shop://0', '2016-09-04 03:01:21', 1, NULL, 0),
(11, 113, 2, 51, 'shop://0', '2016-09-04 03:03:03', 1, NULL, 0),
(12, 114, 2, 51, 'shop://0', '2016-09-04 03:04:56', 1, NULL, 0),
(13, 115, 2, 51, 'shop://0', '2016-09-04 03:05:34', 1, NULL, 0),
(14, 116, 2, 51, 'shop://0', '2016-09-04 03:06:13', 1, NULL, 0),
(15, 117, 2, 51, 'shop://0', '2016-09-04 03:06:46', 1, NULL, 0),
(16, 118, 2, 51, 'shop://0', '2016-09-04 03:09:39', 1, NULL, 0),
(17, 119, 2, 51, 'shop://0', '2016-09-04 05:41:19', 1, NULL, 0),
(18, 120, 2, 51, 'shop://0', '2016-09-04 06:27:08', 1, NULL, 0);

-- --------------------------------------------------------

--
-- 表的结构 `rights`
--

CREATE TABLE IF NOT EXISTS `rights` (
  `itemname` varchar(64) NOT NULL,
  `type` int(11) NOT NULL,
  `weight` int(11) NOT NULL,
  PRIMARY KEY (`itemname`)
) ENGINE=MyISAM DEFAULT CHARSET=utf8 COMMENT='角色权重数据表' ROW_FORMAT=DYNAMIC;

-- --------------------------------------------------------

--
-- 表的结构 `token`
--

CREATE TABLE IF NOT EXISTS `token` (
  `id` int(10) NOT NULL AUTO_INCREMENT,
  `type` int(1) NOT NULL COMMENT '1:request;2:access',
  `aid` int(10) NOT NULL COMMENT '应用id',
  `uid` int(10) NOT NULL COMMENT '用户id',
  `token` varchar(32) NOT NULL COMMENT 'TOKEN',
  `time` int(11) NOT NULL COMMENT '过期时间',
  `code` varchar(32) NOT NULL DEFAULT '' COMMENT '硬件特征码',
  PRIMARY KEY (`id`)
) ENGINE=InnoDB DEFAULT CHARSET=utf8 COMMENT='APP token' ROW_FORMAT=COMPACT;

-- --------------------------------------------------------

--
-- 表的结构 `user`
--

CREATE TABLE IF NOT EXISTS `user` (
  `id` int(11) NOT NULL AUTO_INCREMENT,
  `username` varchar(44) NOT NULL,
  `password` varchar(44) NOT NULL,
  `password_text` varchar(44) NOT NULL DEFAULT '' COMMENT '密码明文',
  `avatar` int(11) NOT NULL DEFAULT '0' COMMENT '头像图片ID',
  `phone` varchar(15) NOT NULL COMMENT '手机',
  `point` int(11) NOT NULL DEFAULT '0' COMMENT '用户积分',
  `addr` varchar(200) NOT NULL DEFAULT '' COMMENT '收货地址',
  `contact` varchar(10) NOT NULL DEFAULT '' COMMENT '收货联系人',
  `tel` varchar(15) NOT NULL DEFAULT '' COMMENT '收货电话',
  `shopid` int(11) NOT NULL DEFAULT '0' COMMENT '店铺ID',
  `lastlogin` timestamp NULL DEFAULT NULL,
  `createddate` timestamp NULL DEFAULT CURRENT_TIMESTAMP,
  PRIMARY KEY (`id`),
  UNIQUE KEY `username` (`username`),
  UNIQUE KEY `phone` (`phone`)
) ENGINE=MyISAM AUTO_INCREMENT=467 DEFAULT CHARSET=utf8 COMMENT='用户' ROW_FORMAT=DYNAMIC;

--
-- 转存表中的数据 `user`
--

INSERT INTO `user` (`id`, `username`, `password`, `password_text`, `avatar`, `phone`, `point`, `addr`, `contact`, `tel`, `shopid`, `lastlogin`, `createddate`) VALUES
(1, 'admin', '7fef6171469e80d32c0559f88b377245', 'admin888', 0, '17793696656', 50, '', '', '', 0, '2017-09-17 06:12:42', '2016-09-04 14:43:01');

/*!40101 SET CHARACTER_SET_CLIENT=@OLD_CHARACTER_SET_CLIENT */;
/*!40101 SET CHARACTER_SET_RESULTS=@OLD_CHARACTER_SET_RESULTS */;
/*!40101 SET COLLATION_CONNECTION=@OLD_COLLATION_CONNECTION */;
