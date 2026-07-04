<?php
echo "Loaded config file: " . php_ini_loaded_file();
echo "<br>Zip Enabled: " . (extension_loaded('zip') ? "YES" : "NO");
?>
