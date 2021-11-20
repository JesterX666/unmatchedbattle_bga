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
 * material.inc.php
 *
 * UnmatchedBattle game material description
 *
 * Here, you can describe the material of your game with PHP variables.
 *   
 * This file is loaded in your game logic class constructor, ie these variables
 * are available everywhere in your game logic code.
 *
 */

 // List of boards

 $this->boards = array(
    1 => array(
        'name' => 'Marmoreal',
        'map' => 'Marmoreal.jpg'
    ),
    2 => array(
        'name' => 'Sarpedon',
        'map' => 'Sarpedon.jpg'
    )
    );


 // List of playable heros
$this->heros = array(
    1 => array(
        "name" => "Alice",
    ),
    2 => array(
        "name" => "Arthur",
    ),
    3 => array(
        "name" => "Medusa",
    ),
    4 => array(
        "name" => "Sinbad",
    ),
);

// List of cards
$this->cardtypes = array(
    1 => array( "name" => "Reference Card",
                "type" => "reference",
                "deck" => "Special",
                "image" => "Reference Card.jpg"
                ),
    2 => array( "name" => "Reference Card - Alice",
                "type" => "reference",
                "deck" => "Alice",
                "image" => "Reference Card-Alice.jpg"
                ),
    3 => array( "name" => "Card Back - Alice",
                "type" => "back",
                "deck" => "Alice",
                "image" => "UM card back_Alice.jpg"
                ),
    4 => array( "name" => "Reference Card - Arthur",
                "type" => "reference",
                "deck" => "Arthur",
                "image" => "Reference Card-Arthur.jpg"
                ),
    5 => array( "name" => "Card Back - Arthur",
                "type" => "back",
                "deck" => "Arthur",
                "image" => "UM card back_Arthur.jpg"
                ),
    6 => array( "name" => "Reference Card - Medusa",
                "type" => "reference",
                "deck" => "Medusa",  
                "image" => "Reference Card-Medusa.jpg"
                ),
    7 => array( "name" => "Card Back - Medusa",
                "type" => "back",
                "deck" => "Medusa",
                "image" => "UM card back_Medusa.jpg"
                ),
    8 => array( "name" => "Reference Card - Sinbad",
                "type" => "reference",
                "deck" => "Sinbad",
                "image" => "Reference Card-Sinbad.jpg"
                ),
    9 => array( "name" => "Card Back - Sinbad",
                "type" => "back",
                "deck" => "Sinbad",
                "image" => "UM card back_Sinbad.jpg"
                ),    
);



