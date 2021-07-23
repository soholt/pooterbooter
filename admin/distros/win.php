<?php

/**
 * The function must be the same name as the file
 */
function win(&$data) {
    // TODO check if initrd exist, script to compress initrd and cp vmlinuz?
    #echo " + win";

    foreach($data['images']['mountedToLower'] as $i => $iso) {
        
        #if(strpos($iso, 'win') !== false) {
        if(strpos($iso, 'win10') !== false) {

            # real iso
            $iso = $data['images']['mounted'][$i];

            if(strpos($iso, 'x64') !== false) {
                $data['menu']['pb']['ipxe']['menu'][] = 'item ' . $iso . '_amd64 ' . $iso . ' x64 amd64';
                $data['menu']['pb']['ipxe']['body'][] = ':' . $iso . '_amd64';
                
                $data['menu']['pb']['ipxe']['body'][] = 'kernel ${hostname}/ipxe/wimboot';
                $data['menu']['pb']['ipxe']['body'][] = 'initrd ${hostname}/mnt/' . $iso . '/boot/bcd         BCD';
                $data['menu']['pb']['ipxe']['body'][] = 'initrd ${hostname}/mnt/' . $iso . '/boot/boot.sdi    boot.sdi';
                $data['menu']['pb']['ipxe']['body'][] = 'initrd ${hostname}/mnt/' . $iso . '/sources/boot.wim boot.wim';

                #$data['menu']['pb']['ipxe']['body'][] = 'kernel ' . $data['config']['ipxe']['http'] . '/' . 'wimboot';
                #$data['menu']['pb']['ipxe']['body'][] = 'initrd ' . $data['config']['http']['mnt'] . '/' . $iso . '/boot/bcd         BCD';
                #$data['menu']['pb']['ipxe']['body'][] = 'initrd ' . $data['config']['http']['mnt'] . '/' . $iso . '/boot/boot.sdi    boot.sdi';
                #$data['menu']['pb']['ipxe']['body'][] = 'initrd ' . $data['config']['http']['mnt'] . '/' . $iso . '/sources/boot.wim boot.wim';

                $data['menu']['pb']['ipxe']['body'][] = 'boot || goto failed';
                $data['menu']['pb']['ipxe']['body'][] = 'goto start';
                $data['menu']['pb']['ipxe']['body'][] = ''; # for empty line
            }
            
            if(strpos($iso, 'x32') !== false) {
                $data['menu']['pb']['ipxe']['menu'][] = 'item ' . $iso . '_i386 ' . $iso . ' x32 i386';
                $data['menu']['pb']['ipxe']['body'][] = ':' . $iso . '_i386';

                $data['menu']['pb']['ipxe']['body'][] = 'kernel ${hostname}/ipxe/wimboot.i386';
                $data['menu']['pb']['ipxe']['body'][] = 'initrd ${hostname}/mnt/' . $iso . '/boot/bcd         BCD';
                $data['menu']['pb']['ipxe']['body'][] = 'initrd ${hostname}/mnt/' . $iso . '/boot/boot.sdi    boot.sdi';
                $data['menu']['pb']['ipxe']['body'][] = 'initrd ${hostname}/mnt/' . $iso . '/sources/boot.wim boot.wim';
                #$data['menu']['pb']['ipxe']['body'][] = 'kernel ' . $data['config']['ipxe']['http'] . '/' . 'wimboot.i386';
                #$data['menu']['pb']['ipxe']['body'][] = 'initrd ' . $data['config']['http']['mnt'] . '/' . $iso . '/boot/bcd         BCD';
                #$data['menu']['pb']['ipxe']['body'][] = 'initrd ' . $data['config']['http']['mnt'] . '/' . $iso . '/boot/boot.sdi    boot.sdi';
                #$data['menu']['pb']['ipxe']['body'][] = 'initrd ' . $data['config']['http']['mnt'] . '/' . $iso . '/sources/boot.wim boot.wim';
                
                $data['menu']['pb']['ipxe']['body'][] = 'boot || goto failed';
                $data['menu']['pb']['ipxe']['body'][] = 'goto start';
                $data['menu']['pb']['ipxe']['body'][] = ''; # for empty line
            }
        }
    }
}
