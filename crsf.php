<?php
	if (session_status() === PHP_SESSION_NONE) {
		session_start();
	}


	/**
	 * The functions are used to generate, retrieve, and validate CSRF tokens in PHP.
	 * Un token CSRF (Cross-Site Request Forgery) es una medida de seguridad utilizada en aplicaciones 
	 * web para protegerse contra los ataques de falsificación de solicitudes entre sitios (CSRF)
	 */
	function generateCsrfToken() {
		if (empty($_SESSION['csrf_token'])) {
			$_SESSION['csrf_token'] = bin2hex(random_bytes(32));
		}
	}

	function getCsrfToken() {
		return $_SESSION['csrf_token'];
	}

	function validateCsrfToken($token) {
		return $token === $_SESSION['csrf_token'];
	}
?>
