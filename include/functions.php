<!-- Các hàm chung của project -->
<?php 
 //if này sẽ ko cho người dùng truy cập bằng đường link url
    if(!defined('_CODE')){//kiểm tra hằng số _CODE ko tồn tại hay ko
        die('Access denied...');
    }

    use PHPMailer\PHPMailer\PHPMailer;
    use PHPMailer\PHPMailer\SMTP;
    use PHPMailer\PHPMailer\Exception;

    function layouts($layoutName='header',$data=[]){
        if(file_exists(_WEB_PATH_TEMPLATES. '/layout/'.$layoutName.'.php'))
            require_once _WEB_PATH_TEMPLATES. '/layout/'.$layoutName.'.php';
    }



    //hàm gửi mail
    function sendMail($to, $subject, $content){

        //Create an instance; passing `true` enables exceptions
        $mail = new PHPMailer(true);

        try {
            //Server settings
            $mail->SMTPDebug = SMTP::DEBUG_OFF;                      //Enable verbose debug output
            $mail->isSMTP();                                            //Send using SMTP
            $mail->Host       = 'smtp.gmail.com';                     //Set the SMTP server to send through
            $mail->SMTPAuth   = true;                                   //Enable SMTP authentication
            $mail->Username   = 'cooperalansheldon@gmail.com';                     //SMTP username
            $mail->Password   = 'amuskepfswdifhxc';                               //SMTP password
            $mail->SMTPSecure = PHPMailer::ENCRYPTION_SMTPS;            //Enable implicit TLS encryption
            $mail->Port       = 465;                                    //TCP port to connect to; use 587 if you have set `SMTPSecure = PHPMailer::ENCRYPTION_STARTTLS`

            //Recipients
            $mail->setFrom('anhtv27112002@gmail.com', 'TV Anh');
            $mail->addAddress($to);     //Add a recipient


            //Content
            $mail ->CharSet = "UTF-8";
            $mail->isHTML(true);                                  //Set email format to HTML
            $mail->Subject = $subject;
            $mail->Body    = $content;

            $mail -> SMTPOptions = array(
                'ssl' => array(
                    'verify_peer' => false,
                    'verify_peer_name' => false,
                    'allow_self_signed' => true
                )
            );

            $sendMail = $mail->send();
            if($sendMail){
                return $sendMail;
            }
            //echo 'Gửi thành công!';
        } catch (Exception $e) {
            echo "Gửi mail thất bại!. Mailer Error: {$mail->ErrorInfo}";
        }
    }

    //hàm kiểm tra phương thức GET
    function isGet(){
        if($_SERVER['REQUEST_METHOD'] == 'GET'){
            return true;
        }
        return false;
    }

      //hàm kiểm tra phương thức POST
      function isPost(){
        if($_SERVER['REQUEST_METHOD'] == 'POST'){
            return true;
        }
        return false;
    }

    function filter(){
        //lấy giá trị trên URL qua key, ví dụ trên url có ?token=kiki, thì muốn lấy thì $token = filter()['token], $token sẽ = kiki
        $filterArr = [];
        if(isGet()){
            //xử lý các dữ liệu trước khi hiển thị
            // Nếu giá trị là một mảng (tức là trường GET có thể chứa nhiều giá trị)
            //  filter_input() sẽ được gọi với FILTER_REQUIRE_ARRAY, 
            //  đảm bảo rằng tất cả các giá trị trong mảng đều được lọc
            // Nếu giá trị không phải là một mảng, nó sẽ được lọc bằng FILTER_SANITIZE_SPECIAL_CHARS, 
            // giúp chuyển đổi các ký tự đặc biệt thành các thực thể HTML an toàn
            if(!empty($_GET)){
                foreach($_GET as $key => $value){
                    $key = strip_tags($key);//lọc khóa: strip_tags() được sử dụng để loại bỏ bất kỳ thẻ HTML nào trong khóa nhằm chống ng dùng nhập cặp thẻ vào url
                    if(is_array($value)){
                        $filterArr[$key] = filter_input(INPUT_GET, $key, FILTER_SANITIZE_SPECIAL_CHARS,FILTER_REQUIRE_ARRAY);
                    }
                    else{
                        $filterArr[$key] = filter_input(INPUT_GET, $key, FILTER_SANITIZE_SPECIAL_CHARS);
                    }
                   
                }
            }
        }

        if(isPost()){
            if(!empty($_POST)){
                foreach($_POST as $key => $value){
                    $key = strip_tags($key);
                    if(is_array($value)){
                        $filterArr[$key] = filter_input(INPUT_POST, $key, FILTER_SANITIZE_SPECIAL_CHARS,FILTER_REQUIRE_ARRAY);
                    }
                    else{
                        $filterArr[$key] = filter_input(INPUT_POST, $key, FILTER_SANITIZE_SPECIAL_CHARS);
                    }        
                }
            }
        }
        return $filterArr;
    }

    //hàm kiểm tra email
    function isEmail($email){
        $checkEmail = filter_var($email,FILTER_VALIDATE_EMAIL);
        return $checkEmail;
    }

     //hàm kiểm tra số nguyên
     function isNumberInt($number){
        $checkNumber = filter_var($number,FILTER_VALIDATE_INT);
        return $checkNumber;
    }

      //hàm kiểm tra số thực
      function isNumberFloat($number){
        $checkNumber = filter_var($number,FILTER_VALIDATE_FLOAT);
        return $checkNumber;
    }

    //kiểm tra số điện thoại
    function isPhone($phone){
        $checkZero = false;

        //điều kiện 1, đầu tiên là số 0
        if($phone[0] == '0'){
            $checkZero = true;
            $phone = substr($phone,1);
        }

        //điều kiện 2, đằng sau có 9 số
        $checkNumber = false;
        if(isNumberInt($phone) && strlen($phone)==9){
            $checkNumber = true;
        }

        if($checkZero && $checkNumber){
            return true;
        }
        return false;
    }

    //thông báo lỗi lớn
    function getSmg($smg, $type ='success'){
        echo '<div class = "alert alert-'.$type.'">';
        echo $smg;
        echo '</div>';
    }

    //hàm chuyển hướng
    function redirect ($path='index.php'){
        header("Location: $path");
        exit;
    }

    //hàm thông báo lỗi từng input nhập sai
    function form_error($fileName,$beforehtml='',$afterhtml='',$erros){
        return (!empty($erros[$fileName])) ? '<span class = "error">'.reset($erros[$fileName]).'</span>':null;
    }
    
    //hàm hiển thị dữ liệu cũ
    function old($fileName, $oldData, $default = null){
        return (!empty($oldData[$fileName])) ? $oldData[$fileName]: $default; 
    }
    
    //hàm kiểm tra trạng thái đăng nhập
    function isLogin(){
        $checkToken = false;
        if(getSession('tokenlogin')){//lấy giá trị token khi người dùng login
            $tokenLogin = getSession('tokenlogin');
            
            //kiểm tra token đó có giống trong database ko, cái tokenlogin này để biết ng dùng đang đăng nhập hay ko
            $queryToken = oneRow("SELECT user_id FROM tokenlogin WHERE token = '$tokenLogin'");

            if(!empty($queryToken)){//nếu tồn tại giá trị, nghĩa là có tokenlogin trong database
                $checkToken = true;
            }
            else{
                removeSession('tokenlogin');
            }
        }
        return $checkToken;
    }

















 ?>