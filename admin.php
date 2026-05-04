<?php

declare(strict_types=1);

session_start();
require_once 'database/database.php';
require_once 'flash.php';

$pageTitle = 'Administration';
ob_start();
require_once 'resources/views/admin/admin_html.php';
$pageContent = ob_get_clean();
require_once 'resources/views/layouts/admin-layout/admin-layout_html.php';

