<?php session_start();
    showForm();
    $uName = $_POST["username"];
    $pass = $_POST["password"];
    $eKey = 'TOPSECRET';
    
    include 'connect.php';
	include 'functions_user.php';
	include 'functions_input.php';
    
    if (isset($_POST["submit"]))
    {
        if (inputIsComplete())
        {
            if (isValidInput($uName))
            {
        		$currentUser = getUserData($con, $uName);
        		if ($currentUser == false)
        		{
        			echo 'No such user!';
        		}
        		
        		if (checkPass($con, $pass, $currentUser))
        		{
        			/*
            		* Log the user in for the current session
            		*/
            		$_SESSION['usr'] = $currentUser;
            		//session_write_close()
            		echo 'Logged in!';
            		//var_dump($currentUser);
            		//var_dump($_SESSION);
            		header('Location: index.php');
        		}
            }
            else
            {
                echo 'Invalid username input';
            }
        }
    }
	
    /**
	*	This function declares an empty array then adds each empty _POST value
	*	to the array. The function then checks to see if the array is empty at
	*	the end, returning true if it is, false if has any values in it.
	*/
    /*function inputIsComplete()
    {  
        
        foreach ($_POST as $value)
        {
            if (empty($value))
            { 
                array_push($emptyFields, $value);
            }
        }
        if (empty($emptyFields))
        {
            return true;
        }
        else
        {
            return false;
        }
    }*/
    
	/**
	*	This function is responsible for outputting the login form to the page
	*/
    function showForm() 
    {
        echo '<div id="loginform"><form method="post" action="'; 
            echo $PHP_SELF; 
            echo '">
            <label for="input_username">Username:</label><br>
            <input type="text" name="username" id="input_username" value="';
            echo $_POST["dUsername"];
            echo '"><br>
            <label for="input_password">Password:</label><br>
            <input type="password" name="password" id="input_password" value=""><br>
            <input type="submit" name="submit" value="Login">
            </form></div>';
    }
    
	/**
	*	Uses a regex to strip any punctuation from the users input to prevent SQL injection
	*	@param string $unameInput This is a string containing the input from the username field
	*/
    function isValidInput($unameInput)
    {
        $unameValid = preg_replace("/[^a-zA-Z 0-9]+/", " ", $unameInput);
        return ($unameValid == $unameInput);
    }

?> 
