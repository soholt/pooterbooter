<?php

require_once('./config.php');
require_once('./functions.php');

/**
 * Regenerate di menu
 */
if(isset($_REQUEST['menu'])) {
    #if(!is_link($data['config']['http']['root'] . '/d-i')) {
    #    symlink($data['config']['http']['root'] . '/d-i', $data['config']['tftp'] . '/d-i');
    #}
    $data['debug']['di']['rebuild-grub'] = sys('sudo di-netboot-assistant -v rebuild-grub', $data);
    $data['debug']['di']['rebuild-menu'] = sys('sudo di-netboot-assistant -v rebuild-menu', $data);
}

/**
 * Install di image + arch/s
 */
if(isset($_REQUEST['img']) && isset($_REQUEST['arch']) && is_array($_REQUEST['arch'])) {
    diInstall($_REQUEST['img'], $_REQUEST['arch'], $data);
}

if(isset($_REQUEST['dtbs'])) {
    $dtbs = $data['config']['di']['root'] . '/' . $_REQUEST['dtbs'] . '/armhf/dtbs';
    if(is_dir($dtbs)) sys('ls ' . $dtbs, $data);
}


/**
 * Available vendors/arches parser
 * Generated from 
 * * // Used to generate ipxe di menu, separate from the original
 */
function diAvailable() {
    $available = array();
    if(is_dir('/etc/di-netboot-assistant')) {
        //$data['di']['raw'] = sh('cat /etc/di-netboot-assistant/di-sources.list', $data);
    
        # Available distros/arches
        $di_deb = sh('cat /etc/di-netboot-assistant/di-sources.list', $data);
    
        # Adding to available distros array
        foreach($di_deb as $key => $val) {
            // if not a comment and and the new line is not empty
            if(strpos($val, '#') !== 0 && $val != '') {
                $image = explode("\t", $val);
                $available[$image[0]][] = $image[1];
            }
        }
    }
    return $available;
}
/**
 * Do the Install image arch/s
 * @param string $img
 * @param array $arch
 */
function diInstall($img, $arch, &$data) {
    $arch = implode(',', $arch);
    $cmd = 'sudo di-netboot-assistant install ' . $img . ' --arch=' . $arch . ' --verbose'; # ' --di-args="' . $args . '"
    echo '--- install: ' . $cmd;
    sys($cmd, $data);
}


require_once('./html/head.php');
require_once('./html/menu.php');

echo '<h3>di-netboot-assistant</h3>' . "\n";

echo '<a href="?menu">rebuild menus</a>' . " (bios, efi, armhf)<br /><br />\n";

$data['di']['installed'] = diInstalled($data);
$data['di']['available'] = diAvailable($data);
#echo 'Installed: ' . implode(', ', $data['di']['installed']) . "<br />\n";


echo '<table border = "1">' . "\n";
echo '<tr><th>img</th><th>- v -</th><th>arches available</th><th>installed</th><th>install</th><th>- - -</th></tr>' . "\n";
foreach($data['di']['available'] as $img => $arches) {

    # I can never remember code names.. so
    # Get the version if available
    if(array_key_exists($img, $data['config']['di']['versions'])) {
        $v = $data['config']['di']['versions'][$img];
    } else {
        $v = 'Debian'; # $img;
    }

    $archesInstalled = array();
    $imgInstalled = array_key_exists($img, $data['di']['installed']);
    
    if($imgInstalled) $archesInstalled = $data['di']['installed'][$img];

    $archesAvailable = implode(', ', $arches);


    $archesDiff = array_diff($arches, $archesInstalled);
    $aInstalled = implode(', ', $archesInstalled);
    $aInstalled = str_replace('armhf', '<a href="?dtbs=' . $img . '">armhf</a>', $aInstalled); # add a link to list dtbs if arm

    #$aInstall = implode(', ', $archesDiff);
    $aInstall = '| ';
    foreach($archesDiff as $_arch) {
        $aInstall .= $_arch . ' <input type="checkbox" name="arch[]" value="' . $_arch . '" />' . " |\n";
    }
    
    
    echo "\t" . '<form method="post" action="' . $_SERVER['PHP_SELF'] . '">' . "\n";
    echo "\t\t" . '<input type="hidden" name="img" value="' . $img . '" />' . "\n";
    echo "\t\t" . '<tr><td>' . $img . '</td><td>' . $v . '</td><td>' . $archesAvailable . '</td><td>' . $aInstalled . '</td><td>' . $aInstall . '</td><td><input type="submit" value="install" /></td></tr>' . "\n";
    echo "\t" . '</form>' . "\n";
}
echo '</table>' . "\n";

/*
diInfo($data);

#if(isset($_REQUEST['di'])) {
#    if(isset($data['di']['debian'])) {

        echo "<br />Unfilter:<br />\n";
        $dists = array_keys($data['di']['unfilter']);
        foreach($dists as $dist) {
            #$imageInstalled = strpos($data['di']['installed'], $dist) !== false ? true : false;
            $imageInstalled = array_key_exists($dist, $data['di']['installed']) !== false ? true : false;
            
            #$arches = array_keys($data['di']['unfilter'][$dist]['arch']);
            $arches = $data['di']['unfilter'][$dist]['arch'];
            asort($arches);

            #echo $dist . ' - ' . $data['di']['unfilter'][$dist]['dist'] . ' arch: ' . implode(',', $arches) . "<br />\n";
            

            echo '<form method="get" action="' . $_SERVER['PHP_SELF'] . '">' . "\n";
            echo '<input type="hidden" name="di" value="' . $dist . '" />' . "\n";

            echo "<b>$dist </b>";
            $installedArches = '';
            if($imageInstalled) {
                $installedArches = sh('ls ' . $data['config']['di']['root'] . '/' . $dist, $data);
                $installedArches = implode(' ', $installedArches);#$installedArches[0];
                echo "<b>$installedArches </b>";
            }
            
            echo ' Install: --arch=';
            foreach($arches as $arch) {
                if(strpos($installedArches, $arch) === false) 
                    echo  $arch . '<input type="checkbox" name="arch[]" value="' . $arch . '" />,' . "\n";
            }
            #echo "<br />\n";
            echo '<input type="submit" value="install" />' . "\n";
            echo '</form>' . "\n";
        }
        */
/*
echo "<hr />Install Packages:<br />\n";
echo '<form method="get" action="' . $_SERVER['PHP_SELF'] . '">' . "\n";
echo '<input type="hidden" name="apt" value="install" />' . "\n";
foreach($data['config']['svcs'] as $svc => $val) {
    if(installed($val['pkg'], $data)) {
        #
    } else {
        echo '<input type="checkbox" name="pkgs[]" value="' . $val['pkg'] . '" checked="checked" />' . $val['pkg'] . "<br />\n";
    }
}
echo '<input type="submit" value="install" />' . "<br />\n";
echo '</form>' . "<br />\n";
echo '<hr />';
*/
        
/*
        echo "Other:<br />\n";
        unset($data['di']['debian']);
        unset($data['di']['ubuntu']);
        $dists = array_keys($data['di']);
        foreach($dists as $dist) {
            $arches = array_keys($data['di'][$dist]['arch']);
            asort($arches);
            echo $dist . ' - ' . $data['di'][$dist] . ' arch: ' . implode(',', $arches) . "<br />\n";
        }
        */
#    }
#}



#echo 'To install run: sudo di-netboot-assistant install stable<br />';
#echo 'or to include arch run: sudo di-netboot-assistant install testing --arch=amd64,armhf<br />';

echo 'This is generated from /etc/di-netboot-assistant/di-sources.list<br />';
echo 'Modify this file to enable/disable extra arches/images<br />';
echo 'The bookworm/bookworm-gtk should become available after bullseye release<br />';
/*
ERROR 404: Not Found.
E: Can't download 'bookworm' for 'amd64' (https://deb.debian.org/debian/dists/bookworm/main/installer-amd64/current/images/SHA256SUMS).
I: Moving and/or removing temporary file(s):
removed '/var/cache/di-netboot-assistant/deb.debian.org_debian_dists_bookworm_main_installer-amd64_current_images_SHA256SUMS.tmp'
error: 1
*/

require_once('./html/foot.php');


## scrap
function diInfo(&$data) {

    if(is_dir('/etc/di-netboot-assistant')) {
        //$data['di']['raw'] = sh('cat /etc/di-netboot-assistant/di-sources.list', $data);
    
        # Available distros/arches
        $di_deb = sh('cat /etc/di-netboot-assistant/di-sources.list', $data);
    
        # Adding to available distros array
        foreach($di_deb as $key => $val) {
            // if not a comment and and the new line is not empty
            if(strpos($val, '#') !== 0 && $val != '') {
                $image = explode("\t", $val);
                #print_r($image);
                //$data['di'][$image[0]][$image[1]] = $image[1];
                #$data['di'][$image[0]]['arch'][$image[1]] = $image[2];
                #$data['di'][$image[0]]['arch'][$image[1]] = $image[3];
    
                #$data['di']['unfilter'][$image[0]]['arch'][$image[1]] = $image[2];
                $data['di']['unfilter'][$image[0]]['arch'][] = $image[1];
                #$data['di']['unfilter'][$image[0]]['url'][$image[1]] = $image[3];
                $data['di']['unfilter'][$image[0]]['dist'] = $image[0];
    
                # Add vesion number if found (cannot always remember codenames)
                switch($image[0]) {
    
                    # Debian https://www.debian.org/releases/
                    case 'trixie':    // change on new debian release
                        #$data['di']['debian'][$image[0]]['arch'][$image[1]] = $image[2];
                        #$data['di']['debian'][$image[0]]['arch'][$image[1]] = $image[3];
                        $data['di']['debian'][$image[0]]['arch'][] = $image[1];
                        $data['di']['debian'][$image[0]]['dist'] = 'Debian 13';
                        break;
    
                    case 'bookworm':    // change on new debian release
                    case 'bookworm-gtk':// change on new debian release
                        #$data['di']['debian'][$image[0]]['arch'][$image[1]] = $image[2];
                        #$data['di']['debian'][$image[0]]['arch'][$image[1]] = $image[3];
                        $data['di']['debian'][$image[0]]['arch'][] = $image[1];
                        $data['di']['debian'][$image[0]]['dist'] = 'Debian 12';
                        break;
                    
                    case 'testing':     // change on new debian release
                    case 'testing-gtk': // change on new debian release
                    case 'bullseye':
                    case 'bullseye-gtk':
                        #$data['di']['debian'][$image[0]]['arch'][$image[1]] = $image[2];
                        #$data['di']['debian'][$image[0]]['arch'][$image[1]] = $image[3];
                        $data['di']['debian'][$image[0]]['arch'][] = $image[1];
                        $data['di']['debian'][$image[0]]['dist'] = 'Debian 11';
                        break;
                
                    case 'stable':      // change on new debian release
                    case 'stable-gtk':  // change on new debian release
                    case 'buster':
                    case 'buster-gtk':
                        #$data['di']['debian'][$image[0]]['arch'][$image[1]] = $image[2];
                        #$data['di']['debian'][$image[0]]['arch'][$image[1]] = $image[3];
                        $data['di']['debian'][$image[0]]['arch'][] = $image[1];
                        $data['di']['debian'][$image[0]]['dist'] = 'Debian 10';
                        break;
    
                    case 'oldstable':    // change on new debian release
                    case 'oldstable-gtk':// change on new debian release
                    case 'stretch':
                    case 'stretch-gtk':
                        #$data['di']['debian'][$image[0]]['arch'][$image[1]] = $image[2];
                        #$data['di']['debian'][$image[0]]['arch'][$image[1]] = $image[3];
                        $data['di']['debian'][$image[0]]['arch'][] = $image[1];
                        $data['di']['debian'][$image[0]]['dist'] = 'Debian 9';
                        break;
    
    
                    # Ubuntu http://releases.ubuntu.com/
    
                    case 'focal':
                        #$data['di']['ubuntu'][$image[0]]['arch'][$image[1]] = $image[2];
                        #$data['di']['ubuntu'][$image[0]]['arch'][$image[1]] = $image[3];
                        $data['di']['ubuntu'][$image[0]]['arch'][] = $image[1];
                        $data['di']['ubuntu'][$image[0]]['dist'] = 'Ubuntu 20.04 LTS';
                        break;
    
                    case 'bionic':
                        #$data['di']['ubuntu'][$image[0]]['arch'][$image[1]] = $image[2];
                        #$data['di']['ubuntu'][$image[0]]['arch'][$image[1]] = $image[3];
                        $data['di']['ubuntu'][$image[0]]['arch'][] = $image[1];
                        $data['di']['ubuntu'][$image[0]]['dist'] = 'Ubuntu 18.04 LTS';
                        break;
    
                    case 'xenial':
                        #$data['di']['ubuntu'][$image[0]]['arch'][$image[1]] = $image[2];
                        #$data['di']['ubuntu'][$image[0]]['arch'][$image[1]] = $image[3];
                        $data['di']['ubuntu'][$image[0]]['arch'][] = $image[1];
                        $data['di']['ubuntu'][$image[0]]['dist'] = 'Ubuntu 16.04';
                        break;
    
                    case 'zesty':
                        #$data['di']['ubuntu'][$image[0]]['arch'][$image[1]] = $image[2];
                        #$data['di']['ubuntu'][$image[0]]['arch'][$image[1]] = $image[3];
                        $data['di']['ubuntu'][$image[0]]['arch'][] = $image[1];
                        $data['di']['ubuntu'][$image[0]]['dist'] = 'Ubuntu 17.04';
                        break;
    
                    case 'yakkety':
                        #$data['di']['ubuntu'][$image[0]]['arch'][$image[1]] = $image[2];
                        #$data['di']['ubuntu'][$image[0]]['arch'][$image[1]] = $image[3];
                        $data['di']['ubuntu'][$image[0]]['arch'][] = $image[1];
                        $data['di']['ubuntu'][$image[0]]['dist'] = 'Ubuntu 16.10';
                        break;
            
                    case 'wily':
                        #$data['di']['ubuntu'][$image[0]]['arch'][$image[1]] = $image[2];
                        #$data['di']['ubuntu'][$image[0]]['arch'][$image[1]] = $image[3];
                        $data['di']['ubuntu'][$image[0]]['arch'][] = $image[1];
                        $data['di']['ubuntu'][$image[0]]['dist'] = 'Ubuntu 15.10';
                        break;
            
                    case 'vivid':
                        #$data['di']['ubuntu'][$image[0]]['arch'][$image[1]] = $image[2];
                        #$data['di']['ubuntu'][$image[0]]['arch'][$image[1]] = $image[3];
                        $data['di']['ubuntu'][$image[0]]['arch'][] = $image[1];
                        $data['di']['ubuntu'][$image[0]]['dist'] = 'Ubuntu 15.04';
                        break;
    
                    default:
                        #$data['di']['unfilter'][$image[0]]['arch'][$image[1]] = $image[2];
                        #$data['di']['unfilter'][$image[0]]['arch'][$image[1]] = $image[3];
                        #$data['di']['unfilter'][$image[0]]['dist'] = $image[0];
                }
            }
        }
    }
    
}
    