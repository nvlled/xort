<?php
require_once "common.php";

layout(function() {
    $id = @$_REQUEST["id"];
    if ($id == "") {
        header("location: index.php");
        return;
    }
    $link = fetchLink($id);
    $shortURL = readBaseURL() . "$id";
?>
    <h2>Your link</h2>
    <p><a href="<?=$shortURL?>"><?=$shortURL?></a> <button>copy to clipboard</button></p>
    Redirects to <a href="<?=$link?>"><?=$link?></a>
<?php
});

?>
