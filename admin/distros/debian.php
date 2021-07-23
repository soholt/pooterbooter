<?php

function debian(&$data) {

    foreach($data['images']['mountedToLower'] as $i => $iso) {

        if(strpos($iso, 'debian') !== false) {

            # real iso
            $iso = $data['images']['mounted'][$i];

            # 
            # Based on
            # debian-live-10.9.0-amd64-gnome.iso
            #

            # iPXE NFS
            $kernel = 'vmlinuz';
            $initrd = 'initrd.gz';

            // TODO should be able to pass this in
            // a function to generate menu entries
/*
            $_args[] = 'hostname=pooterbooter';
            $_args[] = 'locale?=en_GB';
            $_args[] = 'mirror/http/proxy?=' . $data['config']['svcs']['apt-cacher-ng']['www'];
            $_args[] = 'pkgsel/include=' . $data['config']['di']['initrd']['install'];
            $_args[] = 'preseed/late_command="rm -fv /target/etc/apt/apt.conf"';
*/
/*          DOES NOT WORK, can't find cd
            $dir = 'd-i';
            $path = $data['config']['mounted'] . '/'. $iso . '/' . $dir;
            if(is_dir($path)) {

                $kernelPath = $data['config']['http']['mnt'] . '/' . $iso . '/' . $dir . '/' . $kernel;
                $initrdPath = $data['config']['http']['mnt'] . '/' . $iso . '/' . $dir . '/' . $initrd;

                $_args = array();
                $_args[] = $kernel;
                $_args[] = 'initrd=' . $initrd;
                $_args[] = 'root=/dev/nfs netboot=nfs nfsroot=' . $data['config']['nfs']['ip'] . ':' . $data['config']['mounted'] . '/' . $iso; # . '/' . $dir;
                $_args[] = 'ip=dhcp';
                #$_args[] = 'quiet splash'
                $_args[] =  '|| read void';
                
                $data['menu']['pb']['ipxe']['menu'][] = "item install_" . $iso . ' ' . $iso . ' install';
                $data['menu']['pb']['ipxe']['body'][] = ':install_' . $iso ;
                $data['menu']['pb']['ipxe']['body'][] = 'kernel ' . $kernelPath . ' || read void';
                $data['menu']['pb']['ipxe']['body'][] = 'initrd ' . $initrdPath . ' || read void';
                $data['menu']['pb']['ipxe']['body'][] = 'imgargs '. implode(' ', $_args);
                $data['menu']['pb']['ipxe']['body'][] = 'boot || goto failed';
                $data['menu']['pb']['ipxe']['body'][] = 'goto start';
                $data['menu']['pb']['ipxe']['body'][] = ''; # for empty line
            }

            $dir = 'd-i/gtk';
            $path = $data['config']['mounted'] . '/'. $iso . '/' . $dir;
            if(is_dir($path)) {

                $kernelPath = $data['config']['http']['mnt'] . '/' . $iso . '/' . $dir . '/' . $kernel;
                $initrdPath = $data['config']['http']['mnt'] . '/' . $iso . '/' . $dir . '/' . $initrd;

                $_args = array();
                $_args[] = $kernel;
                $_args[] = 'initrd=' . $initrd;
                $_args[] = 'video=vesa:ywrap,mtrr vga=788'; # gtk
                $_args[] = 'root=/dev/nfs netboot=nfs nfsroot=' . $data['config']['nfs']['ip'] . ':' . $data['config']['mounted'] . '/' . $iso;
                $_args[] = 'ip=dhcp';
                #$_args[] = 'quiet splash'
                $_args[] =  '|| read void';
    
                $data['menu']['pb']['ipxe']['menu'][] = "item install_gtk_" . $iso . ' ' . $iso . ' gtk install';
                $data['menu']['pb']['ipxe']['body'][] = ':install_gtk_' . $iso ;
                $data['menu']['pb']['ipxe']['body'][] = 'kernel ' . $kernelPath . ' || read void';
                $data['menu']['pb']['ipxe']['body'][] = 'initrd ' . $initrdPath . ' || read void';
                $data['menu']['pb']['ipxe']['body'][] = 'imgargs '. implode(' ', $_args);
                $data['menu']['pb']['ipxe']['body'][] = 'boot || goto failed';
                $data['menu']['pb']['ipxe']['body'][] = 'goto start';
                $data['menu']['pb']['ipxe']['body'][] = ''; # for empty line
            }

            $dir = 'install.amd';
            $path = $data['config']['mounted'] . '/'. $iso . '/' . $dir;
            if(is_dir($path)) {
                $kernelPath = $data['config']['http']['mnt'] . '/' . $iso . '/' . $dir . '/' . $kernel;
                $initrdPath = $data['config']['http']['mnt'] . '/' . $iso . '/' . $dir . '/' . $initrd;

                $_args = array();
                $_args[] = $kernel;
                $_args[] = 'initrd=' . $initrd;
                $_args[] = 'root=/dev/nfs netboot=nfs nfsroot=' . $data['config']['nfs']['ip'] . ':' . $data['config']['mounted'] . '/' . $iso;
                $_args[] = 'ip=dhcp';
                #$_args[] = 'quiet splash'
                $_args[] =  '|| read void';

                $data['menu']['pb']['ipxe']['menu'][] = "item install_" . $iso . ' ' . $iso . ' install';
                $data['menu']['pb']['ipxe']['body'][] = ':install_' . $iso ;
                $data['menu']['pb']['ipxe']['body'][] = 'kernel ' . $kernelPath . ' || read void';
                $data['menu']['pb']['ipxe']['body'][] = 'initrd ' . $initrdPath . ' || read void';
                $data['menu']['pb']['ipxe']['body'][] = 'imgargs '. implode(' ', $_args);
                $data['menu']['pb']['ipxe']['body'][] = 'boot || goto failed';
                $data['menu']['pb']['ipxe']['body'][] = 'goto start';
                $data['menu']['pb']['ipxe']['body'][] = ''; # for empty line
            }

            $dir = 'install.amd/gtk';
            $path = $data['config']['mounted'] . '/'. $iso . '/' . $dir;
            if(is_dir($path)) {
                $kernelPath = $data['config']['http']['mnt'] . '/' . $iso . '/' . $dir . '/' . $kernel;
                $initrdPath = $data['config']['http']['mnt'] . '/' . $iso . '/' . $dir . '/' . $initrd;

                $_args = array();
                $_args[] = $kernel;
                $_args[] = 'initrd=' . $initrd;$_args[] = 'vga=788'; # gtk
                $_args[] = 'root=/dev/nfs netboot=nfs nfsroot=' . $data['config']['nfs']['ip'] . ':' . $data['config']['mounted'] . '/' . $iso;
                $_args[] = 'ip=dhcp';
                #$_args[] = 'quiet splash'
                $_args[] =  '|| read void';

                $data['menu']['pb']['ipxe']['menu'][] = "item install_gtk_" . $iso . ' ' . $iso . ' gtk install';
                $data['menu']['pb']['ipxe']['body'][] = ':install_gtk_' . $iso ;
                $data['menu']['pb']['ipxe']['body'][] = 'kernel ' . $kernelPath . ' || read void';
                $data['menu']['pb']['ipxe']['body'][] = 'initrd ' . $initrdPath . ' || read void';
                $data['menu']['pb']['ipxe']['body'][] = 'imgargs '. implode(' ', $_args);
                $data['menu']['pb']['ipxe']['body'][] = 'boot || goto failed';
                $data['menu']['pb']['ipxe']['body'][] = 'goto start';
                $data['menu']['pb']['ipxe']['body'][] = ''; # for empty line
            }

            $dir = 'install.amd/xen';
            $path = $data['config']['mounted'] . '/'. $iso . '/' . $dir;
            if(is_dir($path)) {
                $kernelPath = $data['config']['http']['mnt'] . '/' . $iso . '/' . $dir . '/' . $kernel;
                $initrdPath = $data['config']['http']['mnt'] . '/' . $iso . '/' . $dir . '/' . $initrd;
                // TODO
            }
*/
            $dir = 'live';
            $path = $data['config']['mounted'] . '/'. $iso . '/' . $dir;
            if(is_dir($path)) {

                #$kernelLive = 'vmlinuz-4.19.0-16-amd64';
                #$initrdLive = 'initrd.img-4.19.0-16-amd64';

                # Find linux and initrd filenames
                $files = sh('ls ' . $data['config']['mounted'] . '/' . $iso . '/' . $dir, $data);
                foreach($files as $file) {
                    if(strpos($file, 'vmlinuz') !== false) $kernelLive = $file;
                    if(strpos($file, 'initrd') !== false) $initrdLive = $file;
                }

                $kernelPathLive = $data['config']['http']['mnt'] . '/' . $iso . '/' . $dir . '/' . $kernelLive;
                $initrdPathLive = $data['config']['http']['mnt'] . '/' . $iso . '/' . $dir . '/' . $initrdLive;

                $_args = array();
                $_args[] = $kernelLive;
                $_args[] = 'initrd=' . $initrdLive;
                $_args[] = 'boot=live components locales=' . $data['config']['di']['initrd']['locale'] . '.UTF-8';
                $_args[] = 'root=/dev/nfs netboot=nfs nfsroot=' . $data['config']['nfs']['ip'] . ':' . $data['config']['mounted'] . '/' . $iso;
                $_args[] = 'ip=dhcp';
                #$_args[] = 'quiet splash'
                $_args[] =  '|| read void';

                $data['menu']['pb']['ipxe']['menu'][] = "item live_" . $iso . ' ' . $iso . ' NFS LIVE ';
                $data['menu']['pb']['ipxe']['body'][] = ':live_' . $iso ;
                $data['menu']['pb']['ipxe']['body'][] = 'kernel ' . $kernelPathLive . ' || read void';
                $data['menu']['pb']['ipxe']['body'][] = 'initrd ' . $initrdPathLive . ' || read void';
                $data['menu']['pb']['ipxe']['body'][] = 'imgargs '. implode(' ', $_args);
                $data['menu']['pb']['ipxe']['body'][] = 'boot || goto failed';
                $data['menu']['pb']['ipxe']['body'][] = 'goto start';
                $data['menu']['pb']['ipxe']['body'][] = ''; # for empty line
            }

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
/*
https://manpages.debian.org/stretch/manpages/bootparam.7.en.html

https://manpages.debian.org/buster/manpages/initrd.4.en.html
*/