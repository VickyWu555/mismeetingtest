<!DOCTYPE html>
<html lang="zh-TW">

<head>
    <meta charset="utf-8">
    <meta name="viewport" content="width=device-width, initial-scale=1">
    <title>會議排程</title>
    <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.1/dist/css/bootstrap.min.css" rel="stylesheet" integrity="sha384-4bw+/aepP/YC94hEpVNVgiZdgIC5+VKNBQNGCHeKRQN+PtmoHDEXuppvnDJzQIu9" crossorigin="anonymous">
    <link rel="stylesheet" href="../會議排程/schedule.css">
    <link rel="stylesheet" href="../會議排程/schedule_1.css">
    <script src="https://cdn.jsdelivr.net/npm/bootstrap@5.3.1/dist/js/bootstrap.bundle.min.js" integrity="sha384-HwwvtgBNo3bZJJLYd8oVXjrBZt8cqVSpeBNS5n7C8IVInixGAoxmnlMuBnhbgrkm" crossorigin="anonymous"></script>
    <script src="https://cdn.jsdelivr.net/npm/@popperjs/core@2.11.8/dist/umd/popper.min.js" integrity="sha384-I7E8VVD/ismYTF4hNIPjVp/Zjvgyol6VFvRkX/vR+Vc4jQkC+hVqc2pM8ODewa9r" crossorigin="anonymous"></script>
    <script src="https://cdn.jsdelivr.net/npm/bootstrap@5.3.1/dist/js/bootstrap.min.js" integrity="sha384-Rx+T1VzGupg4BHQYs2gCW9It+akI2MM/mndMCy36UVfodzcJcF0GGLxZIzObiEfa" crossorigin="anonymous"></script>
</head>

<body style="background-color: #f7f1f1;">
    <nav class="navbar fixed-top" style="background-color: #586d9a;">
        <div class="container-fluid">
            <span class="title">
                <img src="../image/mis.png" alt="Logo" width="100" height="55" class="logo" >
                會議排程
                <a href="./notify.php">
                    <img src="../image/通知.png" alt="Notify" width="55" height="45" class="notify" >
                </a>
            </span> 
        </div>
    </nav>
    <div>
        <img src="../image/步驟1.png"  width="250" height="15" style="margin-top: 80px; margin-left: 60px; margin-bottom: 10px;">
    </div>
    <div style="font-weight: bold; font-size: 15px; margin-left: 40px;">
        會議成員&ensp; 會議日期&emsp;建議時間&emsp;&emsp; 確認
    </div>
    <div class="textMember">
        &emsp;&emsp;&emsp;&emsp;會議成員
        <input class="search" type="text" name="search" id="search">
        <p style="font-size: 15px;">常用會議成員(0)</p>
        
        <p style="font-size: 15px;">可加入成員</p>
        
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
        $sql = "SELECT Name,UId FROM user ";
        if($stmt = $db->query($sql))
        {
            while($result=mysqli_fetch_object($stmt))
            {
                echo'<img src="../image/成員.png"  width="50" height="50" style="margin:5px" >'.$result->Name.'<input type="checkbox" class="circle-checkbox" value="'.$result->UId.'" name="'.$result->Name.'"><br>';
            }
        }
        mysqli_close($db);  // 關閉資料庫連接
    ?>
    </div>
    <a href="./schedule_2.php">
        <div>
            <button type="button" class="ButtonStyle2">下一步</button>
        </div>  
    </a>
</body>
</html>