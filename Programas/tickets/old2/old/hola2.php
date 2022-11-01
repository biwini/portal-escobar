<?php
	include('Crypt/RSA.php');

	$rsa = new Crypt_RSA();

	$privatekey = '-----BEGIN RSA PRIVATE KEY----- MIICXAIBAAKBgQDOoN+1UR330OwpF07YNZ0wVD0U1VQcQpThT2tpnAlTCWRAesOq V71eBsDZc8wgqFl4RsXhd+x7u10mjlAGE9TKo32zY4OVY7oYDkjN1s3pCa9H/iVz 4INwal+LNbsepDsguRpX6KdcNqHL7GYluVZr6ztNJIyw1xLXK+cQt2Jj6QIDAQAB AoGAAKPIj8JCjJ0A17JNiopfuDGMo2v7+NrdOn5bGkfd/LZHN6wrieqBPFJayzPK KOo4uhVp0TN3gthjTdrP4aEiTsn+gK9lMj48OFq7iofe8vQqCiTDRFSVRsdIcbx7 IWiTNr8QvWwQ+q3s0i8VEQpVJsceghukW2xkJa8et9gIqX0CQQDRnpOBZDDsPcxs PTjzMhbyI1ak5lYih7qjdVnjvE8GivM/RTwzBFJrn6Qvz5LYBVBBEnBV3udEvp0F NC1AEAsPAkEA/FjgtVe4X35GSLriHMLWpUnnCwxMXB0d22GRhOntpzhycqYW9SVp 7lAyrPu+q0oJTIeRopyHqaEJ0wCW+QaBhwJAB9Oi8E7cYMXB7zyt9q6lGq9lGc0b 5DgrKNVF4PH0BWuEv5UHNWRw62HPkJVwhy5Tm8pjdWFYQ0HWvQ4Aroaq0wJBANBH W1DR0i+fPcuR1EcA2cEbOkN4Jx5wOdB0u3ME0U6P3IacZ552/vPf5bO5JSqjtQEh dCbPI3nynPU3K2Tm5ckCQBQq1PbsVnob2WkqoNa/CLFCKCoHkbzhmbDjDvCjUBha Va47GZCO2jkitNdjP7YKE2ASinje/gi6mZrJhDPlZ4k= -----END RSA PRIVATE KEY-----';

	$rsa->loadKey($privatekey);

	$plaintext = new Math_BigInteger('123123123');
	var_dump($plaintext);
	echo '<hr>';
	$rsa->setEncryptionMode(CRYPT_RSA_ENCRYPTION_PKCS1);
	$ciphertext = $rsa->encrypt('123123123');

	var_dump($rsa->_exponentiate($plaintext)->toBytes());


	echo $rsa->decrypt($ciphertext);
// 	$rsa = new Crypt_RSA();
// extract($rsa->createKey());

// $plaintext = '123123123';

// $rsa->loadKey($privatekey);
// $rsa->setEncryptionMode(CRYPT_RSA_ENCRYPTION_PKCS1);
// $ciphertext = $rsa->encrypt($plaintext);

// echo $plaintext.'<hr>';

// echo $ciphertext.'<hr>';

// echo $privatekey;

// extract($rsa->createKey());

// var_dump($privatekey);
// echo "$privatekey<hr />$publickey";

// $keyPair = openssl_pkey_new(array(
//     "digest_alg" => 'sha512',
//     "private_key_bits" => 4096,
//     "private_key_type" => OPENSSL_KEYTYPE_RSA
// ));
// $privateKey = null;

// openssl_pkey_export($keyPair, $privateKey);

// echo sprintf("PHP: %s\n", phpversion());
// echo sprintf("OpenSSL: %s\n", OPENSSL_VERSION_TEXT);
// echo sprintf("Private key header: %s\n", current(explode("\n", $privateKey)));
?>