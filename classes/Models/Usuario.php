<?php
namespace DaVinci\Models;

use PDO;
use DaVinci\DB\DB;

class Usuario
{
	private int $usuario_id;
	private int $rol_fk;
	private string $email;
	private string $password;
	private ?string $username;
	private ?string $avatar;
	private ?string $avatar_descripcion;

    public function cargarDatosDeArray(array $fila)
    {
        $this->usuario_id 	            = $fila['usuario_fk'];
        $this->rol_fk                   = $fila['rol_fk'];
        $this->email                    = $fila['email'];
        $this->password                 = $fila['password'];
        $this->username                 = $fila['username'];
        $this->avatar                   = $fila['avatar'];
        $this->avatar_descripcion       = $fila['avatar_descripcion'];
    }

	public function traerPorId(int $id): self|null
	{
		$db = DB::getConexion();
		$query = "SELECT * FROM usuarios 
				WHERE usuario_id = ?";
		$stmt = $db->prepare($query);
		$stmt->execute([$id]);
		$stmt->setFetchMode(PDO::FETCH_CLASS, self::class);

		$usuario = $stmt->fetch();

		if(!$usuario) return null;

		return $usuario;
	}

	public function traerPorEmail(string $email): self|null
	{
		$db = DB::getConexion();
		$query = "SELECT * FROM usuarios 
				WHERE email = ?";
		$stmt = $db->prepare($query);
		$stmt->execute([$email]);
		$stmt->setFetchMode(PDO::FETCH_CLASS, self::class);

		$usuario = $stmt->fetch();

		if(!$usuario) return null;

		return $usuario;
	}

	public function crear(array $data)
	{
		$db = DB::getConexion();
		$query = "INSERT INTO usuarios (rol_fk, email, password) 
				VALUES (:rol_fk, :email, :password)";
		$stmt = $db->prepare($query);
		$stmt->execute([
			'rol_fk' 	=> $data['rol_fk'],
			'email' 	=> $data['email'],
			'password' 	=> $data['password'],
		]);
	}

    public function editar(array $datos)
    {
        $db = DB::getConexion();
        $query = "UPDATE usuarios SET username  = :username 
                WHERE usuario_id = :usuario_id";
        $stmt = $db->prepare($query);

        $stmt->execute([
            'usuario_id' => $datos['usuario_id'],
            'username' => $datos['username'],
        ]);
    }

    public function verificarDisponibilidadUsuario(int $usuario_id, string $username)
    {
        $db = DB::getConexion();
        $query = "SELECT * FROM usuarios 
                WHERE username = :username 
                AND usuario_id != :usuario_id";

        $stmt = $db->prepare($query);
        $stmt->execute([
            'username' => $username,
            'usuario_id' => $usuario_id,
        ]);
        $stmt->setFetchMode(PDO::FETCH_CLASS, self::class);

        $usuario = $stmt->fetch();

        if (!$usuario) return null;

        return $usuario;
    }

	/*************** Setters & Getters ***************/
	public function getUsuarioId(): int
	{
		return $this->usuario_id;
	}

	public function setUsuarioId(int $id)
	{
		$this->usuario_id = $id;
	}

	public function getRolFk(): int
	{
		return $this->rol_fk;
	}

	public function setRolFk(int $rol_fk)
	{
		$this->rol_fk = $rol_fk;
	}

	public function getEmail(): string
	{
		return $this->email;
	}

	public function setEmail(string $email)
	{
		$this->email = $email;
	}

	public function getPassword(): string
	{
		return $this->password;
	}

	public function setPassword(string $password)
	{
		$this->password = $password;
	}

	public function getUsername(): ?string
	{
		return $this->username;
	}

	public function setUsername(?string $username)
	{
		$this->username = $username;
	}

    /**
     * @param string|null $imagen
     */
    public function setImagen(?string $imagen): void
    {
        $this->imagen = $imagen;
    }

    /**
     * @return string|null
     */
    public function getImagen(): ?string
    {
        return $this->imagen;
    }

    /**
     * @param string|null $avatar
     */
    public function setAvatar(?string $avatar): void
    {
        $this->avatar = $avatar;
    }

    /**
     * @return string|null
     */
    public function getAvatar(): ?string
    {
        return $this->avatar;
    }

    /**
     * @param string|null $avatar_descripcion
     */
    public function setAvatarDescripcion(?string $avatar_descripcion): void
    {
        $this->avatar_descripcion = $avatar_descripcion;
    }

    /**
     * @return string|null
     */
    public function getAvatarDescripcion(): ?string
    {
        return $this->avatar_descripcion;
    }

}