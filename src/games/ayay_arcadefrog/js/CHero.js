function CHero(){
    var _bCanShoot = false;
    var _iBallColors;
    var _aColorsAvailable;
    var _oCurBall;
    var _oNextBall;
    var _oSpriteBg;
    var _oBallContainer;
    var _oSpriteContainer;
    var _oContainer;
    
    this._init = function(){
        var oSprite = s_oSpriteLibrary.getSprite('hero');
        var oData = {   
                        images: [oSprite], 
                        // width, height & registration point of each sprite
                        frames: {width: HERO_WIDTH, height: HERO_HEIGHT}, 
                        animations: {idle:[0],shot:[0,11,"idle"]}
                    };
        
        var oSpriteSheet = new createjs.SpriteSheet(oData);
        
        _oContainer = new createjs.Container();
        _oContainer.regX = HERO_WIDTH/2;
        _oContainer.regY = HERO_HEIGHT/2;
        s_oStage.addChild(_oContainer);
        
        _oBallContainer = new createjs.Container();
        _oContainer.addChild(_oBallContainer);
        
        _oSpriteContainer = new createjs.Container();
        _oContainer.addChild(_oSpriteContainer);
        
        _oSpriteBg = createSprite(oSpriteSheet,"idle",0,0,HERO_WIDTH, HERO_HEIGHT);
        _oSpriteContainer.addChild(_oSpriteBg);

    };
    
    this.reset = function(oPos,iBallColors){
        _bCanShoot = false;
        _iBallColors = iBallColors;
        if(_oCurBall !== undefined && _oCurBall !== null){
            _oCurBall.unload();
        }
        
        if(_oNextBall !== undefined && _oNextBall !== null){
            _oNextBall.unload();
        }

        _oContainer.x = oPos.x;
        _oContainer.y = oPos.y;
        
        _aColorsAvailable = new Array();
        for(var i=0;i<_iBallColors;i++){
            _aColorsAvailable[i] = true;
        }
    };
    
    this.unload = function(){
        
    };
    
    this.rotate = function(iRot){
        _oContainer.rotation = iRot;
    };
    
    this.start = function(){
        _oCurBall = this._getRandomBall();
        _oCurBall.changePos(HERO_WIDTH/2, (HERO_HEIGHT/2) + 36);
        
        _oNextBall = this._getRandomBall();
        _oNextBall.changePos(HERO_WIDTH/2,(HERO_HEIGHT/2) - 12);

        var oParent = this;
        createjs.Tween.get(_oCurBall.getSprite()).to({y:_oCurBall.getY()+25}, 300).call(function(){oParent._onBallReady()}); 
        createjs.Tween.get(_oNextBall.getSprite()).to({y:_oNextBall.getY()+16}, 300); 
    };
    
    this._getRandomBall = function(){
        var oBall;
        
        if(this._checkIfAllColorsNotAvailable() === true){
            return null;
        }
        
        do{
            var iRandomNum = Math.floor(Math.random() * _iBallColors);
            var bFound = false;

            if(_aColorsAvailable[iRandomNum] === true) {
                oBall = new CBall(iRandomNum,_oBallContainer);
                
                bFound = true;
                break;
            }
        }while(bFound === false);
        
        return oBall;
    };
    
    this._checkIfAllColorsNotAvailable = function(){
        var bRet = true;
        for(var i=0;i<_aColorsAvailable.length;i++){
            if(_aColorsAvailable[i] === true){
                bRet = false;
            }
        }

        return bRet;
    };
    
    this._nextShoot = function(){
        if(_oCurBall !== null){
            _oCurBall.unload();
        }

        _oCurBall = _oNextBall;
        _oCurBall.changePos(HERO_WIDTH/2,(HERO_HEIGHT/2) + 36 );

        _oNextBall = this._getRandomBall();
        _oNextBall.changePos(HERO_WIDTH/2,HERO_HEIGHT/2 - 12);
        
        var oParent = this;
        createjs.Tween.get(_oCurBall.getSprite()).to({y:_oCurBall.getY()+25}, 300).call(function(){oParent._onBallReady()}); 
        createjs.Tween.get(_oNextBall.getSprite()).to({y:_oNextBall.getY()+16}, 300); 
    };
    
    this.playAnim = function(szAnim){
        _oSpriteBg.gotoAndPlay(szAnim);
    };
    
    this.colorCleared = function(iColor){
        _aColorsAvailable[iColor] = false;

        if(_oCurBall.getIndex() === iColor){
                _oCurBall.unload();
                _oCurBall = this._getRandomBall();
                if(_oCurBall !== null){
                    _oCurBall.changePos(HERO_WIDTH/2 ,(HERO_HEIGHT/2) + 61 );
                }
        }
        
        if(_oNextBall.getIndex() === iColor){
                _oNextBall.unload();
                _oNextBall = this._getRandomBall();
                if(_oNextBall !== null){
                    _oNextBall.changePos(HERO_WIDTH/2,HERO_HEIGHT/2+4);
                }
        }
       
    };
    
    
    this._onBallReady = function(){
        _bCanShoot = true;
    };
    
    this.getCurrentBall = function(){
        _bCanShoot = false;
        var oBall = _oCurBall;
        this._nextShoot();
        return oBall;
    };
    
    this.getX = function(){
        return _oContainer.x;
    };
    
    this.getY = function(){
        return _oContainer.y;
    };
    
    this.getRotation = function(){
        return _oContainer.rotation;
    };
    
    this.canShoot = function(){
        return _bCanShoot;
    };
    
    this._init();
}