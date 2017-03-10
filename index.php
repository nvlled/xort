<?php
require_once "common.php";

layout(function() {
    //$linkID = @$_REQUEST["linkID"];
    $action = @$_REQUEST["action"];
    $link = trim(@$_REQUEST["link"]);
    $error = "";

    if ($action == "submit") {
        if ($link == "") {
            $error = "URL is required.";
        } else if (! validLink($link)) {
            $error = "URL is not valid. It must have the form http://example.com";
        }
        if ($error)
            goto render;

        $username = @$_SESSION["username"];
        if (!$username)
            $username = "_". session_id();

        $id = registerLink($link, $username);

        if ($id == "") {
            $error = "Failed to create link id. Please try again.";
        } else {
            header("location: list.php");
        }
    }

    render:
?>

<form method="POST">
    <h2>Create a short URL</h2>
    <br>

    <label>
        URL: 
    </label>
    <input name="link" style="width: 70%" value="<?=$link?>" /><br>

    <!--
    <label><input type="checkbox" name="expire"/>expire?</label>
    <input name="numdays" type="number" min="1" /> days
    <br>
    -->

    <label></label><input type="submit" name="action" value="submit"/>
    <span name="genid" class="error"><?=$error?></span>
    <style>
    label { 
        text-align: right;
        width: 100px; 
        display: inline-block;
    }
    </style>
</form>
<?php
});
?>
