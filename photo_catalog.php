<!DOCTYPE html>
<html lang="en">
<head>
	<meta charset="UTF-8">
	<meta name="viewport" content="width=device-width, initial-scale=1, shrink-to-fit=no">
	<title>Document</title>
	<link rel="stylesheet" href="css\stylle.css">
	<link rel="stylesheet" href="css\bootstrap.min.css">
</head>
<body class="bg-info">
	<header class="container bg-dark mt-2" style="border-radius: 20px 20px 20px 20px;">
		<div class="row">
			<div class="d-flex justify-content-center my-3 col-md-1" >
			</div>
			<div class="d-flex justify-content-center my-3 col-md-5 col-sm-12" >	
					<form method="post" action="photo_catalog.php" enctype="multipart/form-data" >
						<h3 class="text-muted">Добавление</h3>
						<input type="file" name="myfile" id="myfile" style="width: 150px;font-size: 14pt;"><br>
						<input type="submit"class="mt-1 text-center"name="submit_image"value="Загрузить фото"style="color:#212529;border:none;width:250px;font-size:16pt;border-radius:4px;background-color:#e49b0f;">
					</form>
<?php
$link = mysqli_connect ('localhost','admin','admin','project');
$result = mysqli_query ($link, "SELECT * FROM photos");
$path_to_photo = "/media/";
$fotos_dir = "/media/";
$foto_name = time().basename($_FILES['myfile']['name']);
if(isset($_FILES["myfile"])){
	$myfile = $_FILES["myfile"]["tmp_name"];
	$myfile_name= $_FILES["myfile"]["name"];
	$myfile_size = $_FILES["myfile"]["size"];
	$myfile_type = $_FILES["myfile"]["type"];
	$error_flag = $_FILES["myfile"]["error"];
	if($error_flag == 0){
		$DOCUMENT_ROOT = $_SERVER['DOCMENT_ROOT'];
		$upfile = getcwd()."/media/".time().basename($_FILES["myfile"]["name"]);
		if ($_FILES['myfile']['tmp_name']){
			if (!move_uploaded_file($_FILES['myfile']['tmp_name'], $upfile)){
				echo "$error_by_file";
				exit;
			}
		}else{
			echo 'Проблема: возможна атака через загрузку файла. ';
			echo $_FILES['myfile']['name'];
			exit;
		}
		$q = "INSERT INTO photos(photo_name,photo_path) VALUES('$foto_name','$foto_name')";
		$query = mysqli_query($link,$q);
		if ($query == 'true') {
			echo "<p>Картинка успешно добавлена на сервер!</p>";
			echo "<script> document.location.href=\"http://photo_catalog/photo_catalog.php\";</script>";
		}else{
			echo "$error_by_mysql";
		}
	}elseif ($myfile_size == 0) {
		echo "<br><label class='label'>Картинка не выбрана!<br><br>Вернитесь и выберите картинку!</label><br><br>";
	}
}
?>
			</div>
			<div class="d-flex justify-content-center my-3 col-md-5 col-sm-12" >
				<form action="photo_catalog.php" method="post">
					<h3 class="text-muted">Удаление</h3>
					<input type="text"name="delid" placeholder="Введите id фото" style="color:#212529;width:150px;font-size:14pt;"><br>
					<input type="submit" [name="go"] class="mt-1" value="Удалить фото"  style="color: #212529;border:none;width:250px;font-size:16pt;border-radius:4px;background-color:#e49b0f;">
				</form>
<?php
if(isset($_POST['delid']))
{   
   	$link = mysqli_connect ('localhost','admin','admin','project') or die("Ошибка ".mysqli_error($link)); 
    $delid = mysqli_real_escape_string($link, $_POST['delid']);
    $queryimgdel = "SELECT * FROM photos WHERE id_photo = '$delid'";
    $resultimgdel = mysqli_query($link,$queryimgdel);
    $arr = mysqli_fetch_assoc($resultimgdel);
    $pathtodel = ("media\\".$arr[photo_path]);
   	unlink($pathtodel);
    $querydel ="DELETE FROM photos WHERE id_photo = '$delid'";
    echo "<script> document.location.href=\"http://photo_catalog/photo_catalog.php\";</script>";
    $resultdel = mysqli_query($link, $querydel) or die("Ошибка " . mysqli_error($link)); 
}
?>
			</div>
			<div class="d-flex justify-content-center col-md-1 my-3" >
			</div>
		</div>
	</header>
	<div class="container masonry bg-dark pt-3 mt-2"  style="border-radius: 20px 20px 10px 10px;">
<?php
$var = mysqli_fetch_assoc ($result);
$res = array ();
for ($i = 0; $var != false; $i++) {
	$res[$i] = $var;
	$var = mysqli_fetch_assoc ($result);
}
mysqli_close ($link);
$varnum = mysqli_num_rows ($result);
for ($i = 0; $i < $varnum; $i++) {
echo "<div class=\"item\"><img class=\"img-fluid\" name=\"".$res[$i][photo_path]."\"alt=\"background-image\" src=\""."/media/".$res[$i][photo_path]."\" ><h3>&nbsp;&nbsp;".$res[$i][id_photo]."</h3></div>";
}
?>
</div>
<script type="text/javascript" src="js\bootstrap.min.js"></script>
</body>
</html>