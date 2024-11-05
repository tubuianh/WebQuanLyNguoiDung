<?php 
 //if này sẽ ko cho người dùng truy cập bằng đường link url
    if(!defined('_CODE')){//kiểm tra hằng số _CODE ko tồn tại hay ko
        die('Access denied...');
    }

    $data = [
      'pageTitle' => 'Trang Dashboard'
  ];

  layouts('header',$data);


  //kiểm tra, nếu ng dùng đăng nhập ko hợp lệ, thì điều hướng về trang login, ng dùng đăng nhập lại
  if(!isLogin()){
   redirect('?module=auth&action=login');
  }
 ?>

<?php 
    layouts(layoutName: 'footer');
?>
 