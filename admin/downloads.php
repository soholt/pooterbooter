<?php
/**
 * © 2021 Gintaras Valatka https://github.com/soholt/
 */

$data['recipes'] = [
    'archlinux' => [
        #'tftp' => [
        #    'KERNEL' => '::' . '',
        #    'INITRD' => '::' . '',
        #    'APPEND' => '',
        #],
        'doc' => [
            'https://wiki.archlinux.org/title/Preboot_Execution_Environment',
        ],
        'dl' => [
            'downloads+mirrors' => 'https://archlinux.org/download/',
            //'dowloads netboot' => 'https://archlinux.org/releng/netboot/';
            'torrent' => 'https://archlinux.org/releng/releases/2021.06.01/torrent/',
            'iso global mirror' => 'https://mirror.rackspace.com/archlinux/iso/2021.06.01/archlinux-2021.06.01-x86_64.iso',
            'iso md5' => 'https://mirror.rackspace.com/archlinux/iso/2021.06.01/md5sums.txt',
            'iso sha1' => 'https://mirror.rackspace.com/archlinux/iso/2021.06.01/sha1sums.txt',
        ],
    ],
    'clonezilla' => [
        #'tftp' => [
        #    'KERNEL' => '::' . '',
        #    'INITRD' => '::' . '',
        #    'APPEND' => '',
        #],
        'doc' => [
            'https://clonezilla.org/livepxe.php'
        ],
        'dl' => [
            'downloads' => 'https://clonezilla.org/downloads.php',
            'iso' => 'https://osdn.net/frs/redir.php?m=constant&f=clonezilla%2F75295%2Fclonezilla-live-2.7.2-39-amd64.iso',
            'iso sum' => 'https://clonezilla.org/downloads/stable/data/CHECKSUMS.TXT',
        ],
    ],
    /*
    'debian' => [
        'tftp' => [
            'KERNEL' => '::' . '',
            'INITRD' => '::' . '',
            'APPEND' => '',
        ],
        'doc' => [
            'https://wiki.debian.org/PXEBootInstall',
        ],
        'dl' => [
            'Downloads' => 'https://www.debian.org/distrib/',

            'Firmware if needed dl' => 'https://www.debian.org/releases/stable/amd64/ch06s04',
            'Live dl' => 'https://www.debian.org/CD/live/',
            'Live 64 torrents' => 'https://cdimage.debian.org/debian-cd/current-live/amd64/bt-hybrid/',
            'Live 64 gnome torrent' => 'https://cdimage.debian.org/debian-cd/current-live/amd64/bt-hybrid/debian-live-10.9.0-amd64-gnome.iso.torrent',
            'Live 64 standard torrent' => 'https://cdimage.debian.org/debian-cd/current-live/amd64/bt-hybrid/debian-live-10.9.0-amd64-standard.iso.torrent',
            'Live md5' => 'https://cdimage.debian.org/debian-cd/current-live/amd64/bt-hybrid/MD5SUMS',
            'Live sha1' => 'https://cdimage.debian.org/debian-cd/current-live/amd64/bt-hybrid/SHA1SUMS',
            'Live sha256' => 'https://cdimage.debian.org/debian-cd/current-live/amd64/bt-hybrid/SHA256SUMS',

            //'netinst' => 'https://cdimage.debian.org/debian-cd/current/amd64/iso-cd/',
            'Netinst dl' => 'https://www.debian.org/distrib/netinst',
            'Netinst 32 iso' => 'https://cdimage.debian.org/debian-cd/current/i386/iso-cd/debian-10.9.0-i386-netinst.iso',
            'Netinst 64 iso' => 'https://cdimage.debian.org/debian-cd/current/amd64/iso-cd/debian-10.9.0-amd64-netinst.iso',

            'non-free main' => 'https://cdimage.debian.org/cdimage/unofficial/non-free/cd-including-firmware/current/',
            'non-free ' => 'https://cdimage.debian.org/cdimage/unofficial/non-free/cd-including-firmware/current/amd64/bt-cd/',
            'non-free torent std' => 'https://cdimage.debian.org/cdimage/unofficial/non-free/cd-including-firmware/current/amd64/bt-cd/firmware-10.9.0-amd64-netinst.iso.torrent',
            'non-free torent edu' => 'https://cdimage.debian.org/cdimage/unofficial/non-free/cd-including-firmware/current/amd64/bt-cd/firmware-edu-10.9.0-amd64-netinst.iso.torrent',
            'non-free md5' => 'https://cdimage.debian.org/cdimage/unofficial/non-free/cd-including-firmware/current/amd64/bt-cd/MD5SUMS',
            'non-free sha1' => 'https://cdimage.debian.org/cdimage/unofficial/non-free/cd-including-firmware/current/amd64/bt-cd/SHA1SUMS',
            'non-free sha256' => 'https://cdimage.debian.org/cdimage/unofficial/non-free/cd-including-firmware/current/amd64/bt-cd/SHA512SUMS',

        ],
    ],
    'fedora' => [
        'tftp' => [
            'KERNEL' => '::' . '',
            'INITRD' => '::' . '',
            'APPEND' => '',
        ],
        'doc' => ['https://docs.fedoraproject.org/en-US/fedora-coreos/live-booting-ipxe/'],
        'dl' => [
            'server 34 download_page' => 'https://getfedora.org/en/workstation/download/',
            'server 34 x86_64 direct_iso' => 'https://download.fedoraproject.org/pub/fedora/linux/releases/34/Server/x86_64/iso/Fedora-Server-dvd-x86_64-34-1.2.iso',
            'server netinst 34 x86_64 direct_iso' => 'https://download.fedoraproject.org/pub/fedora/linux/releases/34/Server/x86_64/iso/Fedora-Server-netinst-x86_64-34-1.2.iso',
            'server 34 x86_64 direct_iso_sum' => 'https://getfedora.org/static/checksums/34/iso/Fedora-Server-34-1.2-x86_64-CHECKSUM',

            'workstation 34 download_page' => 'https://getfedora.org/en/workstation/download/',
            'workstation 34 x86_64 direct_iso' => 'https://download.fedoraproject.org/pub/fedora/linux/releases/34/Workstation/x86_64/iso/Fedora-Workstation-Live-x86_64-34-1.2.iso',
            'workstation 34 x86_64 direct_iso_sum' => 'https://getfedora.org/static/checksums/34/iso/Fedora-Workstation-34-1.2-x86_64-CHECKSUM',
        ],
    ],
    */
    'ubuntu' => [
        #'tftp' => [
        #    'KERNEL' => '::' . '',
        #    'INITRD' => '::' . '',
        #    'APPEND' => '',
        #],
        'doc' => [
            'https://discourse.ubuntu.com/t/netbooting-the-live-server-installer/14510',
        ],
        'dl' => [
            //'netboot' => 'http://cdimage.ubuntu.com/netboot/',
            'server downloads' => 'https://ubuntu.com/download/server',

            'server 20.04.2 LTS iso' => 'https://releases.ubuntu.com/20.04.2/ubuntu-20.04.2-live-server-amd64.iso',
            // sh echo "d1f2bf834bbe9bb43faf16f9be992a6f3935e65be0edece1dee2aa6eb1767423 *ubuntu-20.04.2-live-server-amd64.iso" | shasum -a 256 --check
            
            'server 21.04 iso' => 'https://releases.ubuntu.com/21.04/ubuntu-21.04-live-server-amd64.iso',
            // sh echo "e4089c47104375b59951bad6c7b3ee5d9f6d80bfac4597e43a716bb8f5c1f3b0 *ubuntu-21.04-live-server-amd64.iso" | shasum -a 256 --check

            'desktop downloads' => 'https://ubuntu.com/download/desktop',

            // direct https://releases.ubuntu.com/20.04.2.0/ubuntu-20.04.2.0-desktop-amd64.iso
            'desktop 20.04.2 LTS iso' => 'https://ubuntu.com/download/desktop/thank-you?version=20.04.2.0&architecture=amd64',
            // echo "93bdab204067321ff131f560879db46bee3b994bf24836bb78538640f689e58f *ubuntu-20.04.2.0-desktop-amd64.iso" | shasum -a 256 --check

            //'desktop 21.04 iso' => 'https://releases.ubuntu.com/21.04/ubuntu-21.04-desktop-amd64.iso',
            'desktop 21.04 iso' => 'https://ubuntu.com/download/desktop/thank-you?version=21.04&architecture=amd64',
            // sh echo "fa95fb748b34d470a7cfa5e3c1c8fa1163e2dc340cd5a60f7ece9dc963ecdf88 *ubuntu-21.04-desktop-amd64.iso" | shasum -a 256 --check

            'studio downloads' => 'https://ubuntustudio.org/download/',
            'studio 20.04.2 LTS torrent' => 'https://cdimage.ubuntu.com/ubuntustudio/releases/20.04.2.0/release/ubuntustudio-20.04.2.0-dvd-amd64.iso.torrent',
            'studio 20.04.2 LTS iso' => 'https://cdimage.ubuntu.com/ubuntustudio/releases/20.04.2.0/release/ubuntustudio-20.04.2.0-dvd-amd64.iso',
            'studio 20.04.2 LTS iso sha256' => 'https://cdimage.ubuntu.com/ubuntustudio/releases/20.04.2.0/release/SHA256SUMS',
            'studio 21.04 torrent' => 'https://cdimage.ubuntu.com/ubuntustudio/releases/21.04/release/ubuntustudio-21.04-dvd-amd64.iso.torrent',
            'studio 21.04 iso' => 'https://cdimage.ubuntu.com/ubuntustudio/releases/21.04/release/ubuntustudio-21.04-dvd-amd64.iso',
            'studio 21.04 sha256' => 'https://cdimage.ubuntu.com/ubuntustudio/releases/21.04/release/SHA256SUMS',

            //'focal' => 'http://releases.ubuntu.com/focal/',
        ],
    ],
];
