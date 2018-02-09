<?php


namespace view;




/**
 * Creates an SVG image
 *
 * $svgImage = new \view\SVGImage();
 * $svgImage->initialize(100, 100);
 * $svgImage->drawLine(0, 0, 99, 99); //draw a diagonal line
 * $svgImage->toOutputBuffer(); //echo the image
 * 
 * @author Daniel Toll
 * @package view
 */
class SVGImage  {
	private $width;
	private $height;
	private $content; 
	private $color = "black";



	/**
	 * Creates an SVG Strategy object
	 *
	 * Note! that it must be initialized before used
	 */
	public function __construct($width, $height) {
		$this->content = "";
		$this->initialize($width, $height);
	}

	public function setColor($r, $g, $b) {
		$this->color = "rgb($r,$g,$b)";
	}

	/**
	 * Initialize an image to the correct width and height
	 *
	 * @throws Exception if width or height is less than zero
	 * 
	 * @param  Integer $width  Image width > 0
	 * @param  Integer $height Image height > 0
	 * @return void
	 */
	private function initialize($width, $height) {
		if ($width <= 0 || $height <= 0)
			throw new \Exception("Image width and height must be larger than 0");

		$this->width = $width;
		$this->height = $height;
		$this->content = "
				<svg xmlns='http://www.w3.org/2000/svg' version='1.1'
		      width='$this->width' height='$this->height'>";
	}

	/**
	 * Echoes the content of the image to the output buffer
	 *
	 * 
	 * @return void
	 */
	public function toOutputBuffer() {

		echo $this->getOutputBuffer();
	}

	public function getOutputBuffer() {

		if ($this->width <= 0 || $this->height <= 0)
			throw new \Exception("Image width and height must be larger than 0");

		return "
		
		  $this->content
		</svg>
		";
	}

	/**
	 * gets the width of the image
	 * 
	 * @return Integer 
	 */
	public function getWidth() {
		return $this->width;
	}

	/**
	 * gets the height of the image 
	 * 
	 * @return Integer 
	 */
	public function getHeight() {
		return $this->height;
	}

	/**
	 * Draws a black rectangle, filled with white to the image
	 * 
	 * @param  Integer $posXPixels left position in pixels of the rectangle 
	 * @param  Integer $posYPixels top position in pixels of the rectangle
	 * @param  Integer $width      the width in pixels of the rectangle
	 * @param  Integer $height     the height in pixels of the rectangle
	 * @return void
	 */
	public function drawFrame($posXPixels, $posYPixels, $width, $height) {
		$this->content .= "
			<rect x='$posXPixels' y='$posYPixels' width='$width' height='$height' fill='white' stroke='black' stroke-width='1' />";
	}

	public function drawBackground($posXPixels, $posYPixels, $width, $height, $color = "black") {
		$this->content .= "
			<rect x='$posXPixels' y='$posYPixels' width='$width' height='$height' fill='$color' stroke='black' stroke-width='1' />";
	}

	/**
	 * Draws a black line to the image
	 * 
	 * @param  Integer $x1 line start X position
	 * @param  Integer $y1 line start Y position
	 * @param  Integer $x2 line stop X position
	 * @param  Integer $y2 line stop Y position
	 * @return void
	 */
	public function drawLine($x1, $y1, $x2, $y2) {
		$this->content .= "
			<line x1=\"$x1\" y1=\"$y1\" x2=\"$x2\" y2=\"$y2\" stroke=\"$this->color\"  stroke-width='2' />";
		

	}

	/**
	 * Draws a dotted black line to the image
	 * 
	 * @param  Integer $x1 line start X position
	 * @param  Integer $y1 line start Y position
	 * @param  Integer $x2 line stop X position
	 * @param  Integer $y2 line stop Y position
	 * @return void
	 */
	public function drawDottedLine($x1, $y1, $x2, $y2) {
		$this->content .= "
			<line x1=\"$x1\" y1=\"$y1\" x2=\"$x2\" y2=\"$y2\" stroke=\"$this->color\" stroke-dasharray=\"2 4\" />";
	}

	/**
	 * Draws a black circle with white fill to the image
	 * 
	 * @param  Integer $cx Center X position of the circle
	 * @param  Integer $cy Center y position of the circle
	 * @param  Integer $r  Radius in pixels of the circle
	 * @return void
	 */
	public function drawCircle($cx, $cy, $r) {
		$this->content .= "
			<circle cx=\"$cx\" cy=\"$cy\" r=\"$r\" style=\"stroke:$this->color; stroke-width:1;fill:white\"/>";
	}

	/**
	 * Draws an black text, font , sans-serif to the image
	 * 
	 * @param  Integer $x Left X position of the text
	 * @param  Integer $y Top  Y position of the text
	 * @param  String $string  The text string written to the image
	 * @return void
	 */
	public function drawText($x, $y, $string, $fontSize = 10, $font = "sans-serif", $textDecorator = "") {
		 $this->content .=  "
		 	<text x=\"$x\" y=\"$y\" font-size = \"$fontSize\" font-family = \"$font\" $textDecorator >$string</text>";
		 	//<text x=\"$x\" y=\"$y\" font-size = \"$fontSize\" font-family = \"sans-serif\" $textDecorator >$string</text>";
	}

	/**
	 * Draws an black text, font , sans-serif to the image
	 * 
	 * @param  Integer $x Left X position of the text
	 * @param  Integer $y Top  Y position of the text
	 * @param  String $string  The text string written to the image
	 * @return void
	 */
	public function drawVerticalText($x, $y, $string, $scenTextColor) {
		$this->content .= "<g writing-mode=\"tb-rl\" fill=\"$scenTextColor\"  >";
		$this->content .=  "	<text x=\"$y\" y=\"$x\" font-size = \"20\" font-family = \"sans-serif\" glyph-orientation-vertical=\"auto\" ext-anchor=\"middle\">$string</text> ";
		$this->content .= "</g>";
	}


 
	

}