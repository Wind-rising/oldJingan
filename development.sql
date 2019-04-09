CREATE DATABASE jingan charset = 'utf8';

use jingan;

CREATE TABLE users
(
  id int(11) UNSIGNED auto_increment PRIMARY KEY,
  openid varchar(100),
  checkpoint0 varchar(5) default '0',
  checkpoint1 varchar(5) default '0',
  checkpoint2 varchar(5) default '0',
  checkpoint3 varchar(5) default '0',
  checkpoint4 varchar(5) default '0',
  best_score tinyint(1) default 1 COMMENT '领红包时对应的关卡',
  is_first tinyint(1) default 1 COMMENT '是否第一次进入',
  is_fail tinyint(1) default 0 COMMENT '是否失败',
  is_send_redpack tinyint(1) default 0,
  added_time datetime
);

CREATE TABLE answer
(
  id int(11) UNSIGNED auto_increment PRIMARY KEY,
  user_id int(11),
  checkpoint tinyint(1),
  answer varchar(255),
  score varchar(10),
  added_time datetime
);

CREATE TABLE `redpack_log` (
`id`  int(11) UNSIGNED NOT NULL AUTO_INCREMENT ,
`user_id`  int(11) NULL DEFAULT NULL ,
`amount`  varchar(10) CHARACTER SET utf8 COLLATE utf8_general_ci NULL DEFAULT NULL ,
`openid`  varchar(100) CHARACTER SET utf8 COLLATE utf8_general_ci NULL DEFAULT NULL ,
`checkpoint`  tinyint(1),
`redpack_url`  varchar(255) CHARACTER SET utf8 COLLATE utf8_general_ci NULL DEFAULT NULL ,
`added_time`  datetime NULL DEFAULT NULL ,
PRIMARY KEY (`id`)
)
ENGINE=MyISAM
DEFAULT CHARACTER SET=utf8 COLLATE=utf8_general_ci
AUTO_INCREMENT=1
CHECKSUM=0
ROW_FORMAT=DYNAMIC
DELAY_KEY_WRITE=0
;

CREATE TABLE token_cache
(
  key_name varchar(25),
  key_value VARCHAR(500),
  added_time datetime
);


-- 查询活动总参与人次、过关人次、中奖人次
SELECT count(*) FROM answer;
SELECT count(*) FROM answer WHERE score >= 90;
SELECT count(*) FROM redpack_log;

-- 答对题数的对应人数 
SELECT count(*) from answer WHERE score = 100;
SELECT count(*) from answer WHERE score = 90;
SELECT count(*) from answer WHERE score = 80;