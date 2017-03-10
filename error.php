<?php
require_once "common.php";

layout(function() {
    $msg = @$_REQUEST["msg"];
    if ($msg == "") {
        $msg = "an error occured";
    }
    echo "<h3>$msg</h3>";
});

?>
