function CEndPanel(oSpriteBg){
    
    var _oBg;
    var _oScoreTextOutline;
    var _oScoreText;
    var _oMsgTextOutline;
    var _oMsgText;
    var _oGroup;
    
    this._init = function(oSpriteBg){
        _oGroup = new createjs.Container();
        _oGroup.alpha = 0;
        _oGroup.visible=false;
        s_oStage.addChild(_oGroup);
        
        _oBg = createBitmap(oSpriteBg);
        _oGroup.addChild(_oBg);
        
        _oMsgTextOutline = new CTLText(_oGroup, 
                    CANVAS_WIDTH/2-160, (CANVAS_HEIGHT/2)-90, 320, 32, 
                    32, "center", "#008733", FONT_GAME, 1.1,
                    0, 0,
                    TEXT_CONGRATS,
                    true, true, false,
                    false );
        _oMsgTextOutline.setOutline(3);            
        
        
        _oMsgText = new CTLText(_oGroup, 
                    CANVAS_WIDTH/2-160, (CANVAS_HEIGHT/2)-90, 320, 32, 
                    32, "center", "#ffffff", FONT_GAME, 1.1,
                    0, 0,
                    TEXT_CONGRATS,
                    true, true, false,
                    false );
                    
       
        
        _oScoreTextOutline =  new CTLText(_oGroup, 
                    CANVAS_WIDTH/2-160, (CANVAS_HEIGHT/2), 320, 52, 
                    26, "center", "#008733", FONT_GAME, 1.1,
                    0, 0,
                    TEXT_FINAL_SCORE + "\n99999",
                    true, true, true,
                    false );
                    
        _oScoreTextOutline.setOutline(3);
        
                
        _oScoreText =new CTLText(_oGroup, 
                    CANVAS_WIDTH/2-160, (CANVAS_HEIGHT/2), 320, 52, 
                    26, "center", "#fff", FONT_GAME, 1.1,
                    0, 0,
                    TEXT_FINAL_SCORE + "\n99999",
                    true, true, true,
                    false );
        
    };
    
    this.unload = function(){
        _oGroup.off("mousedown",this._onExit);
        s_oStage.removeChild(_oGroup);
    };
    
    this._initListener = function(){
        _oGroup.on("mousedown",this._onExit);
        $(s_oMain).trigger("show_interlevel_ad");
    };
    
    this.show = function(iScore,bWin){
        if(bWin){
            _oMsgTextOutline.refreshText(TEXT_CONGRATS);
            _oMsgText.refreshText(TEXT_CONGRATS);
        }else{
            _oMsgTextOutline.refreshText(TEXT_GAMEOVER);
            _oMsgText.refreshText(TEXT_GAMEOVER);
        }
        _oScoreTextOutline.refreshText(TEXT_FINAL_SCORE+"\n"+iScore);
        _oScoreText.refreshText(TEXT_FINAL_SCORE+"\n"+iScore);
        _oGroup.visible = true;
        
        var oParent = this;
        createjs.Tween.get(_oGroup).to({alpha:1 }, 500).call(function() {oParent._initListener();});
        
        $(s_oMain).trigger("save_score",iScore);
    };
    
    this._onExit = function(){
        _oGroup.off("mousedown");
        s_oGame.onExit();
    };
    
    this._init(oSpriteBg);
    
    return this;
}