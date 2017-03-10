<?php
require_once "common.php";

//DB::drop();

echo "initializing database<br>";
DB::init();

echo "populating vocab<br>";
DB::initVocab();

?>
