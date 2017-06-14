<html>

<head>
    <meta charset="utf-8" />
    <title>查詢結果</title>
    <link href="css/bootstrap.min.css" rel="stylesheet">
    <script src="http://libs.baidu.com/jquery/2.0.0/jquery.min.js"></script>
    <script src="js/bootstrap.min.js"></script>
    <meta name="viewport" content="width=device-width, initial-scale=1" />
    <!--[if lte IE 8]><script src="assets/css/ie/html5shiv.js"></script><![endif]-->
    <link rel="stylesheet" href="assets/css/main.css" />
    <!--[if lte IE 9]><link rel="stylesheet" href="assets/css/ie/v9.css" /><![endif]-->
    <!--[if lte IE 8]><link rel="stylesheet" href="assets/css/ie/v8.css" /><![endif]-->
    <!--[if lte IE 8]><script src="assets/css/ie/respond.min.js"></script><![endif]-->
    <link rel="shortcut icon" href="favicon.ico">
</head>

<body>


    <style>
        body {
            background-image: url('img/nccuhsnu.jpg');
            background-repeat: no-repeat;
            background-position: center;
            background-attachment: fixed;
        }

    </style>

<?php

require_once 'login.php';

error_reporting(E_ALL^E_DEPRECATED);

$db_server = mysql_connect($db_hostname, $db_username, $db_password);
mysql_query("SET CHARACTER 'utf8'");
mysql_query("SET NAMES utf8");
mysql_query("SET CHARACTER_SET_CONNECTION");
mysql_query("SET CHARACTER_SET_CLIENT=utf8");
mysql_query("SET CHARACTER_SET_RESULTS=utf8");

if (!$db_server) {die("unable to connect to Mysql:".mysql_error());
}

mysql_select_db($db_database) or die("unable to select database".mysql_error());

//print_r($_POST['class']);

if (isset($_POST['name']) && isset($_POST['class'])) {
	$i   = 0;
	$sum = null;
	if (($_POST['name'] != '') && ($_POST['class'] != '')) {

		$name = sanitizeString($_POST['name']);
		foreach ($_POST['class'] as $class) {

			++$i;
			if ($i < count($_POST['class'])) {$sum = $sum." 課程類別 = '$class'" ." OR";
			} else { $sum                          = $sum." 課程類別 = '$class'";
			}
		}
		$query = "SELECT * FROM all_course WHERE 教授姓名='$name' AND ($sum)";

		if (!mysql_query($query, $db_server)) {
			echo "SELECT failed: $query<br/>" .
			mysql_error()."<br/><br/>";
		}
	} elseif (($_POST['name'] == '') && ($_POST['class'] != '')) {
		$class = implode(',', $_POST['class']);
		foreach ($_POST['class'] as $class) {

			++$i;
			if ($i < count($_POST['class'])) {$sum = $sum." 課程類別 = '$class'" ." OR";
			} else { $sum                          = $sum." 課程類別 = '$class'";
			}
		}
		$query = "SELECT * FROM all_course WHERE ($sum)";

		if (!mysql_query($query, $db_server)) {
			echo "SELECT failed: $query<br/>" .
			mysql_error()."<br/><br/>";
		}
	} else {
		$query = "SELECT * FROM all_course";

	}
} else {
	if ($_POST['name'] != '') {

		$name  = sanitizeString($_POST['name']);
		$query = "SELECT * FROM all_course WHERE 教授姓名='$name'";
	} else {
		$query = "SELECT * FROM all_course WHERE 教授姓名='叫獸'";
	}
}

$result = mysql_query($query);

if (!$result) {die("cannot connect:".mysql_error());
}

echo <<<_END
<div  align="center" >
<table class="table table-hover">
  <div class="page-header">
  <h1 align="center">查詢結果</h1>
</div>

     <thead>
      <tr>
         <th>課程類別</th>
         <th>開課學年度</th>
         <th>課程名稱</th>
         <th>教授姓名</th>
         <th>可不可翹</th>
         <th>評分標準</th>
         <th>功課多寡</th>
         <th>熱門程度</th>
         <th>備註</th>

      </tr>
   </thead>
</div>
_END
;
$rows = mysql_num_rows($result);
echo " <tbody>";

for ($j = 0; $j < $rows; ++$j) {
	$row = mysql_fetch_row($result);
	if ($row[1] == 0) {
		$x = '-';
	} else {
		$x = $row[1];
	}
	echo
	" <tr>
         <td>$row[0]</td>
         <td>$x</td>
         <td>$row[2]</td>
         <td>$row[3]</td>
         <td>$row[4]</td>
         <td>$row[5]</td>
         <td>$row[6]</td>
         <td>$row[7]</td>
         <td>$row[8]</td>
      </tr>
";

}
echo " </tbody></table>";
function sanitizeString($var) {
	$var = strip_tags($var);
	$var = htmlentities($var);
	$var = stripslashes($var);
	return mysql_real_escape_string($var);
}

/*function sanitizeMysql($var2)
{
$var = mysql_real_escape_string($var);
$var = sanitizeString($var)
return $var ;
}
 */

mysql_close($db_server);
/*
function get_post($var)
{

return mysql_real_escape_string($_POST[$var]);
}
 */
echo
<<<_END
<div>
<button type="bottom" class="btn btn-default btn-lg btn-block"  onclick="location.href='index.html'" " >重新查詢</button><br/>
</div>
_END;
?>
<div>
            <h5 style="text-align:center;margin-top:13%;">NCCUHSNU ＠ by 陳樺威</h5>
        </div>

</body>

</html>
