<?php

# https://docs.fedoraproject.org/en-US/fedora-coreos/live-booting-ipxe/
#
/*

#!ipxe

set STREAM stable
set VERSION 33.20210328.3.0
set CONFIGURL https://example.com/config.ign

set BASEURL https://builds.coreos.fedoraproject.org/prod/streams/${STREAM}/builds/${VERSION}/x86_64

kernel ${BASEURL}/fedora-coreos-${VERSION}-live-kernel-x86_64 initrd=main coreos.live.rootfs_url=${BASEURL}/fedora-coreos-${VERSION}-live-rootfs.x86_64.img ignition.firstboot ignition.platform.id=metal ignition.config.url=${CONFIGURL} systemd.unified_cgroup_hierarchy=0
initrd --name main ${BASEURL}/fedora-coreos-${VERSION}-live-initramfs.x86_64.img

boot

*/
# Booting the live PXE image requires at least 2 GiB of RAM
# with the coreos.live.rootfs_url kernel argument, and 3 GiB otherwise.

function fedora(&$data) {

    foreach($data['images']['mountedToLower'] as $i => $iso) {
        
        if(strpos($iso, 'fedora') !== false) {

            # real iso
            $iso = $data['images']['mounted'][$i];

            $isoKey = array_search($iso, $data['images']['iso']);
            $isoPath = $data['images']['isoPath'][$isoKey];
            $isoHttpPath = '';//str_replace($data['config']['http_root'], $data['config']['http'], $isoPath);
/*
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
*/
            # iPXE NFS
            $kernel = 'vmlinuz';
            $initrd = 'initrd.img';
            $path = $data['config']['mounted'] . '/'. $iso . '/images/pxeboot';
            $pathHttp = $data['config']['http']['mnt'] . '/'. $iso . '/images/pxeboot';

            /*
            label linux
                menu label ^Start Fedora-Workstation-Live 34
                kernel vmlinuz
                append initrd=initrd.img root=live:CDLABEL=Fedora-WS-Live-34-1-2  rd.live.image quiet
            */
/*
            if(is_dir($path)) {
                $_args = array();
                $_args[] = $kernel;
                $_args[] = 'initrd=' . $initrd;
                $_args[] = 'root=live:CDLABEL=' . $iso . ' rd.live.image'; # quiet
                $_args[] = 'root=/dev/nfs';
                $_args[] = 'netboot=nfs';
                $_args[] = 'nfsroot=' . $data['config']['nfs']['ip'] . ':' . $data['config']['mounted'] . '/' . $iso;
                $_args[] = 'ip=dhcp';
                #$_args[] = 'splash quiet';
                #$_args[] = '--';
                $args = implode(' ', $_args);

                $data['menu']['pb']['ipxe']['menu'][] = "item " . $iso . '_nfs ' . $iso . ' NFS LIVE';
                $data['menu']['pb']['ipxe']['body'][] = ':' . $iso . '_nfs ';
                $data['menu']['pb']['ipxe']['body'][] = 'kernel ' . $pathHttp . '/' . $kernel . ' || read void';
                $data['menu']['pb']['ipxe']['body'][] = 'initrd ' . $pathHttp . '/' . $initrd . ' || read void';
                $data['menu']['pb']['ipxe']['body'][] = 'imgargs  ' . $args . '|| read void';
                $data['menu']['pb']['ipxe']['body'][] = 'boot || goto failed';
                $data['menu']['pb']['ipxe']['body'][] = 'goto start';
                $data['menu']['pb']['ipxe']['body'][] = ''; # for empty line
            }
*/
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
