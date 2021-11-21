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
                "internal_id" => 0,
                "deck" => "Special",
                "image" => "Reference Card.jpg",
                "nbr" => 1
                ),

    // Alice
    2 => array( "name" => "Reference Card - Alice",
                "type" => "reference",
                "internal_id" => 0,
                "deck" => "Alice",
                "image" => "Reference Card-Alice.jpg",
                "nbr" => 1
                ),
    3 => array( "name" => "Card Back - Alice",
                "type" => "back",
                "internal_id" => 0,
                "deck" => "Alice",
                "image" => "UM card back_Alice.jpg",
                "nbr" => 1
                ),
    4 => array( "name" => "Looking Glass",
                "type" => "card",
                "internal_id" => 101,
                "deck" => "Alice",
                "image" => "UM card-Alice1.jpg",
                "nbr" => 2
                ),
    5 => array( "name" => "Snicker-Snack",
                "type" => "card",
                "internal_id" => 102,
                "deck" => "Alice",
                "image" => "UM card-Alice2.jpg",
                "nbr" => 1
                ),
    6 => array( "name" => "O Frabjous Day!",
                "type" => "card",
                "internal_id" => 103,
                "deck" => "Alice",
                "image" => "UM card-Alice3.jpg",
                "nbr" => 1
                ),
    7 => array( "name" => "The Other Side of the Mushroom",
                "type" => "card",
                "internal_id" => 104,
                "deck" => "Alice",
                "image" => "UM card-Alice4.jpg",
                "nbr" => 1
                ),
    8 => array( "name" => "Eat Me",
                "type" => "card",
                "internal_id" => 105,
                "deck" => "Alice",
                "image" => "UM card-Alice5.jpg",
                "nbr" => 2
                ),
    9 => array( "name" => "I'm Late, I'm Late",
                "type" => "card",
                "internal_id" => 106,
                "deck" => "Alice",
                "image" => "UM card-Alice6.jpg",
                "nbr" => 3
                ),
    10 => array( "name" => "Drink Me",
                "type" => "card",
                "internal_id" => 107,
                "deck" => "Alice",
                "image" => "UM card-Alice7.jpg",
                "nbr" => 2
                ),
    11 => array( "name" => "Jaws That Bite",
                "type" => "card",
                "internal_id" => 108,
                "deck" => "Alice",
                "image" => "UM card-Alice8.jpg",
                "nbr" => 2
                ),
    12 => array( "name" => "Claws That Catch",
                "type" => "card",
                "internal_id" => 109,
                "deck" => "Alice",
                "image" => "UM card-Alice9.jpg",
                "nbr" => 2
                ),
    13 => array( "name" => "Momentous Shift",
                "type" => "card",
                "internal_id" => 110,
                "deck" => "Alice",
                "image" => "UM card-Alice10.jpg",
                "nbr" => 2
                ),
    14 => array( "name" => "Skirmish",
                "type" => "card",
                "internal_id" => 111,
                "deck" => "Alice",
                "image" => "UM card-Alice11.jpg",
                "nbr" => 2
                ),
    15 => array( "name" => "Mad as a Hatter",
                "type" => "card",
                "internal_id" => 112,
                "deck" => "Alice",
                "image" => "UM card-Alice12.jpg",
                "nbr" => 2                
                ),
    16 => array( "name" => "Manxome Foe",
                "type" => "card",
                "internal_id" => 113,
                "deck" => "Alice",
                "image" => "UM card-Alice13.jpg",
                "nbr" => 2
                ),
    17 => array( "name" => "Feint",
                "type" => "card",
                "internal_id" => 114,
                "deck" => "Alice",
                "image" => "UM card-Alice14.jpg",
                "nbr" => 3
                ),
    18 => array( "name" => "Regroup",
                "type" => "card",
                "internal_id" => 115,
                "deck" => "Alice",
                "image" => "UM card-Alice15.jpg",
                "nbr" => 3
                ),

    // Arthur
    19 => array( "name" => "Reference Card - Arthur",
                "type" => "reference",
                "internal_id" => 0,
                "deck" => "Arthur",
                "image" => "Reference Card-Arthur.jpg",
                "nbr" => 1                
                ),
    20 => array( "name" => "Card Back - Arthur",
                "type" => "back",
                "internal_id" => 0,
                "deck" => "Arthur",
                "image" => "UM card back_Arthur.jpg",
                "nbr" => 1
                ),
    21 => array( "name" => "Noble Sacrifice",
                "type" => "card",
                "internal_id" => 201,
                "deck" => "Arthur",
                "image" => "UM card-Arthur1.jpg",
                "nbr" => 3
                ),                     
    22 => array( "name" => "Excalibur",
                "type" => "card",
                "internal_id" => 202,
                "deck" => "Arthur",
                "image" => "UM card-Arthur2.jpg",
                "nbr" => 1
                ),                     
    23 => array( "name" => "The Aid of Morgana",
                "type" => "card",
                "internal_id" => 203,
                "deck" => "Arthur",
                "image" => "UM card-Arthur3.jpg",
                "nbr" => 1
                ),                     
    24 => array( "name" => "Divine Intervention",
                "type" => "card",
                "internal_id" => 204,
                "deck" => "Arthur",
                "image" => "UM card-Arthur4.jpg",
                "nbr" => 2
                ),
    25 => array( "name" => "The Holy Grail",
                "type" => "card",
                "internal_id" => 205,
                "deck" => "Arthur",
                "image" => "UM card-Arthur5.jpg",
                "nbr" => 1
                ),
    26 => array( "name" => "The Lady of the Lake",
                "type" => "card",
                "internal_id" => 206,
                "deck" => "Arthur",
                "image" => "UM card-Arthur6.jpg",
                "nbr" => 1
                ),
    27 => array( "name" => "Prophecy",
                "type" => "card",
                "internal_id" => 207,
                "deck" => "Arthur",
                "image" => "UM card-Arthur7.jpg",
                "nbr" => 1
                ),
    28 => array( "name" => "Bewilderment",
                "type" => "card",
                "internal_id" => 208,
                "deck" => "Arthur",
                "image" => "UM card-Arthur8.jpg",
                "nbr" => 2
                ),
    29 => array( "name" => "Aid the Chosen One",
                "type" => "card",
                "internal_id" => 209,
                "deck" => "Arthur",
                "image" => "UM card-Arthur9.jpg",
                "nbr" => 1
                ),
    30 => array( "name" => "Restless Spirits",
                "type" => "card",
                "internal_id" => 210,
                "deck" => "Arthur",
                "image" => "UM card-Arthur10.jpg",
                "nbr" => 1
                ),
    31 => array( "name" => "Command the Storms",
                "type" => "card",
                "internal_id" => 211,
                "deck" => "Arthur",
                "image" => "UM card-Arthur11.jpg",
                "nbr" => 2
                ),
    32 => array( "name" => "Swift Strike",
                "type" => "card",
                "internal_id" => 212,
                "deck" => "Arthur",
                "image" => "UM card-Arthur12.jpg",
                "nbr" => 2
                ),
    33 => array( "name" => "Skirmish",
                "type" => "card",
                "internal_id" => 213,
                "deck" => "Arthur",
                "image" => "UM card-Arthur13.jpg",
                "nbr" => 3
                ),
    34 => array( "name" => "Momentous Shift",
                "type" => "card",
                "internal_id" => 214,
                "deck" => "Arthur",
                "image" => "UM card-Arthur14.jpg",
                "nbr" => 3
                ),
    35 => array( "name" => "Feint",
                "type" => "card",
                "internal_id" => 215,
                "deck" => "Arthur",
                "image" => "UM card-Arthur15.jpg",
                "nbr" => 3
                ),
    36 => array( "name" => "Regroup",
                "type" => "card",
                "internal_id" => 216,
                "deck" => "Arthur",
                "image" => "UM card-Arthur16.jpg",
                "nbr" => 3
                ),

    // Medusa
    37 => array( "name" => "Reference Card - Medusa",
                "type" => "reference",
                "internal_id" => 0,
                "deck" => "Medusa",  
                "image" => "Reference Card-Medusa.jpg",
                "nbr" => 1
                ),
    38 => array( "name" => "Card Back - Medusa",
                "type" => "back",
                "internal_id" => 0,
                "deck" => "Medusa",
                "image" => "UM card back_Medusa.jpg",
                "nbr" => 1
                ),
    39 => array( "name" => "Gaze of Stone",
                "type" => "card",
                "internal_id" => 301,
                "deck" => "Medusa",
                "image" => "UM card-Medusa1.jpg",
                "nbr" => 3
                ),
    40 => array( "name" => "A Momentary Glance",
                "type" => "card",
                "internal_id" => 302,
                "deck" => "Medusa",
                "image" => "UM card-Medusa2.jpg",
                "nbr" => 2
                ),
    41 => array( "name" => "Hiss and Slither",
                "type" => "card",
                "internal_id" => 303,
                "deck" => "Medusa",
                "image" => "UM card-Medusa3.jpg",
                "nbr" => 3
                ),
    42 => array( "name" => "The Hound of Mighty Zeus",
                "type" => "card",
                "internal_id" => 304,
                "deck" => "Medusa",
                "image" => "UM card-Medusa4.jpg",
                "nbr" => 2
                ),
    43 => array( "name" => "Clutching Claws",
                "type" => "card",
                "internal_id" => 305,
                "deck" => "Medusa",
                "image" => "UM card-Medusa5.jpg",
                "nbr" => 3
                ),
    44 => array( "name" => "Winged Frenzy",
                "type" => "card",
                "internal_id" => 306,
                "deck" => "Medusa",
                "image" => "UM card-Medusa6.jpg",
                "nbr" => 2
                ),
    45 => array( "name" => "Second Shot",
                "type" => "card",
                "internal_id" => 307,
                "deck" => "Medusa",
                "image" => "UM card-Medusa7.jpg",
                "nbr" => 3
                ),
    46 => array( "name" => "Dash",
                "type" => "card",
                "internal_id" => 308,
                "deck" => "Medusa",
                "image" => "UM card-Medusa8.jpg",
                "nbr" => 3
                ),
    47 => array( "name" => "Snipe",
                "type" => "card",
                "internal_id" => 309,
                "deck" => "Medusa",
                "image" => "UM card-Medusa9.jpg",
                "nbr" => 3
                ),
    48 => array( "name" => "Feint",
                "type" => "card",
                "internal_id" => 310,
                "deck" => "Medusa",
                "image" => "UM card-Medusa10.jpg",
                "nbr" => 3
                ),
    49 => array( "name" => "Regroup",
                "type" => "card",
                "internal_id" => 311,
                "deck" => "Medusa",
                "image" => "UM card-Medusa11.jpg",
                "nbr" => 3
                ),

    // Sinbad
    50 => array( "name" => "Reference Card - Sinbad",
                "type" => "reference",
                "internal_id" => 0,
                "deck" => "Sinbad",
                "image" => "Reference Card-Sinbad.jpg",
                "nbr" => 1
                ),
    51 => array( "name" => "Card Back - Sinbad",
                "type" => "back",
                "internal_id" => 0,
                "deck" => "Sinbad",
                "image" => "UM card back_Sinbad.jpg",
                "nbr" => 1
                ),    
    52 => array( "name" => "Toil and Danger",
                "type" => "card",
                "internal_id" => 401,
                "deck" => "Sinbad",
                "image" => "UM card-Sinbad1.jpg",
                "nbr" => 4
                ),
    53 => array( "name" => "Voyage Home",
                "type" => "card",
                "internal_id" => 402,
                "deck" => "Sinbad",
                "image" => "UM card-Sinbad2.jpg",
                "nbr" => 1
                ),
    54 => array( "name" => "Riches Beyond Compare",
                "type" => "card",
                "internal_id" => 403,
                "deck" => "Sinbad",
                "image" => "UM card-Sinbad3.jpg",
                "nbr" => 2
                ),
    55 => array( "name" => "By Fortune and Fate",
                "type" => "card",
                "internal_id" => 404,
                "deck" => "Sinbad",
                "image" => "UM card-Sinbad4.jpg",
                "nbr" => 3
                ),
    56 => array( "name" => "Voyage to the Island That Was a Whale",
                "type" => "card",
                "internal_id" => 405,
                "deck" => "Sinbad",
                "image" => "UM card-Sinbad5.jpg",
                "nbr" => 1
                ),
    57 => array( "name" => "Voyage to the Valley of the Giant Snakes",
                "type" => "card",
                "internal_id" => 406,
                "deck" => "Sinbad",
                "image" => "UM card-Sinbad6.jpg",
                "nbr" => 1
                ),
    58 => array( "name" => "Voyage to the Creature With Eyes Like Coals of Fire",
                "type" => "card",
                "internal_id" => 407,
                "deck" => "Sinbad",
                "image" => "UM card-Sinbad7.jpg",
                "nbr" => 1
                ),
    59 => array( "name" => "Voyage to the Cannibals With the Root of Madness",
                "type" => "card",
                "internal_id" => 408,
                "deck" => "Sinbad",
                "image" => "UM card-Sinbad8.jpg",
                "nbr" => 1
                ),
    60 => array( "name" => "Voyage to the City of the Man-Eating Apes",
                "type" => "card",
                "internal_id" => 409,
                "deck" => "Sinbad",
                "image" => "UM card-Sinbad9.jpg",
                "nbr" => 1
                ),
    61 => array( "name" => "Voyage to the City of the King of Serendib",
                "type" => "card",
                "internal_id" => 410,
                "deck" => "Sinbad",
                "image" => "UM card-Sinbad10.jpg",
                "nbr" => 1
                ),
    62 => array( "name" => "Commanding Impact",
                "type" => "card",
                "internal_id" => 411,
                "deck" => "Sinbad",
                "image" => "UM card-Sinbad11.jpg",
                "nbr" => 1
                ),
    63 => array( "name" => "Leap Away",
                "type" => "card",
                "internal_id" => 412,
                "deck" => "Sinbad",
                "image" => "UM card-Sinbad12.jpg",
                "nbr" => 2
                ),
    64 => array( "name" => "Exploit",
                "type" => "card",
                "internal_id" => 413,
                "deck" => "Sinbad",
                "image" => "UM card-Sinbad13.jpg",
                "nbr" => 2
                ),
    65 => array( "name" => "Momentous Shift",
                "type" => "card",
                "internal_id" => 414,
                "deck" => "Sinbad",
                "image" => "UM card-Sinbad14.jpg",
                "nbr" => 3
                ),
    66 => array( "name" => "Feint",
                "type" => "card",
                "internal_id" => 415,
                "deck" => "Sinbad",
                "image" => "UM card-Sinbad15.jpg",
                "nbr" => 3
                ),
    67 => array( "name" => "Regroup",
                "type" => "card",
                "internal_id" => 416,
                "deck" => "Sinbad",
                "image" => "UM card-Sinbad16.jpg",
                "nbr" => 3
                ),
            );



