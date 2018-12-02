### 使用laravel实现微信登陆以及进行微信公众平台开发

- 执行composer update
- 修改.env, 配置appid和app_secret
- 创建用户表
```
CREATE TABLE `user` (
  `id` int(11) unsigned NOT NULL AUTO_INCREMENT COMMENT '主键',
  `openid` varchar(64) NOT NULL DEFAULT '' COMMENT 'open_id, 当前公众号唯一',
  `nickname` varchar(255) NOT NULL DEFAULT '' COMMENT '用户昵称',
  `sex` tinyint(1) NOT NULL DEFAULT '1' COMMENT '1: 男性 2: 女性 0: 未知',
  `subscribe` tinyint(1) NOT NULL DEFAULT '1' COMMENT '用户是否订阅该公众号标识，值为0时，代表此用户没有关注该公众号，拉取不到其余信息。',
  `city` varchar(64) NOT NULL DEFAULT '' COMMENT '城市',
  `province` varchar(64) NOT NULL DEFAULT '' COMMENT '省份',
  `country` varchar(64) NOT NULL DEFAULT '' COMMENT '国家',
  `language` varchar(32) NOT NULL DEFAULT 'zh_CN' COMMENT '用户的语言',
  `headimgurl` varchar(512) NOT NULL DEFAULT '' COMMENT '用户头像，最后一个数值代表正方形头像大小（有0、46、64、96、132数值可选，0代表640*640正方形头像），用户没有头像时该项为空。若用户更换头像，原有头像URL将失效。',
  `subscribe_time` int(11) NOT NULL DEFAULT '0' COMMENT '用户关注时间，为时间戳。如果用户曾多次关注，则取最后关注时间',
  `unionid` varchar(64) NOT NULL DEFAULT '' COMMENT '只有在用户将公众号绑定到微信开放平台帐号后，才会出现该字段。',
  `remark` varchar(64) NOT NULL DEFAULT '' COMMENT '公众号运营者对粉丝的备注，公众号运营者可在微信公众平台用户管理界面对粉丝添加备注',
  `groupid` int(11) NOT NULL DEFAULT '0' COMMENT '用户所在的分组ID（兼容旧的用户分组接口）',
  `tagid_list` varchar(512) DEFAULT '' COMMENT '用户被打上的标签ID列表',
  `subscribe_scene` varchar(32) NOT NULL DEFAULT '' COMMENT '返回用户关注的渠道来源，ADD_SCENE_SEARCH 公众号搜索，ADD_SCENE_ACCOUNT_MIGRATION 公众号迁移，ADD_SCENE_PROFILE_CARD 名片分享，ADD_SCENE_QR_CODE 扫描二维码，ADD_SCENEPROFILE LINK 图文页内名称点击，ADD_SCENE_PROFILE_ITEM 图文页右上角菜单，ADD_SCENE_PAID 支付后关注，ADD_SCENE_OTHERS 其他',
  `qr_scene` int(11) NOT NULL DEFAULT '0' COMMENT '二维码扫码场景（开发者自定义）',
  `qr_scene_str` varchar(32) NOT NULL DEFAULT '' COMMENT '二维码扫码场景描述（开发者自定义）',
  `created_at` timestamp NOT NULL DEFAULT CURRENT_TIMESTAMP ON UPDATE CURRENT_TIMESTAMP COMMENT '创建时间',
  `updated_at` timestamp NOT NULL DEFAULT '0000-00-00 00:00:00' COMMENT '更新时间',
  PRIMARY KEY (`id`),
  UNIQUE KEY `openid` (`openid`)
) ENGINE=InnoDB AUTO_INCREMENT=4 DEFAULT CHARSET=utf8
```
