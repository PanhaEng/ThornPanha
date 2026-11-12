<?php
// ចាប់ផ្តើម Session
if (session_status() === PHP_SESSION_NONE) {
    session_start();
}

// ការកំណត់ Timezone
date_default_timezone_set('Asia/Phnom_Penh');

// ការកំណត់ Character Encoding
header('Content-Type: text/html; charset=UTF-8');

// Define root path
define('ROOT_PATH', dirname(__DIR__));
define('INCLUDES_PATH', ROOT_PATH . '/includes');
define('EXERCISES_PATH', ROOT_PATH . '/exercises');

// App settings
define('APP_NAME', 'PHP Functions & Forms');
define('APP_VERSION', '1.0.0');
