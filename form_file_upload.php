<?php 
$u = $_SESSION['usr'];
showUploadForm();

if(isset($_POST['submit']))
{
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
			echo "Stored in: " . $_FILES["file"]["tmp_name"];

			if (file_exists("upload/" . $_FILES["file"]["name"]))
	      	{
	      		echo $_FILES["file"]["name"] . " already exists. ";
	     	}
	    	else
	      	{
			    if(!is_dir("upload/".$u['username']))
			    {
			    	mkdir("upload/".$u['username']);
			    }

			    move_uploaded_file($_FILES["file"]["tmp_name"],
			    "upload/". $u['username'] . $_FILES["file"]["name"]);

			    //$dirToStoreIn = "upload/".$u['username'];

			    echo "Stored in: " . "upload/". $u['username'] . $_FILES["file"]["name"];
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