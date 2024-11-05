<!-- trang chạy chính -->
<?php 
session_start();//khởi tạo session cho project khi chạy
require_once('config.php');//kết nối với config.php
require_once('./include/connect.php');

//thư viện phpmailer
require_once('./include/phpmailer/Exception.php');
require_once('./include/phpmailer/PHPMailer.php');
require_once('./include/phpmailer/SMTP.php');

require_once('./include/functions.php');
require_once('./include/database.php');
require_once('./include/session.php');

// $session_test = setSession('tu','Giá trị của session test');
// var_dump($session_test);
// removeSession('tu');
//echo getSession('tu');

//setFlashData('haha','Giá trị đc kích hoạt');
//echo getFlashData('haha');

//sendMail('anhtv27112002@gmail.com','Test Mail','Test mail thành công!');



$module = _MODULE;
$action = _ACTION;

// điều hướng sang trang module 
//kiểm tra nếu tồn tại get module, kiểm tra nếu nó là dạng chuỗi, gán $module, trim() để xử lý
    if(!empty($_GET['module'])){     
        if(is_string($_GET['module'])){
            $module  = trim($_GET['module']);
           
        }      
    }

    if(!empty($_GET['action'])){     
        if(is_string($_GET['action'])){
            $action  = trim($_GET['action']);
           
        }      
    }

    // echo '<br>';
    // echo $action;


    $path = 'modules/'.$module. '/'.$action .'.php'; // đường dẫn tới trang muốn điều hướng. module là folder và action là file php
    if(file_exists($path)){//kiểm tra xem file trong đường dẫn tồn tại hay không
        require_once($path);//điều hướng đến trang
    }
    else {
        require_once 'modules/error/404.php';
    }



?>