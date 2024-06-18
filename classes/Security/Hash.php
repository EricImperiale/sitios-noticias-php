<?php
namespace DaVinci\Security;

class Hash
{
	public static function crear(string $value): string
	{
		return password_hash($value, PASSWORD_DEFAULT);
	}

	public static function verificar(string $value, string $hash): bool
	{
		return password_verify($value, $hash);
	}

	/**
	 * Genera un token criptográficamente seguro.
	 * El string resultante va a tener el doble de caracteres que de $bytes.
	 * 
	 * @return string
	 */
	public static function generarToken(int $bytes = 32): string
	{
		return bin2hex(random_bytes($bytes));
	}
}