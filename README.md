eccphp
======

Simple elliptic curve cryptography in PHP, uses NIST's recommended curve P-521.

Requirements:

PHP 5.1.2+ (or earlier with Hash extension)
GMP extension

The crypt works with EC-ElGamal, the sign works with ECDSA algorithms.

Crypt uses AES256 (Rijndael-256) cipher, the key is a SHA256 hash of the random generated X coordinates of point on curve, and IV is the SHA256 hash of the y coordinate this point.

Commands:

 g                     Generate keypair

Generates security and public key into two separate file: sec.key and pub.key.


 s <seckey> <file>     Sign file
 v <pubkey> <file>     Verify signature


