function CInterface(iScore){
    var _pStartPosAudio;
    var _pStartPosExit;
    var _pStartPosText;
    var _pStartPosFullscreen;
	
    var _oLevelText;
    var _oLevelTextOutline;
    var _oScoreText;
    var _oScoreTextOutline;
    var _oButExit;
    var _oHitArea;
    var _oAudioToggle;
    var _oEndPanel;
    var _oNextLevelPanel;
    var _oContainerText;
    var _oButFullscreen;
    var _fRequestFullScreen = null;
    var _fCancelFullScreen = null;
    
    this._init = function(iScore){
        _pStartPosText = {x:10,y:10};
        
        _oContainerText = new createjs.Container();
        _oContainerText.x = _pStartPosText.x;
        _oContainerText.y = _pStartPosText.y;
        s_oStage.addChild(_oContainerText);
        
        _oScoreTextOutline = new CTLText(_oContainerText, 
                    0, 0, 320, 32, 
                    26, "left", "#008733", FONT_GAME, 1.1,
                    0, 0,
                    TEXT_SCORE +" "+iScore,
                    true, true, false,
                    false );
        _oScoreTextOutline.setOutline(3);
        
        _oScoreText = new CTLText(_oContainerText, 
                    0, 0, 320, 32, 
                    26, "left", "#fff", FONT_GAME, 1.1,
                    0, 0,
                    TEXT_SCORE +" "+iScore,
                    true, true, false,
                    false );
        

        _oLevelTextOutline = new CTLText(_oContainerText, 
                    0, 40, 320, 32, 
                    22, "left", "#008733", FONT_GAME, 1.1,
                    0, 0,
                    TEXT_LEVEL +" "+s_iLastLevel,
                    true, true, false,
                    false );
        _oLevelTextOutline.setOutline(3);            
        

        _oLevelText = new CTLText(_oContainerText, 
                    0, 40, 320, 32, 
                    22, "left", "#fff", FONT_GAME, 1.1,
                    0, 0,
                    TEXT_LEVEL +" "+s_iLastLevel,
                    true, true, false,
                    false );

        var oParent = this;
	_oHitArea = createBitmap(s_oSpriteLibrary.getSprite('hit_area'));
        s_oStage.addChild(_oHitArea);
	_oHitArea.on("pressup",function(evt){oParent._onTapScreen(evt.stageX,evt.stageY)}); 

        var oSprite = s_oSpriteLibrary.getSprite('but_exit');
	_pStartPosExit = {x:CANVAS_WIDTH - (oSprite.width/2) ,y:(oSprite.height/2) +4};
        _oButExit = new CGfxButton(_pStartPosExit.x,_pStartPosExit.y,oSprite,s_oStage);
        _oButExit.addEventListener(ON_MOUSE_UP, this._onExit, this);

        if(DISABLE_SOUND_MOBILE === false || s_bMobile === false){
            _pStartPosAudio = {x:_pStartPosExit.x - oSprite.width,y:(oSprite.height/2) + 4}
            var oSprite = s_oSpriteLibrary.getSprite('audio_icon');
            _oAudioToggle = new CToggle(_pStartPosAudio.x,_pStartPosAudio.y,oSprite,s_bAudioActive);
            _oAudioToggle.addEventListener(ON_MOUSE_UP, this._onAudioToggle, this);
            
            _pStartPosFullscreen = {x: _pStartPosAudio.x - oSprite.width/2,y:_pStartPosAudio.y};
        }else{
            _pStartPosFullscreen = {x:_oButExit.getX() - oSprite.width,y:(oSprite.height/2) + 4}
        }

        var doc = window.document;
        var docEl = doc.documentElement;
        _fRequestFullScreen = docEl.requestFullscreen || docEl.mozRequestFullScreen || docEl.webkitRequestFullScreen || docEl.msRequestFullscreen;
        _fCancelFullScreen = doc.exitFullscreen || doc.mozCancelFullScreen || doc.webkitExitFullscreen || doc.msExitFullscreen;
        
        if(ENABLE_FULLSCREEN === false){
            _fRequestFullScreen = false;
        }
        
        if (_fRequestFullScreen && screenfull.isEnabled){
            oSprite = s_oSpriteLibrary.getSprite('but_fullscreen');
            

            _oButFullscreen = new CToggle(_pStartPosFullscreen.x,_pStartPosFullscreen.y,oSprite,s_bFullscreen,s_oStage);
            _oButFullscreen.addEventListener(ON_MOUSE_UP, this._onFullscreenRelease, this);
        }
        
        _oNextLevelPanel = new CNextLevel();
        _oEndPanel = new CEndPanel(s_oSpriteLibrary.getSprite('msg_box'));

	this.refreshButtonPos(s_iOffsetX,s_iOffsetY);		
    };
    
    this.unload = function(){
        _oButExit.unload();
        _oButExit = null;

        if(DISABLE_SOUND_MOBILE === false){
            _oAudioToggle.unload();
            _oAudioToggle = null;
        }
        
        if (_fRequestFullScreen && screenfull.isEnabled){
            _oButFullscreen.unload();
        }
        
        s_oStage.removeAllChildren();
	s_oInterface = null;
    };
	
    this.refreshButtonPos = function(iNewX,iNewY){
        _oContainerText.x = _pStartPosText.x + iNewX;
        _oContainerText.y = _pStartPosText.y + iNewY;

        
        _oButExit.setPosition(_pStartPosExit.x - iNewX,_pStartPosExit.y + iNewY);
        if(DISABLE_SOUND_MOBILE === false || s_bMobile === false){
                _oAudioToggle.setPosition(_pStartPosAudio.x - iNewX,iNewY + _pStartPosAudio.y);
        }
        if (_fRequestFullScreen && screenfull.isEnabled){
            _oButFullscreen.setPosition(_pStartPosFullscreen.x - iNewX,_pStartPosFullscreen.y + iNewY);
        }
    };
    
    this._onTapScreen = function(iX,iY){
        s_oGame.onShot(iX,iY);
    };
    
    this.gameOver = function(iScore){
        _oEndPanel.show(iScore,false);
    };
    
    this.win = function(iScore){
        _oEndPanel.show(iScore,true);
    };
    
    this.nextLevel = function(iLevel,iScore){
        _oNextLevelPanel.show(iLevel,iScore);
    };
    
    this.refreshScore = function(iScore){
        _oScoreText.refreshText(TEXT_SCORE +" "+iScore);
        _oScoreTextOutline.refreshText(TEXT_SCORE +" "+iScore);
    };
    
    this.refreshLevel = function(iLevel){
        _oLevelText.refreshText(TEXT_LEVEL + " " + iLevel);
        _oLevelTextOutline.refreshText(TEXT_LEVEL + " " + iLevel);
        
    };
    
    this._onExit = function(){
        s_oGame.onExit();  
    };
    
    this._onAudioToggle = function(){
        Howler.mute(s_bAudioActive);
	s_bAudioActive = !s_bAudioActive;
    };
    
    this.resetFullscreenBut = function(){
	if (_fRequestFullScreen && screenfull.isEnabled){
		_oButFullscreen.setActive(s_bFullscreen);
	}
    };

    this._onFullscreenRelease = function(){
        if(s_bFullscreen) { 
		_fCancelFullScreen.call(window.document);
	}else{
		_fRequestFullScreen.call(window.document.documentElement);
	}
	
	sizeHandler();
    };
    
    s_oInterface = this;
    
    this._init(iScore);
    
    return this;
}

var s_oInterface = null;