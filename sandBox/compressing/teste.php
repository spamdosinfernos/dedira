<?php 


// open file for writing with maximum compression
$zp = gzopen($filename, "w9");

// write string to file
gzwrite($zp, $s);

// close file
gzclose($zp);

// open file for reading
$zp = gzopen($filename, "r");

// read 3 char
echo gzread($zp, 3);

// output until end of the file and close it.
gzpassthru($zp);
gzclose($zp);

echo "\n";

// open file and print content (the 2nd time).
if (readgzfile($filename) != strlen($s)) {
        echo "Error with zlib functions!";
}
unlink($filename);
echo "</pre>\n</body>\n</html>\n";






?>