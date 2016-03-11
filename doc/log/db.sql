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
