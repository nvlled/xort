<?php
require_once "common.php";

$path = removeTrailingSlashes($_SERVER["REQUEST_URI"]);
$pattern = "/^".preg_quote(settings("basepath"), "/")."([a-zA-Z0-9\-]+)$/";
if (preg_match($pattern, $path, $matches)) {
    $path = "link.php?id=".$matches[1];

    $_REQUEST["id"] = $matches[1];
    include "link.php";
} else if ($path."/" == settings("basepath")) {
    header("Connection: close");
    include "index.php";
} else {
    $_REQUEST["msg"] = "Page not found";
    include "error.php";
}

?>
