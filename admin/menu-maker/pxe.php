<?php

function make_pxe_menu(&$data) {
$i = 0;

$head  = "default " . $data['config']['pxe']['default'];
$head .= "\nprompt 0";
$head .= "\ntimeout " . $data['config']['pxe']['timeout'];
$head .= "\nontimeout " . $data['config']['pxe']['ontimeout'];

$bios = "\n\nmenu title -=pooterbooter=- bios" . $data['config']['pxe']['title'];
$bios .= "\n\nLABEL " . $i . "\n\tMENU LABEL Local Disk";
$bios .= "\n\tLOCALBOOT\n\n";

$efi32 = "\n\nmenu title -=pooterbooter=- efi32" . $data['config']['pxe']['title'];
$efi32 .= "\n\nLABEL " . $i . "\n\tMENU LABEL Local Disk";
$efi32 .= "\n\tLOCALBOOT\n\n";

$efi64 = "\n\nmenu title -=pooterbooter=- efi64" . $data['config']['pxe']['title'];
$efi64 .= "\n\nLABEL " . $i . "\n\tMENU LABEL Local Disk";
$efi64 .= "\n\tLOCALBOOT\n\n";

#$data['config']['pxe']['menu'] = 
$biosPath = $data['config']['tftp'] . '/pxe/modules/bios/pxelinux.cfg/default';
$efi32Path = $data['config']['tftp'] . '/pxe/modules/efi32/pxelinux.cfg/default';
$efi64Path = $data['config']['tftp'] . '/pxe/modules/efi64/pxelinux.cfg/default';
#$macbootPath = $data['config']['tftp'] . '/macboot';

if(isset($data['menu']['pb']['pxe'])) $bios .= implode("\n", $data['menu']['pb']['pxe']);


    /** TODO */
    $foot  = '';
    #$foot .= "\n\nLABEL memtest";
    #$foot .= "\n\tMENU LABEL Memtest86+";
    #$foot .= "\n\tKERNEL memtest";

    #$foot .= "\n\nLABEL memdisk";
    #$foot .= "\n\tMENU LABEL memdisk";
    #$foot .= "\n\tKERNEL memdisk";
    #$foot .= "\n\tINITRD ::mnt/Fedora-Workstation-Live-x86_64-34-1.2.iso/";
    #$foot .= "\n\tAPPEND iso raw";

    # serial # https://alexforencich.com/wiki/en/linux/diskless_pxe_nfs
    #$foot .= "\n\nLABEL memtest-console";
    #$foot .= "\n\tMENU LABEL Memtest86+ (serial console)";
    #$foot .= "\n\tKERNEL memtest";
    #$foot .= "\n\tAPPEND console=ttyS1,115200n8";

# Write config
file_put_contents ($biosPath, $head . $bios . $foot);
file_put_contents ($efi32Path, $head . $efi32 . $foot);
file_put_contents ($efi64Path, $head . $efi64 . $foot);
#file_put_contents ($macbootPath, $head . $macboot . $foot);
}