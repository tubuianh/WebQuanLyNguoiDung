<!-- Quên mật khẩu -->
<?php 
 //if này sẽ ko cho người dùng truy cập bằng đường link url
    if(!defined('_CODE')){//kiểm tra hằng số _CODE ko tồn tại hay ko
        die('Access denied...');
    }

    $data = [
        'pageTitle' => 'Quên Mật Khẩu'
    ];

    layouts('header_login',$data);

    //kiểm tra ng dùng đăng nhập hợp lệ rồi, thì ng dùng vào trang đăng nhập lại thì nó sẽ điều hướng về trang dashboard
    if(isLogin()){
        redirect('?module=home&action=dashboard');
    }

    if(isPost()){
        $filterAll = filter();
        if(!empty($filterAll['email'])){
            $email = $filterAll['email'];
           
            $queryUser = oneRow("SELECT id FROM users WHERE email = '$email'");
            if(!empty($queryUser)){
                $userId = $queryUser['id'];
                //tạo forgot token
                $forgotToken = sha1(uniqid().time());//tạo ra forgot token, chuỗi ngẫu nhiên
                $dataUpdate = [
                    'forgotToken' => $forgotToken
                ];
                $updateStatus = update('users',$dataUpdate,"id=$userId");
                if($updateStatus){
                    //tạo link khôi phục mật khẩu
                    $linkReset = _WEB_HOST .'?module=auth&action=reset&token='.$forgotToken;
                    //gửi mail
                    $subject = 'Yêu cầu khôi phục mật khẩu!';
                    $content = 'Chào bạn!'.'</br>';
                    $content .= '. Chúng tôi nhận được yêu cầu khôi phục mật khẩu từ bạn. Vui lòng click vào link sau để đổi lại mật khẩu!'.'</br>'; 
                    $content .= $linkReset .'</br>';
                    $content .= '. Trân trọng cảm ơn!';
    
                    //tiến hành gửi mail
                    $sendMail = sendMail($email,$subject,$content);

                    if($sendMail){
                        setFlashData('smg','Vui lòng kiểm tra email để xem hướng dẫn đặt lại mật khẩu!');
                        setFlashData('smg_type','success');
                    }
                    else {
                        setFlashData('smg','Lỗi hệ thống, vui lòng thử lại sau(email)!');
                        setFlashData('smg_type','danger');
                    }

                    redirect('?module=auth&action=forgot');

                }
                else {
                    setFlashData('smg','Lỗi hệ thống, vui lòng thử lại sau!');   
                    setFlashData('smg_type','danger');
                }
            }else {
                setFlashData('smg','Địa chỉ email không tồn tại trong hệ thống!');
                setFlashData('smg_type','danger');
            }

        }else {
            setFlashData('smg','Vui lòng nhập địa chỉ email!');
            setFlashData('smg_type','danger');
        }
        //redirect('?module=auth&action=forgot');
    }
    $smg = getFlashData('smg');
    $smg_type = getFlashData('smg_type');

 ?>
<div class="row">
    <div class="col-4" style="margin: 50px auto;">
        <h2 class="text-center">Quên Mật Khẩu</h2>
        <?php 
            if(!empty($smg)){
                getSmg($smg,$smg_type);
            }
        ?>
        <form method="post" action="">
            <div class="form-group mg-form">
                <label for="">Email</label>
                <input name="email" type="email" class="form-control" placeholder="Địa chỉ email">
            </div>
        
            <button type="submit" class="btn btn-primary btn-block mg-form">Gửi</button>
            <hr>
            <p class="text-center"><a href="?module=auth&action=login">Đăng Nhập</a></p>
            <p class="text-center"><a href="?module=auth&action=register">Đăng Ký Tài Khoản</a></p>
        </form>
    </div>
</div>


<?php 
    layouts(layoutName: 'footer_login');
?>