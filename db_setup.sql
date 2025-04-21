
USE text_game;

-- drop old stuff start fresh
DROP TABLE IF EXISTS npcs;
DROP TABLE IF EXISTS items;
DROP TABLE IF EXISTS rooms;

-- table for places
CREATE TABLE rooms (
    id INT AUTO_INCREMENT PRIMARY KEY,
    name VARCHAR(100) NOT NULL,
    description TEXT,
    north_id INT NULL,
    south_id INT NULL,
    east_id INT NULL,
    west_id INT NULL,
    -- maybe need item to enter? store item name simple approach
    required_item VARCHAR(50) NULL
);

-- table for stuff
CREATE TABLE items (
    id INT AUTO_INCREMENT PRIMARY KEY,
    name VARCHAR(50) NOT NULL UNIQUE, -- unique name simpler to handle
    description TEXT,
    room_id INT NULL,         -- where it is initially null if gone/held sorta
    takeable BOOLEAN DEFAULT TRUE,
    can_drop BOOLEAN DEFAULT TRUE,
    -- what happens when used? 'heal 10' 'damage 5' 'unlock north' etc
    use_effect VARCHAR(100) NULL,
    attack_bonus INT DEFAULT 0,
    defense_bonus INT DEFAULT 0
);

-- table for non player characters
CREATE TABLE npcs (
    id INT AUTO_INCREMENT PRIMARY KEY,
    name VARCHAR(50) NOT NULL,
    description TEXT,
    room_id INT NULL, -- which room they in
    health INT DEFAULT 10,
    attack INT DEFAULT 2,
    defense INT DEFAULT 0,
    dialogue TEXT NULL, -- what they say when talked to
    -- maybe drops item on defeat? store item name
    drops_item_name VARCHAR(50) NULL,
    is_alive BOOLEAN DEFAULT TRUE -- track if defeated
);

-- --- SAMPLE DATA ---

-- rooms
INSERT INTO rooms (id, name, description, north_id, east_id, south_id, west_id, required_item) VALUES
(1, 'dusty room', 'ur in a small dusty room maybe sweep it Doors north and east look old', 2, 3, NULL, NULL, NULL),
(2, 'cold passage', 'a chilly stone passage wind blows from north u can go back south', 4, NULL, 1, NULL, NULL),
(3, 'storage closet', 'old crates cobwebs smells musty a key glints on floor west door leads back', NULL, NULL, NULL, 1, NULL),
(4, 'guard room', 'looks like a guard post a grumpy goblin blocks path north south goes back', 5, NULL, 2, NULL, NULL),
(5, 'outside?', 'sunlight maybe a door north needs a key south leads back', 6, NULL, 4, NULL, 'rusty key'),
(6, 'victory lawn', 'wow grass trees u made it congrats', NULL, NULL, 5, NULL, NULL);

-- items
INSERT INTO items (name, description, room_id, takeable, can_drop, use_effect, attack_bonus, defense_bonus) VALUES
('rusty key', 'an old key maybe opens something?', 3, TRUE, TRUE, NULL, 0, 0),
('health potion', 'red bubbly liquid looks healthy', 1, TRUE, TRUE, 'heal 15', 0, 0),
('pointy stick', 'better than fists maybe?', 2, TRUE, TRUE, NULL, 2, 0),
('goblin crown', 'small tacky crown dropped by goblin', NULL, FALSE, FALSE, NULL, 0, 0); -- initially not in world only dropped

-- npcs
INSERT INTO npcs (id, name, description, room_id, health, attack, defense, dialogue, drops_item_name, is_alive) VALUES
(1, 'grumpy goblin', 'short green mean lookin fella blocks path', 4, 20, 5, 1, '"grrr leave me alone human!"', 'goblin crown', TRUE);