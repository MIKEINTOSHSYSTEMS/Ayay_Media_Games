function CMenu(){
    var _pStartPosAudio;
    var _pStartPosCredits;
    var _pStartPosFullscreen;
	
    var _oBg;
    var _oButPlay;
    var _oButContinue = null;
    var _oAudioToggle;
    var _oButCredits;
    var _oFade;
    var _oButFullscreen;
    var _fRequestFullScreen = null;
    var _fCancelFullScreen = null;
    
    this._init = function(){
        _oBg = createBitmap(s_oSpriteLibrary.getSprite('bg_menu'));
	s_oStage.addChild(_oBg);
        
        if(s_bStorageAvailable){
            var szFlag = getItem("frogtastic_level");
            if(szFlag !== null && szFlag !== undefined){
                s_iLastLevel = parseInt(getItem("frogtastic_level"));
            }else{
                saveItem("frogtastic_level", 1);
            }
        }

        if(s_iLastLevel === 1){
            _oButPlay = new CGfxButton((CANVAS_WIDTH/2),CANVAS_HEIGHT -120,s_oSpriteLibrary.getSprite('but_play'),s_oStage);
            _oButPlay.addEventListener(ON_MOUSE_UP, this._onButPlayRelease, this);
        }else{
            _oButPlay = new CGfxButton((CANVAS_WIDTH/2) - 150,CANVAS_HEIGHT -120,s_oSpriteLibrary.getSprite('but_play'),s_oStage);
            _oButPlay.addEventListener(ON_MOUSE_UP, this._onButPlayRelease, this);
            
            _oButContinue = new CGfxButton((CANVAS_WIDTH/2) + 150,CANVAS_HEIGHT -120,s_oSpriteLibrary.getSprite('but_continue'),s_oStage);
            _oButContinue.addEventListener(ON_MOUSE_UP, this._onButContinueRelease, this);
        }
        if(DISABLE_SOUND_MOBILE === false || s_bMobile === false){
            var oSprite = s_oSpriteLibrary.getSprite('audio_icon');
            _pStartPosAudio = {x: CANVAS_WIDTH - (oSprite.height/2) - 10, y: (oSprite.height/2) + 10};      
            _oAudioToggle = new CToggle(_pStartPosAudio.x,_pStartPosAudio.y,oSprite,s_bAudioActive);
            _oAudioToggle.addEventListener(ON_MOUSE_UP, this._onAudioToggle, this);
        }
		
        var oSprite = s_oSpriteLibrary.getSprite('but_credits');
        _pStartPosCredits = {x:(oSprite.height/2) + 10,y:(oSprite.height/2) + 10};
        _oButCredits = new CGfxButton(_pStartPosCredits.x,_pStartPosCredits.y,oSprite,s_oStage);
        _oButCredits.addEventListener(ON_MOUSE_UP, this._onButCreditsRelease, this);
	
        var doc = window.document;
        var docEl = doc.documentElement;
        _fRequestFullScreen = docEl.requestFullscreen || docEl.mozRequestFullScreen || docEl.webkitRequestFullScreen || docEl.msRequestFullscreen;
        _fCancelFullScreen = doc.exitFullscreen || doc.mozCancelFullScreen || doc.webkitExitFullscreen || doc.msExitFullscreen;
        
        if(ENABLE_FULLSCREEN === false){
            _fRequestFullScreen = false;
        }
        
        if (_fRequestFullScreen && screenfull.isEnabled){
            oSprite = s_oSpriteLibrary.getSprite('but_fullscreen');
            _pStartPosFullscreen = {x: _pStartPosCredits.x + oSprite.width/2 + 10,y:_pStartPosCredits.y-2};

            _oButFullscreen = new CToggle(_pStartPosFullscreen.x,_pStartPosFullscreen.y,oSprite,s_bFullscreen,s_oStage);
            _oButFullscreen.addEventListener(ON_MOUSE_UP, this._onFullscreenRelease, this);
        }
        
        _oFade = new createjs.Shape();
        _oFade.graphics.beginFill("black").drawRect(0,0,CANVAS_WIDTH,CANVAS_HEIGHT);
        
        s_oStage.addChild(_oFade);
        
        if(!s_bStorageAvailable){
            new CAlertSavingBox(TEXT_ERR_LS,s_oStage);
        }
        
        createjs.Tween.get(_oFade).to({alpha:0}, 400).call(function(){_oFade.visible = false;}); 

	this.refreshButtonPos(s_iOffsetX,s_iOffsetY);		
    };
    
    this.unload = function(){
        _oButPlay.unload(); 
        _oButPlay = null;
        _oButCredits.unload();
        
        if(_oButContinue !== null){
            _oButContinue.unload();
        }
        
        if(DISABLE_SOUND_MOBILE === false || s_bMobile === false){
            _oAudioToggle.unload();
            _oAudioToggle = null;
        }
        
        if (_fRequestFullScreen && screenfull.isEnabled){
            _oButFullscreen.unload();
        }
        
        s_oStage.removeChild(_oBg);
        _oBg = null;
        
        s_oStage.removeChild(_oFade);
        _oFade = null;
	s_oMenu = null;
    };
	
    this.refreshButtonPos = function(iNewX,iNewY){
        if(DISABLE_SOUND_MOBILE === false || s_bMobile === false){
                _oAudioToggle.setPosition(_pStartPosAudio.x - iNewX,iNewY + _pStartPosAudio.y);
        }
        if (_fRequestFullScreen && screenfull.isEnabled){
            _oButFullscreen.setPosition(_pStartPosFullscreen.x + iNewX,_pStartPosFullscreen.y + iNewY);
        }
	_oButCredits.setPosition(_pStartPosCredits.x + iNewX,_pStartPosCredits.y + iNewY);
    };
    
    this._exitFromMenu = function(){
        this.unload();
        s_oMain.gotoLevelMenu();
        $(s_oMain).trigger("start_session");
    };
    
    this._onButPlayRelease = function(){
        if(s_iLastLevel > 1){
            var oMsgBox = new CMsgBox(TEXT_DELETE_SAVINGS,TEXT_NO,"",TEXT_YES);
            oMsgBox.addEventListener(ON_MSG_BOX_LEFT_BUT,function(){oMsgBox.hide();}, this);
            oMsgBox.addEventListener(ON_MSG_BOX_RIGHT_BUT,function(){oMsgBox.hide();s_oMain.clearLocalStorage();s_oMenu._exitFromMenu();}, this);
        }else{
            s_oMenu._exitFromMenu();
        }
    };
    
    this._onButContinueRelease = function(){

        s_oMenu._exitFromMenu();
    };
	
    this._onButCreditsRelease = function(){
        new CCreditsPanel();
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
    
    s_oMenu = this;
	
    this._init();
}

var s_oMenu = null;