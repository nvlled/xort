<?php
require_once "common.php";

layout(function() {
    $links = [];
    $username = @$_SESSION["username"];
    if (!$username) {
        $username = "_".session_id();
    }

    $linkID = @$_REQUEST["link-id"];

    $action = @$_REQUEST["action"];
    if ($action == "delete" && ownsLink($linkID, $username)) {
        deleteLink($linkID);
    } else if ($action == "random ID") {
        $link = fetchLink($linkID);
        if ($link) {
            deleteLink($linkID);
            registerLink($link, $username);
        }
    }
?>

    <h2>Created Links</h2>
    <?php $links = getUserLinks($username); ?>
    <?php if ($links) { ?>
    <table class="links">
    <thead>
        <tr>
            <th>ID
            <th>Link
            <th>Visits
            <th>
        </tr>
    </thead>
    <?php foreach ($links as $link) { 
        $id = $link["id"];
        $linkURL = htmlspecialchars($link["link"]);
    ?>
        <tr>
        <td><a href="<?=$id?>"><?=$id?></a>
        <td><a href="<?=$linkURL?>"><?=$linkURL?></a>
        <td><?=$link["visits"]?>
        <td><form method="POST">
        <?php if ($link["visits"] == 0) { ?>
            <input type="hidden" name="link-id" value="<?=$link["id"]?>">
            <input type="submit" name="action" value="random ID">
            <input type="submit" name="action" value="delete">
        <?php } else {?>
            --
        <?php } ?>
        </form>
        </tr>
    <?php } ?>
    </table>
    <p>
        Note: You can only manage these links with the
        current browser. You won't be able to change/edit
        these links once your HTTP session expires.
        To keep the links, <a href="register.php">register</a>
        or <a href="login.php">login</a>
    <p>
        Double Note: These links won't expire unless: the domain name
        expires, the server goes down, or I dropped the database
        by accident/on purpose, whichever comes first.
    </p>
    <?php } else { ?>
        <p>(You have not created any links yet.)<p>
    <?php } ?>

<?php
});
?>
