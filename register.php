<?php
require_once "common.php";

layout(function() {
    $username = @trim($_REQUEST["username"]);
    $pass1    = @trim($_REQUEST["pass1"]);
    $pass2    = @trim($_REQUEST["pass2"]);
    $error = "";

    if ( ! @$_REQUEST["action"])
        goto render;

    if ($username == "") {
        $error = "username is required";
    } else if (invalidUsername($username)) {
        $error = "invalid username, must contain only alphanumeric characters";
    } else if (!! userExists($username)) {
        $error = "username is not available";
    } else if ($pass1 == "") {
        $error = "password is required";
    } else if ($pass1 != $pass2) {
        $error = "passwords do not match";
    }

    if (!$error) {
        $errCode = registerUser($username, $pass1);
        if ($errCode != 0000) {
            $error = "registration failed, please try again";
        } else {
            $_SESSION["username"] = $username;
            header("location: " . siteroot());
            startSession($username);
        }
    }

render:
?>

<form method="POST">
    <p><label for="username">username:</label>
        <input type="text" name="username" id="username" value="<?=$username?>">
    <p><label for="pass1">password:</label>
       <input type="text" name="pass1" id="pass1">
    <p><label for="pass2">confirm password:</label>
       <input type="text" name="pass2" id="pass2">
    <p>
        <input type="submit" name="action" value="register">
        <span class="error"><?=$error?></span>
        <a href="login.php">| login</a>
    </p>
    <style>
    label { 
        display: inline-block;
        width: 100px; 
    }
    </style>
</form>

<?php
});

?>
