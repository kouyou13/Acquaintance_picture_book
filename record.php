<!-- 記録画面 -->
<?php
	include 'db_config.php';
	$userid = $_POST['userid'];
	$ErrorMessage = '';
	$photo = '';	
	$age_flag = false;

	if (isset($_POST["record"])) {
		$str = mb_convert_encoding($_POST["remarks"],"sjis","utf-8");
		$length_remarks = strlen($str); //文字数(バイト数)を出す
		
		if ($length_remarks > 28){ //半角で28文字
			$ErrorMessage = '備考は14文字以内で書いて下さい';
		}
		
		if($_POST["age"] < -1 || $_POST["age"] > 120)
			$ErrorMessage = 'その値は使用できません';
		
		if (empty($_FILES['photo']['name'])){
			$photo = 'img_chara/no_image.jpg';
		}
		else{
			$uploadFile = "./img_chara/" . basename($_FILES['photo']['name']);
			
			if (move_uploaded_file($_FILES['photo']['tmp_name'], $uploadFile)) { //一時ファイルに保存できたら
				$file_link = 'img_chara/'.$_FILES['photo']['name'];
				$photo = $file_link;
			}
		}
		
		//もしポストにデータがあるならば
		if (isset($_POST["name"]) && isset($_POST["surname"]) && isset($_POST["name_furigana"]) && isset($_POST["surname_furigana"]) && isset($photo) && isset($_POST["age"]) && isset($_POST["sex"]) && isset($_POST["job"]) && isset($_POST["remarks"]) && isset($_POST["userid"])) {
			
			$name = $_POST["surname"].' '.$_POST["name"]; //漢字での名前
			$name_furigana = $_POST["surname_furigana"].' '.$_POST["name_furigana"];
			// if (move_uploaded_file($_FILES['photo']['tmp_name'], $uploadFile)) { //base64 エンコード https://codelikes.com/php-base64-encode/#toc1
			//     // 画像のリサイズ https://dev-lib.com/php-image-size-change/
			// 	// リサイズ前画像ファイル名
	        //     $imageFile1 = $uploadFile;
	             
	        //     // リサイズ後画像ファイル名
	        //     $imageFile2 = $_FILES['photo']['name'];
	             
	        //     // コピー先画像サイズ指定
	        //     $dst_w = 200;
	        //     $dst_h = 200;
	             
	        //     // コピー先画像作成
	        //     $dst_image = ImageCreateTrueColor($dst_w, $dst_h); //https://okwave.jp/qa/q985978.html 色が変化するやつ防止
	             
	        //     // コピー元画像読み込み
			// 	$src_image = imagecreatefromstring(file_get_contents($imageFile1));
	             
	        //     // コピー元画像のサイズ取得
	        //     $imagesize = getimagesize($imageFile1);
	        //     $src_w = $imagesize[0];
	        //     $src_h = $imagesize[1];
				
			// 	if($src_w > 300 && $src_h > 300){
			// 		// リサイズしてコピー
		    //         imagecopyresampled(
		    //         	$dst_image, // コピー先の画像
		    //         	$src_image, // コピー元の画像
		    //         	0,          // コピー先の x 座標
		    //         	0,          // コピー先の y 座標。
		    //         	0,          // コピー元の x 座標
		    //         	0,          // コピー元の y 座標
		    //         	$dst_w,     // コピー先の幅
		    //         	$dst_h,     // コピー先の高さ
		    //         	$src_w,     // コピー元の幅
		    //         	$src_h);    // コピー元の高さ
			// 	}
	             
	        //     // 画像をファイルに出力
	        //     imagepng($dst_image, $imageFile2);
	        //     $image = file_get_contents($imageFile2);
				
			// 	unlink($imageFile2); //ファイル削除
			// 	unlink($uploadFile); //ファイル削除
			// }
			// $base64Text = base64_encode($image); //画像をbase64で文字列に変換
			
			try{
				// connect＿データベースに接続
				$db = new PDO(PDO_DSN, DB_USERNAME, DB_PASSWORD);
				$db->setAttribute(PDO::ATTR_ERRMODE, PDO::ERRMODE_EXCEPTION);
				
				$tmp = $db->query("select count(id) as cnt from characters");
				$characters_counts = $tmp->fetch(PDO::FETCH_ASSOC);
				$characters_count = $characters_counts["cnt"]; //キャラ数
				
				//ポストのデータを変数に変換する
				$chara_id = $characters_count + 1;
				// $name = $_POST["name"];
				// $name_furigana = $_POST["name_furigana"];
				// $photo = $base64Text;
				// $photo = $file_link;
				$age = $_POST["age"];
				$sex = $_POST["sex"];
				$job = $_POST["job"];
				$remarks = $_POST["remarks"];
				$userid = $_POST["userid"];
				
				$db->exec("INSERT INTO characters(id, name, furigana, photo, age, sex, job, remarks, userid) VALUES($chara_id, '$name', '$name_furigana', '$photo', $age, '$sex', '$job', '$remarks', $userid)");
				$db = null;
	
				header("Location:list.php?userid=$userid");  // メイン画面へ遷移
		        exit();  // 処理終了
			}
			catch (PDOException $e) {
				echo $e->getMessage();
				// $ErrorMessage_password = 'データベースエラー';
				// echo $name,$age,$sex,$job,$remarks,$userid;
			}
		}
	}	
?>

<!DOCTYPE HTML>
<html>
	
	<head>
		<meta charset="shift_JS">
		<title>知り合い図鑑</title> 
		<!--CSS-->
		<link rel="stylesheet" href="css/record.css"> <!--cssを追加-->
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
		
		<h2>追加画面</h2>
		<?php echo "<div class=error>$ErrorMessage</div>";?><!-- 未入力があったときに出る -->
		
		<form method="post" enctype="multipart/form-data">
			<table class='table_record' align="center">
				<?php
				//名前入力
				if(empty($_POST["name"])){
					echo "<tr><td width='30%'><p>氏名</p></td><td width='60%'><input id='surname' type='text' name='surname' placeholder='姓' required>
					<input id='name' type='text' name='name' placeholder='名' required></td></tr>";
				}
				else{
					echo "<tr><td width='30%'><p>氏名</p></td><td width='60%'><input id='surname' type='text' name='surname' placeholder='姓' value='{$_POST['surname']}' required>
					<input id='name' type='text' name='name' placeholder='名' value='{$_POST['name']}' required></td></tr>";
				}
				
				//フリガナ
				if(empty($_POST["name_furigana"])){
					echo "<tr><td><p>フリガナ</p></td><td><input id='surname_furigana' type='text' name='surname_furigana' placeholder='セイ' required>
					<input id='name_furigana' type='text' name='name_furigana' placeholder='メイ' required></td></tr>";
				}
				else{
					echo "<tr><td><p>フリガナ</p></td><td><input id='surname_furigana' type='text' name='surname_furigana' placeholder='セイ' value='{$_POST['surname_furigana']}' required>
					<input id='name_furigana' type='text' name='name_furigana' placeholder='メイ' value='{$_POST['name_furigana']}' required></td></tr>";
				}
				
				//性別
				if(empty($_POST["sex"]) || $_POST["sex"] == '男'){
					echo "<tr><td><p>性別</p></td><td><input id='radio-a' name='sex' type='radio' value='男' checked><label for='radio-a'>男</label><br>
				 		<input id='radio-b' name='sex' type='radio' value='女'><label for='radio-b'>女</label><br>
				 		<input id='radio-c' name='sex' type='radio' value='その他'><label for='radio-c'>その他</label></td></tr>";
				}
				else if($_POST["sex"] == '女'){
					echo "<tr><td><p>性別</p></td><td><input id='radio-a' name='sex' type='radio' value='男'><label for='radio-a'>男</label><br>
				 		<input id='radio-b' name='sex' type='radio' value='女' checked><label for='radio-b'>女</label><br>
				 		<input id='radio-c' name='sex' type='radio' value='その他'><label for='radio-c'>その他</label></td></tr>";
				}
				else if($_POST["sex"] == 'その他'){
					echo "<tr><td><p>性別</p></td><td><input id='radio-a' name='sex' type='radio' value='男'><label for='radio-a'>男</label><br>
				 		<input id='radio-b' name='sex' type='radio' value='女'><label for='radio-b'>女</label><br>
				 		<input id='radio-c' name='sex' type='radio' value='その他' checked><label for='radio-c'>その他</label></td></tr>";
				}
				
				//年齢 明日直す！
				if(empty($_POST["age"])){
					echo "<tr><td><p>年齢<br>（不明の場合は<br>「-1」と入力）</p></td><td><input id='age' type='number' name='age' size='6' required></td></tr>";
				}
				else{
					echo "<tr><td><p>年齢<br>（不明の場合は<br>「-1」と入力）</p></td><td><input id='age' type='number' name='age' size='6' value='{$_POST['age']}' required></td></tr>";
				}
				
				//職業
				if(empty($_POST["job"])){
					echo "<tr><td><p>職業</p></td><td><input id='job' type='text' name='job' required></td></tr>";
				}
				else{
					echo "<tr><td><p>職業</p></td><td><input id='job' type='text' name='job' value='{$_POST['job']}' required></td></tr>";
				}
				
				//備考
				if(empty($_POST["remarks"])){
					echo "<tr><td><p>備考</p></td><td><input id='remarks' name='remarks' required></input></td></tr>";
				}
				else{
					echo "<tr><td><p>備考</p></td><td><input id='remarks' name='remarks' value='{$_POST['remarks']}' required></td></tr>";
				}
				?>
				<!--<tr><td><p>写真を選択</p></td><td><input type="file" id="upload-btn" name="photo" accept="image/*" required/></td></tr>-->
				
				<script
		          src="https://code.jquery.com/jquery-2.2.4.min.js"
		          integrity="sha256-BbhdlvQf/xTY9gja0Dq3HiwQF8LaCRTXxZKRutelT44="
		          crossorigin="anonymous">
				</script>

	            <div class="formFile">
	            	<tr><td><p>写真を選択<br>（任意）</p></td>
	            	<td><label id="image" for="upload-btn" >
	            	<span class="browse_btn">ファイルを選択</span><input type="file" name="photo" id="upload-btn" accept="image/*" style='display: none;'/>
	            	</label>
	            <p class="formFileName">選択されていません</p></td></tr>
	          	</div >
		        
		        <script>
		        $(function(){
		          $(document).on('change','input[type="file"]',function(){
		            var file = this.files[0];
		            console.log(file);
		            $(this).parents('label').nextAll('.formFileName').text(file.name);
		          });
		        });
		        </script>
				
			</table>
			<input type="hidden" id="userid" name="userid" value="<?php echo $userid; ?>">
			
			<!--<script>
		    $("input[type='file']").on('change',function(){
		     var file = $(this).prop('files')[0];
		     if(!($(".filename").length)){
		       $("#upload-wrap").append('<span class="filename"></span>');
		     }
		     $("#upload-label").addClass('changed');
		     $(".filename").html(file.name);
		   });
		    </script>-->
			<br><input type="submit" id="record" name="record" value="追加">
		</form>
	</body>
</html>