   <head>
      <link rel="stylesheet" href="/css/sidebar.css">
      <link rel="preconnect" href="https://fonts.googleapis.com">
      <link rel="preconnect" href="https://fonts.gstatic.com" crossorigin>
      <link href="https://fonts.googleapis.com/css2?family=Kaisei+Tokumin:wght@500&display=swap" rel="stylesheet">
   </head>
   
   <div class="lefter">
      <ul>
         <?php if(!isset($_SESSION['id'])): ?>
         <li><a href="signup.php">新規登録</a></li>
         <li><a href="login.php">ログイン</a></li>
         <?php else: ?>
         <li><a href="logout.php">ログアウト</a></li>
         <?php endif ?>
      </ul>
   </div>
    <div class="righter">

    </div>