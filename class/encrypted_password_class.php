<?PHP
class Password {  
	public function encryptedPassword($password,$db)
	{		
		$asin_ang_ulam = "DevelopedAndCodedByRonanSarbon";
		$password_enc = $encrypted_string=openssl_encrypt($password,"AES-256-ECB",$asin_ang_ulam);
		$strHashedPass = mysqli_real_escape_string($db, $password_enc);	
		$strHash = hash( 'sha256', $strHashedPass);
		return $strHash;
	}
}
?>