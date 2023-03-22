function CBee(aTraj,oParentContainer){
    var _iWidth;
    var _iHeight;
    var _oTrajectory;
    var _oSprite;
    var _oParentContainer;
    
    this._init = function(aTraj){
        _oTrajectory = aTraj;
        
        var oSprite = s_oSpriteLibrary.getSprite('bee');
        _iWidth = oSprite.width/2;
        _iHeight = oSprite.height;
        
        var oData = {   
                        framerate:15,
                        images: [oSprite], 
                        // width, height & registration point of each sprite
                        frames: {width: _iWidth, height: _iHeight,regX: _iWidth/2,regY:_iHeight/2}, 
                        animations: {idle:[0,1]}
                    };
        
        var oSpriteSheet = new createjs.SpriteSheet(oData);
        
        _oSprite = createSprite(oSpriteSheet,"idle",_iWidth/2,_iHeight/2,_iWidth,_iHeight);
        _oParentContainer.addChild(_oSprite);
        
        if(_oTrajectory.start > _oTrajectory.end){
            _oSprite.scaleX *= -1;
        }
        
        _oSprite.rotation = _oTrajectory.start_rot;
        var oParent = this;
        createjs.Tween.get(_oSprite).to({guide:{ path:_oTrajectory.path ,start:_oTrajectory.start,end:_oTrajectory.end},rotation:_oTrajectory.final_rot},_oTrajectory.time).call(
                    function(){oParent.unload();}
                );
    };
    
    this.unload = function(){
        _oParentContainer.removeChild(_oSprite);
    };
    
    _oParentContainer = oParentContainer;
    this._init(aTraj);
}