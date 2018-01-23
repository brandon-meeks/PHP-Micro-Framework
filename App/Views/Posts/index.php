<!doctype html>
<html lang="en">
<head>
	<meta charset="UTF-8">
	<title>PHP CMS</title>
</head>
<body>
<h1>Posts Index</h1>

<?php

    foreach ($posts as $key => $value) {
        //print_r($value);
        echo "<h2>" . $value['title'] . "</h2>";
        echo "<p>" . $value['content'] . "</p>";
    }
?>
</body>
</html>