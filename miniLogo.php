<head>
  <link rel="stylesheet" href="/css/miniLogo.css">
  <link rel="preconnect" href="https://fonts.googleapis.com">
  <link rel="preconnect" href="https://fonts.gstatic.com" crossorigin>
  <link href="https://fonts.googleapis.com/css2?family=Michroma&display=swap" rel="stylesheet">
  <link href="https://fonts.googleapis.com/css2?family=Kaisei+Tokumin:wght@500&display=swap" rel="stylesheet">
</head>

<div class="miniLogo">
  <a href="memo.php">AppPort</a>
  <?php if(isset($_SESSION['username'])): ?>
  <span id="hi">こんにちは！<?= $_SESSION['username'] ?>さん！</span>
  <?php endif ?>
  <a href="info.php" id="info">お問い合わせ</a>
  <a href="#" id="setting"><i class="fa-solid fa-gear"></i></a>
</div>
