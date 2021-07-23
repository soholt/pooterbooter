<?php
/**
 * © 2021 Gintaras Valatka https://github.com/soholt/
 */

$data = array();
require('config.php');
require('functions.php');


/**
 * Populate $data array with available and mounted images
 */
lsImages($data);
lsMounted($data);

/**
 * Mount/umount images
 */
if(isset($_GET["action"])) {
    switch($_GET["action"]) {
        case "mount":
            ##$data['id'] = $_GET["id"];
            mountById($_GET["id"], $data); // mount image
            header("Location: " . $_SERVER['PHP_SELF']);exit;
            //lsMounted($data); // refresh mounted images
            //pxeMenuMake($data);
            break;
        case "umount":
            ###$data['id'] = $_GET["id"];
            umountById($_GET["id"], $data); // mount image
            header("Location: " . $_SERVER['PHP_SELF']);exit;
            //lsMounted($data); // refresh mounted images
            //pxeMenuMake($data);
            break;
    }
}

/**
 * Generate pxe and iPXE menus
 */
#pxeMenuMake($data);
makeMenus($data);

/**
 * Html
 */
require_once('./html/head.php');
require_once('./html/menu.php');

echo '<hr />';
echo "Mounted:<ul>\n"; // ---Mounted Images
    foreach ($data['images']['mountedToLower'] as $key => $value) {
        echo "\n\t\t<li>un-mount <a href=\"?action=umount&id=$key\">$value</a></li>";
    }
    echo '</ul><hr />';
    echo 'Images:<ul>'; // ---Available Images to Mount
    foreach ($data['images']['isoToLower'] as $id => $iso) {
        if(!in_array($iso, $data['images']['mountedToLower'])) {
            echo "\n\t\t<li>mount <a href=\"?action=mount&id=$id\">$iso</a></li>";
        }
    }
echo '</ul>';

require_once('./html/foot.php');
