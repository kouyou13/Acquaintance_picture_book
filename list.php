<!-- 図鑑画面（ログイン、ログアップ後） -->
<?php
	include 'db_config.php';
	if(isset($_GET['userid']))
		$userid = $_GET['userid'];
	else if(isset($_POST['userid']))
		$userid = $_POST['userid'];
	$characters = null;
	
	try{
		// connect＿データベースに接続
		$db = new PDO(PDO_DSN, DB_USERNAME, DB_PASSWORD);
		$db->setAttribute(PDO::ATTR_ERRMODE, PDO::ERRMODE_EXCEPTION);

		$tmp = $db->query("SELECT * FROM characters WHERE userid = $userid ORDER BY id DESC");
		$characters = $tmp->fetchAll(PDO::FETCH_ASSOC);
	}
	catch (PDOException $e) {
		$ErrorMessage = 'データベースエラー';
	}
	
	if(isset($_POST['list_search'])){
		if(!empty($_POST['search'])){
			try{
				$search = $_POST['search'];
				switch($_POST['search_type']){
					case 'name':
						$tmp = $db->query("SELECT * FROM characters WHERE userid = $userid AND name LIKE '%{$search}%' OR furigana LIKE '%{$search}%' ORDER BY id DESC");
						break;
					case 'age':
						$tmp = $db->query("SELECT * FROM characters WHERE userid = $userid AND age LIKE '%{$search}%' ORDER BY id DESC");
						break;
					case 'sex':
						$tmp = $db->query("SELECT * FROM characters WHERE userid = $userid AND sex LIKE '%{$search}%' ORDER BY id DESC");
						break;
					case 'job':
						$tmp = $db->query("SELECT * FROM characters WHERE userid = $userid AND job LIKE '%{$search}%' ORDER BY id DESC");
						break;
					case 'remarks':
						$tmp = $db->query("SELECT * FROM characters WHERE userid = $userid AND remarks LIKE '%{$search}%' ORDER BY id DESC");
						break;
				}
				$characters = $tmp->fetchAll(PDO::FETCH_ASSOC);
			}
			catch (PDOException $e) {
				$ErrorMessage = 'データベースエラー';
			}
		}
	}
?>
<!DOCTYPE HTML>
<html>
	<head>
		<meta charset="utf-8">
		<title>知り合い図鑑</title>
		<!--CSS-->
		<link rel="stylesheet" href="css/list.css?<?php echo date('Ymd-His'); ?>">
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
		<div class="modal-wrapper">
          <img src="" alt="" class="modal-image">
     	</div>
		
		<h2>一覧画面</h2>
		 
		<div class='fixed_bottom'> <!-- 画面下に固定-->
			<form action='record.php' method='POST'>
				<input type="hidden" name="userid" value="<?php echo $userid; ?>">
				<input type="submit" name="record_bottom" value="追加" class='left_button'>
			</form>
			<form action='review.php' method='POST'>
				<input type="hidden" name="userid" value="<?php echo $userid; ?>">
				<input type="hidden" name="quenstion_num" value=0>
				<input type="submit" name="review_bottom" value="復習" class='right_button'>
			</form>
		</div>
		
		<div>
			<form action='list.php' method='POST'>
				<select name="search_type" >
					<option>調べる条件</option>
					<?php
						if($_POST['search_type'] == 'name')
							echo "<option value='name' selected>名前</option>";
						else
							echo "<option value='name'>名前</option>";
							
						if($_POST['search_type'] == 'sex')
							echo "<option value='sex' selected>性別</option>";
						else
							echo "<option value='sex'>性別</option>";
						
						if($_POST['search_type'] == 'age')
							echo "<option value='age' selected>年齢</option>";
						else
							echo "<option value='age'>年齢</option>";
						
						if($_POST['search_type'] == 'job')
							echo "<option value='job' selected>仕事</option>";
						else
							echo "<option value='job'>仕事</option>";
						
						if($_POST['search_type'] == 'remarks')
							echo "<option value='remarks' selected>備考</option>";
						else
							echo "<option value='remarks'>備考</option>";
					?>
				</select>
				<?php
					if(empty($_POST['search'])){
						echo "<input id='search' type='text' name='search'>";
					}
					else if(!empty($_POST['search'])){
						$search = $_POST['search'];
						echo "<input id='search' type='text' name='search' value=$search>";
					}
				?>
				<input type='hidden' name='userid' value=<?php echo $userid;?>>
				<input id='reseach_submit' type='submit' name='list_search' value='検索'>
			</form>
		</div>
		
		<div>
			<?php
				if($characters != null){
					foreach ($characters as $c){
						$chara_name = $c['name']; //名前
						$chara_name_furigana = $c['furigana']; //フリガナ
						// $chara_base64Text = $c['photo']; //写真のbase64
						$file_link = $c['photo'];
						$chara_sex = $c['sex']; //性別
						$chara_age = $c['age']; //年齢
						$chara_job = $c['job']; //仕事
						$chara_remarks = $c['remarks']; //備考
						
						if($chara_age != -1){
						   echo "<div id=box>
									<img class='image' id=image src='{$file_link}' data-src='{$file_link}' width='300px' height='300'>
										<div id=text>
											<div id=inner>
												<a>$chara_name_furigana</a><br>
											    <h3>$chara_name</h3>
												<p>性別：$chara_sex</p>
												<p>年齢：{$chara_age}歳</p>
												<p>職業：$chara_job</p>
												<p>備考：$chara_remarks</p>
											</div>
										</div>
							     </div>";
						}
						else if($chara_age == -1){
						   echo "<div id=box>
									<img class='image' id=image src='{$file_link}' data-src='{$file_link}' width='300px' height='300'>
										<div id=text>
											<div id=inner>
												<a>$chara_name_furigana</a><br>
											    <h3>$chara_name</h3>
												<p>性別：$chara_sex</p>
												<p>年齢：不詳</p>
												<p>職業：$chara_job</p>
												<p>備考：$chara_remarks</p>
											</div>
										</div>
							     </div>";
						}
					}	
				}
			?>
		</div>
		
		<div style='height: 15vw;'/>
		<script src="list.js"></script>
	</body>
</html>