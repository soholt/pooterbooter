<?php

require_once('./config.php');
require_once('./functions.php');

if(isset($_REQUEST['menu'])) {
    #if(!is_link($data['config']['http']['root'] . '/ltsp')) {
    #    symlink($data['config']['http']['root'] . '/ltsp', $data['config']['tftp'] . '/ltsp');
    #}
    $data['debug']['ltsp']['rebuild-menu'] = sh('sudo ltsp ipxe', $data);
}

require_once('./html/head.php');
require_once('./html/menu.php');

echo '<h3>Linux Terminal Server Project</h3>' . "\n";

echo 'TODO debootstrap GUI' . "<br />\n";

echo '<br /><a href="?menu">Update LTSP menu</a>' . "<br />\n";

echo "<br />\n";

$info = sh('ltsp info', $data);
echo implode("<br />\n", $info);

require_once('./html/foot.php');
