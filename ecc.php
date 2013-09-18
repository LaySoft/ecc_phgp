#!/usr/bin/php -f
<?

error_reporting(E_ALL|E_STRICT);

ini_set('display_errors',TRUE);

define('P',gmp_sub(gmp_pow(2,521),1)); // 6864797660130609714981900799081393217269435300143305409394463459185543183397656052122559640661454554977296311391480858037121987999716643812574028291115057151

define('N','6864797660130609714981900799081393217269435300143305409394463459185543183397655394245057746333217197532963996371363321113864768612440380340372808892707005449');

define('A',-3);

define('B',gmp_init('0x051953eb9618e1c9a1f929a21a0b68540eea2da725b99b315f3b8b489918ef109e156193951ec7e937b1652c0bd3bb1bf073573df883d2c34f1ef451fd46b503f00',16));

define('Gx',gmp_init('0xc6858e06b70404e9cd9e3ecb662395b4429c648139053fb521f828af606b4d3dbaa14b5e77efe75928fe1dc127a2ffa8de3348b3c1856a429bf97e7e31c2e5bd66',16));

define('Gy',gmp_init('0x11839296a789a3bc0045c8a5fb42c7d1bd998f54449579b446817afbd17273e662c97ee72995ef42640c550b9013fad0761353c7086a272c24088be94769fd16650',16));

define('STOREBASE',62);

function Osszead($x1,$y1,$x2,$y2) {
	$i=gmp_mod(gmp_mul(gmp_mod(gmp_sub($y2,$y1),P),gmp_invert(gmp_sub($x2,$x1),P)),P);
	$x3=gmp_mod(gmp_sub(gmp_sub(gmp_pow($i,2),$x1),$x2),P);
	$y3=gmp_mod(gmp_sub(gmp_mul($i,gmp_sub($x1,$x3)),$y1),P);
	return(array($x3,$y3));
}

function Duplaz($x1,$y1) {
	$i=gmp_mod(gmp_mul(gmp_mod(gmp_add(gmp_mul(gmp_pow($x1,2),3),A),P),gmp_invert(gmp_mul($y1,2),P)),P);
	$x3=gmp_mod(gmp_sub(gmp_pow($i,2),gmp_mul($x1,2)),P);
	$y3=gmp_mod(gmp_sub(gmp_mul($i,gmp_sub($x1,$x3)),$y1),P);
	return(array($x3,$y3));
}

function Szoroz($kx,$ky,$s) {
	$bitek=gmp_strval($s,2);
	$hany=strlen($bitek)-1;
	$x=$kx;
	$y=$ky;
	for ($i=0;$i<=$hany;$i++) {
		if (($i!=0)&&($bitek{$i}=='1')) {
			$hozzaad=Osszead($x,$y,$kx,$ky);
			$x=$hozzaad[0];
			$y=$hozzaad[1];
		}
		if ($i!=$hany) {
			$dupla=Duplaz($x,$y);
			$x=$dupla[0];
			$y=$dupla[1];
		}
	}
	return(array($x,$y));
}

function rnd($hatar) {
	$random=gmp_strval(gmp_random());
	$small_rand=rand();
	while (gmp_cmp($random,$hatar)>0) {
		$random=gmp_div($random,$small_rand,GMP_ROUND_ZERO);
	}
	return(gmp_strval($random));
}

define('LF',chr(10));

if (sizeof($argv)==1) {

	$h=LF;
	$h.='Commands:'.LF;
	$h.=LF;
	$h.=' g                               Generate keypair'.LF;
	$h.=' e <pubkey> <infile> <destfile>  Encrypt file'.LF;
	$h.=' d <seckey> <infile> <destfile>  Decrypt file'.LF;
	$h.=' s <seckey> <file>               Sign file'.LF;
	$h.=' v <pubkey> <file>               Verify signature'.LF;
	$h.=LF;

	echo $h;

} else {

	switch ($argv[1]) {
	case 'g': // Generate keys

		$d=rnd(gmp_sub(N,1));

		$Q=Szoroz(Gx,Gy,$d);

		$sec=gmp_strval($d,STOREBASE);

		$pub=gmp_strval($Q[0],STOREBASE).LF.gmp_strval($Q[1],STOREBASE);
		
		file_put_contents('sec.key',$sec);

		file_put_contents('pub.key',$pub);

		echo 'Key generation complete'.LF;

	break;
	case 'e': // Encrypt

		if (isset($argv[2])) {

			if (file_exists($argv[2])) {

				if (isset($argv[3])) {

					if (file_exists($argv[3])) {

						do {

							$x=rnd(gmp_sub(N,1));

							$jobb=gmp_mod(gmp_add(gmp_add(gmp_pow($x,3),gmp_mul($x,A)),B),P);

						} while (gmp_legendre($jobb,P)!=1);

						$y=gmp_powm($jobb,gmp_div(gmp_add(P,1),4),P); // Special case in Tonelli-Shanks algorithm

						$bal=gmp_powm($y,2,P);

						$k=rnd(gmp_sub(N,1));

						$kG=Szoroz(Gx,Gy,$k);

						$pub=explode(LF,file_get_contents($argv[2]));

						$bG[0]=gmp_init($pub[0],STOREBASE);
						$bG[1]=gmp_init($pub[1],STOREBASE);

						$kbG=Szoroz($bG[0],$bG[1],$k);

						$M_kbG=Osszead($kbG[0],$kbG[1],$x,$y);

						$keyfile=gmp_strval($kG[0],STOREBASE).LF.gmp_strval($kG[1],STOREBASE).LF.gmp_strval($M_kbG[0],STOREBASE).LF.gmp_strval($M_kbG[1],STOREBASE);

						file_put_contents($argv[4].'.key',$keyfile);

						$key=hash('sha256',gmp_strval($x,STOREBASE),TRUE);

						$iv=hash('sha256',gmp_strval($y,STOREBASE),TRUE);

						file_put_contents($argv[4],mcrypt_encrypt('rijndael-256',$key,file_get_contents($argv[3]),'ctr',$iv));

						echo 'Encryption complete'.LF;

					} else {

						echo 'Can\'t open file!'.LF;
					}

				} else {

					echo 'Please give file to verify!'.LF;
				}

			} else {

				echo 'Can\'t open public key!'.LF;
			}

		} else {

			echo 'Please give public key!'.LF;
		}

	break;
	case 'd': // Decrypt

		if (isset($argv[2])) {

			if (file_exists($argv[2])) {

				if (isset($argv[3])) {

					if (file_exists($argv[3])) {

						if (file_exists($argv[3].'.key')) {

							$d=gmp_init(file_get_contents($argv[2]),STOREBASE);

							$key=explode(LF,file_get_contents($argv[3].'.key'));

							$kG[0]=gmp_init($key[0],STOREBASE);
							$kG[1]=gmp_init($key[1],STOREBASE);

							$dkG=Szoroz($kG[0],$kG[1],$d);

							$dkG[1]=gmp_mod(gmp_neg($dkG[1]),P);

							$M_kbG[0]=gmp_init($key[2],STOREBASE);
							$M_kbG[1]=gmp_init($key[3],STOREBASE);

							$M=Osszead($M_kbG[0],$M_kbG[1],$dkG[0],$dkG[1]);

							$key=hash('sha256',gmp_strval($M[0],STOREBASE),TRUE);

							$iv=hash('sha256',gmp_strval($M[1],STOREBASE),TRUE);

							file_put_contents($argv[4],mcrypt_decrypt('rijndael-256',$key,file_get_contents($argv[3]),'ctr',$iv));

							echo 'Decryption complete'.LF;

						} else {

							echo 'Can\'t open .key file!'.LF;
						}

					} else {

						echo 'Can\'t open file!'.LF;
					}

				} else {

					echo 'Please give file to sign!'.LF;
				}

			} else {

				echo 'Can\'t open security key!'.LF;
			}

		} else {

			echo 'Please give security key!'.LF;
		}

	break;
	case 's': // Sign

		if (isset($argv[2])) {

			if (file_exists($argv[2])) {

				if (isset($argv[3])) {

					if (file_exists($argv[3])) {

						$d=gmp_init(file_get_contents($argv[2]),STOREBASE);

						$e=gmp_init(hash_file('sha512',$argv[3]),16);

						$k=rnd(gmp_sub(N,1));

						$k1=gmp_invert($k,N);

						$R=Szoroz(Gx,Gy,$k);

						$xR=$R[0];

						$r=gmp_mod($xR,N);

						$s=gmp_mod(gmp_mul($k1,gmp_add($e,gmp_mul($d,$r))),N);

						$sig=gmp_strval($r,STOREBASE).LF.gmp_strval($s,STOREBASE);

						file_put_contents($argv[3].'.sig',$sig);

						echo 'Signing complete'.LF;

					} else {

						echo 'Can\'t open file!'.LF;
					}

				} else {

					echo 'Please give file to sign!'.LF;
				}

			} else {

				echo 'Can\'t open security key!'.LF;
			}

		} else {

			echo 'Please give security key!'.LF;
		}
	break;
	case 'v': // Verify

		if (isset($argv[2])) {

			if (file_exists($argv[2])) {

				if (isset($argv[3])) {

					if (file_exists($argv[3])) {

						if (file_exists($argv[3].'.sig')) {

							$pub=explode(LF,file_get_contents($argv[2]));

							$Q[0]=gmp_init($pub[0],STOREBASE);
							$Q[1]=gmp_init($pub[1],STOREBASE);

							$sig=explode(LF,file_get_contents($argv[3].'.sig'));

							$r=gmp_init($sig[0],STOREBASE);
							$s=gmp_init($sig[1],STOREBASE);

							$e=gmp_init(hash_file('sha512',$argv[3]),16);

							$w=gmp_invert($s,N);

							$u1=gmp_mod(gmp_mul($e,$w),N);

							$u2=gmp_mod(gmp_mul($r,$w),N);

							$t1=Szoroz(Gx,Gy,$u1);

							$t2=Szoroz($Q[0],$Q[1],$u2);

							$Rr=Osszead($t1[0],$t1[1],$t2[0],$t2[1]);

							$v=gmp_mod($Rr[0],N);

							if (gmp_strval($v,STOREBASE)==gmp_strval($r,STOREBASE)) {

								echo 'Verify OK'.LF;

							} else {

								echo 'Signature different!'.LF;
							}

						} else {

							echo 'Can\'t open .sig file!'.LF;
						}

					} else {

						echo 'Can\'t open file!'.LF;
					}

				} else {

					echo 'Please give file to verify!'.LF;
				}

			} else {

				echo 'Can\'t open public key!'.LF;
			}

		} else {

			echo 'Please give public key!'.LF;
		}

	break;
	default:
		echo 'Unrecognized command!'.LF;
	break;
	}
}

?>
