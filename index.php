<?php

declare(strict_types=1);

session_start();
require_once 'database/database.php';
require_once 'flash.php';

//--script PHP

$pageTitle = 'Notre blog d\'accueil';
ob_start();
require_once 'resources/views/blog/index_html.php';
$pageContent = ob_get_clean();
require_once 'resources/views/layouts/blog-layout/blog-layout_html.php';

