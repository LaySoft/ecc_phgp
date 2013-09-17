#ecc ph(g)p  Elliptic curve cryptography in PHP, like PGP

Simple elliptic curve cryptography in PHP, uses NIST's recommended curve P-521.

---

###Requirements

PHP 5.1.2+, or earlier with Hash extension
GMP extension
Mcrypt extension

---

###Algorithms

The crypt works with EC-ElGamal, the sign works with ECDSA algorithms.

Crypt uses AES256 (Rijndael-256) cipher, the key is SHA256 hash of the X coordinate of the random generated point on the curve, and IV is the SHA256 hash of the Y coordinate of this point.

Sign hash function is SHA512

---

###Commands:

 g                     Generate keypair

Generates security and public key into two separate file: sec.key and pub.key.


 s <seckey> <file>     Sign file
 v <pubkey> <file>     Verify signature

