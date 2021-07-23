<?php

function make_ipxe_menu(&$data) {
$ipxeMenu = ''; // menu entires
$ipxeBody = ''; // boot entries

$ipxeHead = '#!ipxe';
#$ipxeHead .= "\n\n" . 'menu Please choose an operating system to boot';
#$ipxeHead .= 'item fedora   Fedora Linux';
#$ipxeHead .= 'item win7     Windows 7';
#$ipxeHead .= 'choose os';

$ipxeHead .= "\n" . '# Variables are specified in boot.ipxe.cfg';

#$ipxeHead .= "\n" . 'set menu-default fedora-workstation-live-x86_64-34-1.2.iso';
#$ipxeHead .= "\n" . 'set menu-default Win10_21H1_English_x64.iso';
#$ipxeHead .= "\n" . 'set menu-default archlinux-2021.06.01-x86_64.iso_chain';
if(isset($data['config']['ipxe']['boot'])) $ipxeHead .= "\n" . 'set menu-default ' . $data['config']['ipxe']['boot'];

$ipxeHead .= "\n\n" . '# Some menu defaults';
$ipxeHead .= "\n" . 'set menu-timeout 5000';
$ipxeHead .= "\n" . 'set submenu-timeout ${menu-timeout}';
$ipxeHead .= "\n" . 'isset ${menu-default} || set menu-default exit';

$ipxeHead .= "\n\n" . '# Figure out if client is 64-bit capable';
$ipxeHead .= "\n" . 'cpuid --ext 29 && set arch x64 || set arch x86';
$ipxeHead .= "\n" . 'cpuid --ext 29 && set archl amd64 || set archl i386';

$ipxeHead .= "\n\n" . '###################### MAIN MENU ####################################';

$ipxeHead .= "\n\n" . ':start';
$ipxeHead .= "\n" . 'menu iPXE pooterbooter for ' . $data['config']['pxe']['title'];
$ipxeHead .= "\n" . 'item --gap --             ------------------------- Operating systems ------------------------------';



#$ipxeHead .= "\n" . 'item --key f freedos      Boot FreeDOS from iSCSI';
#$ipxeHead .= "\n" . 'item --key m msdos        Boot MS-DOS from iSCSI';
#$ipxeHead .= "\n" . 'item --key u ubuntu       Boot Ubuntu from iSCSI';
#$ipxeHead .= "\n" . 'item --key v vmware       Boot VMware ESXi from iSCSI';
#$ipxeHead .= "\n" . 'item --key w windows7     Boot Windows 7 from iSCSI';
#$ipxeHead .= "\n" . 'item --key l menu-live    Live environments...';
#$ipxeHead .= "\n" . 'item fedora-workstation-live-x86_64-34-1.2.iso fedora-workstation-live-x86_64-34-1.2.iso';
#$ipxeHead .= "\n" . 'item --gap --             ------------------------- Tools and utilities ----------------------------';
#$ipxeHead .= "\n" . 'item --key r menu-recovery Recovery tools...';
#$ipxeHead .= "\n" . 'item --key d menu-diag    Diagnostics tools...';
#$ipxeHead .= "\n" . 'item --key i menu-install Installers...';
$ipxeHeadEnd = '';
$ipxeHeadEnd .= "\n" . 'item --gap --             --------------------------- pooterbooter ---------------------------------';
$ipxeHeadEnd .= "\n" . 'item --key p pxelinux     PXELinux archlinux/clonezilla';
if($data['config']['di']['installed']) $ipxeHeadEnd .= "\n" . 'item --key d di di-netboot';
if($data['config']['ltsp']['installed']) $ipxeHeadEnd .= "\n" . 'item --key l ltsp LTSP';
$ipxeHeadEnd .= '';
$ipxeHeadEnd .= "\n" . 'item --gap --             ------------------------- Advanced options -------------------------------';
$ipxeHeadEnd .= "\n" . 'item --key c config       Configure settings';
$ipxeHeadEnd .= "\n" . 'item shell                Drop to iPXE shell';
$ipxeHeadEnd .= "\n" . 'item reboot               Reboot computer';
$ipxeHeadEnd .= "\n" . 'item';
$ipxeHeadEnd .= "\n" . 'item --key x exit         Exit iPXE and continue BIOS boot';
$ipxeHeadEnd .= "\n" . 'choose --timeout ${menu-timeout} --default ${menu-default} selected || goto cancel';
$ipxeHeadEnd .= "\n" . 'set menu-timeout 0';
$ipxeHeadEnd .= "\n" . 'goto ${selected}';
$ipxeHeadEnd .= "\n";

$ipxeFoot = '';

if(is_dir('/srv/tftp/ltsp')) {
    $ipxeFoot .= "\n\n" . ':ltsp';
    // for this to work, need to symlink
    #$ipxeFoot .= "\n" . 'chain --replace --autofree http://${next-server}/ipxe/ltsp.ipxe || goto failed';
    // or we can tftp it
    $ipxeFoot .= "\n" . 'chain --replace --autofree tftp://${next-server}/ltsp/ltsp.ipxe || goto failed';
    $ipxeFoot .= "\n" . 'goto start';
}

$ipxeFoot .= "\n\n" . ':pxelinux';
$ipxeFoot .= "\n" . 'set 210:string tftp://${next-server}/pxe/modules/bios/';
$ipxeFoot .= "\n" . 'chain ${210:string}pxelinux.0 || goto failed';
$ipxeFoot .= "\n" . 'goto start';

$ipxeFoot .= "\n\n" . ':di';
$ipxeFoot .= "\n" . 'set 210:string tftp://${next-server}/d-i/n-a/';
$ipxeFoot .= "\n" . 'chain ${210:string}pxelinux.0 || goto failed';
$ipxeFoot .= "\n" . 'goto start';

$ipxeFoot .= "\n\n" . ':cancel';
$ipxeFoot .= "\n" . 'echo You cancelled the menu, dropping you to a shell';

$ipxeFoot .= "\n\n" . ':shell';
$ipxeFoot .= "\n" . 'echo Type \'exit\' to get the back to the menu';
$ipxeFoot .= "\n" . 'shell';
$ipxeFoot .= "\n" . 'set menu-timeout 0';
$ipxeFoot .= "\n" . 'set submenu-timeout 0';
$ipxeFoot .= "\n" . 'goto start';

$ipxeFoot .= "\n\n" . ':failed';
$ipxeFoot .= "\n" . 'echo Booting failed, dropping to shell';
$ipxeFoot .= "\n" . 'goto shell';

$ipxeFoot .= "\n\n" . ':reboot';
$ipxeFoot .= "\n" . 'reboot';

$ipxeFoot .= "\n\n" . ':exit';
$ipxeFoot .= "\n" . 'exit';

$ipxeFoot .= "\n\n" . ':config';
$ipxeFoot .= "\n" . 'config';
$ipxeFoot .= "\n" . 'goto start';

$ipxeFoot .= "\n\n" . ':back';
$ipxeFoot .= "\n" . 'set submenu-timeout 0';
$ipxeFoot .= "\n" . 'clear submenu-default';
$ipxeFoot .= "\n" . 'goto start';
$ipxeFoot .= "\n";

#$data['config']['pxe']['menu'] = 
#$biosPath = $data['config']['tftp'] . '/pxe/modules/bios/pxelinux.cfg/default';
#$efi32Path = $data['config']['tftp'] . '/pxe/modules/efi32/pxelinux.cfg/default';
#$efi64Path = $data['config']['tftp'] . '/pxe/modules/efi64/pxelinux.cfg/default';
#$macbootPath = $data['config']['tftp'] . '/macboot';
$ipxePath = $data['config']['ipxe']['root'] . '/pooterbooter.ipxe';

# Check if popuplated amd make manu
if(isset($data['menu']['pb']['ipxe']['menu'])) {
    $ipxeMenu .= "\n" . implode("\n", $data['menu']['pb']['ipxe']['menu']);
    $ipxeBody .= "\n" . implode("\n", $data['menu']['pb']['ipxe']['body']);
}

# Make menu from di, so we can controll autoinstall/locale/packages, etc
# without having to edit di configs. Default di menu still available
if($data['config']['di']['installed']) {
    require_once('./menu-maker/di.php');
    $ipxeMenu .= "\n" . implode("\n", $data['menu']['pb']['ipxe']['di']['menu']);
    $ipxeBody .= "\n" . implode("\n", $data['menu']['pb']['ipxe']['di']['body']);
}

# Write config
#file_put_contents ($biosPath, $head . $bios . $foot);
#file_put_contents ($efi32Path, $head . $efi32 . $foot);
#file_put_contents ($efi64Path, $head . $efi64 . $foot);
#file_put_contents ($macbootPath, $head . $macboot . $foot);
#echo file_put_contents($ipxePath, $ipxeHead . $ipxeMenu . $ipxeHeadEnd . $ipxeBody. $ipxeFoot);
file_put_contents($ipxePath, $ipxeHead . $ipxeMenu . $ipxeHeadEnd . $ipxeBody. $ipxeFoot);
}