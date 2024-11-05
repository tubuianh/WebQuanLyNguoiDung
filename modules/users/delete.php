<?php 
 //if này sẽ ko cho người dùng truy cập bằng đường link url
    if(!defined('_CODE')){//kiểm tra hằng số _CODE ko tồn tại hay ko
        die('Access denied...');
    }

//kiểm tra id có tồn tại trong db hay ko, tồn tại thì xóa dữ liệu bảng tokenlogin -> xóa dữ liệu bảng users
    $filterAll = filter();
    if(!empty($filterAll['id'])){
        $userId = $filterAll['id'];
        $userDetail = countRow("SELECT * FROM users WhERE id='$userId'");
        if($userDetail>0){
            //có dữ liệu
            $deleteToken = xoa('tokenlogin',"user_id=$userId");
            if($deleteToken){
                $deleteUser = xoa('users',"id=$userId");
                if($deleteUser){
                    setFlashData('smg','Xóa người dùng thành công!');
                    setFlashData('smg_type','success');
                }else{
                    setFlashData('smg','Lỗi hệ thống    !');
                    setFlashData('smg_type','danger');
                }
            }
        }
        else{
            setFlashData('smg','Người dùng không tồn tại!');
            setFlashData('smg_type','danger');
        }
    }
    else{
        setFlashData('smg','Liên kết không tồn tại!');
        setFlashData('smg_type','danger');
    }
    redirect('?module=users&action=list');

 ?>