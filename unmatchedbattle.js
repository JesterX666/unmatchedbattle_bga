/**
 *------
 * BGA framework: © Gregory Isabelli <gisabelli@boardgamearena.com> & Emmanuel Colin <ecolin@boardgamearena.com>
 * UnmatchedBattle implementation : © <Your name here> <Your email address here>
 *
 * This code has been produced on the BGA studio platform for use on http://boardgamearena.com.
 * See http://en.boardgamearena.com/#!doc/Studio for more information.
 * -----
 *
 * unmatchedbattle.js
 *
 * UnmatchedBattle user interface script
 * 
 * In this file, you are describing the logic of your user interface, in Javascript language.
 *
 */

define([
    "dojo","dojo/_base/declare",
    "ebg/core/gamegui",
    "ebg/counter",
    "ebg/stock"
],
function (dojo, declare) {
    return declare("bgagame.unmatchedbattle", ebg.core.gamegui, {
        constructor: function(){
            console.log('unmatchedbattle constructor');
              
            // Here, you can init the global variables of your user interface
            // Example:
            // this.myGlobalValue = 0;

            // Dimensions of cards
            this.cardheight = 520;
            this.cardwidth = 372;
        },
        
        /*
            setup:
            
            This method must set up the game user interface according to current game situation specified
            in parameters.
            
            The method is called each time the game interface is displayed to a player, ie:
            _ when the game starts
            _ when a player refreshes the game page (F5)
            
            "gamedatas" argument contains all datas retrieved by your "getAllDatas" PHP method.
        */
        
        setup: function( gamedatas )
        {
            console.log( "Starting game setup" );
            
            // Setting up player boards
            for( var player_id in gamedatas.players )
            {
                var player = gamedatas.players[player_id];
                         
                // TODO: Setting up players boards if needed
            }
            
            // TODO: Set up your game interface here, according to "gamedatas"

            switch (gamedatas.gamestate.name) {
                case "chooseHero":
                    this.setupChooseHero(gamedatas);
                    break;
            }
            // Setup game notifications to handle (see "setupNotifications" method below)
            this.setupNotifications();

            console.log( "Ending game setup" );
        },

        setupChooseHero: function (gamedatas) {            
            // List of available characters
            this.availableCharacters = new ebg.stock();
            this.availableCharacters.create( this, $('availableCharacters'), this.cardwidth, this.cardheight );
            this.availableCharacters.setSelectionMode(1);
            
            dojo.connect (this.availableCharacters, 'onChangeSelection', this, 'onChangeCharacterSelection');

            // Specify that there are 4 characters per row in the CSS sprite image
            this.availableCharacters.image_items_per_row = 4;
            
            var type = 0;
            // Adding all available characters to the stock, with their image
            Object.values(gamedatas.availablecharacters).forEach(character => {
                    // We find the card back image from the list of all cards
                    var card = Object.values(gamedatas.cardtypes).find(card => card['deck'] == character['name'] && 
                                                                               card['type'] == 'back');
                    
                    if (card) {
                        // We initialise the card item type to the stock
                        this.availableCharacters.addItemType(type, 0, g_gamethemeurl + 'img/Cards/' + card['image']);

                        // We add the card to the stock
                        this.availableCharacters.addToStockWithId(type, character['name']);
                    }

                    type++;
            });        
        },

        onChangeCharacterSelection: function (selection) {
            var items = this.availableCharacters.getSelectedItems();
            if (items.length > 0) {
                if (!document.getElementById('characterSelectionConfirm')) {
                    this.addActionButton( 'characterSelectionConfirm', _('Confirm'), 'onCharacterSelect' ); 
                }
            }
            else {
                    this.removeActionButtons();
            }            
        },       

        onCharacterSelect: function () {
            debugger;
            if (this.checkAction('chooseCharacter')) {
                var items = this.availableCharacters.getSelectedItems();
                this.ajaxcall( '/unmatchedbattle/unmatchedbattle/chooseCharacter.html', { 'character': items[0]['id'] }, this, 'onCharacterSelectResponse');                
            }
        },

        onCharacterSelectResponse: function (data) {
            debugger;
            this.endAction('chooseCharacter');
            this.availableCharacters.removeAllItems();
            this.removeActionButtons();
        },

        ///////////////////////////////////////////////////
        //// Game & client states
        
        // onEnteringState: this method is called each time we are entering into a new game state.
        //                  You can use this method to perform some user interface changes at this moment.
        //
        onEnteringState: function( stateName, args )
        {
            console.log( 'Entering state: '+stateName );
            
            switch( stateName ) {
                case "chooseHero":                
                    // Show the character selection HTML block at this game state
                    dojo.style( 'availableCharacters', 'display', 'block' );
                    // Hide the main game HTML block at this game state
                    dojo.style( 'mainGame', 'display', 'none' );    
                    break;           
           
                case 'dummmy':
                    break;
            }
        },

        // onLeavingState: this method is called each time we are leaving a game state.
        //                 You can use this method to perform some user interface changes at this moment.
        //
        onLeavingState: function( stateName )
        {
            console.log( 'Leaving state: '+stateName );
            
            switch( stateName )
            {
            
            /* Example:
            
            case 'myGameState':
            
                // Hide the HTML block we are displaying only during this game state
                dojo.style( 'my_html_block_id', 'display', 'none' );
                
                break;
           */
           
           
            case 'dummmy':
                break;
            }               
        }, 

        // onUpdateActionButtons: in this method you can manage "action buttons" that are displayed in the
        //                        action status bar (ie: the HTML links in the status bar).
        //        
        onUpdateActionButtons: function( stateName, args )
        {
            console.log( 'onUpdateActionButtons: '+stateName );
                      
            if( this.isCurrentPlayerActive() )
            {            
                switch( stateName )
                {
/*               
                 Example:
 
                 case 'myGameState':
                    
                    // Add 3 action buttons in the action status bar:
                    
                    this.addActionButton( 'button_1_id', _('Button 1 label'), 'onMyMethodToCall1' ); 
                    this.addActionButton( 'button_2_id', _('Button 2 label'), 'onMyMethodToCall2' ); 
                    this.addActionButton( 'button_3_id', _('Button 3 label'), 'onMyMethodToCall3' ); 
                    break;
*/
                }
            }
        },        

        ///////////////////////////////////////////////////
        //// Utility methods
        
        /*
        
            Here, you can defines some utility methods that you can use everywhere in your javascript
            script.
        
        */


        ///////////////////////////////////////////////////
        //// Player's action
        
        /*
        
            Here, you are defining methods to handle player's action (ex: results of mouse click on 
            game objects).
            
            Most of the time, these methods:
            _ check the action is possible at this game state.
            _ make a call to the game server
        
        */
        
        /* Example:
        
        onMyMethodToCall1: function( evt )
        {
            console.log( 'onMyMethodToCall1' );
            
            // Preventing default browser reaction
            dojo.stopEvent( evt );

            // Check that this action is possible (see "possibleactions" in states.inc.php)
            if( ! this.checkAction( 'myAction' ) )
            {   return; }

            this.ajaxcall( "/unmatchedbattle/unmatchedbattle/myAction.html", { 
                                                                    lock: true, 
                                                                    myArgument1: arg1, 
                                                                    myArgument2: arg2,
                                                                    ...
                                                                 }, 
                         this, function( result ) {
                            
                            // What to do after the server call if it succeeded
                            // (most of the time: nothing)
                            
                         }, function( is_error) {

                            // What to do after the server call in anyway (success or failure)
                            // (most of the time: nothing)

                         } );        
        },        
        
        */

        
        ///////////////////////////////////////////////////
        //// Reaction to cometD notifications

        /*
            setupNotifications:
            
            In this method, you associate each of your game notifications with your local method to handle it.
            
            Note: game notification names correspond to "notifyAllPlayers" and "notifyPlayer" calls in
                  your unmatchedbattle.game.php file.
        
        */
        setupNotifications: function()
        {
            console.log( 'notifications subscriptions setup' );
            
            // TODO: here, associate your game notifications with local methods
            
            // Example 1: standard notification handling
            // dojo.subscribe( 'cardPlayed', this, "notif_cardPlayed" );
            
            // Example 2: standard notification handling + tell the user interface to wait
            //            during 3 seconds after calling the method in order to let the players
            //            see what is happening in the game.
            // dojo.subscribe( 'cardPlayed', this, "notif_cardPlayed" );
            // this.notifqueue.setSynchronous( 'cardPlayed', 3000 );
            // 
        },  
        
        // TODO: from this point and below, you can write your game notifications handling methods
        
        /*
        Example:
        
        notif_cardPlayed: function( notif )
        {
            console.log( 'notif_cardPlayed' );
            console.log( notif );
            
            // Note: notif.args contains the arguments specified during you "notifyAllPlayers" / "notifyPlayer" PHP call
            
            // TODO: play the card in the user interface.
        },    
        
        */
   });             
});
