<?php  

/*

* Change the value of 'num' in the request url to change the number of fetched results.

* 'index'(second argument in find function) is set to 'null' by default, so it is required to be set to an integer


*/

require_once('dom_parser.php');

function fetch_data(){

if(isset($_REQUEST['go'])){

$name = str_replace(" ","+",$_REQUEST['search_val']);

// Create DOM from URL or file
$html = file_get_html("https://www.google.co.in/search?q={$name}&num=10");

// Displaying the entire fetched content
//echo $html;

// Displaying single result (second result)
//echo $html->find('div.g',1) . '<br>';

// If the wikipedia image of the searched term exists then display it.

$image_src = $html->find('div._i8d a img',0)->src;
$image_name = $html->find('div._B5d',0)->plaintext;
$image_desc = $html->find('div._zdb',0)->plaintext;

if(isset($image_src)){
	echo "<img class='wiki_image' src='{$image_src}' /><p class='image_desc'>{$image_name} ($image_desc)</p>";
}

$feeds = array();

// Fetching every google result

foreach ($html->find('div.g') as $result) {

	//Fetching the contents of each google result into an array

	$item = array();
	
	$item["title"] = $result->find('h3.r a',0)->plaintext;
	$item["link"] = $result->find('div.s div.kv cite',0)->plaintext;
	$item["description"] = $result->find('div.s span.st',0)->plaintext;

	// Inserting each result into a single array

	$feeds[] = $item;

}

// Displaying all the results from the fetched 

function addhttp($url) {
    if (!preg_match("~^(?:f|ht)tps?://~i", $url)) {
        $url = "http://" . $url;
    }
    return $url;
}


foreach ($feeds as $feed) {
	
	if(isset($feed["title"]) && isset($feed["link"]) && isset($feed["description"])){

		$link = addhttp($feed['link']);

		$data =<<<DELIMITER
		<a href='{$link}' target="_blank">
			<div class="results">
				<h4>{$feed["title"]}</h4>
				<p>{$feed["description"]}read more....</p>
				<p class="link">{$link}</p>
			</div>
		</a>
DELIMITER;

		echo $data;

	}

}

}
}

?> 

<html>
	<head>
		<title>Mr. Fetcher | Google Crawler</title>
		<meta charset="utf-8"/>
		<style>
			body{
				margin: 0px;
				padding: 0px;
				font-family: sans-serif;
			}
			.search_wrapper{
				height: 430px;
				width: 500px;
				//background-color: #777;
				margin: 0 auto;
			}
			.logo{
				height: 300px;
				width: 300px;
				margin: 0 auto;
				display: block;
			}
			.wiki_image{
				height: 110px;
				width: 110px;
				margin: 0 auto;
				display: block;
				margin-bottom: 10px;
				border-radius: 5px;
			}
			.intro_text{
				width: 350px;
				text-align: center;
				margin: 20px auto 0px;
			}
			.search_field{
				height: 40px;
				width: 400px;
				border-radius: 5px;
				margin-top: 20px;
				font-size: 20px;
			}
			.go_btn{
				height: 40px;
				width: 96px;
				cursor: pointer;
			}
			.results_wrapper{
				height: auto;
				width: 1160px;
				//background-color: #000;
				margin: 50px auto 0px;
			}
			.image_desc{
				text-align: center;
				margin-bottom: 40px;
			}
			.results{
				height: 240px;
				padding: 10px;
				width: 240px;
				//background-color: #777;
				margin-right: 50px;
				float: left;
				text-align: center;
				border-radius: 5px;
				margin-bottom: 50px;
				border: 1px solid #777;
				box-sizing: border-box;
				font-size: 13px;
			}
			.results:nth-child(4){
				height: 220px;
				padding: 10px;
				width: 220px;
				background-color: #777;
				margin-right: 0px;
				float: left;
				text-align: center;
				border-radius: 5px;
				margin-bottom: 50px;
			}
			a{
				text-decoration: none;
				color: #000;
			}
			.link{
				color: #0000ff;
			}
		</style>
	</head>
	<body>
		<div class="search_wrapper">
			<img class="logo" src="./fetcher.jpg"/>
			<p class="intro_text"><b>Mr. Fetcher</b> is best friends with Google!<br>And he can find you results for anyone & anything!</p>
			<form method="post" action="">
				<input class="search_field" name="search_val" type="text">
				<input class="go_btn" type="submit" value="Go" name="go">
			</form>
		</div>
		<div class="results_wrapper">
			<?php fetch_data(); ?>
			<div style="clear: both"></div>
		</div>
	</body>
</html>