<?php
include('dateBase.php');
if(isset($_SESSION['id'])){
    session_regenerate_id(TRUE);
    header('Location:index.php');
    exit();
}

if(count($_POST)===0){
    $message = '';
}else{
    if(empty($_POST['email']) && empty($_POST['pass'])){
        $message = 'メールアドレスとパスワードを入力してください';
    }else{
        $login = $mysqli -> prepare('SELECT * FROM users WHERE email = ?');
        $login -> bind_param('s',$_POST['email']);
        $login -> execute();
        $result = $login -> get_result() -> fetch_assoc();
        if(!isset($result['email'])){
            $message = 'メールアドレスがパスワードが違います';
        }else{
            if(!password_verify($_POST['pass'],$result['password'])){
                $message = 'メールアドレスがパスワードが違います';
            }else{
                session_regenerate_id(TRUE);
                $_SESSION['id'] = $result['id'];
                $_SESSION['username'] = $result['username'];
                header('Location:index.php');
            }
        }

    }
}

htmlspecialchars($message);
?>

<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta http-equiv="X-UA-Compatible" content="IE=edge">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <link rel="stylesheet" href="/css/login.css">
    <title>ログイン</title>
    <link rel="preconnect" href="https://fonts.googleapis.com">
      <link rel="preconnect" href="https://fonts.gstatic.com" crossorigin>
      <link href="https://fonts.googleapis.com/css2?family=Kaisei+Tokumin:wght@500&display=swap" rel="stylesheet">
      <script src="https://www.google.com/recaptcha/api.js"></script>
</head>
<body>
    <?php include('miniLogo.php'); ?>

    <main>
        <h1>ログイン</h1>

        <?php if($message !== ''): ?>
            <div class="error">
                <p><?php echo $message ?></p>
            </div>
        <?php endif ?>
        <div class="form">
            <form action="login.php" method="POST" id="loginForm">
                <p>メールアドレス</p>
                <input type="text" name="email" value="<?php if(isset($_POST['email'])){ echo $_POST['email']; }?>">
                <p>パスワード</p>
                <input type="password" name="pass">
                <br>
                <button id="loginSubmit" class="g-recaptcha" data-sitekey="<?php echo getenv('API_KEY_RE')?>" data-callback='onSubmit' data-action='submit'>ログイン</button>
            </form>
        </div>
    </main>
    
<script>
    function onSubmit(token) {
     document.getElementById("loginForm").submit();
   }
</script>
</body>
</html>