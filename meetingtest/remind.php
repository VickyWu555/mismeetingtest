<?php

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

    // 找出符合條件的會議
    $sql = "SELECT * FROM orderr WHERE DATE(ODate) = DATE(DATE_ADD(NOW(), INTERVAL 1 DAY))";

    $result = $db->query($sql);

    if ($result->num_rows > 0) {
        // 有符合條件的會議，進行提醒
        while ($row = $result->fetch_assoc()) {
            $meetingDate = $row['ODate'];
            $meetingRoom = $row['Room'];
            $startTime = $row['OTimeS'];
            $endTime = $row['OTimeE'];
            $userId = $row['UId'];

            $sql2 = "SELECT Userid FROM user WHERE UId = '$userId'";
            $stmt2 = $db->query($sql2);

            if ($stmt2) {
                $result2 = mysqli_fetch_object($stmt2);
                $Lineid = $result2->Userid;
            }

            // 處理 Line 發送提醒
            $apiResponse = sendLineReminder($Lineid, "會議提醒 \n 日期： $meetingDate \n 時間：$startTime ~ $endTime \n 地點：$meetingRoom");
        }
    } 

    // 關閉資料庫連線
    $db->close();

    // Line 發送提醒的函式
    function sendLineReminder($userId, $message) {
        $channelAccessToken = "Ty2mnEF/hFq5jp+eL6SyK1N/NbV6gnksZV/oknwgf0yxfXJfqf5TmY3tEuXVmjsyXwvk7IXE0pSbhsbqauZZVvJtTRKwG9Ye9a1FF7W81EvSTvRUlEJ/nzTFXgQ6YtOE0LPdn4LNw/RCGaAQF6g/ZQdB04t89/1O/w1cDnyilFU=";
        $apiUrl = "https://api.line.me/v2/bot/message/push";
    
        $headers = [
            "Content-Type: application/json",
            "Authorization: Bearer $channelAccessToken",
        ];
    
        $data = [
            "to" => $userId,
            "messages" => [
                ["type" => "text", "text" => $message],
            ],
        ];
    
        $ch = curl_init($apiUrl);
        curl_setopt($ch, CURLOPT_POST, 1);
        curl_setopt($ch, CURLOPT_POSTFIELDS, json_encode($data));
        curl_setopt($ch, CURLOPT_HTTPHEADER, $headers);
        curl_setopt($ch, CURLOPT_RETURNTRANSFER, true);
        $response = curl_exec($ch);
    
        curl_close($ch);

        // 返回 API 的回應
        return $response;
    
    }

    if ($apiResponse === false) {
        // 請求失敗
        echo "Line API 請求失敗。";
    } else {
        // 處理 API 的回應
        var_dump($apiResponse);
    }
?>
