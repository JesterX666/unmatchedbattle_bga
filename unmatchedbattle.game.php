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
  * unmatchedbattle.game.php
  *
  * This is the main file for your game logic.
  *
  * In this PHP file, you are going to defines the rules of the game.
  *
  */


require_once( APP_GAMEMODULE_PATH.'module/table/table.game.php' );


class UnmatchedBattle extends Table
{
	function __construct( )
	{
        // Your global variables labels:
        //  Here, you can assign labels to global variables you are using for this game.
        //  You can use any number of global variables with IDs between 10 and 99.
        //  If your game has options (variants), you also have to associate here a label to
        //  the corresponding ID in gameoptions.inc.php.
        // Note: afterwards, you can get/set the global variables with getGameStateValue/setGameStateInitialValue/setGameStateValue
        parent::__construct();
        
        self::initGameStateLabels( array( 
            "boardId" => 10,
            //    "my_second_global_variable" => 11,
            //      ...
            //    "my_first_game_variant" => 100,
            //    "my_second_game_variant" => 101,
            //      ...
        ) );        

        $this->cards = self::getNew( "module.common.deck" );
        $this->cards->init("cards");

	}
	
    protected function getGameName( )
    {
		// Used for translations and stuff. Please do not modify.
        return "unmatchedbattle";
    }	

    /*
        setupNewGame:
        
        This method is called only once, when a new game is launched.
        In this method, you must setup the game according to the game rules, so that
        the game is ready to be played.
    */
    protected function setupNewGame( $players, $options = array() )
    {    
        // Set the colors of the players with HTML color code
        // The default below is red/green/blue/orange/brown
        // The number of colors defined here must correspond to the maximum number of players allowed for the gams
        $gameinfos = self::getGameinfos();
        $default_colors = $gameinfos['player_colors'];
 
        // Create players
        // Note: if you added some extra field on "player" table in the database (dbmodel.sql), you can initialize it there.
        $sql = "INSERT INTO player (player_id, player_color, player_canal, player_name, player_avatar) VALUES ";
        $values = array();
        foreach( $players as $player_id => $player )
        {
            $color = array_shift( $default_colors );
            $values[] = "('".$player_id."','$color','".$player['player_canal']."','".addslashes( $player['player_name'] )."','".addslashes( $player['player_avatar'] )."')";
        }
        $sql .= implode( $values, ',' );
        self::DbQuery( $sql );
        self::reattributeColorsBasedOnPreferences( $players, $gameinfos['player_colors'] );
        self::reloadPlayersBasicInfos();
        
        /************ Start the game initialization *****/

        // Init global values with their initial values
        //self::setGameStateInitialValue( 'my_first_global_variable', 0 );
        
        // Init game statistics
        // (note: statistics used in this file must be defined in your stats.inc.php file)
        //self::initStat( 'table', 'table_teststat1', 0 );    // Init a table statistics
        //self::initStat( 'player', 'player_teststat1', 0 );  // Init a player statistics (for all players)

        $this->initTables();
        
        // Activate first player (which is in general a good idea :) )
        $this->activeNextPlayer();

        /************ End of the game initialization *****/
    }

    protected function initTables()
    {        
        // TODO: setup the initial game situation here
        if ($this->gamestate->table_globals[100] == 1)
        {
            // Determine a random map
            $boardId = array_rand($this->boards);
            self::debug("Random map : ".$boardId);
        }
        else
        {
            // Find the map in the list
            $boardId = $this->gamestate->table_globals[100];
            self::debug("Chosen map : ".$boardId);
        }   
               
        // We save the board name in the game state
        self::setGameStateInitialValue( 'boardId', $boardId );
    }


    /*
        getAllDatas: 
        
        Gather all informations about current game situation (visible by the current player).
        
        The method is called each time the game interface is displayed to a player, ie:
        _ when the game starts
        _ when a player refreshes the game page (F5)
    */
    protected function getAllDatas()
    {
        $result = array();
    
        $current_player_id = self::getCurrentPlayerId();    // !! We must only return informations visible by this player !!
    
        // Get information about players
        $sql = "SELECT player_id id, player_score score, hero FROM player ";
        $result['players'] = self::getCollectionFromDb( $sql );
  
        // TODO: Gather all information about current game situation (visible by player $current_player_id).

        $hero = $result['players'][$current_player_id]['hero'];
        self::debug("getAllData HERO : ".$hero);

        $state = $this->gamestate->state();
        switch($state['name'])
        {
            case 'chooseHero':
            case 'chooseHeroNextPlayer':
                // Which heros are available at the start of the game
                $result['availableHeros'] = $this->getAvailableHeros();
                break;
            case 'distributeCards':
            case 'placeHeroStartingArea':
            case 'assignSidekicks':                
            case 'placeSidekicks':
                $result['playerDeck'] = array_filter($this->cardtypes, function($obj) use ($hero) 
                {
                     return $obj['deck'] == $hero && $obj['type'] == 'card'; 
                });
            
                $result['playerHand'] = array_column($this->cards->getPlayerHand($current_player_id), 'type_arg');
                $result['tokensPlacement'] = $this->getTokensPlacement();

                $heroObject = $this->heros[$hero];

                self::debug("HERO OBJECT : ".json_encode($heroObject));

                $result['playerSidekicks'] = $heroObject['sidekicks'];
                $result['playerHero'] = $hero;

                //$result['currentBoard'] = array_column($this->cards->getPlayerDeck($current_player_id), 'type_arg');
                self::debug("getAllData HERO : ".json_encode($result));
                break;
        }

        // Informations on existing cards of the game
        $result['cardtypes'] = $this->cardtypes;
  
        return $result;
    }

    /*
        getGameProgression:
        
        Compute and return the current game progression.
        The number returned must be an integer beween 0 (=the game just started) and
        100 (= the game is finished or almost finished).
    
        This method is called each time we are in a game state with the "updateGameProgression" property set to true 
        (see states.inc.php)
    */
    function getGameProgression()
    {
        // TODO: compute and return the game progression

        return 0;
    }


//////////////////////////////////////////////////////////////////////////////
//////////// Utility functions
////////////    

    /*
        In this space, you can put any utility methods useful for your game logic
    */



//////////////////////////////////////////////////////////////////////////////
//////////// Player actions
//////////// 

    /*
        Each time a player is doing some game action, one of the methods below is called.
        (note: each method below must match an input method in unmatchedbattle.action.php)
    */

    /*
    
    Example:

    function playCard( $card_id )
    {
        // Check that this is the player's turn and that it is a "possible action" at this game state (see states.inc.php)
        self::checkAction( 'playCard' ); 
        
        $player_id = self::getActivePlayerId();
        
        // Add your game logic to play a card there 
        ...
        
        // Notify all players about the card played
        self::notifyAllPlayers( "cardPlayed", clienttranslate( '${player_name} plays ${card_name}' ), array(
            'player_id' => $player_id,
            'player_name' => self::getActivePlayerName(),
            'card_name' => $card_name,
            'card_id' => $card_id
        ) );
          
    }
    
    */

    function chooseHero( $hero )
    {
        self::checkAction('chooseHero');
        
        $player_id = self::getActivePlayerId();

        // Set the choosen hero in the database
        $sql = "UPDATE player SET hero = '".$hero."' WHERE player_id = ".$player_id;
        self::DbQuery( $sql );

        // Get the players object

        // Notify all players about the hero chosen and the remaining available heroes
        $availableHeros = $this->getAvailableHeros();

        self::notifyAllPlayers( "heroSelected", clienttranslate( '${player_name} choosed to play ${hero}' ), array(
            'player_id' => $player_id,
            'player_name' => self::getActivePlayerName(),
            'hero' => $hero,
        ) );


        self::debug("Player ".$player_id." choosed to play ".$hero);

        // Next player
        $this->gamestate->nextState( 'chooseHeroNextPlayer' );
    }


    // Verifies if every player has choosen a hero
    function checkEveryoneChoosedHero()
    {
        $sql = "SELECT player_id id, hero FROM player WHERE hero is null";
        $playerHero = self::getCollectionFromDb( $sql );

        if (count($playerHero) == 0) {
            self::debug("Everyone choosed their hero");
            $this->gamestate->nextState( 'everyoneChoosedHero' );
        }
        else {
            $this->activeNextPlayer();
            $this->gamestate->nextState( 'chooseHero' );
        }
    }

    function placeHeroStartingArea()
    {
        // Get all players
        $sql = "SELECT player_id, hero FROM player" ;
        $playersHeros = self::getCollectionFromDb( $sql );

        // We place the heros on the table

        // Finding the right board
        $boardId = $this->getGameStateValue('boardId');
        $board = $this->boards[$boardId];

        self::debug("Placing heros in their starting area");
                
        // We loop on all players in order of play (player_no)
        foreach ($this->players as $player)
        {
            $playerHero = $playersHeros[$player['player_id']];
                            
            // We find the starting location of said hero           
            foreach($board['zones'] as $key => $zone)
            {
                if ($zone['startingPlayer'] == $player['player_no'])
                {
                    self::debug("Hero ".$playerHero['hero']." is in starting area ".$key);
                    $startingArea = $zone;
                    
                    $sql = "INSERT INTO tokens (token_name, area_id) VALUES ('".$playerHero['hero']."', ".$key.")";
                    self::DbQuery( $sql );               
                }
            }                            
        }

        $this->notifyTokensPlacement();

        $this->gamestate->nextState( 'assignSidekicks' );
    }

    function notifyTokensPlacement()
    {
        self::notifyAllPlayers( "placeTokens", clienttranslate( 'All heros are placed in their starting area' ),
        array ('tokensPlacement' => $this->getTokensPlacement()));
    }

    // Assign sidekicks to each players
    function assignSidekicks()
    {        
        $sql = "SELECT player_id, hero FROM player";
        $playersHeros = self::getCollectionFromDb( $sql );

        foreach($playersHeros as $playerHero)
        {
            $this->notifySidekicksPlacement($playerHero['player_id'], $playerHero['hero']);
        }

        $this->gamestate->nextState( 'placeSidekicks' );
    }

    // Notify a player about which sidekicks he has to place
    function notifySidekicksPlacement($player_id, $hero)
    {
        $sidekicks= $this->heros[$hero]['sidekicks'];
        self::debug("Assigning sidekick: ".json_encode($sidekicks));

        self::notifyPlayer($player_id, "placeSidekicks", "", array(
            'sidekicks' => $sidekicks, 'playerHero' => $hero
        ));
    }

    function distributeCards()
    {
        self::debug("Distributing Cards");

        // Get all players
        $sql = "SELECT hero, player_id FROM player" ;
        $players = self::getCollectionFromDb( $sql );

        // Create cards for all players
        foreach($players as $player)
        {            
            self::debug("Creating card for: ".$player['hero']);

            $cardsofhero = array_filter($this->cardtypes, function($obj) use ($player) 
            {
                 return $obj['deck'] == $player['hero'] && $obj['type'] == 'card'; 
            });
            
            $cards = array();

            // Loop on all cards of the hero
            foreach($cardsofhero as $card)
            {
                self::debug("Card: ".$card['name']);
                $cards[] = array('type' => $player['hero'], 'type_arg' => $card['internal_id'], 'nbr' => $card['nbr']);
            }

            // Add the cards of the hero to his their deck
            $this->cards->createCards( $cards, 'deck_'.$player['hero']);

            // Shuffle the deck
            $this->cards->shuffle( 'deck_'.$player['hero'] );

            // Give 5 cards to the player
            $this->cards->pickCards(5, 'deck_'.$player['hero'], $player['player_id']);

            $playerhand = array_column($this->cards->getPlayerHand($player['player_id']), 'type_arg');

            // Notify the player about the cards he received (and the definition of the cards in his deck)
            self::notifyPlayer( $player['player_id'], 'cardsReceived', '', array ('playerhand' => $playerhand, 'cards' => $cardsofhero));
        }

        $this->gamestate->nextState( 'placeHero' );
    }

    function getTokensPlacement()
    {
        $sql = "SELECT area_id, token_name FROM tokens";
        $result = self::getCollectionFromDb( $sql );
        
        return $result;
    }
    
//////////////////////////////////////////////////////////////////////////////
//////////// Game state arguments
////////////

    /*
        Here, you can create methods defined as "game state arguments" (see "args" property in states.inc.php).
        These methods function is to return some additional information that is specific to the current
        game state.
    */

    /*
    
    Example for game state "MyGameState":
    
    function argMyGameState()
    {
        // Get some values from the current game situation in database...
    
        // return values:
        return array(
            'variable1' => $value1,
            'variable2' => $value2,
            ...
        );
    }    
    */

//////////////////////////////////////////////////////////////////////////////
//////////// Game state actions
////////////

    /*
        Here, you can create methods defined as "game state actions" (see "action" property in states.inc.php).
        The action method of state X is called everytime the current game state is set to X.
    */
    
    /*
    
    Example for game state "MyGameState":

    function stMyGameState()
    {
        // Do some stuff ...
        
        // (very often) go to another gamestate
        $this->gamestate->nextState( 'some_gamestate_transition' );
    }    
    */

//////////////////////////////////////////////////////////////////////////////
//////////// Zombie
////////////

    /*
        zombieTurn:
        
        This method is called each time it is the turn of a player who has quit the game (= "zombie" player).
        You can do whatever you want in order to make sure the turn of this player ends appropriately
        (ex: pass).
        
        Important: your zombie code will be called when the player leaves the game. This action is triggered
        from the main site and propagated to the gameserver from a server, not from a browser.
        As a consequence, there is no current player associated to this action. In your zombieTurn function,
        you must _never_ use getCurrentPlayerId() or getCurrentPlayerName(), otherwise it will fail with a "Not logged" error message. 
    */

    function zombieTurn( $state, $active_player )
    {
    	$statename = $state['name'];
    	
        if ($state['type'] === "activeplayer") {
            switch ($statename) {
                default:
                    $this->gamestate->nextState( "zombiePass" );
                	break;
            }

            return;
        }

        if ($state['type'] === "multipleactiveplayer") {
            // Make sure player is in a non blocking status for role turn
            $this->gamestate->setPlayerNonMultiactive( $active_player, '' );
            
            return;
        }

        throw new feException( "Zombie mode not supported at this game state: ".$statename );
    }
    
///////////////////////////////////////////////////////////////////////////////////:
////////// DB upgrade
//////////

    /*
        upgradeTableDb:
        
        You don't have to care about this until your game has been published on BGA.
        Once your game is on BGA, this method is called everytime the system detects a game running with your old
        Database scheme.
        In this case, if you change your Database scheme, you just have to apply the needed changes in order to
        update the game database and allow the game to continue to run with your new version.
    
    */
    
    function upgradeTableDb( $from_version )
    {
        // $from_version is the current version of this game database, in numerical form.
        // For example, if the game was running with a release of your game named "140430-1345",
        // $from_version is equal to 1404301345
        
        // Example:
//        if( $from_version <= 1404301345 )
//        {
//            // ! important ! Use DBPREFIX_<table_name> for all tables
//
//            $sql = "ALTER TABLE DBPREFIX_xxxxxxx ....";
//            self::applyDbUpgradeToAllDB( $sql );
//        }
//        if( $from_version <= 1405061421 )
//        {
//            // ! important ! Use DBPREFIX_<table_name> for all tables
//
//            $sql = "CREATE TABLE DBPREFIX_xxxxxxx ....";
//            self::applyDbUpgradeToAllDB( $sql );
//        }
//        // Please add your future database scheme changes here
//
//
    }    

    // Returns the list of available heros for choosing
    protected function getAvailableHeros()
    {
        self::debug("getAvailableHeros");

        // Get already choosed heros
        $sql = "SELECT hero FROM player WHERE hero IS NOT NULL";
        $result = self::getCollectionFromDb( $sql );
        $remainingHeroes = array();

        $databaseHeroesNames = array_column($result, 'hero');

        self::debug("Heroes in the DB: ".implode(', ', $databaseHeroesNames));

        // Loop on each available heros and add only those who are not choosed
        foreach ($this->heros as $hero) {
            self::debug("Checking hero ".key($hero));

            if (!in_array(key($hero), array_column($result, 'hero'))) {
                $remainingHeroes[] = $hero;
            }
        }

        return $remainingHeroes;
    }
}
