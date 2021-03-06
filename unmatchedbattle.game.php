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

        $this->initTables($players);
        $this->assignTeamsToPlayers($players);
        
        // Activate first player (which is in general a good idea :) )
        $this->activeNextPlayer();

        /************ End of the game initialization *****/
    }

    protected function initTables($players)
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

        // Assign teams to players
    function assignTeamsToPlayers($players)
    {
        $randomizedPlayers = array_keys($players);
        shuffle($randomizedPlayers);

        $teamId = 1;
        $playersInTeam = 0;
        foreach($randomizedPlayers as $player_id)
        {
            $sql = "UPDATE player SET team = '".$teamId."' WHERE player_id = ".$player_id;
            self::DbQuery( $sql );
                    
            $playersInTeam++;
            if ((array_key_exists(101, $this->gamestate->table_globals)) && ($this->gamestate->table_globals[101] == 1))
            {
                if ($playersInTeam == 2)
                {
                    $teamId++;
                    $playersInTeam = 0;
                }
            }
            else
            {
                $teamId++;
            }
        }        
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

        $player = $this->getPlayerById($currentPlayerId);
        $hero = $player['hero'];

        self::debug("State : ".$this->gamestate->state()['name']);
        self::debug("Player Info : ".json_encode($player));
        self::debug("Current Hero : ".$hero);

        if ($hero != null) 
        {
            $result['playerHero'] = $hero;
        }

        $state = $this->gamestate->state();
        if (($state['name'] == 'chooseHero') || ($state['name'] == 'chooseHeroNextPlayer'))
        {
                // Which heros are available at the start of the game
                $result['availableHeros'] = $this->getAvailableHeros();
        }

        if (($state['name'] == 'placeSidekicks') || ($state['name'] == 'placeSidekicksNextPlayer'))
        {
                // Which sidekicks the player has to place
                $result['playerSidekicks'] = $this->getPlayerSidekicks($player);
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

        // Informations on existing cards of the game
        $result['cardtypes'] = $this->cardtypes;

        // If a move is possible, we return the possible characters, the move amount and any special restrictions
        if ($state['name'] == "actionMove")
        {
            $action = $this->getActionInProgress();
            self::debug("Action : ".json_encode($action));

            if ($action['action_type'] != "Move")
            {
                throw new feException("Action is not Move");
            }

            $result['moveWho'] = $action['arg1'];
            $result['moveAmount'] = $action['arg2'];
            $result['moveSpecial'] = $action['arg3'];
        }

        $result['team'] = $player['team'];

        self::debug("This->heros : ".json_encode($this->heros));
        // Player panels
        $result['playersPanels'] = $this->getPlayersPanels();

        self::debug("getAllData: ".json_encode($result));
  
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

    function aliceChooseSize ( $size )
    {
        self::checkAction('chooseSize');
        
        $player_id = self::getActivePlayerId();
        $sizeValue = ($size == "sizeBig") ? "B" : "S";

        $this->aliceChangeSize($sizeValue);
        $sizeLabel = $this->getAliceSizeLabel($sizeValue);

        // Notify all players about the hero chosen and the remaining available heroes
        self::notifyAllPlayers( "sizeSelected", clienttranslate( '${player_name} choosed Alice to be ${sizeLabel}' ), array(
            'player_id' => $player_id,
            'player_name' => self::getActivePlayerName(),
            'sizeLabel' => $sizeLabel
        ) );

        $this->gamestate->nextState( 'everyoneChoosedHero' );
    }

    function sidekickPlacementDone($sidekicksPlacement)
    {
        self::checkAction('placeSidekicks');

        if ($this->validateSidekicksPlacement($sidekicksPlacement))
        {
            self::debug("Sidekick placement: ".json_encode($sidekicksPlacement));

            foreach($sidekicksPlacement as $sidekickPlacement)
            {              
                // Infer the hero name from the sidekick id
                $internal_id = $sidekickPlacement['sidekick'];
                $hero = explode("_", $internal_id)[0];

                $sidekick = array_filter($this->heros[$hero]['sidekicks'], function($sidekick) use ($internal_id) {
                    return $sidekick['internal_id'] == $internal_id;
                });

                $health = array_pop($sidekick)['health'];
                $sql = "INSERT INTO tokens (token_name, area_id, health) VALUES ('".$sidekickPlacement['sidekick']."', ".$sidekickPlacement['area_id'].", ".$health.")";
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
        $playerId = self::getCurrentPlayerId();

        $currentPlayerHero = $this->getPlayerById($playerId)['hero'];
        $sidekicks = array_column($this->heros[$currentPlayerHero]['sidekicks'], 'internal_id');
        self::debug("Sidekicks : ".json_encode($sidekicks));
        
        $playerNo = $this->getPlayerNoById($playerId);
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

    function tokensMovementDone($tokensMovement)
    {
        self::debug("Tokens Movement: ".json_encode($tokensMovement));

        if ($this->validateMovement($tokensMovement))
        {
            foreach($tokensMovement as $tokenMovement)
            {
                $sql = "UPDATE tokens SET area_id = ".$tokenMovement['area_id']." WHERE token_name = '".$tokenMovement['token']."'";
                self::DbQuery( $sql );
            }

            $tokensPlacement = $this->getTokensPlacement();

            $player_id = self::getActivePlayerId();

            self::notifyAllPlayers( "placeTokens", clienttranslate( '${player_name} moved his fighters' ), array(
                'player_id' => $player_id,
                'player_name' => self::getActivePlayerName(),
                'tokensPlacement' => $tokensPlacement
            ) );

            // Discard the boost card if any
            $this->cards->moveAllCardsInLocation( 'played_'.$player_id, 'discard_'.$player_id);

            $this->updatePlayerActions("M");

            // Check if the player has played his two actions
            $this->gamestate->nextState('checkPlayActionDone');
        }
    }

    function validateMovement($tokensMovement)
    {
        // Get the amount of possible movements
        $player_id = self::getCurrentPlayerId();

        $action = $this->getActionInProgress();

        if ($action['action_type'] != "Move")
        {
            throw new feException("Action type is not Move");
        }

        $moveAmount = $action['arg2'];

        self::debug("Move amount : ".$moveAmount);
        $playerTeam = $this->getPlayerTeam($player_id);
        
        self::debug("Player team : ".$playerTeam);
        self::debug("Tokens movement : ".json_encode($tokensMovement));

        $tokensPlacement = $this->getTokensPlacement();
        self::debug("Tokens placement : ".json_encode($tokensPlacement));

        $impassablesTokens = array_filter($tokensPlacement, function($tokenPlacement) use ($playerTeam)
        {
            return $tokenPlacement['team'] != $playerTeam;
        });

        self::debug("Impassable tokens : ".json_encode($impassablesTokens));

        $impassablesAreas = array_column($impassablesTokens, 'area_id');

        self::debug("Impassable areas : ".json_encode($impassablesAreas));

        foreach($tokensMovement as $tokenMovement)
        {
            $initialPlacement = array_filter($tokensPlacement, function($tokenPlacement) use ($tokenMovement) 
            {
                return $tokenPlacement['token_id'] == $tokenMovement['token'];
            });

            self::debug("Initial placement : ".json_encode($initialPlacement));
            $initialArea = array_pop($initialPlacement);
            self::debug("Initial area : ".$initialArea['area_id']);

            $validAreas = $this->getAdjacentAreas($initialArea['area_id'], null, 1, $moveAmount, $impassablesAreas);

            self::debug("Valid areas : ".json_encode($validAreas));

            if (!in_array($tokenMovement['area_id'], $validAreas))
            {
                throw new feException("Invalid Movement Area: ".$tokenMovement['area_id']);
            }
        }

        $uniquePlacement = array();        
        foreach($tokensPlacement as $tokenPlacement)
        {
            // Find if the token is one that has been moved
            $tokenMovement = array_filter($tokensMovement, function($tokenMovement) use ($tokenPlacement) 
            {
                return $tokenMovement['token'] == $tokenPlacement['token_id'];
            });

            self::debug("Token movement : ".json_encode($tokenMovement));

            $area_id = 0;
            if (count($tokenMovement) == 0)
            {
                $area_id = $tokenPlacement['area_id'];
            }
            else
            {
                $area_id = array_pop($tokenMovement)['area_id'];
            }

            if (in_array($area_id, $uniquePlacement))
            {
                throw new feException("Invalid Movement - Two Tokens in Same Area : ".$area_id);
            }
            else
            {
                array_push($uniquePlacement, $area_id);
            }
        }

        self::debug("Unique placement : ".json_encode($uniquePlacement));

        return true;
    }

    // Recursive function to get all the adjacent zones of an area until the move amount is reached
    function getAdjacentAreas($startingArea, $areasFound, $currentDistance, $maxDistance, $impassablesAreas) {
        if ($areasFound == null) {
            $areasFound = array();
        }

        $boardId = $this->getGameStateValue('boardId');
        $exits = $this->boards[$boardId]['zones'][$startingArea]['exits'];

        foreach($exits as $exit)
        {
            if (($impassablesAreas == null) || (!in_array($exit, $impassablesAreas))) 
            {
                if (!in_array($exit, $areasFound))
                {
                    array_push($areasFound, $exit);
                }

                if ($currentDistance < $maxDistance) 
                {
                    $areasFound = $this->getAdjacentAreas($exit, $areasFound, $currentDistance + 1, $maxDistance, $impassablesAreas);
                }                
            }
        }

        return $areasFound;
    }

    function playActionManeuverDrawCard()
    {
        self::checkAction('playActionManeuver');

        $playerId = self::getActivePlayerId();
        $hero = $this->getPlayerById($playerId)['hero'];

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
        $hero = $this->getPlayerById($playerId)['hero'];

        self::debug("Hero : ".$hero." played boost card: ".$boostCardId);

        $boostAmount = 0;

        if ($boostCardId != 0)
        {
            $card = $this->cards->getCard($boostCardId);
            $this->cards->moveCard( $boostCardId, 'discard_'.$playerId );
            self::debug("Boost card: ".json_encode($card));
            $cardDefinition = $this->getCardByInternalId($card['type_arg']);
            self::debug("Boost card definition: ".json_encode($cardDefinition));
            $boostAmount = $cardDefinition['boost'];
        }

        $heroMove = 0;

        if ($hero != null)
        {
            $heroMove = $this->heros[$hero]['move'];
        }

        self::debug("Hero move : ".$heroMove." - Boost amount : ".$boostAmount);

        $moveAmount = $boostAmount + $heroMove;

        $this->setActionInProgress("Move", "OwnFighters", $moveAmount, "Normal");

        if ($boostCardId == 0)
        {
            self::notifyAllPlayers( "moveAmount", clienttranslate( '${player_name} declined to play a boost card and can move ${moveAmount} area per fighters' ), array(
                'player_id' => $playerId,
                'player_name' => self::getActivePlayerName(),
                'moveAmount' => $moveAmount
            ));
        }
        else
        {
            self::notifyAllPlayers( "moveAmount", clienttranslate( '${player_name} played the ${cardName} card as a boost and can move ${moveAmount} area per fighters' ), array(
                'player_id' => $playerId,
                'player_name' => self::getActivePlayerName(),
                'cardName' => $this->getCardByInternalId($card['type_arg'])['name'],
                'moveAmount' => $moveAmount
            ));
        }        

        $this->gamestate->nextState('actionMove');
    }

    function playSchemeCard($schemeCardId)
    {
        $player_id = self::getActivePlayerId();
        self::debug("Play scheme card: ".$schemeCardId);
        self::checkAction('playActionScheme');
        $this->cards->moveCard( $schemeCardId, 'discard_'.$player_id );
        $this->setActionInProgress("Scheme", $schemeCardId, null, null);
        $this->gamestate->nextState('playActionScheme');
    }

    function playActionScheme()
    {        
        $player_id = self::getActivePlayerId();

        $action = $this->getActionInProgress();
        
        if ($action["action_type"] != "Scheme")
        {
            throw new feException("Action type is not Scheme");
        }

        $card_id = $action['arg1'];

        if ($card_id == null)
            throw new feException("No scheme card found");

        self::debug("Play scheme card: ".$card_id);

        switch($card_id)
        {
            case 105: // Eat Me
                $this->schemeEatMe();
                break;
            case 107: // Drink Me
                $this->schemeDrinkMe();
                break;
            case 206: // The Lady of the Lake
                $this->schemeTheLadyOfTheLake();
                break;
            case 207: // Prophecy
                $this->schemeProphecy();
                break;
            case 210: // Restless Spirits
                $this->schemeRestlessSpirits();
                break;
            case 211: // Command the Storms
                $this->schemeCommandTheStorms();
                break;
            case 302: // A Momentary Glance
                $this->schemeAMomentaryGlance();
                break;
            case 306: // Winged Frenzy
                $this->schemeWingedFrenzy();
                break;
            case 403: // Riches Beyond Compare
                $this->schemeRichesBeyondCompare();
                break;            
        }        
    }

    function schemeEatMe()
    {
        $player_id = self::getActivePlayerId();
        $newSize = $this->aliceChangeSize(null);
        $newSizeLabel = $this->getAliceSizeLabel($newSize);

        self::notifyAllPlayers( "schemeEatMe", clienttranslate( '${player_name} played the Eat Me scheme card and became ${newSizeLabel}.' ), array(
            'player_id' => $player_id,
            'player_name' => self::getActivePlayerName(),
            'newSize' => $newSize,
            'newSizeLabel' => $newSizeLabel
        ));

        $this->gamestate->nextState('moveFighter');
    }
    
    function schemeDrinkMe()
    {
        $player_id = self::getActivePlayerId();
        $cards = $this->drawCards($player_id, 2);
        $cardsNames = $this->getCardsNames($cards);
        
        $newSize = $this->aliceChangeSize(null);
        $newSizeLabel = $this->getAliceSizeLabel($newSize);
        
        self::notifyPlayer( $player_id, "schemeDrinkMe", clienttranslate( 'You played the Drink Me scheme card.  You received: ${cardsNames} and became ${newSizeLabel}.' ), array(
            'player_id' => $player_id,
            'cards' => $cards,
            'cardsNames' => implode(", ", $cardsNames),
            'newSize' => $newSize,
            'newSizeLabel' => $newSizeLabel
        ));
                
        self::notifyAllPlayers( "schemeDrinkMe", clienttranslate( '${player_name} played the Drink Me scheme card, drew ${cardDrawn} cards and became ${newSizeLabel}.' ), array(
            'player_id' => $player_id,
            'player_name' => self::getActivePlayerName(),
            'newSizeLabel' => $newSizeLabel,
            'newSize' => $newSize,
            'cardDrawn' => count($cards)
        ));

        $this->gamestate->nextState('checkPlayActionDone');
    }

    function schemeTheLadyOfTheLake()
    {
        self::notifyAllPlayers( "moveAmount", clienttranslate( '${player_name} played the Eat Me scheme card' ), array(
            'player_id' => $playerId,
            'player_name' => self::getActivePlayerName(),
            'cardName' => $this->getCardByInternalId($card['type_arg'])['name']
        ));

        $this->gamestate->nextState('checkPlayActionDone');
    }
    
    function schemeProphecy()
    {
        self::notifyAllPlayers( "moveAmount", clienttranslate( '${player_name} played the Eat Me scheme card' ), array(
            'player_id' => $playerId,
            'player_name' => self::getActivePlayerName(),
            'cardName' => $this->getCardByInternalId($card['type_arg'])['name']
        ));

        $this->gamestate->nextState('chooseCards');
    }

    function schemeRestlessSpririts()
    {
        self::notifyAllPlayers( "moveAmount", clienttranslate( '${player_name} played the Eat Me scheme card' ), array(
            'player_id' => $playerId,
            'player_name' => self::getActivePlayerName(),
            'cardName' => $this->getCardByInternalId($card['type_arg'])['name']
        ));

        $this->gamestate->nextState('chooseZone');
    }
    
    function schemeCommandTheStorms()
    {
        self::notifyAllPlayers( "moveAmount", clienttranslate( '${player_name} played the Eat Me scheme card' ), array(
            'player_id' => $playerId,
            'player_name' => self::getActivePlayerName(),
            'cardName' => $this->getCardByInternalId($card['type_arg'])['name']
        ));

        $this->gamestate->nextState('moveAnyFighters');
    }

    function schemeAMomentaryGlance()
    {
        self::notifyAllPlayers( "moveAmount", clienttranslate( '${player_name} played the Eat Me scheme card' ), array(
            'player_id' => $playerId,
            'player_name' => self::getActivePlayerName(),
            'cardName' => $this->getCardByInternalId($card['type_arg'])['name']
        ));

        $this->gamestate->nextState('chooseFighter');
    }
    
    function schemeWingedFrenzy()
    {
        self::notifyAllPlayers( "moveAmount", clienttranslate( '${player_name} played the Eat Me scheme card' ), array(
            'player_id' => $playerId,
            'player_name' => self::getActivePlayerName(),
            'cardName' => $this->getCardByInternalId($card['type_arg'])['name']
        ));

        $this->gamestate->nextState('moveFightersNoImpassable');
    }

    function schemeRichesBeyondCompare()
    {
        $player_id = self::getActivePlayerId();
        $cards = $this->drawCards($player_id, 3);
        $cardsNames = $this->getCardsNames($cards);
        
        self::notifyPlayer( $player_id, "receiveCards", clienttranslate( 'You played the Riches Beyond Compare scheme card.  You received: ${cardsNames}' ), array(
            'player_id' => $player_id,
            'cards' => $cards,
            'cardsNames' => implode(", ", $cardsNames)
        ));
                
        self::notifyAllPlayers( "moveAmount", clienttranslate( '${player_name} played the Riches Beyond Compare scheme card' ), array(
            'player_id' => $player_id,
            'player_name' => self::getActivePlayerName(),
        ));

        $this->gamestate->nextState('checkPlayActionDone');
    }

    function getMoveFighterArgs()
    {
        $player_id = self::getActivePlayerId();
        $card = $this->cards->getCardOnTop( 'played_'.$player_id );

        self::debug("getMoveFighterArgs Played Card: ".json_encode($card));

        if ($card["type_arg"] == 105) // Alice Scheme : Eat Me
        {
            $moveAmount = 3;
            $fighterName = clienttranslate("Alice");
        }

        return array(
            'moveAmount' => $moveAmount,
            'fighterName' => $fighterName,
        );
    }

    function getActionMoveArgs()
    {
        $action = $this->getActionInProgress();

        if ($action["action_type"] != "Move")
        {
            throw new feException("Action is not Move");
        }

        switch ($action["arg1"])
        {
            case "Any":
                $moveWhoSelf = clienttranslate("any fighters");
                $moveWhoOther = clienttranslate("any fighters");
                break;
            case "Own":
                $moveWhoSelf = clienttranslate("your fighters");
                $moveWhoOther = clienttranslate("his fighters");
                break;
            default:
                $moveWhoSelf = clienttranslate($action["arg1"]);
                $moveWhoOther = clienttranslate($action["arg1"]);
                break;
        }

        switch ($action["arg3"])
        {
            default:
                $moveSpecial = "";
                break;
        }

        return array(
            'moveWhoSelf' => $moveWhoSelf,
            'moveWhoOther' => $moveWhoOther,
            'moveAmount' => $action["arg2"],
            'moveSpecial' => $moveSpecial
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
        $sql = "SELECT player_id id, hero FROM player";
        $playersHeros = self::getCollectionFromDb( $sql );

        $playersWithoutHero = array_filter($playersHeros, function($player) 
        {
            return $player['hero'] == null;
        });

        if (count($playersWithoutHero) == 0) {
            self::debug("Everyone choosed their hero");

            // If one player choosed Alice, it's now time to choose if she starts big or small
            $alicePlayer = array_filter($playersHeros, function($player) {
                return $player['hero'] == 'Alice';
            });

            self::debug("Alice player : ".json_encode($alicePlayer));

            if (count($alicePlayer) == 0)
            {
                $this->gamestate->nextState( 'everyoneChoosedHero' );
            }
            else
            {
                $this->gamestate->changeActivePlayer( array_pop($alicePlayer)['id'] );
                $this->gamestate->nextState('aliceChooseSize');
            }
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

                    $playerHeroObject = $this->heros[$playerHero['hero']];
                    self::debug("Player Hero Object : ".json_encode($playerHeroObject));
                    
                    $health = $playerHeroObject['health'];
                    $sql = "INSERT INTO tokens (token_name, area_id, health) VALUES ('".$playerHero['hero']."', ".$key.", ".$health.")";
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
        $player = $this->getPlayerById($player_id);
        $sidekicks= $this->getPlayerSidekicks($player);

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

    function updatePlayerActions($currentAction)
    {
        $sql = "SELECT player_id, first_action, second_Action FROM player WHERE player_id = ".self::getActivePlayerId();
        $playerActions = self::getCollectionFromDb( $sql );
        self::debug("Player actions : ".json_encode($playerActions));

        $playerActionRecord = array_pop($playerActions);

        $fieldName = '';
        if (($playerActionRecord['first_action']) == null)
        {            
            $fieldName = 'first_action';
        }
        else
        {
            $fieldName = 'second_action';
        }

        self::debug("Updating player action : ".$fieldName);

        $sql = "UPDATE player SET ".$fieldName." = '".$currentAction."' WHERE player_id = ".self::getActivePlayerId();
        self::DbQuery( $sql );
    }

    // Check if the current player has finished his turn
    function checkPlayActionDone()
    {
        $sql = "SELECT second_Action FROM player WHERE player_id = ".self::getActivePlayerId(). " AND second_Action IS NOT NULL";
        $playerActions = self::getCollectionFromDb( $sql );

        if (count($playerActions) > 0)
        {
            $this->resetPlayerActions();

            self::notifyAllPlayers( "playAction", clienttranslate( '${player_name} finished is turn' ), array(
                'player_id' => self::getActivePlayerId(),
                'player_name' => self::getActivePlayerName(),
            ) );

            $this->activeNextPlayer();
        }
        else
        {
            self::notifyAllPlayers( "playAction", clienttranslate( '${player_name} must play his second action' ), array(
                'player_id' => self::getActivePlayerId(),
                'player_name' => self::getActivePlayerName(),
            ) );            
        }

        $this->gamestate->nextState('playAction');        
    }

    
    // Reset the player actions
    function resetPlayerActions()
    {
        $sql = "UPDATE player SET first_action = null, second_action = null WHERE player_id = ".self::getActivePlayerId();
        self::DbQuery( $sql );
    }

    function getPlayerSidekicks($player)
    {
        $heroObject = $this->heros[$player['hero']];
        $sidekicks = array();

        foreach($heroObject['sidekicks'] as $sidekick)
        {
            $sidekicks[] = array(
                'area_id' => '', 
                'token_id' => $sidekick['internal_id'], 
                'token_type' => $sidekick['name'],
                'team' => $player['team']
            );
        }

        self::debug("Hero Sidekicks to place : ".json_encode($sidekicks));

        return $sidekicks;
    }

    function getTokensPlacement()
    {
        $sql = "SELECT area_id, token_name FROM tokens";
        $tokensFromDB = self::getCollectionFromDb( $sql );

        $sql = "SELECT hero, team FROM player";
        $playersHeros = self::getCollectionFromDb( $sql );

        $tokens = array();

        foreach ($tokensFromDB as $token)
        {
            $area = $token['area_id'];
            $tokenId = $token['token_name'];
            $tokenType = $this->getTokenType($tokenId);
            $hero = explode('_', $tokenId)[0];
            $team = $playersHeros[$hero]['team'];

            self::debug("Token : ".$tokenId." Hero: ".$hero." Team: ".$team);

            $tokens[] = array('area_id' => $area, 'token_id' => $tokenId, 'token_type' => $tokenType, 'team' => $team);
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

            $sidekick = array_filter($this->heros[$hero]['sidekicks'], function($sidekick) use ($hero)                 
            {
                return substr($sidekick['internal_id'], 0, strpos($sidekick['internal_id'], '_')) == $hero;
            });

            $tokenType = $this->heros[$hero]['sidekicks'][array_key_first($sidekick)]['name'];
        }
        else
        {
            $tokenType = $tokenId;
        }

        return $tokenType;
    }

    function getPlayersPanels()
    {
        $sql = "SELECT player_id, hero, team, alice_size FROM player";
        $players = self::getCollectionFromDb( $sql );

        $sql = "SELECT token_name, health FROM tokens";
        $tokens = self::getCollectionFromDb( $sql );  

        self::debug("Players: ".json_encode($players));

        $playersPanels = array();

        foreach($players as $player)
        {
            $hero = $player['hero'];
            $playerTokens = array_filter($tokens, function($token) use ($hero)
            {
                return explode("_", $token['token_name'])[0] == $hero;
            });

            $tokensStatus = array();
            foreach($playerTokens as $token)
            {
                $fighterName = $this->getFighterNameFromTokenId($token['token_name']);
                array_push($tokensStatus, array('token_id' => $fighterName, 'health' => $token['health']));
            }

            $playerPanel = array(
                'player_id' => $player['player_id'],
                'hero' => $player['hero'],
                'team' => $player['team'],
                'tokens' => $tokensStatus
            );

            if ($hero == 'Alice')
            {
                $playerPanel['alice_size'] = $player['alice_size'];
            }

            array_push($playersPanels, $playerPanel);
        }

        self::debug("Players Panels: ".json_encode($playersPanels));
        return $playersPanels;
    }

    function getFighterNameFromTokenId($token_id)
    {
        if (strpos($token_id, "_") == 0)
            return $token_id;
        else
        {                  
            $hero = explode("_", $token_id)[0];
            $sidekickObj = array_filter($this->heros[$hero]['sidekicks'] , function($sidekick) use ($token_id)
            {                        
                return $sidekick['internal_id'] == $token_id;
            });

            return array_pop($sidekickObj)['name'];
        }
    }

    function getPlayerTeam($player_id)
    {      
        $sql = "SELECT team FROM player WHERE player_id = ".$player_id;
        $team = self::getCollectionFromDb( $sql );

        self::debug("Team: ".json_encode($team));

        $team = array_pop($team)['team'];

        self::debug('Team : '.$team);

        return $team;
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
    
    function drawCards($player_id, $cardCount)
    {
        $hero = $this->getPlayerById($player_id)['hero'];
        $cards = array();
        $cardsPick = $this->cards->pickCards($cardCount, 'deck_'.$hero, $player_id);
        
        foreach($cardsPick as $card)
        {
            array_push($cards, array ("id" => $card['id'], "internal_id" => $card['type_arg']));
        }
                                        
        // TODO APPLY FATIGUE DAMAGE

        return $cards;
    }

    function getCardsNames($cards)
    {
        self::debug("Cards: ".json_encode($cards));

        $cardsNames = array();
        foreach($cards as $card)
        {
            $cardName = $this->getCardByInternalId($card['internal_id'])['name'];
            array_push($cardsNames, $cardName);
        }

        return $cardsNames;
    }

    function aliceChangeSize($newSize)
    {
        $player_id = self::getActivePlayerId();
        
        if ($newSize == null)
        {
            $sql = "SELECT alice_size FROM player WHERE player_id = ".$player_id;
            $currentSize = self::getUniqueValueFromDB( $sql );
            $newSize = ($currentSize == "S") ? "B" : "S";
        }    
        
        $sql = "UPDATE player SET alice_size = '".$newSize."' WHERE player_id = ".$player_id;
        self::DbQuery( $sql );

        return $newSize;
    }

    function getAliceSizeLabel($size)
    {
        return ($size == "B") ? clienttranslate("Big") : clienttranslate("Small");
    }

    function setActionInProgress($action, $arg1, $arg2, $arg3)
    {
        $player_id = self::getActivePlayerId();
        $sql = "DELETE FROM action_in_progress";
        self::DbQuery( $sql );

        $sql = "INSERT INTO action_in_progress SET action_type = '".$action."', arg1 = '".$arg1."', arg2 = '".$arg2."', arg3 = '".$arg3."'";
        self::DbQuery( $sql );
    }

    function getActionInProgress()
    {
        $sql = "SELECT action_type, arg1, arg2, arg3 FROM action_in_progress";
        $action = self::getCollectionFromDb( $sql );
        self::debug("Action: ".json_encode($action));
        return array_pop($action);
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

    protected function getPlayerById($playerId)
    {   
        // Get information about players
        $sql = "SELECT player_id id, player_score score, hero, team FROM player where player_id = ".$playerId;
        $result = self::getCollectionFromDb( $sql ); 
        $player = array_pop($result);
        
        return $player;
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
