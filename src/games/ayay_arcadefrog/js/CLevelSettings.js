function CLevelSettings(oJson){
    var _oJson;
    var _aBallSpeed;
    var _aBallNumber;
    var _aBallColors;
    var _aHeroPos;
    var _aCurveLevel;
    var _aBgPerLevel;
    var _aFgPerLevel;
    var _aBeeTrajectory;
    var _aDaisyPos;
    
    this._init = function(oJson){
        _oJson = oJson;
        NUM_LEVELS = Object.keys(_oJson).length;

        this._initBallSpeed();
        this._initBallNumber();
        this._initBallColors();
        this._initHeroPos();
        this._initCurveLevel();
        this._initBgLevel();
        this._initBeeTraj();
        this._initDaisySpawnPos();
    };
    
    this._initBallSpeed = function(){
        _aBallSpeed = new Array();
       
       for(var i=0;i<NUM_LEVELS;i++){
           _aBallSpeed[i] = _oJson[i].ball_speed;
       }
       
    };
    
    this._initBallNumber = function(){
        _aBallNumber = new Array();
        
        for(var i=0;i<NUM_LEVELS;i++){
            _aBallNumber[i] = _oJson[i].ball_number;
        }
    };
    
    this._initBallColors = function(){
        _aBallColors = new Array();
        
        for(var i=0;i<NUM_LEVELS;i++){
            _aBallColors[i] = _oJson[i].num_colors;
        }
    };
    
    this._initHeroPos = function(){
        _aHeroPos = new Array();
        
        for(var i=0;i<NUM_LEVELS;i++){
            _aHeroPos[i] = new createjs.Point(_oJson[i].hero_pos.x,_oJson[i].hero_pos.y);
        }
    };
    
    this._initCurveLevel = function(){
        _aCurveLevel = new Array();
        
        for(var i=0;i<NUM_LEVELS;i++){
            _aCurveLevel[i] = new Array();
            var aPoints = _oJson[i].curve_point;
            for(var j=0;j<aPoints.length;j++){
                _aCurveLevel[i].push( [aPoints[j].x,aPoints[j].y]);
            }
        }
        
    };
    
    this._initBgLevel = function(){
        _aBgPerLevel = new Array();
        _aFgPerLevel = new Array();
        for(var i=0;i<NUM_LEVELS;i++){
            _aBgPerLevel[i] = _oJson[i].bg_image;
            _aFgPerLevel[i] = _oJson[i].fg_image;
        }
    };
    
    this._initBeeTraj = function(){
        _aBeeTrajectory = new Array();
        
        _aBeeTrajectory[0] = {path:[-58,267,100,100,1005,352],start_rot:0,final_rot:25,start:0,end:1,time:12000};
        _aBeeTrajectory[1] = {path:[-33,458,300,470,980, 329],start_rot:0,final_rot:-25,start:0,end:1,time:12000};
        _aBeeTrajectory[2] = {path:[304, 574,300,54,500,-100],start_rot:-90,final_rot:-50,start:0,end:1,time:12000};
    };
    
    this._initDaisySpawnPos = function(){
        _aDaisyPos = new Array();
        
        _aDaisyPos[0] = [{x:80,y:274},{x:224,y:296},{x:452,y:358},{x:616,y:288},{x:845,y:344}];
        _aDaisyPos[1] = [{x:850,y:470},{x:877,y:152},{x:256,y:126},{x:650,y:277}];
        _aDaisyPos[2] = [{x:57,y:277},{x:627,y:150},{x:867,y:433},{x:238,y:510}];
        _aDaisyPos[3] = [{x:600,y:35},{x:85,y:186},{x:200,y:496},{x:782,y:160},{x:720,y:470},{X:890,Y:400}];
        _aDaisyPos[4] = [{x:82,y:166},{x:325,y:205},{x:373,y:338},{x:571,y:200},{x:845,y:278}];
        _aDaisyPos[5] = [{x:93,y:150},{x:374,y:30},{x:334,y:342},{x:870,y:163},{x:892,y:405}];
        _aDaisyPos[6] = [{x:70,y:150},{x:135,y:480},{x:387,y:258},{x:786,y:360},{x:880,y:194}];
        _aDaisyPos[7] = [{x:87,y:154},{x:141,y:478},{x:314,y:260},{x:571,y:347},{x:847,y:160},{x:854,y:425}];
        _aDaisyPos[8] = [{x:69,y:154},{x:83,y:332},{x:289,y:500},{x:834,y:155},{x:900,y:392}];
        _aDaisyPos[9] = [{x:77,y:170},{x:200,y:490},{x:328,y:311},{x:682,y:344},{x:896,y:217}];
        _aDaisyPos[10] = [{x:52,y:147},{x:300,y:248},{x:576,y:284},{x:847,y:168},{x:873,y:427}];
        _aDaisyPos[11] = [{x:82,y:122},{x:90,y:343},{x:308,y:113},{x:784,y:146},{x:894,y:334}];
        _aDaisyPos[12] = [{x:49,y:162},{x:400,y:61},{x:607,y:34},{x:482,y:266},{x:893,y:200}];
    };
    
    this.getBallSpeedForLevel = function(iLevel){
        return _aBallSpeed[iLevel-1];
    };
    
    this.getBallNumberForLevel = function(iLevel){
        return _aBallNumber[iLevel-1];
    };
    
    this.getBallColorsForLevel = function(iLevel){
        return _aBallColors[iLevel-1];
    };
    
    this.getHeroPosForLevel = function(iLevel){
        return _aHeroPos[iLevel-1];
    };
    
    this.getCurveForLevel = function(iLevel){
        return _aCurveLevel[iLevel-1];
    };
    
    this.getNumLevels = function(){
        return _aCurveLevel.length;
    };
    
    this.getBgForLevel = function(iLevel){
        return _aBgPerLevel[iLevel-1];
    };
    
    this.getFgForLevel = function(iLevel){
        return _aFgPerLevel[iLevel-1];
    };
    
    this.getRandBeeTrajectory = function(){
        var iRand = Math.floor(Math.random() * _aBeeTrajectory.length);
        return _aBeeTrajectory[iRand];
    };
    
    this.getDaisyPosForLevel = function(iLevel){
        return _aDaisyPos[iLevel-1];
    };
    
    this._init(oJson);
}