<!-- �𓚉�� -->
<?php
	include 'db_config.php';
	$userid = $_POST['userid']; //���[�U�[ID
	$quenstion_num = $_POST['quenstion_num']; //��萔
	$info = $_POST['info']; //���
	$answer = $_POST['answer']; //����
	$user_answer = $_POST['user_answer']; //���[�U�[�̓���
	$random_num =$_POST['random_num'];
	$character_name = $_POST['character_name'];
	$true_quenstion_num = $_POST['true_quenstion_num']; //����
	$flag_correct_incorrect = false; //���[�U�[�������Ȃ�true
	
	$question_text = ''; //�ԈႦ�����̖�蕶
	$true_quenstion_num_text = ''; //����������萔
	$false_text = ''; //�ԈႦ�����̕���
	
	if($info == $answer){ //���������̎�
		if($user_answer == 'true'){ //����
			$flag_correct_incorrect = true;
			$true_quenstion_num += 1;
		}
		else{ //�s����
			$flag_correct_incorrect = false;
			switch($random_num){
				case 2:
					// $question_text = '���F'.$character_name.'�����'.$info.'�΂ł���';
					$false_text = '�𓚁F'.$character_name.'�����'.$answer.'�΂ł���';
					break;
				case 5:
					// $question_text = '���F'.$character_name.'�����'.$info;
					$false_text = '�𓚁F'.$character_name.'�����'.$answer;
					break;
				default:
					// $question_text = '���F'.$character_name.'�����'.$info.'�ł���';
					$false_text = '�𓚁F'.$character_name.'�����'.$answer.'�ł���';
					break;
			}
		}
	}
	else if($info != $answer){ //�������~�̂Ƃ�
		if($user_answer != 'true'){ //����
			$flag_correct_incorrect = true;
			$true_quenstion_num += 1;
		}
		else{ //�s����
			$flag_correct_incorrect = false;
			switch($random_num){
				case 2:
					$question_text = '���F'.$character_name.'�����'.$info.'�΂ł���';
					$false_text = '�𓚁F'.$character_name.'�����'.$answer.'�΂ł���';
					break;
				case 5:
					$question_text = '���F'.$character_name.'�����'.$info;
					$false_text = '�𓚁F'.$character_name.'�����'.$answer;
					break;
				default:
					$question_text = '���F'.$character_name.'�����'.$info.'�ł���';
					$false_text = '�𓚁F'.$character_name.'�����'.$answer.'�ł���';
					break;
			}
		}
	}
	$true_quenstion_num_text = '���𐔁F'.$true_quenstion_num.'/'.$quenstion_num;
?>
<!DOCTYPE HTML>
<html>
	<head>
		<meta charset="utf-8">
		<title>�m�荇���}��</title> 
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
			<input id="return-button" type="submit" name="list" value="��TOP">
		</form>
		
		<?php
			if($flag_correct_incorrect == true){
				echo "<h2 id=true>����</h2>
				<p>$true_quenstion_num_text</p>
				<img src='img/true.png'>"; //�����̉摜
			}
			else if($flag_correct_incorrect == false){
				echo "<h2 id=false>�s����</h2>
				<p>$true_quenstion_num_text</p>
				<p>$question_text</p>
				<p id=red>$false_text</p>
				<img src='img/false.png'>"; //�s�����̉摜
				
			}
			
			echo "<form action='review.php' method='POST'>
					<input type='hidden' name='userid' value=$userid>
					<input type='hidden' name='quenstion_num' value=$quenstion_num>
					<input type='hidden' name='true_quenstion_num' value=$true_quenstion_num>
					<input type='submit' name='next' value='���ɐi��'>
				</form>";
			
			echo "<form action='list.php' method='GET'>
						<input type='hidden' name='userid' value=$userid>
						<input type='submit' name='next' value='�I��'>
					</form>";
			
			// if($quenstion_num == 10){
			// 	echo "<form action='list.php' method='GET'>
			// 			<input type='hidden' name='userid' value=$userid>
			// 			<input type='submit' name='next' value='�I��'>
			// 		</form>";
			// }
			// else{
			// 	echo "<form action='review.php' method='POST'>
			// 		<input type='hidden' name='userid' value=$userid>
			// 		<input type='hidden' name='quenstion_num' value=$quenstion_num>
			// 		<input type='submit' name='next' value='���ɐi��'>
			// 	</form>";
			// }
		?>
	</body>
</html>