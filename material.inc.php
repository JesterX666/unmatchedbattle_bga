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
                "internal_id" => "REF_ALL",
                "deck" => "Special",
                "image" => "Reference Card.jpg"
                ),

    // Alice
    2 => array( "name" => "Reference Card - Alice",
                "type" => "reference",
                "internal_id" => "REF_ALICE",
                "deck" => "Alice",
                "image" => "Reference Card-Alice.jpg"
                ),
    3 => array( "name" => "Card Back - Alice",
                "type" => "back",
                "internal_id" => "BACK_ALICE",
                "deck" => "Alice",
                "image" => "UM card back_Alice.jpg"
                ),
    4 => array( "name" => "Looking Glass",
                "type" => "card",
                "internal_id" => "ALICE_01",
                "deck" => "Alice",
                "image" => "UM card-Alice1.jpg"
                ),
    5 => array( "name" => "Looking Glass",
                "type" => "card",
                "internal_id" => "ALICE_01",
                "deck" => "Alice",
                "image" => "UM card-Alice1.jpg"
                ),
    6 => array( "name" => "Snicker-Snack",
                "type" => "card",
                "internal_id" => "ALICE_02",
                "deck" => "Alice",
                "image" => "UM card-Alice2.jpg"
                ),
    7 => array( "name" => "O Frabjous Day!",
                "type" => "card",
                "internal_id" => "ALICE_03",
                "deck" => "Alice",
                "image" => "UM card-Alice3.jpg"
                ),
    8 => array( "name" => "The Other Side of the Mushroom",
                "type" => "card",
                "internal_id" => "ALICE_04",
                "deck" => "Alice",
                "image" => "UM card-Alice4.jpg"
                ),
    9 => array( "name" => "Eat Me",
                "type" => "card",
                "internal_id" => "ALICE_05",
                "deck" => "Alice",
                "image" => "UM card-Alice5.jpg"
                ),
    10 => array( "name" => "Eat Me",
                "type" => "card",
                "internal_id" => "ALICE_05",
                "deck" => "Alice",
                "image" => "UM card-Alice5.jpg"
                ),
    11 => array( "name" => "I'm Late, I'm Late",
                "type" => "card",
                "internal_id" => "ALICE_06",
                "deck" => "Alice",
                "image" => "UM card-Alice6.jpg"
                ),
    12 => array( "name" => "I'm Late, I'm Late",
                "type" => "card",
                "internal_id" => "ALICE_06",
                "deck" => "Alice",
                "image" => "UM card-Alice6.jpg"
                ),
    13 => array( "name" => "I'm Late, I'm Late",
                "type" => "card",
                "internal_id" => "ALICE_06",
                "deck" => "Alice",
                "image" => "UM card-Alice6.jpg"
                ),
    14 => array( "name" => "Drink Me",
                "type" => "card",
                "internal_id" => "ALICE_07",
                "deck" => "Alice",
                "image" => "UM card-Alice7.jpg"
                ),
    15 => array( "name" => "Drink Me",
                "type" => "card",
                "internal_id" => "ALICE_07",
                "deck" => "Alice",
                "image" => "UM card-Alice7.jpg"
                ),
    16 => array( "name" => "Jaws That Bite",
                "type" => "card",
                "internal_id" => "ALICE_08",
                "deck" => "Alice",
                "image" => "UM card-Alice8.jpg"
                ),
    17 => array( "name" => "Jaws That Bite",
                "type" => "card",
                "internal_id" => "ALICE_08",
                "deck" => "Alice",
                "image" => "UM card-Alice8.jpg"
                ),
    18 => array( "name" => "Claws That Catch",
                "type" => "card",
                "internal_id" => "ALICE_09",
                "deck" => "Alice",
                "image" => "UM card-Alice9.jpg"
                ),
    19 => array( "name" => "Claws That Catch",
                "type" => "card",
                "internal_id" => "ALICE_09",
                "deck" => "Alice",
                "image" => "UM card-Alice9.jpg"
                ),
    20 => array( "name" => "Momentous Shift",
                "type" => "card",
                "internal_id" => "ALICE_10",        
                "deck" => "Alice",
                "image" => "UM card-Alice10.jpg"
                ),
    21 => array( "name" => "Momentous Shift",
                "type" => "card",
                "internal_id" => "ALICE_10",        
                "deck" => "Alice",
                "image" => "UM card-Alice10.jpg"
                ),
    22 => array( "name" => "Skirmish",
                "type" => "card",
                "internal_id" => "ALICE_11",
                "deck" => "Alice",
                "image" => "UM card-Alice11.jpg"
                ),
    23 => array( "name" => "Skirmish",
                "type" => "card",
                "internal_id" => "ALICE_11",
                "deck" => "Alice",
                "image" => "UM card-Alice11.jpg"
                ),
    24 => array( "name" => "Mad as a Hatter",
                "type" => "card",
                "internal_id" => "ALICE_12",
                "deck" => "Alice",
                "image" => "UM card-Alice12.jpg"
                ),
    25 => array( "name" => "Mad as a Hatter",
                "type" => "card",
                "internal_id" => "ALICE_12",
                "deck" => "Alice",
                "image" => "UM card-Alice12.jpg"
                ),    
    26 => array( "name" => "Manxome Foe",
                "type" => "card",
                "internal_id" => "ALICE_13",
                "deck" => "Alice",
                "image" => "UM card-Alice13.jpg"
                ),
    27 => array( "name" => "Manxome Foe",
                "type" => "card",
                "internal_id" => "ALICE_13",
                "deck" => "Alice",
                "image" => "UM card-Alice13.jpg"
                ),
    28 => array( "name" => "Feint",
                "type" => "card",
                "internal_id" => "ALICE_14",
                "deck" => "Alice",
                "image" => "UM card-Alice14.jpg"
                ),
    29 => array( "name" => "Feint",
                "type" => "card",
                "internal_id" => "ALICE_14",
                "deck" => "Alice",
                "image" => "UM card-Alice14.jpg"
                ),   
    30 => array( "name" => "Feint",
                "type" => "card",
                "internal_id" => "ALICE_14",
                "deck" => "Alice",
                "image" => "UM card-Alice14.jpg"
                ),                                
    31 => array( "name" => "Regroup",
                "type" => "card",
                "internal_id" => "ALICE_15",
                "deck" => "Alice",
                "image" => "UM card-Alice15.jpg"
                ),
    32 => array( "name" => "Regroup",
                "type" => "card",
                "internal_id" => "ALICE_15",
                "deck" => "Alice",
                "image" => "UM card-Alice15.jpg"
                ),
    33 => array( "name" => "Regroup",
                "type" => "card",
                "internal_id" => "ALICE_15",
                "deck" => "Alice",
                "image" => "UM card-Alice15.jpg"
                ),                

    // Arthur
    34 => array( "name" => "Reference Card - Arthur",
                "type" => "reference",
                "internal_id" => "REF ARTHUR",
                "deck" => "Arthur",
                "image" => "Reference Card-Arthur.jpg"
                ),
    35 => array( "name" => "Card Back - Arthur",
                "type" => "back",
                "internal_id" => "BACK ARTHUR",
                "deck" => "Arthur",
                "image" => "UM card back_Arthur.jpg"
                ),
    36 => array( "name" => "Noble Sacrifice",
                "type" => "card",
                "internal_id" => "ARTHUR_01",
                "deck" => "Arthur",
                "image" => "UM card-Arthur1.jpg"
                ),                     
    37 => array( "name" => "Noble Sacrifice",
                "type" => "card",
                "internal_id" => "ARTHUR_01",
                "deck" => "Arthur",
                "image" => "UM card-Arthur1.jpg"
                ),                     
    38 => array( "name" => "Noble Sacrifice",
                "type" => "card",
                "internal_id" => "ARTHUR_01",
                "deck" => "Arthur",
                "image" => "UM card-Arthur1.jpg"
                ),                     
    39 => array( "name" => "Excalibur",
                "type" => "card",
                "internal_id" => "ARTHUR_02",
                "deck" => "Arthur",
                "image" => "UM card-Arthur2.jpg"
                ),                     
    40 => array( "name" => "The Aid of Morgana",
                "type" => "card",
                "internal_id" => "ARTHUR_03",
                "deck" => "Arthur",
                "image" => "UM card-Arthur3.jpg"
                ),                     
    41 => array( "name" => "Divine Intervention",
                "type" => "card",
                "internal_id" => "ARTHUR_04",
                "deck" => "Arthur",
                "image" => "UM card-Arthur4.jpg"
                ),
    42 => array( "name" => "Divine Intervention",
                "type" => "card",
                "internal_id" => "ARTHUR_04",
                "deck" => "Arthur",
                "image" => "UM card-Arthur4.jpg"
                ),
    43 => array( "name" => "The Holy Grail",
                "type" => "card",
                "internal_id" => "ARTHUR_05",
                "deck" => "Arthur",
                "image" => "UM card-Arthur5.jpg"
                ),
    44 => array( "name" => "The Lady of the Lake",
                "type" => "card",
                "internal_id" => "ARTHUR_06",
                "deck" => "Arthur",
                "image" => "UM card-Arthur6.jpg"
                ),
    45 => array( "name" => "Prophecy",
                "type" => "card",
                "internal_id" => "ARTHUR_07",
                "deck" => "Arthur",
                "image" => "UM card-Arthur7.jpg"
                ),
    46 => array( "name" => "Bewilderment",
                "type" => "card",
                "internal_id" => "ARTHUR_08",
                "deck" => "Arthur",
                "image" => "UM card-Arthur8.jpg"
                ),
    47 => array( "name" => "Bewilderment",
                "type" => "card",
                "internal_id" => "ARTHUR_08",
                "deck" => "Arthur",
                "image" => "UM card-Arthur8.jpg"
                ),
    48 => array( "name" => "Aid the Chosen One",
                "type" => "card",
                "internal_id" => "ARTHUR_09",
                "deck" => "Arthur",
                "image" => "UM card-Arthur9.jpg"
                ),
    49 => array( "name" => "Restless Spirits",
                "type" => "card",
                "internal_id" => "ARTHUR_10",
                "deck" => "Arthur",
                "image" => "UM card-Arthur10.jpg"
                ),
    50 => array( "name" => "Command the Storms",
                "type" => "card",
                "internal_id" => "ARTHUR_11",
                "deck" => "Arthur",
                "image" => "UM card-Arthur11.jpg"
                ),
    51 => array( "name" => "Command the Storms",
                "type" => "card",
                "internal_id" => "ARTHUR_11",
                "deck" => "Arthur",
                "image" => "UM card-Arthur11.jpg"
                ),
    52 => array( "name" => "Swift Strike",
                "type" => "card",
                "internal_id" => "ARTHUR_12",
                "deck" => "Arthur",
                "image" => "UM card-Arthur12.jpg"
                ),
    53 => array( "name" => "Swift Strike",
                "type" => "card",
                "internal_id" => "ARTHUR_12",
                "deck" => "Arthur",
                "image" => "UM card-Arthur12.jpg"
                ),
    54 => array( "name" => "Skirmish",
                "type" => "card",
                "internal_id" => "ARTHUR_13",
                "deck" => "Arthur",
                "image" => "UM card-Arthur13.jpg"
                ),
    55 => array( "name" => "Skirmish",
                "type" => "card",
                "internal_id" => "ARTHUR_13",
                "deck" => "Arthur",
                "image" => "UM card-Arthur13.jpg"
                ),
    56 => array( "name" => "Skirmish",
                "type" => "card",
                "internal_id" => "ARTHUR_13",
                "deck" => "Arthur",
                "image" => "UM card-Arthur13.jpg"
                ),
    57 => array( "name" => "Momentous Shift",
                "type" => "card",
                "internal_id" => "ARTHUR_14",
                "deck" => "Arthur",
                "image" => "UM card-Arthur14.jpg"
                ),
    58 => array( "name" => "Momentous Shift",
                "type" => "card",
                "internal_id" => "ARTHUR_14",
                "deck" => "Arthur",
                "image" => "UM card-Arthur14.jpg"
                ),
    59 => array( "name" => "Momentous Shift",
                "type" => "card",
                "internal_id" => "ARTHUR_14",
                "deck" => "Arthur",
                "image" => "UM card-Arthur14.jpg"
                ),
    60 => array( "name" => "Feint",
                "type" => "card",
                "internal_id" => "ARTHUR_15",
                "deck" => "Arthur",
                "image" => "UM card-Arthur15.jpg"
                ),
    61 => array( "name" => "Feint",
                "type" => "card",
                "internal_id" => "ARTHUR_15",
                "deck" => "Arthur",
                "image" => "UM card-Arthur15.jpg"
                ),
    62 => array( "name" => "Feint",
                "type" => "card",
                "internal_id" => "ARTHUR_15",
                "deck" => "Arthur",
                "image" => "UM card-Arthur15.jpg"
                ),
    63 => array( "name" => "Regroup",
                "type" => "card",
                "internal_id" => "ARTHUR_16",
                "deck" => "Arthur",
                "image" => "UM card-Arthur16.jpg"
                ),
    64 => array( "name" => "Regroup",
                "type" => "card",
                "internal_id" => "ARTHUR_16",
                "deck" => "Arthur",
                "image" => "UM card-Arthur16.jpg"
                ),
    65 => array( "name" => "Regroup",
                "type" => "card",
                "internal_id" => "ARTHUR_16",
                "deck" => "Arthur",
                "image" => "UM card-Arthur16.jpg"
                ),

    // Medusa
    66 => array( "name" => "Reference Card - Medusa",
                "type" => "reference",
                "internal_id" => "REF MEDUSA",
                "deck" => "Medusa",  
                "image" => "Reference Card-Medusa.jpg"
                ),
    67 => array( "name" => "Card Back - Medusa",
                "type" => "back",
                "internal_id" => "BACK MEDUSA",
                "deck" => "Medusa",
                "image" => "UM card back_Medusa.jpg"
                ),
    68 => array( "name" => "Gaze of Stone",
                "type" => "card",
                "internal_id" => "MEDUSA_01",
                "deck" => "Medusa",
                "image" => "UM card-Medusa1.jpg"
                ),
    69 => array( "name" => "Gaze of Stone",
                "type" => "card",
                "internal_id" => "MEDUSA_01",
                "deck" => "Medusa",
                "image" => "UM card-Medusa1.jpg"
                ),
    70 => array( "name" => "Gaze of Stone",
                "type" => "card",
                "internal_id" => "MEDUSA_01",
                "deck" => "Medusa",
                "image" => "UM card-Medusa1.jpg"
                ),
    71 => array( "name" => "A Momentary Glance",
                "type" => "card",
                "internal_id" => "MEDUSA_02",
                "deck" => "Medusa",
                "image" => "UM card-Medusa2.jpg"
                ),
    72 => array( "name" => "A Momentary Glance",
                "type" => "card",
                "internal_id" => "MEDUSA_02",
                "deck" => "Medusa",
                "image" => "UM card-Medusa2.jpg"
                ),
    73 => array( "name" => "Hiss and Slither",
                "type" => "card",
                "internal_id" => "MEDUSA_03",
                "deck" => "Medusa",
                "image" => "UM card-Medusa3.jpg"
                ),
    74 => array( "name" => "Hiss and Slither",
                "type" => "card",
                "internal_id" => "MEDUSA_03",
                "deck" => "Medusa",
                "image" => "UM card-Medusa3.jpg"
                ),
    75 => array( "name" => "Hiss and Slither",
                "type" => "card",
                "internal_id" => "MEDUSA_03",
                "deck" => "Medusa",
                "image" => "UM card-Medusa3.jpg"
                ),
    76 => array( "name" => "The Hound of Mighty Zeus",
                "type" => "card",
                "internal_id" => "MEDUSA_04",
                "deck" => "Medusa",
                "image" => "UM card-Medusa4.jpg"
                ),
    77 => array( "name" => "The Hound of Mighty Zeus",
                "type" => "card",
                "internal_id" => "MEDUSA_04",
                "deck" => "Medusa",
                "image" => "UM card-Medusa4.jpg"
                ),
    78 => array( "name" => "Clutching Claws",
                "type" => "card",
                "internal_id" => "MEDUSA_05",
                "deck" => "Medusa",
                "image" => "UM card-Medusa5.jpg"
                ),
    79 => array( "name" => "Clutching Claws",
                "type" => "card",
                "internal_id" => "MEDUSA_05",
                "deck" => "Medusa",
                "image" => "UM card-Medusa5.jpg"
                ),
    80 => array( "name" => "Clutching Claws",
                "type" => "card",
                "internal_id" => "MEDUSA_05",
                "deck" => "Medusa",
                "image" => "UM card-Medusa5.jpg"
                ),
    81 => array( "name" => "Winged Frenzy",
                "type" => "card",
                "internal_id" => "MEDUSA_06",
                "deck" => "Medusa",
                "image" => "UM card-Medusa6.jpg"
                ),
    82 => array( "name" => "Winged Frenzy",
                "type" => "card",
                "internal_id" => "MEDUSA_06",
                "deck" => "Medusa",
                "image" => "UM card-Medusa6.jpg"
                ),
    83 => array( "name" => "Second Shot",
                "type" => "card",
                "internal_id" => "MEDUSA_07",
                "deck" => "Medusa",
                "image" => "UM card-Medusa7.jpg"
                ),
    84 => array( "name" => "Second Shot",
                "type" => "card",
                "internal_id" => "MEDUSA_07",
                "deck" => "Medusa",
                "image" => "UM card-Medusa7.jpg"
                ),
    85 => array( "name" => "Second Shot",
                "type" => "card",
                "internal_id" => "MEDUSA_07",
                "deck" => "Medusa",
                "image" => "UM card-Medusa7.jpg"
                ),
    86 => array( "name" => "Dash",
                "type" => "card",
                "internal_id" => "MEDUSA_08",
                "deck" => "Medusa",
                "image" => "UM card-Medusa8.jpg"
                ),
    87 => array( "name" => "Dash",
                "type" => "card",
                "internal_id" => "MEDUSA_08",
                "deck" => "Medusa",
                "image" => "UM card-Medusa8.jpg"
                ),
    88 => array( "name" => "Dash",
                "type" => "card",
                "internal_id" => "MEDUSA_08",
                "deck" => "Medusa",
                "image" => "UM card-Medusa8.jpg"
                ),
    89 => array( "name" => "Snipe",
                "type" => "card",
                "internal_id" => "MEDUSA_09",
                "deck" => "Medusa",
                "image" => "UM card-Medusa9.jpg"
                ),
    90 => array( "name" => "Snipe",
                "type" => "card",
                "internal_id" => "MEDUSA_09",
                "deck" => "Medusa",
                "image" => "UM card-Medusa9.jpg"
                ),
    91 => array( "name" => "Snipe",
                "type" => "card",
                "internal_id" => "MEDUSA_09",
                "deck" => "Medusa",
                "image" => "UM card-Medusa9.jpg"
                ),
    92 => array( "name" => "Feint",
                "type" => "card",
                "internal_id" => "MEDUSA_10",
                "deck" => "Medusa",
                "image" => "UM card-Medusa10.jpg"
                ),
    93 => array( "name" => "Feint",
                "type" => "card",
                "internal_id" => "MEDUSA_10",
                "deck" => "Medusa",
                "image" => "UM card-Medusa10.jpg"
                ),
    94 => array( "name" => "Feint",
                "type" => "card",
                "internal_id" => "MEDUSA_10",
                "deck" => "Medusa",
                "image" => "UM card-Medusa10.jpg"
                ),
    95 => array( "name" => "Regroup",
                "type" => "card",
                "internal_id" => "MEDUSA_11",
                "deck" => "Medusa",
                "image" => "UM card-Medusa11.jpg"
                ),
    96 => array( "name" => "Regroup",
                "type" => "card",
                "internal_id" => "MEDUSA_11",
                "deck" => "Medusa",
                "image" => "UM card-Medusa11.jpg"
                ),
    97 => array( "name" => "Regroup",
                "type" => "card",
                "internal_id" => "MEDUSA_11",
                "deck" => "Medusa",
                "image" => "UM card-Medusa11.jpg"
                ),

    // Sinbad
    98 => array( "name" => "Reference Card - Sinbad",
                "type" => "reference",
                "internal_id" => "REF SINBAD",
                "deck" => "Sinbad",
                "image" => "Reference Card-Sinbad.jpg"
                ),
    99 => array( "name" => "Card Back - Sinbad",
                "type" => "back",
                "internal_id" => "BACK SINBAD",
                "deck" => "Sinbad",
                "image" => "UM card back_Sinbad.jpg"
                ),    
    100 => array( "name" => "Toil and Danger",
                "type" => "card",
                "internal_id" => "SINBAD_01",
                "deck" => "Sinbad",
                "image" => "UM card-Sinbad1.jpg"
                ),
    101 => array( "name" => "Toil and Danger",
                "type" => "card",
                "internal_id" => "SINBAD_01",
                "deck" => "Sinbad",
                "image" => "UM card-Sinbad1.jpg"
                ),
    102 => array( "name" => "Toil and Danger",
                "type" => "card",
                "internal_id" => "SINBAD_01",
                "deck" => "Sinbad",
                "image" => "UM card-Sinbad1.jpg"
                ),
    103 => array( "name" => "Toil and Danger",
                "type" => "card",
                "internal_id" => "SINBAD_01",
                "deck" => "Sinbad",
                "image" => "UM card-Sinbad1.jpg"
                ),
    104 => array( "name" => "Voyage Home",
                "type" => "card",
                "internal_id" => "SINBAD_02",
                "deck" => "Sinbad",
                "image" => "UM card-Sinbad2.jpg"
                ),
    105 => array( "name" => "Riches Beyond Compare",
                "type" => "card",
                "internal_id" => "SINBAD_03",
                "deck" => "Sinbad",
                "image" => "UM card-Sinbad3.jpg"
                ),
    106 => array( "name" => "Riches Beyond Compare",
                "type" => "card",
                "internal_id" => "SINBAD_03",
                "deck" => "Sinbad",
                "image" => "UM card-Sinbad3.jpg"
                ),
    107 => array( "name" => "By Fortune and Fate",
                "type" => "card",
                "internal_id" => "SINBAD_04",
                "deck" => "Sinbad",
                "image" => "UM card-Sinbad4.jpg"
                ),
    108 => array( "name" => "By Fortune and Fate",
                "type" => "card",
                "internal_id" => "SINBAD_04",
                "deck" => "Sinbad",
                "image" => "UM card-Sinbad4.jpg"
                ),
    109 => array( "name" => "By Fortune and Fate",
                "type" => "card",
                "internal_id" => "SINBAD_04",
                "deck" => "Sinbad",
                "image" => "UM card-Sinbad4.jpg"
                ),
    110 => array( "name" => "Voyage to the Island That Was a Whale",
                "type" => "card",
                "internal_id" => "SINBAD_05",
                "deck" => "Sinbad",
                "image" => "UM card-Sinbad5.jpg"
                ),
    111 => array( "name" => "Voyage to the Valley of the Giant Snakes",
                "type" => "card",
                "internal_id" => "SINBAD_06",
                "deck" => "Sinbad",
                "image" => "UM card-Sinbad6.jpg"
                ),
    112 => array( "name" => "Voyage to the Creature With Eyes Like Coals of Fire",
                "type" => "card",
                "internal_id" => "SINBAD_07",
                "deck" => "Sinbad",
                "image" => "UM card-Sinbad7.jpg"
                ),
    113 => array( "name" => "Voyage to the Cannibals With the Root of Madness",
                "type" => "card",
                "internal_id" => "SINBAD_08",
                "deck" => "Sinbad",
                "image" => "UM card-Sinbad8.jpg"
                ),
    114 => array( "name" => "Voyage to the City of the Man-Eating Apes",
                "type" => "card",
                "internal_id" => "SINBAD_09",
                "deck" => "Sinbad",
                "image" => "UM card-Sinbad9.jpg"
                ),
    115 => array( "name" => "Voyage to the City of the King of Serendib",
                "type" => "card",
                "internal_id" => "SINBAD_10",
                "deck" => "Sinbad",
                "image" => "UM card-Sinbad10.jpg"
                ),
    116 => array( "name" => "Commanding Impact",
                "type" => "card",
                "internal_id" => "SINBAD_11",
                "deck" => "Sinbad",
                "image" => "UM card-Sinbad11.jpg"
                ),
    117 => array( "name" => "Leap Away",
                "type" => "card",
                "internal_id" => "SINBAD_12",
                "deck" => "Sinbad",
                "image" => "UM card-Sinbad12.jpg"
                ),
    118 => array( "name" => "Leap Away",
                "type" => "card",
                "internal_id" => "SINBAD_12",
                "deck" => "Sinbad",
                "image" => "UM card-Sinbad12.jpg"
                ),
    119 => array( "name" => "Exploit",
                "type" => "card",
                "internal_id" => "SINBAD_13",
                "deck" => "Sinbad",
                "image" => "UM card-Sinbad13.jpg"
                ),
    120 => array( "name" => "Exploit",
                "type" => "card",
                "internal_id" => "SINBAD_13",
                "deck" => "Sinbad",
                "image" => "UM card-Sinbad13.jpg"
                ),
    121 => array( "name" => "Momentous Shift",
                "type" => "card",
                "internal_id" => "SINBAD_14",
                "deck" => "Sinbad",
                "image" => "UM card-Sinbad14.jpg"
                ),
    122 => array( "name" => "Momentous Shift",
                "type" => "card",
                "internal_id" => "SINBAD_14",
                "deck" => "Sinbad",
                "image" => "UM card-Sinbad14.jpg"
                ),
    123 => array( "name" => "Momentous Shift",
                "type" => "card",
                "internal_id" => "SINBAD_14",
                "deck" => "Sinbad",
                "image" => "UM card-Sinbad14.jpg"
                ),
    124 => array( "name" => "Feint",
                "type" => "card",
                "internal_id" => "SINBAD_15",
                "deck" => "Sinbad",
                "image" => "UM card-Sinbad15.jpg"
                ),
    125 => array( "name" => "Feint",
                "type" => "card",
                "internal_id" => "SINBAD_15",
                "deck" => "Sinbad",
                "image" => "UM card-Sinbad15.jpg"
                ),
    126 => array( "name" => "Feint",
                "type" => "card",
                "internal_id" => "SINBAD_15",
                "deck" => "Sinbad",
                "image" => "UM card-Sinbad15.jpg"
                ),
    127 => array( "name" => "Regroup",
                "type" => "card",
                "internal_id" => "SINBAD_16",
                "deck" => "Sinbad",
                "image" => "UM card-Sinbad16.jpg"
                ),
    128 => array( "name" => "Regroup",
                "type" => "card",
                "internal_id" => "SINBAD_16",
                "deck" => "Sinbad",
                "image" => "UM card-Sinbad16.jpg"
                ),
    129 => array( "name" => "Regroup",
                "type" => "card",
                "internal_id" => "SINBAD_16",
                "deck" => "Sinbad",
                "image" => "UM card-Sinbad16.jpg"
                ),
            );



