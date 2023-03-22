$(document).ready(function(){
function size (num) {
var spacers = num-1;
var spacersTotal = 2*spacers;
var buttonAvailableSpace = 100-spacersTotal;
var buttonDimensions = buttonAvailableSpace/num;
var buttonDimensionsRounded = Math.floor(buttonDimensions *100)/100;
return [buttonDimensionsRounded, +spacers];
}
var level = 0;
var numcorrect = 0;
var arr = [];
var clickable = false;
var strikes = "";
function vars (cor) {
$("#begin").css("opacity", "0");
clickable = false;
$( ".button" ).remove();
if (cor === "start") {
level = 3;
}
if (cor === "increase") {
level++;
}
if (cor === "decrease") {
level--;
}
arr = [];
numcorrect = 0;
var i = 1;
while (i*i/2 < level) {
i++;
}


setTiles(i);
$("#level").text(level+" blocks");

}

function setTiles (num) {
var square = num*num;
var right = "marginright";
var top = ""
for (var i = 1; i < square+1; i++){
right = "marginright";
if (i % num === 0) {
right = "";
}
if (i > num) {
top = "margintop";
}
$("#square").append('<div id="b'+i+'" class="button clickable color1  left '+right+' '+top+'"></div>');
}

$(".button").css('height', size(num)[0]+'%');
$(".button").css('width', size(num)[0]+'%');
create(square);
}
function shuffle (num) {
var o = [];
for (var i = 1; i <num+1; i++) {
o.push("#b"+i);
}
for(var j, x, i = o.length; i; j = parseInt(Math.random() * i), x = o[--i], o[i] = o[j], o[j] = x);
                return o;
                };

function create (num) {
arr = shuffle(num);
arr = arr.slice(0,level);
for (var i = 0; i < level+1; i++) {
display(arr[i]);
}
}
function display (div) {

  $( div ).animate({
    opacity: 1,
  }, 1000, function() {
 $( div ).animate({
    opacity: 0.25,
  }, 1000, function() {

  });
  });
setTimeout(function(){
clickable = true;
}, 2000);
}
$(document).delegate('.clickable', 'click', function() {
if (clickable === true) {
var id = $(this).attr('id');
$(this).removeClass("clickable");
check (arr, id);
}
});
function check (array, click) {
var loc = array.indexOf("#"+click);
if (loc >= 0) {
$("#"+click).css("opacity", "1");
numcorrect++;
if (numcorrect === arr.length) {
correctping ();
setTimeout(function(){
vars ("increase");
}, 300);
}
} else {

strikes = strikes+"✖";
incorrectping ();
if (strikes === "✖✖✖") {
restart();
} else {
setTimeout(function(){
vars ("decrease");
}, 300);
}
}


}
function correctping () {
$("#ping").css("color","green");
$("#ping").text("✓");

$( "#ping" ).animate({
    opacity: 1,
  }, 145, function() {
 $( "#ping" ).animate({
    opacity: 0.0,
  }, 145, function() {

  });
  });
}
function incorrectping () {
$("#ping").css("color","red");
$("#ping").text(strikes);

$( "#ping" ).animate({
    opacity: 1,
  }, 145, function() {
 $( "#ping" ).animate({
    opacity: 0.0,
  }, 145, function() {

  });
  });
}
function restart() {
var num = 3;
level = 3;
numcorrect = 0;
arr = [];
clickable = false;
strikes = "";
$( ".button" ).remove();
$("#level").text(level+" blocks");
$("#begin").css("opacity", "1");
var square = num*num;
var right = "marginright";
var top = ""
for (var i = 1; i < square+1; i++){
right = "marginright";
if (i % num === 0) {
right = "";
}
if (i > num) {
top = "margintop";
}
$("#square").append('<div id="b'+i+'" class="button clickable color1  left '+right+' '+top+'"></div>');
}

$(".button").css('height', size(num)[0]+'%');
$(".button").css('width', size(num)[0]+'%');
}
restart();
$( "#begin" ).click(function() {
if (clickable === false) {
vars ("start");
}
});
function startButton () {
$("#begin").css("opacity", "0");
vars ("start");
}
});