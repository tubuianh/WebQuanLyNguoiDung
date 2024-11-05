<!-- kích hoạt tài khoảns -->
<?php 
 //if này sẽ ko cho người dùng truy cập bằng đường link url
    if(!defined('_CODE')){//kiểm tra hằng số _CODE ko tồn tại hay ko
        die('Access denied...');
    }
    layouts('header_login');

    $token = filter()['token'];
    if(!empty($token)){
        //truy vấn để kiểm tra token trên url có = token trong database
        $tokenQuery = oneRow("SELECT id FROM users WHERE activeToken = '$token'");
        if(!empty($tokenQuery)){
            $userId = $tokenQuery['id'];
            $dataUpdate = [
                'status' => 1,
                'activeToken' => null
            ];

            $updateStatus = update('users',$dataUpdate,"id=$userId");

            if($updateStatus){
                setFlashData('smg','Kích hoạt tài khoản thành công!');
                setFlashData('smg_type','success');            
            }
            else {
                setFlashData('smg','Kích hoạt tài khoản không thành công, vui lòng liên hệ quản trị viên!');
                setFlashData('smg_type','danger');
            }
            redirect('?module=auth&action=login');

        }else {
            getSmg('Liên kết không tồn tại hoặc đã hết hạn!','danger');
        }
    }else {
        getSmg('Liên kết không tồn tại hoặc đã hết hạn!','danger');
    }

 ?>

<?php 
    layouts(layoutName: 'footer_login');
?>