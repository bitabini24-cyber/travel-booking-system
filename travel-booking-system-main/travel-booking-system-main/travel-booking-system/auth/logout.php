<?php
require_once __DIR__ . '/../config/app.php';
require_once __DIR__ . '/../backend/helpers/functions.php';
session_destroy();
redirect(APP_URL . '/index.php');
