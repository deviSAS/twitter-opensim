<!DOCTYPE html PUBLIC "-//W3C//DTD XHTML 1.0 Transitional//EN" "http://www.w3.org/TR/xhtml1/DTD/xhtml1-transitional.dtd">
<html xmlns="http://www.w3.org/1999/xhtml">
<head>
<meta http-equiv="Content-Type" content="text/html; charset=utf-8" />
<title>Twitter for OpenSim</title>
<style type="text/css">
body {
	background: #C0DEED url('templates/default/bg-clouds.png') repeat-x;
	font: .75em 'Lucida Grande',sans-serif;
	padding: 0; margin: 0;
}
#container {
	width: 800px;
	margin: 100px auto 0 auto;
	background-color: #FFF;
	min-height:300px;
	padding: 10px;
	border-radius: 5px 5px 0 0;
	-moz-border-radius: 5px 5px 0 0;
	-webkit-border-top-left-radius: 5px;
	-webkit-border-top-right-radius: 5px;
	border-top-left-radius: 5px 5px;
	border-top-right-radius: 5px 5px;
}
h1 {
	line-height: 30px;
	font-size: 20px;
	padding: 10px; margin: 0 0 10px 0;
	background-color:#ECF4F2;
	display: block;
}
p { 
	font-size: 14px;
	padding: 0 10px;
}

#footer {
	position: fixed;
	bottom: 0; left: 0;
	width: 100%;
	background-color:#333;
	color: #FFF;
	padding: 10px;
	margin: 0;
}

a, a:hover, a:visited, img {
  text-decoration: none !important;
  border: 0 !important;
  list-style-type: none;
  color: inherit;
}

</style>
</head>

<body>
<div id="container">
	<h1><?php echo $title; ?></h1>
    <p><?php echo $message; ?></p>
</div>
<div id="footer">
	<span>Powered by <a href="http://devi.com.co/">devi</a> - default theme. 2011.</span>
</div>
</body>
</html>
