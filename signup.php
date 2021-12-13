<?php
include('dateBase.php');
if(isset($_SESSION['id'])){
    session_regenerate_id(TRUE);
    header('Location:index.php');
    exit();
}

if(count($_POST) === 0){
    $message = '';
}else{
    if (isset($_POST['guest'])) {
        $_SESSION['id'] = 25;
        $_SESSION['username'] = 'ゲスト';
        header('Location:index.php');
    }
    if(empty($_POST['username']) && empty($_POST['email']) && empty($_POST['password'])){
        $message = '入力されていない箇所があります';
    }else{
        if($_POST['pass'] !== $_POST['repass']){
            $message = 'パスワードが一致していません';
        }else{
            $hashPass = password_hash($_POST['pass'],PASSWORD_BCRYPT);
            $check = $mysqli -> prepare('SELECT * FROM users WHERE email=?');
            $check -> bind_param('s',$_POST['email']);
            $check -> execute();
            $checkResult = $check -> get_result() -> fetch_assoc();
            if(isset($chckResult)){
                $message = 'すでに登録済みのメールアドレスです';
            }else{
                $signup = $mysqli -> prepare('INSERT INTO users(email,username,password) VALUES(?,?,?)');
                $signup -> bind_param('sss',$_POST['email'],$_POST['username'],$hashPass);
                $signup -> execute();
                $signupResult = $signup -> fetch();
                session_regenerate_id(TRUE);
                $_SESSION['username'] = $_POST['username'];
                $_SESSION['id'] = $mysqli->insert_id;
                header('Location:index.php');
                exit();
            }
        }
    }
}

htmlspecialchars($message);
?>

<!DOCTYPE html>
<html lang="ja">
<head>
    <meta charset="UTF-8">
    <meta http-equiv="X-UA-Compatible" content="IE=edge">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>新規登録</title>
    <link rel="stylesheet" href="/css/signup.css">
    <script src="https://www.google.com/recaptcha/api.js"></script>
</head>
<body>
    <?php include('miniLogo.php'); ?>

    

    <main>
        <h1>新規登録</h1>

        <?php if($message !== ''): ?>
            <div class="error">
                <p><?php echo $message ?></p>
            </div>
        <?php endif ?>

        <div class="form">
            <form action="signup.php" method="POST" id="signupForm">
                <p>ユーザー名</p>
                <input type="text" name="username" value="<?php if(isset($_POST['username'])){echo $_POST['username'];} ?>">
                <p>メールアドレス</p>
                <input type="text" name="email" value="<?php if(isset($_POST['email'])){echo $_POST['email'];} ?>">
                <p>パスワード</p>
                <input type="password" name="pass">
                <p>パスワード(確認)</p>
                <input type="password" name="repass">
                <br>
                <button id="loginSubmit" class="g-recaptcha" data-sitekey="<?php echo getenv('API_KEY_RE')?>" data-callback='onSubmit' data-action='submit'>新規登録</button>
            </form>
            <form action="login.php" method="POST" id="guestForm">
                <input type="hidden" value="guest" name="guest">
                <button id="guestLogin" class="g-recaptcha" data-sitekey="<?php echo getenv('API_KEY_RE') ?>" data-callback='onSubmitGuest' data-action='submit'>ゲストはこちら</button>
            </form>
        </div>

    </main>

    <script>
        function onSubmit(token){
            document.getElementById("signupForm").submit();
        }
        function onSubmitGuest(token) {
            document.getElementById("guestForm").submit();
        }
    </script>
</body>
</html>