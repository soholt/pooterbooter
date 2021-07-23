<?php

function clonezilla(&$data) {

    foreach($data['images']['mountedToLower'] as $i => $iso) {

        if(strpos($iso, 'clonezilla') !== false) {

            $append = 'boot=live username=user union=overlay config components noswap edd=on nomodeset nodmraid locales=en_US.UTF-8 keyboard-layouts=en ocs_live_run="ocs-live-general" ocs_live_extra_param="" ocs_live_batch=no net.ifnames=0 fetch=' . $data['config']['http']['mnt'] . '/' . $iso . '/live/filesystem.squashfs';

            $linux = 'vmlinuz';
            $initrd = 'initrd.img';
            $path = $data['config']['http']['mnt'] . '/' . $iso . '/live';

            #echo " + clonezilla";
            $data['menu']['pb']['pxe'][] =  "\n\nLABEL " . $i . "\n\tMENU LABEL " . ' clonezilla Live (Ramdisk)';
            $data['menu']['pb']['pxe'][]= "\n\tKERNEL ::mnt/" . $iso . "/live/vmlinuz";
            $data['menu']['pb']['pxe'][]= "\n\tINITRD ::mnt/" . $iso . "/live/initrd.img";
            $data['menu']['pb']['pxe'][]= "\n\tAPPEND " . $append;
            $data['menu']['pb']['pxe'][] = ''; # for empty line

            # iPXE
            $data['menu']['pb']['ipxe']['menu'][] = "item " . $iso . ' clonezilla Live (Ramdisk) ' . $iso;
            $data['menu']['pb']['ipxe']['body'][] = ':' . $iso;
            $data['menu']['pb']['ipxe']['body'][] = 'kernel ' . $path . '/' . $linux .' || read void';
            $data['menu']['pb']['ipxe']['body'][] = 'initrd ' . $path . '/' . $initrd .' || read void';
            $data['menu']['pb']['ipxe']['body'][] = 'imgargs ' . $linux . ' initrd=' . $initrd . ' ' . $append;
            $data['menu']['pb']['ipxe']['body'][] = 'boot || goto failed';
            $data['menu']['pb']['ipxe']['body'][] = 'goto start';
            $data['menu']['pb']['ipxe']['body'][] = ''; # for empty line
        }
    }
}
