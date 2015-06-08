<?php header("Last-Modified: " . gmdate("D, d M Y H:i:s") . " GMT");
header("Cache-Control: no-store, no-cache, must-revalidate"); // HTTP/1.1
header("Cache-Control: post-check=0, pre-check=0", false);
header("Pragma: no-cache"); // HTTP/1.0
header("Expires: Sat, 26 Jul 1997 05:00:00 GMT"); // Date in the past
////http://stackoverflow.com/questions/1907653/how-to-force-page-not-to-be-cached-in-php
?>
<!DOCTYPE html>
<html>
<head lang="en">
    <meta charset="UTF-8">
    <meta name="author" content="Robert Jackson">
    <meta name="description" content="">
    <title><?php echo $this->title ?></title>
    <meta name="viewport" content="width=device-width, initial-scale=1.0" />

    <link rel="stylesheet" href="src/MyApp/css/foundation.css" />
    <link rel="stylesheet" href="src/MyApp/css/webicons.css">
    <link href='http://fonts.googleapis.com/css?family=Baumans' rel='stylesheet' type='text/css'>
    <script src="src/MyApp/js/vendor/jquery.js"></script>
    <script src="src/MyApp/js/vendor/modernizr.js"></script>
</head>
<body>
<span class="alertText"></span>
