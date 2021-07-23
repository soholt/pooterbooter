# pooterbooter
Dnsmasq pxe tftp boot server from .iso files, via tftp/http/nfs/ipxe and maybe others
..in an attempt to make the simplest pxe server ..in the world ..and to save myself from dd'ing

## v0.0.2 (The best setup in my mind)
* apt-cacher-ng and auto-apt-proxy for apt caching
* di-netboot-assistant for installing Debian/Ubuntu
* NFS and ufw
* Debian/Ubuntu Live with NFS (less ram)
* Boot into original di-netboot-assistant/LTSP menus
* Can boot into windows 10 setup

## v0.0.2 Quick start
* Setup manual IP address by hand or via dhcp::mac
* Run: `git clone https://github.com/soholt/pooterbooter && cd pooterbooter`
* Install required software `./install`
* Setup folders and firewall `./setup`
* If no errors, start the firewall `sudo ufw enable`
* Edit: configs/dnsmasq.pb.conf (might automate soon)
* Run: `cd admin && ./serv` to start the admin server, you will be required to enter root password to admin/mount/unmount
* Browse: http://$my_ip_address:3000/
* Download some images to /srv/tftp/www/iso or mount hdd/usb
* Mount iso images in admin

## v0.0.2 Todo
* try to reduce journalctl output to speedup json
* ui fixes+improvments, multi iPXE menu
* 
### Tested on Debian 11 Bullseye (testing)

## Workings of ./admin
* Search for available *.iso files in /srv/tftp/www/iso
* Search for mounted iso file systems
* Mount/umount iso and generate pxe/ipxe menus

## v0.0.1 Currently tftp/http pxe, only BIOS boot at this time is available
* Tested and works with archlinux, clonezilla, ubuntu server
* Ubuntu desktop/studio RAM: 3Gb=crash, 6Gb=Ok*
* Archlinux boots in 4min from Raspberry Pi1, ubuntu slow to dowload via 100Mb network
* Archlonux boot Gigabit lan 1min15sec, Pi4 should be similar
* TODO: Debian, Fedora and others
* TODO: Raspberry Pi boot
* TODO: EFI64 & EFI32 boot + arch
* TODO: iPXE & NFS(also need to see mounted iso-loop, maybe missing some opts)
* debian(I suspect it might be the same as pi)/ubuntu setup files

### Tested on 21/June/2021 on Raspberry Pi1 - Raspberry Pi OS Lite from [raspberrypi.org](https://www.raspberrypi.org/software/operating-systems/)

ISO: [2021-05-07-raspios-buster-armhf-lite.zip](https://downloads.raspberrypi.org/raspios_lite_armhf/images/raspios_lite_armhf-2021-05-28/2021-05-07-raspios-buster-armhf-lite.zip)
sha256 zip file c5dad159a2775c687e9281b1a0e586f7471690ae28f2f2282c90e7d59f64273c
Update OS: `sudo apt update && sudo apt upgrade -y && sudo apt install git` 
Enable ssh: `sudo systemctl enable ssh && sudo systemctl start ssh`
The rest can be done remotly

## Troubleshooting
* Disable firewall `sudo ufw disable`
* Check the *services* are up & running with no errors
* * `systemctl status dnsmasq`
* * `systemctl status nginx`
* * `systemctl status nfs-kernel-server`
* To restart a service/after reconfig `systemctl restart SERVICE_NAME_FROM_ABOVE`

## Dev/debug
* For realtime happenings run `./logDnsmasq` `./logNginxAccess` `./logNginxError` files
* Some docs in ./admin/downloads.php

### Unable to test EFI64/32, my current hardware is decade old.
### Crowd funding for Raspberry Pi4 and EFI64/32 https://ko-fi.com/ginsoholt

***
This (is)was supposed the be part of https://github.com/soholt/Tango but..

.. I also remember pxe booting G5, need to find the old scripts or magic options

***
My try to https://hackaday.io/project/180773-pooterbooter but I missed the deadlines :(
oh well
