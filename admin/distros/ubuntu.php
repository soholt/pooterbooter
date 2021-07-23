<?php

/* https://ipxe.org/appnote/ubuntu_live
#!ipxe
  
  set server_ip 192.168.100.1
  set nfs_path /srv/nfs/ubuntu-14.04.1-desktop-amd64
  kernel nfs://${server_ip}${nfs_path}/casper/vmlinuz.efi || read void
  initrd nfs://${server_ip}${nfs_path}/casper/initrd.lz || read void
  imgargs vmlinuz.efi initrd=initrd.lz root=/dev/nfs boot=casper netboot=nfs nfsroot=${server_ip}:${nfs_path} ip=dhcp splash quiet -- || read void
  boot || read void
*/

function ubuntu(&$data) {
    
    foreach($data['images']['mountedToLower'] as $i => $iso) {

        if(strpos($iso, 'ubuntu') !== false) {

            # real iso
            $iso = $data['images']['mounted'][$i];

            $isoKey = array_search($iso, $data['images']['iso']);
            $isoPath = $data['images']['isoPath'][$isoKey];
            $isoHttpPath = '';//str_replace($data['config']['http_root'], $data['config']['http'], $isoPath);

            # pxe
            $data['menu']['pb']['pxe'][] = "LABEL " . $iso;// . $i;
            $data['menu']['pb']['pxe'][] = "\tMENU LABEL " . $iso . ' - LIVE, full iso download';
            $data['menu']['pb']['pxe'][] = "\tKERNEL ::www/mnt/" . $iso . "/casper/vmlinuz";
            $data['menu']['pb']['pxe'][] = "\tINITRD ::www/mnt/" . $iso . "/casper/initrd";
            $data['menu']['pb']['pxe'][] = "\tAPPEND root=/dev/ram0 ip=dhcp url=" . $isoHttpPath;#ramdisk_size=1500000 
            $data['menu']['pb']['pxe'][] = "\tTEXT HELP";
            $data['menu']['pb']['pxe'][] = "\tSlow on 100M eth, ok on gigabit";
            $data['menu']['pb']['pxe'][] = "\tENDTEXT";
            $data['menu']['pb']['pxe'][] = '';
            
            # iPXE NFS
            $kernel = 'vmlinuz';
            $initrd = 'initrd';
            $path = $data['config']['http']['mnt'] . '/'. $iso . '/casper';
            
            $_args = array();
            $_args[] = $kernel;
            $_args[] = 'initrd=' . $initrd;
            $_args[] = 'root=/dev/nfs';
            $_args[] = 'boot=casper';
            $_args[] = 'netboot=nfs';
            $_args[] = 'nfsroot=' . $data['config']['nfs']['ip'] . ':' . $data['config']['mounted'] . '/' . $iso;
            $_args[] = 'ip=dhcp';
            #$_args[] = 'splash quiet';
            $_args[] = '--';
            $args = implode(' ', $_args);

            $data['menu']['pb']['ipxe']['menu'][] = "item " . $iso . '_nfs ' . $iso . ' NFS LIVE';
            $data['menu']['pb']['ipxe']['body'][] = ':' . $iso . '_nfs ';
            $data['menu']['pb']['ipxe']['body'][] = 'kernel ' . $path . '/' . $kernel . ' || read void';
            $data['menu']['pb']['ipxe']['body'][] = 'initrd ' . $path . '/' . $initrd . ' || read void';
            $data['menu']['pb']['ipxe']['body'][] = 'imgargs  ' . $args . '|| read void';
            $data['menu']['pb']['ipxe']['body'][] = 'boot || goto failed';
            $data['menu']['pb']['ipxe']['body'][] = 'goto start';
            $data['menu']['pb']['ipxe']['body'][] = ''; # for empty line

            # iPXE SAN ISO
/*
            $data['menu']['pb']['ipxe']['menu'][] = "item " . $iso . '_san ' . $iso . '_san_iso ';
            $data['menu']['pb']['ipxe']['body'][] = ':' . $iso . '_san ';
            $data['menu']['pb']['ipxe']['body'][] = 'sanboot ' . $isoHttpPath;
            $data['menu']['pb']['ipxe']['body'][] = 'boot || goto failed';
            $data['menu']['pb']['ipxe']['body'][] = 'goto start';
            $data['menu']['pb']['ipxe']['body'][] = ''; # for empty line
*/

        }
    }
}
