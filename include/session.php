<!-- Hàm liên quan đến session hay cookie -->
<?php 
 //if này sẽ ko cho người dùng truy cập bằng đường link url
    if(!defined('_CODE')){//kiểm tra hằng số _CODE ko tồn tại hay ko
        die('Access denied...');
    }

    //hàm gán session
    function setSession($key, $value){
        return $_SESSION[$key] = $value;
    }

    //hàm đọc session
    function getSession($key = ''){
        if(empty($key)){
            return $_SESSION;
        }else {
            if(isset($_SESSION[$key])){
                return $_SESSION[$key];
            }
        }
    }

    //hàm xóa session
    function removeSession($key=''){
        if(empty($key)){
            session_destroy();
            return true;
        }else {
            if(isset($_SESSION[$key])){
                unset($_SESSION[$key]);
                return true;
            }
        }
    }


 ?>