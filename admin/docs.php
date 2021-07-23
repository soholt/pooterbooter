<?php
#require_once('functions.php');
require_once('config.php');

require_once('html/head.php');
require_once('html/menu.php');

# --- Note ltsp version: (2021)

# debian stable 10 buster: version 5 - aka old
# debian testing 11 byllseye: version 21 - aka new

$data['docs'] = array();

$data['docs']['pxe'][] = array(
    'rem' => 'Installing Debian using network booting',
    'url' => 'https://wiki.debian.org/PXEBootInstall'
);

$data['docs']['pxe'][] = array(
    'rem' => 'archlinux Preboot Execution Environment',
    'url' => 'https://wiki.archlinux.org/title/Preboot_Execution_Environment'
);

$data['docs']['pxe'][] = array(
    'rem' => 'Clonezilla Live on PXE Server',
    'url' => 'https://clonezilla.org/livepxe.php'
);


$data['docs']['ipxe'][] = array(
    'rem' => 'Booting live Fedora CoreOS via iPXE',
    'url' => 'https://docs.fedoraproject.org/en-US/fedora-coreos/live-booting-ipxe/'
);

$data['docs']['ipxe'][] = array(
    'rem' => 'Fedora Producing an Ignition Config',
    'url' => 'https://docs.fedoraproject.org/en-US/fedora-coreos/producing-ign/'
);

$data['docs']['dnsmasq'][] = array(
    'rem' => 'Installing Debian using network booting',
    'url' => 'https://wiki.debian.org/dnsmasq'
);

$data['docs']['nfs'][] = array(
    'rem' => 'Network File System (NFS)',
    'url' => 'https://wiki.debian.org/NFS'
);
$data['docs']['nfs'][] = array(
    'rem' => 'NFS Server Setup',
    'url' => 'https://wiki.debian.org/NFSServerSetup'
);

$data['docs']['di'][] = array(
    'rem' => 'DebianInstaller NetbootAssistant',
    'url' => 'https://wiki.debian.org/DebianInstaller/NetbootAssistant'
);

$data['docs']['di'][] = array(
    'rem' => 'DebianInstallerPreseed',
    'url' => 'https://wiki.debian.org/DebianInstaller/Preseed'
);

$data['docs']['di'][] = array(
    'rem' => 'auto preseed.cfg WARNING: don\'t just blithely try this on a machine you care about -- it will cheerfully re-partition the hard disk, and overwrite your data, WITHOUT asking for permission once you\'ve launched it, and that can include things like external USB-attached hard drives if you\'re unlucky, so unplug them if you want to keep them, unless you mean for it to use them -- you have been warned.',
    'url' => 'http://hands.com/d-i/'
);

$data['docs']['di'][] = array(
    'rem' => 'U-boot is a bootloader for embedded boards. Most boards supported in the Debian packages of u-boot are ARM based.',
    'url' => 'https://wiki.debian.org/U-boot'
);


$data['docs']['debootstrap'][] = array(
    'rem' => 'Debootstrap',
    'url' => 'https://wiki.debian.org/Debootstrap'
);

$data['docs']['ltsp'][] = array(
    'rem' => 'Linux Terminal Server Project (LTSP) wiki',
    'url' => 'https://wiki.debian.org/LTSP'
);

$data['docs']['ltsp'][] = array(
    'rem' => 'LTSP5 How To',
    'url' => 'https://wiki.debian.org/LTSP/Howto'
);

$data['docs']['ltsp'][] = array(
    'rem' => 'Debian Edu How To Ltsp Diskless Workstation',
    'url' => 'https://wiki.debian.org/DebianEdu/HowTo/LtspDisklessWorkstation'
);

$data['docs']['ltsp'][] = array(
    'rem' => 'Debian Edu Ltsp-server packages',
    'url' => 'https://blends.debian.org/edu/tasks/ltsp-server',
);

$data['docs']['ltsp'][] = array(
    'rem' => 'LTSP wiki',
    'url' => 'https://github.com/ltsp/ltsp/wiki'
);

$data['docs']['ltsp'][] = array(
    'rem' => 'LTSP Man pages',
    'url' => 'https://ltsp.org/man/'
);

$data['docs']['ltsp'][] = array(
    'rem' => 'LTSP Raspberry Pi OS',
    'url' => 'https://ltsp.org/docs/installation/raspios/'
);

$data['docs']['ltsp'][] = array(
    'rem' => 'LTSP Netboot clients',
    'url' => 'https://ltsp.org/docs/netboot-clients/
    ',
);

$docs = array_keys($data['docs']);
asort($docs);

echo '<h3>Docs</h3>';
echo '<table>' . "\n";
#echo '<tr><th>pkg</th><th>-</th><th>url</th></tr>' . "\n";
foreach($docs as $doc) {
    foreach($data['docs'][$doc] as $val) {
        echo '<tr><td>' . $doc . '</td><td>' . $val['rem'] . '</td><td><a target="_blank" href="' . $val['url'] . '">' . $val['url'] . '</a></td></tr>' . "\n";
        #echo $doc . ':: ' . $val['rem'] . ' <a href="' . $val['url'] . '">' . $val['url'] . '</a>' . "<br />\n";
    }
}
echo '</table>' . "\n";

#
#
#



require_once('html/foot.php');
