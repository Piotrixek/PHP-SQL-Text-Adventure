<?php
session_start(); // need session access to clear it

// clear all our game specific session vars
unset($_SESSION['playerloc']);
unset($_SESSION['inv']);
unset($_SESSION['playerhealth']);
unset($_SESSION['playerattack']);
unset($_SESSION['playerdefense']);
unset($_SESSION['player_init']); // make sure it re-initializes
unset($_SESSION['message']); // clear last message

// could use session_destroy() but might kill other session stuff if exists
// session_destroy();
// session_start(); // need to start again after destroy

// set a reset message for next page load
$_SESSION['message'] = "game reset good luck";

// send player back to main game page
header('Location: game.php');
exit(); // stop script here