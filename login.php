<?php
require_once "common.php";

layout(function() {
    $username = @trim($_REQUEST["username"]);
    $password    = @trim($_REQUEST["password"]);
    $error = "";

    if ( ! @$_REQUEST["action"])
        goto render;


    if (! getUser($username, $password)) {
        $error = "invalid username or password";
    }

    if (!$error) {
        startSession($username);
        header("location: " . siteroot());
    }

render:
?>

<form method="POST">
    <p><label for="username">username:</label>
        <input type="text" name="username" id="username" value="<?=$username?>">
    <p><label for="password">password:</label>
       <input type="text" name="password" id="password">
    <p>
        <input type="submit" name="action" value="login">
        <span class="error"><?=$error?></span>
        <a href="register.php">| register</a>
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
