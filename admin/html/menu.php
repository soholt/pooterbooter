<hr />
    | <a href="/index.php">pb</a> |
    | <a href="/di.php">di</a> |
    | <a href="/ltsp.php">LTSP</a> |
    | <a href="/ufw.php">ufw</a> |
    | <a href="/systemd.php">systemd</a> |
    | <a href="/downloads.php">downloads</a> |
    | <a href="/setup.php">setup</a> |
    | <a href="/docs.php">docs</a> |
<?php
    if(DEBUG) {
        //echo '| <a href="' . $_SERVER['PHP_SELF'] .'?debug=0">debug Off</a> |';
        echo '| <a href="' . $_SERVER['SCRIPT_NAME'] .'?debug=0">debug Off</a> |' . "\n";
    } else {
        //echo '| <a href="' . $_SERVER['PHP_SELF'] . '?debug">debug On</a> |';
        echo '| <a href="' . $_SERVER['SCRIPT_NAME'] .'?debug">debug On</a> |' . "\n";
    }
?>
<hr />