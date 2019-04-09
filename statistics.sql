-- 查询活动总参与人次、过关人次、中奖人次
SELECT count(*) FROM answer;
SELECT count(*) FROM answer WHERE score >= 90;
SELECT count(*) FROM redpack_log;

-- 答对题数的对应人数 
SELECT count(*) from answer WHERE score = 100;
SELECT count(*) from answer WHERE score = 90;
SELECT count(*) from answer WHERE score = 80;

-- 十月四号相关数据
SELECT count(*) FROM answer WHERE added_time >= '2018-12-04 00:00:00' AND added_time < '2018-12-05 00:00:00';
SELECT count(*) FROM answer WHERE score = 100 AND added_time >= '2018-12-04 00:00:00' AND added_time < '2018-12-05 00:00:00';
SELECT count(*) FROM answer WHERE score = 90 AND added_time >= '2018-12-04 00:00:00' AND added_time < '2018-12-05 00:00:00';
SELECT count(*) FROM answer WHERE score = 80 AND added_time >= '2018-12-04 00:00:00' AND added_time < '2018-12-05 00:00:00';
SELECT count(*) FROM redpack_log WHERE added_time >= '2018-12-04 00:00:00' AND added_time < '2018-12-05 00:00:00';

-- 十月五号相关数据
SELECT count(*) FROM answer WHERE added_time >= '2018-12-05 00:00:00' AND added_time < '2018-12-06 00:00:00';
SELECT count(*) FROM answer WHERE score = 100 AND added_time >= '2018-12-05 00:00:00' AND added_time < '2018-12-06 00:00:00';
SELECT count(*) FROM answer WHERE score = 90 AND added_time >= '2018-12-05 00:00:00' AND added_time < '2018-12-06 00:00:00';
SELECT count(*) FROM answer WHERE score = 80 AND added_time >= '2018-12-05 00:00:00' AND added_time < '2018-12-06 00:00:00';
SELECT count(*) FROM redpack_log WHERE added_time >= '2018-12-05 00:00:00' AND added_time < '2018-12-06 00:00:00';

-- 十月六号相关数据
SELECT count(*) FROM answer WHERE added_time >= '2018-12-06 00:00:00' AND added_time < '2018-12-07 00:00:00';
SELECT count(*) FROM answer WHERE score = 100 AND added_time >= '2018-12-06 00:00:00' AND added_time < '2018-12-07 00:00:00';
SELECT count(*) FROM answer WHERE score = 90 AND added_time >= '2018-12-06 00:00:00' AND added_time < '2018-12-07 00:00:00';
SELECT count(*) FROM answer WHERE score = 80 AND added_time >= '2018-12-06 00:00:00' AND added_time < '2018-12-07 00:00:00';
SELECT count(*) FROM redpack_log WHERE added_time >= '2018-12-06 00:00:00' AND added_time < '2018-12-07 00:00:00';