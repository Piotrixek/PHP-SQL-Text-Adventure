<!DOCTYPE html>
<html>
<head>
    <title>php text adventure deluxe</title>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <style>
        body { font-family: monospace; background: #1a1a1a; color: #e0e0e0; padding: 15px; max-width: 800px; margin: 0 auto; }
        a { color: #6f6; text-decoration: none; }
        a:hover { text-decoration: underline; color: #af8;}
        .header-info { background: #2a2a2a; padding: 5px 10px; margin-bottom: 15px; border-bottom: 1px solid #444; font-size: 0.9em; display: flex; justify-content: space-between;}
        .room-name { color: #ffb; font-size: 1.6em; margin-bottom: 10px; border-bottom: 1px dashed #555; padding-bottom: 5px;}
        .description { margin-bottom: 15px; color: #ccc; line-height: 1.4; }
        .section { margin-bottom: 20px; background: #252525; padding: 10px; border: 1px solid #333; border-radius: 3px; }
        .section h3 { color: #ddd; margin-top: 0; margin-bottom: 10px; border-bottom: 1px solid #555; padding-bottom: 5px; font-size: 1.1em; }
        ul { list-style: none; padding: 0; margin: 0; }
        li { margin-bottom: 6px; }
        .message { background: #4a4a3a; padding: 10px; margin-bottom: 15px; border-left: 4px solid #ff9; color: #ffc; }
        .error { background: #5a3a3a; border-left-color: #f88; color: #fbb; } /* specific error style */
        .player-stats span { margin-right: 15px; }
        .player-stats .health { color: #f88; }
        .player-stats .attack { color: #fcc; }
        .player-stats .defense { color: #aaf; }
        .action-link { margin-right: 5px; } /* spacing for inline links */
        small { color: #888; }
    </style>
</head>
<body>
    <div class="header-info">
        <div class="player-stats">
             <span class="health">hp: <?php echo (int)($_SESSION['playerhealth'] ?? 0); ?></span>
             <span class="attack">atk: <?php echo getplayerstat('attack'); ?></span>
             <span class="defense">def: <?php echo getplayerstat('defense'); ?></span>
        </div>
        <div><a href="reset.php" onclick="return confirm('r u sure u wanna reset?')">[reset game]</a></div>
    </div>