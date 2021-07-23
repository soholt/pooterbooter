<!DOCTYPE html>
<html lang="en-GB">
    <head>
        <meta http-equiv="content-type" content="text/html; charset=utf-8">
        <title>-=pooterbooter=-</title>
    </head>
    <body>
<?php

if(isset($data['error'])) {
    echo "<pre>"; #$key => 
    echo "<h2>";
    echo 'cmd: ';
    foreach($data['error'] as $key => $val){
        
        echo $val['cmd'];

        foreach($val['msg'] as $error)
        echo '<br />error: ' . $error;
        #echo $val['cmd'];
        #echo $val['err'];
       
    } 
    echo "</h2>";
    echo '</pre>';
}
