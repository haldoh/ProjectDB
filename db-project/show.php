<?php

header("Content-type: image/jpeg");
$jpeg = fopen("/home/postgres2/tmp.jpg","r");
$image = fread($jpeg,filesize("/home/postgres2/tmp.jpg"));
echo $image;

?> 