$(document).ready(function(){
var c1Array = ["b5","b4","b3","b2","b1"];
var c2Array = [];
var c3Array = [];
var counter = 0;
var active = "";
function opacityBack () {

$(".block").css("opacity","1");
}


$(".hone").click(function(){
if (active.length > 1 ) {
if (c1Array.length === 0 || active.substring(1,2)>c1Array[0].substring(1,2)) {
c1Array.unshift(active);
$("#"+active).css("opacity","1");
$("#"+active).prependTo("#c1");
active = "";
counter++;
$(".numbermoves").text(counter);
}

} else {

if (c1Array.length > 0) {
active = c1Array[0];
c1Array.shift();
}

$("#"+active).css("opacity","0.5");
}
});
$(".htwo").click(function(){
if (active.length > 1) {
if (c2Array.length === 0 || active.substring(1,2)>c2Array[0].substring(1,2)) {
c2Array.unshift(active);
$("#"+active).css("opacity","1");
$("#"+active).prependTo("#c2");
active = "";
counter++;
$(".numbermoves").text(counter);
}
} else {

if (c2Array.length > 0) {
active = c2Array[0];
c2Array.shift();
}
$("#"+active).css("opacity","0.5");
}
});
$(".hthree").click(function(){
if (active.length > 1) {
if (c3Array.length === 0 || active.substring(1,2)>c3Array[0].substring(1,2)) {
c3Array.unshift(active);
$("#"+active).css("opacity","1");
$("#"+active).prependTo("#c3");
active = "";
counter++;
$(".numbermoves").text(counter);
if (c3Array.length === 5) {
alert("completed in "+counter+" moves");
}
}
} else {

if (c3Array.length > 0) {
active = c3Array[0];
c3Array.shift();
}

$("#"+active).css("opacity","0.5");
}
});
function restart() {
opacityBack ();
c1Array = ["b5","b4","b3","b2","b1"];
c2Array = [];
c3Array = [];
counter = 0;
active = "";
$(".numbermoves").text("0");
$("#b1").prependTo("#c1");
$("#b2").prependTo("#c1");
$("#b3").prependTo("#c1");
$("#b4").prependTo("#c1");
$("#b5").prependTo("#c1");
}
$(".restart").click(function(){
restart();
});
});