var $ = jQuery.noConflict();

$(document).ready(function(e) {
    SiteManager.init();
});

var SiteManager = {

    mob: false,
    myHomeStartButton:$('.js-home-start'),
    myInstructionsStartButton:$('.js-instructions-start'),
    myInstructionsScreen: $('.masega-section-instructions'),
    myIncident1BTScreen: $('.masega-section-incident1-bt'),

    init: function () {
        this.mob = (navigator.userAgent.match(/(Android|webOS|BlackBerry|IEMobile|Opera Mini|iPad|iPhone|iPod)/g) ? true : false);
        if (window.orientation == 0 || window.orientation == 90 || window.orientation == -90 || window.orientation == 180) {
            this.mob = true;
        }

        $(window).resize(this.handleStageResize.bind(this));
        this.handleStageResize();

        QuestionsManager.init();
        DragAnswerManager.init();

        $.getJSON("data/data.json", this.onDataLoaded.bind(this));
        this.myHomeStartButton.on("click", this.onHomeStartClicked.bind(this));
        this.myInstructionsStartButton.on("click", this.onInstructionsStartClicked.bind(this));

    },

    getURLParam: function (name) {
        var results = new RegExp('[\?&]' + name + '=([^&#]*)').exec(window.location.href);
        if (results==null) {
            return null;
        }
        return decodeURI(results[1]) || 0;
    },

    onDataLoaded: function (data) {
        $('.js-main-image').attr('src', data.startimage);
        //
        $('.js-personname').html(data.personname);
        //
        $('.masega-section-instructions h2').html(data.instructions.title);
        $('.masega-section-instructions .masega-p-container').html(this.getBodyHtml(data.instructions.bodycopy));
        //
        $('.masega-section-incident1-bt h2').html(data.incidenttitle);
        //
        $('#question1 h2').html(data.intro.title);
        $('#question1 .masega-p-container').html(this.getBodyHtml(data.intro.bodycopy));
        //
        $('#question2 h2').html(data.video.title);
        //
        $('#question3 h2').html(data.answer1.title);
        $('#question3 .masega-p-container').html(this.getBodyHtml(data.answer1.bodycopy));
        //
        $('#question4 h2').html(data.feedbackvideo1.title);
        //
        $('#question5 h2').html(data.answerreview1.title);
        $('#question5 .masega-p-container').html(this.getBodyHtml(data.answerreview1.bodycopy));
        //
        $('#question6 h2').html(data.answer2.title);
        $('#question6 .masega-p-container').html(this.getBodyHtml(data.answer2.bodycopy));
        //
        $('#question7 h2').html(data.feedbackvideo2.title);
        //
        $('#question8 h2').html(data.answerreview2.title);
        $('#question8 .masega-p-container').html(this.getBodyHtml(data.answerreview2.bodycopy));
        //
        $('#question9 h2').html(data.finalreview.title);
        $('#question9 .masega-p-container').html(this.getBodyHtml(data.finalreview.bodycopy));

        QuestionsManager.aPersonAnswers["person-answer-1"] = [];
        QuestionsManager.aPersonAnswers["person-answer-2"] = [];
        for(var i=0;i<4;i++){
            QuestionsManager.aPersonAnswers["person-answer-1"].push(100 - data.personanswers.data[i].power);
            QuestionsManager.aPersonAnswers["person-answer-2"].push(data.personanswers.data[i].valance);
            //
            $('.js-answer-word-'+(i+1)).html(data.personanswers.visual[i]);
        }



        LocalVideoManager.init([
            {
                divID:'video-1',
                videoID:'clip-1',
                videopath:data.video.videopath
            },
            {
                divID:'video-2',
                videoID:'clip-2',
                videopath:data.feedbackvideo1.videopath
            },
            {
                divID:'video-3',
                videoID:'clip-3',
                videopath:data.feedbackvideo2.videopath
            }
        ]);

        $('.masega-section-home').fadeIn();
    },

    getBodyHtml: function (_ar) {
        var htmltxt = "";
        for(var i=0;i<_ar.length;i++){
            htmltxt+="<p>"+_ar[i]+"</p>";
        }
        return htmltxt;
    },

    handleStageResize: function () {
        // First we get the viewport height and we multiple it by 1% to get a value for a vh unit
        var vh = window.innerHeight * 0.01;
        var vw = window.innerWidth * 0.01;

        // Then we set the value in the --vh custom property to the root of the document
        document.documentElement.style.setProperty('--vw', vw + 'px');
        document.documentElement.style.setProperty('--vh', vh + 'px');
    },

    onHomeStartClicked: function () {
        this.myInstructionsScreen.show();

        $('html, body').animate({
            scrollTop: this.myInstructionsScreen.offset().top
        }, 500);
    },

    onInstructionsStartClicked: function () {
        this.myIncident1BTScreen.show();

        $('html, body').animate({
            scrollTop: this.myIncident1BTScreen.offset().top
        }, 500);

        QuestionsManager.checkAnswer(0);
    }
};

var QuestionsManager = {
    LINE_OPTIONS:["maybe", "other-than-cultural-difference", "cultural-difference"],

    myQuestions: $('.masega-question'),
    myNextButtons: $('.masega-question .js-btn-next'),
    bVideoPerson1IsFinished:false,
    bVideoPerson2IsFinished:false,
    bVideoPerson3IsFinished:false,
    myFinalScreenTheirsToggle:null,
    myFinalScreenYoursToggle:null,
    myFinalScreenLines:null,

    aPersonAnswers:{
        "person-answer-1":[],
        "person-answer-2":[]
    },

    init: function () {
        this.myNextButtons.addClass('masega-button--inactive');
        for (var i = 0; i < this.myQuestions.length; i++) {
            $(this.myQuestions[i]).attr("id", "question" + i);
        }


        this.myFinalScreenTheirsToggle = $('#question'+(this.myQuestions.length-1)+' .masega-question__copy-left .masega-drag-answer__theirs');
        this.myFinalScreenYoursToggle = $('#question'+(this.myQuestions.length-1)+' .masega-question__copy-left .masega-drag-answer__yours');
        this.myFinalScreenLines = $('.masega-drag-answer__connecting-line');

        this.myFinalScreenLines.on("click", this.onToggleFinalScreenLineClicked.bind(this));
    },

    checkAnswer: function (_questionID){
        switch(_questionID) {
            case 0:
            case 1:
                //title or text intro screen
                this.changeQuestionStatus(_questionID, true);
                break;
            case 2:
                this.changeQuestionStatus(_questionID, this.bVideoPerson1IsFinished);
                break;
            case 3:
                this.changeQuestionStatus(_questionID, DragAnswerManager.checkIfAllPlaced('person-answer-1'));
                break;
            case 4:
                this.changeQuestionStatus(_questionID, this.bVideoPerson2IsFinished);
                break;
            case 5:
                this.changeQuestionStatus(_questionID, true);
                break;
            case 6:
                this.changeQuestionStatus(_questionID, DragAnswerManager.checkIfAllPlaced('person-answer-2'));
                break;
            case 7:
                this.changeQuestionStatus(_questionID, this.bVideoPerson3IsFinished);
                break;
            case 8:
                this.changeQuestionStatus(_questionID, true);
                break;
        }
    },

    changeQuestionStatus: function (_questionID, _isUnlocked) {
        if(_isUnlocked) {
            if($(this.myNextButtons[_questionID]).hasClass('masega-button--inactive')) {
                $(this.myNextButtons[_questionID]).removeClass('masega-button--inactive');
                $(this.myNextButtons[_questionID]).on("click", this.onNextClicked.bind(this));
                this.unlockNextQuestion(_questionID+1);
            }
        }else {
            if(!$(this.myNextButtons[_questionID]).hasClass('masega-button--inactive')) {
                $(this.myNextButtons[_questionID]).addClass('masega-button--inactive');
                $(this.myNextButtons[_questionID]).off("click");
               // this.lockNextQuestions(_questionID+1);
            }
        }
    },

    lockSectionAnswers: function (_sectionID) {
        $('#'+ _sectionID + ' .masega-drag-answer').css({
            pointerEvents:'none'
        });
    },

    onNextClicked: function (e) {
        var questionID = Number($(e.currentTarget).closest('.masega-question')[0].id.substr(8));

        console.log("MXT", "#question" + (questionID + 1));

        //if(questionID < 8){
            $('html, body').animate({
                scrollTop: $("#question" + (questionID + 1)).offset().top
            }, 500);
        //}

        if(questionID + 1 == 2){
            LocalVideoManager.playVideo('video-1');
        }

        if(questionID + 1 == 4){
            this.lockSectionAnswers('question3');
            LocalVideoManager.playVideo('video-2');
        }

        if(questionID + 1 == 5){
            this.setupAnswerReview('person-answer-1');
        }

        if(questionID + 1 == 7){
            this.lockSectionAnswers('question6');
            LocalVideoManager.playVideo('video-3');
        }

        if(questionID + 1 == 8){
            this.setupAnswerReview('person-answer-2');
        }

        if(questionID + 1 == 9){
            this.setupAnswerReview('person-answer-end');
        }

    },

    onShowYoursOnFinalScreenClicked: function () {
        this.myFinalScreenYoursToggle.removeClass('masega-drag-answer--inactive');
        this.myFinalScreenYoursToggle.removeClass('masega-drag-answer--clickable');
        this.myFinalScreenTheirsToggle.addClass('masega-drag-answer--inactive');
        this.myFinalScreenTheirsToggle.addClass('masega-drag-answer--clickable');
        $('#person-answer-end-review .masega-drag-answer__yours').removeClass('masega-drag-answer--inactive');
        $('#person-answer-end-review .masega-drag-answer__theirs').addClass('masega-drag-answer--inactive');
    },

    onShowTheirsOnFinalScreenClicked: function () {
        this.myFinalScreenYoursToggle.addClass('masega-drag-answer--inactive');
        this.myFinalScreenYoursToggle.addClass('masega-drag-answer--clickable');
        this.myFinalScreenTheirsToggle.removeClass('masega-drag-answer--inactive');
        this.myFinalScreenTheirsToggle.removeClass('masega-drag-answer--clickable');
        $('#person-answer-end-review .masega-drag-answer__theirs').removeClass('masega-drag-answer--inactive');
        $('#person-answer-end-review .masega-drag-answer__yours').addClass('masega-drag-answer--inactive');
    },

    setupAnswerReview: function (_answerID) {
        var answ = DragAnswerManager.getAnswerPositions(_answerID);
        var yours = $('#'+_answerID+'-review .masega-drag-answer__yours');
        var theirs = $('#'+_answerID+'-review .masega-drag-answer__theirs');

        if(_answerID == "person-answer-end"){
            var answ1 = DragAnswerManager.getAnswerPositions('person-answer-1');
            var answ2 = DragAnswerManager.getAnswerPositions('person-answer-2');

            //HACK just for the loop
            answ = answ1;

            this.myFinalScreenYoursToggle.on("click", this.onShowYoursOnFinalScreenClicked.bind(this));
            this.myFinalScreenTheirsToggle.on("click", this.onShowTheirsOnFinalScreenClicked.bind(this));
        }

        for(var i=0;i<answ.length;i++){
            var yourLeft = answ[i].x*100;
            var yourTop = answ[i].y*100;
            var theirLeft, theirTop;

            switch(_answerID){
                case "person-answer-1":
                    yourLeft = "6%";
                    yourTop = this.adjustPos(_answerID, false, yourTop);
                    //
                    theirLeft = "50%";
                    theirTop = this.adjustPos(_answerID, false, this.aPersonAnswers[_answerID][i]);
                    break;

                case "person-answer-2":
                    yourTop = (3 + (i*20))+"%";
                    yourLeft = this.adjustPos(_answerID, true, yourLeft);
                    //
                    theirTop = (13 + (i*20))+"%";
                    theirLeft = this.adjustPos(_answerID, true, this.aPersonAnswers[_answerID][i]);
                    break;

                case "person-answer-end":
                    yourTop = this.adjustPos(_answerID, false, answ1[i].y*100);
                    yourLeft = this.adjustPos(_answerID, true, answ2[i].x*100);
                    theirTop = this.adjustPos(_answerID, false, this.aPersonAnswers["person-answer-1"][i]);
                    theirLeft = this.adjustPos(_answerID, true, this.aPersonAnswers["person-answer-2"][i]);
                    //
                    break;
            }

            $(yours[i]).css({
                left:yourLeft,
                top:yourTop
            });

            $(theirs[i]).css({
                left:theirLeft,
                top:theirTop
            });
        }

        if(_answerID == "person-answer-end"){
            window.setTimeout(this.handleLines.bind(this), 100);
        }
    },

    adjustPos: function (_answerID, _isHor, _perc) {
        _perc = _perc / 100;
        var itemWidth = $('#'+_answerID+'-review .masega-drag-answer__yours').width();
        var itemHeight = $('#'+_answerID+'-review .masega-drag-answer__yours').height();
        var areaWidth = $('#'+_answerID+'-review .masega-drag-answer__area').width();
        var areaHeight = $('#'+_answerID+'-review .masega-drag-answer__area').height();

        var adjustedPos;

        if(_isHor){
            adjustedPos = (_perc * (areaWidth - itemWidth)) + "px";
        }else{
            adjustedPos = (_perc * (areaHeight - itemHeight)) + "px";
        }

        console.log(itemWidth, itemHeight, areaWidth, areaHeight, _perc, adjustedPos);

        return adjustedPos;
    },

    handleLines: function () {
        var yours = $('#person-answer-end-review .masega-drag-answer__yours');
        var theirs = $('#person-answer-end-review .masega-drag-answer__theirs');
        var offset = $('#person-answer-end-review .masega-general-container').offset();
        for(var i=0;i<yours.length;i++) {
            this.adjustLine($(yours[i]), $(theirs[i]), $($('#person-answer-end-review .masega-drag-answer__connecting-line')[i]), offset);
        }
    },

    onToggleFinalScreenLineClicked: function (e) {
        var c = 0;
        for(var i=0;i<this.LINE_OPTIONS.length;i++){
            if($(e.currentTarget).hasClass('masega-drag-answer__connecting-line--'+this.LINE_OPTIONS[i])){
                c = i;
                $(e.currentTarget).removeClass('masega-drag-answer__connecting-line--'+this.LINE_OPTIONS[i]);
            }
        }

        c++;
        if(c == this.LINE_OPTIONS.length){
            c = 0;
        }
        $(e.currentTarget).addClass('masega-drag-answer__connecting-line--'+this.LINE_OPTIONS[c]);
    },

    unlockNextQuestion: function (_questionID) {
        console.log('unlockNextQuestion',_questionID);
        $(this.myQuestions[_questionID]).show();
        this.checkAnswer(_questionID);
    },

    onVideoFinished: function (_playerID){
        switch(_playerID){
            case "video-1":
                this.bVideoPerson1IsFinished = true;
                this.checkAnswer(2);
                break;

            case "video-2":
                this.bVideoPerson2IsFinished = true;
                this.checkAnswer(4);
                break;

            case "video-3":
                this.bVideoPerson3IsFinished = true;
                this.checkAnswer(7);
                break;
        }
    },

    adjustLine: function (from, to, line, _offset) {
        var fT = from.offset().top  + from.height()/2;
        var tT = to.offset().top 	 + to.height()/2;
        var fL = from.offset().left + from.width()/2;
        var tL = to.offset().left 	 + to.width()/2;

        var CA   = Math.abs(tT - fT);
        var CO   = Math.abs(tL - fL);
        var H    = Math.sqrt(CA*CA + CO*CO);
        var ANG  = 180 / Math.PI * Math.acos( CA/H );

        if(tT > fT){
            var top  = (tT-fT)/2 + fT;
        }else{
            var top  = (fT-tT)/2 + tT;
        }
        if(tL > fL){
            var left = (tL-fL)/2 + fL;
        }else{
            var left = (fL-tL)/2 + tL;
        }

        if(( fT < tT && fL < tL) || ( tT < fT && tL < fL) || (fT > tT && fL > tL) || (tT > fT && tL > fL)){
            ANG *= -1;
        }
        top-= H/2;

        top-=_offset.top;
        left-=_offset.left;

        line.css({
            '-webkit-transform' : 'rotate(' + ANG +'deg)',
            '-moz-transform'    : 'rotate(' + ANG +'deg)',
            '-ms-transform'     : 'rotate(' + ANG +'deg)',
            '-o-transform'      : 'rotate(' + ANG +'deg)',
            'transform'         : 'rotate(' + ANG +'deg)',
            top:top+'px',
            left:left+'px',
            height:H+'px'

        });
    }
};

var LocalVideoManager = {
    myPlayers:null,
    myVideoDiv:$('.masega-question__video'),
    sCurrentVideoPlayerID:'',

    init: function (_data) {
        this.setupPlayers(_data);
    },

    playVideo: function (_playerID) {
        this.sCurrentVideoPlayerID = _playerID;
        this.myPlayers[_playerID][0].play();
        this.myPlayers[_playerID].on("ended", this.onVideoComplete.bind(this));
    },

    stopVideo: function (_playerID) {

    },

    hideVideo: function () {
        this.myVideoDiv.hide();
    },

    setupPlayers: function (_playerIDs) {
        this.myPlayers = {};
        for(var i=0;i<_playerIDs.length;i++) {

            $('#'+_playerIDs[i].divID).html('<video src="'+_playerIDs[i].videopath+'" playsinline controls></video>')


            this.myPlayers[_playerIDs[i].divID] = $('#'+_playerIDs[i].divID+' video');
        }
    },

    onVideoComplete: function (e) {
        console.log("onVideoComplete", this.sCurrentVideoPlayerID);
        this.myPlayers[this.sCurrentVideoPlayerID].off("ended");
        QuestionsManager.onVideoFinished(this.sCurrentVideoPlayerID);
    }
}

var YouTubeManager = {
    myPlayers:null,
    bAPIReady:false,
    myVideoDiv:$('.masega-question__video'),
    sCurrentVideoPlayerID:'',

    init: function () {
        var tag = document.createElement('script');

        tag.src = "https://www.youtube.com/iframe_api";
        var firstScriptTag = document.getElementsByTagName('script')[0];
        firstScriptTag.parentNode.insertBefore(tag, firstScriptTag);
    },

    playVideo: function (_playerID) {
        if(this.myPlayers[_playerID]){
            this.myPlayers[_playerID].playVideo();
            this.sCurrentVideoPlayerID = _playerID;
        }
    },

    stopVideo: function (_playerID) {
        if(this.myPlayers[_playerID]){
            this.myPlayers[_playerID].stopVideo();
        }
    },

    hideVideo: function () {
        this.myVideoDiv.hide();
    },

    onAPIReady: function () {
        console.log("onAPIReady");
        this.bAPIReady = true;
        this.setupPlayers([
            {
                divID:'video-1',
                videoID:'5_sfnQDr1-o'
            },
            {
                divID:'video-2',
                videoID:'t6wCykq_bLo'
            },
            {
                divID:'video-3',
                videoID:'Q9MFDKka0Bw'
            }
        ]);
    },

    setupPlayers: function (_playerIDs) {
        this.myPlayers = {};
        for(var i=0;i<_playerIDs.length;i++) {
            this.myPlayers[_playerIDs[i].divID] = new YT.Player(_playerIDs[i].divID, {
                height: '100%',
                width: '100%',
                videoId: _playerIDs[i].videoID,
                events: {
                    'onReady': YouTubeManager.onPlayerReady.bind(YouTubeManager),
                    'onStateChange': YouTubeManager.onPlayerStateChange.bind(YouTubeManager)
                }
            });
        }
    },

    onPlayerReady: function () {

    },

    onPlayerStateChange: function (e) {
        this.sCurrentVideoPlayerID = e.target.m.id;
        if(e.data == 0) {
            QuestionsManager.onVideoFinished(this.sCurrentVideoPlayerID);
        }
    }
};

function onYouTubeIframeAPIReady() {
    YouTubeManager.onAPIReady();
}




var DragAnswerManager = {
    myDragAnswer: $('.masega-drag-answer'),
    myAnswers:{},
    myAnswersData:{},

    init: function () {
        for(var i=0;i<this.myDragAnswer.length;i++){
            var ai = $(this.myDragAnswer[i]).attr("id");
            this.myAnswers[ai] = [];
            for(var j=0;j<4;j++) {
                var dai = new DragableAnswerItem(ai, ai+'dai'+j, 'area'+i);
                dai.activateInteractions();
                this.myAnswers[ai].push(dai);
            }
        }
    },

    checkIfAllPlaced: function (_answerID) {
        this.myAnswersData[_answerID] = [];

        var allPlaced = true;
        for(var i=0;i<this.myAnswers[_answerID].length;i++){
            var dta = this.myAnswers[_answerID][i].checkIfInArea();
            if(!dta.isOk){
                allPlaced = false;
            }else{
                this.myAnswersData[_answerID].push(dta);
            }
        }

        return allPlaced;
    },

    getAnswerPositions: function (_answerID) {
        return this.myAnswersData[_answerID];
    }
};

var DragableAnswerItem = function(_answerID, _id, _area) {
    this.oLastPos = {left:0,top:0};
    this.bIsDragging = false;
    this.myArea = $('#'+_area);
    this.myVisual = $('#'+_id);
    this.myBaseSpot = $('#bs'+_id);
    this.myAnswerID = _answerID;
    //
    this.hammerRef = new Hammer(document.getElementById(_id));
    this.hammerRef.add( new Hammer.Pan({ direction: Hammer.DIRECTION_ALL, threshold: 0 }) );
    this.hammerRef.add( new Hammer.Tap() );
}

DragableAnswerItem.prototype.activateInteractions = function () {
    this.hammerRef.on("pan", this.handleDrag.bind(this));
    this.hammerRef.on("tap", this.handleTap.bind(this));
}

DragableAnswerItem.prototype.deActivateInteractions = function () {
    this.hammerRef.off("pan");
    this.hammerRef.off("tap");
}

DragableAnswerItem.prototype.handleTap = function (e) {

}

DragableAnswerItem.prototype.handleDrag = function (e) {
    if ( ! this.bIsDragging ) {
        this.bIsDragging = true;
        this.oLastPos = this.myVisual.position();
        this.myVisual.addClass('masega-drag-answer__dragable--top')
    }

    var posX = e.deltaX + this.oLastPos.left;
    var posY = e.deltaY + this.oLastPos.top;

    // move our element to that position
    this.myVisual.css({
        left:posX + "px",
        top:posY + "px"
    });

    if (e.isFinal) {
        this.myVisual.removeClass('masega-drag-answer__dragable--top');
        this.checkIfInArea();
        this.bIsDragging = false;

        switch(this.myAnswerID){
            case 'person-answer-1':
                QuestionsManager.checkAnswer(3);
                break;
            case 'person-answer-2':
                QuestionsManager.checkAnswer(6);
                break;
        }
    }
}

DragableAnswerItem.prototype.checkIfInArea = function () {
    var myPos = this.myVisual.position();
    var areaPos = this.myArea.position();
    var myVisualWidth = this.myVisual.width();
    var areaWidth = this.myArea.width();
    var myVisualHeight = this.myVisual.height();
    var areaHeight = this.myArea.height();
    //
    var placedOK = {
        isOk:false,
        x:0,
        y:0
    };
    if(myPos.left >= areaPos.left && myPos.left < areaPos.left+areaWidth-myVisualWidth){
        if(myPos.top >= areaPos.top && myPos.top < areaPos.top+areaHeight-myVisualHeight){
            placedOK.isOk = true;
            placedOK.x = (myPos.left-areaPos.left) / (areaWidth-myVisualWidth);
            placedOK.y = (myPos.top-areaPos.top) / (areaHeight-myVisualHeight);
        }
    }
    if(!placedOK.isOk){
        var startPos = this.myBaseSpot.position();
        this.myVisual.css({
            left:startPos.left + "px",
            top:startPos.top + "px"
        });
    }

    console.log(placedOK);

    return placedOK;
}
