<?php

function layoutHeader() {
?>
<html>
<head>
   <link rel="stylesheet" href="resources/site.css"/> 
   <title><?=settings("sitename")?></title>
</head>
<body>
<div class="wrapper">
<h1 class="site-name"><a href="<?=settings("basepath")?>"><?=settings("sitename")?></a></h1>
<span class="site-elm">| a crappy URL shortener</span>

<?php
$username = @$_SESSION["username"];
if ($username) { ?>
    <span class="site-elm">| logged in as <a href="list.php"><?=$username?></a></span>
    <span class="site-elm">| <a href="logout.php">logout</a></span>
<?php } else { ?>
    <span class="site-elm">| session ID: <a href="list.php"><?=session_id()?></a></span>
    <span class="site-elm">| <a href="login.php">login</a></span>
<?php } ?>

<br style="clear: left">
<hr>
<?php
}

function layout($renderer) {
    layoutHeader();
    $renderer();
    layoutFooter();
}

function layoutFooter() {
?>
</div><!--wrapper!-->
</body>
</html>
<?php
}
?>

