<?php
require_once('./config.php');
require_once('./functions.php');
/*
if(isset($_REQUEST['apt'])) {
    if($_REQUEST['apt'] == 'update') sys('sudo apt-get update', $data);
    if($_REQUEST['apt'] == 'upgrade') sys('sudo apt-get upgrade', $data);
    if($_REQUEST['apt'] == 'install' && isset($_REQUEST['pkgs']) && is_array($_REQUEST['pkgs'])) {
        $pkgs = implode(' ', $_REQUEST['pkgs']);
        # exception, installing with option --no-install-recommends
        if(strpos($pkgs, 'di-netboot-assistant') !== false) {
            # install it separate
            #sys('sudo apt-get install -y di-netboot-assistant --no-install-recommends', $data);
            echo 'sudo apt-get install -y di-netboot-assistant --no-install-recommends' . "<br />\n";
            # remove di-netboot-assistant and let the script run
            $pkgs = str_replace(' di-netboot-assistant', '', $pkgs);
        }
        $cmd = 'sudo apt-get install -y ' . $pkgs;
        echo $cmd . "<br />\n";
        #sys($cmd, $data); # would fail on apt-cacher-ng how to accept default? apt [-h] [-o=config_string] [-c=config_file]

    }
}
*/
if(isset($_REQUEST['install'])) {
    if($_REQUEST['install'] == 'wimboot32') {
        sys('wget ' . $data['config']['ipxe']['wimboot32'] . ' -O ' . $data['config']['ipxe']['root'] . '/wimboot.i386', $ata);
    }
    if($_REQUEST['install'] == 'wimboot64') {
        sys('wget ' . $data['config']['ipxe']['wimboot64'] . ' -O ' . $data['config']['ipxe']['root'] . '/wimboot', $ata);
    }
    /**
     * Install/update config files
     */
    if($_REQUEST['install'] == 'dnsmasq') {
        # Install config
        if(isset($_REQUEST['config'])) {
            #sh('sudo mkdir -p /etc/dnsmasq.d', $data);
            sh('sudo cp ../configs/dnsmasq.pb.conf /etc/dnsmasq.d/pb.conf', $data);
            sh('sudo chown root:root /etc/dnsmasq.d/pb.conf', $data);
            systemctl('dnsmasq', 'restart', $data);
            systemctl('dnsmasq', 'status', $data);
        }
    }

    if($_REQUEST['install'] == 'nfs') {
        # Install config
        if(isset($_REQUEST['config'])) {
            #sh('sudo mkdir -p /etc/exports.d');
            sh('sudo cp ../configs/nfs.pb.conf /etc/exports.d/pb.exports', $data);
            sh('sudo chown root:root /etc/exports.d/pb.exports', $data);
            sys('sudo exportfs -ra', $data);
            systemctl('nfs-server', 'restart', $data);
            systemctl('nfs-server', 'status', $data);
            
        }
    }

    if($_REQUEST['install'] == 'nginx') {
        # Install config
        if(isset($_REQUEST['config'])) {
            sh('sudo cp ../configs/nginx.pb.conf /etc/nginx/sites-available/pb.conf', $data);
            sh('sudo chown root:root /etc/nginx/sites-available/pb.conf', $data);
        }
        # enable disable nginx virtual host
        if(isset($_REQUEST['pb'])) {
            if($_REQUEST['pb'] == '0' && is_link('/etc/nginx/sites-enabled/pb.conf')) {
                sh('sudo rm /etc/nginx/sites-enabled/pb.conf', $data);
            }

            if($_REQUEST['pb'] == '1' && is_file('/etc/nginx/sites-available/pb.conf') && is_link('/etc/nginx/sites-enabled/pb.conf') === false) {
                sh('sudo ln -s /etc/nginx/sites-available/pb.conf /etc/nginx/sites-enabled/', $data);
                sh('sudo chown root:root /etc/nginx/sites-enabled/pb.conf', $data);
            }
        }
        systemctl('nginx', 'restart', $data);
        systemctl('nginx', 'status', $data);
    }
}

if(isset($_REQUEST['test'])) {
    if($_REQUEST['test'] == 'avahi') sys('avahi-browse --all --resolve --terminate', $data); //--ignore-local
}

require_once('./html/head.php');
require_once('./html/menu.php');

echo 'Setup:' . "<hr />\n";
/*
#echo '<a href="?apt=update">apt update</a> | <a href="?apt=upgrade">apt upgrade</a>';
echo "<hr />Install required Packages:<br />\n";
echo '<form method="post" action="' . $_SERVER['PHP_SELF'] . '">' . "\n";
echo '<input type="hidden" name="apt" value="install" />' . "\n";
foreach($data['config']['svcs'] as $svc => $val) {
    if($val['required']) {
        $checkBox = ' checked="checked"';
    } else {
        $checkBox = '';
    }
    if(installed($val['pkg'], $data)) {
        echo 'Pkg: ' . $val['pkg'] . ' is installed' . "<br />\n";
    } else {
        echo '<input type="checkbox" name="pkgs[]" value="' . $val['pkg'] . '"' . $checkBox . ' />' . $val['pkg'] . "<br />\n";
    }
}
foreach($data['config']['pkgs']['debian']['required'] as $pkg) {
    if(installed($pkg, $data)) {
        echo 'Pkg: ' . $pkg . ' is installed' . "<br />\n";
    } else {
        echo '<input type="checkbox" name="pkgs[]" value="' . $pkg . '"' . $checkBox . ' />' . $pkg . "<br />\n";
    }
}
echo '<input type="submit" value="install" />' . "<br />\n";
echo '</form>' . "<br />\n";
echo '<hr />';
*/
$data['debug']['setup'] = array();


$dist = sh('cat /etc/issue', $data);
$dist = strtolower(trim($dist[0]));

if(strpos($dist, 'debian') !== false) $data['config']['dist'] = 'debian';
if(strpos($dist, 'raspbian') !== false) $data['config']['dist'] = 'raspbian';
if(strpos($dist, 'ubuntu') !== false) $data['config']['dist'] = 'ubuntu';

$data['debug']['dist'] = $dist;
#$data['debug']['dist'] = trim($data['debug']['dist'][0]);
/*
$svc = 'nginx';
if(is_file('/etc/nginx/sites-available/pb.conf')) {
    #$data['debug']['svcs']['installed'][$svc] =  false;
} else {
    $data['debug']['svcs']['installed'][$svc] = '<a href="?install=' . $svc . '&config">install/reinstall ' . $svc . ' config</a>';
}

$svc = 'dnsmasq';
if(is_file('/etc/dnsmasq.d/pb.conf')) {
    #$data['debug']['svcs']['installed'][$svc] = false;
} else {
    $data['debug']['svcs']['installed'][$svc] = '<a href="?install=' . $svc . '&config">install/reinstall ' . $svc . ' config</a>';
}

$svc = 'nfs';
if(is_file('/etc/exports.d/pb.exports')) {
    #$data['debug']['svcs']['installed'][$svc] = false;
} else {
    $data['debug']['svcs']['installed'][$svc] = '<a href="?install=' . $svc . '&config">install/reinstall ' . $svc . ' config</a>';
}

echo 'Dist: ' . $data['debug']['dist'][0] . "<br />\n";
*/
/*
foreach($data['config']['svcs']['required']['svcs'] as $svc) {
    "required: $svc<br />\n";
}


foreach($data['debug']['svcs']['installed'] as $svc => $state) {
    
    if($data['debug']['svcs']['installed'][$svc] !== false) {
        echo "svc config: => $svc => $state\n";
        echo "<br />\n";
    } else {
        echo "svc config: => $svc => $state\n";
        echo "<br />\n";
    }
}
*/

echo 'Edit the config files in pooterbooter/configs/* then install/update' . "<br />\n";

if(is_file('/etc/dnsmasq.d/pb.conf')) {
    echo '<a href="?install=dnsmasq&config">update dnsmasq config</a>' . "<br />\n";
} else {
    echo '<a href="?install=dnsmasq&config">install dnsmasq config</a>' . "<br />\n";
}

if(is_file('/etc/exports.d/pb.exports')) {
    echo '<a href="?install=nfs&config">update nfs config</a>' . "<br />\n";
} else {
    echo '<a href="?install=nfs&config">install nfs config</a>' . "<br />\n";
}

if(is_file('/etc/nginx/sites-available/pb.conf')) {
    echo '<a href="?install=nginx&config">update nginx config</a>' . "<br />\n";
} else {
    echo '<a href="?install=nginx&config">install nginx config</a>' . "<br />\n";
}

# Enable/disable nginx vhost
if(is_link('/etc/nginx/sites-enabled/pb.conf')) {
    echo 'Nginx pb vhost <a href="?install=nginx&pb=0">disable</a>  Test it <a target="_blank" href="' . $data['config']['http']['www'] . '">' . $data['config']['http']['www'] . '</a>' . "<br />\n";
} else {
    if(is_file('/etc/nginx/sites-available/pb.conf')) {
        echo 'Nginx pb vhost <a href="?install=nginx&pb=1">enable</a>' . "<br />\n";
    }
}

echo '<a href="?test=avahi">Test avahi</a>' . "<br />\n";
echo 'Test apt-cacher-ng <a target="_blank" href="' . $data['config']['svcs']['apt-cacher-ng']['www'] . '">' . $data['config']['svcs']['apt-cacher-ng']['www'] . '</a>' . "<br />\n";
echo 'Stats apt-cacher-ng <a target="_blank" href="' . $data['config']['svcs']['apt-cacher-ng']['www'] . '/acng-report.html">' . $data['config']['svcs']['apt-cacher-ng']['www'] . '/acng-report.html</a>' . "<br />\n";



/*
echo "Services:<br />\n";
foreach($data['config']['svcs']['required']['svcs'] as $pkg) {
    #echo 'install ' . $pkg['name'] . 'svc: ' . $pkg['svc'];

}
*/


#$data['config']['svcs']['required']['svc'] = array('dnsmasq');
#$data['config']['svcs']['install'] = array('dnsmasq');
#foreach($data['config']['svcs']['required']['svc'] as $svc => $pkg) {
    //$data['config']['svcs']['required']['svcs'][] = $svc;
#}
#$data['config']['svcs']['required']['svcs'] = array('dnsmasq', 'nginx', );


/*
# Required packages
$pkg = 'dnsmasq';
$data['config']['svcs']['required'] = array(
    'name' => $pkg,
    'pkg' => $pkg,
    'svc' => $pkg,
);

$pkg = 'nfs';
$data['config']['svcs']['required'][] = array(
    'name' => $pkg,
    'pkg' => $pkg .'-kernel-server',
    'svc' => $pkg . '-server',
);

$pkg = 'nginx';
$data['config']['svcs']['required'][] = array(
    'name' => $pkg,
    'pkg' => $pkg,
    'svc' => $pkg,
);
*/

/**
 * Check if di tftp is setup correctly
 */
if(is_file('/etc/di-netboot-assistant/di-netboot-assistant.conf')) {
    $di = shRaw('cat /etc/di-netboot-assistant/di-netboot-assistant.conf | grep TFTP_ROOT', $data);
    if(in_array('TFTP_ROOT=' . $data['config']['tftp'], $di)) {
        #echo 'di tftp found';
    } else {
        echo 'sudo edit /etc/di-netboot-assistant/di-netboot-assistant.conf and set TFTP_ROOT to TFTP_ROOT=' . $data['config']['tftp'];
    }
}
/**
 * Check if nfs port enabled for ufw or nfs boot will not work
    # Options for rpc.mountd.
    # If you have a port-based firewall, you might want to set up
    # a fixed port here using the --port option. For more information, 
    # see rpc.mountd(8) or http://wiki.debian.org/SecuringNFS
    # To disable NFSv4 on the server, specify '--no-nfs-version 4' here
    RPCMOUNTDOPTS="--manage-gids --port 20490"
 */
if(is_file('/etc/default/nfs-kernel-server')) {
    $nfs = shRaw('cat /etc/default/nfs-kernel-server | grep RPCMOUNTDOPTS', $data);
    $nfs = $nfs[0];
    if(strpos($nfs, '--port 20490') !== false) {
        #echo 'nfs port found' . $nfs . "<br />\n";
    } else {
        echo 'nfs ' . $nfs . "<br />\n";
        echo 'sudo edit /etc/default/nfs-kernel-server and add --port 20490 like: RPCMOUNTDOPTS="--manage-gids --port 20490"' . "<br />\n";
    }
}

$win32 = is_file($data['config']['ipxe']['root'] . '/wimboot.i386');
$win64 = is_file($data['config']['ipxe']['root'] . '/wimboot');

echo '<hr />Windows: it boots into the Windows installer <a target="_blank" href="https://ipxe.org/wimboot">https://ipxe.org/wimboot</a>' . "<br />\n";
echo 'TODO more steps: <a target="_blank" href="https://ipxe.org/howto/winpe">https://ipxe.org/howto/winpe</a>' . "<br />\n";
echo 'Windows x32 support: ' . ($win32 ? 'yes <a href="?install=wimboot32">reinstall</a>' : 'no <a href="?install=wimboot32">install</a>') . "<br />\n";
echo 'Windows x64 support: ' . ($win64 ? 'yes <a href="?install=wimboot64">reinstall</a>' : 'no <a href="?install=wimboot64">install</a>') . "<br />\n";

echo '<hr />';


echo 'TODO router';

require_once('./html/foot.php');