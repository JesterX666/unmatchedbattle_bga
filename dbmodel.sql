
-- ------
-- BGA framework: © Gregory Isabelli <gisabelli@boardgamearena.com> & Emmanuel Colin <ecolin@boardgamearena.com>
-- UnmatchedBattle implementation : © <Your name here> <Your email address here>
-- 
-- This code has been produced on the BGA studio platform for use on http://boardgamearena.com.
-- See http://en.boardgamearena.com/#!doc/Studio for more information.
-- -----

-- dbmodel.sql

-- This is the file where you are describing the database schema of your game
-- Basically, you just have to export from PhpMyAdmin your table structure and copy/paste
-- this export here.
-- Note that the database itself and the standard tables ("global", "stats", "gamelog" and "player") are
-- already created and must not be created here

-- Note: The database schema is created from this file when the game starts. If you modify this file,
--       you have to restart a game to see your changes in database.

-- Example 1: create a standard "card" table to be used with the "Deck" tools (see example game "hearts"):

-- CREATE TABLE IF NOT EXISTS `card` (
--   `card_id` int(10) unsigned NOT NULL AUTO_INCREMENT,
--   `card_type` varchar(16) NOT NULL,
--   `card_type_arg` int(11) NOT NULL,
--   `card_location` varchar(16) NOT NULL,
--   `card_location_arg` int(11) NOT NULL,
--   PRIMARY KEY (`card_id`)
-- ) ENGINE=InnoDB DEFAULT CHARSET=utf8 AUTO_INCREMENT=1 ;


-- Add a the hero choice to the player table
ALTER TABLE `player` ADD `hero` VARCHAR(20), ADD `team` INT(1), ADD `first_action` VARCHAR(1), ADD `second_action` VARCHAR(1), ADD `alice_size` VARCHAR(1);

-- Cards and their placement
CREATE TABLE IF NOT EXISTS `cards` (
  `card_id` int(10) unsigned NOT NULL AUTO_INCREMENT,
  `card_type` varchar(16) NOT NULL,
  `card_type_arg` int(11) NOT NULL,
  `card_location` varchar(16) NOT NULL,
  `card_location_arg` int(11) NOT NULL,
  PRIMARY KEY (`card_id`)
) ENGINE=InnoDB DEFAULT CHARSET=utf8 AUTO_INCREMENT=1 ;

-- Tokens and their placement
CREATE TABLE IF NOT EXISTS `tokens` (
  `token_name` varchar(20) NOT NULL,
  `area_id` int(4) NOT NULL,
  `health` int(2) NOT NULL,
  PRIMARY KEY (`token_name`)
) ENGINE=InnoDB DEFAULT CHARSET=utf8;

-- Action in progress and their special arguments
-- Action Type :
-- Move : movement
---- arg1: Any, Own or a list of specific Token_Id separated by commas
---- arg2: Movement Amount
---- arg3: Movement Type (Normal, IgnoreEnemies)
-- Scheme : scheme
---- arg1: Scheme Card Id
-- Attack : attack
CREATE TABLE IF NOT EXISTS `action_in_progress` (
  `action_type` varchar(10) NOT NULL,
  `arg1` varchar(50) NOT NULL,
  `arg2` varchar(20) NULL,
  `arg3` varchar(20) NULL,
  PRIMARY KEY (`action_type`)
) ENGINE=InnoDB DEFAULT CHARSET=utf8;
