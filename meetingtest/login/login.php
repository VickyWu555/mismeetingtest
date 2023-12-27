
<!DOCTYPE html>
<html lang="zh-TW">

<head>
    <meta charset="utf-8">
    <meta name="viewport" content="width=device-width, initial-scale=1">
    <title>登入</title>
    <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.1/dist/css/bootstrap.min.css" rel="stylesheet" integrity="sha384-4bw+/aepP/YC94hEpVNVgiZdgIC5+VKNBQNGCHeKRQN+PtmoHDEXuppvnDJzQIu9" crossorigin="anonymous">
    <link rel="stylesheet" href="../login/login.css">
    <script src="https://cdn.jsdelivr.net/npm/bootstrap@5.3.1/dist/js/bootstrap.bundle.min.js" integrity="sha384-HwwvtgBNo3bZJJLYd8oVXjrBZt8cqVSpeBNS5n7C8IVInixGAoxmnlMuBnhbgrkm" crossorigin="anonymous"></script>
    <script src="https://cdn.jsdelivr.net/npm/@popperjs/core@2.11.8/dist/umd/popper.min.js" integrity="sha384-I7E8VVD/ismYTF4hNIPjVp/Zjvgyol6VFvRkX/vR+Vc4jQkC+hVqc2pM8ODewa9r" crossorigin="anonymous"></script>
    <script src="https://cdn.jsdelivr.net/npm/bootstrap@5.3.1/dist/js/bootstrap.min.js" integrity="sha384-Rx+T1VzGupg4BHQYs2gCW9It+akI2MM/mndMCy36UVfodzcJcF0GGLxZIzObiEfa" crossorigin="anonymous"></script>
    <script src="js/jquery-1.3.2.js"></script> 
    
</head>

<body style="background-color: #f7f1f1;">
    <nav class="navbar fixed-top" style="background-color: #586d9a;">
        <div class="container-fluid">
            <span class="title">
                <img src="../image/mis.png" alt="Logo" width="100" height="55" class="logo" >
                登入
            </span> 
        </div>
    </nav>
  
    

    <div class="signupFrm">
      <form action="<?php echo $_SERVER['PHP_SELF']; ?>" method="POST" class="form" name="log" id="login" >
        <div class="text1">
            登入
        </div>
        <div class="identity">
         <input type="radio" id="identity1" name="identity" value="教師" required />
         <label for="identity1">教師</label>

         <input type="radio" id="identity2" name="identity" value="學生" />
         <label for="identity2">學生</label>          
        </div>

        <div class="inputContainer">
          <input name="id" type="text" class="input" placeholder="a" autocomplete="off" required/>
          <label for="" class="label">教師編號/學號</label>
        </div>

        <div class="inputContainer">
          <input name="Name"  minlength="2"  type="text" class="input" placeholder="a" autocomplete="off" required/>
          <label for="" class="label">姓名</label>
        </div>

        <div class="inputContainer">
          <input name="Phone"  type="text" class="input" placeholder="a" autocomplete="off" required/>
          <label for="" class="label">手機</label>
        </div>

        <div class="inputContainer">
          <input name="mail"  type="email" class="input" pattern=".+@gmail.com" placeholder="a" autocomplete="off" required/>
          <label for="" class="label">e-mail(請輸入gmail)</label>
        </div>

      
      
        <input type="submit" class="submitBtn" id="submitButton" value="確認">
      </form>
    </div>

    <?php
       session_start();
       //echo $_SESSION['original_url'];
       if(isset($_GET['code']))
       {
        getLine();
        $db = @mysqli_connect( 
            'localhost',  // MySQL主機名稱 
            'root',       // 使用者名稱 
            '',  // 密碼 
            'meeting');  // 預設使用的資料庫名稱 
        if ( !$db ) {
            echo "MySQL資料庫連接錯誤!<br/>";
            exit();
        }
        else {
            //echo "MySQL資料庫test連接成功!<br/>";
        }

        
        $user_onlyID=$_SESSION["user_onlyID"];

        $sql3 = "SELECT UId FROM user WHERE Userid = '$user_onlyID'";
    
        $stmt2 = $db->query($sql3);

        if($stmt2->num_rows > 0)
        {
        
            echo "<script>alert('已登入')</script>";
            header('Location: ' . $_SESSION['original_url']);
        }
        }

        // 建立MySQL的資料庫連接 
        if($_SERVER["REQUEST_METHOD"] == "POST")
        {
            
            $db = @mysqli_connect( 
                    'localhost',  // MySQL主機名稱 
                    'root',       // 使用者名稱 
                    '',  // 密碼 
                    'meeting');  // 預設使用的資料庫名稱 
            if ( !$db ) {
                echo "MySQL資料庫連接錯誤!<br/>";
                exit();
            }
            else {
                //echo "MySQL資料庫test連接成功!<br/>";
            }

            $uid = $_POST['id'];
            $name = $_POST['Name'];
            $identity=$_POST['identity'];
            $phone = $_POST['Phone'];
            $mail = $_POST['mail'];
            $user_onlyID=$_SESSION["user_onlyID"];

            //$sql = "SELECT UId FROM user WHERE UId = '$uid'";
            //$stmt = $db->query($sql);

            $sql4 = "SELECT UId,Name,identity FROM user WHERE UId = '$uid'";
            $stmt3 = $db->query($sql4);

            if($stmt3->num_rows > 0)
            {
                $result = mysqli_fetch_object($stmt3);
                $uid_now = $result->UId;
                $name_now = $result->Name;
                $identity_now = $result->identity;
                if($name_now != $name || $identity_now != $identity)
                {
                    echo "<script>alert('您輸入的資料有誤，請重新輸入')</script>";
                }
                else
                {
                    $sql2 = "UPDATE user SET phone = '$phone', Userid = '$user_onlyID', mail = '$mail' WHERE UId = '$uid'";
                    //$sql2 = "INSERT INTO user (UId,Name,identity,phone,Userid,mail) VALUES ('$uid','$name','$identity','$phone','$user_onlyID','$mail')";
                
                    if($db->query($sql2) === TRUE)
                    {
                    echo "<script>alert('登入成功')</script>";
                    header('Location: ' . $_SESSION['original_url']);
                    }
                    else
                    {
                    echo "<script>alert('登入失敗')</script>";
                    }
                    mysqli_close($db);  // 關閉資料庫連接
                }
            }
            else
            {
                echo "<script>alert('非資管系成員，無法加入此系統')</script>";
            }

            //if($stmt->num_rows > 0)
            //{
                
                //echo "<script>alert('編號已登入')</script>";
            //}
            
        }    
    ?>

    <?php
        function getLine()
        {
            //session_start();
            global $user_onlyID;

            $get_code = $_GET['code'];

            $client_id='2000473574';
            $client_secret='3b676600b845fbc630349d3ec7a299b5';

            $data = array(
            "grant_type"=>"authorization_code",
            "code"=>$get_code,
            "redirect_uri"=>"http://localhost/meeting/login/login.php",
            "client_id"=>$client_id,
            "client_secret"=>$client_secret);

            $data_string=http_build_query($data);

            $ch = curl_init('https://api.line.me/oauth2/v2.1/token');
            curl_setopt($ch, CURLOPT_CUSTOMREQUEST, "POST");
            curl_setopt($ch, CURLOPT_POSTFIELDS,$data_string);
            curl_setopt($ch, CURLOPT_RETURNTRANSFER,true);
            curl_setopt($ch, CURLOPT_SSL_VERIFYPEER, false);
            curl_setopt($ch, CURLOPT_HTTPHEADER, array(
            'Content-Type: application/x-www-form-urlencoded')
            );

            $result = curl_exec($ch);
            //echo $result;
            $return_arr=array();
            $return_arr=json_decode($result, true);


            // 解析 id_token
            $data = array(
            "id_token"=>$return_arr['id_token'],
            "client_id"=>$client_id);


            $data_string=http_build_query($data);

            //echo $data_string;
            $ch = curl_init('https://api.line.me/oauth2/v2.1/verify');
            curl_setopt($ch, CURLOPT_CUSTOMREQUEST, "POST");
            curl_setopt($ch, CURLOPT_POSTFIELDS,$data_string);
            curl_setopt($ch, CURLOPT_RETURNTRANSFER,true);
            curl_setopt($ch, CURLOPT_SSL_VERIFYPEER, false);
            curl_setopt($ch, CURLOPT_HTTPHEADER, array(
                'Content-Type: application/x-www-form-urlencoded')
            );

            $result2 = curl_exec($ch);
            //echo $result2;
            $return_arr2=array();
            $return_arr2=json_decode($result2, true);
            //echo $return_arr2;
            // 取得姓名
            $user_name=$return_arr2['name'];
            //echo $user_name;
            // 取得line編號
            $user_onlyID=$return_arr2['sub'];
            $_SESSION["user_onlyID"]= $user_onlyID;
            //echo $user_onlyID; 
        }
        
    ?>
</body>
</html>