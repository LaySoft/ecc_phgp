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
<td><b>g</b></td>
<td><b>Generate keypair</b></td>
</tr>
<tr>
<td colspan="2">Generates new security and public keys, then saved into two separate file: sec.key and pub.key. Old key files will be overwrite!<br/><br/></td>
</tr>
<tr>
<td width="1%" nowrap><b>e</b>&nbsp;&nbsp;&lt;pubkey&gt;&nbsp;&nbsp;&lt;infile&gt;&nbsp;&nbsp;&lt;destfile&gt;</td>
<td><b>Encrypt</b></td>
</tr>
<tr>
<td colspan="2">Encode &lt;infile&gt; with &lt;pubkey&gt;, and save the encoded data to &lt;destfile&gt;. The encoded crypt key saved to &lt;destfile&gt;.key<br/><br/></td>
</tr>
<tr>
<td><b>d</b>&nbsp;&nbsp;&lt;seckey&gt;&nbsp;&nbsp;&lt;infile&gt;&nbsp;&nbsp;&lt;destfile&gt;</td>
<td><b>Decrypt</b></td>
</tr>
<tr>
<td colspan="2">Decode &lt;infile&gt; with &lt;seckey&gt; and &lt;infile&gt;.key, then save the decoded data to &lt;destfile&gt;<br/><br/></td>
</tr>
<td><b>s</b>&nbsp;&nbsp;&lt;seckey&gt;&nbsp;&nbsp;&lt;file&gt;</td>
<td><b>Sign file</b></td>
</tr>
<tr>
<td colspan="2">Sign &lt;file&gt; with &lt;seckey&gt; and save the signature to &lt;file&gt;.sig<br/><br/></td>
</tr>
<tr>
<td><b>v</b>&nbsp;&nbsp;&lt;pubkey&gt;&nbsp;&nbsp;&lt;file&gt;</td>
<td><b>Verify signature</b></td>
</tr>
<tr>
<td colspan="2">Verify the signature in &lt;file&gt;.sig on &lt;file&gt; with &lt;pubkey&gt; and print the result<br/><br/></td>
</tr>
</table>










###Technial info

The encrypt and decrypt uses AES256 (Rijndael-256) block cipher algorithm. The encrypt process choose a random point on the curve, and this point X coordinate SHA256 hash will be the 256 bit key, and Y coodinate SHA256 hash will be the 256 bit IV for the AES256 block cipher. The X and Y coordinates encoded and decoded with EC-ElGamal algorithm. The encode process result is four numbers, which is saved to &lt;destfile&gt;.key file.

The sign and verify works with ECDSA algorithm. The sign process result is two numbers, which is saved to &lt;file&gt;.sig file. The sign hash funtion is SHA512.
