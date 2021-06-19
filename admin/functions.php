<?php
/**
 * © 2021 Gintaras Valatka https://github.com/soholt/
 */

if(isset($_GET['debug'])) {
    define("DEBUG", true);
    ini_set('display_errors', 1);
    ini_set('display_startup_errors', 1);
    error_reporting(E_ALL);
} else {
    define("DEBUG", false);
}
ob_end_flush();

function sh($cmd, &$data) {
    $debug = array();
    $debug['raw'] = $cmd;
    $cmd = escapeshellcmd($cmd);
    $debug['escaped'] = $cmd;
    if(strpos($cmd, 'sudo') !== false) {
        //echo "switch to terminal and enter root pwd";
        //echo "to exec cmd: " . $cmd;
    }
    $response = array();
    exec($cmd, $response, $debug['result_code']);
    //$debug['response'] = $response;
    $data['debug']['sh'][] = $debug;
    return $response;
}

/**
 * TODO redo to accept images array
 */
function lsImages(&$data) {
    $data['debug']['function'][] = 'lsImages()';
    $cmd = 'find ' . $data['config']['images'] . ' -name "*.iso"';
    //$images = sh($cmd, $data); ## note doesnt work through sh(), * gets escaped to \*
    exec($cmd, $data['paths'], $data['debug']['error']['lsImages']);
    foreach($data['paths'] as $i => $path) {
        $parts = explode('/', $path);
        $data['images'][$i] = array_pop($parts); // extracting iso file name
        $data['imagesToLower'][$i] = strtolower($data['images'][$i]); // mixed case does not sort right
    }
    asort($data['imagesToLower']); // sort by name
}

function lsMounted(&$data) {
    $data['debug']['function'][] = 'lsMounted()';
    $data['mounted'] = array(); // reset array, for when called twice
    $data['mountedToLower'] = array(); // reset array, for when called twice
    $path = sh('mount -t iso9660', $data);
    foreach($path as $key => $value) { // extract all *.iso files
        $parts = explode(" ", $value);
        $vals = explode("/", $parts[0]);
        $dist = array_pop($vals);
        if(strpos($value, ".iso") !== false) {
            $data['mounted'][$key] = $dist;
            $data['mountedToLower'][$key] = strtolower($dist);
        }
    }
    asort($data['mountedToLower']); // alpha sort
}

function mountById(&$data) {
    $data['debug']['function'][] = 'mountById()';
    if(is_numeric($data['id'])) {
        $id = (int) $data['id'];
        if(array_key_exists($id, $data['images'])) { // check if key exist
            $iso = $data['images'][$id];
            $imagePath = $data['paths'][$id];
            $mountPath = $data['config']['mounted'] . '/' . $iso;
            if(!in_array($iso, $data['mounted'])) { // check if not mounted yet
                $cmd = 'sudo mkdir -p ' . $mountPath;
                sh($cmd, $debug); # create dir if missing
                $cmd = 'sudo mount -o loop,ro ' . $imagePath . ' ' . $mountPath;
                sh($cmd, $debug); # mount
            }
        }
    }
}

function umountById(&$data) { $data['debug']['function'][] = 'umountById()';
    if(is_numeric($data['id'])) {
        $id = (int) $data['id'];
        if(array_key_exists($id, $data['mounted'])) {
            $umount = 'sudo umount ' . $data['config']['mounted'] . '/' . $data['mounted'][$id];
            sh($umount, $data); # unmount
            $rmdir = 'sudo rmdir ' . $data['config']['mounted'] . '/' . $data['mounted'][$id];
            sh($rmdir, $data); # remove empty dir
        }
    }
}


function downloads(&$data)
{
    echo '<li>';
    $data['download'] = sh('');
    foreach($data as $name => $dl) {
        echo 'name: ' . $name;
        echo 'dl: ' . $dl;
    }
    echo '</li>';
}
