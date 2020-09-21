<!DOCTYPE html PUBLIC "-//W3C//DTD XHTML 1.0 Transitional//EN" "http://www.w3.org/TR/xhtml1/DTD/xhtml1-transitional.dtd">
<html xmlns="http://www.w3.org/1999/xhtml">
<head>
<meta http-equiv="Content-Type" content="text/html; charset=utf-8" />
<title>AUSU DEMO | jQuery-Ajax Auto Suggest Plugin</title>
<link rel="stylesheet" type="text/css" href="css/style.css" />
<style>
    div				{margin: 10px; font-family: Arial, Helvetica, sans-serif; font-size:12px; }
	.ausu-suggest	{width: 280px;}
    #wrapper 		{margin-left: auto; position: relative; margin-right: auto; margin-top:75px ;width:  600px;}
    h3 				{font-size: 11px; text-align: center;}
	span 			{font-size: 11px; font-weight: bold}

	a:link			{color: #F06;text-decoration: none;}
	a:visited 		{text-decoration: none;color: #F06;}
	a:hover 		{text-decoration: underline;color: #09F;}
	a:active		{text-decoration: none;color: #09F;}
</style>
<script type="text/javascript" src="http://ajax.googleapis.com/ajax/libs/jquery/1/jquery.min.js"></script>
<script type="text/javascript" src="js/jquery.ausu-autosuggest.min.js"></script>
<script>
$(document).ready(function() {
    $.fn.autosugguest({  
           className: 'ausu-suggest',
          methodType: 'POST',
            minChars: 2,
              rtnIDs: true,
            dataFile: 'data.php'
    });
});
</script>
</head>
<body>
<div>
	AUSU jQuery-Ajax Auto Suggest Demo | <a href="http://discussion.oslund.ca/2011/01/a-simple-jquery-ajax-autosuggest-plugin/"><strong>Download Source</strong></a>.
	<br /><span>Created by Isaac  | <a href="http://www.oslund.ca">oslund.ca</a></span>
</div>
    <div id="wrapper">
       <form action="index.php" method="get">
           <div class="ausu-suggest">
              <input type="text" size="25" value="" name="countries" id="countries" autocomplete="off" />
              <input type="text" size="4" value="" name="countriesID" id="countriesid" autocomplete="off" disabled="disabled" />
              <h3>Where do you want to work?</h3>
           </div>
           <div class="ausu-suggest">
               <input type="text" size="25" value="" name="categories" id="categories" autocomplete="off" />
               <input type="text" size="4" value="" name="categoriesID" id="categoriesID" autocomplete="off" disabled="disabled" />
               <input name="go" type="submit" value="go" style="width:1px;height:1px" />
    		<h3>Doing what?</h3>
          </div>
       </form>
       <div style="clear:both">
        <img src="images/ausu-background-description.jpg" width="600" height="433" alt="AUSU jQuery-Ajax Autosuggesyt Plugin" />
       </div>
    </div>
</body>
</html>