-- phpMyAdmin SQL Dump
-- version 4.5.2
-- http://www.phpmyadmin.net
--
-- Host: localhost:3306
-- Generation Time: 2016-02-05 20:41:39
-- 服务器版本： 5.5.42
-- PHP Version: 5.5.30

SET SQL_MODE = "NO_AUTO_VALUE_ON_ZERO";
SET time_zone = "+00:00";


/*!40101 SET @OLD_CHARACTER_SET_CLIENT=@@CHARACTER_SET_CLIENT */;
/*!40101 SET @OLD_CHARACTER_SET_RESULTS=@@CHARACTER_SET_RESULTS */;
/*!40101 SET @OLD_COLLATION_CONNECTION=@@COLLATION_CONNECTION */;
/*!40101 SET NAMES utf8mb4 */;

--
-- Database: `yiibasic123456`
--
CREATE DATABASE IF NOT EXISTS `yiibasic123456` DEFAULT CHARACTER SET utf8 COLLATE utf8_general_ci;
USE `yiibasic123456`;

-- --------------------------------------------------------

--
-- 表的结构 `authassignment`
--

DROP TABLE IF EXISTS `authassignment`;
CREATE TABLE `authassignment` (
  `itemname` varchar(64) NOT NULL COMMENT '角色名称',
  `userid` varchar(64) NOT NULL COMMENT '用户ID',
  `bizrule` text COMMENT '业务规则',
  `data` text COMMENT '业务数据',
  `createdby` char(15) DEFAULT NULL COMMENT '创建人',
  `createddate` timestamp NULL DEFAULT CURRENT_TIMESTAMP COMMENT '创建时间',
  `updatedby` char(15) DEFAULT NULL COMMENT '更新人',
  `updateddate` timestamp NULL DEFAULT NULL COMMENT '更新时间'
) ENGINE=MyISAM DEFAULT CHARSET=utf8 COMMENT='用户角色指派数据表';

--
-- 插入之前先把表清空（truncate） `authassignment`
--

TRUNCATE TABLE `authassignment`;
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

DROP TABLE IF EXISTS `authitem`;
CREATE TABLE `authitem` (
  `name` varchar(64) NOT NULL COMMENT '角色名称',
  `type` int(11) NOT NULL COMMENT '类型',
  `description` text COMMENT '描述',
  `bizrule` text COMMENT '业务规则',
  `data` text COMMENT '业务数据',
  `createdby` char(15) DEFAULT NULL COMMENT '创建人',
  `createddate` timestamp NULL DEFAULT CURRENT_TIMESTAMP COMMENT '创建时间',
  `updatedby` char(15) DEFAULT NULL COMMENT '更新人',
  `updateddate` timestamp NULL DEFAULT NULL COMMENT '更新时间'
) ENGINE=MyISAM DEFAULT CHARSET=utf8 COMMENT='用户角色数据表';

--
-- 插入之前先把表清空（truncate） `authitem`
--

TRUNCATE TABLE `authitem`;
--
-- 转存表中的数据 `authitem`
--

INSERT INTO `authitem` (`name`, `type`, `description`, `bizrule`, `data`, `createdby`, `createddate`, `updatedby`, `updateddate`) VALUES
('Admin', 2, '管理员', '', 'N;', NULL, '2015-12-11 16:00:00', NULL, NULL);

-- --------------------------------------------------------

--
-- 表的结构 `authitemchild`
--

DROP TABLE IF EXISTS `authitemchild`;
CREATE TABLE `authitemchild` (
  `parent` varchar(64) NOT NULL,
  `child` varchar(64) NOT NULL
) ENGINE=MyISAM DEFAULT CHARSET=utf8;

--
-- 插入之前先把表清空（truncate） `authitemchild`
--

TRUNCATE TABLE `authitemchild`;
-- --------------------------------------------------------

--
-- 表的结构 `configs`
--

DROP TABLE IF EXISTS `configs`;
CREATE TABLE `configs` (
  `id` int(11) NOT NULL,
  `key` varchar(50) NOT NULL COMMENT '参数项名称',
  `value` text COMMENT '参数项值',
  `remark` varchar(100) NOT NULL DEFAULT '' COMMENT '备注'
) ENGINE=MyISAM DEFAULT CHARSET=utf8 COMMENT='配置项目数据表';

--
-- 插入之前先把表清空（truncate） `configs`
--

TRUNCATE TABLE `configs`;
--
-- 转存表中的数据 `configs`
--

INSERT INTO `configs` (`id`, `key`, `value`, `remark`) VALUES
(1, 'seo_title', '网站名称', ''),
(2, 'seo_keywords', '', ''),
(3, 'seo_description', '', ''),
(4, 'service_tel', '400', ''),
(5, 'service_mail', 'service@domain.com', ''),
(6, 'ipc_no', '', '');

-- --------------------------------------------------------

--
-- 表的结构 `rights`
--

DROP TABLE IF EXISTS `rights`;
CREATE TABLE `rights` (
  `itemname` varchar(64) NOT NULL,
  `type` int(11) NOT NULL,
  `weight` int(11) NOT NULL
) ENGINE=MyISAM DEFAULT CHARSET=utf8 COMMENT='角色权重数据表';

--
-- 插入之前先把表清空（truncate） `rights`
--

TRUNCATE TABLE `rights`;
-- --------------------------------------------------------

--
-- 表的结构 `user`
--

DROP TABLE IF EXISTS `user`;
CREATE TABLE `user` (
  `id` int(11) NOT NULL,
  `username` varchar(44) NOT NULL,
  `password` varchar(44) NOT NULL,
  `lastlogin` timestamp NULL DEFAULT NULL,
  `createddate` timestamp NULL DEFAULT CURRENT_TIMESTAMP
) ENGINE=MyISAM DEFAULT CHARSET=utf8;

--
-- 插入之前先把表清空（truncate） `user`
--

TRUNCATE TABLE `user`;
--
-- 转存表中的数据 `user`
--

INSERT INTO `user` (`id`, `username`, `password`, `lastlogin`, `createddate`) VALUES
(1, 'admin', '7fef6171469e80d32c0559f88b377245', '2016-02-05 12:32:28', '2015-12-23 05:23:02');

--
-- Indexes for dumped tables
--

--
-- Indexes for table `authassignment`
--
ALTER TABLE `authassignment`
  ADD PRIMARY KEY (`itemname`,`userid`);

--
-- Indexes for table `authitem`
--
ALTER TABLE `authitem`
  ADD PRIMARY KEY (`name`);

--
-- Indexes for table `authitemchild`
--
ALTER TABLE `authitemchild`
  ADD PRIMARY KEY (`parent`,`child`),
  ADD KEY `child` (`child`);

--
-- Indexes for table `configs`
--
ALTER TABLE `configs`
  ADD PRIMARY KEY (`id`),
  ADD UNIQUE KEY `key` (`key`),
  ADD KEY `key_2` (`key`);

--
-- Indexes for table `rights`
--
ALTER TABLE `rights`
  ADD PRIMARY KEY (`itemname`);

--
-- Indexes for table `user`
--
ALTER TABLE `user`
  ADD PRIMARY KEY (`id`);

--
-- 在导出的表使用AUTO_INCREMENT
--

--
-- 使用表AUTO_INCREMENT `configs`
--
ALTER TABLE `configs`
  MODIFY `id` int(11) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=7;
--
-- 使用表AUTO_INCREMENT `user`
--
ALTER TABLE `user`
  MODIFY `id` int(11) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=2;
/*!40101 SET CHARACTER_SET_CLIENT=@OLD_CHARACTER_SET_CLIENT */;
/*!40101 SET CHARACTER_SET_RESULTS=@OLD_CHARACTER_SET_RESULTS */;
/*!40101 SET COLLATION_CONNECTION=@OLD_COLLATION_CONNECTION */;
