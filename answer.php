<!-- 解答画面 -->
<?php
	include 'db_config.php';
	$userid = $_POST['userid']; //ユーザーID
	$quenstion_num = $_POST['quenstion_num']; //問題数
	$info = $_POST['info']; //問題
	$answer = $_POST['answer']; //答え
	$user_answer = $_POST['user_answer']; //ユーザーの答え
	$random_num =$_POST['random_num'];
	$character_name = $_POST['character_name'];
	$true_quenstion_num = $_POST['true_quenstion_num']; //正解数
	$flag_correct_incorrect = false; //ユーザーが正解ならtrue
	
	$question_text = ''; //間違えた時の問題文
	$true_quenstion_num_text = ''; //正解した問題数
	$false_text = ''; //間違えた時の文章
	
	if($info == $answer){ //答えが○の時
		if($user_answer == 'true'){ //正解
			$flag_correct_incorrect = true;
			$true_quenstion_num += 1;
		}
		else{ //不正解
			$flag_correct_incorrect = false;
			switch($random_num){
				case 2:
					// $question_text = '問題：'.$character_name.'さんは'.$info.'歳である';
					$false_text = '解答：'.$character_name.'さんは'.$answer.'歳である';
					break;
				case 5:
					// $question_text = '問題：'.$character_name.'さんは'.$info;
					$false_text = '解答：'.$character_name.'さんは'.$answer;
					break;
				default:
					// $question_text = '問題：'.$character_name.'さんは'.$info.'である';
					$false_text = '解答：'.$character_name.'さんは'.$answer.'である';
					break;
			}
		}
	}
	else if($info != $answer){ //答えが×のとき
		if($user_answer != 'true'){ //正解
			$flag_correct_incorrect = true;
			$true_quenstion_num += 1;
		}
		else{ //不正解
			$flag_correct_incorrect = false;
			switch($random_num){
				case 2:
					$question_text = '問題：'.$character_name.'さんは'.$info.'歳である';
					$false_text = '解答：'.$character_name.'さんは'.$answer.'歳である';
					break;
				case 5:
					$question_text = '問題：'.$character_name.'さんは'.$info;
					$false_text = '解答：'.$character_name.'さんは'.$answer;
					break;
				default:
					$question_text = '問題：'.$character_name.'さんは'.$info.'である';
					$false_text = '解答：'.$character_name.'さんは'.$answer.'である';
					break;
			}
		}
	}
	$true_quenstion_num_text = '正解数：'.$true_quenstion_num.'/'.$quenstion_num;
?>
<!DOCTYPE HTML>
<html>
	<head>
		<meta charset="utf-8">
		<title>知り合い図鑑</title> 
		<!--CSS-->
		<link rel="stylesheet" href="css/answer.css?<?php echo date('Ymd-His'); ?>">
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
		
		<form action='list.php' method='GET' class='return'>
			<input type="hidden" name="userid" value="<?php echo $userid; ?>">
			<input id="return-button" type="submit" name="list" value="←TOP">
		</form>
		
		<?php
			if($flag_correct_incorrect == true){
				echo "<h2 id=true>正解</h2>
				<p>$true_quenstion_num_text</p>
				<img src='img/true.png'>"; //正解の画像
			}
			else if($flag_correct_incorrect == false){
				echo "<h2 id=false>不正解</h2>
				<p>$true_quenstion_num_text</p>
				<p>$question_text</p>
				<p id=red>$false_text</p>
				<img src='img/false.png'>"; //不正解の画像
				
			}
			
			echo "<form action='review.php' method='POST'>
					<input type='hidden' name='userid' value=$userid>
					<input type='hidden' name='quenstion_num' value=$quenstion_num>
					<input type='hidden' name='true_quenstion_num' value=$true_quenstion_num>
					<input type='submit' name='next' value='次に進む'>
				</form>";
			
			echo "<form action='list.php' method='GET'>
						<input type='hidden' name='userid' value=$userid>
						<input type='submit' name='next' value='終了'>
					</form>";
			
			// if($quenstion_num == 10){
			// 	echo "<form action='list.php' method='GET'>
			// 			<input type='hidden' name='userid' value=$userid>
			// 			<input type='submit' name='next' value='終了'>
			// 		</form>";
			// }
			// else{
			// 	echo "<form action='review.php' method='POST'>
			// 		<input type='hidden' name='userid' value=$userid>
			// 		<input type='hidden' name='quenstion_num' value=$quenstion_num>
			// 		<input type='submit' name='next' value='次に進む'>
			// 	</form>";
			// }
		?>
	</body>
</html>