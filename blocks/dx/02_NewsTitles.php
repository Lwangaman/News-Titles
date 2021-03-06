<?php

require_once("NewsTitles/config.php");

global $_FN;

if(!function_exists("is_news_admin")){
  function is_news_admin(){
  	$user = getparam("myforum",PAR_COOKIE,SAN_FLAT);
  	if ( $user != "" )
  	{
  		if ( (is_admin()||user_in_group($user,"newsadmin")) && versecid($user) )
  		{
  			return true;
  		}
  	}
  	return false;
  }
}
$news_section = find_section("news");
$topbox = ($_NEWSTITLES_CFG['use-topbox'] == 1) ? "<div id=\"newstitles-newnews\"></div><hr />" : "";
$newsbar = ($_NEWSTITLES_CFG['use-jqueryui'] == 0) ?
   "<table id=\"newstitles-verticalbar-table\">
			<tr><td class=\"newsbar-up-fastest\">^</td></tr>
			<tr><td class=\"newsbar-up-faster midbuttons\">&middot;</td></tr>
			<tr><td class=\"newsbar-up-fast midbuttons\">&middot;</td></tr>
			<tr><td class=\"newsbar-stop midbuttons\">X</td></tr>
			<tr><td class=\"newsbar-down-fast midbuttons\">&middot;</td></tr>
			<tr><td class=\"newsbar-down-faster midbuttons\">&middot;</td></tr>
			<tr><td class=\"newsbar-down-fastest\">v</td></tr>
		</table>"
    : "";

echo $topbox;

?>

<div id="newstitles-newsbox">
	<div id="newstitles-wrapper"><marquee id="newstitles-view"></marquee></div>
  <div id="newstitles-verticalbar"><?php echo $newsbar; ?></div>
</div>
<?php if ($_FN["vmod"]!=$news_section) { echo "<div id=\"gotonews\"><a href=\"index.php?mod=news\">Vai alle News</a></div>"; } ?>
<?php if (is_news_admin()) { echo "<div id=\"addnews\"><a href=\"".fn_rewritelink("index.php?mod=news&amp;op=news")."\">"._ADDNEWS."</a></div>"; } ?>

<script type="text/javascript">

if (typeof jQuery == 'undefined') {  
  var headID = document.getElementsByTagName("head")[0];         
  var newScript1 = document.createElement('script');
  newScript1.type = 'text/javascript';
  newScript1.src = 'http://ajax.googleapis.com/ajax/libs/jquery/1/jquery.min.js';
  headID.appendChild(newScript1);
}
if (typeof jQuery.ui == 'undefined') {
  var headID = document.getElementsByTagName("head")[0];         
  var newScript2 = document.createElement('script');
  newScript2.type = 'text/javascript';
  newScript2.src = 'http://ajax.googleapis.com/ajax/libs/jqueryui/1/jquery-ui.min.js';
  headID.appendChild(newScript2);
}

jQuery(document).ready(function(){

  // according to whether the block is on the right or left side of the page, keep the scrollbar external
	if($("#newstitles-newsbox").offset().left < 100){
		latopagina = "sx";
    $("#newstitles-wrapper").css({'right':'1px'});
		$("#newstitles-verticalbar").css({'left':'1px'});		
	}
  else {
		latopagina = "dx";
    $("#newstitles-wrapper").css({'left':'1px'});
		$("#newstitles-verticalbar").css({'right':'1px'});
  }

<?php
echo ($_NEWSTITLES_CFG['use-jqueryui'] == 0) ? "var a = document.createElement('link'),
        b = document.getElementsByTagName('head')[0];     
    a.rel = 'stylesheet';
    a.type = 'text/css';
    a.href = 'blocks/'+latopagina+'/NewsTitles/style.css';
    b.appendChild(a);" : "var a = document.createElement('link'),
        b = document.getElementsByTagName('head')[0];     
    a.rel = 'stylesheet';
    a.type = 'text/css';
    a.href = 'blocks/'+latopagina+'/NewsTitles/style-ui.css';
    b.appendChild(a);";
?>

if( $("#newstitles-verticalbar-table").length!=0 ) {
	$("#newstitles-verticalbar-table td.newsbar-up-fastest").hover(function(){
	    $("#newstitles-view").trigger("start").attr({ direction: "down", scrollAmount: 10});
	});
	$("#newstitles-verticalbar-table td.newsbar-up-faster").hover(function(){
	    $("#newstitles-view").trigger("start").attr({ direction: "down", scrollAmount: 4});
	});
	$("#newstitles-verticalbar-table td.newsbar-up-fast").hover(function(){
	    $("#newstitles-view").trigger("start").attr({ direction: "down", scrollAmount: 1});
	});
	$("#newstitles-verticalbar-table td.newsbar-stop").hover(function(){
	    $("#newstitles-view").trigger("stop");
	});
	$("#newstitles-verticalbar-table td.newsbar-down-fast").hover(function(){
	    $("#newstitles-view").trigger("start").attr({ direction: "up", scrollAmount: 1});
	});
	$("#newstitles-verticalbar-table td.newsbar-down-faster").hover(function(){
	    $("#newstitles-view").trigger("start").attr({ direction: "up", scrollAmount: 4});
	});
	$("#newstitles-verticalbar-table td.newsbar-down-fastest").hover(function(){
	    $("#newstitles-view").trigger("start").attr({ direction: "up", scrollAmount: 10});
	});
}
else {
	$("#newstitles-verticalbar").slider({
			orientation: "vertical",
			min: 0,
			max: 100,
			value: 40,
			slide: function( event, ui ) {
        if (ui.value>45&&ui.value<55){ $( "#newstitles-view" ).trigger("stop"); }
        else {
  				b = (ui.value>50) ? "down" : "up";
          if (ui.value>50&&ui.value<90) { c =  (ui.value / (100 - ui.value)) }
          if (ui.value>=90) { c = (ui.value / 10) }
          if (ui.value<50&&ui.value>10) { c = ((100 - ui.value) / ui.value) }
          if (ui.value<=10) { c = ((100-ui.value) / 10 ) }
          $( "#newstitles-view" ).trigger("start").attr({ direction: b, scrollAmount: c });
        }
			}
		});
  $("#newstitles-newsbox .ui-slider").removeClass("ui-corner-all");
  sliderwidth = ( $("#newstitles-newsbox .ui-slider").width() - 2);
  $("#newstitles-newsbox .ui-slider-handle").append("<span class='ui-icon ui-icon-grip-dotted-horizontal'></span>");   
}

if( $("#newstitles-newnews").length!=0 ){
  $("#newstitles-newnews").hide();
  $.get("/include/ajax/getnewstitles.php", {latestnews: "true"}, function(newnewsdata){ $('#newstitles-newnews').html(newnewsdata).fadeIn(600); });
}
  
  $("#newstitles-view").hide();
  $.get("/include/ajax/getnewstitles.php", function(mydata){
  	if(mydata!=""){ $('#newstitles-view').html(mydata).fadeIn(600);}
	  else{$('#newstitles-view').html("THERE ARE NO NEWS TITLES IN THIS LANGUAGE<br /><br />NO HAY TITULOS DE NOTICIAS EN ESTO IDIOMA<br /><br />IL N'Y A PAS DE TITRES DE NOUVELLES DANS CETTE LANGUE").fadeIn(600);}
  });
  $("#newstitles-view").attr({direction:"up",behaviour:"scroll",scrollamount:1,height:200});

});
</script>