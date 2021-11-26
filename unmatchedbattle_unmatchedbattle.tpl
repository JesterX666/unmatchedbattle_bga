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

<!-- BEGIN pickup hero -->
    <div id="availableHeros"></div>
<!-- END pickup hero -->

<!-- BEGIN main game -->
    <div id="mainGame">
        <!-- BEGIN player -->
	        <div id="myhand_wrap" class="whiteblock">
        		<h3>{MY_HAND}</h3>
	        	<div id="myhand"></div>
    	    </div>

            <div id="playerHelper" style="border: 1px solid black">
                <a href="#" id="playerHelperToggle" class="expandabletoggle expandablearrow">
                    <div class="icon20"></div>
                </a>
                <div id="my_hidden_content" class="expandablecontent">
                    Here's the hidden content
                </div>
            </div>
        <!-- END player -->

        <!-- BEGIN map -->
            <div class="mapProxy">
            </div>
        <!-- END map -->
    </div>
<!-- END main game -->

<script type="text/javascript">

// Javascript HTML templates

/*
// Example:
var jstpl_some_game_item='<div class="my_game_item" id="my_game_item_${MY_ITEM_ID}"></div>';

*/

</script>  

{OVERALL_GAME_FOOTER}
