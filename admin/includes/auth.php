<?php
if (!isset($_SESSION['admin_id'])) {
    header('Location: ' . dirname($_SERVER['SCRIPT_NAME']) . '/login.php');
    exit;
}
