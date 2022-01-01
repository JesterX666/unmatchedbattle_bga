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
 * states.inc.php
 *
 * UnmatchedBattle game states description
 *
 */

/*
   Game state machine is a tool used to facilitate game developpement by doing common stuff that can be set up
   in a very easy way from this configuration file.

   Please check the BGA Studio presentation about game state to understand this, and associated documentation.

   Summary:

   States types:
   _ activeplayer: in this type of state, we expect some action from the active player.
   _ multipleactiveplayer: in this type of state, we expect some action from multiple players (the active players)
   _ game: this is an intermediary state where we don't expect any actions from players. Your game logic must decide what is the next game state.
   _ manager: special type for initial and final state

   Arguments of game states:
   _ name: the name of the GameState, in order you can recognize it on your own code.
   _ description: the description of the current game state is always displayed in the action status bar on
                  the top of the game. Most of the time this is useless for game state with "game" type.
   _ descriptionmyturn: the description of the current game state when it's your turn.
   _ type: defines the type of game states (activeplayer / multipleactiveplayer / game / manager)
   _ action: name of the method to call when this game state become the current game state. Usually, the
             action method is prefixed by "st" (ex: "stMyGameStateName").
   _ possibleactions: array that specify possible player actions on this step. It allows you to use "checkAction"
                      method on both client side (Javacript: this.checkAction) and server side (PHP: self::checkAction).
   _ transitions: the transitions are the possible paths to go from a game state to another. You must name
                  transitions in order to use transition names in "nextState" PHP method, and use IDs to
                  specify the next game state for each transition.
   _ args: name of the method to call to retrieve arguments for this gamestate. Arguments are sent to the
           client side to be used on "onEnteringState" or to set arguments in the gamestate description.
   _ updateGameProgression: when specified, the game progression is updated (=> call to your getGameProgression
                            method).
*/

//    !! It is not a good idea to modify this file when a game is running !!

 
$machinestates = array(

    // The initial state. Please do not modify.
    1 => array(
        "name" => "gameSetup",
        "description" => "",
        "type" => "manager",
        "action" => "stGameSetup",
        "transitions" => array( "" => 2 )
    ),
    
    // Note: ID=2 => your first state

    2 => array(
    	"name" => "chooseHero",
    	"description" => clienttranslate('${actplayer} must choose his hero'),
    	"descriptionmyturn" => clienttranslate('${you} must choose your hero'),
    	"type" => "activeplayer",
    	"possibleactions" => array( "chooseHero" ),
    	"transitions" => array( "chooseHeroNextPlayer" => 3 )
    ),

    3 => array(
        "name" => "chooseHeroNextPlayer",
        "description" => "",
        "type" => "game",
        "action" => "checkEveryoneChoosedHero",
        "transitions" => array( "chooseHero" => 2, "everyoneChoosedHero" => 4, "aliceChooseSize" => 15 )
    ), 
    
	4 => array(
    	"name" => "distributeCards",
    	"description" => clienttranslate('Distributing starting hands'),
    	"type" => "game",
        "action" => "distributeCards",
    	"transitions" => array( "placeHero" => 5 )
    ),

    5 => array(
        "name" => "placeHeroStartingArea",
        "description" => clienttranslate('Placing heros to their starting area'),
        "type" => "game",
        "action" => "placeHeroStartingArea",
        "transitions" => array( "assignSidekicks" => 6 )
    ),

    6 => array(
        "name" => "assignSidekicks",
        "description" => clienttranslate('Assigning sidekicks to Players'),
        "type" => "game",
        "action" => "assignSidekicks",
        "transitions" => array( "placeSidekicks" => 7 )
    ),
    
	7 => array(
        "name" => "placeSidekicks",
        "description" => clienttranslate('${actplayer} must place his sidekicks'),
        "descriptionmyturn" => clienttranslate('${you} must place your sidekicks in the same zone as your hero'),
        "type" => "activeplayer",
        "possibleactions" => array( "placeSidekicks" ),
        "transitions" => array( "placeSidekicksNextPlayer" => 8 )
    ),

    8 => array(
        "name" => "placeSidekicksNextPlayer",
        "description" => "",
        "type" => "game",
        "action" => "checkEveryonePlacedSidekicks",
        "transitions" => array( "placeSidekicks" => 7, "playAction" => 9 )
    ),

    9 => array(
        "name" => "playAction",
        "description" => clienttranslate('${actplayer} must play an action'),
        "descriptionmyturn" => clienttranslate('${you} must play an action'),
        "type" => "activeplayer",
        "possibleactions" => array( "playAction" ),
        "transitions" => array( "playActionManeuver" => 10, "playActionScheme" => 12, "playActionAttack" => 13 )
    ),

    10 => array(
        "name" => "playActionManeuver",
        "description" => clienttranslate('${actplayer} must choose if he wants to play a boost card'),
        "descriptionmyturn" => clienttranslate('${you} may play a boost card'),
        "type" => "activeplayer",
        "possibleactions" => array( "playBoostCard", "skipBoostCard" ),
        "transitions" => array( "playActionMove" => 11 )
    ),

    11 => array(
        "name" => "playActionMove",
        "description" => clienttranslate('${actplayer} may move his fighters'),
        "descriptionmyturn" => clienttranslate('${you} may move your fighters up to ${moveAmount} spaces'),
        "args" => "getPlayActionMoveArgs",
        "type" => "activeplayer",
        "possibleactions" => array( "playActionMoveDone" ),
        "transitions" => array( "checkPlayActionDone" => 14 )
    ),

    12 => array(
        "name" => "playActionScheme",
        "description" => "",
        "type" => "game",
        "action" => "checkEveryonePlacedSidekicks",
        "transitions" => array( "placeSidekicks" => 7, "playAction" => 9 )
    ),
    
    13 => array(
        "name" => "playActionAttack",
        "description" => "",
        "type" => "game",
        "action" => "checkEveryonePlacedSidekicks",
        "transitions" => array( "placeSidekicks" => 7, "playAction" => 9 )
    ),
        
    14 => array(
        "name" => "checkPlayActionDone",
        "description" => "",
        "type" => "game",
        "action" => "checkPlayActionDone",
        "transitions" => array( "playAction" => 9 )
    ), 
    
    15 => array(
    	"name" => "aliceChooseSize",
    	"description" => clienttranslate('${actplayer} must choose if Alice will be a big or small'),
    	"descriptionmyturn" => clienttranslate('${you} must choose if Alice will be a big or small'),
    	"type" => "activeplayer",
    	"possibleactions" => array( "chooseSize" ),
    	"transitions" => array( "everyoneChoosedHero" => 4 )
    ),

/*
    Examples:
    
    2 => array(
        "name" => "nextPlayer",
        "description" => '',
        "type" => "game",
        "action" => "stNextPlayer",
        "updateGameProgression" => true,   
        "transitions" => array( "endGame" => 99, "nextPlayer" => 10 )
    ),
    
    10 => array(
        "name" => "playerTurn",
        "description" => clienttranslate('${actplayer} must play a card or pass'),
        "descriptionmyturn" => clienttranslate('${you} must play a card or pass'),
        "type" => "activeplayer",
        "possibleactions" => array( "playCard", "pass" ),
        "transitions" => array( "playCard" => 2, "pass" => 2 )
    ), 

*/    
   
    // Final state.
    // Please do not modify (and do not overload action/args methods).
    99 => array(
        "name" => "gameEnd",
        "description" => clienttranslate("End of game"),
        "type" => "manager",
        "action" => "stGameEnd",
        "args" => "argGameEnd"
    )

);



