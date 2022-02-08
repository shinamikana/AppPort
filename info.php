<?php
session_start();
session_regenerate_id(TRUE);
use PHPMailer\PHPMailer\PHPMailer;
use PHPMailer\PHPMailer\SMTP;
use PHPMailer\PHPMailer\Exception;


if (count($_POST) != 0) {

    if (isset($_POST['csrfToken']) && $_POST['csrfToken'] === $_SESSION['csrfToken']) {
        if (isset($_POST['username']) && isset($_POST['subject']) && isset($_POST['text']) && $_POST['username'] != '' && $_POST['subject'] != '' && $_POST['text'] != '') {
            $username = htmlspecialchars($_POST['username'], ENT_QUOTES);
            $subjectText = htmlspecialchars($_POST['subject'], ENT_QUOTES);
            $text = htmlspecialchars($_POST['text'], ENT_QUOTES);
            $pass = getenv('MAIL_PASSWORD');
            $to = getenv('MAIL_TO');
            $from = getenv('MAIL_FROM');
            $subject = 'AppPortだけど' . $username . 'さんから' . $subjectText . 'についてのお問い合わせです';
            mb_language('Japanese');
            mb_internal_encoding('UTF-8');
            require 'vendor/autoload.php';
            $mail = new PHPMailer(true);
            $mail->isSMTP();
            $mail->Host = 'smtp.gmail.com';
            $mail->SMTPAuth = true;
            $mail->Username = $from;
            $mail->Password = getenv('MAIL_PASSWORD');
            $mail->SMTPSecure = 'tls';
            $mail->Port = 587;
            $mail->setFrom($from, 'AppPort');
            $mail->addAddress($to, mb_encode_mimeheader('shinami', 'ISO-2022-JP'));
            $mail->Subject = mb_encode_mimeheader($subject, 'ISO-2022-JP');
            $mail->Body = mb_convert_encoding($text, 'JIS', 'UTF-8');
            if ($mail->send()) {
                header('Location:infoDone.php');
            } else {
                $error = '<p>サイトかメールアカウントに何かあったようです。</p>';
                $error = $error . '<p>お手数ですが<a href="https://twitter.com/tunanikan">こちら</a>のツイッターまで至急ご連絡ください。</p>';
            }
        } else {
            $error = '';
            if (empty($_POST['username'])) {
                $error = '<p>ユーザーネームが入力されていません！</p>';
            }
            if (empty($_POST['subject'])) {
                $error = $error . '<p>件名が入力されていません！</p>';
            }
            if (empty($_POST['text'])) {
                $error = $error . '<p>本文が入力されていません！</p>';
            }
        }
    }else{
        $error = '不正な問い合わせです。';
    }
} else {
    $error = '';
}

$csrfByte = openssl_random_pseudo_bytes(16);
$csrfToken = bin2hex($csrfByte);
$_SESSION['csrfToken'] = $csrfToken;

function h($str){
  return htmlspecialchars($str,ENT_QUOTES,'UTF-8');
}
?>

<!DOCTYPE html>
<html lang="ja">

<head>
    <meta charset="UTF-8">
    <meta http-equiv="X-UA-Compatible" content="IE=edge">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>お問い合わせ</title>
    <link rel="stylesheet" href="/css/info.css">
    <link rel="preconnect" href="https://fonts.googleapis.com">
    <link rel="preconnect" href="https://fonts.gstatic.com" crossorigin>
    <link href="https://fonts.googleapis.com/css2?family=Kaisei+Tokumin:wght@500&display=swap" rel="stylesheet">
    <script src="https://www.google.com/recaptcha/api.js"></script>
</head>

<body>
    <?php include('miniLogo.php') ?>
    <main>
        <h1>お問い合わせ</h1>
        <?php if ($error !== '') : ?>
            <div class="error">
                <p class="infoError"><?php echo $error ?></p>
            </div>
        <?php endif ?>

        <form action="info.php" method="POST" id="infoForm">
            <p>ユーザーネーム</p>
            <input type="text" id="username" name="username" value="<?php if (isset($_POST['username'])) {
                                                                        echo h($_POST['username']);
                                                                    } ?>">
            <p>件名</p>
            <input type="text" id="subject" name="subject" value="<?php if (isset($_POST['subject'])) {
                                                                        echo h($_POST['subject']);
                                                                    } ?>">
            <p>お問い合わせ内容</p>
            <textarea name="text" cols="30" rows="10" id="text"><?php if (isset($_POST['text'])) {
                                                                    echo h($_POST['text']);
                                                                } ?></textarea>
            <input type="hidden" name="csrfToken" value="<?= $csrfToken ?>">
            <br>
            <input type="submit" id="button" class="g-recaptcha" data-sitekey="<?php echo getenv('API_KEY_RE') ?>" data-action='submit' data-callback='onSubmit'>
        </form>
    </main>
    <script>
        function onSubmit(token) {
            document.getElementById('infoForm').submit();
        }
    </script>
</body>

</html>
