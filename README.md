#ecc ph(g)p

##Elliptic curve cryptography in PHP, like PGP

Simple elliptic curve public key cryptography in PHP, uses <a href="http://csrc.nist.gov/groups/ST/toolkit/documents/dss/NISTReCur.pdf" target="_blank">NIST's recommended curve P-521</a>.

---

###Requirements

PHP 5.1.2+ (cli), or earlier with Hash extension

GMP extension

Mcrypt extension

---

###Commands

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

The sign and verify works with ECDSA algorithm, the hash function is SHA512. The sign process result is two numbers, which is saved to &lt;file&gt;.sig file. The verify process checks the signature in this .sig file on &lt;file&gt;, and print the signature is match or not.

###Why elliptic curve cryptography is "better" than RSA?

<ul>
<li>Elliptic curve mathematics is more complex than RSA, but the keysize is smaller, so the calculations is faster, and consume less power.</li>
</ul>

<table border="1" cellspacing="0" cellpadding="10">
    <th align="center">Symmetric Key Size (bits)</th>
	<th align="center">RSA and Diffie-Hellman Key Size (bits)</th>
	<th align="center">Elliptic Curve Key Size (bits)</th>
  <tr>
    <td align="center">80</td>
	<td align="center">1024</td>
	<td align="center">160</td>
  </tr>
  <tr id="highlight">
    <td align="center">112</td>
	<td align="center">2048</td>
	<td align="center">224</td>
  </tr>
  <tr>
    <td align="center">128</td>
	<td align="center">3072</td>
	<td align="center">256</td>
  </tr>  
  <tr id="highlight">
    <td align="center">192</td>
	<td align="center">7680</td>
	<td align="center">384</td>
  </tr>
  <tr>
    <td align="center">256</td>
	<td align="center">15360</td>
	<td align="center">521</td>
  </tr> 
  <tr><td colspan="3" align="center"><b>NIST Recommended Key Sizes</b></td></tr> 
</table>

<ul>
<li>The RSA keypair generation needs to generate big primes, elliptic curve keypair generation only needs random numbers.</li>
<li>Elliptic curve crypt use ElGamal algorithm which works with random numbers, so same plaintext encoded to different ciphertext, which is more secure.</li>
</ul>
