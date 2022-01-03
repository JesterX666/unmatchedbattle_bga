{OVERALL_GAME_HEADER}

<!-- 
--------
-- BGA framework: © Gregory Isabelli <gisabelli@boardgamearena.com> & Emmanuel Colin <ecolin@boardgamearena.com>
-- UnmatchedBattle implementation : © <Your name here> <Your email address here>
-- 
-- This code has been produced on the BGA studio platform for use on http://boardgamearena.com.
-- See http://en.boardgamearena.com/#!doc/Studio for more information.
-------

    unmatchedbattle_unmatchedbattle.tpl
    
    This is the HTML template of your game.
    
    Everything you are writing in this file will be displayed in the HTML page of your game user interface,
    in the "main game zone" of the screen.
    
    You can use in this template:
    _ variables, with the format {MY_VARIABLE_ELEMENT}.
    _ HTML block, with the BEGIN/END format
    
    See your "view" PHP file to check how to set variables and control blocks
    
    Please REMOVE this comment before publishing your game on BGA
-->
<i class="fas fa-heart"></i>
<i class="fas fa-hand-paper"></i>


<!-- BEGIN pickup hero -->
    <div id="availableHeros"></div>
<!-- END pickup hero -->

<!-- BEGIN main game -->
    <div id="mainGame">
        <!-- BEGIN player -->
	        <div id="myhand_wrap" class="whiteblock">
	        	<div id="myhand"></div>
    	    </div>
        <!-- END player -->

        <!-- BEGIN sidekicks -->
            <div id="sidekicks" class="whiteblock">
                <h3>Sidekicks</h3>
                <!-- BEGIN sidekicks -->
                <div id="sidekicksPool" class="sidekicksPool">
                </div>
                <!-- END sidekicks -->
            </div>
        <!-- END sidekicks -->

        <!== BEGIN zoom -->
        <input type="range" id="zoomLevel" min="=100" max="100">
        <!== END zoom -->

        <!-- BEGIN map -->        
            <div class="mapContainer">
                <div id="mapImage" class="map{MAP_NAME}">     
                    <!-- BEGIN area -->           
                        <div id="area_{AREA_ID}" class="selectionCircle" style="left: {LEFT}; top: {TOP}; " data-colors="{COLORS}" data-exits="{EXITS}">
                        </div>
                    <!-- END area -->
                </div>
            </div>
        <!-- END map -->
    </div>
<!-- END main game -->

<script type="text/javascript">

// Javascript HTML templates

var jstpl_sidekickPoolItem = '<div id="${sidekickPoolItemId}" class="sidekickPoolItem"></div>';
var jstpl_token = '<div id="${internalId}" class="token ${tokenType}" draggable="true" data-team="${team}"></div>';

var jstpl_player_board = '<div>${blockFighters}<div class="aliceSize ${aliceSize}"></div></div>';
var jstpl_player_board_fighter = '<div>${fighterName}<span id="health" class="fighterHealth">${fighterHealth}</span></div>';

/*
// Example:
var jstpl_some_game_item='<div class="my_game_item" id="my_game_item_${MY_ITEM_ID}"></div>';

*/

</script>  

{OVERALL_GAME_FOOTER}
