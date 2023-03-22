function CDaisy(iX,iY,oParentContainer){
    var _oSprite;
    var _oParentContainer;
    
    this._init = function(iX,iY){
        var oSprite = s_oSpriteLibrary.getSprite('daisy_spritesheet');
        var oData = {   
                        framerate:15,
                        images: [oSprite], 
                        // width, height & registration point of each sprite
                        frames: {width: DAISY_WIDTH, height: DAISY_HEIGHT,regX: DAISY_WIDTH/2,regY:DAISY_HEIGHT/2}, 
                        animations: {start:[0],idle:[20],anim:[0,20,"idle"]}
                    };
        
        var oSpriteSheet = new createjs.SpriteSheet(oData);
        _oSprite = createSprite(oSpriteSheet,"start",DAISY_WIDTH/2,DAISY_HEIGHT/2,DAISY_WIDTH,DAISY_HEIGHT);
        _oSprite.stop();
        _oSprite.visible = false;
        _oSprite.x = iX;
        _oSprite.y = iY;
        _oParentContainer.addChild(_oSprite);
    };
    
    this.show = function(iDelay){
        setTimeout(function(){_oSprite.visible = true;
                              _oSprite.gotoAndPlay("anim");
        },iDelay);
    };
    
    this.unload = function(){
        _oParentContainer.removeChild(_oSprite);
    };
    
    _oParentContainer = oParentContainer;
    this._init(iX,iY);
}