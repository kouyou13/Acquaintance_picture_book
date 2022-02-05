<!-- ログイン画面 -->
<?php
	include 'db_config.php';
	$ErrorMessage_username = '';
	$ErrorMessage_password = '';
	
	// ログインボタンが押された場合
	if(isset($_POST['login'])){
		if(empty($_POST['username']))
			$ErrorMessage_username = 'ユーザー名が未入力です';
		
		if(empty($_POST['password']))
			$ErrorMessage_password = 'パスワードが未入力です';
		
		if(!empty($_POST['username']) && !empty($_POST['password'])){
			try{
				// connect＿データベースに接続
     			$db = new PDO(PDO_DSN, DB_USERNAME, DB_PASSWORD);
     			$db->setAttribute(PDO::ATTR_ERRMODE, PDO::ERRMODE_EXCEPTION);
				
				$username = $_POST['username'];
				$password = $_POST['password'];
				
				$stmt = $db->query("SELECT * FROM users WHERE username = '$username' AND password = '$password'");
	 			$user = $stmt->fetch(PDO::FETCH_ASSOC);
				
				if (isset($user['id'])){
					$db = null;
					$userid=$user['id'];

					// require("list.php");  // メイン画面へ遷移
					header("Location:list.php?userid=$userid");
		        	exit();  // 処理終了
				}
				else {
	                // 認証失敗
	                $ErrorMessage_password = 'ユーザー名あるいはパスワードに誤りがあります';
	            }
			}
			catch (PDOException $e) {
				$ErrorMessage_password = 'データベースエラー';
			}
		}
	}
?>
<!DOCTYPE HTML>
<html>
	<head>
		<meta charset="utf-8">
		<title>知り合い図鑑</title>
		<!-- CSS -->
		<link rel="stylesheet" href="css/login.css?<?php echo date('Ymd-His'); ?>">
		<!--FONT-->
		<link rel="preconnect" href="https://fonts.gstatic.com">
		<link href="https://fonts.googleapis.com/css2?family=M+PLUS+Rounded+1c&display=swap" rel="stylesheet">
		<script src="login.js"></script> 
	</head>
	<body>
		<div class='Tab_on_top'>
			<img id="logo" src='img/logo.png'>
		</div>
		
		<div>
			<h2>ログイン</h2>	<!-- ログイン追加した -->
			
			<form method='POST' action=''>
				<?php
					if(!empty($_POST['username']))
						echo "<input type='text' name='username' class='username' placeholder='ユーザー名' value='{$_POST['username']}' required>";
						
					else
						echo "<input type='text' name='username' class='username' placeholder='ユーザー名' required>";
					
					echo "<div class=error>$ErrorMessage_username</div>";
				
				
					if(!empty($_POST["password"]))
						echo "<input type='password' name='password' class='password' placeholder='パスワード' value='{$_POST['password']}' required>";
						
					else
						echo "<input type='password' name='password' class='password' placeholder='パスワード' required>";
					
					echo "<div class=error>$ErrorMessage_password</div>";
				?>
				<!-- id="login-buttonを追加 -->
				<input id="login-button" type="submit" name="login" value="ログイン">
			</form>
			
			<div align="center">	<!-- cssで中央寄せにできなかったからココに記述した -->
				<table><tr><td><hr width='100px'></td><td>アカウントを持っていない方</td><td><hr width='100px'></td></tr></table>
			</div>
			
			<a href='logup.php'>新規登録</a>

		</div>
	</body>
</html>