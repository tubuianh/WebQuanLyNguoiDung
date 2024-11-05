<?php 
 //if này sẽ ko cho người dùng truy cập bằng đường link url
    if(!defined('_CODE')){//kiểm tra hằng số _CODE ko tồn tại hay ko
        die('Access denied...');
    }
    $data = [
        'pageTitle' => 'Danh sách người dùng'
    ];
  
    layouts('header',$data);
  
  
    //kiểm tra, nếu ng dùng đăng nhập ko hợp lệ, thì điều hướng về trang login, ng dùng đăng nhập lại
    if(!isLogin()){
     redirect('?module=auth&action=login');
    }

    //truy vấn lấy dữ liệu users
    $listUsers = getRows("SELECT * FROM users ORDER BY creat_at");
    $smg = getFlashData('smg');
    $smg_type = getFlashData('smg_type');
   
 ?>

 <div class="container">
    <hr>
    <h2>Quản Lý Người Dùng</h2>
    <p>
        <a href="?module=users&action=add" class="btn btn-success btn-sm"><i class="fa-solid fa-plus"></i> Thêm người dùng</a>
    </p>
    <?php 
            if(!empty($smg)){
                getSmg($smg,$smg_type);
            }
        ?>
    <table class="table table-bordered">
        <thead>
            <th>STT</th>
            <th>Họ Tên</th>
            <th>Email</th>
            <th>Số Điện Thoại</th>
            <th>Trạng Thái</th>
            <th width = "5%">Sửa</th>
            <th width = "5%">Xóa</th>
        </thead>
        <tbody>
            <?php 
                if(!empty($listUsers)){
                    $stt = 0;// số thứ tự
                    foreach($listUsers as $item){
                        $stt++;
            ?>
            <tr>
            <td><?php echo $stt; ?></td> 
            <td><?php echo $item['fullname']; ?></td>
            <td><?php echo $item['email']; ?></td>
            <td><?php echo $item['phone']; ?></td>
            <td><?php echo $item['status']==1 ? '<button class="btn btn-success btn-sm">Đã kích hoạt</button>' : '<button class="btn btn-danger btn-sm">Chưa kích hoạt</button>'; ?></td>
            <td><a href="<?php echo _WEB_HOST;?>?module=users&action=edit&id=<?php echo $item['id']; ?>" class="btn btn-warning btn-sm"><i class="fa-solid fa-pen-to-square"></i></a></td>
            <td><a href="<?php echo _WEB_HOST;?>?module=users&action=delete&id=<?php echo $item['id']; ?>" onclick="return confirm('Bạn có chắc chắn muốn xóa?')" class="btn btn-danger btn-sm"><i class="fa-solid fa-trash"></i></a></td>
            </tr>
            <?php 
                    }
                }
                else{

            ?>
                <tr>
                    <td colspan="7">
                        <div class="alert alert-danger text-center">Không có người dùng nào!</div>
                    </td>
                </tr>
            <?php 
                }
            ?>
        </tbody>
    </table>
 </div>

<?php 
    layouts(layoutName: 'footer');
?>