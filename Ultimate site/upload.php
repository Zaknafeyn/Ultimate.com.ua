<?
	include_once $_SERVER['DOCUMENT_ROOT']."/tmpl/init.php";


	$where = array(
		"/upload/",
		"/man/img/",
		"/teams/photo/",
		"/blog/img/",
		"/tourn/img/",
		"/img/tmp/"
	);


	if ($rights['all_rights']) {

		$title="Загрузить файл";
		include $_SERVER['DOCUMENT_ROOT']."/tmpl/header.php";


	    print "<div class=\"padding\">";

		if (isset($_FILES["userfile"])) {
			$upath = $where[digits_only($_POST["where"])];
			for ($i=0;$i<3;$i++) {
		    	if ($_FILES["userfile"]["name"][$i]) {
		    		if ($_POST["fname"][$i]) {
		    			$newname = $_POST["fname"][$i];
		    		} else {
		    			$newname = $_FILES["userfile"]["name"][$i];
		    		}
			    	if (move_uploaded_file($_FILES["userfile"]["tmp_name"][$i],$_SERVER['DOCUMENT_ROOT'].$upath.$newname)) {
	                	chmod($_SERVER['DOCUMENT_ROOT'].$upath.$newname, 0644);
			    		print "<p><h1><a href=\"".$upath.$newname."\">".$upath.$newname."</a> <span style=\"color: blue;\">OK</span></h1>";
			    	} else {
			    		print "<p><h1>".$upath.$newname." <span style=\"color: red;\">fail</span></h1>";
			    	}
		    	}
			}
		}


	    print "</div>";
  
?>


	    <center>
	    	<form action="upload.php" method="post" enctype="multipart/form-data">
			<p>
				<select name="where" style="width: 500px; font-size: 2em; background-color: #F0F0F0; ">
				<?
					for ($i=0;$i<sizeof($where);$i++)
						print "<option value=\"".$i."\">".$where[$i]."</option>";
				?>
				</select>
			</p>
			<br />
			<p><input type="file" style="width: 500px; text-align: center; font-size: 2em;" name="userfile[]" /></p>
			<p><input style="width: 500px; text-align: center; font-size: 2em;" value="" name="fname[]" /></p>
			<br />
			<p><input type="file" style="width: 500px; text-align: center; font-size: 2em;" name="userfile[]" /></p>
			<p><input style="width: 500px; text-align: center; font-size: 2em;" value="" name="fname[]" /></p>
			<br />
			<p><input type="file" style="width: 500px; text-align: center; font-size: 2em;" name="userfile[]" /></p>
			<p><input style="width: 500px; text-align: center; font-size: 2em;" value="" name="fname[]" /></p>
			<br />

			<p><input value="ОК" type="Submit" style="text-align: center; font-size: 2em;" /></p>
			</form>
		</center>


<?
		include $_SERVER['DOCUMENT_ROOT']."/tmpl/footer.php";
	}
?>

