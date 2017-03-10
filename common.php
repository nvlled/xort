<?php
session_start();

// default settings
$settings = [
    "dbcred" => [
        "urn" => "mysql:hostname=localhost;dbname=XXXX",
        "username" => "XXXX",
        "password" => "XXXX",
    ],

    "sitename" => "xort",
    "basepath" => "/xortir/",
];

// override settings in settings.php
@include "settings.php";
define("LINK_COUNTDOWN", 5);
define("DEFAULT_BASEURL", "http://localhost" . settings("basepath"));
define("BASEURL_FILE", "baseurl");

require_once "db.php";
require_once "layout.php";

function siteroot($path="") {
    $base = removeTrailingSlashes(settings("basepath"));
    return "$base/$path";
}

function removeTrailingSlashes($path) { return rtrim($path, "/"); }

// TODO: re-use ID for similar links
function registerLink($link, $username) {
    $db = DB::connect();
    for ($retries = 5; $retries > 0; $retries--) {
        $linkID = generateID();
        $st = $db->prepare("insert into urlmap(id, link, dateCreated, owner) values(?, ?, ?, ?)");
        $st->execute([$linkID, $link, date("Y-m-d H:i:s"), $username]);
        if ($st->errorCode() == 0000) {
            return $linkID;
        }
        echo "<hr>";
        var_dump($st->errorCode());
        echo "<hr>";
        var_dump($st->errorInfo());
        echo "<hr>";
    }
    return NULL;
}

function readBaseURL() {
    $url = @file_get_contents(BASEURL_FILE);
    if (!$url)
        $url = DEFAULT_BASEURL;
    return $url;
}

function validLink($link) {
    return !!filter_var($link, FILTER_VALIDATE_URL);
}

function fetchLink($id) {
    $db = DB::connect();
    $st = $db->prepare("select * from urlmap where BINARY id = ?");
    $st->execute([$id]);
    return htmlspecialchars($st->fetch()["link"]);
}

function fetchLinkID($link) {
    $db = DB::connect();
    $st = $db->prepare("select * from urlmap where link = ?");
    $st->execute([$link]);
    return $st->fetch()["id"];
}

function generateID() {
    $db = DB::connect();
    $st = $db->query("select word from vocab order by rand() limit 1");
    foreach ($st as $row) {
        $word = $row["word"]; 
        $suffix = generateRandomString(2);
        return $word . "-" . $suffix;
    }
    return "";
}

function generateRandomString($length) {
        $alphanum = '0123456789abcdefghijklmnopqrstuvwxyzABCDEFGHIJKLMNOPQRSTUVWXYZ';
        $str = str_repeat($alphanum, ceil($length/strlen($alphanum)));
        return substr(str_shuffle($str), 1,$length);
}

////////////////////////////////////////////

function invalidUsername($username) {
    if (trim($username) == "")
        return true;
    $pattern = "/[^a-zA-Z0-9]+/";
    return preg_match($pattern, $username);
}

function userExists($username) {
    $db = DB::connect();
    $st = $db->prepare("select * from users where username = ?");
    $st->execute([$username]);
    return $st->fetch();
}

function getUser($username, $password) {
    $db = DB::connect();
    $st = $db->prepare("select * from users where username = ? and password = ?");
    $st->execute([$username, $password]);
    return $st->fetch();
}

function registerUser($username, $password) {
    $db = DB::connect();
    $st = $db->prepare("insert into users(username, password) values(?, ?)");
    $st->execute([$username, $password]);
    return $st->errorCode();
}

function getUserLinks($username) {
    $db = DB::connect();
    $st = $db->prepare("select * from urlmap where owner = ?");
    $st->execute([$username]);
    return $st->fetchAll();
}

function settings($name) {
    global $settings;
    $val = @$settings[$name];
    if (!$val)
        $val = "*NOT SET: $name*";
    return $val;
}

function startSession($username) {
    $db = DB::connect();
    $st = $db->prepare("update urlmap set owner = ? where owner = ?");
    $sessionId = "_" . session_id();
    $st->execute([$username, $sessionId]);
    $_SESSION["username"] = $username;
}

function ownsLink($linkId, $username) {
    $db = DB::connect();
    $st = $db->prepare("select * from urlmap where id = ? and owner = ?");
    $st->execute([$linkId, $username]);
    return !! $st->fetch();
}

function incrementVisitCount($linkId) {
    $db = DB::connect();
    $st = $db->prepare("update urlmap set visits=visits+1 where id = ?");
    $st->execute([$linkId]);
}

function deleteLink($linkId) {
    $db = DB::connect();
    $st = $db->prepare("delete from urlmap where id = ?");
    $st->execute([$linkId]);
}

?>
