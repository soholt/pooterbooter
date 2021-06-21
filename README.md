# pooterbooter
Dnsmasq pxe tftp boot server from .iso files, via tftp/http/nfs/ipxe and maybe others

..in an attempt to make the simplest pxe server ..in the world ..and to save myself from dd'ing

*TESTING v0.0.1, not production ready/secure*

## Currently tftp/http pxe, only BIOS boot at this time is available
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
 
## Quick start
* Setup manual IP address by hand or via dhcp::mac
* Run: `git clone https://github.com/soholt/pooterbooter && cd pooterbooter`
* Run: (also read the setup file itself):
`./setupPi1`
* If no errors, start the firewall `sudo ufw enable`
* Edit: /srv/dnsmasq/dnsmasq.config (might automate soon)
* Restart: dnsmasq for changes `sudo systemctl restart dnsmasq`
* Run: `cd admin && ./serv` to start admin the server
* Browse: http://$my_ip_address:3000/
* Download some images to /srv/tftp/mnt/iso or mount hdd/usb
* Mount iso images in admin

## Workings of ./admin
* Search for available *.iso files in /srv/tftp/mnt/iso
* Search for mounted iso file systems
* Mount/umount iso and generate pxe menu
* See Tested and works above

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
