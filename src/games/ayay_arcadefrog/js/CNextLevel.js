function CNextLevel(){
    var _oBg;
    var _oMsgText;
    var _oMsgTextBack;
    var _oScoreText;
    var _oScoreTextBack;
    var _oGroup;
    
    this._init = function(){
        _oGroup = new createjs.Container();
        _oGroup.alpha = 0;
        _oGroup.visible=false;
        s_oStage.addChild(_oGroup);
        
        _oBg = createBitmap(s_oSpriteLibrary.getSprite('msg_box'));
        _oGroup.addChild(_oBg);


        _oMsgTextBack = new CTLText(_oGroup, 
                    CANVAS_WIDTH/2-160, (CANVAS_HEIGHT/2)-90, 320, 48, 
                    48, "center", "#008733", FONT_GAME, 1.1,
                    0, 0,
                    "LEVEL 1",
                    true, true, false,
                    false );
        _oMsgTextBack.setOutline(3);            


        _oMsgText = new CTLText(_oGroup, 
                    CANVAS_WIDTH/2-160, (CANVAS_HEIGHT/2)-90, 320, 48, 
                    48, "center", "#fff", FONT_GAME, 1.1,
                    0, 0,
                    "LEVEL 1",
                    true, true, false,
                    false );
        
        _oScoreTextBack = new CTLText(_oGroup, 
                    CANVAS_WIDTH/2-160, (CANVAS_HEIGHT/2)+30, 320, 34, 
                    34, "center", "#008733", FONT_GAME, 1.1,
                    0, 0,
                    "SCORE 99999",
                    true, true, false,
                    false );
                    
        _oScoreTextBack.setOutline(3);

        _oScoreText = new CTLText(_oGroup, 
                    CANVAS_WIDTH/2-160, (CANVAS_HEIGHT/2)+30, 320, 34, 
                    34, "center", "#fff", FONT_GAME, 1.1,
                    0, 0,
                    "SCORE 99999",
                    true, true, false,
                    false );

    };
    
    this.show = function(iLevel,iScore){
        _oMsgTextBack.refreshText(TEXT_LEVEL + " "+ iLevel);
        _oMsgText.refreshText(TEXT_LEVEL + " "+ iLevel);
        
        _oScoreTextBack.refreshText(TEXT_SCORE +" "+ iScore);
        _oScoreText.refreshText(TEXT_SCORE +" "+ iScore);
        
        _oGroup.visible = true;
        
        var oParent = this;
        createjs.Tween.get(_oGroup).to({alpha:1 }, 500).call(function() {oParent._initListener();});
        
        $(s_oMain).trigger("save_score",iScore);
    };
    
    this._initListener = function(){
        _oGroup.on("mousedown",this._onExit);
        $(s_oMain).trigger("show_interlevel_ad");
    };
    
    this._onExit = function(){
        _oGroup.off("mousedown");
        _oGroup.alpha = 0;
        _oGroup.visible = false;
        s_oGame.nextLevel();
    };
    
    this._init();
}