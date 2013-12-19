<!DOCTYPE HTML PUBLIC "-//W3C//DTD HTML 4.01//EN" "http://www.w3.org/TR/html4/strict.dtd">
<?php

include_once ('../config.php');
session_start();
if (!isset($_SESSION['USER_AUTH_LOGIN'])) {
    header('Location: ' . URL_PREFIX);
}

?>
<html>
<head>
    <meta http-equiv="Content-Type" content="text/html; charset=utf-8" />
    <title>webhomebudget: Panel</title>

    <link rel="stylesheet" type="text/css" href="../Libs/ExtJS4.1.1a/resources/css/ext-all-gray.css">
    <link rel="stylesheet" type="text/css" href="../Css/custom.css">
    
    <script type="text/javascript" src="../Libs/ExtJS4.1.1a/bootstrap.js"></script>
    <script type="text/javascript" src="config.js"></script>
    <script type="text/javascript" src="utils.js"></script>
    <script type="text/javascript" src="app.js"></script>
    <script type="text/javascript" src="../Libs/ExtJS4.1.1a/locale/ext-lang-pl.js"></script>
</head>
<body></body>
</html>