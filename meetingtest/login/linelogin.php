
<!DOCTYPE html>
<html lang="Zh-TW">

<head>
    <meta charset="utf-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">        
    <meta http-equiv="X-UA-Compatible" content="ie=edge">
    <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.1/dist/css/bootstrap.min.css" rel="stylesheet" integrity="sha384-4bw+/aepP/YC94hEpVNVgiZdgIC5+VKNBQNGCHeKRQN+PtmoHDEXuppvnDJzQIu9" crossorigin="anonymous">
    <link rel="stylesheet" href="../login/linelogin.css">
    <script src='https://cdnjs.cloudflare.com/ajax/libs/jquery/3.4.1/jquery.js'></script>
    <script src="js/jquery-1.3.2.js"></script> 
    <title>LINE Login </title>
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
        <div class="form">
            <div class="text1">
                登入

            </div>
            <button class="submitBtn" id="lineLoginBtn">line 登入</button>
        </div>
    </div>           
    <script>
            $('#lineLoginBtn').on('click', function(e) {
                let client_id = '2000473574';
                let redirect_uri = 'http://localhost/meeting/login/login.php';
                let link = 'https://access.line.me/oauth2/v2.1/authorize?';
                link += 'response_type=code';
                link += '&client_id=' + client_id;
                link += '&redirect_uri=' + redirect_uri;
                link += '&state=login';
                link += '&scope=openid%20profile';
                window.location.href = link;
            });
    </script>

</body>
</html>