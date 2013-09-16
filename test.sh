chmod +x ecc.php
./ecc.php g
./ecc.php e pub.key testfile testfile_enc
./ecc.php d sec.key testfile_enc testfile_dec
cmp testfile testfile_dec
./ecc.php s sec.key testfile
./ecc.php v pub.key testfile
