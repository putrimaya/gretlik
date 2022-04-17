<?php
function bitly($url) {
	$username = 'DODHELES OG'; // your bitly account
	$api = 'ece7f89a9619c07a9992f91a2ded1e6069c0818c'; // your bitly password
	
	return getContent("http://api.bit.ly/v3/shorten?format=txt&longUrl=$url&login=$username&apiKey=$api");
}

function tinyurl($url) {
	return getContent("http://tinyurl.com/api-create.php?url=$url");
}

function google($url) {
	$data = getContent("http://ggl-shortener.appspot.com/?url=$url");
	$json = json_decode($data);

    return $json->short_url;
}

function isgd($url) {
	return getContent("http://is.gd/api.php?longurl=$url");
}

/**
 * Helper function for getting remote content
 *
 * Using cURL or file_get_contents
 */
function getContent($url) {
	$content = '';
	
	// use cURL
	if (function_exists('curl_init')) {
		$ch = curl_init($url);
		curl_setopt($ch, CURLOPT_HEADER, false);
		curl_setopt($ch, CURLOPT_RETURNTRANSFER, true);
		curl_setopt($ch, CURLOPT_FOLLOWLOCATION, true);
		$content = curl_exec($ch);
		curl_close($ch);
	}
	// use file_get_contents
	elseif (ini_get('allow_url_fopen')) {
		$content = file_get_contents($url);
	}
	
	return $content;
}

if (!empty($_GET['url'])) {
	$url = rawurlencode($_GET['url']);
	
	$result = array();
	$result['bitly'] = bitly($url);
	$result['tinyurl'] = tinyurl($url);
	$result['google'] = google($url);
	$result['isgd'] = isgd($url);
	
	echo json_encode($result);
	
	exit();
}
?>
<!DOCTYPE html>
<html lang="en">
<head>
<meta charset="utf-8" />
<title>Multiple URL Shortener</title>

<!--[if IE]>
<script src="http://html5shiv.googlecode.com/svn/trunk/html5.js"></script>
<![endif]-->

<!--[if lt IE 9]>
<script src="http://ie7-js.googlecode.com/svn/version/2.1(beta4)/IE9.js"></script>
<![endif]-->

<style>
/* yui 2 reset */
body,div,dl,dt,dd,ul,ol,li,h1,h2,h3,h4,h5,h6,pre,form,fieldset,input,textarea,p,blockquote,th,td{margin:0;padding:0}table{border-collapse:collapse;border-spacing:0}fieldset,img{border:0}address,caption,cite,code,dfn,em,strong,th,var{font-style:normal;font-weight:normal}ol,ul{list-style:none}caption,th{text-align:left}h1,h2,h3,h4,h5,h6{font-size:100%;font-weight:normal}q:before,q:after{content:''}abbr,acronym{border:0}

body{font:13px/1.5em Verdana,Arial,Helvetica,sans-serif;color:#333}
#container{width:500px;margin:30px auto}
header,section,footer{display:block}

header{margin-bottom:30px}
header h1{font:italic 30px Georgia,"Times New Roman",Times,serif;color:#888;text-align:center}

section{margin-bottom:30px}

#form{float:left;margin-bottom:20px}
input{float:left;border:1px solid #6baff0}
#url{width:350px;height:18px;padding:5px 15px;color:#999;margin-right:10px;-webkit-border-radius:30px;-moz-border-radius:30px;border-radius:30px}
#url:hover,#url:focus{background:#fefeec;color:#333}

#submit{height:30px;border-top:1px solid #96d1f8;background:#65a9d7;background:-webkit-gradient(linear,left top,left bottom,from(#9ec8f5),to(#65a9d7));background:-moz-linear-gradient(top,#9ec8f5,#65a9d7);padding:5px 10px;-webkit-border-radius:30px;-moz-border-radius:30px;border-radius:30px;color:#fff;font-size:13px;font-family:Helvetica,Arial,Sans-Serif}
#submit:hover{border-top-color:#3f98d4;background:#3f98d4;color:#fff}
#submit:active{border-top-color:#3f98d4;background:#3f98d4}

#shorturls{clear:both}
#shorturls p{margin-bottom:10px;line-height:1.5em}
#shorturls strong{font-size:18px;font-weight:normal;width:150px;display:inline-block;color:#4096ee}

footer{margin:15px auto}
</style>
</head>

<body>
<div id="container">
	<header>
		<h1>Multiple URL Shortener</h1>
	</header>

	<section>
		<form action="" method="get" id="form">
			<input type="text" name="url" id="url" value="Enter URL here..." onfocus="if(this.value==this.defaultValue)this.value=''" onblur="if(this.value=='')this.value=this.defaultValue" />
			<input type="submit" id="submit" value="Shorten it!" />
		</form>
		<div id="shorturls">
			<p><strong>Bit.ly</strong> <span id="bitly" class="result"></span></p>
			<p><strong>TinyURL</strong> <span id="tinyurl" class="result"></span></p>
			<p><strong>Google</strong> <span id="google" class="result"></span></p>
			<p><strong>Is.gd</strong> <span id="isgd" class="result"></span></p>
		</div>
	</section>

	<footer>
		Created by Rilwis. <a href="http://www.deluxeblogtips.com/2010/06/multiple-url-shortener-page.html">Return to article</a>.
	</footer>
</div> <!-- #container -->

<script src="http://ajax.googleapis.com/ajax/libs/jquery/1.4.2/jquery.min.js"></script>
<script>

$('#form').submit(function(){
	// show loading icon
	$('.result').html('<img src="loading.gif" />');

	var url = $('#url').val();
	
	$.getJSON('', {url: url}, function(data){
		$('#bitly').text(data.bitly);
		$('#tinyurl').text(data.tinyurl);
		$('#google').text(data.google);
		$('#isgd').text(data.isgd);
	});
	
	return false;
});

</script>
</body>
</html>