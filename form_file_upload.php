<?php 
$u = $_SESSION['usr'];
showUploadForm();
if(isset($_POST['submit']))
{
	
	//mkdir('www/test',0777,true);
	
	$allowedExts = array("jpg", "jpeg", "gif", "png");
	$extension = end(explode(".", $_FILES["file"]["name"]));
	if ((($_FILES["file"]["type"] == "image/gif")
	|| ($_FILES["file"]["type"] == "image/jpeg")
	|| ($_FILES["file"]["type"] == "image/png")
	|| ($_FILES["file"]["type"] == "image/pjpeg"))
	&& ($_FILES["file"]["size"] < 20000)
	&& in_array($extension, $allowedExts))
	{
		if ($_FILES["file"]["error"] > 0)
		{
			echo "Error: " . $_FILES["file"]["error"] . "<br>";
		}
		else
		{
			echo "Upload: " . $_FILES["file"]["name"] . "<br>";
			echo "Type: " . $_FILES["file"]["type"] . "<br>";
			echo "Size: " . ($_FILES["file"]["size"] / 1024) . " kB<br>";
			
			$dirToStoreIn = "/var/upload/".$u['username']."/";
			
			$dirExists = is_dir($dirToStoreIn);
			//var_dump($dirExists);
			
			//echo dirname( __FILE__ );
			
			if(!$dirExists)
			{
			   	if(!mkdir($dirToStoreIn))
			   	{
			   		echo 'Error creating directory';
			   		die();
			   	}
			   	//var_dump($success);
			}
			
			//echo "Stored in: " . $_FILES["file"]["tmp_name"];
	
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
				   	echo 'success!';
				   }
				   else
				   {
				   	echo 'failed!';
				   }
				   
				    //$dirToStoreIn = "upload/".$u['username'];
				    echo "Stored in: " . $dirToStoreIn;
		      }
		}
	}
	else
	{
		echo "Invalid file";
	}
}

function showUploadForm()
{
	echo '<form action="'; echo $PHP_SELF; echo '" method="post"
	enctype="multipart/form-data">
	<label for="file">Filename:</label><br>
	<input type="file" name="file" id="file"><br>
	<input type="submit" name="submit" value="Submit">
	</form>';
}

?>
