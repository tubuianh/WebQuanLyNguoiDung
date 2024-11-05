<!-- các hằng số -->
<!-- //mặc định module sẽ là trag auth và action login -->
<?php
const _MODULE = 'auth';
const _ACTION = 'login';
const _CODE = true;


//thiết lập host
define('_WEB_HOST','http://'.$_SERVER['HTTP_HOST'].'/TuHocPHP/manager_users');
define('_WEB_HOST_TEMPLATES',_WEB_HOST.'/templates');


//thiết lập path
define('_WEB_PATH',__DIR__);
define('_WEB_PATH_TEMPLATES',_WEB_PATH.'/templates');

//kết nối database
const _HOST = 'localhost';
const _DB = 'qlhocsinh';
const _USER = 'root';
const _PASS = '';

?>