<?php
/**
* @author: Nathan Emery
*/
    include 'header.php';
    include 'connect.php';
    include 'functions_idea.php';
    include 'functions_comment.php';
    include 'functions_task.php';
    include 'functions_input.php';
    include 'functions_gatherings.php';
    include 'functions_user.php';
    $idea = getIdea();
    $tasks = getIdeaTasks($idea);
    $gatherings = getIdeaGatherings($idea);
    if(isset($_POST['submitComment']))
    {
        postComment($parent);
    }
    elseif(isset($_POST['upvote']))
    {
        incrementIdeaUpvotes($idea,$_SESSION['usr'],$con);
    }
    elseif(isset($_POST['upVoted']))
    {
        decrementIdeaUpvotes($idea,$_SESSION['usr'],$con);
    }
    elseif(isset($_POST['joinTeam']))
    {
        joinIdeaTeam($idea, $_SESSION['usr'], $con);
        $idea = getIdea();
    }
    

    echo '<script>
	$(function(){
		$( "#tabs" ).tabs();
	});
	</script>
    <div class="clear"></div>
        <div id="post-container">
            <div class="post">
                <div class="sidebar">';
                    getIdeaAvatar($idea);
                    if(isset($_SESSION['usr'])){
                        $_SESSION['usr'] = getUserData($con, $_SESSION['usr']['username']);
                        if(userHasVoted($idea, $_SESSION['usr'])==false){
                            echo '<div style="float:left; width:100%;"><form method="post" action="'; 
                            echo $PHP_SELF; 
                            echo '">
                            <input type="submit" name="upvote" value="Upvote">
                            </form></div>';
                        }
                        else{
                            echo '<div style="float:left; width:100%;"><form method="post" action="'; 
                            echo $PHP_SELF; 
                            echo '">
                            <input type="submit" name="upVoted" value="Undo" id="upVoted">
                            </form></div>';
                        }
                        $ideaMember = userMemberStatus($idea, $_SESSION['usr'], $con);
                        if($ideaMember == 0){
                            echo '<form method="post" action="'; 
                            echo $PHP_SELF; 
                            echo '">
                            <input type="submit" name="joinTeam" value="Show Interest">
                            </form>';
                        }
                        elseif($ideaMember == 1){
                            echo "<p class='helperMsg'>Interested</p>";
                        }
                        else{
                            echo "<p class='modMsg'>You're an idea mod</p>";
                        }
                    }
                    else{
                        echo "<div class=smallerForm>You must first <a href='login.php'>login</a> or register before you can show your interest and vote on this idea.
                                Don't worry, it will take you less than a minute!
                             </div>";
                    }
                    showSidebarContent($idea);
                echo'</div>
                <div class="mainRight">';
                    echo '
                    <div id="tabs">
                        <ul>
                            <li><a href="#tabs-1">Description</a></li>
                            <li><a href="#tabs-2">Todo List</a></li>
                            <li><a href="#tabs-3">Gatherings</a></li>
                        </ul>
                        <div id="tabs-1">
                    ';
                    
                    showIdea($idea);
                    echo '<br><hr>';
                    getComments($con);
                    if(isset($_SESSION['usr']))
                    {
                        showCommentForm();
                    }
                    else
                    {
                        echo "<h2>Oops!</h2></br>
                            You must first <a href='login.php'>login</a> or <a href='register.php'>register</a> before you can post a comment!
                           <br>
                           But don't worry, it will take you less than a minute!
                           <br>";
                    }
                    echo'
                        </div>
                        <div id="tabs-2">
                    ';
                        //$tasks = getIdeaTasks($idea);
                        if(currentUserIsIdeaMod($idea))
                        {
                            include 'task_form.php';
                        }
                        displayTasks($tasks);
                    echo '
                        </div>
                        <div id="tabs-3">
                    ';
                        
                        if(currentUserIsIdeaMod($idea))
                        {
                            include 'form_gathering.php';
                        }
                        displayGatherings($gatherings);

                    echo '
                        </div>
                    </div>            
                </div>
            </div>
        </div>'; 

include 'footer.php'; 

function showCommentForm()
{
    echo '<form method="post" action="'; echo $PHP_SELF; echo '">
    <label for="comment"><h2>Leave a comment:</h2></label><br>
    <input type="text" name="content" id="contentInput" width="60%" value="">
    <br><input type="submit" name="submitComment" value="Submit"></form>';
}

function postComment($p)
{
    if ($p == null)
    {
        $parentID = 0;
    }
    else
    {
        $parentID = $p['commentID'];
    }
    $u = $_SESSION['usr'];
    $n = $u['username'];
    $now = date("Y-m-d H:i:s");
    $content = mysql_real_escape_string($_POST['content']);
    $sql = "INSERT INTO comments (ideaID, parentID, username, content, datePosted, upVotes) VALUES (" . $_GET['pid'] . ", ".$parentID.", '" .$n. "', '" .$content. "','" .$now. "', 0)";
    mysql_query($sql);
}

function currentUserIsIdeaMod($idea)
{
    $u = $_SESSION['usr'];
    $mods = $idea['moderators'];
    $mods = explode(',', $mods);
    foreach ($mods as $uID)
    {
        if ($uID == $u['userID'])
        { 
            return true;
        }
    }
    return false;
}

echo'</div>';
?>
