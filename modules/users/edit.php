<!-- Đăng ký tài khoản -->
<?php 
 //if này sẽ ko cho người dùng truy cập bằng đường link url
    if(!defined('_CODE')){//kiểm tra hằng số _CODE ko tồn tại hay ko
        die('Access denied...');
    }
    $data = [
        'pageTitle' => 'Đăng Ký Tài Khoản'
    ];

    // $data = [
    //          'fullname' => 'Hùng',
    //          'email' => 'hung@gmail.com',
    //          'phone' => '0232145874'
    //      ];  
    // insert('users',$data);

    //xoa('users','id = 5');
    //$kq = countRow('SELECT * FROM users');
    // echo '<pre>';
    // print_r($kq);
    // echo '</pre>';
    layouts('header_login',$data);

    $filterAll = filter();
    if(!empty($filterAll['id'])){
        $userId = $filterAll['id'];

        //kiểm tra id có tồn tại trong db hay ko, tồn tại thì lấy thông tin, ko tồn tại quay lại trang list
        $userDetail = oneRow("SELECT * FROM users WhERE id='$userId'");
        if(!empty($userDetail)){
            setFlashData('userDetail',$userDetail);
        }else {
            redirect('?module=users&action=list');
        }
    }

    if(isPost()){
        $filterAll = filter();
        $erros = [];//mảng chứa lỗi

        //validdate fullname
        if(empty($filterAll['fullname'])){
            $erros['fullname']['requied'] = 'Họ tên bắt buộc!';
        }
        else{
            if(strlen($filterAll['fullname']) < 5){
                $erros['fullname']['min'] = 'Họ tên tối thiểu 5 kí tự!';
            }
        }

        //validdate email
        if(empty($filterAll['email'])){
            $erros['email']['requied'] = 'Email bắt buộc!';
        }
        else{
            $email = $filterAll['email'];
            $sql = "SELECT id FROM users WHERE email = '$email' AND id <> $userId";
            if(countRow($sql) > 0){
                $erros['email']['unique'] = 'Email đã tồn tại!';
            }
        }

        //validdate số điện thoại
        if(empty($filterAll['phone'])){
            $erros['phone']['requied'] = 'Số điện thoại bắt buộc!';
        }
        else{
            if(!isPhone($filterAll['phone'])){
                $erros['phone']['isPhone'] = 'Số điện thoại không hợp lệ!';
            }
        }

        //validdate password 
        if(!empty($filterAll['password'])){
            if(strlen($filterAll['password']) < 8){
                $erros['password']['min'] = 'Mật khẩu tối thiểu 8 kí tự!';
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
        }

        

        if(empty($erros)){
            $dataUpdate = [
                'fullname' => $filterAll['fullname'],
                'email' => $filterAll['email'],
                'phone' => $filterAll['phone'],
                'status' => $filterAll['status'],
                'creat_at' => date('Y-m-d H:i:s')

            ];

            if(!empty($filterAll['password'])){
                $dataUpdate['password'] = password_hash($filterAll['password'],PASSWORD_DEFAULT);
            }

            $condition = "id = $userId";
            $UpdateStatus = update('users', $dataUpdate,$condition);

            if($UpdateStatus){
                setFlashData('smg','Update người dùng thành công!');
                setFlashData('smg_type','success');
            }
            else{
                setFlashData('smg','Hệ thống đang lỗi, vui lòng thử lại sau!');
                setFlashData('smg_type','danger');
            }
            
            
        }
        else {
     
            setFlashData('smg','Vui lòng kiểm tra lại dữ liệu!');
            setFlashData('smg_type','danger');
            setFlashData('erros',$erros);
            setFlashData('old_data',$filterAll);
        }
        redirect('?module=users&action=edit&id='.$userId);
       
    }
    $smg = getFlashData('smg');
    $smg_type = getFlashData('smg_type');
    $erros = getFlashData('erros');//lấy lỗi từ mảng lỗi ra
    $old_data = getFlashData('old_data');
    $userDetail = getFlashData('userDetail');
    if(!empty($userDetail)){
        $old_data = $userDetail;
    }

 ?>
<div class="container">
    <div class="row" style="margin: 50px auto;">
        <h2 class="text-center">Update Người Dùng</h2>
        <?php 
            if(!empty($smg)){
                getSmg($smg,$smg_type);
            }
        ?>
        <form method="post" action="">
            <div class="row">
                <div class="col">
                        <div class="form-group mg-form">
                        <label for="">Họ Tên</label>
                        <input name="fullname" type="fullname" class="form-control" placeholder="Họ tên" value="<?php
                            //echo (!empty($old_data['fullname'])) ? $old_data['fullname']: null; 
                            echo old('fullname',$old_data);
                        ?>">
                        <!-- hàm reset(mảng) là hàm chỉ lấy phần tử đầu tiên -->
                        <?php 
                            //echo (!empty($erros['fullname'])) ? '<span class = "error">'.reset($erros['fullname']).'</span>':null;
                            echo form_error('fullname','<span class = "error">','</span>',$erros);
                        ?>
                    </div>
                    <div class="form-group mg-form">
                        <label for="">Email</label>
                        <input name="email" type="email" class="form-control" placeholder="Địa chỉ email" value="<?php
                            //echo (!empty($old_data['email'])) ? $old_data['email']: null; 
                            echo old('email',$old_data);
                        ?>">
                        <?php 
                            //echo (!empty($erros['email'])) ? '<span class = "error">'.reset($erros['email']).'</span>':null;
                            echo form_error('email','<span class = "error">','</span>',$erros);
                        ?>
                    </div>
                    <div class="form-group mg-form">
                        <label for="">Số Điện Thoại</label>
                        <input name="phone" type="number" class="form-control" placeholder="Số điện thoại" value="<?php
                            //echo (!empty($old_data['phone'])) ? $old_data['phone']: null; 
                            echo old('phone',$old_data);
                        ?>">
                        <?php 
                            //echo (!empty($erros['phone'])) ? '<span class = "error">'.reset($erros['phone']).'</span>':null;
                            echo form_error('phone','<span class = "error">','</span>',$erros);
                        ?>
                    </div>
                </div>

                <div class="col">
                    <div class="form-group mg-form">
                        <label for="">Mật Khẩu</label>
                        <input name="password" type="password" class="form-control" placeholder="Mật khẩu (Không nhập nếu không thay đổi)">
                        <?php 
                            //echo (!empty($erros['password'])) ? '<span class = "error">'.reset($erros['password']).'</span>':null;
                            echo form_error('password','<span class = "error">','</span>',$erros);
                        ?>
                    </div>
                    <div class="form-group mg-form">
                        <label for="">Nhập Lại Mật Khẩu</label>
                        <input name="password_confirm" type="password" class="form-control" placeholder="Nhập lại mật khẩu (Không nhập nếu không thay đổi)">
                        <?php 
                            //echo (!empty($erros['password_confirm'])) ? '<span class = "error">'.reset($erros['password_confirm']).'</span>':null;
                            echo form_error('password_confirm','<span class = "error">','</span>',$erros);
                        ?>
                    </div>
                    <div class="form-group">
                        <label for="">Trạng Thái</label>
                        <select name="status" id="" class="form-control">
                            <option value="0"<?php echo (old('status',$old_data)==0)?'selected':false; ?>>Chưa kích hoạt</option>
                            <option value="1"<?php echo (old('status',$old_data)==1)?'selected':false; ?>>Đã kích hoạt</option>
                        </select>
                    </div>
                </div>
            </div>

            <input type="hidden" name="id" value="<?php echo $userId;?>">

            <button style="width:20%" type="submit" class="btn btn-primary btn-block mg-form">Update người dùng</button>
            <a href="?module=users&action=list" style="width:15%" class="btn btn-success btn-block mg-form">Quay lại</a>
            <hr>
        </form>
    </div>
</div>


<?php 
    layouts(layoutName: 'footer_login');
?>