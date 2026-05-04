<?php
session_start();
require_once 'flash.php';

$_SESSION = [];
flash_set('success', "Vous avez été déconnecté avec succès.");

header('Location: login.php');