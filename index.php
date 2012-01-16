<?php

header('HTTP/1.1 301 Moved Permanently');
header('Location: '. $_SERVER['REQUEST_URI'].'public/index.php/', true);
exit;
