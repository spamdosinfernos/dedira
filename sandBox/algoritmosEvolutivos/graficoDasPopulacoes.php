<?php 
// Requires the GD Library
header("Content-type: image/png");


$arrArquivo = str_split(file_get_contents("geracoes.txt"),20);

$qtdeLinhas = count($arrArquivo);

$im = imagecreatetruecolor(20, 5000) or die("Cannot Initialize new GD image stream");
$white = imagecolorallocate($im, 255, 255, 0);

foreach ($arrArquivo as $numLinha => $linha) {
	
	if($numLinha == 5000) break;
	
	$arrCaractere = str_split($linha,1);
	
	foreach ($arrCaractere as $numColuna => $caractere) {
		
		if($caractere == 0) continue;
		
		imagesetpixel($im, $numColuna, $numLinha, $white);
	}
}
imagepng($im);
imagedestroy($im);

?>