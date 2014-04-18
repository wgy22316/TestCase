/*
Navicat MySQL Data Transfer

Source Server         : mysql
Source Server Version : 50137
Source Host           : localhost:3306
Source Database       : tc_sys_v2

Target Server Type    : MYSQL
Target Server Version : 50137
File Encoding         : 65001

Date: 2014-04-15 17:21:01
*/

SET FOREIGN_KEY_CHECKS=0;

-- ----------------------------
-- Table structure for tc_case
-- ----------------------------
DROP TABLE IF EXISTS `tc_case`;
CREATE TABLE `tc_case` (
  `case_id` int(11) NOT NULL AUTO_INCREMENT,
  `casename` varchar(20) DEFAULT NULL,
  `game_id` int(11) DEFAULT NULL,
  PRIMARY KEY (`case_id`)
) ENGINE=InnoDB AUTO_INCREMENT=71 DEFAULT CHARSET=utf8;

-- ----------------------------
-- Records of tc_case
-- ----------------------------
INSERT INTO `tc_case` VALUES ('61', 'OP_场景分线', '1');
INSERT INTO `tc_case` VALUES ('64', 'OP_采集任务-szq', '1');
INSERT INTO `tc_case` VALUES ('65', 'OP_场景内传送功能-szq', '1');
INSERT INTO `tc_case` VALUES ('66', 'OP_成就系统-szq', '1');
INSERT INTO `tc_case` VALUES ('67', 'OP_防沉迷系统-szq', '1');

-- ----------------------------
-- Table structure for tc_case_v
-- ----------------------------
DROP TABLE IF EXISTS `tc_case_v`;
CREATE TABLE `tc_case_v` (
  `case_v_id` int(11) NOT NULL AUTO_INCREMENT,
  `case_id` int(11) DEFAULT NULL,
  `version` varchar(20) DEFAULT NULL,
  `author_id` varchar(20) DEFAULT NULL,
  `create_time` datetime DEFAULT NULL,
  PRIMARY KEY (`case_v_id`)
) ENGINE=InnoDB AUTO_INCREMENT=127 DEFAULT CHARSET=utf8;

-- ----------------------------
-- Records of tc_case_v
-- ----------------------------
INSERT INTO `tc_case_v` VALUES ('122', '64', 'V1.1', '0', '2014-04-15 16:47:59');
INSERT INTO `tc_case_v` VALUES ('123', '61', 'V1.2', '0', '2014-04-15 16:48:02');
INSERT INTO `tc_case_v` VALUES ('124', '65', 'V1.1', '0', '2014-04-15 16:48:05');
INSERT INTO `tc_case_v` VALUES ('125', '66', 'V1.1', '0', '2014-04-15 16:48:08');
INSERT INTO `tc_case_v` VALUES ('126', '67', 'V1.1', '0', '2014-04-15 16:48:11');

-- ----------------------------
-- Table structure for tc_game
-- ----------------------------
DROP TABLE IF EXISTS `tc_game`;
CREATE TABLE `tc_game` (
  `game_id` int(11) NOT NULL AUTO_INCREMENT,
  `gamename` varchar(20) DEFAULT NULL,
  PRIMARY KEY (`game_id`)
) ENGINE=InnoDB AUTO_INCREMENT=3 DEFAULT CHARSET=utf8 ROW_FORMAT=COMPACT;

-- ----------------------------
-- Records of tc_game
-- ----------------------------
INSERT INTO `tc_game` VALUES ('1', '海贼');

-- ----------------------------
-- Table structure for tc_game_modules
-- ----------------------------
DROP TABLE IF EXISTS `tc_game_modules`;
CREATE TABLE `tc_game_modules` (
  `modules_id` int(11) NOT NULL AUTO_INCREMENT,
  `modulesname` varchar(20) DEFAULT NULL,
  `game_id` int(11) DEFAULT NULL,
  PRIMARY KEY (`modules_id`)
) ENGINE=InnoDB AUTO_INCREMENT=7 DEFAULT CHARSET=utf8;

-- ----------------------------
-- Records of tc_game_modules
-- ----------------------------

-- ----------------------------
-- Table structure for tc_game_v
-- ----------------------------
DROP TABLE IF EXISTS `tc_game_v`;
CREATE TABLE `tc_game_v` (
  `game_v_id` int(11) NOT NULL AUTO_INCREMENT,
  `game_v` varchar(24) DEFAULT NULL,
  `game_id` int(11) DEFAULT NULL,
  PRIMARY KEY (`game_v_id`)
) ENGINE=InnoDB AUTO_INCREMENT=8 DEFAULT CHARSET=utf8;

-- ----------------------------
-- Records of tc_game_v
-- ----------------------------

-- ----------------------------
-- Table structure for tc_group
-- ----------------------------
DROP TABLE IF EXISTS `tc_group`;
CREATE TABLE `tc_group` (
  `group_id` int(11) NOT NULL COMMENT '组id',
  `menu_id` varchar(256) DEFAULT NULL COMMENT '权限id',
  `group_name` varchar(256) NOT NULL COMMENT '组名',
  `module_id` int(11) DEFAULT NULL,
  PRIMARY KEY (`group_id`)
) ENGINE=InnoDB DEFAULT CHARSET=utf8;

-- ----------------------------
-- Records of tc_group
-- ----------------------------
INSERT INTO `tc_group` VALUES ('1000', '1,2,7,30,31,32,33,34,36,37,38', '共有组', '0');
INSERT INTO `tc_group` VALUES ('1001', '', '海贼测试组', '1');
INSERT INTO `tc_group` VALUES ('1002', '42,43,44,6,45,46', '用例管理', '0');

-- ----------------------------
-- Table structure for tc_log
-- ----------------------------
DROP TABLE IF EXISTS `tc_log`;
CREATE TABLE `tc_log` (
  `log_id` int(11) NOT NULL AUTO_INCREMENT,
  `user_id` int(11) DEFAULT NULL,
  `description` text,
  `time` datetime DEFAULT NULL,
  PRIMARY KEY (`log_id`)
) ENGINE=InnoDB AUTO_INCREMENT=88 DEFAULT CHARSET=utf8;

-- ----------------------------
-- Records of tc_log
-- ----------------------------
INSERT INTO `tc_log` VALUES ('78', '0', '0添加了OP_场景分线用例', '2014-04-15 16:46:51');
INSERT INTO `tc_log` VALUES ('79', '0', '0添加了OP_封魔岭系统-szq用例', '2014-04-15 16:47:09');
INSERT INTO `tc_log` VALUES ('80', '0', '0添加了OP_财富神石-szq用例', '2014-04-15 16:47:12');
INSERT INTO `tc_log` VALUES ('81', '0', '0添加了OP_采集任务-szq用例', '2014-04-15 16:47:14');
INSERT INTO `tc_log` VALUES ('82', '0', '0添加了OP_场景内传送功能-szq用例', '2014-04-15 16:47:19');
INSERT INTO `tc_log` VALUES ('83', '0', '0添加了OP_成就系统-szq用例', '2014-04-15 16:47:22');
INSERT INTO `tc_log` VALUES ('84', '0', '0添加了OP_防沉迷系统-szq用例', '2014-04-15 16:47:25');
INSERT INTO `tc_log` VALUES ('85', '0', '0添加了OP_组队系统-szq用例', '2014-04-15 16:47:41');
INSERT INTO `tc_log` VALUES ('86', '0', '0添加了OP_boss之家-szq用例', '2014-04-15 16:47:46');
INSERT INTO `tc_log` VALUES ('87', '0', '0添加了OP_VIP系统-szq用例', '2014-04-15 16:47:51');

-- ----------------------------
-- Table structure for tc_menu
-- ----------------------------
DROP TABLE IF EXISTS `tc_menu`;
CREATE TABLE `tc_menu` (
  `module_id` int(11) DEFAULT NULL COMMENT '模块的id',
  `menu_id` int(11) NOT NULL AUTO_INCREMENT COMMENT '菜单id',
  `menu_pid` int(11) NOT NULL COMMENT '菜单的父id',
  `menu_name` varchar(256) NOT NULL COMMENT '菜单名称',
  `menu_url` varchar(256) NOT NULL COMMENT '菜单的url',
  `menu_sort_id` int(11) NOT NULL COMMENT '菜单排序的id',
  `menu_view` int(4) NOT NULL COMMENT '菜单是否有视图',
  PRIMARY KEY (`menu_id`)
) ENGINE=InnoDB AUTO_INCREMENT=47 DEFAULT CHARSET=utf8;

-- ----------------------------
-- Records of tc_menu
-- ----------------------------
INSERT INTO `tc_menu` VALUES ('0', '1', '0', '首页', '../../Home/Home/home', '1', '0');
INSERT INTO `tc_menu` VALUES ('0', '2', '0', '用例管理', '#', '1', '1');
INSERT INTO `tc_menu` VALUES ('0', '4', '0', '权限管理', '#', '3', '1');
INSERT INTO `tc_menu` VALUES ('0', '5', '0', '菜单管理', '#', '3', '1');
INSERT INTO `tc_menu` VALUES ('0', '6', '2', '导入', '../../Excel/Excelopera/excelinputview', '7', '1');
INSERT INTO `tc_menu` VALUES ('0', '7', '2', '查看用例', '../../Case/Testcase/showallversionofcase', '6', '1');
INSERT INTO `tc_menu` VALUES ('0', '9', '5', '菜单', '../../Home/Home/allocamenu1', '2', '1');
INSERT INTO `tc_menu` VALUES ('0', '13', '4', '分配组权限', '../../Privilege/Authority/groupauthority', '1', '1');
INSERT INTO `tc_menu` VALUES ('0', '14', '4', '分配特殊权限', '../../Privilege/Authority/specialauthority', '2', '1');
INSERT INTO `tc_menu` VALUES ('0', '15', '4', '权限分组', '../../Privilege/Authority/groupmanage', '1', '1');
INSERT INTO `tc_menu` VALUES ('0', '30', '1', '获取菜单ajax', '../../Home/Home/menu', '0', '0');
INSERT INTO `tc_menu` VALUES ('0', '31', '1', '获取用户名ajax', '../../Home/Home/getuser', '0', '0');
INSERT INTO `tc_menu` VALUES ('0', '32', '1', '跳转修改密码页面', '../../User/User/modpass', '0', '0');
INSERT INTO `tc_menu` VALUES ('0', '33', '1', '修改密码ajax', '../../User/User/modpassword', '0', '0');
INSERT INTO `tc_menu` VALUES ('0', '34', '1', '登出ajax', '../../Home/Home/loginout', '0', '0');
INSERT INTO `tc_menu` VALUES ('0', '35', '1', '更新菜单ajax', '../../Home/Home/updatemenu', '0', '0');
INSERT INTO `tc_menu` VALUES ('0', '36', '1', '用例搜索ajax', '../../Case/Search/search', '0', '0');
INSERT INTO `tc_menu` VALUES ('0', '37', '1', '查看用例详情ajax', '../../Case/Search/detail', '0', '0');
INSERT INTO `tc_menu` VALUES ('0', '38', '1', '越权', '../../Login/Login/ytqe', '0', '0');
INSERT INTO `tc_menu` VALUES ('0', '39', '1', '添加到我的用例ajax', '../../Case/Search/add', '0', '0');
INSERT INTO `tc_menu` VALUES ('0', '40', '0', '日志管理', '#', '1', '1');
INSERT INTO `tc_menu` VALUES ('0', '41', '40', '查看日志', '../../Log/Log/index', '1', '1');
INSERT INTO `tc_menu` VALUES ('0', '42', '1', '删除案例', '../../Case/Testcase/deletecase', '1', '0');
INSERT INTO `tc_menu` VALUES ('0', '43', '1', '增加用例', '../../Table/Table/add', '1', '0');
INSERT INTO `tc_menu` VALUES ('0', '44', '1', '删除用例', '../../Table/Table/delete', '1', '0');
INSERT INTO `tc_menu` VALUES ('0', '45', '1', '修改用例', '../../Table/Table/update', '1', '0');
INSERT INTO `tc_menu` VALUES ('0', '46', '1', '导入', '../../Excel/Excelopera/excelinput', '1', '0');

-- ----------------------------
-- Table structure for tc_module
-- ----------------------------
DROP TABLE IF EXISTS `tc_module`;
CREATE TABLE `tc_module` (
  `module_id` int(11) NOT NULL COMMENT '模块(游戏)的id',
  `module_name` varchar(256) NOT NULL COMMENT '模块名称',
  PRIMARY KEY (`module_id`)
) ENGINE=InnoDB DEFAULT CHARSET=utf8;

-- ----------------------------
-- Records of tc_module
-- ----------------------------
INSERT INTO `tc_module` VALUES ('0', '共有模块');
INSERT INTO `tc_module` VALUES ('1', '海贼');

-- ----------------------------
-- Table structure for tc_task
-- ----------------------------
DROP TABLE IF EXISTS `tc_task`;
CREATE TABLE `tc_task` (
  `task_id` int(11) NOT NULL AUTO_INCREMENT,
  `task_name` varchar(32) NOT NULL,
  `task_desc` varchar(128) DEFAULT NULL,
  `assigner_id` varchar(64) DEFAULT NULL,
  `tester_id` varchar(64) DEFAULT NULL,
  `modules_id` int(11) DEFAULT NULL,
  `game_v_id` int(11) DEFAULT NULL,
  `tasktime` datetime DEFAULT NULL,
  `iscomplet` tinyint(1) DEFAULT NULL,
  PRIMARY KEY (`task_id`)
) ENGINE=InnoDB DEFAULT CHARSET=utf8;

-- ----------------------------
-- Records of tc_task
-- ----------------------------

-- ----------------------------
-- Table structure for tc_testcase
-- ----------------------------
DROP TABLE IF EXISTS `tc_testcase`;
CREATE TABLE `tc_testcase` (
  `testcase_id` int(11) NOT NULL AUTO_INCREMENT,
  `case_v_id` int(11) NOT NULL,
  `tc_id` int(11) NOT NULL COMMENT '测试计划中的具体内容id',
  `title` varchar(64) DEFAULT '' COMMENT '标题',
  `Initial_condition` varchar(128) DEFAULT '' COMMENT '初始条件',
  `procedures` varchar(128) DEFAULT '' COMMENT '过程',
  `expected_result` varchar(128) DEFAULT '' COMMENT '期望结果',
  `graph` char(10) DEFAULT 'NBTB' COMMENT '画面',
  `sound` char(10) DEFAULT 'NBTB' COMMENT '声效',
  `feature` char(10) DEFAULT 'NBTB' COMMENT '功能',
  `Total_duration` time DEFAULT '00:00:00',
  `bug_id` varchar(32) DEFAULT '' COMMENT 'bug id',
  PRIMARY KEY (`testcase_id`)
) ENGINE=MyISAM AUTO_INCREMENT=13170 DEFAULT CHARSET=utf8;

-- ----------------------------
-- Records of tc_testcase
-- ----------------------------
INSERT INTO `tc_testcase` VALUES ('13060', '122', '1', '点击任务npc进行对话时', null, null, '打开任务界面', 'NBTB', 'NBTB', 'NBTB', '00:00:00', '');
INSERT INTO `tc_testcase` VALUES ('13061', '122', '2', '当背包中满足仓库满足上缴物品的数量时，点击‘上缴物资’按钮', null, null, '任务完成，获得奖励', 'NBTB', 'NBTB', 'NBTB', '00:00:00', '');
INSERT INTO `tc_testcase` VALUES ('13062', '122', '3', '当背包中满足仓库不满足上缴物品的数量时，点击‘上缴物资’按钮', null, null, '任务完成，获得奖励', 'NBTB', 'NBTB', 'NBTB', '00:00:00', '');
INSERT INTO `tc_testcase` VALUES ('13063', '122', '4', '当背包中不满足仓库中满足上缴物品的数量时，点击‘上缴物资’按钮', null, null, '提示：物资不足，无法获得奖励', 'NBTB', 'NBTB', 'NBTB', '00:00:00', '');
INSERT INTO `tc_testcase` VALUES ('13064', '122', '5', '当背包中不满足仓库中不满足上缴物品的数量时，点击‘上缴物资’按钮', null, null, '提示：物资不足，无法获得奖励', 'NBTB', 'NBTB', 'NBTB', '00:00:00', '');
INSERT INTO `tc_testcase` VALUES ('13065', '122', '6', '检查任务奖励是否正确', null, null, '100W绑定贝里（2个贝里包（小）道具）', 'NBTB', 'NBTB', 'NBTB', '00:00:00', '');
INSERT INTO `tc_testcase` VALUES ('13066', '122', '7', '检查任务可完成的次数', null, null, '3次', 'NBTB', 'NBTB', 'NBTB', '00:00:00', '');
INSERT INTO `tc_testcase` VALUES ('13067', '122', '8', '当无任务次数时，提交任务', null, null, '提示：今天的物资已经足够，请明天再来', 'NBTB', 'NBTB', 'NBTB', '00:00:00', '');
INSERT INTO `tc_testcase` VALUES ('13068', '122', '9', '一直挂机不下线，第二天查看采集次数是否刷新', null, null, '正常刷新', 'NBTB', 'NBTB', 'NBTB', '00:00:00', '');
INSERT INTO `tc_testcase` VALUES ('13069', '122', '10', '0点前下线，第二天查看采集次数是否刷新', null, null, '正常刷新', 'NBTB', 'NBTB', 'NBTB', '00:00:00', '');
INSERT INTO `tc_testcase` VALUES ('13070', '122', '11', '查看采集npc是否正常1', null, null, '不可移动', 'NBTB', 'NBTB', 'NBTB', '00:00:00', '');
INSERT INTO `tc_testcase` VALUES ('13071', '122', '12', '查看采集npc是否正常2', null, null, '不可攻击', 'NBTB', 'NBTB', 'NBTB', '00:00:00', '');
INSERT INTO `tc_testcase` VALUES ('13072', '122', '13', '查看采集npc是否正常3', null, null, '不可被攻击', 'NBTB', 'NBTB', 'NBTB', '00:00:00', '');
INSERT INTO `tc_testcase` VALUES ('13073', '122', '14', '点击采集npc', null, null, '出现进度条，采集时间为5秒', 'NBTB', 'NBTB', 'NBTB', '00:00:00', '');
INSERT INTO `tc_testcase` VALUES ('13074', '122', '15', '在进度条读取过程中，玩家移动', null, null, '采集过程中断，进度条消失，未采集到物品', 'NBTB', 'NBTB', 'NBTB', '00:00:00', '');
INSERT INTO `tc_testcase` VALUES ('13075', '122', '16', '在进度条读取过程中，玩家被攻击', null, null, '采集过程中断，进度条消失，未采集到物品', 'NBTB', 'NBTB', 'NBTB', '00:00:00', '');
INSERT INTO `tc_testcase` VALUES ('13076', '122', '17', '在进度条读取过程中，玩家掉线', null, null, '采集过程中断，进度条消失，未采集到物品', 'NBTB', 'NBTB', 'NBTB', '00:00:00', '');
INSERT INTO `tc_testcase` VALUES ('13077', '122', '18', '当玩家背包中空位为0时', null, null, '提示：背包空间不足，无法进行采集', 'NBTB', 'NBTB', 'NBTB', '00:00:00', '');
INSERT INTO `tc_testcase` VALUES ('13078', '122', '19', '当玩家背包中空位等于1时', null, null, '正常采集', 'NBTB', 'NBTB', 'NBTB', '00:00:00', '');
INSERT INTO `tc_testcase` VALUES ('13079', '122', '20', '当玩家背包中空位大于1时', null, null, '正常采集', 'NBTB', 'NBTB', 'NBTB', '00:00:00', '');
INSERT INTO `tc_testcase` VALUES ('13080', '122', '21', '采集成功后，查看采集npc', null, null, '采集npc消失', 'NBTB', 'NBTB', 'NBTB', '00:00:00', '');
INSERT INTO `tc_testcase` VALUES ('13081', '122', '22', '5分钟后，查看采集npc是否正常刷新', null, null, '正常刷新', 'NBTB', 'NBTB', 'NBTB', '00:00:00', '');
INSERT INTO `tc_testcase` VALUES ('13082', '122', '23', '再次采集该npc', null, null, '正常采集', 'NBTB', 'NBTB', 'NBTB', '00:00:00', '');
INSERT INTO `tc_testcase` VALUES ('13083', '122', '24', '查看采集npc的类型', null, null, '水稻、小麦、蘑菇', 'NBTB', 'NBTB', 'NBTB', '00:00:00', '');
INSERT INTO `tc_testcase` VALUES ('13084', '122', '25', '背包中查看采集的道具是否可交易', null, null, '可交易', 'NBTB', 'NBTB', 'NBTB', '00:00:00', '');
INSERT INTO `tc_testcase` VALUES ('13085', '123', '1', '同一个分线场景最多容纳800人', null, null, '正常', 'NBTB', 'NBTB', 'NBTB', '00:00:00', '');
INSERT INTO `tc_testcase` VALUES ('13086', '123', '2', '当分线场景中人数满时，切换至该分线', null, null, '不可切换至该分线', 'NBTB', 'NBTB', 'NBTB', '00:00:00', '');
INSERT INTO `tc_testcase` VALUES ('13087', '123', '3', '当分线场景中人数不满时，切换至该分线', null, null, '正常切换分线', 'NBTB', 'NBTB', 'NBTB', '00:00:00', '');
INSERT INTO `tc_testcase` VALUES ('13088', '123', '4', 'A、B在同一分线场景中', null, null, 'AB互相可见', 'NBTB', 'NBTB', 'NBTB', '00:00:00', '');
INSERT INTO `tc_testcase` VALUES ('13089', '123', '5', 'A不变，B切换至另一个分线场景', null, null, 'AB互不可见', 'NBTB', 'NBTB', 'NBTB', '00:00:00', '');
INSERT INTO `tc_testcase` VALUES ('13090', '123', '6', 'A切换至B所在的分线场景中', null, null, 'AB互相可见', 'NBTB', 'NBTB', 'NBTB', '00:00:00', '');
INSERT INTO `tc_testcase` VALUES ('13091', '123', '7', '不同分线场景中的玩家世界说话时', null, null, '互相可见', 'NBTB', 'NBTB', 'NBTB', '00:00:00', '');
INSERT INTO `tc_testcase` VALUES ('13092', '123', '8', '不同分线场景中的玩家组队说话时', null, null, '互相可见', 'NBTB', 'NBTB', 'NBTB', '00:00:00', '');
INSERT INTO `tc_testcase` VALUES ('13093', '123', '9', '不同分线场景中的玩家公会说话时', null, null, '互相可见', 'NBTB', 'NBTB', 'NBTB', '00:00:00', '');
INSERT INTO `tc_testcase` VALUES ('13094', '123', '10', '不同分线场景中的玩家本地说话时', null, null, '互不可见', 'NBTB', 'NBTB', 'NBTB', '00:00:00', '');
INSERT INTO `tc_testcase` VALUES ('13095', '123', '11', '不同分线场景中的玩家互相交易', null, null, '不可交易', 'NBTB', 'NBTB', 'NBTB', '00:00:00', '');
INSERT INTO `tc_testcase` VALUES ('13096', '123', '12', '不同分线场景中的玩家组队', null, null, '可组队', 'NBTB', 'NBTB', 'NBTB', '00:00:00', '');
INSERT INTO `tc_testcase` VALUES ('13097', '123', '13', '不同分线场景中的玩家申请入会', null, null, '可入会', 'NBTB', 'NBTB', 'NBTB', '00:00:00', '');
INSERT INTO `tc_testcase` VALUES ('13098', '123', '14', '不同分线场景中的玩家组队做任务', null, null, '1.任务计数同组队规则\n2.经验分配同组队规则', 'NBTB', 'NBTB', 'NBTB', '00:00:00', '');
INSERT INTO `tc_testcase` VALUES ('13099', '123', '15', '不同分线场景中的玩家互相PK', null, null, '不可PK', 'NBTB', 'NBTB', 'NBTB', '00:00:00', '');
INSERT INTO `tc_testcase` VALUES ('13100', '123', '16', '同场景不同分线的怪', null, null, '互不影响，各自刷新', 'NBTB', 'NBTB', 'NBTB', '00:00:00', '');
INSERT INTO `tc_testcase` VALUES ('13101', '123', '17', '不同的分线场景中游戏各模块是否正常使用', null, null, '正常', 'NBTB', 'NBTB', 'NBTB', '00:00:00', '');
INSERT INTO `tc_testcase` VALUES ('13102', '123', '18', '不同分线场景中的玩家购买交易所', null, null, '可购买', 'NBTB', 'NBTB', 'NBTB', '00:00:00', '');
INSERT INTO `tc_testcase` VALUES ('13103', '123', '19', '不同分线场景中的玩家进行私聊', null, null, '正常私聊', 'NBTB', 'NBTB', 'NBTB', '00:00:00', '');
INSERT INTO `tc_testcase` VALUES ('13104', '123', '20', '道具掉落后，换线再换回来，是否还有拾取权', null, null, '有', 'NBTB', 'NBTB', 'NBTB', '00:00:00', '');
INSERT INTO `tc_testcase` VALUES ('13105', '123', '21', '攻击BOSS时，攻击伤害在换线后再换回来，伤害数值是否清空', null, null, '不会', 'NBTB', 'NBTB', 'NBTB', '00:00:00', '');
INSERT INTO `tc_testcase` VALUES ('13106', '124', '1', '当区域为单层时，玩家进入该区域', null, null, '不能进行传送，正常游戏', 'NBTB', 'NBTB', 'NBTB', '00:00:00', '');
INSERT INTO `tc_testcase` VALUES ('13107', '124', '2', '当区域为多层时，玩家进入后不满足当前层数杀怪数量', null, null, '1.弹出提示：还需杀XX怪XX个\n2.不能进入下一层', 'NBTB', 'NBTB', 'NBTB', '00:00:00', '');
INSERT INTO `tc_testcase` VALUES ('13108', '124', '3', '当区域为多层时，玩家进入后满足当前层数杀怪数量', null, null, '成功进入下一层', 'NBTB', 'NBTB', 'NBTB', '00:00:00', '');
INSERT INTO `tc_testcase` VALUES ('13109', '124', '4', '当区域为多层时，玩家进入后不满足指定数量道具', null, null, '1.弹出提示：需要XX道具XX个\n2.不能进入下一层', 'NBTB', 'NBTB', 'NBTB', '00:00:00', '');
INSERT INTO `tc_testcase` VALUES ('13110', '124', '5', '当区域为多层时，玩家进入后满足指定数量道具', null, null, '1.成功进入下一层\n2.道具数量扣除正确', 'NBTB', 'NBTB', 'NBTB', '00:00:00', '');
INSERT INTO `tc_testcase` VALUES ('13111', '124', '6', '当区域为多层时，玩家满足杀怪数量，满足指定数量道具', null, null, '1.成功进入下一层\n2.道具数量扣除正确', 'NBTB', 'NBTB', 'NBTB', '00:00:00', '');
INSERT INTO `tc_testcase` VALUES ('13112', '124', '7', '当区域为多层时，玩家满足杀怪数量，不满足指定数量道具', null, null, '1.弹出提示：\n2.不能进入下一层', 'NBTB', 'NBTB', 'NBTB', '00:00:00', '');
INSERT INTO `tc_testcase` VALUES ('13113', '124', '8', '当区域为多层时，玩家不满足杀怪数量，满足指定数量道具', null, null, '1.弹出提示：\n2.不能进入下一层', 'NBTB', 'NBTB', 'NBTB', '00:00:00', '');
INSERT INTO `tc_testcase` VALUES ('13114', '124', '9', '当区域为多层时，玩家不满足杀怪数量，不满足指定数量道具', null, null, '1.弹出提示：\n2.不能进入下一层', 'NBTB', 'NBTB', 'NBTB', '00:00:00', '');
INSERT INTO `tc_testcase` VALUES ('13115', '124', '10', '当区域为多层时，传送无条件限制时', null, null, '成功进入下一层', 'NBTB', 'NBTB', 'NBTB', '00:00:00', '');
INSERT INTO `tc_testcase` VALUES ('13116', '124', '11', '传送至下一层地图时，传送无奖励时', null, null, '无法获得奖励', 'NBTB', 'NBTB', 'NBTB', '00:00:00', '');
INSERT INTO `tc_testcase` VALUES ('13117', '124', '12', '传送至下一层地图时，传送有奖励且还有剩余数量时', null, null, '获得奖励', 'NBTB', 'NBTB', 'NBTB', '00:00:00', '');
INSERT INTO `tc_testcase` VALUES ('13118', '124', '13', '传送至下一层地图时，传送有奖励但没有剩余数量时', null, null, '无法获得奖励', 'NBTB', 'NBTB', 'NBTB', '00:00:00', '');
INSERT INTO `tc_testcase` VALUES ('13119', '124', '14', '使用WPE发送领取传送奖励的封包', null, null, '奖励不能重复获得', 'NBTB', 'NBTB', 'NBTB', '00:00:00', '');
INSERT INTO `tc_testcase` VALUES ('13120', '125', '1', '点击‘成就’按钮', null, null, '打开成就系统界面', 'NBTB', 'NBTB', 'NBTB', '00:00:00', '');
INSERT INTO `tc_testcase` VALUES ('13121', '125', '2', '检查成就系统界面UI', null, null, '文字/模块等显示正常', 'NBTB', 'NBTB', 'NBTB', '00:00:00', '');
INSERT INTO `tc_testcase` VALUES ('13122', '125', '3', '检查第1个成就分类', null, null, '初入江湖', 'NBTB', 'NBTB', 'NBTB', '00:00:00', '');
INSERT INTO `tc_testcase` VALUES ('13123', '125', '4', '检查第2个成就分类', null, null, '累积登录', 'NBTB', 'NBTB', 'NBTB', '00:00:00', '');
INSERT INTO `tc_testcase` VALUES ('13124', '125', '5', '检查第3个成就分类', null, null, '等级修炼', 'NBTB', 'NBTB', 'NBTB', '00:00:00', '');
INSERT INTO `tc_testcase` VALUES ('13125', '125', '6', '检查第4个成就分类', null, null, '降妖除魔', 'NBTB', 'NBTB', 'NBTB', '00:00:00', '');
INSERT INTO `tc_testcase` VALUES ('13126', '125', '7', '检查第5个成就分类', null, null, 'BOSS击杀', 'NBTB', 'NBTB', 'NBTB', '00:00:00', '');
INSERT INTO `tc_testcase` VALUES ('13127', '125', '8', '检查第6个成就分类', null, null, '装备强化', 'NBTB', 'NBTB', 'NBTB', '00:00:00', '');
INSERT INTO `tc_testcase` VALUES ('13128', '125', '9', '检查第7个成就分类', null, null, '副本成就', 'NBTB', 'NBTB', 'NBTB', '00:00:00', '');
INSERT INTO `tc_testcase` VALUES ('13129', '125', '10', '检查第8个成就分类', null, null, '战场杀敌', 'NBTB', 'NBTB', 'NBTB', '00:00:00', '');
INSERT INTO `tc_testcase` VALUES ('13130', '125', '11', '当未达到成就条件时', null, null, '成就显示为正常状态', 'NBTB', 'NBTB', 'NBTB', '00:00:00', '');
INSERT INTO `tc_testcase` VALUES ('13131', '125', '12', '当达到成就条件但未领取时1', null, null, '成就显示为‘可领取’状态', 'NBTB', 'NBTB', 'NBTB', '00:00:00', '');
INSERT INTO `tc_testcase` VALUES ('13132', '125', '13', '当达到成就条件但未领取时2', null, null, '成就按钮处显示可领取的成就个数', 'NBTB', 'NBTB', 'NBTB', '00:00:00', '');
INSERT INTO `tc_testcase` VALUES ('13133', '125', '14', '当达到成就条件但未领取时3', null, null, '成就分类处显示可领取的成就个数', 'NBTB', 'NBTB', 'NBTB', '00:00:00', '');
INSERT INTO `tc_testcase` VALUES ('13134', '125', '15', '领取部分成就后，查看显示的可领取成就数', null, null, '显示正确', 'NBTB', 'NBTB', 'NBTB', '00:00:00', '');
INSERT INTO `tc_testcase` VALUES ('13135', '125', '16', '当已完成的成就被领取时', null, null, '成就显示为‘已领取’状态', 'NBTB', 'NBTB', 'NBTB', '00:00:00', '');
INSERT INTO `tc_testcase` VALUES ('13136', '125', '17', '检查各个成就能否正常获得', null, null, '正常获得', 'NBTB', 'NBTB', 'NBTB', '00:00:00', '');
INSERT INTO `tc_testcase` VALUES ('13137', '125', '18', '检查领取成就的奖励', null, null, '奖励获得正确', 'NBTB', 'NBTB', 'NBTB', '00:00:00', '');
INSERT INTO `tc_testcase` VALUES ('13138', '125', '19', '当有1个成就未领取时，点击‘一键领取’', null, null, '成功领取，奖励正确', 'NBTB', 'NBTB', 'NBTB', '00:00:00', '');
INSERT INTO `tc_testcase` VALUES ('13139', '125', '20', '当有2个成就未领取时，点击‘一键领取’', null, null, '成功领取2个成就奖励，奖励正确', 'NBTB', 'NBTB', 'NBTB', '00:00:00', '');
INSERT INTO `tc_testcase` VALUES ('13140', '125', '21', '当没有奖励可领取时，点击‘一键领取’', null, null, '提示‘当前没有奖励可领取’', 'NBTB', 'NBTB', 'NBTB', '00:00:00', '');
INSERT INTO `tc_testcase` VALUES ('13141', '125', '22', '点击‘查看排行榜’按钮', null, null, '打开排行榜界面', 'NBTB', 'NBTB', 'NBTB', '00:00:00', '');
INSERT INTO `tc_testcase` VALUES ('13142', '125', '23', '检查成就总积分显示是否正确', null, null, '显示正确', 'NBTB', 'NBTB', 'NBTB', '00:00:00', '');
INSERT INTO `tc_testcase` VALUES ('13143', '125', '24', '检查成就排名是否正确', null, null, '排名正确', 'NBTB', 'NBTB', 'NBTB', '00:00:00', '');
INSERT INTO `tc_testcase` VALUES ('13144', '125', '25', '点击成就界面右上角的‘X’', null, null, '关闭成就界面', 'NBTB', 'NBTB', 'NBTB', '00:00:00', '');
INSERT INTO `tc_testcase` VALUES ('13145', '125', '26', '刷新游戏，查看成就的领取状态', null, null, '状态不变', 'NBTB', 'NBTB', 'NBTB', '00:00:00', '');
INSERT INTO `tc_testcase` VALUES ('13146', '125', '27', '使用WPE发送成就领取包', null, null, '不可获得奖励', 'NBTB', 'NBTB', 'NBTB', '00:00:00', '');
INSERT INTO `tc_testcase` VALUES ('13147', '126', '1', '检查‘官方首页的底部’', null, null, '显示健康游戏忠告', 'NBTB', 'NBTB', 'NBTB', '00:00:00', '');
INSERT INTO `tc_testcase` VALUES ('13148', '126', '2', '检查‘角色创建界面的底部’', null, null, '显示健康游戏忠告', 'NBTB', 'NBTB', 'NBTB', '00:00:00', '');
INSERT INTO `tc_testcase` VALUES ('13149', '126', '3', '检查‘聊天窗口’', null, null, '显示健康游戏忠告', 'NBTB', 'NBTB', 'NBTB', '00:00:00', '');
INSERT INTO `tc_testcase` VALUES ('13150', '126', '4', '检查健康游戏忠告内容', null, null, '抵制不良游戏 拒绝盗版游戏 注意自我保护 谨防受骗上当 适度游戏益脑 沉迷游戏伤身 合理安排时间 享受健康生活 ——《健康游戏忠告》', 'NBTB', 'NBTB', 'NBTB', '00:00:00', '');
INSERT INTO `tc_testcase` VALUES ('13151', '126', '5', '当成年人账号A累积在线1小时', null, null, '1.弹出提示：您已累计在线1小时，为了您的健康，请注意下线休息。\n2.经验、贝里收益正常', 'NBTB', 'NBTB', 'NBTB', '00:00:00', '');
INSERT INTO `tc_testcase` VALUES ('13152', '126', '6', '当成年人账号A累积在线2小时', null, null, '1.弹出提示：您已累计在线2小时，为了您的健康，请注意下线休息。\n2.经验、贝里收益正常', 'NBTB', 'NBTB', 'NBTB', '00:00:00', '');
INSERT INTO `tc_testcase` VALUES ('13153', '126', '7', '当成年人账号A累积在线3小时', null, null, '1.弹出提示：您已累计在线3小时，为了您的健康，请注意下线休息。\n2.经验、贝里收益正常', 'NBTB', 'NBTB', 'NBTB', '00:00:00', '');
INSERT INTO `tc_testcase` VALUES ('13154', '126', '8', '当成年人账号A累积在线4小时', null, null, '1.弹出提示：您已累计在线4小时，为了您的健康，请注意下线休息。\n2.经验、贝里收益正常', 'NBTB', 'NBTB', 'NBTB', '00:00:00', '');
INSERT INTO `tc_testcase` VALUES ('13155', '126', '9', '当成年人账号A累积在线5小时', null, null, '1.弹出提示：您已累计在5小时，为了您的健康，请注意下线休息。\n2.经验、贝里收益正常', 'NBTB', 'NBTB', 'NBTB', '00:00:00', '');
INSERT INTO `tc_testcase` VALUES ('13156', '126', '10', '当成年人账号A累积在线6小时', null, null, '1.弹出提示：您已累计在线6小时，为了您的健康，请注意下线休息。\n2.经验、贝里收益正常', 'NBTB', 'NBTB', 'NBTB', '00:00:00', '');
INSERT INTO `tc_testcase` VALUES ('13157', '126', '11', '当成年人账号A中途下线B分钟后在上线', null, null, 'B分钟没有计算在累积在线时间内', 'NBTB', 'NBTB', 'NBTB', '00:00:00', '');
INSERT INTO `tc_testcase` VALUES ('13158', '126', '12', '当过了每日0点后', null, null, '累积在线时间重新计算', 'NBTB', 'NBTB', 'NBTB', '00:00:00', '');
INSERT INTO `tc_testcase` VALUES ('13159', '126', '13', '当未成人账号C累积在线1小时', null, null, '1.弹出提示：您已累计在线1小时，为了您的健康，请注意下线休息。\n2.经验、贝里收益正常', 'NBTB', 'NBTB', 'NBTB', '00:00:00', '');
INSERT INTO `tc_testcase` VALUES ('13160', '126', '14', '当未成人账号C累积在线2小时', null, null, '1.弹出提示：您已累计在线1小时，为了您的健康，请注意下线休息。\n2.经验、贝里收益正常', 'NBTB', 'NBTB', 'NBTB', '00:00:00', '');
INSERT INTO `tc_testcase` VALUES ('13161', '126', '15', '当未成人账号C累积在线3小时', null, null, '1.提示：您已经进入疲劳游戏时间，您的游戏收益将降为正常值的50%，为了您的健康，请尽快下线休息，做适当身体活动，合理安排学习生活。\n2.经验、贝里收益减半', 'NBTB', 'NBTB', 'NBTB', '00:00:00', '');
INSERT INTO `tc_testcase` VALUES ('13162', '126', '16', '当未成人账号C累积在线4小时', null, null, '1.提示：您已经进入疲劳游戏时间，您的游戏收益将降为正常值的50%，为了您的健康，请尽快下线休息，做适当身体活动，合理安排学习生活。\n2.经验、贝里收益减半', 'NBTB', 'NBTB', 'NBTB', '00:00:00', '');
INSERT INTO `tc_testcase` VALUES ('13163', '126', '17', '当未成人账号C累积在线5小时', null, null, '1.提示：您已进入不健康游戏时间，为了您的健康，请您立即下线休息。  如不下线，您的身体将受到损害，您的收益已降为零\n2.经验、贝里收益为0', 'NBTB', 'NBTB', 'NBTB', '00:00:00', '');
INSERT INTO `tc_testcase` VALUES ('13164', '126', '18', '点击弹框中的‘确认’按钮', null, null, '退出游戏', 'NBTB', 'NBTB', 'NBTB', '00:00:00', '');
INSERT INTO `tc_testcase` VALUES ('13165', '126', '19', '点击弹框中的‘取消’按钮', null, null, '返回游戏界面', 'NBTB', 'NBTB', 'NBTB', '00:00:00', '');
INSERT INTO `tc_testcase` VALUES ('13166', '126', '20', '当未成年人账号累积在线6小时', null, null, '1.提示：你已累计在线6小时，系统会在2秒后自动下线\n2.2秒后自动下线\n3.重新登录后仍提示：你已累计在线6小时，系统会在2秒后自动下线', 'NBTB', 'NBTB', 'NBTB', '00:00:00', '');
INSERT INTO `tc_testcase` VALUES ('13167', '126', '21', '当未成年人账号中途下线D分钟后在上线', null, null, 'D分钟没有被计算在累积在线时间内', 'NBTB', 'NBTB', 'NBTB', '00:00:00', '');
INSERT INTO `tc_testcase` VALUES ('13168', '126', '22', '当过了每日0点后', null, null, '1.累积在线时间重新计算\n2.登录游戏正常，收益正常', 'NBTB', 'NBTB', 'NBTB', '00:00:00', '');
INSERT INTO `tc_testcase` VALUES ('13169', '126', '23', '依次检查游戏的各个模块', null, null, '没有色情、暴力、赌博的内容', 'NBTB', 'NBTB', 'NBTB', '00:00:00', '');

-- ----------------------------
-- Table structure for tc_userinfo
-- ----------------------------
DROP TABLE IF EXISTS `tc_userinfo`;
CREATE TABLE `tc_userinfo` (
  `user_id` varchar(64) NOT NULL COMMENT '用户的id或者工号',
  `username` varchar(64) NOT NULL COMMENT '用户名',
  `password` varchar(64) NOT NULL COMMENT '用户密码',
  `email` varchar(64) NOT NULL COMMENT '用户email',
  PRIMARY KEY (`user_id`)
) ENGINE=InnoDB DEFAULT CHARSET=utf8;

-- ----------------------------
-- Records of tc_userinfo
-- ----------------------------
INSERT INTO `tc_userinfo` VALUES ('0', 'super admin', '63a9f0ea7bb98050796b649e85481845', 'root@mail.51.com');
INSERT INTO `tc_userinfo` VALUES ('1234', 'zhoujixiang', '81dc9bdb52d04dc20036dbd8313ed055', '15527881097@163.com');
INSERT INTO `tc_userinfo` VALUES ('2089', 'songgl', 'bf424cb7b0dea050a42b9739eb261a3a', '15527881098@163.com');
INSERT INTO `tc_userinfo` VALUES ('2090', 'asd', '0b1ec366924b26fc98fa7b71a9c249cf', '156161@163.com');
INSERT INTO `tc_userinfo` VALUES ('2091', 'zhoujx', '0b1ec366924b26fc98fa7b71a9c249cf', '332403170@qq.com');
INSERT INTO `tc_userinfo` VALUES ('2092', 'chenghan', '801272ee79cfde7fa5960571fee36b9b', '155213@163.com');

-- ----------------------------
-- Table structure for tc_user_privilege
-- ----------------------------
DROP TABLE IF EXISTS `tc_user_privilege`;
CREATE TABLE `tc_user_privilege` (
  `user_id` varchar(64) NOT NULL COMMENT '用户的id',
  `module_id` varchar(256) DEFAULT NULL COMMENT '用户的模块id',
  `group_id` varchar(256) DEFAULT NULL COMMENT '用户所在组的id',
  `privilege` varchar(256) DEFAULT NULL COMMENT '用户个人的特别权限，对应menu_id',
  `serialize_menu` varchar(1024) DEFAULT NULL COMMENT '用户的全部菜单，序列化数组',
  PRIMARY KEY (`user_id`)
) ENGINE=InnoDB DEFAULT CHARSET=utf8;

-- ----------------------------
-- Records of tc_user_privilege
-- ----------------------------
INSERT INTO `tc_user_privilege` VALUES ('0', '0', '', null, null);
INSERT INTO `tc_user_privilege` VALUES ('1234', '0,1', '1000', '', null);
INSERT INTO `tc_user_privilege` VALUES ('2089', '0,1', '1000', '', null);
INSERT INTO `tc_user_privilege` VALUES ('2091', '0,1', '1000', '', null);
INSERT INTO `tc_user_privilege` VALUES ('2092', '0,1', '1000', '', null);
