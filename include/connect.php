<!-- Kết nối database -->
 <?php 
 //if này sẽ ko cho người dùng truy cập bằng đường link url ko hợp lệ(truy cập thẳng trực tiếp)
    if(!defined('_CODE')){//kiểm tra hằng số _CODE ko tồn tại hay ko
        die('Access denied...');
    }


    try{
        if(class_exists('PDO')){
            $dsn = 'mysql:dbname=' . _DB . ';host=' . _HOST;
            $options = [
                PDO::MYSQL_ATTR_INIT_COMMAND => 'SET NAMES utf8', 
                PDO::ATTR_ERRMODE => PDO::ERRMODE_EXCEPTION
                ];

                $conn = new PDO ($dsn,_USER, _PASS, $options);
        }
        
    }catch(Exception $ex){
        echo '<div style="color: red; padding: 5px 15px; border: 1px solid red;">';
        echo $exception->getMessage().'<br>';
    }

 ?>