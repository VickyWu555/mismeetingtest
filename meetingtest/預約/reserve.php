<!DOCTYPE html>
<html lang="zh-TW">

<head>
    <meta charset="utf-8">
    <meta name="viewport" content="width=device-width, initial-scale=1">
    <title>預約</title>
    <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.1/dist/css/bootstrap.min.css" rel="stylesheet" integrity="sha384-4bw+/aepP/YC94hEpVNVgiZdgIC5+VKNBQNGCHeKRQN+PtmoHDEXuppvnDJzQIu9" crossorigin="anonymous">
    <link rel="stylesheet" href="../預約/reserve.css">
    <script src="https://cdn.jsdelivr.net/npm/bootstrap@5.3.1/dist/js/bootstrap.bundle.min.js" integrity="sha384-HwwvtgBNo3bZJJLYd8oVXjrBZt8cqVSpeBNS5n7C8IVInixGAoxmnlMuBnhbgrkm" crossorigin="anonymous"></script>
    <script src="https://cdn.jsdelivr.net/npm/@popperjs/core@2.11.8/dist/umd/popper.min.js" integrity="sha384-I7E8VVD/ismYTF4hNIPjVp/Zjvgyol6VFvRkX/vR+Vc4jQkC+hVqc2pM8ODewa9r" crossorigin="anonymous"></script>
    <script src="https://cdn.jsdelivr.net/npm/bootstrap@5.3.1/dist/js/bootstrap.min.js" integrity="sha384-Rx+T1VzGupg4BHQYs2gCW9It+akI2MM/mndMCy36UVfodzcJcF0GGLxZIzObiEfa" crossorigin="anonymous"></script>

    <link href="https://cdn.jsdelivr.net/npm/air-datepicker@3.3.5/air-datepicker.min.css" rel="stylesheet">
    <script src="https://cdn.jsdelivr.net/npm/air-datepicker@3.3.5/air-datepicker.min.js"></script>

    <link rel="stylesheet" type="text/css" href="../預約/jquery-clockpicker.min.css">
    <link rel="stylesheet" type="text/css" href="../預約/github.min.css">
    <script type="text/javascript" src="../預約/jquery.min.js"></script>
    <script type="text/javascript" src="../預約/jquery-clockpicker.min.js"></script>

    <link rel="stylesheet" href="https://cdn.jsdelivr.net/npm/flatpickr/dist/flatpickr.min.css">
    <script src="https://cdn.jsdelivr.net/npm/flatpickr"></script>
</head>

<body style="background-color: #f7f1f1;">
    <nav class="navbar fixed-top" style="background-color: #586d9a;">
        <div class="container-fluid">
            <span class="title">
                <img src="../image/mis.png" alt="Logo" width="100" height="55" class="logo" >
                預約
            </span> 
        </div>
    </nav>
    <br>
    <form action="<?php echo $_SERVER['PHP_SELF']; ?>" method="POST">
        <label class="text" style="margin-top:60px; margin-bottom:20px">預約日期</label>
        <br>
        <input name="date" class="inputDate" id="inputDate">
        <br>
        <label class="text" style="margin-top:20px; margin-bottom:20px">預約時間</label>
        <br>
        <input name="time1" type="text" class="inputTime timeselector" id="inputTime1" oninput="changeRoom()" style="margin-right: 0px;"> ~ <input name="time2" type="text" class="inputTime timeselector2" id="inputTime2" oninput="changeRoom()" style="margin-left: 0px;">
        <br>
        <label class="text" style="margin-top:20px; margin-bottom:20px">預約會議室</label>
        <br>
        <select name="room" class="dropdown" id="roomOption" >
            <option></option>
        </select>
        <input type="submit" class="ButtonStyle" value="確認預約">
    </form>
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

    <!--預約功能-->
    <?php
        // 建立MySQL的資料庫連接 
        if($_SERVER["REQUEST_METHOD"] == "POST")
        {
            if ($_POST["date"] != "" && $_POST["time1"] != "" && $_POST["time2"] != "" && $_POST["room"] != "")
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

                $sql = "SELECT OId FROM orderr ORDER BY OId DESC LIMIT 1";
                $stmt = $db->query($sql);

                if ($stmt->num_rows > 0) {
                    $result = mysqli_fetch_object($stmt);
                    $oid = $result->OId + 1;  // 新的 OId 值
                } else {
                    // 如果没有資料，就從 1 開始
                    $oid = 1;
                }

                $sql4 = "SELECT UId FROM user WHERE Userid = '$user_Line'";
                $stmt4 = $db->query($sql4);

                if ($stmt4) {
                    $result4 = mysqli_fetch_object($stmt4);
                    $uid = $result4->UId;
                }

                $date = $_POST['date'];
                $time1 = $_POST['time1'];
                $time2 = $_POST['time2'];
                $room = $_POST['room'];
                $today = date('Y/m/d');

                if ($date <= $today and $date != "") 
                {
                    echo "<script>alert('請選擇今天以後的日期。')</script>";
                }
                elseif($time1 >= $time2)
                {
                    echo "<script>alert('預約時間選擇有誤，請重新選擇。')</script>";
                }
                elseif($room == "請選擇")
                {
                    echo "<script>alert('尚未選擇會議室，請重新選擇。')</script>";
                }
                else 
                {
                    $sql3 = "INSERT INTO orderr (OId,Room,ODate,OTimeS,OTimeE,UId) VALUES ('$oid','$room','$date','$time1','$time2','$uid')";
                    if($db->query($sql3) === TRUE)
                    {
                        echo "<script>alert('預約成功')</script>";
                    }
                    else
                    {
                        echo "<script>alert('預約失敗')</script>";
                    }
                }   
                mysqli_close($db);  // 關閉資料庫連接
            }
            elseif($_POST["date"] == "")
            {
                echo "<script>alert('尚未選擇預約日期，請重新選擇。')</script>";
            }
            elseif($_POST["time1"] == "")
            {
                echo "<script>alert('尚未選擇開始時間，請重新選擇。')</script>";
            }
            elseif($_POST["time2"] == "")
            {
                echo "<script>alert('尚未選擇結束時間，請重新選擇。')</script>";
            }
        }
    ?>

    <!--判斷預約已滿的日期-->
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
        $disabledDates = array();
        $sql2 = "SELECT ODate, SUM(TIME_TO_SEC(TIMEDIFF(OTimeE, OTimeS))) AS total_duration FROM orderr GROUP BY ODate";
        $stmt2 = $db->query($sql2);
        if ($stmt2->num_rows > 0) 
        {
            while ($row = $stmt2->fetch_assoc()) 
            {
                $NowDate = $row['ODate'];
                $Totaltime = $row['total_duration'];
                $MaxTime = 10*60*60*3;

                if($Totaltime >= $MaxTime)
                {
                    // 將每行資料加到陣列中
                    $disabledDates[] = $row['ODate'];
                }
            }
        }
    ?>

    <!--判斷目前時間可選擇的會議室-->
    <?php
        if($_SERVER["REQUEST_METHOD"] == "GET")
        {
            if(isset($_GET['Date']) && isset($_GET['Time1']) && isset($_GET['Time2']))
            {
                if ($_GET['Date'] != "" && $_GET['Time1'] != "" && $_GET['Time2'] != "" )
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

                    $selectedDate = $_GET['Date'];
                    $selectedTime1 = $_GET['Time1'];
                    $selectedTime2 = $_GET['Time2'];
                    $RoomOption = ["請選擇","302","824","825"];
            
                    $sql2 = "SELECT DISTINCT Room FROM orderr WHERE ODate = '$selectedDate' AND ((OTimeS <= '$selectedTime1' AND OTimeE >= '$selectedTime1') OR (OTimeS <= '$selectedTime2' AND OTimeE >= '$selectedTime2'))";
                    $stmt2 = $db->query($sql2);
                    if ($stmt2->num_rows > 0) 
                    {
                        while ($row = $stmt2->fetch_assoc()) 
                        {
                            if (($key = array_search($row['Room'], $RoomOption)) !== false) {
                                array_splice($RoomOption,$key,1);
                            }
                        }
                    }
		            for($i=0;$i<count($RoomOption);$i++)
                    {
	                    echo "<script type=\"text/javascript\">";
	                    echo "document.getElementById(\"roomOption\").options[$i]=new Option(\"$RoomOption[$i]\",\"$RoomOption[$i]\")";
	                    echo "</script>";
                    }
                }
            }
        }
    ?>

    <!--將所選擇的日期和時間傳給資料庫來改變會議室的選項-->
    <script>
        function changeRoom() {
            // 獲取日期和時間輸入元素
            var inputDate = document.getElementById("inputDate");
            var inputTime1 = document.getElementById("inputTime1");
            var inputTime2 = document.getElementById("inputTime2");

            // 獲取所選的日期和時間
            var selectedDate = inputDate.value;
            var selectedTime1 = inputTime1.value;
            var selectedTime2 = inputTime2.value;

            // 使用AJAX將日期和時間傳送到後端PHP代碼
            $.ajax({
                type: "GET",
                url: "<?php echo $_SERVER['PHP_SELF']; ?>",
                data: {
                        Date: selectedDate,
                        Time1: selectedTime1,
                        Time2: selectedTime2
                    },
                success: function (response) {
                    console.log(response); 
                    var roomSelect = $("#roomOption");
                    roomSelect.empty(); // 清空會議室下拉式選單的選項

                    if (response !== "") {
                        roomSelect.append(response); // 更新會議室下拉式選單的選項
                    }
                },
                error: function () {
                    alert("發生錯誤，請稍後再試。");
                }
             });
        }
    </script>

    <!--按下確認後清空會議室選項-->
    <script>
        document.getElementById('submit').onclick = function() {
            document.querySelector('#roomOption').innerHTML = '';
        }
    </script>

    <!--防止重複提交-->
    <script>
        if(window.history.replaceState)
        {
            window.history.replaceState(null,null,window.location.href);
        }
    </script>

    <!--日期選擇-->
    <script>
        const tw = {
                days: ['星期日', '星期一', '星期二', '星期三', '星期四', '星期五', '星期六'],
                daysShort: ['日', '一', '二', '三', '四', '五', '六'],
                daysMin: ['日', '一', '二', '三', '四', '五', '六'],
                months: ['一月', '二月', '三月', '四月', '五月', '六月', '七月', '八月', '九月', '十月', '十一月', '十二月'],
                monthsShort: ['一月', '二月', '三月', '四月', '五月', '六月', '七月', '八月', '九月', '十月', '十一月', '十二月'],
                today: 'Today',
                clear: 'Clear',
                dateFormat: 'yyyy/MM/dd',
                timeFormat: 'hh:mm aa',
                firstDay: 0
            }
            
        const disabledDates = ["<?php echo join("\",\"",$disabledDates); ?>"];
            
        new AirDatepicker('#inputDate',{
                locale: tw, // Set language
                navTitles: {
                                days: '<strong>yyyy</strong> <i>MMMM</i>'
                },
                onRenderCell({date,cellType}){
                    if((cellType === 'day' && disabledDates.some(disabledDate => isSameDay(date, disabledDate))) || (cellType === 'day' && date.getDay() === 0 || date.getDay() === 6))
                    {
                        return{
                            disabled: true,
                            classes: 'disabled-class',
                            attrs:{
                                title: 'Cell is disabled'
                            }
                        }
                    }
                },
                onHide: function () {
                    changeRoom();
                }
        })
        function isSameDay(date1,date2){
            pretty_date = date1.getFullYear() + '-' + ((date1.getMonth() + 1).toString().padStart(2,'0')) + '-' + date1.getDate().toString().padStart(2,'0');
            return (
                pretty_date === date2
            );
        }
    </script>

    <!--時間選擇-->
    <script type="text/javascript">
        $(".timeselector").flatpickr({
            enableTime: true,
            noCalendar: true,
            time_24hr: true,
            defaultHour: 8,
            defaultMinute: 10,
            dateFormat: "H:i",
            minTime: "08:00",
            maxTime: "20:00",
            disableMobile: "true",
   
            onChange: function(selectedDates, dateStr, instance) {
                var picker = instance;
                var selectedDate = picker.selectedDates[0];

                // 設定分鐘為 10
                if (selectedDate) {
                    selectedDate.setMinutes(10);
                    picker.setDate(selectedDate);
                }
            }
        });
        $(".timeselector2").flatpickr({
            enableTime: true,
            noCalendar: true,
            time_24hr: true,
            defaultHour: 8,
            minuteIncrement:60,
            dateFormat: "H:i",
            minTime: "08:00",
            maxTime: "20:00",
            disableMobile: "true"
        });
    </script>
</body>
</html>