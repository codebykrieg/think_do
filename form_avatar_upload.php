<?php 
$u = $_SESSION['usr'];

if(isset($_POST['submit']))
{
	
	//mkdir('www/test',0777,true);
	
	$allowedExts = array("jpg", "jpeg", "gif", "png");
	$extension = end(explode(".", $_FILES["file"]["name"]));
	if ((($_FILES["file"]["type"] == "image/gif")
	|| ($_FILES["file"]["type"] == "image/jpeg")
	|| ($_FILES["file"]["type"] == "image/png")
	|| ($_FILES["file"]["type"] == "image/pjpeg"))
	&& ($_FILES["file"]["size"] < 2000000)
	&& in_array($extension, $allowedExts))
	{
		if ($_FILES["file"]["error"] > 0)
		{
			echo "Error: " . $_FILES["file"]["error"] . "<br>";
		}
		else
		{
			//echo "Upload: " . $_FILES["file"]["name"] . "<br>";
			//echo "Type: " . $_FILES["file"]["type"] . "<br>";
			//echo "Size: " . ($_FILES["file"]["size"] / 1024) . " kB<br>";
			
			$dirToStoreIn = "/var/www/upload/".$u['username']."/";
			
			$dirExists = is_dir($dirToStoreIn);
			//var_dump($dirExists);
			
			//echo dirname( __FILE__ );
			
			if(!$dirExists)
			{
			   	if(!mkdir($dirToStoreIn))
			   	{
			   		echo 'Error creating directory!';
			   		die();
			   	}
			   	//var_dump($success);
			}
			
			//echo "Stored in: " . $_FILES["file"]["tmp_name"];

			//$_FILES["file"]["name"] = "avatar.jpg";
	
			if (file_exists($dirToStoreIn . $_FILES["file"]["name"]))
	      	{
	      		echo $_FILES["file"]["name"] . " already exists. ";
	     	}
	    	else
	      	{
	      		$dirToStoreIn = $dirToStoreIn . basename($_FILES["file"]["name"]);
			    if (move_uploaded_file($_FILES["file"]["tmp_name"],
			    $dirToStoreIn))
			    {
			   		$sqlDir = "/upload/".$u['username']."/".basename($_FILES["file"]["name"]);
			   		$sql = "UPDATE user SET avatarLocation = '".$sqlDir."' WHERE userID =".$u['userID'];
			   		//var_dump($sql);
			   		mysql_query($sql, $con) or die(mysql_error());
			   		echo 'Upload successful!';
			   		//$_SESSION['usr'] = getUserData($con, $u['username']);
			    }
			    else
			    {
			   		echo 'Error moving the file to the correct directory!';
			   		die();
			    }
			   
			    //$dirToStoreIn = "upload/".$u['username'];
			    //echo "Stored in: " . $dirToStoreIn;
		      }
		}
	}
	else
	{
		echo "<h2>Invalid file</h2>";
	}
}

showUploadForm();

function showUploadForm()
{
	echo '<form action="#tabs-2" method="post"
	enctype="multipart/form-data">
	<label for="file">Upload an avatar!: (must be less than 2MB)</label><br>
	<input type="file" name="file" class="normalButton" id="file"><br>
	<input type="submit" name="submit" class="normalButton" value="Submit">
	</form>';
}

?>
