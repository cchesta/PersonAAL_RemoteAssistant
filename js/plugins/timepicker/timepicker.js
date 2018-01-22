/* 
 * To change this license header, choose License Headers in Project Properties.
 * To change this template file, choose Tools | Templates
 * and open the template in the editor.
 */

//TODO creare un interval picker in modo procedurale


function resetTimepicker()
{
    document.getElementById("hour_prev").textContent= 24;
    document.getElementById("hour_selected").textContent= 0;
    document.getElementById("hour_next").textContent= 1;
    
    document.getElementById("minute_prev").textContent= 9;
    document.getElementById("minute_selected").textContent= 10;
    document.getElementById("minute_next").textContent= 11;
}

function hour_prev(){
                
    var hour_prev= document.getElementById("hour_prev").textContent;
    var hour_selected= document.getElementById("hour_selected").textContent;
    var hour_next= document.getElementById("hour_next").textContent;

    if(hour_prev <= 0)
        document.getElementById("hour_prev").textContent= 24;
    else
        document.getElementById("hour_prev").textContent--;

    if(hour_selected <= 0)
        document.getElementById("hour_selected").textContent= 24;
    else
        document.getElementById("hour_selected").textContent--;

    if(hour_next <= 0)
        document.getElementById("hour_next").textContent= 24;
    else
        document.getElementById("hour_next").textContent--;
}

function hour_next(){

    var hour_prev= document.getElementById("hour_prev").textContent;
    var hour_selected= document.getElementById("hour_selected").textContent;
    var hour_next= document.getElementById("hour_next").textContent;

    if(hour_prev >= 24)
        document.getElementById("hour_prev").textContent= 0;
    else
        document.getElementById("hour_prev").textContent++;

    if(hour_selected >= 24)
        document.getElementById("hour_selected").textContent= 0;
    else
        document.getElementById("hour_selected").textContent++;

    if(hour_next >= 24)
        document.getElementById("hour_next").textContent= 0;
    else
        document.getElementById("hour_next").textContent++;
}

function minute_prev(){

    var minute_prev= document.getElementById("minute_prev").textContent;
    var minute_selected= document.getElementById("minute_selected").textContent;
    var minute_next= document.getElementById("minute_next").textContent;

    if(minute_prev <= 0)
        document.getElementById("minute_prev").textContent= 59;
    else
        document.getElementById("minute_prev").textContent--;

    if(minute_selected <= 0)
        document.getElementById("minute_selected").textContent= 59;
    else
        document.getElementById("minute_selected").textContent--;

    if(minute_next <= 0)
        document.getElementById("minute_next").textContent= 59;
    else
        document.getElementById("minute_next").textContent--;
}

function minute_next(){

    var minute_prev= document.getElementById("minute_prev").textContent;
    var minute_selected= document.getElementById("minute_selected").textContent;
    var minute_next= document.getElementById("minute_next").textContent;

    if(minute_prev >= 59)
        document.getElementById("minute_prev").textContent= 0;
    else
        document.getElementById("minute_prev").textContent++;

    if(minute_selected >= 59)
        document.getElementById("minute_selected").textContent= 0;
    else
        document.getElementById("minute_selected").textContent++;

    if(minute_next >= 59)
        document.getElementById("minute_next").textContent= 0;
    else
        document.getElementById("minute_next").textContent++;
}




function resetIntervalPicker(max, intervalStep)
{
    document.getElementById("interval_prev").textContent= max;
    document.getElementById("interval_selected").textContent= intervalStep;
    document.getElementById("interval_next").textContent= intervalStep + intervalStep;
}

function interval_prev(max, intervalStep){
                
    var interval_prev= document.getElementById("interval_prev").textContent;
    var interval_selected= document.getElementById("interval_selected").textContent;
    var interval_next= document.getElementById("interval_next").textContent;

    if(interval_prev <= intervalStep)
        document.getElementById("interval_prev").textContent= max;
    else
        document.getElementById("interval_prev").textContent-= intervalStep;

    if(interval_selected <= intervalStep)
        document.getElementById("interval_selected").textContent= max;
    else
        document.getElementById("interval_selected").textContent-= intervalStep;

    if(interval_next <= intervalStep)
        document.getElementById("interval_next").textContent= max;
    else
        document.getElementById("interval_next").textContent-= intervalStep;
}

function interval_next(max, intervalStep){

    var interval_prev= document.getElementById("interval_prev").textContent;
    var interval_selected= document.getElementById("interval_selected").textContent;
    var interval_next= document.getElementById("interval_next").textContent;

    if(interval_prev >= max)
        document.getElementById("interval_prev").textContent= intervalStep;
    else
        document.getElementById("interval_prev").textContent= parseInt(document.getElementById("interval_prev").textContent) + intervalStep;

    if(interval_selected >= max)
        document.getElementById("interval_selected").textContent= intervalStep;
    else
        document.getElementById("interval_selected").textContent= parseInt(document.getElementById("interval_selected").textContent) + intervalStep;

    if(interval_next >= max)
        document.getElementById("interval_next").textContent= intervalStep;
    else
        document.getElementById("interval_next").textContent= parseInt(document.getElementById("interval_next").textContent) + intervalStep;
}

//
//function resetTimepicker()
//{
//    document.getElementById("hour_prev").textContent= 24;
//    document.getElementById("hour_selected").textContent= 0;
//    document.getElementById("hour_next").textContent= 1;
//    
//    document.getElementById("minute_prev").textContent= 59;
//    document.getElementById("minute_selected").textContent= 0;
//    document.getElementById("minute_next").textContent= 1;
//}
//
//function hour_prev(){
//                
//    var hour_prev= document.getElementById("hour_prev").textContent;
//    var hour_selected= document.getElementById("hour_selected").textContent;
//    var hour_next= document.getElementById("hour_next").textContent;
//
//    if(hour_prev <= 0)
//        document.getElementById("hour_prev").textContent= 24;
//    else
//        document.getElementById("hour_prev").textContent--;
//
//    if(hour_selected <= 0)
//        document.getElementById("hour_selected").textContent= 24;
//    else
//        document.getElementById("hour_selected").textContent--;
//
//    if(hour_next <= 0)
//        document.getElementById("hour_next").textContent= 24;
//    else
//        document.getElementById("hour_next").textContent--;
//}
//
//function hour_next(){
//
//    var hour_prev= document.getElementById("hour_prev").textContent;
//    var hour_selected= document.getElementById("hour_selected").textContent;
//    var hour_next= document.getElementById("hour_next").textContent;
//
//    if(hour_prev >= 24)
//        document.getElementById("hour_prev").textContent= 0;
//    else
//        document.getElementById("hour_prev").textContent++;
//
//    if(hour_selected >= 24)
//        document.getElementById("hour_selected").textContent= 0;
//    else
//        document.getElementById("hour_selected").textContent++;
//
//    if(hour_next >= 24)
//        document.getElementById("hour_next").textContent= 0;
//    else
//        document.getElementById("hour_next").textContent++;
//}
//
//function minute_prev(){
//
//    var minute_prev= document.getElementById("minute_prev").textContent;
//    var minute_selected= document.getElementById("minute_selected").textContent;
//    var minute_next= document.getElementById("minute_next").textContent;
//
//    if(minute_prev <= 0)
//        document.getElementById("minute_prev").textContent= 59;
//    else
//        document.getElementById("minute_prev").textContent--;
//
//    if(minute_selected <= 0)
//        document.getElementById("minute_selected").textContent= 59;
//    else
//        document.getElementById("minute_selected").textContent--;
//
//    if(minute_next <= 0)
//        document.getElementById("minute_next").textContent= 59;
//    else
//        document.getElementById("minute_next").textContent--;
//}
//
//function minute_next(){
//
//    var minute_prev= document.getElementById("minute_prev").textContent;
//    var minute_selected= document.getElementById("minute_selected").textContent;
//    var minute_next= document.getElementById("minute_next").textContent;
//
//    if(minute_prev >= 59)
//        document.getElementById("minute_prev").textContent= 0;
//    else
//        document.getElementById("minute_prev").textContent++;
//
//    if(minute_selected >= 59)
//        document.getElementById("minute_selected").textContent= 0;
//    else
//        document.getElementById("minute_selected").textContent++;
//
//    if(minute_next >= 59)
//        document.getElementById("minute_next").textContent= 0;
//    else
//        document.getElementById("minute_next").textContent++;
//}
//
//
//
//
//function resetIntervalPicker(max, intervalStep)
//{
//    document.getElementById("interval_prev").textContent= max;
//    document.getElementById("interval_selected").textContent= 0;
//    document.getElementById("interval_next").textContent= intervalStep;
//}
//
//function interval_prev(max, intervalStep){
//                
//    var interval_prev= document.getElementById("interval_prev").textContent;
//    var interval_selected= document.getElementById("interval_selected").textContent;
//    var interval_next= document.getElementById("interval_next").textContent;
//
//    if(interval_prev <= 0)
//        document.getElementById("interval_prev").textContent= max;
//    else
//        document.getElementById("interval_prev").textContent-= intervalStep;
//
//    if(interval_selected <= 0)
//        document.getElementById("interval_selected").textContent= max;
//    else
//        document.getElementById("interval_selected").textContent-= intervalStep;
//
//    if(interval_next <= 0)
//        document.getElementById("interval_next").textContent= max;
//    else
//        document.getElementById("interval_next").textContent-= intervalStep;
//}
//
//function interval_next(max, intervalStep){
//
//    var interval_prev= document.getElementById("interval_prev").textContent;
//    var interval_selected= document.getElementById("interval_selected").textContent;
//    var interval_next= document.getElementById("interval_next").textContent;
//
//    if(interval_prev >= max)
//        document.getElementById("interval_prev").textContent= 0;
//    else
//        document.getElementById("interval_prev").textContent= parseInt(document.getElementById("interval_prev").textContent) + intervalStep;
//
//    if(interval_selected >= max)
//        document.getElementById("interval_selected").textContent= 0;
//    else
//        document.getElementById("interval_selected").textContent= parseInt(document.getElementById("interval_selected").textContent) + intervalStep;
//
//    if(interval_next >= max)
//        document.getElementById("interval_next").textContent= 0;
//    else
//        document.getElementById("interval_next").textContent= parseInt(document.getElementById("interval_next").textContent) + intervalStep;
//}