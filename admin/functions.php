<?php
/**
 * © 2021 Gintaras Valatka https://github.com/soholt/
 */

function sh($cmd, &$data) {
    $cmd = escapeshellcmd($cmd);
    return shRaw($cmd, $data);
}
/**
 * same as sh, just unescaped
 */
function shRaw($cmd, &$data) {
    $debug = array();
    $debug['cmd'] = $cmd;
    #$debug['cmd_raw'] = $cmd;
    #$debug['escaped'] = $cmd;

    session_write_close();
    #ob_end_flush();
    if(strpos($cmd, 'sudo') !== false) {
        echo "<h4>switch to terminal and enter root pwd to exec cmd: <b>" . $cmd . '</b></h4>';
    }
    $response = array();
    exec($cmd . ' 2>&1', $response, $debug['result_code']);
    //$debug['response'] = $response;
    if($debug['result_code'] !== 0) {
        $data['error'][] = array('cmd' => $cmd, 'msg' => $response); #'todo display error';
        #$response = array();
    }
    $data['debug']['sh'][] = $debug;
    return $response;
}

if(isset($_REQUEST['sys'])) sys('sudo apt-get update', $data);
#sys('ls ~/', $data);
#if(isset($_REQUEST['sys'])) sys('~/sleep', $data);
#sys('/home/gin/sleep', $data);
function sys($cmd, &$data) {
    return sysRaw(escapeshellcmd($cmd), $data);
}
function sysRaw($cmd, &$data) {
    session_write_close();
    #ob_end_flush();
    if(strpos($cmd, 'sudo') !== false) {

        echo "<h4>switch to terminal and enter root pwd to exec cmd: <b>" . $cmd . '</b></h4>';
    }
    echo "<pre>\n";
    $error = 0;
    $line = system($cmd . ' 2>&1', $error);
    $_data = array();
    
    // trick for getting rid of the repeated last line
    $previousLine = '';
    if($line !== false) {
        echo $previousLine;
        $previousLine = $line;
        $_data[] = $line;
    }
    if($error != 0) echo 'error: ' . $error;
    echo "</pre>\n";

    $data['debug']['sh'][] = array('cmd' => $cmd, 'error' => $error);

    array_pop($_data); // get rid of the last repeated line
    return $_data;
}

/**
 * TODO redo to accept images array
 */
function lsImages(&$data) {
    #$data['debug']['function'][] = 'lsImages()';
    $cmd = 'find ' . $data['config']['images'] . ' -name "*.iso"';
    //$images = sh($cmd, $data); ## note doesnt work through sh(), * gets escaped to \*
    #exec($cmd, $data['paths'], $data['debug']['error']['lsImages']);
    $paths = shRaw($cmd, $data);
    #$key = 0;
    foreach($paths as $i => $path) {
        $parts = explode('/', $path);
        $iso = array_pop($parts);
        #$data['images'] = array('iso', 'isoToLower');
        $data['images']['iso'][$i] = $iso; // extracting iso file name
        $data['images']['isoPath'][$i] = $path; // extracting iso file name
        $data['images']['isoToLower'][$i] = strtolower($iso); // mixed case does not sort right
    }

    asort($data['images']['isoToLower']); // sort by name

    // Flip iso array, so we can faind the id and path by name
    $data['images']['isoIdByName'] = array_flip($data['images']['iso']); // sort by name
}


function lsMounted(&$data) {
    $data['images']['mounted'] = array(); // reset array, for when if.. called twice
    $data['images']['mountedToLower'] = array(); // reset array
    $paths_iso = sh('mount -t iso9660', $data);
    $paths_udf = sh('mount -t udf', $data); // win 10 images udf = Universal Disk Format
    $paths = array_merge($paths_iso, $paths_udf);
    foreach($paths as $key => $value) { // extract all *.iso files
        $parts = explode(" ", $value);
        $vals = explode("/", $parts[0]);
        $dist = array_pop($vals);
        if(strpos($value, ".iso") !== false) {
            $data['images']['mounted'][$key] = $dist;
            $data['images']['mountedToLower'][$key] = strtolower($dist);
        }
    }
    asort($data['images']['mountedToLower']); // alpha sort
}

function mountById($id, &$data) {
    if(is_numeric($id)) {
        $id = (int) $id;
        if(array_key_exists($id, $data['images']['iso'])) { // check if key exist
            $iso = $data['images']['iso'][$id];
            $imagePath = $data['images']['isoPath'][$id];
            $mountPath = $data['config']['mounted'] . '/' . $iso;
            if(!in_array($iso, $data['images']['mounted'])) { // check if not mounted yet
                # create dir if missing
                $cmd = 'sudo mkdir -p ' . $mountPath;
                sh($cmd, $debug);
                # mount
                $cmd = 'sudo mount -o loop,ro ' . $imagePath . ' ' . $mountPath;
                sh($cmd, $debug);
            }
        }
    }
    
}

function umountById($id, &$data) { #$data['debug']['function'][] = 'umountById()';
    if(is_numeric($id)) {
        $id = (int) $id;
        if(array_key_exists($id, $data['images']['mounted'])) {
            $umount = 'sudo umount ' . $data['config']['mounted'] . '/' . $data['images']['mounted'][$id];
            sh($umount, $data); # unmount
            $rmdir = 'sudo rmdir ' . $data['config']['mounted'] . '/' . $data['images']['mounted'][$id];
            sh($rmdir, $data); # remove empty dir
        }
    }
}

// List of supported distributions
function supported(&$data) {

    $dists = sh('ls ./distros', $data);
    #$data['supported'] = $dists;

    foreach($dists as $dist) {
        // require
        require_once('./distros/' . $dist);

        // function name
        $dist = str_replace('.php', '', $dist);
        $data['config']['supported'][] = $dist;
        // call function
        $dist($data);
    }

}

supported($data);

# Get full http path to the iso image by iso
function isoHttpPath($iso, &$data) {
    $isoHttpPath = '';
    $isoKey = array_search($iso, $data['images']['iso']);
    if($isoKey !== false) {
        $isoPath = $data['images']['isoPath'][$isoKey];
        $isoHttpPath = str_replace($data['config']['http']['root'], $data['config']['http']['www'], $isoPath);
    }
    return $isoHttpPath;
}

function makeMenus(&$data) {

    foreach($data['config']['supported'] as $dist) {
        // require
        require_once('./distros/' . $dist . '.php');

        // call the function to generate menu(which is the same name as the file name)
        $dist($data);
    }

    // Menu generators
    require_once('./menu-maker/pxe.php');
    require_once('./menu-maker/ipxe.php');

    make_pxe_menu($data);
    make_ipxe_menu($data);

    // TODO sudo chmod 666 /srv/tftp/d-i/n-a/pxelinux.cfg/default-arm
    if(is_file('/srv/tftp/d-i/n-a/pxelinux.cfg/default')) {
        $data['menu']['di']['default'] = sh('cat /srv/tftp/d-i/n-a/pxelinux.cfg/default', $data);
    }
    if(is_file('/srv/tftp/d-i/n-a/pxelinux.cfg/default-arm')) {
        $data['menu']['di']['default-arm'] = sh('cat /srv/tftp/d-i/n-a/pxelinux.cfg/default-arm', $data);
    }
    if(is_file('/srv/tftp/ltsp/ltsp.ipxe')) {
        $data['menu']['ltsp'] = sh('cat /srv/tftp/ltsp/ltsp.ipxe', $data);
    }
/*

    foreach($data['images']['mounted'] as $i => $val) {
        echo "i: $i val: $val<br>\n";

    }
    */
}

/**
 * Check if package is installed
 * @return bool
 */
function installed($pkg, &$data) {
    $cmd = 'dpkg -l ' . $pkg . ' | grep ' . $pkg;
    $dpkg = shRaw($cmd, $data);
    #echo '<pre>---';print_r($dpkg);echo '</pre>';
    #$dpkg = $dpkg[0];
    #echo 'PKG: ' . $pkg . ' CMD: ' . $cmd . "<br />\n";
    if(count($dpkg) > 0 && strpos($dpkg[0], 'ii') !== false) {
        #echo $val['pkg'] . " installed<br />\n";
        return true;
    } else {
        return false;
    }
}

/**
 * Controll services
 */
function systemctl($svc, $action, &$data) {
    if(array_key_exists($svc, $data['config']['svcs'])) {
        if($action == 'enable') sys('sudo systemctl enable ' . $svc . ' --no-pager', $data);
        if($action == 'disable') sys('sudo systemctl disable ' . $svc . ' --no-pager', $data);
        if($action == 'status') sys('sudo systemctl status ' . $svc . ' --no-pager', $data);
        if($action == 'start') sys('sudo systemctl start ' . $svc . ' --no-pager', $data);
        if($action == 'stop') sys('sudo systemctl stop ' . $svc . ' --no-pager', $data);
        if($action == 'restart') sys('sudo systemctl restart ' . $svc . ' --no-pager', $data);
        if($action == 'reload') sys('sudo systemctl reload ' . $svc . ' --no-pager', $data);
    }
}




/**
 * Installed di images and arches
 * @return array
 */
function diInstalled(&$data) {
    # To get installed run: "di-netboot-assistant purge"
    # And it returns installed in error, so run it here
    # cmd: di-netboot-assistant purge
    # error: E: No repository name was passed for 'purge'.
    # error: I: Purgable repositories are:
    # error:  focal stable testing testing-gtk
    #$installed = sh('di-netboot-assistant purge', $data);

    $dists = array();
    $installed = array();
    exec('di-netboot-assistant purge 2>&1', $installed);
    $_dists = array_pop($installed);
    $_dists = explode(' ', $_dists);

    # Arches
    foreach($_dists as $dist) {
        $dist = trim($dist);
        if($dist != '') $dists[$dist] = sh('ls ' . $data['config']['di']['root'] . '/' . $dist, $data);
    }

    return $dists;
}