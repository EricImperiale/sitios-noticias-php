<?php

namespace DaVinci\Models;

use PDO;
use DaVinci\DB\DB;

class Comentario
{
    private int $comentario_id;
    private string $comentario;
    private string $fecha_publicacion;
    private string $usuario_id;
    private string $rol_fk;
    private string $email;
    private ?string $password;
    private ?string $username = null;

    public function cargarDatosDeArray(array $data)
    {
        $this->comentario_id            = $data['comentario_id'];
        $this->comentario               = $data['comentario'];
        $this->fecha_publicacion 		= $data['fecha_publicacion'];
        $this->usuario_id 		        = $data['usuario_id'];
        $this->email 		            = $data['email'];
        $this->username 		        = $data['username'];
    }

    public function crear(int $id, string $comentario, int $usuario_id)
    {
        $db = DB::getConexion();
        $db->beginTransaction();

        try {
            $this->insertarComentario($db, $usuario_id, $comentario);

            $comentario_fk = $db->lastInsertId();

            $this->asociarComentario($db, $id, $comentario_fk);

            $db->commit();
        } catch (\Exception $e) {
            $db->rollBack();
            throw $e;
        }
    }

    protected function insertarComentario($db, int $usuario_id, string $comentario)
    {
        $query = "INSERT INTO comentarios (usuario_fk, comentario, fecha_publicacion) VALUES (:usuario_fk, :comentario, :fecha_publicacion)";

        $stmt = $db->prepare($query);
        $stmt->execute([
            ':usuario_fk' => $usuario_id,
            ':comentario' => $comentario,
            ':fecha_publicacion' => date('Y-m-d H:i:s'),
        ]);
    }

    protected function asociarComentario($db, int $id, string $comentario_fk)
    {
        $query2 = "INSERT INTO noticias_tienen_comentarios (noticia_fk, comentario_fk) VALUES (:noticia_fk, :comentario_fk)";
        $stmt2 = $db->prepare($query2);
        $stmt2->execute([
            ':noticia_fk' => $id,
            ':comentario_fk' => $comentario_fk,
        ]);

    }

    /************** Setters & Getters **************/
    /**
     * @param int $comentario_id
     */
    public function setComentarioId(int $comentario_id): void
    {
        $this->comentario_id = $comentario_id;
    }

    /**
     * @return int
     */
    public function getComentarioId(): int
    {
        return $this->comentario_id;
    }

    /**
     * @param string $comentario
     */
    public function setComentario(string $comentario): void
    {
        $this->comentario = $comentario;
    }

    /**
     * @return string
     */
    public function getComentario(): string
    {
        return $this->comentario;
    }

    /**
     * @param string $fecha_publicacion
     */
    public function setFechaPublicacion(string $fecha_publicacion): void
    {
        $this->fecha_publicacion = $fecha_publicacion;
    }

    /**
     * @return string
     */
    public function getFechaPublicacion(): string
    {
        return $this->fecha_publicacion;
    }

    /**
     * @param string $usuario_id
     */
    public function setUsuarioId(string $usuario_id): void
    {
        $this->usuario_id = $usuario_id;
    }

    /**
     * @return string
     */
    public function getUsuarioId(): string
    {
        return $this->usuario_id;
    }

    /**
     * @param string $rol_fk
     */
    public function setRolFk(string $rol_fk): void
    {
        $this->rol_fk = $rol_fk;
    }

    /**
     * @return string
     */
    public function getRolFk(): string
    {
        return $this->rol_fk;
    }

    /**
     * @param string $email
     */
    public function setEmail(string $email): void
    {
        $this->email = $email;
    }

    /**
     * @return string
     */
    public function getEmail(): string
    {
        return $this->email;
    }

    /**
     * @param string $password
     */
    public function setPassword(string $password): void
    {
        $this->password = $password;
    }

    /**
     * @return string
     */
    public function getPassword(): string
    {
        return $this->password;
    }

    /**
     * @param string|null $username
     */
    public function setUsername(?string $username): void
    {
        $this->username = $username;
    }

    /**
     * @return string|null
     */
    public function getUsername(): ?string
    {
        return $this->username;
    }
}