<?php 
    showForm();
    $uName = $_POST["username"];
    $pass = $_POST["password"];
    $eKey = 'TOPSECRET';
    
    include 'connect.php';
    
    if (isset($_POST["submit"]))
    {
        if (inputIsComplete())
        {
            if (isValidInput($uName))
            {
                if (validateLogin($con, $uName, $pass, $eKey))
                {
                    /*
                    * Log the user in for the current session
                    */
                    echo 'Logged in!';
                }
                else
                {
                    echo 'Your login could not be validated for some reason';
                }
            }
            else
            {
                echo 'Invalid username input';
            }
        }
        else
        {   
            echo 'All fields must be filled in!';
        }
    }
    
    function inputIsComplete()
    {  
        $emptyFields = array();
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
    }
    
     function showForm() 
    {
        echo '<form method="post" action="'; 
            echo $PHP_SELF; 
            echo '">
            <label for="input_username">Username:</label><br>
            <input type="text" name="username" id="input_username" value="';
            echo $_POST["dUsername"];
            echo '"><br>
            <label for="input_password">Password:</label><br>
            <input type="password" name="password" id="input_password" value=""><br>
            <input type="submit" name="submit" value="Login">
            </form>';
    }
    
    function isValidInput($unameInput)
    {
        $unameValid = preg_replace("/[^a-zA-Z 0-9]+/", " ", $unameInput);
        return ($unameValid == $unameInput);
    }
    
    
    function validateLogin($c, $u, $p, $k)
    {
        //Query database to check if passwords are equal
        $sql = "SELECT password FROM user WHERE username = '" . $u . "'";
        $resultRow = mysql_query($sql, $c);
        $user = mysql_fetch_assoc($resultRow);
        $sP = $user['password'];
        
        //Decrypt the database password and check if it is equal to the one inputted
        function decrypt($str, $key)
        {   
            	$str = mcrypt_decrypt(MCRYPT_DES, $key, $str, MCRYPT_MODE_ECB);
    		$block = mcrypt_get_block_size('des', 'ecb');
    		$pad = ord($str[($len = strlen($str)) - 1]);
    		return substr($str, 0, strlen($str) - $pad);
	}
	
        $decryptedPass = decrypt($sP, $k);
        
        echo $sql;
        echo '<br>';
        echo $sP;
        echo '<br>';
        echo $decryptedPass;
        echo '<br>';
        return ($decryptedPass == $p);
    }
?> 
