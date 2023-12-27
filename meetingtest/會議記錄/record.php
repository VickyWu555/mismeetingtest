<!DOCTYPE html>
<html lang="zh-TW">

<head>
    <meta charset="utf-8">
    <meta name="viewport" content="width=device-width, initial-scale=1">
    <title>會議記錄</title>
    <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.1/dist/css/bootstrap.min.css" rel="stylesheet" integrity="sha384-4bw+/aepP/YC94hEpVNVgiZdgIC5+VKNBQNGCHeKRQN+PtmoHDEXuppvnDJzQIu9" crossorigin="anonymous">
    <link rel="stylesheet" href="../會議記錄/record.css">
    <script src="https://cdn.jsdelivr.net/npm/bootstrap@5.3.1/dist/js/bootstrap.bundle.min.js" integrity="sha384-HwwvtgBNo3bZJJLYd8oVXjrBZt8cqVSpeBNS5n7C8IVInixGAoxmnlMuBnhbgrkm" crossorigin="anonymous"></script>
    <script src="https://cdn.jsdelivr.net/npm/@popperjs/core@2.11.8/dist/umd/popper.min.js" integrity="sha384-I7E8VVD/ismYTF4hNIPjVp/Zjvgyol6VFvRkX/vR+Vc4jQkC+hVqc2pM8ODewa9r" crossorigin="anonymous"></script>
    <script src="https://cdn.jsdelivr.net/npm/bootstrap@5.3.1/dist/js/bootstrap.min.js" integrity="sha384-Rx+T1VzGupg4BHQYs2gCW9It+akI2MM/mndMCy36UVfodzcJcF0GGLxZIzObiEfa" crossorigin="anonymous"></script>
</head>

<body style="background-color: #f7f1f1;">
    <nav class="navbar fixed-top" style="background-color: #586d9a;">
        <div class="container-fluid">
            <span class="title">
                <img src="../image/mis.png" alt="Logo" width="100" height="55" class="logo" >
                會議記錄
            </span> 
        </div>
    </nav>
    <div class="text1">
        預約紀錄
    </div>

    <!--判斷使用者是否登入-->
    <?php
        session_start();

        $original_url = $_SERVER['REQUEST_URI'];
        $_SESSION['original_url'] = $original_url;
        //session_destroy();

        // 檢查使用者是否登入
        if (!isset($_SESSION['user_onlyID'])) {
            // 如果使用者尚未登入，則跳轉到登入頁面
            $url = "../login/linelogin.php";
            echo "<script type='text/javascript'>";
            echo "window.location.href='$url'";
            echo "</script>"; 
        }
        else{
            unset($_SESSION['original_url']);
        }
        
        // 使用者已登入，紀錄LineID
        $user_Line = $_SESSION['user_onlyID'];
    ?>

    <?php
        // 建立MySQL的資料庫連接 
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

        $sql = "SELECT UId FROM user WHERE Userid = '$user_Line'";
                $stmt = $db->query($sql);

                if ($stmt) {
                    $result = mysqli_fetch_object($stmt);
                    $uid = $result->UId;
                }
        $sql2 = "SELECT UId,OId,doc FROM record WHERE UId = '$uid'";
        $has_data = false;
        if($stmt2 = $db->query($sql2))
        {
            while($result2=mysqli_fetch_object($stmt2))
            {
                $oid = $result2->OId;
                $sql3 = "SELECT ODate,DATE_FORMAT(OTimeS,'%H:%i') As OTimeS,DATE_FORMAT(OTimeE,'%H:%i') As OTimeE,Room,user.Name FROM orderr,user,record WHERE orderr.UId=user.UId AND user.UId='$uid' AND record.OId=orderr.OId AND record.OId='$oid'";
                $doc = $result2->doc;
                if($stmt3 = $db->query($sql3))
                {
                    while($result3=mysqli_fetch_object($stmt3))
                    {
                        echo'<a href="' . $doc . '" target="_blank" style="color: black; text-decoration: none;">
                                <div class="text2">
                                    預定時間 '.$result3->ODate.' '.$result3->OTimeS.'~'.$result3->OTimeE.'<br>
                                    預定地點 '.$result3->Room.'<br>
                                    預約人 '.$result3->Name.'<br>
                                </div>
                            </a>';
                        $has_data = true;
                    }
                }
            }
        }
        if(!$has_data) 
        {
            echo'<div class="text2" style="text-align: center; line-height: 75px;">
                        目前沒有可下載的會議紀錄
                 </div>';
        }
        mysqli_close($db);  // 關閉資料庫連接
    ?>

</body>
</html>