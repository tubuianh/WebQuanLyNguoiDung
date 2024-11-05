<!-- Các hàm xử lý liên quan Database -->
<?php 
 //if này sẽ ko cho người dùng truy cập bằng đường link url
    if(!defined('_CODE')){//kiểm tra hằng số _CODE ko tồn tại hay ko
        die('Access denied...');
    }


    //hàm query dùng cho insert, update, ...
    function query($sql,$data=[], $check = false){
        global $conn;
        $ketqua = false;
        try{
            $statement = $conn->prepare($sql);
            if(!empty($data)){
                $ketqua = $statement -> execute($data);
            }
            else{
                $ketqua = $statement->execute();
            }
        }catch (Exception $exp) {
            echo $exp->getMessage().'<br>';
            echo 'File: '.$exp->getFile() . '<br>';
            echo 'Line:' . $exp->getLine();
            die();
        }
        if($check)
            return $statement;
        return $ketqua;
    }

    function insert($table, $data){
        $key  = array_keys($data);
        $truong = implode(',',$key);
        $valuetb = ':'.implode(',:',$key);

        $sql = 'INSERT INTO ' . $table . ' (' . $truong . ') VALUES (' . $valuetb . ')';

        $kq = query($sql,$data);
        return $kq;
    }

    function xoa ($table, $condition=''){
        if(empty($condition)){
            $sql = 'DELETE FROM '.$table;
        }else {
            $sql = 'DELETE FROM '.$table . ' WHERE ' .$condition;
        }
        $kq = query($sql);
        return $kq;
    }

    function update($table, $data,$condition=''){
        $update = '';
        foreach($data as $key => $value){
            $update .= $key .' = :'.$key.',' ; 
        }
        $update = trim($update,',');//$update là 1 chuỗi key = :value, . Do có dấu phẩy ở cuối nên hàm trim() sẽ bỏ nó
        
        if(!empty($condition)){
            $sql = 'UPDATE '.$table . ' SET ' .$update. ' WHERE '.$condition;
        }
        else{
            $sql = 'UPDATE '.$table . ' SET ' .$update;
        }
        $kq = query($sql,$data);
        return $kq;
    }


    //lấy nhiều dòng
    function getRows($sql){
        $kq = query($sql,'',true);
        if(is_object($kq)){
            $dataFetch = $kq -> fetchAll(PDO::FETCH_ASSOC);
        }
        return $dataFetch;
    }

    //lấy 1 dòng
    function oneRow($sql){
        $kq = query($sql,'',true);
        if(is_object($kq)){
            $dataFetch = $kq -> fetch(PDO::FETCH_ASSOC);
            //$dataFetch = $kq -> fetch();
        }
        return $dataFetch;
    }


    //đếm số dòng
    function countRow($sql){
        $kq = query($sql,'',true);
        if(!empty($kq)){
            return $kq -> rowCount();
        }
        
    }


    //khi tạo session và lấy dữ liệu xong thì tự xóa đi
    //hàm tạo session
    function setFlashData($key, $value){
        $key = 'flash_'.$key;
        return setSession($key,$value);
    }

    //hàm lấy dữ liệu session xong tự động xóa session
    function getFlashData($key){
        $key = 'flash_'.$key;
        $data = getSession($key);
        removeSession($key);
        return $data;
    }





 ?>