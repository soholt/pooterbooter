<?php
/**
 * © 2021 Gintaras Valatka https://github.com/soholt/
 */
session_start();
ini_set('display_errors', 1);
ini_set('display_startup_errors', 1);
error_reporting(E_ALL);

//$data = array(); now in index
// so it is shown first
$data['debug'] = array();
$data['images'] = array();
$data['menu'] = array();

/**
 * Get ip
 */
if(!isset($_SERVER['SERVER_ADDR'])) { // unavail in cli
    $_ip = $_SERVER['HTTP_HOST']; # 192.168.1.111:3000
    $_ip = explode(':', $_ip);
    $ip = $_ip[0];
} else {
    $ip = $_SERVER['SERVER_ADDR'];
}

# tftp ip custom or from above
#$data['config']['ip'] = '192.168.1.11';
$data['config']['ip'] = $ip;

# tftp root
# this path also goes into /etc/exports for NFS
$data['config']['tftp'] = '/srv/tftp';

# NFS
$data['config']['nfs']['ip'] = $ip;
$data['config']['nfs']['root'] = $data['config']['nfs']['ip'] . ':' . $data['config']['tftp'];

# config http/s and port
$data['config']['http']['port'] = '8080';
$data['config']['http']['root']= $data['config']['tftp'] . '/www';
$data['config']['http']['www'] = 'http://' . $data['config']['ip'] . ':' . $data['config']['http']['port'];
$data['config']['http']['iso'] = $data['config']['http']['www'] . '/iso';
$data['config']['http']['mnt'] = $data['config']['http']['www'] . '/mnt';


# dont touch below unless you know what you are doing
# this path goes into /etc/exports for NFS and also for HTTP root
$data['config']['images'] = $data['config']['tftp'] . '/www/iso';
$data['config']['mounted'] = $data['config']['tftp'] . '/www/mnt';
# .iso images (.img)


#$data['config']['nfs_ip'] = $data['config']['ip'];
#$data['config']['nfs_root'] = $data['config']['nfs_ip'] . ':' . $data['config']['tftp'];


$data['config']['pxe']['default'] = 'menu.c32'; # or 'vesamenu.c32'
#$data['config']['pxe']['default'] = 'vesamenu.c32'; # or just menu.c32
$data['config']['pxe']['timeout'] = 50;
$data['config']['pxe']['ontimeout'] = 1;
$data['config']['pxe']['title'] = '@' . gethostname(); # add org or domain name if desired

$data['config']['ipxe']['root'] = $data['config']['tftp'] . '/www/ipxe';
$data['config']['ipxe']['http'] = $data['config']['http']['www'] . '/ipxe';
#$data['config']['ipxe']['www'] = 'http://' . $data['config']['ip'] . '/ipxe';
#$data['config']['ipxe'] = 'http://' . $data['config']['ip'] . '/ipxe';


// comment it out to disable 'boot' and  from HDD
#$data['config']['ipxe']['boot'] = 'archlinux-2021.06.01-x86_64.iso_gin';
#'fedora-workstation-live-x86_64-34-1.2.iso_iscsi';
#'ubuntu-18.04.5-desktop-amd64.iso';
#'fedora-workstation-live-x86_64-34-1.2.iso_iscsi';
#'ubuntu-21.04-desktop-amd64.iso_http';

# Windows support https://ipxe.org/wimboot
# https://github.com/ipxe/wimboot/releases
$data['config']['ipxe']['wimboot32'] = 'https://github.com/ipxe/wimboot/releases/latest/download/wimboot.i386';
$data['config']['ipxe']['wimboot64'] = 'https://github.com/ipxe/wimboot/releases/latest/download/wimboot';

#$data['config']['di']['apt-cacher-ng'] = $ip . ':3142';
$data['config']['di']['root'] = $data['config']['tftp'] . '/d-i/n-a';
$data['config']['di']['initrd']['locale'] = 'en_GB'; // TODO get list de_DE
$data['config']['di']['initrd']['install'] = 'aptitude,auto-apt-proxy,htop,git,mc,openssh-server,ufw'; # build-essential,?etckeeper
# memtest86 memtest86+ linux-image-rt-amd64
$data['config']['di']['menu']['install'] = true;
$data['config']['di']['menu']['autoinstall'] = true;
$data['config']['di']['menu']['playbook'] = true;

$data['config']['di']['versions'] = array(
    # Debian https://www.debian.org/releases/
    'trixie' => 'Debian 13', 'trixie-gtk' => 'Debian 13',
    'bookworm' => 'Debian 12', 'bookworm-gtk' => 'Debian 12',
    'bullseye' => 'Debian 11', 'bullseye-gtk' => 'Debian 11',
    'buster' => 'Debian 10', 'buster-gtk' => 'Debian 10',
    'stretch' => 'Debian 9', 'stretch-gtk' => 'Debian 9',
    'jessie' => 'Debian 8', 'jessie-gtk' => 'Debian 8',
    'wheezy' => 'Debian 7', 'wheezy-gtk' => 'Debian 7',
    'squeeze' => 'Debian 6', 'squeeze-gtk' => 'Debian 6',
    'lenny' => 'Debian 5', 'lenny-gtk' => 'Debian 5',

    # Ubuntu https://releases.ubuntu.com/
    'focal' => 'Ubuntu 20.04 LTS',
    'bionic' => 'Ubuntu 18.04 LTS',
    'xenial' => 'Ubuntu 16.04 LTS',

    'zesty' => 'Ubuntu 17.04',
    'yakkety' => 'Ubuntu 16.10',
    'wily' => 'Ubuntu 15.10',
    'vivid' => 'Ubuntu 15.04',
);

$data['config']['di']['installed'] = is_dir($data['config']['tftp'] . '/d-i' );
$data['config']['ltsp']['installed'] = is_dir($data['config']['tftp'] . '/ltsp' );


$data['config']['svcs']['apt-cacher-ng']['pkg'] = 'apt-cacher-ng';
$data['config']['svcs']['apt-cacher-ng']['ports'] = array('3142/tcp');
$data['config']['svcs']['apt-cacher-ng']['www'] = 'http://' . $ip . ':3142';
$data['config']['svcs']['apt-cacher-ng']['required'] = true;

# sudo ufw app info Bonjour
$data['config']['svcs']['avahi-daemon']['pkg'] = 'avahi-daemon';
$data['config']['svcs']['avahi-daemon']['ports'] = array('5298', '5353/udp');
$data['config']['svcs']['avahi-daemon']['required'] = true;

# sudo ufw app info DNS
# dns 53 (udp & tcp)
# dhcp 67/udp
# tftp 69 (udp & tcp)
# dhcp proxy 4011/udp
$data['config']['svcs']['dnsmasq']['pkg'] = 'dnsmasq';
$data['config']['svcs']['dnsmasq']['ports'] = array('53', '69', '67/udp', '4011/udp');
$data['config']['svcs']['dnsmasq']['required'] = true;

# sudo ufw app info NFS
$data['config']['svcs']['nfs-server']['pkg'] = 'nfs-kernel-server'; 
$data['config']['svcs']['nfs-server']['ports'] = array('111', '2049', '20490');
$data['config']['svcs']['nfs-server']['required'] = true;

# 3000 - fot admin panel
$data['config']['svcs']['nginx']['pkg'] = 'nginx';
$data['config']['svcs']['nginx']['ports'] = array('3000/tcp', '8080/tcp');
$data['config']['svcs']['nginx']['required'] = true;


$data['config']['svcs']['ssh']['pkg'] = 'openssh-server';
$data['config']['svcs']['ssh']['ports'] = array('22/tcp');
$data['config']['svcs']['ssh']['required'] = true;

$data['config']['svcs']['ltsp']['pkg'] = 'ltsp';
$data['config']['svcs']['ltsp']['required'] = false;

# dnsmasq nfs-kernel-server nginx ipxe pxelinux syslinux-efi php-cli
$data['config']['pkgs']['debian']['required'] = array('auto-apt-proxy', 'avahi-utils', 'di-netboot-assistant', 'ipxe', 'pxelinux', 'syslinux', 'syslinux-efi', 'php-cli', 'ufw');// no 'pxe'
$data['config']['pkgs']['debian']['optional'] = array('deboostrap', 'sshfs');

// temp menu entries
$data['menu']['pb'] = array(); // pooterbooter menu
$data['menu']['di'] = array();
$data['menu']['ltsp'] = array();

$data['images'] = array();
$data['images']['iso'] = array();
$data['images']['isoPath'] = array();
$data['images']['isoToLower'] = array();
$data['images']['isoIdByName'] = array();
$data['images']['mounted'] = array();
$data['images']['mountedToLower'] = array();



if(isset($_REQUEST['debug'])) {
    if($_REQUEST['debug'] == '0') {
        $_SESSION['debug'] = false;
    } else {
        $_SESSION['debug'] = true;
    }
}

if(!isset($_SESSION['debug'])) $_SESSION['debug'] = false;

define("DEBUG", $_SESSION['debug']);
