var g_Interval=1;var g_PersonCount=1000;var g_Timer;var running=false;function beginRndNum(trigger){if(running){running=false;clearTimeout(g_Timer);$(trigger).val("閲嶆柊鎶藉");$('#ResultNum').css('color','red');$('#yt0').css('visibility','visible');}
else{running=true;$('#ResultNum').css('color','black');$(trigger).val("鍋滄鎶藉");$('#yt0').css('visibility','hidden');beginTimer();}}
function updateRndNum(){var num=Math.floor(Math.random()*g_PersonCount+1);num=fillZero(num,3);$('#ResultNum').val(num);}
function beginTimer(){g_Timer=setTimeout(beat,g_Interval);}
function beat(){g_Timer=setTimeout(beat,g_Interval);updateRndNum();}
function fillZero(num,digits){num=String(num);var length=num.length;if(num.length<digits){for(var i=0;i<digits-length;i++){num="0"+num;}}
return num;}