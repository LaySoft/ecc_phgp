#ecc ph(g)p

##Elliptic curve cryptography in PHP, like PGP

Simple elliptic curve public key cryptography in PHP, uses NIST's recommended curve P-521.

---

###Requirements

PHP 5.1.2+, or earlier with Hash extension

GMP extension

Mcrypt extension

---

###Commands:

 g                     Generate keypair

Generates security and public key into two separate file: sec.key and pub.key.


 s <seckey> <file>     Sign file
 v <pubkey> <file>     Verify signature

###Technial info

The encrypt and decrypt uses AES256 (Rijndael-256) block cipher algorithm. The encrypt process choose a random point on the curve, and this point X coordinate SHA256 hash will be the 256 bit key, and Y coodinate SHA256 hash will be the 256 bit IV for the AES256 block cipher. The X and Y coordinates encoded and decoded with EC-ElGamal algorithm. The encode process result is four numbers, which is saved to &lt;destfile&gt;.key file.

The sign and verify works with ECDSA algorithm. The sign process result is two numbers, which is saved to &lt;file&gt;.sig file. The sign hash funtion is SHA512.
