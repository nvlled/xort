<?php
require_once "common.php";

layout(function() {
    $linkID = @$_REQUEST["id"];
    $link = fetchLink($linkID);

    if ($link == "") {
        echo "link not found";
        return;
    }

    $username = @$_SESSION["username"];
    if (!$username) {
        $username = "_".session_id();
    }

    if ( ! ownsLink($linkID, $username))
        incrementVisitCount($linkID);
?>
    <p>
        <img class="ads" src="resources/biggie.png"><br>
        Redirecting to 
        <a class="link" href="<?=$link?>"><?=$link?></a>
        [<span class="count"><?=LINK_COUNTDOWN?></span>]
        <button class="cancel">cancel</button>
        <br>
    </p>
    <style>
        .ads {
            width: 80px;
        }
    </style>
    <script>
    window.onload = function() {
        var countDown = document.querySelector(".count");
        var n = parseInt(countDown.textContent);
        var link = document.querySelector(".link").href;
        var cancelBtn = document.querySelector(".cancel");
        var timerId = setInterval(function() {
            console.log(n);
            if (n == 0) {
                window.location = link;
                clearInterval(timerId);
            } else {
                n--;
            }
            countDown.textContent = n;
        }, 1000);

        cancelBtn.onclick = function() {
            countDown.textContent = "";
            clearInterval(timerId);
        }
    }
    </script>
<?php

});

?>
