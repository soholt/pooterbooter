<?php
/**
 * © 2021 Gintaras Valatka https://github.com/soholt/
 */

/**
 * Generate default pxe menu
 */
//function menuRefresh($paths, $recipes, $mountPath, $mounted_http, &$debug) {
//data['supported'] = array_keys($recipes);

function pxeMenuMake(&$data) {
    $i = 0;

    $head  = "default " . $data['config']['pxe']['default'];
    $head .= "\nprompt 0";
    $head .= "\ntimeout " . $data['config']['pxe']['timeout'];
    $head .= "\nontimeout " . $data['config']['pxe']['ontimeout'];

    $bios = "\n\nmenu title -=pooterbooter=- bios" . $data['config']['pxe']['title'];
    $bios .= "\n\nLABEL " . $i . "\n\tMENU LABEL Local Disk";
    $bios .= "\n\tLOCALBOOT";

    $efi32 = "\n\nmenu title -=pooterbooter=- efi32" . $data['config']['pxe']['title'];
    $efi32 .= "\n\nLABEL " . $i . "\n\tMENU LABEL Local Disk";
    $efi32 .= "\n\tLOCALBOOT";

    $efi64 = "\n\nmenu title -=pooterbooter=- efi64" . $data['config']['pxe']['title'];
    $efi64 .= "\n\nLABEL " . $i . "\n\tMENU LABEL Local Disk";
    $efi64 .= "\n\tLOCALBOOT";

    $macboot = "\n\nmenu title -=pooterbooter=- macboot" . $data['config']['pxe']['title'];
    $macboot .= "\n\nLABEL " . $i . "\n\tMENU LABEL Local Disk";
    $macboot .= "\n\tLOCALBOOT";
    #$head .= "\n\tKERNEL memdisk iso";
    #$head .= "\n\tINITRD ::mnt/iso/ubuntu/ubuntu-21.04-desktop-amd64.iso";
    #$head .= "\n\tAPPEND ramdisk_size=1500000 iso";

    $i++;
    foreach($data['mountedToLower'] as $key => $name) {
        
        $iso = $data['mounted'][$key];

        $isoIdByIsoName = array_search($iso, $data['images']);

        $path = $data['paths'][$isoIdByIsoName];
        $httpIsoPath = str_replace($data['config']['mounted'], $data['config']['http'], $path);
        
        // supported distros
        $supported = array_keys($data['recipes']);
        foreach($supported as $_type) {
            if(strpos($name, $_type) !== false) {
                $type = $_type;
            }
        }

        if(isset($type)) {
            switch($type) {

                case 'archlinux':
                    $bios .= "\n\nLABEL " . $i . "\n\tMENU LABEL " . $name;
                    $bios .= "\n\tKERNEL ::mnt/" . $iso . "/arch/boot/x86_64/vmlinuz-linux";
                    $bios .= "\n\tINITRD ::mnt/" . $iso . "/arch/boot/intel-ucode.img,::mnt/" . $iso . "/arch/boot/amd-ucode.img,::mnt/" . $iso . "/arch/boot/x86_64/initramfs-linux.img";
                    $bios .= "\n\tAPPEND archisobasedir=arch archiso_http_srv=" . $data['config']['http'] . "/" . $iso . "/";
                    #$bios .= "\n\tAPPEND archisobasedir=arch archiso_nfs_srv=" . $data['config']['http'] . "/" . $iso . "/";
                    $bios .= "\n\tSYSAPPEND 3"; # no idea
                    $bios .= "\n\tTEXT HELP";
                    $bios .= "\n\tArch Linux (downloads .. via http)";
                    $bios .= "\n\tENDTEXT";

                    # TODO
                    #$efi64 .= "\n\nLABEL " . $i . "\n\tMENU LABEL " . $name;
                    #$efi64 .= "\n\tKERNEL ::mnt/" . $iso . "/arch/boot/x86_64/vmlinuz-linux";
                    #$efi64 .= "\n\tINITRD ::mnt/" . $iso . "/arch/boot/intel-ucode.img,::mnt/" . $iso . "/arch/boot/amd-ucode.img,::mnt/" . $iso . "/arch/boot/x86_64/initramfs-linux.img";
                    #$efi64 .= "\n\tAPPEND archisobasedir=arch archiso_http_srv=" . $data['config']['http'] . "/" . $iso . "/";
                    #$efi64 .= "\n\tSYSAPPEND 3"; # no idea
                    #$efi64 .= "\n\tTEXT HELP";
                    #$efi64 .= "\n\tArch Linux (downloads .. via http)";
                    #$efi64 .= "\n\tENDTEXT";
                    break;

                case 'clonezilla':
                    $bios .= "\n\nLABEL " . $i . "\n\tMENU LABEL " . $name . ' Live (Ramdisk)';
                    $bios .= "\n\tKERNEL ::mnt/" . $iso . "/live/vmlinuz";
                    $bios .= "\n\tINITRD ::mnt/" . $iso . "/live/initrd.img";
                    $bios .= "\n\tAPPEND boot=live username=user union=overlay config components noswap edd=on nomodeset nodmraid locales=en_US.UTF-8 keyboard-layouts=en fetch=" . $data['config']['http'] . "/" . $iso . "/live/filesystem.squashfs";
                    //fetch=tftp://$serverIP/filesystem.squashfs
                    // unattended(read the docs): ocs_live_run="ocs-live-general"';
                    break;

                case 'debian':
                case 'firmware':
                    #if(strpos($path, 'netinst')) {
                        $bios .= "\n\nLABEL " . $i . "\n\tMENU LABEL " . $name;
                        $bios .= "\n\tKERNEL ::mnt/" . $iso . "/install.amd/vmlinuz";
                        $bios .= "\n\tINITRD ::mnt/" . $iso . "/install.amd/initrd.gz";
                        $bios .= "\n\tAPPEND ip=dhcp url=" . $data['config']['http'] . '/' .$iso;
                        # . '/' . 'live/';
                    #}
                    
                    $i++;
                    $bios .= "\n\nLABEL " . $i . "\n\tMENU LABEL " . $name . ' - GTK NFS';
                    $bios .= "\n\tKERNEL ::mnt/" . $iso . "/install.amd/vmlinuz";
                    $bios .= "\n\tINITRD ::mnt/" . $iso . "/install.amd/gtk/initrd.gz";
                    $bios .= "\n\tAPPEND ip=dhcp vga=788 root=/dev/nfs/" . $iso . " nfsroot=" . $data['config']['nfs_root'];
                    #$data['config']['images'] . $iso;

                    #$i++;
                    #$bios .= "\n\nLABEL " . $i . "\n\tMENU LABEL " . $name . ' (xen)';
                    #$bios .= "\n\tKERNEL ::mnt/" . $iso . "/install.amd/xen/vmlinuz";
                    #$bios .= "\n\tINITRD ::mnt/" . $iso . "/install.amd/xen/initrd.gz";

                    //$bios .= "\n\tAPPEND boot=casper netboot=nfs nfsroot=192.168.1.11:/srv/dnsmasq/mounted/" . $iso;
                    //$cmd = 'sudo echo ' . $bios . ' >> /srv/dnsmasq/tftp/syslinux/pxelinux.cfg/defaulta';
                    //sh($cmd, $debug);
                    break;
                case 'suse':
                    break;
                    
    //MENU LABEL 7 debian-10.9.0-amd64-netinst.iso
    //LINUX ::iso/debian-10.9.0-amd64-netinst.iso/install.amd/vmlinuz
    //INITRD ::iso/debian-10.9.0-amd64-netinst.iso/install.amd/initrd.gz
    //APPEND vga=788 root=/dev/ram0 ramdisk_size=2000000 ip=dhcp method=http://192.168.1.11/debian-10.9.0-amd64-netinst.iso/

                case 'fedora':
                    # https://fedoraproject.org/wiki/StatelessLinux/HOWTO
# todo if Server
# todo if Workstation
                    if(strpos($name, 'workstation') !== false) {
                        $bios .= "\n\nLABEL " . $i . "\n\tMENU LABEL " . $name . ' Live';
                        ###$bios .= "\n\tKERNEL ::mnt/" . $iso . "/images/pxeboot/vmlinuz initrd=::mnt/" . $iso . "/images/pxeboot/initrd.img coreos.live.rootfs_url=" . $data['config']['http'] . '/' .$iso . '/LiveOS/squashfs.img systemd.unified_cgroup_hierarchy=0 ip=dhcp';
                        $bios .= "\n\tKERNEL ::mnt/" . $iso . "/images/pxeboot/vmlinuz";
                        $bios .= "\n\tINITRD ::mnt/" . $iso . "/images/pxeboot/initrd.img";
                        $bios .= "\n\tAPPEND selinux=0 ip=dhcp coreos.live.rootfs_url=" . $data['config']['http'] . '/' .$iso . '/LiveOS/squashfs.img'; # systemd.unified_cgroup_hierarchy=0';
                        # selinux=0 needed for NFS?
                        //$bios .= "\n\tINITRD ::mnt/" . $iso . "/images/pxeboot/initrd.img";
                        #$bios .= "\n\tAPPEND root=" . $data['config']['http'] . '/' .$iso . "/LiveOS/squashfs.img coreos.live.rootfs_url=" . $data['config']['http'] . '/' .$iso;
                        #$bios .= "\n\tAPPEND coreos.live.rootfs_url=" . $data['config']['http'] . '/' .$iso . '/LiveOS/squashfs.img';
                        //$bios .= "\n\tAPPEND root=/dev/ram0 ramdisk_size=1500000 ip=dhcp url=" . $httpIsoPath;
                    }

                    if(strpos($name, 'server') !== false) {
                        $bios .= "\n\nLABEL " . $i . "\n\tMENU LABEL " . $name . ' Live';
                        ###$bios .= "\n\tKERNEL ::mnt/" . $iso . "/images/pxeboot/vmlinuz initrd=::mnt/" . $iso . "/images/pxeboot/initrd.img coreos.live.rootfs_url=" . $data['config']['http'] . '/' .$iso . '/LiveOS/squashfs.img systemd.unified_cgroup_hierarchy=0 ip=dhcp';
                        $bios .= "\n\tKERNEL ::mnt/" . $iso . "/images/pxeboot/vmlinuz";
                        $bios .= "\n\tINITRD ::mnt/" . $iso . "/images/pxeboot/initrd.img";
                        $bios .= "\n\tAPPEND ip=dhcp url=" . $httpIsoPath;
                    }
                    //$i++;
                    //$bios .= "\n\nLABEL " . $i . "\n\tMENU LABEL " . $name . ' Live NFS';
                    //$bios .= "\n\tKERNEL ::mnt/" . $iso . "/images/pxeboot/vmlinuz";
                    //$bios .= "\n\tINITRD ::mnt/" . $iso . "/images/pxeboot/initrd.img";
                    #$bios .= "\n\tAPPEND selinux=0 root=/dev/nfs/" . $iso . " nfsroot=" . $data['config']['nfs'] . ":" . $data['config']['mounted'];
                    //$bios .= "\n\tAPPEND selinux=0 ip=dhcp root=" . $data['config']['nfs_root'];
                    
                    // https://fedoraproject.org/wiki/StatelessLinux/NFSRoot
                    $macboot .= "\n\nLABEL " . $i . "\n\tMENU LABEL " . $name . ' Live';
                    $macboot .= "\n\tKERNEL ::mnt/" . $iso . "/images/macboot.img";
                    break;
                        
                case 'ubuntu':
                    $bios .= "\n\nLABEL " . $i . "\n\tMENU LABEL " . $name . ' - LIVE, full iso download';
                    $bios .= "\n\tKERNEL ::mnt/" . $iso . "/casper/vmlinuz";
                    $bios .= "\n\tINITRD ::mnt/" . $iso . "/casper/initrd";
                    $bios .= "\n\tAPPEND root=/dev/ram0 ip=dhcp url=" . $httpIsoPath;#ramdisk_size=1500000 
                    $bios .= "\n\tTEXT HELP";
                    $bios .= "\n\tSlo on 100M eth, ok on gigabit";
                    if(strpos($name, 'desktop') !== false || strpos($name, 'studio') !== false ) {
                        $bios .= "\n\t!!! Failed to boot with less than 3Gb of ram";
                    }
                    $bios .= "\n\tENDTEXT";
                        
                    #$i++;
                    #$bios .= "\n\nLABEL " . $i . "\n\tMENU LABEL " . $name . ' (nfs)';
                    #$bios .= "\n\tKERNEL ::mnt/" . $iso . "/casper/vmlinuz";
                    #$bios .= "\n\tINITRD ::mnt/" . $iso . "/casper/initrd";
                    #$bios .= "\n\tAPPEND boot=casper netboot=nfs nfsroot=" . $data['config']['nfs'] . ":" . $data['config']['mounted'] . ' root=/dev/nfs/' . $iso;
                        //$cmd = 'sudo echo ' . $bios . ' >> /srv/dnsmasq/tftp/syslinux/pxelinux.cfg/defaulta';
                        //sh($cmd, $debug);
                    break;
            }
            unset($type);
        }
        $i++;
    }

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

    #$data['config']['pxe']['menu'] = 
    $biosPath = $data['config']['tftp'] . '/pxe/modules/bios/pxelinux.cfg/default';
    $efi32Path = $data['config']['tftp'] . '/pxe/modules/efi32/pxelinux.cfg/default';
    $efi64Path = $data['config']['tftp'] . '/pxe/modules/efi64/pxelinux.cfg/default';
    $macbootPath = $data['config']['tftp'] . '/macboot';

    # Write config
    file_put_contents ($biosPath, $head . $bios . $foot);
    file_put_contents ($efi32Path, $head . $efi32 . $foot);
    file_put_contents ($efi64Path, $head . $efi64 . $foot);
    file_put_contents ($macbootPath, $head . $macboot . $foot);
}
