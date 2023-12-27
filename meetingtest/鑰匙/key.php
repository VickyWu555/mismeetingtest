<!DOCTYPE html>
<html lang="zh-TW">

<head>
    <meta charset="utf-8">
    <meta name="viewport" content="width=device-width, initial-scale=1">
    <title>鑰匙</title>
    <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.1/dist/css/bootstrap.min.css" rel="stylesheet" integrity="sha384-4bw+/aepP/YC94hEpVNVgiZdgIC5+VKNBQNGCHeKRQN+PtmoHDEXuppvnDJzQIu9" crossorigin="anonymous">
    <link rel="stylesheet" href="../鑰匙/key.css">
    <script src="https://cdn.jsdelivr.net/npm/bootstrap@5.3.1/dist/js/bootstrap.bundle.min.js" integrity="sha384-HwwvtgBNo3bZJJLYd8oVXjrBZt8cqVSpeBNS5n7C8IVInixGAoxmnlMuBnhbgrkm" crossorigin="anonymous"></script>
    <script src="https://cdn.jsdelivr.net/npm/@popperjs/core@2.11.8/dist/umd/popper.min.js" integrity="sha384-I7E8VVD/ismYTF4hNIPjVp/Zjvgyol6VFvRkX/vR+Vc4jQkC+hVqc2pM8ODewa9r" crossorigin="anonymous"></script>
    <script src="https://cdn.jsdelivr.net/npm/bootstrap@5.3.1/dist/js/bootstrap.min.js" integrity="sha384-Rx+T1VzGupg4BHQYs2gCW9It+akI2MM/mndMCy36UVfodzcJcF0GGLxZIzObiEfa" crossorigin="anonymous"></script>
</head>

<body style="background-color: #f7f1f1;">
    <nav class="navbar fixed-top" style="background-color: #586d9a;">
        <div class="container-fluid">
            <span class="title">
                <img src="../image/mis.png" alt="Logo" width="100" height="55" class="logo" >
                鑰匙
            </span> 
        </div>
    </nav>
    <div class="text1">
        預約紀錄
    </div>
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
        $sql = "SELECT ODate,DATE_FORMAT(OTimeS,'%H:%i') As OTimeS,DATE_FORMAT(OTimeE,'%H:%i') As OTimeE,Room,user.Name FROM orderr,user WHERE orderr.UId=user.UId AND user.UId=10";
        if($stmt = $db->query($sql))
        {
            while($result=mysqli_fetch_object($stmt))
            {
                echo'<a href="./key_inside.php" style="color: black; text-decoration: none;">
                        <div class="text2">
                            預定時間 '.$result->ODate.' '.$result->OTimeS.'~'.$result->OTimeE.'<br>
                            預定地點 '.$result->Room.'<br>
                            預約人 '.$result->Name.'<br>
                        </div>
                    </a>';
            }
        }
        mysqli_close($db);  // 關閉資料庫連接
    ?>

</body>
</html>