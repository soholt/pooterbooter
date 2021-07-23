<?php
# ########################################################################
#
# Use a temporary package cache during installation, other default locale, install etckeeper.
#LABEL tmp pkg cache
#   MENU LABEL Debian stable (amd64) + tmp-pkg-cache + locale
#   kernel ::/d-i/n-a/stable/amd64/linux
#   append initrd=::/d-i/n-a/stable/amd64/initrd.gz locale?=de_DE mirror/http/proxy?=http://192.168.122.1:3142/ pkgsel/include=etckeeper preseed/late_command="rm -fv /target/etc/apt/apt.conf" ---
#
#
# Install stable automatically.
# For details consult '/usr/share/doc/di-netboot-assistant/README.preseed'.
#
#LABEL autoinstall
#   MENU LABEL Debian stable (amd64) + preseed
#   kernel ::/d-i/n-a/stable/amd64/linux
#   append initrd=::/d-i/n-a/stable/amd64/initrd.gz auto=true priority=critical url=tftp://installbox ---
#
#LABEL example.yml
#   MENU LABEL Debian stable (amd64) + preseed + example.yml
#   kernel ::/d-i/n-a/stable/amd64/linux
#   append initrd=::/d-i/n-a/stable/amd64/initrd.gz auto=true priority=critical hostname=example url=tftp://installbox playbook=example.yml ---
#
# ########################################################################

/* https://ipxe.org/appnote/ubuntu_live
#!ipxe
  
  set server_ip 192.168.100.1
  set nfs_path /srv/nfs/ubuntu-14.04.1-desktop-amd64
  kernel nfs://${server_ip}${nfs_path}/casper/vmlinuz.efi || read void
  initrd nfs://${server_ip}${nfs_path}/casper/initrd.lz || read void
  imgargs vmlinuz.efi initrd=initrd.lz root=/dev/nfs boot=casper netboot=nfs nfsroot=${server_ip}:${nfs_path} ip=dhcp splash quiet -- || read void
  boot || read void
*/

$data['menu']['pb']['ipxe']['di']['menu'] = array();
$data['menu']['pb']['ipxe']['di']['body'] = array();

foreach(diInstalled($data) as $img => $arches) {

    $_inst = array();
    $_inst[] = 'linux initrd=initrd.gz';
    if(strpos($img, 'gtk')) $_inst[] = 'vga=788'; # needed for gtk
    $_inst[] = 'hostname=pooterbooter';# . 'TODO';
    $_inst[] = 'locale?=' . $data['config']['di']['initrd']['locale'];
    $_inst[] = 'mirror/http/proxy?=' . $data['config']['svcs']['apt-cacher-ng']['www'];
    $_inst[] = 'pkgsel/include=' . $data['config']['di']['initrd']['install'];
    $_inst[] = "preseed/late_command='rm -fv /target/etc/apt/apt.conf'";
    $_inst[] = 'ip=dhcp';
    #$_inst[] = 'splash quiet';
    $_inst[] = '--';
    $_inst[] = '|| read void';
    $install = implode(' ', $_inst);

    #$_url = 'url=' . $data['config']['http']['www'];
    $_url = 'url=' . $data['config']['ipxe']['http'] . '/di';
    $_version = array_key_exists($img, $data['config']['di']['versions']) ? $data['config']['di']['versions'][$img] : 'Debian';

    if(strpos($_version, 'Debian') !== false) $_url .= '/debian/preseed.cfg';
    if(strpos($_version, 'Ubuntu') !== false) $_url .= '/ubuntu/preseed.cfg';

    $_auto = array();
    $_auto[] = 'linux initrd=initrd.gz';
        $_auto[] = 'hostname=pooterbooter';
        $_auto[] = 'locale?=' . $data['config']['di']['initrd']['locale'];
    $_auto[] = 'auto=true';
    $_auto[] = 'priority=critical';
    #$_auto[] = 'url=tftp://installbox';
    $_auto[] = $_url;
    #$auto[] = 'playbook=example.yml';
    $_auto[] = '---';
    $_auto[] = '|| read void';
    $autoinstall = implode(' ', $_auto);

    $_play = array();
    $_play[] = 'linux initrd=initrd.gz';
        $_play[] = 'hostname=pooterbooter';
        $_play[] = 'locale?=' . $data['config']['di']['initrd']['locale'];
    $_play[] = 'auto=true';
    $_play[] = 'priority=critical';
    #$_play[] = 'url=tftp://installbox';
    $_play[] = $_url; # it will look for ipxe/debian-installer file
    $_play[] = 'playbook=example.yml';
    $_play[] = '---';
    $_play[] = '|| read void';
    $playbook = implode(' ', $_play);

    $arch = 'i386';
    if(in_array($arch, $arches)) {
        if($data['config']['di']['menu']['install']) diIpxeMenu($img, $arch, $install, 'install', $data);
        if($data['config']['di']['menu']['autoinstall']) diIpxeMenu($img, $arch, $autoinstall, 'autoinstall', $data);
        if($data['config']['di']['menu']['playbook']) diIpxeMenu($img, $arch, $playbook, 'playbook', $data);
    }

    $arch = 'amd64';
    if(in_array($arch, $arches)) {
        if($data['config']['di']['menu']['install']) diIpxeMenu($img, $arch, $install, 'install', $data);
        if($data['config']['di']['menu']['autoinstall']) diIpxeMenu($img, $arch, $autoinstall, 'autoinstall', $data);
        if($data['config']['di']['menu']['playbook']) diIpxeMenu($img, $arch, $playbook, 'playbook', $data);
    }
}

function diIpxeMenu($img, $arch, $args, $prefix, &$data) {
    $version = array_key_exists($img, $data['config']['di']['versions']) ? $data['config']['di']['versions'][$img] : 'Debian';
    $version .= ' ' . $arch;

    $data['menu']['pb']['ipxe']['di']['menu'][] = 'item di_' . $prefix . '_' . $img . ' DI ' . $prefix . ' '. $img . ' ' . $version;
    $data['menu']['pb']['ipxe']['di']['body'][] = ':di_' . $prefix . '_' . $img;
    # kernel ::/d-i/n-a/testing/amd64/linux
    # initrd ::/d-i/n-a/testing/amd64/initrd.gz
    $data['menu']['pb']['ipxe']['di']['body'][] = 'kernel ' . $data['config']['http']['www'] . '/d-i/n-a/' . $img . '/' . $arch . '/linux || read void';
    $data['menu']['pb']['ipxe']['di']['body'][] = 'initrd ' . $data['config']['http']['www'] . '/d-i/n-a/' . $img . '/' . $arch . '/initrd.gz || read void';
    $data['menu']['pb']['ipxe']['di']['body'][] = 'imgargs ' . $args;
    $data['menu']['pb']['ipxe']['di']['body'][] = 'boot || goto failed';
    $data['menu']['pb']['ipxe']['di']['body'][] = 'goto start';
    $data['menu']['pb']['ipxe']['di']['body'][] = ''; # for empty line
}
/*
#LABEL autoinstall
#   MENU LABEL Debian stable (amd64) + preseed
#   kernel ::/d-i/n-a/stable/amd64/linux
#   append initrd=::/d-i/n-a/stable/amd64/initrd.gz auto=true priority=critical url=tftp://installbox ---
#
#LABEL example.yml
#   MENU LABEL Debian stable (amd64) + preseed + example.yml
#   kernel ::/d-i/n-a/stable/amd64/linux
#   append initrd=::/d-i/n-a/stable/amd64/initrd.gz auto=true priority=critical hostname=example url=tftp://installbox playbook=example.yml ---

*/
