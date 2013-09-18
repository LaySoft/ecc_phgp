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



<table border="0" cellspacing="0" cellpadding="3">
<tr>
<td width="1%">g</td>
<td>Generate keypair</td>
</tr>
<tr>
<td colspan="2">Generates security and public keys, then saved into two separate file: sec.key and pub.key.<br/><br/></td>
</tr>
<tr>
<td>e &lt;pubkey&gt; &lt;infile&gt; &lt;destfile&gt;</td>
<td>Encrypt</td>
</tr>
<tr>
<td colspan="2">Encode &lt;infile&gt; with &lt;pubkey&gt; and save the encoded data to &lt;destfile&gt;. The encoded crypt key saved to &lt;destfile&gt;.key<br/><br/></td>
</tr>
<tr>
<td>d &lt;seckey&gt; &lt;infile&gt; &lt;destfile&gt;</td>
<td>Decrypt</td>
</tr>
<tr>
<td colspan="2">Decode &lt;infile&gt; with &lt;seckey&gt; and &lt;infile&gt;.key, then save the decoded data to &lt;destfile&gt;<br/><br/></td>
</tr>
<td>s &lt;seckey&gt; &lt;file&gt;</td>
<td>Sign file</td>
</tr>
<tr>
<td colspan="2">Sign &lt;file&gt; with &lt;seckey&gt; and save the signature to &lt;file&gt;.sig<br/><br/></td>
</tr>
<tr>
<td>v &lt;pubkey&gt; &lt;file&gt;</td>
<td>Verify signature</td>
</tr>
<tr>
<td colspan="2">Verify the &lt;file&gt;.sig signature on &lt;file&gt; with &lt;pubkey&gt; and print the result<br/><br/></td>
</tr>
</table>




###Technial info

The encrypt and decrypt uses AES256 (Rijndael-256) block cipher algorithm. The encrypt process choose a random point on the curve, and this point X coordinate SHA256 hash will be the 256 bit key, and Y coodinate SHA256 hash will be the 256 bit IV for the AES256 block cipher. The X and Y coordinates encoded and decoded with EC-ElGamal algorithm. The encode process result is four numbers, which is saved to &lt;destfile&gt;.key file.

The sign and verify works with ECDSA algorithm. The sign process result is two numbers, which is saved to &lt;file&gt;.sig file. The sign hash funtion is SHA512.
