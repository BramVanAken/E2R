<?php
try { $db = new PDO('sqlite:sqlite/database'); } catch (Exception $e) { die($e); }
class Bcrypt {
	public static function hash($p) {
		$s = substr(strtr(base64_encode(openssl_random_pseudo_bytes(16)), '+', '.'),0,22);
		return crypt($p, '$2y$12$'.$s);
	}
	public static function check($p, $h) { return crypt($p, $h) == $h; }
}
?>