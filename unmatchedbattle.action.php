<?php
/**
 *------
 * BGA framework: © Gregory Isabelli <gisabelli@boardgamearena.com> & Emmanuel Colin <ecolin@boardgamearena.com>
 * UnmatchedBattle implementation : © <Your name here> <Your email address here>
 *
 * This code has been produced on the BGA studio platform for use on https://boardgamearena.com.
 * See http://en.doc.boardgamearena.com/Studio for more information.
 * -----
 * 
 * unmatchedbattle.action.php
 *
 * UnmatchedBattle main action entry point
 *
 *
 * In this file, you are describing all the methods that can be called from your
 * user interface logic (javascript).
 *       
 * If you define a method "myAction" here, then you can call it from your javascript code with:
 * this.ajaxcall( "/unmatchedbattle/unmatchedbattle/myAction.html", ...)
 *
 */
  
  
  class action_unmatchedbattle extends APP_GameAction
  { 
    // Constructor: please do not modify
   	public function __default()
  	{
  	    if( self::isArg( 'notifwindow') )
  	    {
            $this->view = "common_notifwindow";
  	        $this->viewArgs['table'] = self::getArg( "table", AT_posint, true );
  	    }
  	    else
  	    {
            $this->view = "unmatchedbattle_unmatchedbattle";
            self::trace( "Complete reinitialization of board game" );
      }
  	} 
  	
  	// TODO: defines your action entry points there

    public function chooseHero()
    {
        self::setAjaxMode();     
        $hero = self::getArg( "hero", AT_alphanum, true );
        $this->game->chooseHero($hero);

        self::ajaxResponse( );        
    }

    public function sidekickPlacementDone()
    {
        self::setAjaxMode();     
        $sidekicksPlacement = self::getArg( "sidekicksPlacement", AT_json, true );

        // Sanitizes the input
        $this->validateJSonAlphaNum($sidekicksPlacement, "sidekickPlacement");

        $this->game->sidekickPlacementDone($sidekicksPlacement);

        self::ajaxResponse( );        
    }

    public function playActionManeuverDrawCard()
    {
        self::setAjaxMode();     
        $this->game->playActionManeuverDrawCard();
        self::ajaxResponse( );        
    }

    public function validateJSonAlphaNum($value, $argName = 'unknown')
    {
      if (is_array($value)) {
        foreach ($value as $key => $v) {
          $this->validateJSonAlphaNum($key, $argName);
          $this->validateJSonAlphaNum($v, $argName);
        }
        return true;
      }
      if (is_int($value)) {
        return true;
      }
      $bValid = preg_match("/^[_0-9a-zA-Z- ]*$/", $value) === 1;
      if (!$bValid) {
        throw new feException("Bad value for: $argName", true, true, FEX_bad_input_argument);
      }
      return true;
    }

    /*
    
    Example:
  	
    public function myAction()
    {
        self::setAjaxMode();     

        // Retrieve arguments
        // Note: these arguments correspond to what has been sent through the javascript "ajaxcall" method
        $arg1 = self::getArg( "myArgument1", AT_posint, true );
        $arg2 = self::getArg( "myArgument2", AT_posint, true );

        // Then, call the appropriate method in your game logic, like "playCard" or "myAction"
        $this->game->myAction( $arg1, $arg2 );

        self::ajaxResponse( );
    }
    
    */

  }
  

