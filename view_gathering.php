<?php

include 'header.php';
include 'functions_gatherings.php';
include 'functions_user.php';


if(array_key_exists("pid", $_GET))
{
	$gathID = $_GET["pid"];
}
/*else
{
	header("Location: error_page.php");
}*/


if(isset($_POST['attendGath']))
{
	markAsAttending($gathID);
}
elseif(isset($_POST['cancelAttend']))
{
	markAsNotAttending($gathID);
}

?>

<div class="clear"></div>
    <div id="post-container">
    	<div class="sidebar">
    		<?php
    		
    		var_dump($gathID);
    		showGathSidebarContent();

    		?>
    	</div>
		<div class="mainRight">
			<?php
			/*
			 *	We don't even really need to run an SQL query here if we use some JQuery
			 *	to have a drop down when the task is clicked
			 */
			showGathering($gathID);	
				
			?>
        </div>
    </div>
</div>
<?php

include 'footer.php';

?>