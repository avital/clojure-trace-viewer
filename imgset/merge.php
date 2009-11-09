<pre>
<?php

/*

This script merges all of the icons and tidbits for a MooTree theme
into a single "strip", containing all of the image files.

Make sure your output folder has write permission, so the output image
can be saved.

Note that the output file is a PNG - you may wish to use an image
editing application afterwards, to convert the file to a GIF, since
PNG support has it's limitations in IE 6 and older.

At the end of the script output, you will get the javascript statement
you need to insert into your script.

You can configure the output filename, and the maximum width/height of
the button images, by changing the following defines ...

*/

define("IMG_WIDTH",  18);
define("IMG_HEIGHT", 18);
define("IMG_OUTPUT", "tree.png"); // the output filename (will be saved in the script's folder)
define("IMG_INPUT",  "tree");         // the folder containing your input button images

/* --- END OF CONFIGURATION --- */

$files = glob(IMG_INPUT . '/*.gif');

$numfiles = count($files);

echo "merging $numfiles files...\n";

$strip_w = IMG_WIDTH*$numfiles;

$strip = imagecreatetruecolor($strip_w, IMG_HEIGHT);
imagealphablending($strip, false);
imagefilledrectangle($strip, 0, 0, $strip_w, IMG_HEIGHT, IMG_COLOR_TRANSPARENT);
imagealphablending($strip, true);

$num = 0;

$strings = array();

foreach ($files as $file) {

	echo "adding $file: ";

	$img = imagecreatefromgif($file);
	if (!$img) die("error loading $file");
	
	$w = imagesx($img);
	$x = round( 0.5 * (IMG_WIDTH - $w) );
	
	$h = imagesy($img);
	$y = round( 0.5 * (IMG_HEIGHT - $h) );
	
	echo " ($x,$y : $w x $h) ";
	
	if ($w > IMG_WIDTH || $h > IMG_HEIGHT)
		die("Output image too small - use at least width = $w, height = $h ...\n");
	
	imagecopy($strip, $img, $num*IMG_WIDTH + $x, $y, 0, 0, $w, $h);
	
	imagedestroy($img);
	
	echo "ok\n";
	
	$strings[] = "'".basename($file,'.gif')."'";
	
	$num++;
	
}

imagesavealpha($strip, true);
imagepng($strip, IMG_OUTPUT);

echo "done!\n\n";

echo "MooTreeIcon = [" . implode(',', $strings) . "];\n\n";

imagedestroy($strip);

?>
</pre>
