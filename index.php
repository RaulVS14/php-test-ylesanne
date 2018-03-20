<?php
$curl = curl_init();
curl_setopt_array($curl, array(
    CURLOPT_URL => $_SERVER['SERVER_NAME'].$_SERVER['REQUEST_URI']."api/getData",
    CURLOPT_RETURNTRANSFER => true,
    CURLOPT_TIMEOUT => 30,
    CURLOPT_HTTP_VERSION => CURL_HTTP_VERSION_1_1,
    CURLOPT_CUSTOMREQUEST => "GET",
    CURLOPT_HTTPHEADER => array(
        "cache-control: no-cache"
    ),
));
$response = curl_exec($curl);
$displayData = [];

if ($response) {
    $displayData = json_decode($response, true);

}
$err = curl_error($curl);

?>
<!doctype html>
<html lang="en">
<head>
	<meta charset="UTF-8">
	<meta name="viewport"
		  content="width=device-width, user-scalable=no, initial-scale=1.0, maximum-scale=1.0, minimum-scale=1.0">
	<meta http-equiv="X-UA-Compatible" content="ie=edge">
	<title>PHP Test Ylesanne</title>
	<style>
		*{
			box-sizing: border-box;
		}
		body,html{
			width: 100%;
			height: 100%;
			margin: 0;
			padding: 0;
		}
		a {
			text-decoration: none;
			color: #333;
		}
		#header{
			text-align: center;
			padding: 20px 0;
			height: 180px;
			display: flex;
			flex-direction: column;
			justify-content: center;
			align-items: center;
		}

		#header a{
			width: 125px;
			border: 1px solid black;
			border-radius: 5px;
			padding: 10px;
		}
		.container{
			width: 100%;
			height: calc(100% - 180px);
			display: flex;
			flex-direction: row;
			flex-wrap: wrap;
			justify-content: center;
			overflow-y: auto;
			overflow-x: hidden;
		}
		.article {
			width: 220px;
			padding: 15px;
		}
		.article .read-more{
			display: block;
			margin: 10px 0;
			font-weight: bold;
		}
		.article .read-more:hover{
			color:green;

		}
	</style>
</head>
<body>
<div id="header">
	<h1>Saved search results</h1>
	<a href="<?=$_SERVER['REQUEST_URI']?>/wikipediaRetrievalScript.php">Mine skripti lingile</a>
</div>
<div class="container">
    <?php $counter = 1;foreach ($displayData as $data): ?>
		<div class="article">
			<h2><span><?= $counter++ ?>.</span> <a href="https://en.wikipedia.org/wiki/<?= urlencode(str_replace(' ', '_',$data["title"])) ?>"><?= $data["title"] ?></a></h2>
			<p><?= $data["snippet"] ?>... <a class="read-more" href="https://en.wikipedia.org/wiki/<?= urlencode(str_replace(' ', '_',$data["title"])) ?>">Read more &raquo;</a></p>
		</div>
    <?php endforeach; ?>
</div>
</body>
</html>
