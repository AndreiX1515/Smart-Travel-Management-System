<?php
session_start();
if (session_destroy()) {
    // If session is destroyed, return success
    echo 'success';
} else {
    // If session destroy fails, return error
    echo 'error';
}
?>
