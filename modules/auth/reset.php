<!-- reset password -->
<?php 
 //if này sẽ ko cho người dùng truy cập bằng đường link url
    if(!defined('_CODE')){//kiểm tra hằng số _CODE ko tồn tại hay ko
        die('Access denied...');
    }
    $data = [
        'pageTitle' => 'Đặt lại mật khẩu'
    ];
    layouts('header_login',$data);

    $token = filter()['token'];
    if(!empty($token)){
        $tokenQuery = oneRow("SELECT id, fullname, email FROM users WHERE forgotToken = '$token'");
        if(!empty($tokenQuery)){
            $userId = $tokenQuery['id'];
            if(isPost()){
                $filterAll = filter();
                $errors = [];
                //validdate password 
                if(empty($filterAll['password'])){
                    $erros['password']['requied'] = 'Mật khẩu bắt buộc!';
                }
                else{
                    if(strlen($filterAll['password']) < 8){
                        $erros['password']['min'] = 'Mật khẩu tối thiểu 8 kí tự!';
                    }
                }

                //validdate password_confirm 
                if(empty($filterAll['password_confirm'])){
                    $erros['password_confirm']['requied'] = 'Nhập lại mật khẩu bắt buộc!';
                }
                else{
                    if($filterAll['password'] != $filterAll['password_confirm']){
                        $erros['password_confirm']['match'] = 'Mật khẩu nhập lại không đúng!';
                    }
                }

                if(empty($erros)){
                    //update mật khẩu
                    $passwordHash = password_hash($filterAll['password'],PASSWORD_DEFAULT);
                    $dataUpdate = [
                        'password' => $passwordHash,
                        'forgotToken' => null,
                        'update_at' => date('Y-m-d H:i:s')
                    ];
                    $updateStatus = update('users',$dataUpdate, "id = '$userId'");
                   if($updateStatus){
                        setFlashData('smg','Thay đổi mật khẩu thành công!');
                        setFlashData('smg_type','success');
                   }
                   else {
                        setFlashData('smg','Vui lòng kiểm tra lại dữ liệu!');
                        setFlashData('smg_type','danger');
                        redirect('?module=auth&action=login');
                   }
                }
                else {
             
                    setFlashData('smg','Vui lòng kiểm tra lại dữ liệu!');
                    setFlashData('smg_type','danger');
                    setFlashData('erros',$erros);
                    redirect('?module=auth&action=reset&token='.$token);
                }


            }
            $smg = getFlashData('smg');
            $smg_type = getFlashData('smg_type');
            $erros = getFlashData('erros');//lấy lỗi từ mảng lỗi ra

           ?>
            <div class="row">
                <div class="col-4" style="margin: 50px auto;">
                    <h2 class="text-center">Đặt Lại Mật Khẩu</h2>
                    <?php 
                        if(!empty($smg)){
                            getSmg($smg,$smg_type);
                        }
                    ?>
                    <form method="post" action="">
                        <div class="form-group mg-form">
                            <label for="">Mật Khẩu</label>
                            <input name="password" type="password" class="form-control" placeholder="Mật khẩu">
                            <?php 
                                //echo (!empty($erros['password'])) ? '<span class = "error">'.reset($erros['password']).'</span>':null;
                                echo form_error('password','<span class = "error">','</span>',$erros);
                            ?>
                        </div>
                        <div class="form-group mg-form">
                            <label for="">Nhập Lại Mật Khẩu</label>
                            <input name="password_confirm" type="password" class="form-control" placeholder="Nhập lại mật khẩu">
                            <?php 
                                //echo (!empty($erros['password_confirm'])) ? '<span class = "error">'.reset($erros['password_confirm']).'</span>':null;
                                echo form_error('password_confirm','<span class = "error">','</span>',$erros);
                            ?>
                        </div>
                        <input type="hidden" name="token" value="<?php echo $token; ?>">
                        <button type="submit" class="btn btn-primary btn-block mg-form">Gửi</button>
                        <hr>
                        <p class="text-center"><a href="?module=auth&action=login">Đăng Nhập Tài khoản</a></p>
                    </form>
                </div>
            </div>
           <?php

        }
        else{
            getSmg('Liên kết không tồn tại!','danger');
        }
    }else{
        getSmg('Liên kết không tồn tại!','danger');
    }

 ?>

<?php 
    layouts(layoutName: 'footer_login');
?>
