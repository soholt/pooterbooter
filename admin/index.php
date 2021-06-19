<?php
/**
 * © 2021 Gintaras Valatka https://github.com/soholt/
 */

$data = array();

require('config.php');
require('functions.php');
require('pxe_menu_maker.php');
require('downloads.php');

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
            $data['id'] = $_GET["id"];
            mountById($data); // mount image
            header("Location: " . $_SERVER['PHP_SELF']);exit;
            //lsMounted($data); // refresh mounted images
            //pxeMenuMake($data);
            break;
        case "umount":
            $data['id'] = $_GET["id"];
            umountById($data); // mount image
            header("Location: " . $_SERVER['PHP_SELF']);exit;
            //lsMounted($data); // refresh mounted images
            //pxeMenuMake($data);
            break;
    }
}

/**
 * Generate pxe menu
 */
pxeMenuMake($data);

/**
 * Html
 */
?>
<!DOCTYPE html>
<html lang="en-GB">
    <head>
        <meta http-equiv="content-type" content="text/html; charset=utf-8">
        <title>-=pooterbooter=-</title>
    </head>
    <body>
    <?php

        echo '<hr />';
        if(isset($_GET['debug'])) {
            $debug = '&debug';
        } else {
            $debug = '';
        }
        echo 'Mounted:<ul>'; // ---Mounted Images
        foreach ($data['mountedToLower'] as $key => $value) {
            echo "\n\t\t<li>un-mount <a href=\"?action=umount&id=$key$debug\">$value</a></li>";
        }
        echo '</ul><hr />';
        echo 'Images:<ul>'; // ---Available Images to Mount
        foreach ($data['imagesToLower'] as $id => $iso) {
            if(!in_array($iso, $data['mountedToLower'])) {
                echo "\n\t\t<li>mount <a href=\"?action=mount&id=$id$debug\">$iso</a></li>";
            }
        }
        echo '</ul>';

        if(isset($_GET['downloads'])) { # && count($_GET['downloads']) > 0
            echo '<hr />Downloads:';
            $data['downloads'] = downloads($data);
            
            foreach($data['downloads'] as $key => $val) {
                echo 'key: ' . $key . ' val: '. $val;
            }
        }
        //if(isset($_GET['dl'])) { // Show downloads
            echo "\n<hr />";
            echo "\n<br />Mount hdd or download some images to /srv/tftp/mnt/iso";
            echo "\n<br />inside /srv/tftp/mnt/iso, images can be organized in to folders";
            echo "\n<br />(like ./iso/debian/buster to keep iso and checksum together, avoid symlinks)";
            echo "\n<br />Correct: @ 17/June/2021";
            echo "";
            foreach($data['recipes'] as $dist => $val) {
                echo $dist . ":\n<ul>\n";
                //if(is_array($val['doc'])) {
                //    foreach($val['doc'] as $doc) {
                //        echo '<li>doc: <a href="' . $doc . '" target="_blank">' . $doc . "</a></li>\n";
                //    }
                //}
                if(is_array($val['dl'])) {
                    foreach($val['dl'] as $type => $url) {
                        echo '<li>' . $type . ': <a href="' . $url . '">' . $url . "</a></li>\n";
                    }
                }
                echo "</ul>\n";
            }
        //} else {
        //    echo '<a href="' . $_SERVER['PHP_SELF'] . '?dl"></a>';
        //}

        if(DEBUG) {
            echo '<hr /><a href="' . $_SERVER['PHP_SELF'] .'">debug Off</a>';
            echo '<pre>';
            print_r($data);
            echo '</pre>';
        } else {
            echo '<a href="' . $_SERVER['PHP_SELF'] . '?debug">debug On</a>';
        }

    ?>
    <hr />
    BIOS: using pxelinux.0 (lpxelinux.0 on some systems crash)
    <ul>
        <li>Archlinux = OK</li>
        <li>Clonezilla = OK</li>
        <li>Ubuntu Server = OK</li>
        <li>Ubuntu Desktop/Studio Live = !!! FAILS ON SYSTEMS WITH 3Gb RAM, BOOTED OK on 6Gb RAM</li>
    </ul>
    TODO:
    <ul>
        <li>Raspberry Pi boot</li>
        <li>Debian & Fedora boot</li>
        <li>EFI64 & EFI32 boot</li>
        <li>iPXE & NFS(also need to see mounted iso-loop, maybe missing some opts)</li>
        <li>Security + fixes</li>
    </ul>
    </body>
</html>
