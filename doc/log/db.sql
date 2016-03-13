-- linzequan 20160311
-- 添加商家优惠菜单表
create table if not exists `sjyh_cate` (
    `id` int not null auto_increment comment '自增id',
    `name` varchar(128) not null comment '名称',
    `logo` varchar(512) comment '图标',
    `rank` int default 100 comment '排序，值越大越靠前。默认100',
    `create_time` int(11) comment '创建时间',
    `create_user_id` int comment '创建账号id',
    `update_time` int(11) comment '更新时间',
    `update_user_id` int comment '更新账号id',
    primary key (`id`)
) engine = myisam character set utf8 collate utf8_general_ci comment = '商家优惠菜单表';


-- linzequan 20160311
-- 添加商家优惠内容表
create table if not exists `sjyh_content` (
    `id` int not null auto_increment comment '自增id',
    `name` varchar(256) not null comment '名称',
    `content` text comment '优惠内容',
    `address` varchar(1024) comment '地址',
    `phone` varchar(128) comment '电话',
    `period` int(11) comment '优惠日期',
    `cover` varchar(512) comment '封面图片',
    `photoList` text comment '图片列表',
    `rank` int default 100 comment '排序，值越大越靠前。默认100',
    `create_time` int(11) comment '创建时间',
    `create_user_id` int comment '创建账号id',
    `update_time` int(11) comment '更新时间',
    `update_user_id` int comment '更新账号id',
    primary key (`id`)
) engine = myisam character set utf8 collate utf8_general_ci comment = '商家优惠内容表';


-- linzequan 20160311
-- 商家优惠添加地区表
create table if not exists `sjyh_zone` (
    `id` int not null auto_increment comment '自增id',
    `name` varchar(256) comment '地区名称',
    `rank` int default 100 comment '排序，值越大越靠前。默认100',
    primary key (`id`)
) engine = myisam character set utf8 collate utf8_general_ci comment = '商家优惠地区表';


-- linzequan 20160311
-- 商家优惠内容表添加地区字段
alter table `sjyh_content` add `zone_id` int comment '地区id';


-- linzequan 20160311
-- 商家优惠地区表添加创建和更新信息字段
alter table `sjyh_zone` add `create_time` int(11) comment '创建时间', add `create_user_id` int comment '创建账号id', add `update_time` int(11) comment '更新时间', add `update_user_id` int comment '更新账号id';


-- linzequan 20160313
-- 商家优惠内容表修改优惠日期字段类型
alter table `sjyh_content` change column `period` `period` varchar(256) comment '优惠日期';


-- linzequan 20160314
-- 公共模块添加图片管理表
create table if not exists `common_upload` (
    `id` int not null auto_increment comment '自增id',
    `url` varchar(256) comment '图片地址',
    `create_time` int(11) comment '创建时间',
    `create_user_id` int comment '创建账号id',
    `update_time` int(11) comment '更新时间',
    `update_user_id` int comment '更新账号id',
    primary key (`id`)
) engine = myisam character set utf8 collate utf8_general_ci comment = '公共模块添加图片管理表';
