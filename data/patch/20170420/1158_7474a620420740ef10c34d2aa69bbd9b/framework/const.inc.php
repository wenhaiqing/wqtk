<?php 
/**
 * [WeEngine System] Copyright (c) 2014 WE7.CC
 * WeEngine is NOT a free software, it under the license terms, visited http://www.we7.cc/ for more details.
 */

defined('IN_IA') or exit('Access Denied');

define('REGULAR_EMAIL', '/\w+([-+.]\w+)*@\w+([-.]\w+)*\.\w+([-.]\w+)*/i');
define('REGULAR_MOBILE', '/1\d{10}/');
define('REGULAR_USERNAME', '/^[\x{4e00}-\x{9fa5}a-z\d_\.]{3,15}$/iu');

define('TEMPLATE_DISPLAY', 0);
define('TEMPLATE_FETCH', 1);
define('TEMPLATE_INCLUDEPATH', 2);

define('ACCOUNT_SUBSCRIPTION', 1);
define('ACCOUNT_SUBSCRIPTION_VERIFY', 3);
define('ACCOUNT_SERVICE', 2);
define('ACCOUNT_SERVICE_VERIFY', 4);
define('ACCOUNT_TYPE_OFFCIAL_NORMAL', 1);
define('ACCOUNT_TYPE_OFFCIAL_AUTH', 3);
define('ACCOUNT_TYPE_APP_NORMAL', 4);

define('ACCOUNT_OAUTH_LOGIN', 3);
define('ACCOUNT_NORMAL_LOGIN', 1);

define('WEIXIN_ROOT', 'https://mp.weixin.qq.com');

define('ACCOUNT_OPERATE_ONLINE', 1);
define('ACCOUNT_OPERATE_MANAGER', 2);
define('ACCOUNT_OPERATE_CLERK', 3);

define('SYSTEM_COUPON', 1);
define('WECHAT_COUPON', 2);
define('COUPON_TYPE_DISCOUNT', '1');define('COUPON_TYPE_CASH', '2');define('COUPON_TYPE_GROUPON', '3');define('COUPON_TYPE_GIFT', '4');define('COUPON_TYPE_GENERAL', '5');define('COUPON_TYPE_MEMBER', '6');define('COUPON_TYPE_SCENIC', '7');define('COUPON_TYPE_MOVIE', '8');define('COUPON_TYPE_BOARDINGPASS', '9');define('COUPON_TYPE_MEETING', '10');define('COUPON_TYPE_BUS', '11');
define('ATTACH_FTP', 1);define('ATTACH_OSS', 2);define('ATTACH_QINIU', 3);define('ATTACH_COS', 4);
