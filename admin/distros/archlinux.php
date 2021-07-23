<?php

function archlinux(&$data) {

    foreach($data['images']['mountedToLower'] as $i => $iso) {

        if(strpos($iso, 'archlinux') !== false) {

            # real iso
            $iso = $data['images']['mounted'][$i];
            
            $data['menu']['pb']['pxe'][] = "LABEL " . $iso;// . $i;
            $data['menu']['pb']['pxe'][] = "\tMENU LABEL " . $iso;
            $data['menu']['pb']['pxe'][] = "\tKERNEL ::www/mnt/" . $iso . "/arch/boot/x86_64/vmlinuz-linux";
            $data['menu']['pb']['pxe'][] = "\tINITRD ::www/mnt/" . $iso . "/arch/boot/intel-ucode.img,::www/mnt/" . $iso . "/arch/boot/amd-ucode.img,::www/mnt/" . $iso . "/arch/boot/x86_64/initramfs-linux.img";
            $data['menu']['pb']['pxe'][] = "\tAPPEND archisobasedir=arch archiso_http_srv=" . $data['config']['http']['mnt'] . '/' . $iso . "/";
            #$data['menu']['pb']['pxe'][] = "\tAPPEND archisobasedir=arch archiso_nfs_srv=" . $data['config']['http'] . "/" . $iso . "/";
            $data['menu']['pb']['pxe'][] = "\tSYSAPPEND 3"; # no idea
            $data['menu']['pb']['pxe'][] = "\tTEXT HELP";
            $data['menu']['pb']['pxe'][] = "\tArch Linux (downloads .. via http)";
            $data['menu']['pb']['pxe'][] = "\tENDTEXT";
            $data['menu']['pb']['pxe'][] = '';
/*
            # iPXE http
            $linux = $data['config']['http']['mnt'] . '/' . $iso . '/arch/boot/x86_64/vmlinuz-linux';
            $initrd = $data['config']['http']['mnt'] . '/' . $iso . '/arch/boot/x86_64/initramfs-linux.img';
            $intel = $data['config']['http']['mnt'] . '/' . $iso . '/arch/boot/intel-ucode.img';
            $amd = $data['config']['http']['mnt'] . '/' . $iso . '/arch/boot/amd-ucode.img';

            $data['menu']['pb']['ipxe']['menu'][] = "item " . $iso . ' ' . $iso;
            $data['menu']['pb']['ipxe']['body'][] = ':' . $iso;
            $data['menu']['pb']['ipxe']['body'][] = 'kernel ' . $linux . ' || read void';
            $data['menu']['pb']['ipxe']['body'][] = 'initrd ' . $intel . ' || read void';
            $data['menu']['pb']['ipxe']['body'][] = 'initrd ' . $amd . ' || read void';
            $data['menu']['pb']['ipxe']['body'][] = 'initrd ' . $initrd . ' || read void';
            $data['menu']['pb']['ipxe']['body'][] = 'imgargs vmlinuz-linux initrd=intel-ucode.img,amd-ucode.img,initramfs-linux.img archisobasedir=arch archiso_http_srv=' . $data['config']['http']['mnt'] . '/' . $iso . '/';
            $data['menu']['pb']['ipxe']['body'][] = 'boot || goto failed';
            $data['menu']['pb']['ipxe']['body'][] = 'goto start';
            $data['menu']['pb']['ipxe']['body'][] = ''; # for empty line
*/
/*
            $data['menu']['pb']['ipxe']['menu'][] = "item " . $iso . '_san ' . $iso . '_san ';
            $data['menu']['pb']['ipxe']['body'][] = ':' . $iso . '_san ';
            $data['menu']['pb']['ipxe']['body'][] = 'sanboot ' . isoHttpPath($iso, $data);
            $data['menu']['pb']['ipxe']['body'][] = 'boot || goto failed';
            $data['menu']['pb']['ipxe']['body'][] = 'goto start';
            $data['menu']['pb']['ipxe']['body'][] = ''; # for empty line


            $data['menu']['pb']['ipxe']['menu'][] = "item " . $iso . '_iscsi ' . $iso . '_iscsi ';
            $data['menu']['pb']['ipxe']['body'][] = ':' . $iso . '_iscsi ';
            $data['menu']['pb']['ipxe']['body'][] = 'sanboot iscsi:192.168.5.5:::1:iqn.2021-06.lan.home.xw:archlinux';
            #$data['menu']['pb']['ipxe']['body'][] = 'sanboot --no-describe iscsi:192.168.5.5:::1:iqn.2021-06.lan.home.xw:archlinux';
            $data['menu']['pb']['ipxe']['body'][] = 'boot || goto failed';
            $data['menu']['pb']['ipxe']['body'][] = 'goto start';
            $data['menu']['pb']['ipxe']['body'][] = ''; # for empty line
*/
        }
    }
}
