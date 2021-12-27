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
        self::debug("Init tables");

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
        self::debug("Board ID : ".$boardId);
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

        // !! We must only return informations visible by this player !!
    
        $currentPlayerId = self::getCurrentPlayerId();    
        $hero = $this->getCurrentPlayerHero($currentPlayerId);

        self::debug("State : ".$this->gamestate->state()['name']);
        self::debug("Current Hero : ".$hero);

        $state = $this->gamestate->state();
        if (($state['name'] == 'chooseHero') || ($state['name'] == 'chooseHeroNextPlayer'))
        {
                // Which heros are available at the start of the game
                $result['availableHeros'] = $this->getAvailableHeros();
        }

        $result['playerDeck'] = $this->getHeroCards($hero);

        $playerHand = $this->cards->getPlayerHand($currentPlayerId);

        $cards = array();

        foreach($playerHand as $card)
        {                   
            $cards[] = array ("id" => $card['id'], "internal_id" => $card['type_arg']);
        }

        $result['playerHand'] = $cards;
        $result['tokensPlacement'] = $this->getTokensPlacement();

        self::debug("Tokens Placement : ".json_encode($result['tokensPlacement']));

        $result['playerSidekicks'] = $this->getHeroSidekicks($hero);
        $result['playerHero'] = $hero;

        self::debug("getAllData HERO : ".json_encode($result));

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

        $this->createCardDeck($hero);

        self::notifyAllPlayers( "heroSelected", clienttranslate( '${player_name} choosed to play ${hero}' ), array(
            'player_id' => $player_id,
            'player_name' => self::getActivePlayerName(),
            'hero' => $hero,
            'deck' => $this->getHeroCards($hero)
        ) );

        self::debug("Player ".$player_id." choosed to play ".$hero);

        // Next player
        $this->gamestate->nextState( 'chooseHeroNextPlayer' );
    }

    function sidekickPlacementDone($sidekicksPlacement)
    {
        self::checkAction('placeSidekicks');

        if ($this->validateSidekicksPlacement($sidekicksPlacement))
        {
            self::debug("Sidekick placement: ".json_encode($sidekicksPlacement));

            foreach($sidekicksPlacement as $sidekick => $sidekickPlacement)
            {            
                $sql = "INSERT INTO tokens (token_name, area_id) VALUES ('".$sidekickPlacement['sidekick']."', ".$sidekickPlacement['area_id'].")";
                self::DbQuery( $sql );
            }

            $tokensPlacement = $this->getTokensPlacement();

            self::notifyAllPlayers( "placeTokens", clienttranslate( '${player_name} placed his sidekicks' ), array(
                'player_id' => self::getActivePlayerId(),
                'player_name' => self::getActivePlayerName(),
                'tokensPlacement' => $tokensPlacement
            ) );

            // Check if it's the last player to place his sidekicks
            $this->gamestate->nextState('placeSidekicksNextPlayer');
        }
    }

    function validateSidekicksPlacement($sidekicksPlacement)
    {
        $boardId = $this->getGameStateValue('boardId');
        $board = $this->boards[$boardId];
        $zones = $board['zones'];

        $currentPlayerHero = $this->getCurrentPlayerHero(self::getCurrentPlayerId());
        $sidekicks = array_column($this->heros[$currentPlayerHero]['sidekicks'], 'internal_id');
        self::debug("Sidekicks : ".json_encode($sidekicks));
        
        $playerNo = $this->getPlayerNoById(self::getCurrentPlayerId());
        self::debug("PlayerNo : ".$playerNo);
        
        $startingArea = array_filter($zones, function($zone) use ($playerNo) 
        {
            return $zone['startingPlayer'] == $playerNo;
        });

        if (count($startingArea) != 1)
        {
            throw new feException("Starting Area not found for player: ".$playerNo);
        }

        self::debug("Starting area : ".json_encode($startingArea));

        $availableZones = $this->getZonesSameColors($startingArea[array_key_first($startingArea)]['colors'], array(array_key_first($startingArea)));
        self::debug("Available Zones : ".json_encode($availableZones));

        foreach ($sidekicksPlacement as $sidekickPlacement)
        {
            // Is the sidekick in the correct starting area ?
            if(!key_exists($sidekickPlacement['area_id'], $availableZones))
            {
                throw new feException("Invalid zone: ".$sidekickPlacement['area_id']);
            }

            self::debug("Sidekick placement : ".json_encode($sidekickPlacement));

            // Is the sidekick in the correct player sidekick pool?
            if(!in_array($sidekickPlacement['sidekick'], $sidekicks))
            {
                throw new feException("Invalid sidekick: ".$sidekickPlacement['sidekick']);
            }
        }

        // Are all sidekicks assigned only once ?
        $sidekickPlacement = array_column($sidekicksPlacement, 'sidekick');
        if (count(array_unique($sidekickPlacement)) != count($sidekickPlacement))
        {
            throw new feException("Duplicate sidekicks");
        }

        // Are all sidekick placed ?
        if (count($sidekicksPlacement) != count($sidekicks))
        {
            throw new feException("Missing sidekicks");
        }

        return true;
    }

    function playActionManeuverDrawCard()
    {
        self::checkAction('playAction');

        $playerId = self::getActivePlayerId();
        $hero = $this->getCurrentPlayerHero($playerId);

        // draw a card
        $cards = array();
        $card = $this->cards->pickCard('deck_'.$hero, $playerId);

        array_push($cards, array ("id" => $card['id'], "internal_id" => $card['type_arg']));
        
        self::debug("Maneuver draw card: ".json_encode($card));

        self::notifyPlayer( $playerId, "receiveCards", clienttranslate( 'You start a maneuver and drawed the ${cardName} card' ), array(
            'player_id' => $playerId,
            'player_name' => self::getActivePlayerName(),
            'cards' => $cards,
            'cardName' => $this->getCardByInternalId($card['type_arg'])['name']
        ));

        self::notifyAllPlayers( "performManeuver", clienttranslate( '${player_name} performs a maneuver' ), array(
            'player_id' => $playerId,
            'player_name' => self::getActivePlayerName()
        ) );

        // Check if it's the last player to place his sidekicks
        $this->gamestate->nextState('playActionManeuver');
    }

    function playBoostCard($boostCardId)
    {
        self::checkAction('playBoostCard');

        $playerId = self::getActivePlayerId();
        $hero = $this->getCurrentPlayerHero($playerId);

        self::debug("Hero : ".$hero." played boost card: ".$boostCardId);

        $card = $this->cards->getCard($boostCardId);

        $this->cards->moveCard( $boostCardId, 'played_'.$playerId );

        $this->gamestate->nextState('playActionMove');
    }

    function getPlayActionMoveArgs()
    {
        $playerId = self::getCurrentPlayerId();

        $card = $this->cards->getCardOnTop( 'played_'.$playerId );
        $boostAmount = 0;

        if ($card != null)
        {
            self::debug("getPlayActionMoveArgs - Boost card: ".json_encode($card));
            $cardDefinition = $this->getCardByInternalId($card['type_arg']);
            self::debug("getPlayActionMoveArgs - Boost card definition: ".json_encode($cardDefinition));
            $boostAmount = $cardDefinition['boost'];
        }

        $hero = $currentPlayerHero = $this->getCurrentPlayerHero(self::getCurrentPlayerId());
        $heroMove = $this->heros[$hero]['move'];

        self::debug("Hero move : ".$heroMove." - Boost amount : ".$boostAmount);

        $moveAmount = $boostAmount + $heroMove;

        return array(
            'moveAmount' => $moveAmount
        );
    }

    function getZonesSameColors($colors, $excludedZones)
    {
        $boardId = $this->getGameStateValue('boardId');
        $board = $this->boards[$boardId];
        $zones = $board['zones'];

        $availableZones = array_filter($zones, function($zone) use ($colors) 
        {
            return count(array_intersect($zone['colors'], $colors)) > 0;
        });

        self::debug("Excluded Zones : ".json_encode($excludedZones));
        self::debug("Available Zones : ".json_encode($availableZones));

        // Remove any excluded zones
        if (count($excludedZones) > 0)
        {
            $availableZones = array_filter($availableZones, function($zoneKey) use ($excludedZones) 
            {
                self::debug("Zone key : ".$zoneKey);
                return !in_array($zoneKey, $excludedZones);
            }, ARRAY_FILTER_USE_KEY);
        }

        return $availableZones;
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

    function checkEveryonePlacedSidekicks()
    {
        self::debug("---- Check everyone placed sidekicks ----");

        $sql = "SELECT player_id id, hero FROM player";
        $playerHero = self::getCollectionFromDb( $sql );

        // Compose a list of all sidekicks
        $sidekicks = array();

        foreach ($playerHero as $player)
        {
            $sidekicks = array_merge($sidekicks, array_column($this->heros[$player['hero']]['sidekicks'], 'internal_id'));
        }

        self::debug("Sidekicks : ".json_encode($sidekicks));

        // Check if all sidekicks are placed
        $sql = "SELECT token_name FROM tokens";
        $tokensPlaced = self::getCollectionFromDb( $sql );

        self::debug("Tokens placed : ".json_encode($tokensPlaced));

        $AllSidekicksPlaced = true;
        foreach($sidekicks as $sidekick)
        {
            if(!array_key_exists($sidekick, $tokensPlaced))
            {
                self::debug("Sidekick not placed : ".$sidekick);
                $AllSidekicksPlaced = false;
                break;
            }
        }

        if ($AllSidekicksPlaced)
        {
            self::debug("Everyone placed their sidekicks");
            
            self::notifyAllPlayers( "playAction", clienttranslate( 'The battle is ready to start!' ), array(
                'player_id' => self::getActivePlayerId(),
            ) );

            $this->gamestate->nextState( 'playAction' );
        }
        else
        {
            self::debug("Not everyone placed their sidekicks");
            $this->activeNextPlayer();
            $this->gamestate->nextState( 'placeSidekicks' );
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

        self::debug("Placing heros in their starting area of board : ".$board['name']." (".$boardId.")");
                
        // We loop on all players in order of play (player_no)
        foreach ($this->players as $player)
        {
            $playerHero = $playersHeros[$player['player_id']];
            self::debug("Player id ".$player['player_id']." has hero ".$playerHero['hero']);
                            
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

        $this->notifyHerosPlacement();

        $this->gamestate->nextState( 'assignSidekicks' );
    }

    function notifyHerosPlacement()
    {
        self::notifyAllPlayers( "placeTokens", 
            clienttranslate( 'All heros are placed in their starting area' ),
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
        $sidekicks= $this->getHeroSidekicks($hero);

        self::debug("Assigning sidekick: ".json_encode($sidekicks));

        self::notifyPlayer($player_id, "receiveSidekicks", "", array(
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
            // Give 5 cards to the player
            $playerHand = $this->cards->pickCards(5, 'deck_'.$player['hero'], $player['player_id']);

            $cards = array();

            foreach($playerHand as $card)
            {
                $cards[] = array ("id" => $card['id'], "internal_id" => $card['type_arg']);
            }

            // Notify the player about the cards he received (and the definition of the cards in his deck)
            self::notifyPlayer( $player['player_id'], 'receiveCards', '', array ('cards' => $cards ));
        }

        $this->gamestate->nextState( 'placeHero' );
    }

    // Check if the current player has finished his turn
    function checkPlayActionDone()
    {

    }

    function getHeroSidekicks($hero)
    {
        $heroObject = $this->heros[$hero];
        $sidekicks = array();

        foreach($heroObject['sidekicks'] as $sidekick)
        {
            $sidekicks[] = array('area_id' => '', 'token_id' => $sidekick['internal_id'], 'token_type' => $sidekick['name']);
        }

        self::debug("Hero Sidekicks to place : ".json_encode($sidekicks));

        return $sidekicks;
    }

    function getTokensPlacement()
    {
        $sql = "SELECT area_id, token_name FROM tokens";
        $tokensFromDB = self::getCollectionFromDb( $sql );
        
        $tokens = array();

        foreach ($tokensFromDB as $token)
        {
            $area = $token['area_id'];
            $tokenId = $token['token_name'];
            $tokenType = $this->getTokenType($tokenId);
            $tokens[] = array('area_id' => $area, 'token_id' => $tokenId, 'token_type' => $tokenType);
        }

        self::debug("Tokens: ".json_encode($tokens));
        return $tokens;
    }

    function getTokenType($token_name)
    {
        // Checks if the token_name contains a _ which would mean a sidekick
        $separator = strpos($token_name, "_");
        $tokenId = $token_name;
        $tokenType = "";

        if ($separator !== false)
        {
            $hero = substr($token_name, 0, $separator);
            self::debug("Sidekick for Hero: ".$hero);

            $sidekick = array_filter($this->heros[$hero]['sidekicks'], function($sidekick) use ($hero)                 
            {
                self::debug("Sidekick internal_id: ".$sidekick['internal_id']);
                return substr($sidekick['internal_id'], 0, strpos($sidekick['internal_id'], '_')) == $hero;
            });

            self::debug("Sidekick: ".json_encode($sidekick));

            $tokenType = $this->heros[$hero]['sidekicks'][array_key_first($sidekick)]['name'];

            self::debug("Sidekick: ".$tokenType);
        }
        else
        {
            $tokenType = $tokenId;
        }

        return $tokenType;
    }

    function getHeroCards($hero)
    {
        self::debug("All cards: ".json_encode($this->cardtypes));

        $cards = array_filter($this->cardtypes, function($obj) use ($hero) 
        {
             return $obj['deck'] == $hero && $obj['type'] == 'card'; 
        });

        self::debug("Hero : ".$hero." cards: ".json_encode($cards));
    
        return $cards;
    }

    function getCardByInternalId($internal_id)
    {
        $cards = array_filter($this->cardtypes, function($obj) use ($internal_id) 
        {
             return $obj['internal_id'] == $internal_id; 
        });

        self::debug("Card: ".json_encode($cards));

        return array_pop($cards);
    }

    // Create cards for all players
    function createCardDeck($hero)
    {
        self::debug("Creating card for: ".$hero);

        $cardsofhero = array_filter($this->cardtypes, function($obj) use ($hero) 
        {
             return $obj['deck'] == $hero && $obj['type'] == 'card'; 
        });
        
        $cards = array();

        // Loop on all cards of the hero
        foreach($cardsofhero as $card)
        {
            self::debug("Card: ".$card['name']);
            $cards[] = array('type' => $hero, 'type_arg' => $card['internal_id'], 'nbr' => $card['nbr']);
        }

        // Add the cards of the hero to his their deck
        $this->cards->createCards( $cards, 'deck_'.$hero);

        // Shuffle the deck
        $this->cards->shuffle( 'deck_'.$hero );
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

    protected function getCurrentPlayerHero($currentPlayerId)
    {   
        // Get information about players
        $sql = "SELECT player_id id, player_score score, hero FROM player ";
        $result['players'] = self::getCollectionFromDb( $sql );
  
        // TODO: Gather all information about current game situation (visible by player $current_player_id).

        $hero = $result['players'][$currentPlayerId]['hero'];
        
        return $hero;
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
        foreach ($this->heros as $heroKey => $hero) {
            self::debug("Checking hero ".$heroKey);

            if (!in_array($heroKey, array_column($result, 'hero'))) {
                $remainingHeroes[] = $heroKey;
            }
        }

        return $remainingHeroes;
    }
}
