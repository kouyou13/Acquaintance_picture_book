<!-- ログアップ画面 -->
<?php
	include 'db_config.php';
	$ErrorMessage_username = '';
	$ErrorMessage_password = '';
	
	$flag_username = false;
	$flag_password = false;
	
	// ログインボタンが押された場合
	if(isset($_POST['login'])){
		if(empty($_POST['username']))
			$ErrorMessage_username = 'ユーザー名が未入力です';
		else
			$flag_username = true;
		
		if(empty($_POST['password']))
			$ErrorMessage_password = 'パスワードが未入力です';
		else if (!preg_match('/^[0-9a-zA-Z]{8,30}$/', $_POST["password"]))
			$ErrorMessage_password = 'パスワードは半角英数字の8文字以上<br>30文字以下で入力して下さい';
		else
			$flag_password = true;
		
		if($flag_username==true && $flag_password==true){
			try{
				// connect＿データベースに接続
     			$db = new PDO(PDO_DSN, DB_USERNAME, DB_PASSWORD);
     			$db->setAttribute(PDO::ATTR_ERRMODE, PDO::ERRMODE_EXCEPTION);
				
				$new_username = $_POST['username'];
				$new_password = $_POST['password'];
				
				$tmp = $db->query("SELECT * FROM users WHERE username = '$new_username'");
	 			$users_username = $tmp->fetch(PDO::FETCH_ASSOC);
				$tmp = $db->query("SELECT * FROM users WHERE password = '$new_password'");
	 			$users_password = $tmp->fetch(PDO::FETCH_ASSOC);
				
				if(isset($users_username['id']))
					$ErrorMessage_username = "この名前は使用できません";
				if(isset($users_password['id']))
					$ErrorMessage_password = "このパスワードは使用できません";
				
				if(!isset($users_username['id']) && !isset($users_password['id'])){
					$db->exec("INSERT INTO users(username, password) VALUES('$new_username', '$new_password')");
					$tmp = $db->query("SELECT * FROM users WHERE username = '$new_username'");
	 				$user = $tmp->fetch(PDO::FETCH_ASSOC);
					$db = null;
					$userid=$user['id'];
					header("Location:list.php?userid=$userid");  // メイン画面へ遷移
		        	exit();  // 処理終了
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
		<link rel="stylesheet" href="css/logup.css?<?php echo date('Ymd-His'); ?>"> <!--cssを追加-->
		<!--FONT-->
		<link rel="preconnect" href="https://fonts.gstatic.com">
		<link href="https://fonts.googleapis.com/css2?family=M+PLUS+Rounded+1c&display=swap" rel="stylesheet">
	</head>
	<body>
		<div class='Tab_on_top'>
			<img id="logo" src='img/logo.png'>
		</div>
		
		<div>
			
			<h2>ログアップ</h2>
			
			<form method='post' action=''>
				<?php
					if(!empty($_POST["username"]))
						echo "<input type='text' name='username' class='username' placeholder='ユーザー名' value='{$_POST['username']}' required>";
						
					else
						echo "<input type='text' name='username' class='username' placeholder='ユーザー名' required>";
					
					echo "<div class=error>$ErrorMessage_username</div>";
				?>
				
				<?php
					if(!empty($_POST["password"]))
						echo "<input type='password' name='password' class='password' placeholder='パスワード' value='{$_POST['password']}' required>";
						
					else
						echo "<input type='password' name='password' class='password' placeholder='パスワード' required>";
					
					echo "<div class=error>$ErrorMessage_password</div>";
				?>
				<!-- id="logup-buttonを追加 -->
				<input id="logup-button" type="submit" name="login" value="新規登録">				
			</form>

			<div align="center">	<!-- cssで中央寄せにできなかったからココに記述した -->			
				<table><tr><td><hr width='100px'></td><td>アカウントを持っている方</td><td><hr width='100px'></td></tr></table>
			</div>
			
			<a href='login.php'>ログイン</a>
		</div>
	</body>
</html>