<?php

/**
 *------
 * BGA framework: © Gregory Isabelli <gisabelli@boardgamearena.com> & Emmanuel Colin <ecolin@boardgamearena.com>
 * UnmatchedBattle implementation : © <Your name here> <Your email address here>
 *
 * This code has been produced on the BGA studio platform for use on http://boardgamearena.com.
 * See http://en.boardgamearena.com/#!doc/Studio for more information.
 * -----
 *
 * gameoptions.inc.php
 *
 * UnmatchedBattle game options description
 * 
 * In this file, you can define your game options (= game variants).
 *   
 * Note: If your game has no variant, you don't have to modify this file.
 *
 * Note²: All options defined in this file should have a corresponding "game state labels"
 *        with the same ID (see "initGameStateLabels" in unmatchedbattle.game.php)
 *
 * !! It is not a good idea to modify this file when a game is running !!
 *
 */

$game_options = array(

    /*
    
    // note: game variant ID should start at 100 (ie: 100, 101, 102, ...). The maximum is 199.
    100 => array(
                'name' => totranslate('my game option'),    
                'values' => array(

                            // A simple value for this option:
                            1 => array( 'name' => totranslate('option 1') )

                            // A simple value for this option.
                            // If this value is chosen, the value of "tmdisplay" is displayed in the game lobby
                            2 => array( 'name' => totranslate('option 2'), 'tmdisplay' => totranslate('option 2') ),

                            // Another value, with other options:
                            //  description => this text will be displayed underneath the option when this value is selected to explain what it does
                            //  beta=true => this option is in beta version right now (there will be a warning)
                            //  alpha=true => this option is in alpha version right now (there will be a warning, and starting the game will be allowed only in training mode except for the developer)
                            //  nobeginner=true  =>  this option is not recommended for beginners
                            //  firstgameonly=true  =>  this option is recommended only for the first game (discovery option)
                            3 => array( 'name' => totranslate('option 3'), 'description' => totranslate('this option does X'), 'beta' => true, 'nobeginner' => true )
                        ),
                'default' => 1
            ),

    */

        // Choice for the battleground map, the index of the array corresponds directly to the map ID
        100 => array(
            'name' => totranslate('Map choice'),    
            'values' => array(                
                        0 => array( 'name' => totranslate('Random') ),
                        1 => array( 'name' => totranslate('Marmoreal') ),
                        2 => array( 'name' => totranslate('Sarpedon') ),
                    ),
            'default' => 0            
        ),

        // Is the game played in teams (true) or alone (false).  Only available in 4 players mode.
        101 => array(
            'name' => totranslate('Team play'),    
            'values' => array(
                        1 => array( 'name' => totranslate('Yes') ),
                        2 => array( 'name' => totranslate('No') ),
                    ),
            'default' => 2,
            'displaycondition' => array( 
                // Note: do not display this option unless these conditions are met
                array(
                    'type' => 'minplayers',                    
                    'value' => array(4)
                ),
            )
        )
);


