<!-- Đăng nhập tài khoản -->
<?php 
 //if này sẽ ko cho người dùng truy cập bằng đường link url
    if(!defined('_CODE')){//kiểm tra hằng số _CODE ko tồn tại hay ko
        die('Access denied...');
    }

    $data = [
        'pageTitle' => 'Đăng Nhập Tài Khoản'
    ];

    layouts('header_login',$data);






    //kiểm tra ng dùng đăng nhập hợp lệ rồi, thì ng dùng vào trang đăng nhập lại thì nó sẽ điều hướng về trang dashboard
    if(isLogin()){
        redirect('?module=home&action=dashboard');
    }

    if(isPost()){
        $filterAll = filter();
        //nếu người dùng nhập thông tin hợp lệ
        if(!empty(trim($filterAll['email'])) && !empty(trim($filterAll['password']))){
            //kiểm tra đăng nhập
            $email = $filterAll['email'];
            $password = $filterAll['password'];

            //lấy mật khẩu và id từ bảng users qua điều kiện email ng dùng nhập vào và so sánh mật khẩu
            $userQuery = oneRow("SELECT password, id FROM users WHERE email = '$email'");

           if(!empty($userQuery)){
                $passwordHash = $userQuery['password'];//mật khẩu lấy từ database
                $userID = $userQuery['id'];
                if(password_verify($password,$passwordHash)){//so sánh password ng dùng nhập vào và mật khẩu trong database

                    //kiểm tra tài khoản đã login chưa
                    $userLogin = countRow("SELECT * FROM tokenlogin WHERE user_id = '$userID'");
                    if($userLogin > 0){
                        setFlashData('msg','Tài khoản đang đăng nhập ở một nơi khác!');
                        setFlashData('msg_type','danger');
                        redirect('?module=auth&action=login');
                    }
                    else {
                        //tạo tokenLogin, khi người dùng đăng nhập thành công và chuyển hướng sang trang dashboard, tạo 1 tokenlogin
                        $tokenLogin = sha1(uniqid().time());

                        //insert vào bảng loginToken
                        $dataInsert = [
                            'user_id' => $userID,
                            'token' => $tokenLogin,
                            'creat_at' => date('Y-m-d H:i:s')
                        ];

                        $insertStatus =  insert('tokenlogin',$dataInsert);
                        if($insertStatus){
                            //insert thành công
                            //lưu logintoken vào session, để kiểm tra phiên đăng nhập của ng dùng có đang đăng nhập hay ko
                            setSession('tokenlogin',$tokenLogin);
                            redirect('?module=home&action=dashboard');//sang trang dashboard kiểm tra
                        }
                        else{
                            setFlashData('msg','Không thể đăng nhập, vui lòng thử lại sau!');
                            setFlashData('msg_type','danger');
                        }
                    }
                   
                }
                else{
                    setFlashData('msg','Mật khẩu không chính xác!');
                    setFlashData('msg_type','danger');
                }
           }
           else{
                setFlashData('msg','Email không tồn tại!');
                setFlashData('msg_type','danger');
           }
        }
        else{
            setFlashData('msg','Vui lòng nhập email và mật khẩu!');
            setFlashData('msg_type','danger');                       
        }

        redirect('?module=auth&action=login');
    }
    $msg = getFlashData('msg');
    $msg_type = getFlashData('msg_type');

 ?>
<div class="row">
    <div class="col-4" style="margin: 50px auto;">
        <h2 class="text-center">Đăng Nhập Quản Lý Users</h2>
        <?php 
            if(!empty($msg)){
                getSmg($msg,$msg_type);
            }
        ?>
        <form method="post" action="">
            <div class="form-group mg-form">
                <label for="">Email</label>
                <input name="email" type="email" class="form-control" placeholder="Địa chỉ email">
            </div>
            <div class="form-group mg-form">
                <label for="">Password</label>
                <input name="password" type="password" class="form-control" placeholder="Mật khẩu">
            </div>

            <button type="submit" class="btn btn-primary btn-block mg-form">Đăng Nhập</button>
            <hr>
            <p class="text-center"><a href="?module=auth&action=forgot">Quên Mật Khẩu</a></p>
            <p class="text-center"><a href="?module=auth&action=register">Đăng Ký Tài Khoản</a></p>
        </form>
    </div>
</div>


<?php 
    layouts(layoutName: 'footer_login');
?>