<?php
// main game controller
session_start(); // gotta track player state

// include essentials
require_once 'config.php';
require_once 'includes/db.php';
require_once 'includes/functions.php';

// get db connection
$pdo = getdb();

// setup player if first time or check if dead
initplayer();

// get player action from url ?action=go&target=north etc
$action = $_GET['action'] ?? null;
$target = $_GET['target'] ?? null;

// process the action update game state get message back
$message = processaction($pdo, $action, $target);

// add session message if exists (e.g., from reset or init)
if(isset($_SESSION['message'])){
    $message = $_SESSION['message'] . ($message ? '<br>'.$message : ''); // combine messages
    unset($_SESSION['message']); // clear session message after showing
}


// --- prepare data for display ---
$playerloc = $_SESSION['playerloc']; // current location id
$room = getroomdata($pdo, $playerloc);
$items = getitems_inroom($pdo, $playerloc);
$npcs = getnpcs_inroom($pdo, $playerloc);
$inventory = $_SESSION['inv'];

// --- render the page using templates ---
include 'templates/header.php'; // includes player stats too

// show messages if any action occurred
include 'templates/messages.php'; // pass $message

// show current room items npcs
include 'templates/room.php'; // pass $room $items $npcs

// show available actions like movement
include 'templates/actions.php'; // pass $room

// show player inventory n actions
include 'templates/inventory.php'; // pass $inventory

include 'templates/footer.php'; // closing html

?>