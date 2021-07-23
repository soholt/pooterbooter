<?php

require_once('./config.php');
require_once('./functions.php');
require_once('./html/head.php');
require_once('./html/menu.php');


#$data['debug']['ufw']['apps'] = sh('sudo ufw app list', $data);

$data['ufw']['current']['rules'] = ufwCurrentRules($data);
$data['ufw']['current']['ports'] = ufwCurrentPorts($data['ufw']['current']['rules']);

$data['ufw']['required']['ports'] = ufwRequiredPorts($data);

if(isset($_REQUEST['ufw'])) {
    switch($_REQUEST['ufw']) {

        # Enable/dissable ufw
        case 'enable':
            //sys('sudo ufw enable', $data);
            echo 'To enable ufw run in terminal: sudo ufw enable';
            break;

        # Enable/dissable ufw
        case 'disable':
            sys('sudo ufw disable', $data);
            break;
    }
}

if(isset($_REQUEST['ufw'])) {

    if($_REQUEST['ufw'] == 'allow' && isset($_REQUEST['rules']) && is_array($_REQUEST['rules'])) {
        foreach($_REQUEST['rules'] as $id) {
            # find port by id
            echo 'sudo ufw allow ' . $data['ufw']['required']['ports'][$id] . "<br />\n";
            sys('sudo ufw allow ' . $data['ufw']['required']['ports'][$id], $data);
        }
    }
    // it does not work, requires cli confirmation
    if($_REQUEST['ufw'] == 'del') {
        if(isset($_REQUEST['id']) && is_numeric($_REQUEST['id'])) {
            #sys('sudo ufw delete ' . (int)$_REQUEST['id'], $data);
            echo 'run in cmd: ' . 'sudo ufw delete ' . (int)$_REQUEST['id'] . "<br />\n";
        }
    }
    # regen
    $data['ufw']['current']['rules'] = ufwCurrentRules($data);
    $data['ufw']['current']['ports'] = ufwCurrentPorts($data['ufw']['current']['rules']);
}


echo '<h3>Uncomplicated firewall</h3>' . "\n";

# ufw Status
$uwfStatus = sh('sudo ufw status', $data);
$uwfStatus = $uwfStatus[0];
if(strpos($uwfStatus, 'inactive') !== false) {
    $status = false;
    echo 'STATUS: OFF - <a href="?ufw=enable">enable</a>' . "<br />\n";
} else {
    $status = true;
    echo 'STATUS: ON - <a href="?ufw=disable">disable</a>' . "<br />\n";
}




echo '<table border="1">' . "\n";
echo '<tr><th>#rule</th><th>port</th><th>allow</th><th>ipv6</th></tr>' . "\n";//<th>del</th>
foreach($data['ufw']['current']['rules'] as $id => $rule) {
    $port = $rule['port'];
    $allow = $rule['allow'] == true ? 'true' : 'false';
    $ipv6 = $rule['ipv6'] == true ? ' (ipv6)' : '';
    #echo "port: $port allow: $allow $ipv6<br />\n";
    #if(in_array($port, $ports)) {
    #    $_enabled = 'green';
    #} else {
    #    $_enabled = 'red';
    #}
    # style="background-color: ' . $_enabled . '"
    echo '<tr><td>' . $id . "</td><td>$port</td><td>$allow</td><td>$ipv6</td></tr>\n";//<td><a href=\"?ufw=del&id=" . $rule['rule'] . "\">del</a></td>
}
echo '</table>' . "<br />\n";
echo 'To detete # rule, run it in cmd: <b>sudo ufw delete </b>#rule number' . "<br />\n";
echo 'Can not do it via the browser as confirmation required..' . "<br />\n";



echo "<hr />\nRequired ports:<br />\n";

echo '<form method="post" action="' . $_SERVER['PHP_SELF'] . '">' . "\n";
echo '<input type="hidden" name="ufw" value="allow" />' . "\n";

echo '<table border = "1">' . "\n";
#echo '<tr><th>img</th><th>- v -</th><th>arches available</th><th>installed</th><th>install</th><th>- - -</th></tr>' . "\n";
echo '<tr>';
    echo '<th>port</th>';
    echo '<th>status</th>';
    echo '<th>allow</th>';
echo '</tr>' . "\n";

foreach($data['ufw']['required']['ports'] as $i => $port) {

    $allowed = in_array($port, $data['ufw']['current']['ports']);

    if($allowed) {
        $_bg = '#77dd77';
        $status = 'allowed';
        $action = '- - -';
    } else {
        # enable
        $_bg = '#ff9691';
        $status = 'error';
        $action = '<input type="checkbox" name="rules[]" value="' . $i . '" checked="checked" />';
    }

    echo '<tr style="background-color: ' . $_bg . '">';
        echo '<td>' . $port . '</td>';
        echo '<td>' . $status . '</td>';
        echo '<td>' . $action . '</td>';
    echo '</tr>' . "\n";
    #echo $i . ' - ' .$port . ' - ' . $action . "<br />\n";
    #echo "\t\t" . '<tr><td>' . $img . '</td><td>' . $v . '</td><td>' . $archesAvailable . '</td><td>' . $aInstalled . '</td><td>' . $aInstall . '</td><td><input type="submit" value="install" /></td></tr>' . "\n";
    
}
echo '</table>' . "\n";
echo '<input type="submit" value="allow" />';
echo '</form>' . "\n";

require_once('./html/foot.php');

function ufwRequiredPorts(&$data) {
    #$ports = array_values($data['config']['svcs']['ports']);
    $ports = array();
    foreach($data['config']['svcs'] as $svc => $params) {
        if(isset($data['config']['svcs'][$svc]['ports'])) {
            #$ports[$svc] = $data['config']['svcs'][$svc]['ports'];
            foreach($data['config']['svcs'][$svc]['ports'] as $port) {
                $ports[] = $port;
            }
        }
    }
    return $ports;
}

function ufwCurrentRules(&$data) {
    $_data = array();
    $data['debug']['ufw']['raw'] = sh('sudo ufw status numbered', $data);

    foreach($data['debug']['ufw']['raw'] as $rule) {
        #if($rule[0] == '[') {
        
        if($rule != '' && strpos($rule, '[') !== false) {

            $rule = trim($rule);

            $rule = str_replace('[ ', '', $rule); // 0-9 contain extra pad space "[ 1] 53/udp                     ALLOW IN    Anywhere"
            $rule = str_replace('[', '', $rule);
            $rule = str_replace(']', '', $rule);
            $rule = str_replace('  ', '', $rule);

            $part = explode(' ', $rule);

            #$_data['p'][] = $part;
            #$_data['r'][] = array(
            $_data[$part[0]] = array(
                #'rule' => $part[0],
                'port' => str_replace('ALLOW', '', $part[1]),
                'allow' => (strpos($rule ,'ALLOW') !== false ? true : false),
                'ipv6' => (strpos($rule ,'(v6)') !== false ? true : false),
            );
        }
    }
    return $_data;
}
function ufwCurrentPorts($rules) {
    $_data = array();
    foreach($rules as $rule) {
        $_data[] = $rule['port'];
    }
    return $_data;
}
