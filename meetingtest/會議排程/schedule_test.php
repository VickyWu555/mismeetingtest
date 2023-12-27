<!DOCTYPE html>
<html lang="zh-TW">

<head>
    <meta charset="utf-8">
    <meta name="viewport" content="width=device-width, initial-scale=1">
    <title>會議排程</title>
    <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.1/dist/css/bootstrap.min.css" rel="stylesheet" integrity="sha384-4bw+/aepP/YC94hEpVNVgiZdgIC5+VKNBQNGCHeKRQN+PtmoHDEXuppvnDJzQIu9" crossorigin="anonymous">
    <link rel="stylesheet" href="schedule_test.css">
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
    <div class="text1">
        選擇其他忙碌時間
    </div>

    <?php
        $timearray=['8:10~9:00','9:10~10:00','10:10~11:00','11:10~12:00','12:10~13:00'];//發起人選擇的時間範圍
        $datearray=['2023/11/12','2023/11/13','2023/11/14','2023/11/15'];//發起人選擇的日期範圍
        $busy_time = [
            ['2023/11/12', '9:10~10:00'],
            ['2023/11/13', '11:10~12:00'],
        ]; //跟google日曆比對出忙碌的時間
        $flag = false;
        echo "<div class='container'><table  style='background-color:white;'  class='table-bordered border-dark'> ";
        for($i=0 ; $i<count($timearray)+1 ; $i++)
        {
            echo "<tr>";
            for($j=0; $j<count($datearray)+1 ; $j++)
            {
                if($i==0 && $j!=0)
                {
                    echo "<td width='100px' style='padding:5px' >" .$datearray[$j-1]. "</td>";
                }
                else if($i!=0 && $j==0)
                {
                    echo "<td style='padding:5px'>"  .$timearray[$i-1]. "</td>";
                }
                else if($i!=0 && $j!=0)
                {
                    for($k=0 ; $k<count($busy_time) ; $k++)
                    {
                        if($datearray[$j-1]==$busy_time[$k][0] && $timearray[$i-1]==$busy_time[$k][1])
                        {
                            $flag = true;
                            break;
                        }
                        else 
                        {
                            $flag = false;
                        }
                    }
                    if($flag)
                    {
                        echo "<td style='color:#fd6a63;padding:5px' bgcolor='#fd6a63'>" .$datearray[$j-1]."<br>" .$timearray[$i-1]. "</td>";
                    }
                    else
                    {
                        echo "<td style='color:white;padding:5px' onclick='cellClick(this)'>" .$datearray[$j-1]."<br>" .$timearray[$i-1]. "</td>";
                    }
                }
                else if($i==0 && $j==0)
                {
                    echo "<td style='padding:5px'>時間/日期</td>";
                }
            }
            echo "</tr>";
        }
        echo"</table></div>";
    ?>
    <script>
        var selectedCells = []; //儲存其餘忙碌的時間
        function cellClick(cell) {
            var data;
            if(cell.style.backgroundColor !== "rgb(88, 109, 154)")
            {
                cell.style.backgroundColor = "#586d9a";
                cell.style.color = "#586d9a";
                data = cell.innerHTML;
                let dataarray = data.split('<br>');
                selectedCells.push(dataarray);
            }
            else 
            {
                cell.style.backgroundColor = "#ffffff"
                cell.style.color = "#ffffff";
                data = cell.innerHTML;
                let dataarray = data.split('<br>');
                let indexToRemove = selectedCells.findIndex(item => item.join(', ') === dataarray.join(', '));
                if (indexToRemove !== -1) {
                    selectedCells.splice(indexToRemove, 1);
                }
            }
        }
    </script>
    
    <div>
        <button type="button" class="ButtonStyle" style="background-color:#87c4a3 ; border-color: #87c4a3;">確認</button>
    </div>
</body>
</html>