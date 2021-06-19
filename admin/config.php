<?php
/**
 * © 2021 Gintaras Valatka https://github.com/soholt/
 */

//$data = array(); now in index
$data['debug'] = array();

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
$data['config']['tftp'] = '/srv/tftp';

# dont touch below unless you know what you are doing
# this path goes into /etc/exports for NFS and also for HTTP root
$data['config']['mounted'] = $data['config']['tftp'] . '/mnt';
# .iso images
$data['config']['images'] = $data['config']['tftp'] . '/mnt/iso';

$data['config']['nfs_ip'] = $data['config']['ip'];
$data['config']['nfs_root'] = $data['config']['nfs_ip'] . ':' . $data['config']['mounted'];
$data['config']['http_port'] = ''; # ':80' or ':4011' etc
$data['config']['http'] = 'http://' . $data['config']['ip'] . $data['config']['http_port'];

$data['config']['pxe']['default'] = 'menu.c32'; # or 'vesamenu.c32'
#$data['config']['pxe']['default'] = 'vesamenu.c32'; # or just menu.c32
$data['config']['pxe']['timeout'] = 50;
$data['config']['pxe']['ontimeout'] = 0;
$data['config']['pxe']['title'] = '@' . gethostname(); # add org or domain name if desired

$data['images'] = array();
$data['mounted'] = array();
$data['paths'] = array();
$data['imagesToLower'] = array();
$data['mountedToLower'] = array();
