<?php
if (class_exists('ZipArchive')) {
    echo "Success: ZipArchive is ENABLED!";
} else {
    echo "Error: ZipArchive is still DISABLED.";
}
