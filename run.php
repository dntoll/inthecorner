<?php

require_once("dependencies/SVGImage.php");


function divideIntoSubstrings($longString, $maxCharLen) {
	
	$ret = array();

	if (mb_strlen($longString) <= $maxCharLen) {
		
		$ret[] = $longString;
		return $ret;
	} else {
		$maxTextThatFits = mb_substr($longString,0, $maxCharLen);
		
		$cutAt = mb_strrpos($maxTextThatFits, " ");
		if ($cutAt === FALSE) {
			$ret[] = $longString;
			return $ret;
		}
		
		
		$ret[] = mb_substr($longString, 0, $cutAt);
		//do it for the rest
		$rest = trim(mb_substr($longString, $cutAt));
		
		$end = divideIntoSubstrings($rest, $maxCharLen);
		$ret = array_merge($ret, $end);

		
		return $ret;
	}
}

function printLineWrapped($svgImage, $x, $y, $lineHeight, $text, $maxDescriptionLength, $textSize, $font, $textDecorator = "") {
	$substrings = divideIntoSubstrings($text, $maxDescriptionLength);
	
	foreach($substrings as $str) {
		$svgImage->drawText($x . "mm",  $y . "mm", $str, $textSize, $font, $textDecorator); //Kortets förklaring
		$y += $lineHeight;
	}
}

function handleCard(array $input, $targetFile) {
	$cardWidth = 60;
	$cardHeight = 80;
	$edge = 1; //mm around the edge (for cutting)
	$maxDescriptionLength = 38;
	$lineHeight = 4;

	$titleLineHeight = 8;
	$maxTitleLength = 16;

	$titleFont = "Indie Flower";
	$descriptionFont = "sans-serif";
	
	$scenNummer = intval($input[0]);
	$scen = $scenNummer . ". " . $input[1];
	$title = $input[2];
	$description = $input[5];
	if (trim($input[3]) !== "")
		$citat = "\"". trim($input[3])."\"";
	else 
		$citat = "";

	switch($scenNummer) {
		case 1 : $color = "blue"; $scenTextColor = "white"; break;
		case 2 : $color = "green"; $scenTextColor = "white"; break;
		case 3 : $color = "red"; $scenTextColor = "black"; break;
		case 4 : $color = "yellow"; $scenTextColor = "black"; break;
		case 5 : $color = "black"; $scenTextColor = "white"; break;
	}
	


	$lowerpart = $cardHeight/4;
	
	$svgImage = new \view\SVGImage("{$cardWidth}mm", "{$cardHeight}mm");

	
	$svgImage->drawFrame($edge, $edge, ($cardWidth-$edge) . "mm", ($cardHeight-$edge) . "mm"); 
	$svgImage->drawBackground($edge, $edge, 35, ($cardHeight-$edge) . "mm", $color);
	$svgImage->drawVerticalText(16, 20, $scen, $scenTextColor); //Kortets scen
	

	printLineWrapped($svgImage, 12, 10, $titleLineHeight, $title, $maxTitleLength, 25, $titleFont);
	printLineWrapped($svgImage, 12, 40, $lineHeight, $citat, $maxDescriptionLength, 12, $titleFont, 'font-style = "italic"');
	
	$atY = $cardHeight - $edge -$lowerpart;
	printLineWrapped($svgImage, 12, $atY, $lineHeight, $description, $maxDescriptionLength, 10, $descriptionFont);
	//$svgImage->toOutputBuffer(); //echo the image


	fwrite($targetFile, $svgImage->getOutputBuffer());
	
}


$inputFileName = "data/Alla kort - Kort.csv";
$outputFileName = "output/index.html";
$inputFile = fopen($inputFileName, "r");
$targetFile = fopen($outputFileName, "w");

$htmlStart = '<!DOCTYPE html>
<html lang="en">
  <head>
    <meta charset="utf-8">
    <title>title</title>
    <link rel="stylesheet" href="style.css">
	<link href="https://fonts.googleapis.com/css?family=Indie+Flower" rel="stylesheet">
    <script src="script.js"></script>
  </head>
  <body>';
$htmlend ='
  </body>
</html>';

fwrite($targetFile, $htmlStart);
$input = fgetcsv($inputFile, 1024);
while (($input = fgetcsv($inputFile, 1024)) !== FALSE) {
	handleCard($input, $targetFile);
}
fwrite($targetFile, $htmlend);
fclose($inputFile);
fclose($targetFile);

