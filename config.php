<?php
// --- db stuff ---
define('DB_HOST', 'localhost');
define('DB_NAME', 'text_game');
define('DB_USER', 'root');
define('DB_PASS', ''); // default xampp pass empty

// --- game settings ---
define('START_ROOM_ID', 1);
define('PLAYER_START_HEALTH', 50);
define('PLAYER_START_ATTACK', 3);
define('PLAYER_START_DEFENSE', 1);

// error reporting helps during dev remove for production maybe
error_reporting(E_ALL);
ini_set('display_errors', 1);