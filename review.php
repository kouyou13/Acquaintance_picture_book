<!-- 復習画面 -->
<?php
	include 'db_config.php';
	$userid = $_POST['userid'];
	$ErrorMessage = '';
	
	if(isset($_POST['quenstion_num']))
		$quenstion_num = $_POST['quenstion_num'] + 1; //何問目か
	else
		$quenstion_num = 1;
	
	if(isset($_POST['true_quenstion_num']))
		$true_quenstion_num = $_POST['true_quenstion_num']; //何問正解したか
	else
		$true_quenstion_num = 0;

	try{
		// connect＿データベースに接続
		$db = new PDO(PDO_DSN, DB_USERNAME, DB_PASSWORD);
		$db->setAttribute(PDO::ATTR_ERRMODE, PDO::ERRMODE_EXCEPTION);
		
		$tmp = $db->query("select count(id) as cnt from characters WHERE userid = $userid");
		$characters_counts = $tmp->fetch(PDO::FETCH_ASSOC);
		$characters_count = $characters_counts["cnt"];

		if($characters_count > 0){
			$first_ramdom_num_characters = mt_rand(1, $characters_count); //クイズに出す人をランダムに持ってくる
			$first_ramdom_num_characters -= 1;
			$temp = $db->query("SELECT * FROM characters WHERE userid = $userid");
			$first_character = $temp->fetchAll(PDO::FETCH_ASSOC);
			if(isset($first_character[$first_ramdom_num_characters]['id']))
				$character_name = $first_character[$first_ramdom_num_characters]['name'];
			
			$second_ramdom_num_characters = mt_rand(1, $characters_count);
			$second_ramdom_num_characters -= 1;
			$temp = $db->query("SELECT * FROM characters WHERE userid = $userid");
			$second_character = $temp->fetchAll(PDO::FETCH_ASSOC);
			
			$random_num_column = mt_rand(2, 5); //上で持ってきた人の要素をランダムに持ってくる
			// 1:写真 2:年齢 3:性別 4:職業 5:備考
			if(isset($character_name) && isset($second_character[0]['id'])){
				switch($random_num_column){
					// case 1:
					case 2:
						$info = $second_character[$second_ramdom_num_characters]['age'];
						$info_answer = $first_character[$first_ramdom_num_characters]['age'];
						break;	
					case 3:
						$info = $second_character[$second_ramdom_num_characters]['sex'];
						$info_answer = $first_character[$first_ramdom_num_characters]['sex'];
						break;
					case 4:
						$info = $second_character[$second_ramdom_num_characters]['job'];
						$info_answer = $first_character[$first_ramdom_num_characters]['job'];
						break;
					case 5:
						$info = $second_character[$second_ramdom_num_characters]['remarks'];
						$info_answer = $first_character[$first_ramdom_num_characters]['remarks'];
						break;
				}	
			}
		}
		else{ //データがないとき
				$ErrorMessage = '人物を追加してください';
		}
	}
	catch (PDOException $e) {
		$ErrorMessage = 'データベースエラー';
	}
?>
<!DOCTYPE HTML>
<html>
	<head>
		<meta charset="utf-8">
		<title>知り合い図鑑</title>
		<!-- CSS -->
		<link rel="stylesheet" href="css/review.css?<?php echo date('Ymd-His'); ?>">
		<!--FONT-->
		<link rel="preconnect" href="https://fonts.gstatic.com">
		<link href="https://fonts.googleapis.com/css2?family=M+PLUS+Rounded+1c&display=swap" rel="stylesheet">
	</head>
	<body>
		<div class='Tab_on_top'>
			<img id="logo" src='img/logo.png'>
		</div>
		<div style='height: 10vw;'>
		</div>
		
		<div class='return'>
			<form action='list.php' method='GET'>
				<input type="hidden" name="userid" value="<?php echo $userid; ?>">
				<input id="return-button" type="submit" name="list" value="←TOP">
			</form>
		</div>
		
		<h2>復習画面</h2>
		<div>
			
			<?php
				if(isset($character_name) && isset($info)){
					echo "<h3>第{$quenstion_num}問</h3>";
					switch($random_num_column){
						case 2:
							if ($info == -1)
								echo "<p>{$character_name}さんは年齢不詳である<br>";
							else
								echo "<p>{$character_name}さんは{$info}歳である<br>";
							break;
						case 3:
							echo "<p>{$character_name}さんの性別は{$info}である<br>";
							break;
						case 5:
							echo "<p>{$character_name}さんは{$info}<br>";
							break;
						default:
							echo "<p>{$character_name}さんは{$info}である<br>";
							break;
					}
					
					echo "<div class='image'>
						<form action='answer.php' method='POST'>
							<input type='hidden' id='userid' name='userid' value=$userid>
							<input type='hidden' id='info' name='info' value=$info>
							<input type='hidden' id='answer' name='answer' value=$info_answer>
							<input type='hidden' id='quenstion_num' name='quenstion_num' value=$quenstion_num>
							<input type='hidden' id='user_answer' name='user_answer' value='true'>
							<input type='hidden' id='random_num' name='random_num' value=$random_num_column>
							<input type='hidden' id='character_name' name='character_name' value='{$character_name}'>
							<input type='hidden' id='true_quenstion_num' name='true_quenstion_num' value=$true_quenstion_num>
							<input type='image' id='circle' name='circle' src='img/select1.png' value='○'>
						</form>
						<form action='answer.php' method='POST'>
							<input type='hidden' id='userid' name='userid' value=$userid>
							<input type='hidden' id='info' name='info' value=$info>
							<input type='hidden' id='answer' name='answer' value=$info_answer>
							<input type='hidden' id='quenstion_num' name='quenstion_num' value=$quenstion_num>
							<input type='hidden' id='user_answer' name='user_answer' value='false'>
							<input type='hidden' id='random_num' name='random_num' value=$random_num_column>
							<input type='hidden' id='character_name' name='character_name' value='{$character_name}'>
							<input type='hidden' id='true_quenstion_num' name='true_quenstion_num' value=$true_quenstion_num>
							<input type='image' id='cross' name='cross' src='img/select2.png' value='×'>
						</form>			
					</div>";
				}
				else{
					echo "<p>{$ErrorMessage}</p>";
				}
			 ?>
		</div>
	</body>
</html>