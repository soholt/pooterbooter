<?php

require_once('./config.php');
require_once('./functions.php');

#header('Content-Type: application/json');

$svc_options = array();
#$svc_options['type'] = 'service';
$svc_options['output'] = 'json';
$svc_options['no-pager'] = '';


if(isset($_REQUEST['svc'])) {
    $svc = trim($_REQUEST['svc']);
    if(array_key_exists($svc, $data['config']['svcs'])) {
    /*
        if(isset($_REQUEST['enable'])) sys('sudo systemctl enable ' . $svc . ' --no-pager', $data);
        if(isset($_REQUEST['disable'])) sys('sudo systemctl disable ' . $svc . ' --no-pager', $data);
        if(isset($_REQUEST['stop'])) sys('sudo systemctl stop ' . $svc . ' --no-pager', $data);
        if(isset($_REQUEST['start'])) sys('sudo systemctl start ' . $svc . ' --no-pager', $data);
        if(isset($_REQUEST['restart'])) sys('sudo systemctl restart ' . $svc . ' --no-pager', $data);
*/

        if(isset($_REQUEST['enable'])) systemctl($svc, 'enable', $data);
        if(isset($_REQUEST['disable'])) systemctl($svc, 'disable', $data);
        if(isset($_REQUEST['status'])) systemctl($svc, 'status', $data);
        if(isset($_REQUEST['start'])) systemctl($svc, 'start', $data);
        if(isset($_REQUEST['stop'])) systemctl($svc, 'stop', $data);
        if(isset($_REQUEST['restart'])) systemctl($svc, 'restart', $data);
        if(isset($_REQUEST['reload'])) systemctl($svc, 'reload', $data);

        # display status after the op, unless it's status :D
        if(isset($_REQUEST['status']) == false) systemctl($svc, 'status', $data);
    }
}

function listUnits($svc_options, &$data) {
    $cmd = 'systemctl list-units --all --state=failed,active,inactive'; # --state=active,dead
    foreach($svc_options as $key => $val) {
        $cmd .= ' --' . $key . ' ' . $val;
    }
    $cmd = trim($cmd);
    $raw = sh($cmd, $data);
    $_data = json_decode($raw[0], true);
    $services  = array_keys($data['config']['svcs']);
    foreach($_data as $key => $val) {
        $unit = str_replace('.service', '', $val['unit']);
        if(in_array($unit, $services)) $data['svcs']['units'][$unit] = $val;
    }
}

/**
 * installed
 */
function listUnitFiles($svc_options, &$data) {
    $cmd = 'systemctl list-unit-files --type=service --state=enabled,disabled';
    foreach($svc_options as $key => $val) {
        $cmd .= ' --' . $key . ' ' . $val;
    }
    $cmd = trim($cmd);
    $raw = sh($cmd, $data);
    $_data = json_decode($raw[0], true);
    $services  = array_keys($data['config']['svcs']);
    foreach($_data as $key => $val) { #echo " uf " . $val['unit_file'];
        $unit = str_replace('.service', '', $val['unit_file']);
        if(in_array($unit, $services)) $data['svcs']['unit-files'][$unit] = $val;
    }
}

//$data['units'] = json_decode(listUnits($svc_options)[0], true);
#$data['debug']['svcs']['units'] = 
listUnits($svc_options, $data);
#$data['debug']['svcs']['files'] = 
listUnitFiles($svc_options, $data);


require_once('./html/head.php');
require_once('./html/menu.php');

echo '<h3>systemd</h3>' . "\n";

echo 'TODO: show disbled service status<br />' . "\n";
echo 'NOTE: enabled service can be stopped, use status for now<br />' . "\n";

echo '<table border = "1">';
echo "<tr>";
    echo '<th>unit</th>';
    echo '<th>state</th>';
    echo '<th>action</th>';


    echo '<th>load</th>';
    echo '<th>active</th>';
    echo '<th>sub</th>';
    echo '<th>action</th>';
    echo '<th>service</th>';
    echo "</tr>\n";
foreach($data['svcs']['unit-files'] as $unit => $val) {

    echo "<tr>";
    echo "<td>$unit</td>\n";

    $_bg = $val['state'] == 'enabled' ? 'green' : 'red';
    $_enable = $val['state'] == 'enabled' ? 'disable' : 'enable';
    echo '<td style="background-color: ' . $_bg . '">' . $val['state'] . '</td>' . "\n";
    echo '<td>| <a href="?svc=' . $unit . '&' . $_enable . '">' . $_enable . '</a> |</td>' . "\n";
    

    if($val['state'] == 'enabled' && isset($data['svcs']['units'][$unit]['active'])) {
        echo "<td>" . $data['svcs']['units'][$unit]['load'] . "</td>\n";
        $_active = $data['svcs']['units'][$unit]['active'] == 'active' ? 'green' : 'red';
        echo '<td style="background-color: ' . $_active . '">' . $data['svcs']['units'][$unit]['active'] . "</td>\n";
        echo "<td>" . $data['svcs']['units'][$unit]['sub'] . "</td>\n";
    } else {
        echo "<td> - </td>\n";
        echo "<td> - </td>\n";
        echo "<td> - </td>\n";
    }

    #echo '<td style="background-color: ' . $_bg . '">' . $val['state'] . '</td>' . "\n";
    echo '<td>
            | <a href="?svc=' . $unit . '&status">status</a> |
            | <a href="?svc=' . $unit . '&start">start</a> |
            | <a href="?svc=' . $unit . '&stop">stop</a> |
            | <a href="?svc=' . $unit . '&restart">restart</a> |
            | <a href="?svc=' . $unit . '&reload">reload</a> |
        </td>' . "\n";
    echo "<td>" . $data['svcs']['units'][$unit]['description'] . "</td>\n";
    echo "</tr>\n";
}
echo '</table>';

require_once('./html/foot.php');




#$data['svcs']['diff'] = array_diff_key($data['svcs']['unit-files'], $data['svcs']['units']);

# systemctl list-units -o json --no-pager
/*
$output = array();
$error = null;

$cmd = '';

if(isset($_REQUEST['units'])) $cmd = 'systemctl list-units';
if(isset($_REQUEST['files'])) $cmd = 'systemctl list-unit-files';



foreach($svc_options as $key => $val) {
    $cmd .= ' --' . $key . ' ' . $val;
}
$cmd = trim($cmd);

#echo '<hr />cmd: ' . $cmd . '<hr />';
if($cmd != '') exec($cmd, $output, $error);

#if(isset($_REQUEST['units'])) exec('systemctl list-units --type service --output json --no-pager', $output, $retval);

#if(isset($_REQUEST['files'])) exec('systemctl list-unit-files --type service --output json --no-pager', $output, $retval);

if(isset($output[0])) {
    $data = array();
    $_data = json_decode($output[0], true);
    if(isset($_REQUEST['units'])) {
        foreach($_data as $key => $val) {
            //array_push($val, ['svc' => str_replace('.service', '', $val['unit'])]);
            $val['svcs'] = str_replace('.service', '', $val['unit']);
            if(in_array($val['unit'], $display_services)) array_push($data, $val);
        }
    }
    if(isset($_REQUEST['files'])) {
        foreach($_data as $key => $val) {
            $val['svcs'] = str_replace('.service', '', $val['unit_file']);
            if(in_array($val['unit_file'], $display_services)) array_push($data, $val);
        }
    }
    echo json_encode(['cmd' => $cmd, 'data' => $data]); #$output[0]);//json_decode($output[0]]);
} else {
    echo json_encode(['error' => 'cmd not found']);
}

#$data['status'] = json_decode($output['status'][0]);
#$data['units'] = json_decode($output['units'][0]);

#echo "Returned with status $retval and output:\n";
//echo "<pre>\n";
//print_r($data);
//echo "</pre>\n";
*/
#require_once('./html/foot.php');