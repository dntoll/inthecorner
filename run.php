<?php

require_once("dependencies/SVGImage.php");


function handleCard(array $input, $targetFile) {
	
	$svgImage = new \view\SVGImage("60mm", "40mm");
	$svgImage->drawFrame(0, 0, "59mm", 299); //draw a diagonal line
	$svgImage->drawText(10, 20, $input[0]); //Kortets scen
	$svgImage->drawText(10, 40, $input[1]); //Kortets Titel
	$svgImage->drawText(10, 60, $input[2]); //Kortets Citat
	//$svgImage->drawText(10, 80, $input[3]); //Kortets typ
	$svgImage->drawText(10, 120, $input[4]); //Kortets förklaring
	//$svgImage->toOutputBuffer(); //echo the image


	fwrite($targetFile, $svgImage->getOutputBuffer());
	
}


$inputFileName = "data/Alla kort - Kort.csv";
$outputFileName = "output/index.html";
$inputFile = fopen($inputFileName, "r");
$targetFile = fopen($outputFileName, "w");

while (($input = fgetcsv($inputFile, 1024)) !== FALSE) {
	handleCard($input, $targetFile);
}
fclose($inputFile);
fclose($targetFile);

