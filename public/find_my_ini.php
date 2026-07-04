<?php
// This will tell us EXACTLY which file to edit
echo "The file you must edit is: " . php_ini_loaded_file();

echo "<br><br>Checking if Zip is enabled: ";
if (extension_loaded('zip')) {
    echo "YES! It is enabled.";
} else {
    echo "NO! It is still disabled.";
}
?>
