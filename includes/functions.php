<?php
// --- session and player init ---

// start player off right or check if dead
function initplayer() {
    if (!isset($_SESSION['player_init'])) {
        $_SESSION['playerloc'] = START_ROOM_ID;
        $_SESSION['inv'] = []; // empty inventory array item names
        $_SESSION['playerhealth'] = PLAYER_START_HEALTH;
        $_SESSION['playerattack'] = PLAYER_START_ATTACK; // base attack
        $_SESSION['playerdefense'] = PLAYER_START_DEFENSE; // base defense
        $_SESSION['player_init'] = true; // flag player setup
        $_SESSION['message'] = "welcome adventurer find a way out";
    }

    // check if player died last turn
    if ($_SESSION['playerhealth'] <= 0) {
         $_SESSION['message'] = "u died game over man <a href='reset.php'>try again?</a>";
         // maybe lock actions here? simpler just show message
    }
}

// --- data fetching functions ---

// get all details for a room
function getroomdata($pdo, $roomid) {
    $stmt = $pdo->prepare("SELECT * FROM rooms WHERE id = ?");
    $stmt->execute([$roomid]);
    $room = $stmt->fetch();
    if (!$room) { // safety check if room doesnt exist somehow
        $_SESSION['message'] = "whoops room went missing resetting u";
        unset($_SESSION['player_init']); // force reinit
        header('Location: game.php'); // redirect to try again
        exit();
    }
    return $room;
}

// get items currently in a specific room
function getitems_inroom($pdo, $roomid) {
    $stmt = $pdo->prepare("SELECT * FROM items WHERE room_id = ?");
    $stmt->execute([$roomid]);
    return $stmt->fetchAll();
}

// get npcs currently in a specific room only alive ones
function getnpcs_inroom($pdo, $roomid) {
    $stmt = $pdo->prepare("SELECT * FROM npcs WHERE room_id = ? AND is_alive = TRUE");
    $stmt->execute([$roomid]);
    return $stmt->fetchAll();
}

// get data for one specific item by name careful uses unique name
function getitemdata($pdo, $itemname) {
     $stmt = $pdo->prepare("SELECT * FROM items WHERE name = ?");
     $stmt->execute([$itemname]);
     return $stmt->fetch();
}

// --- player state functions ---

// check if player has item
function playerhasitem($itemname) {
    return in_array($itemname, $_SESSION['inv']);
}

// add item name to session inventory
function additemtoinv($itemname) {
    if (!playerhasitem($itemname)) {
        $_SESSION['inv'][] = $itemname;
    }
}

// remove item name from session inventory
function removeitemfrominv($itemname) {
    $_SESSION['inv'] = array_filter($_SESSION['inv'], function($i) use ($itemname) {
        return $i !== $itemname;
    });
}

// get player total attack or defense including item bonuses
function getplayerstat($stat_type) {
    $base_stat = $_SESSION['player' . $stat_type] ?? 0; // health attack or defense
    $item_bonus = 0;
    $pdo = getdb(); // need db connection
    foreach ($_SESSION['inv'] as $itemname) {
        $item = getitemdata($pdo, $itemname);
        if ($item && $item[$stat_type . '_bonus'] > 0) {
            $item_bonus += $item[$stat_type . '_bonus'];
        }
    }
    return $base_stat + $item_bonus;
}


// --- action processing ---

// handles all player actions updates state returns message
function processaction($pdo, $action, $target) {
    $message = ""; // feedback message for player
    $playerloc = $_SESSION['playerloc'];
    $currentroom = getroomdata($pdo, $playerloc); // need current room info

    // dont allow actions if player dead
    if ($_SESSION['playerhealth'] <= 0 && $action !== null) {
        return "u cant do anything ur kinda dead try reset";
    }

    switch ($action) {
        case 'go':
            $dir = $target;
            $targetroomid = null;
            $required_item = $currentroom['required_item'] ?? null;
            $next_room_key = $dir . '_id'; // e.g. north_id south_id

            if (isset($currentroom[$next_room_key]) && $currentroom[$next_room_key]) {
                $targetroomid = $currentroom[$next_room_key];
                // check if next room needs item
                $nextroomdata = getroomdata($pdo, $targetroomid);
                $item_needed_to_enter = $nextroomdata['required_item'] ?? null;

                if($item_needed_to_enter && !playerhasitem($item_needed_to_enter)){
                    $message = "the way is blocked seems u need a ".htmlspecialchars($item_needed_to_enter);
                    $targetroomid = null; // block move
                }

            } else {
                 $message = "u cant go that way";
            }

            if ($targetroomid) {
                $_SESSION['playerloc'] = $targetroomid;
                $message = "u go $dir";
            }
            break; // end go

        case 'take':
            $itemname = $target;
            $stmt = $pdo->prepare("SELECT id, name FROM items WHERE room_id = ? AND name = ? AND takeable = TRUE");
            $stmt->execute([$playerloc, $itemname]);
            $item = $stmt->fetch();

            if ($item) {
                additemtoinv($item['name']);
                // remove item from room db
                $stmt = $pdo->prepare("UPDATE items SET room_id = NULL WHERE id = ?");
                $stmt->execute([$item['id']]);
                $message = "u took the " . htmlspecialchars($item['name']);
            } else {
                $message = "u cant take that";
            }
            break; // end take

        case 'drop':
             $itemname = $target;
             if(playerhasitem($itemname)){
                 $itemdata = getitemdata($pdo, $itemname);
                 if($itemdata && $itemdata['can_drop']){
                     removeitemfrominv($itemname);
                     // put item back in current room db
                     $stmt = $pdo->prepare("UPDATE items SET room_id = ? WHERE name = ?");
                     $stmt->execute([$playerloc, $itemname]);
                     $message = "u dropped the ".htmlspecialchars($itemname);
                 } else {
                     $message = "u cant drop the ".htmlspecialchars($itemname);
                 }
             } else {
                 $message = "u dont have a ".htmlspecialchars($itemname);
             }
             break; // end drop

        case 'use':
            $itemname = $target;
            if(playerhasitem($itemname)){
                $itemdata = getitemdata($pdo, $itemname);
                if($itemdata && $itemdata['use_effect']){
                    $effect = $itemdata['use_effect'];
                    // parse effect simple examples
                    if(strpos($effect, 'heal') === 0){
                        $amount = (int) filter_var($effect, FILTER_SANITIZE_NUMBER_INT);
                        $_SESSION['playerhealth'] += $amount;
                        $message = "u used ".htmlspecialchars($itemname)." and healed ".$amount." hp";
                        removeitemfrominv($itemname); // consume item
                        // maybe remove from db too? depends if reusable
                    }
                    // add more effects like 'unlock direction' 'damage npc' etc
                    else {
                         $message = htmlspecialchars($itemname)." doesnt seem usable right now";
                    }
                } else {
                    $message = "cant use the ".htmlspecialchars($itemname);
                }
            } else {
                $message = "u dont have a ".htmlspecialchars($itemname);
            }
            break; // end use

        case 'talk':
            $npcname = $target;
            $stmt = $pdo->prepare("SELECT dialogue FROM npcs WHERE room_id = ? AND name = ? AND is_alive = TRUE");
            $stmt->execute([$playerloc, $npcname]);
            $npc = $stmt->fetch();
            if ($npc && $npc['dialogue']) {
                $message = htmlspecialchars($npcname) . " says: \"" . htmlspecialchars($npc['dialogue']) . "\"";
            } elseif($npc) {
                 $message = htmlspecialchars($npcname) . " doesnt seem talkative";
            } else {
                $message = "who u talkin to?";
            }
            break; // end talk

        case 'attack':
            $npcname = $target;
            // get npc data
            $stmt = $pdo->prepare("SELECT * FROM npcs WHERE room_id = ? AND name = ? AND is_alive = TRUE");
            $stmt->execute([$playerloc, $npcname]);
            $npc = $stmt->fetch();

            if($npc){
                $message = ""; // start combat log
                $player_attack = getplayerstat('attack');
                $player_defense = getplayerstat('defense');
                $npc_attack = $npc['attack'];
                $npc_defense = $npc['defense'];
                $npc_health = $npc['health'];
                $npc_id = $npc['id'];

                // player attacks npc
                $player_damage = max(0, $player_attack - $npc_defense); // cant do negative damage
                $npc_health -= $player_damage;
                $message .= "u hit ".htmlspecialchars($npcname)." for ".$player_damage." damage. ";

                if($npc_health <= 0) {
                    // npc defeated
                    $message .= htmlspecialchars($npcname)." is defeated! ";
                    // update npc state in db
                    $stmt = $pdo->prepare("UPDATE npcs SET is_alive = FALSE, room_id = NULL, health = 0 WHERE id = ?");
                    $stmt->execute([$npc_id]);

                    // check drops
                    if($npc['drops_item_name']){
                        $dropped_item_name = $npc['drops_item_name'];
                        // place item in current room
                        $stmt = $pdo->prepare("UPDATE items SET room_id = ? WHERE name = ?");
                        $stmt->execute([$playerloc, $dropped_item_name]);
                        $message .= "they dropped a ".htmlspecialchars($dropped_item_name)."!";
                    }
                    // no npc counter attack if defeated
                } else {
                    // npc still alive update health in db
                    $stmt = $pdo->prepare("UPDATE npcs SET health = ? WHERE id = ?");
                    $stmt->execute([$npc_health, $npc_id]);

                    // npc attacks player
                    $npc_damage = max(0, $npc_attack - $player_defense);
                    $_SESSION['playerhealth'] -= $npc_damage;
                    $message .= htmlspecialchars($npcname)." hits u for ".$npc_damage." damage.";

                    if($_SESSION['playerhealth'] <= 0){
                        $message .= " u have died!";
                        // game over handled in initplayer check next load
                    }
                }

            } else {
                $message = "who u trying to fight?";
            }
            break; // end attack

        case 'look':
             $message = "u look around the " . htmlspecialchars($currentroom['name']);
             break; // end look

        default:
            if($action !== null) $message = "unknown action"; // handle weird actions?
    } // end switch action

    return $message;
} // end processaction

?>