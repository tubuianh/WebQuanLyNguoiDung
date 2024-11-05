<!-- Đăng xuất -->
<?php 
 //if này sẽ ko cho người dùng truy cập bằng đường link url
    if(!defined('_CODE')){//kiểm tra hằng số _CODE ko tồn tại hay ko
        die('Access denied...');
    }
    if(isLogin()){
        $token = getSession('tokenlogin');
        xoa('tokenlogin',"token='$token'");    
        removeSession('tokenlogin');
        redirect('?module=auth&action=login');
    }
 ?>