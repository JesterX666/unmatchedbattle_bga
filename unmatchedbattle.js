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
    "ebg/stock",
    "ebg/expandablesection"
],
function (dojo, declare) {
    return declare("bgagame.unmatchedbattle", ebg.core.gamegui, {
        constructor: function(){
            console.log('unmatchedbattle constructor');
              
            // Here, you can init the global variables of your user interface
            // Example:
            // this.myGlobalValue = 0;

            // Dimensions of cards
            this.cardheight = 260; // Native size = 520
            this.cardwidth = 186; // Native size = 372
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

            switch(gamedatas.gamestate.name) {
               case "chooseHero":
                    this.setupChooseHero(gamedatas);
                    break;
                case "placeSidekicks":
                    this.placeSidekicksInPool(gamedatas.playerSidekicks);
                    break;
                default:
                    this.setupPlaceGame(gamedatas);
            }
            
            // Setup game notifications to handle (see "setupNotifications" method below)
            this.setupNotifications();

            this.connect(this.zoomLevel, "onchange", 'onZoomLevelChange');
            this.connect(null, "ondragstart", 'onDragStartHandler');

            dojo.query('.selectionCircle').forEach(function (selectionCircle) {
                this.connect(selectionCircle, 'onclick', 'onAreaClick');
            }, this);

            console.log( "Ending game setup" );
        },

        setupChooseHero: function (gamedatas) {
            // List of available heros
            this.availableHeros = new ebg.stock();
            this.availableHeros.create( this, $('availableHeros'), this.cardwidth, this.cardheight );
            this.availableHeros.setSelectionMode(1);
            this.availableHeros.extraClasses = "cardContains";
            
            this.connect (this.availableHeros, 'onChangeSelection', 'onChangeHeroSelection');

            // Specify that there are 4 heros per row in the CSS sprite image
            this.availableHeros.image_items_per_row = 4;
           
            var type = 0;
            // Adding all available heros to the stock, with their image
            Object.values(gamedatas.availableHeros).forEach(hero => {
                    // We find the card back image from the list of all cards
                    var card = Object.values(gamedatas.cardtypes).find(card => card['deck'] == hero && 
                                                                               card['type'] == 'back');
                    
                    if (card) {
                        // We initialise the card item type to the stock
                        this.availableHeros.addItemType(type, 0, g_gamethemeurl + 'img/Cards/' + card['image']);

                        // We add the card to the stock
                        this.availableHeros.addToStockWithId(type, hero);
                    }

                    type++;
            });        
        },

        setupPlaceGame: function (gamedatas) {
            console.log(gamedatas);
            this.playerHero = gamedatas.playerHero;
            this.initializeCardDeck(gamedatas.playerDeck);
            this.addCardsToPlayerHand(gamedatas.playerHand);
            this.placeTokens(gamedatas.tokensPlacement);
        },

        initializeCardDeck: function (cards) {
            this.playerDeck = cards;            

            // Player hand        
            this.playerHand = new ebg.stock();
            this.playerHand.create( this, $('myhand'), this.cardwidth, this.cardheight );
            this.playerHand.setSelectionMode(1);
            this.playerHand.extraClasses = "cardContains";
                        
            // Adding all cards to the stock, with their image
            Object.values(cards).forEach(card => {
                // We initialise the card item type to the stock
                this.playerHand.addItemType(card['internal_id'], 0, g_gamethemeurl + 'img/Cards/' + card['image']);
            });        

            this.gameHelper = new ebg.expandablesection();
            this.gameHelper.create(this, "gameHelper");
            //this.gameHelper.expand();       
        },
        
        addCardsToPlayerHand: function (cards) {        
            cards.forEach(card=> {
                // We add the card to the stock
                this.playerHand.addToStockWithId(card.internal_id, card.id);
            });            
        },

        createToken: function (token, area) {
            var tokenBlock =  this.format_block( 'jstpl_token', {internalId: token['token_id'], tokenType: 'token' + token['token_type']} );
            var tokenElement = dojo.place(tokenBlock, area);
            this.connect(tokenElement, 'onclick', 'onTokenClick');
        },

        moveToken: function (token, newArea) {
            debugger;

        },

        placeTokens: function (tokensPlacement) {
            console.log('New tokens placement: ' + tokensPlacement);

            Object.values(tokensPlacement).forEach(token => {
                var existingToken = document.querySelector('#' + token['token_id']);

                if (existingToken == null) {
                    var area = document.getElementById('area_' +token['area_id']);
                    this.createToken(token, area);
                }
                else {
                    if (existingToken.parentElement.id != token['area_id']) {
                        this.moveToken(existingToken, token['area_id']);
                    }
                }
            }, this);

            document.querySelectorAll('.selectionCircle > .token').forEach(token => {
                if (!tokensPlacement.find(searchToken => { return searchToken['token_id'] == token.id; })) {
                    token.remove();
                }
            });
        },

        placeSidekicksInPool: function (sidekicks) {
            this.sidekicks = sidekicks;

            Object.values(sidekicks).forEach(sidekick => {
                var sideKickPoolItem = this.format_block('jstpl_sidekickPoolItem', {'sidekickPoolItemId' : 'placement_' + sidekick['token_id']});
                var placementObj = dojo.place( sideKickPoolItem, 'sidekicksPool' );
                this.createToken(sidekick, placementObj);
            }, this);
        },

        // Find the area where the hero token is placed
        findHeroArea: function () {
            var heroPlacement = document.querySelector('#' + this.playerHero);
            return heroPlacement.parentElement;
        },

        // Will highlight all areas with same colors as the selected area
        highlightSameColor: function(area) {
            // Remove the highlights of all areas
            this.removeAreaHighlights();
            
            var colors = area.getAttribute('data-colors');

            colors.split(',').forEach(color => {
                // Highlight all areas with same color that doesn't have a token
                var areas = document.querySelectorAll('[data-colors*="' + color + '"]');

                areas.forEach(area => {
                    if (area.querySelector('.token') == null) {
                        area.classList.add('selectionCircleSelected');
                    }
                });
            });
        },

        // Will highlight all areas immediatly adjacent to the selected area
        highlightAdjacentAreas: function(area, distance, tokensToIgnore) {
            debugger;

            // Remove the highlights of all areas
            this.removeAreaHighlights();
            
            var areas = this.getAdjacentAreas(area, null, 1, distance, tokensToIgnore);

            areas.forEach(area => {
                area.classList.add('selectionCircleSelected');
            });
        },

        // Recursive function that gathers all areas that are adjacent to the selected area up to a certain distance
        getAdjacentAreas: function(startingArea, areasFound, currentDistance, maxDistance, tokenClassesToIgnore) {
            if (areasFound == null) {
                areasFound = [];
            }

            var exits = startingArea.getAttribute('data-exits');

            exits.split(',').forEach(exit => {
                // Highlight all exits area that doesn't have a token in the ignore list
                var area = document.querySelector('#area_' + exit);

                var token = area.querySelector('.token');
                if ((token == null) || (tokenClassesToIgnore == null) || (tokenClassesToIgnore.indexOf(token.className) == -1)) {
                    if (areasFound.indexOf(area) == -1) {
                        areasFound.push(area);
                    }
                }

                if (currentDistance < maxDistance) {
                    areasFound = this.getAdjacentAreas(area, areasFound, currentDistance + 1, maxDistance, tokenClassesToIgnore);
                }
            });

            return areasFound;
        },

        // Will remove the highlights of all areas
        removeAreaHighlights:function () {
            var areas = document.getElementsByClassName('selectionCircle');
            for (var i = 0; i < areas.length; i++) {
                areas[i].classList.remove('selectionCircleSelected');
            }
        },

        getScaleTransformForToken(scaleSlider){
            return 'scale(' + (scaleSlider.value) / 100 + ')';
        },

        resetActionButtonsAction: function () {
            this.removeActionButtons();

            if (this.isCurrentPlayerActive()) {
                this.addActionButton( 'playActionManeuver', _('Maneuver'), 'onPlayActionManeuver' ); 
                this.addActionButton( 'playActionScheme', _('Scheme'), 'onPlayActionScheme' ); 
                this.addActionButton( 'playActionAttack', _('Attack'), 'onPlayActionAttack' ); 
            }
        },

        onChangeHeroSelection: function (selection) {
            var items = this.availableHeros.getSelectedItems();
            if ((items.length == 1) && this.isCurrentPlayerActive()) {
                if (!document.getElementById('heroSelectionConfirm')) {
                    this.addActionButton( 'heroSelectionConfirm', _('Confirm'), 'onHeroSelect' ); 
                }
            }
            else {
                    this.removeActionButtons();
            }            
        },       

        onHeroSelect: function () {
            if (this.checkAction('chooseHero')) {
                var items = this.availableHeros.getSelectedItems();
                var hero = items[0]['id'];
                this.ajaxcall( '/unmatchedbattle/unmatchedbattle/chooseHero.html', 
                               { 'lock': true, 'hero': hero }, this, 'onHeroSelectResponse');

            }
        },
        
        onSidekickPlacementDone: function () {
            if (this.checkAction('placeSidekicks')) {
                var sidekicksPlacement = [];

                Object.values(this.sidekicks).forEach(sidekick => {
                    var sidekickPlacement = document.getElementById(sidekick['token_id']);
                    var sidekickPlacementItem = { 'sidekick': sidekick['token_id'], 'area_id': sidekickPlacement.parentElement.id.split('_')[1] };
                    sidekicksPlacement.push(sidekickPlacementItem);
                });

                this.ajaxcall( '/unmatchedbattle/unmatchedbattle/sidekickPlacementDone.html', 
                               { 'lock': true, 'sidekicksPlacement': JSON.stringify(sidekicksPlacement) }, this, 'onSidekickPlacementResponse');
            }
        },

        onHeroSelectResponse: function (data) {
            this.availableHeros.removeAll();
            this.removeActionButtons();
        },

        onSidekickPlacementResponse: function (data) {        
            document.getElementById('sidekicks').style.display = 'none';
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
                    document.getElementById('availableHeros').style.display = 'block';
                    document.getElementById('mainGame').style.display = 'none';
                    break;
                case "placeSidekicks":
                    document.getElementById('availableHeros').style.display = 'none';
                    document.getElementById('mainGame').style.display = 'block';
                    break;
                case "playActionManeuver":
                    if (this.isCurrentPlayerActive()) {
                        this.addActionButton( 'playBoostCard', _('Play Boost Card'), 'onPlayBoostCard' ); 
                        this.addActionButton( 'skipBoostCard', _('Skip'), 'onSkipBoostCard' ); 
                    }
                    break;   
                case "playActionMove":
                    if (this.isCurrentPlayerActive()) {
                        this.addActionButton( 'endManeuver', _('End Maneuver Action'), 'onEndManeuver' ); 
                    }
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
                    case 'playAction':
                        this.resetActionButtonsAction();
                        break;
        
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

        onZoomLevelChange: function(event) {
            console.log(event.target.value);
            document.getElementById('mapImage').style.transform = this.getScaleTransformForToken(event.target);
        },

        onTokenSelected: function (event) {
            dojo.toggleClass(event.target, 'tokenSelected');
        },

        onTokenClick: function (event) {
            if (!this.isCurrentPlayerActive())
                return;

            var selected = event.target.classList.contains('tokenSelected');

            document.querySelectorAll('.tokenSelected').forEach(token => {
                token.classList.remove('tokenSelected');
            });

            if (!selected)
                event.target.classList.add('tokenSelected');
            
            if (event.target.classList.contains('tokenSelected')) {
                this.highlightSameColor(this.findHeroArea());
            }
            else {
                this.removeAreaHighlights();
            }
            
            event.cancelBubble = true;
        },

        onAreaClick: function (event) {
            var selectedToken = dojo.query('.tokenSelected');

            if (selectedToken.length < 1)
                return;

            if (!event.target.classList.contains('selectionCircleSelected')) {
                this.showMessage( "Invalid Placement", "error" );
                return;
            }

            //var transform = this.getScaleTransformForToken(dojo.query('#zoomLevel')[0]);

            // var animation = new dojo.Animation({
            //     onAnimate: function (v) {
            //         selectedToken.style[transform] = transform;
            //     }
            // }).play();

            // if (selectedToken.length == 1) {
            //     var anim = this.slideToObject( selectedToken[0], event.target);
                
            //     anim.play();
            // }

            dojo.place(selectedToken[0], event.target);
            selectedToken.removeClass('tokenSelected');
            this.removeAreaHighlights();
            
            if (document.querySelectorAll(".sidekickPoolItem > .token").length == 0) {
                var sidekicksArea = document.getElementById('sidekicks');
                
                if (sidekicksArea != undefined) {
                    sidekicksArea.style.display = "none";
                }

                if (!document.getElementById('sidekickPlacementConfirm')) {
                    this.addActionButton( 'sidekickPlacementConfirm', _('Confirm'), 'onSidekickPlacementDone' ); 
                }
            }
        },

        onDragStartHandler: function (event) {
            event.dataTransfer.setData("text/plain", event.target.id);
        },

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
        onPlayActionManeuver: function(evt) {
            if (this.checkAction('playAction')) {
                this.ajaxcall( '/unmatchedbattle/unmatchedbattle/playActionManeuverDrawCard.html', 
                    { 'lock': true }, this, function(result) {} );
            }
        },

        onPlayActionScheme:  function(evt) {
            if (this.checkAction('playAction')) {

            }
        },

        onPlayActionAttack: function(evt) {
            if (this.checkAction('playAction')) {
                
            }
        },

        onPlayBoostCard: function(evt) {
            if (this.checkAction('playBoostCard')) {
                if (this.boostCardPlayed != null) {
                    this.showMessage( "You can only play one boost card per turn", "error" );                
                    return;
                }

                var cardsPlayed = this.playerHand.getSelectedItems();

                if (cardsPlayed.length != 1) {
                    this.showMessage( "You must select a boost card to play", "error" );
                    return;
                }

                var cardDefinition = Object.values(this.playerDeck).find(card => 
                {  
                    return card.internal_id == cardsPlayed[0].type;
                });

                if (cardDefinition == null) {
                    this.showMessage( "Invalid card", "error" );
                    return;
                }

                this.playerHand.removeFromStockById(cardsPlayed[0]['id']);

                this.ajaxcall( '/unmatchedbattle/unmatchedbattle/playBoostCard.html',
                    { 'lock': true, 'boostCard': cardsPlayed[0]['id'] }, this, 'onPlayBoostCardDone' );
            }
        },

        onEndManeuver: function(evt) {
        },

        onSkipBoostCard: function(evt) {
            if (this.checkAction('skipBoostCard')) {
                this.ajaxcall( '/unmatchedbattle/unmatchedbattle/playBoostCard.html',
                    { 'lock': true, 'boostCard': 0 }, this, 'onPlayBoostCardDone' );
            }
        },

        onPlayBoostCardDone: function(evt) {
            // Nothing to do here
        },

        onCancel: function(evt) {
        },

        onMoveDone: function(evt) {
        },

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

            dojo.subscribe( 'heroSelected', this, "notif_heroSelected" );
            dojo.subscribe( 'placeTokens', this, "notif_placeTokens" );
            dojo.subscribe( 'receiveSidekicks', this, "notif_receiveSidekicks" );
            dojo.subscribe( 'receiveCards', this, "notif_receiveCards" );

            this.notifqueue.setIgnoreNotificationCheck( 'performManeuver', (notif) => (notif.args.player_id == this.player_id) );
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

        notif_heroSelected: function( notif ) 
        {
            console.log( 'notif_heroSelected' );
            console.log( notif );

            if (notif.args.player_id == this.player_id) {
                this.initializeCardDeck(notif.args.deck);
            }
            else {
                // Remove the selected hero from the available heroes list
                this.availableHeros.removeFromStockById( notif.args.hero );
            }
        },

        notif_placeTokens: function( notif )
        {
            console.log( 'notif_placeTokens' );
            console.log( notif.args.tokensPlacement );

            this.placeTokens(notif.args.tokensPlacement);
        },

        notif_receiveSidekicks: function( notif )
        {
            this.playerHero = notif.args.playerHero;
            this.placeSidekicksInPool(notif.args.sidekicks);
        },
                
        notif_receiveCards: function( notif )
        {
            console.log( 'notif_receiveCards' );
            console.log( notif );

            this.addCardsToPlayerHand(Object.values(notif.args.cards));
        }
   });             
});
