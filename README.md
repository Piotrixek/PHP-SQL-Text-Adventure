# Simple PHP/SQL Text Adventure Game

A classic text-based adventure/RPG built purely with PHP and SQL. Explore rooms, find items, talk to NPCs, engage in basic combat, and try to find your way out. Designed to run easily on a standard web server with PHP and MySQL (like XAMPP).

## Features

* **Room Exploration:** Navigate between different areas using directional links.
* **Item Interaction:** Find, take, drop, and use items. Some items provide stat bonuses or have specific effects (like healing).
* **NPCs:** Encounter non-player characters. Talk to them or fight them!
* **Basic Combat:** Turn-based combat against NPCs. Player and NPCs have health, attack, and defense stats.
* **Player Stats:** Track player health, attack, and defense (influenced by items).
* **Session-Based:** Game state is tracked using PHP sessions.
* **Reset Option:** Easily reset your progress and start over.
* **Pure PHP/SQL:** No JavaScript involved for core game logic – everything happens on the server.

## Tech Stack

* **PHP:** Core application logic.
* **SQL (MySQL/MariaDB):** Database for storing game world data (rooms, items, NPCs).
* **HTML/CSS:** Front-end display.

## Requirements

* A web server that runs PHP (like Apache from XAMPP, MAMP, WAMP, or a standard Linux server).
* A MySQL or MariaDB database server.
* PHP Data Objects (PDO) extension enabled (usually enabled by default with PHP).
* A web browser.

## Setup Instructions

1.  **Download Files:** Clone this repository or download the source code files.
2.  **Place Files:** Put the entire project folder (e.g., `text_game`) into your web server's document root (like `htdocs` in XAMPP). The structure should look like this:
    ```
    /your-web-root/
    └── text_game/
        ├── config.php
        ├── includes/
        │   ├── db.php
        │   └── functions.php
        ├── templates/
        │   ├── header.php
        │   ├── footer.php
        │   ├── room.php
        │   ├── inventory.php
        │   ├── messages.php
        │   └── actions.php
        ├── game.php
        ├── reset.php
        └── db_setup.sql
    ```
3.  **Database Setup:**
    * Access your database management tool (like phpMyAdmin, usually at `http://localhost/phpmyadmin`).
    * Create a new database named `text_game`.
    * Select the `text_game` database.
    * Go to the "SQL" tab or "Import" tab.
    * Open the `db_setup.sql` file from the project, copy its contents, and paste them into the SQL query box. Alternatively, use the "Import" function to upload the `db_setup.sql` file.
    * Run the SQL query/Import the file. This will create the necessary tables (`rooms`, `items`, `npcs`) and populate them with sample data.
4.  **Configuration (if needed):**
    * Open `config.php`.
    * If your database username or password is *not* the default XAMPP `root` with no password, update the `DB_USER` and `DB_PASS` constants accordingly.
5.  **Run the Game:**
    * Make sure your web server (Apache) and database server (MySQL) are running via your XAMPP control panel (or equivalent).
    * Open your web browser and navigate to the `game.php` file within the project folder. Example URL: `http://localhost/text_game/game.php`

## How to Play

* The game screen shows your current location description, items you can see, any creatures present, and your available actions.
* Your current health, attack, and defense stats are shown at the top.
* Click links like `[go north]`, `[take rusty key]`, `[talk grumpy goblin]`, `[attack grumpy goblin]`, `[use health potion]`, or `[drop pointy stick]` to interact with the world.
* Every action reloads the page and updates the game state.
* Read the messages at the top to see the results of your actions.
* Your goal is typically to explore, overcome obstacles (like locked doors or enemies), and reach a designated end room (like the "victory lawn" in the sample data).
* Use the `[reset game]` link at the top right if you get stuck or want to start over.

## Future Ideas (Maybe!)

* More complex puzzles (item combinations, levers).
* More detailed combat (special abilities, different weapon types).
* Player persistence using database accounts instead of just sessions.
* More sophisticated NPC interactions/quests.
* Saving/Loading game state.

---

Have fun playing (and maybe expanding) this simple adventure.
