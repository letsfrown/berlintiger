<?php

// www.berlintiger.de nutzt dieselbe Live-Config.
$live = __DIR__ . '/config.berlintiger.de.php';
return is_file($live) ? require $live : [];
