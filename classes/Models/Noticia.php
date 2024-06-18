<?php
// Definimos la clase Noticia de nuestro proyecto.
// Esta clase va a representar cómo es una Noticia.
namespace DaVinci\Models;

use PDO;
use DaVinci\DB\DB;

class Noticia
{
    private int $noticia_id;
    private int $usuario_fk;
    private int $estado_publicacion_fk;
    private string $fecha_publicacion;
    private string $titulo;
    private string $sinopsis;
    private string $texto;
    private ?string $imagen;
    private ?string $imagen_descripcion;

    private EstadoPublicacion $estado_publicacion;
    private Usuario $usuario;

    /** @var Etiqueta[] */
    private array $etiquetas = [];
    private array $etiquetas_ids = [];

    /** @var Comentario[] */
    private array $comentarios = [];
    private int $cantidadComentarios  = 0;

    public function cargarDatosDeArray(array $fila)
    {
        $this->noticia_id               = $fila['noticia_id'];
        $this->usuario_fk               = $fila['usuario_fk'];
        $this->estado_publicacion_fk    = $fila['estado_publicacion_fk'];
        $this->fecha_publicacion        = $fila['fecha_publicacion'];
        $this->titulo                   = $fila['titulo'];
        $this->sinopsis                 = $fila['sinopsis'];
        $this->texto                    = $fila['texto'];
        $this->imagen                   = $fila['imagen'];
        $this->imagen_descripcion       = $fila['imagen_descripcion'];
    }

    /**
     * Obtiene todas las noticias disponibles.
     *
     * @return Noticia[] - Esto significa que el retorno es un "array de Noticia".
     */
    public function todo(): array
    {
        $db = DB::getConexion();
        $query = "SELECT *
                FROM noticias n
                JOIN estados_publicacion ep
                ON n.estado_publicacion_fk = ep.estado_publicacion_id";
        $stmt = $db->prepare($query);
        $stmt->execute();

        $salida = [];

        while($fila = $stmt->fetch(PDO::FETCH_ASSOC)) {
            $noticia = new Noticia;
            $noticia->cargarDatosDeArray($fila);

            $estado = new EstadoPublicacion;
            $estado->cargarDatosDeArray($fila);

            $noticia->setEstadoPublicacion($estado);

            $salida[] = $noticia;
        }

        return $salida;
    }

    /**
     * Obtiene todas las noticias publicadas.
     *
     * @return Noticia[] - Esto significa que el retorno es un "array de Noticia".
     */
    public function publicadas(): array
    {
        $db = DB::getConexion();
        $query = "SELECT * 
                FROM noticias n
                JOIN usuarios u
                ON n.usuario_fk = u.usuario_id
                WHERE n.estado_publicacion_fk = 2";
        $stmt = $db->prepare($query);

        $stmt->execute();

        $salida = [];

        while($fila = $stmt->fetch(PDO::FETCH_ASSOC)) {
            $noticia = new Noticia;
            $noticia->cargarDatosDeArray($fila);

            $usuario = new Usuario;
            $usuario->cargarDatosDeArray($fila);

            $noticia->setUsuario($usuario);

            $salida[] = $noticia;
        }

        $this->asignarEtiquetas($salida);

        return $salida;
    }

    /**
     * Obtiene todas las noticias publicadas paginadas.
     *
     * @return Noticia[] - Esto significa que el retorno es un "array de Noticia".
     */
    public function paginadas(int $limit, int $offset): array
    {
        $db = DB::getConexion();
        $query = "SELECT * 
                FROM noticias n
                JOIN usuarios u
                ON n.usuario_fk = u.usuario_id
                WHERE n.estado_publicacion_fk = 2 
                LIMIT :limit OFFSET :offset";
        $stmt = $db->prepare($query);
        $stmt->bindParam(':limit', $limit, PDO::PARAM_INT);
        $stmt->bindParam(':offset', $offset, PDO::PARAM_INT);

        $stmt->execute();

        $salida = [];

        while($fila = $stmt->fetch(PDO::FETCH_ASSOC)) {
            $noticia = new Noticia;
            $noticia->cargarDatosDeArray($fila);

            $usuario = new Usuario;
            $usuario->cargarDatosDeArray($fila);

            $noticia->setUsuario($usuario);

            $salida[] = $noticia;
        }

        $this->asignarEtiquetas($salida);

        return $salida;
        /* $stmt->setFetchMode(PDO::FETCH_CLASS, Noticia::class);

            fetchAll() es un método que retorna todos los
            registros como un array.
        return $stmt->fetchAll(); */
    }

    /**
     * Carga las etiquetas para las noticias.
     *
     * @param array $noticias
     * @return void
     */
    public function asignarEtiquetas(array $noticias)
    {
        $ids = array_map(function($noticia) {
            return $noticia->noticia_id;
        }, $noticias);

        $etiquetas = (new Etiqueta)->cargarEtiquetas($ids);

        foreach($noticias as $noticia) {
            $noticia->setEtiquetas($etiquetas[$noticia->getNoticiaId()]);
        }
    }

    /**
     * Obtiene la noticia correspondiente al $id provisto.
     * Si la noticia no existe, retorna null.
     *
     * @param int $id
     * @return Noticia|null
     */
    public function traerPorId(int $id): ?Noticia
    {
        $db = DB::getConexion();
        $query = "SELECT n.*, u.*
                FROM noticias n
                JOIN usuarios u
                ON n.usuario_fk = u.usuario_id
                WHERE noticia_id = ?";
        $stmt = $db->prepare($query);
        $stmt->execute([$id]);

        while($fila = $stmt->fetch(PDO::FETCH_ASSOC)) {
            $noticia = new Noticia;
            $noticia->cargarDatosDeArray($fila);

            $usuario = new Usuario;
            $usuario->cargarDatosDeArray($fila);

            $noticia->setUsuario($usuario);
        }

        if(!$noticia) {
            return null;
        }

        $noticia->asignarTagsPorId();
        $noticia->asignarComentariosPorId();

        return $noticia;
    }

    /**
     * Obtiene las etiquetas correspondientes para una noticia.
     *
     * @return void
     */
    protected function asignarTagsPorId()
    {
        $db = DB::getConexion();

        $query = "SELECT e.*
                FROM noticias_tienen_etiquetas nte
                JOIN etiquetas e
                ON nte.etiqueta_fk = e.etiqueta_id
                WHERE nte.noticia_fk = ?";
        $stmt = $db->prepare($query);
        $stmt->execute([$this->noticia_id]);

        $etiquetasIds = [];
        $this->etiquetas = [];

        while($fila = $stmt->fetch(PDO::FETCH_ASSOC)) {
            $etiquetasIds[] = $fila['etiqueta_id'];

            $etiqueta = new Etiqueta;
            $etiqueta->cargarDatosDeArray($fila);
            $this->etiquetas[] = $etiqueta;
        }

        $this->setEtiquetasIds($etiquetasIds);
    }

    /**
     *  Obtiene los comentarios correspondientes para una noticia.
     *
     * @return void
     */
    protected function asignarComentariosPorId(): void
    {
        $db = DB::getConexion();

        $query = "SELECT c.*, u.*
              FROM noticias_tienen_comentarios ntc
              JOIN comentarios c
              ON ntc.comentario_fk = c.comentario_id
              JOIN usuarios u 
              ON c.usuario_fk = u.usuario_id
              WHERE ntc.noticia_fk = ?";
        $stmt = $db->prepare($query);
        $stmt->execute([$this->noticia_id]);

        $this->totalComentarios($db);

        $salida = [];

        while ($fila = $stmt->fetch(PDO::FETCH_ASSOC)) {
            $comentario = new Comentario();
            $comentario->cargarDatosDeArray($fila);

            $usuario = new Usuario();
            $usuario->cargarDatosDeArray($fila);

            $salida[] = $comentario;
        }

        $this->setComentarios($salida);
    }

    /**
     * Obtiene la cantidad de noticias para paginarlas.
     *
     * @param PDO $db
     * @return void
     */
    protected function totalComentarios($db)
    {
        $query = "SELECT COUNT(*) AS totalComentarios
              FROM noticias_tienen_comentarios
              WHERE noticia_fk = ?";
        $stmt = $db->prepare($query);
        $stmt->execute([$this->noticia_id]);

        $fila = $stmt->fetch(PDO::FETCH_ASSOC);

        $this->setCantidadComentarios($fila['totalComentarios']);
    }

    /**
     * Obtiene la cantidad de noticias para paginarlas.
     *
     * @return void
     */
    public function totalNoticias(): int
    {
        $db = DB::getConexion();
        $query = "SELECT COUNT(*) AS totalNoticias
                 FROM noticias
                 WHERE estado_publicacion_fk = 2";
        $stmt = $db->prepare($query);
        $stmt->execute();

        $fila = $stmt->fetch(PDO::FETCH_ASSOC);

        return $fila['totalNoticias'];
    }

    /**
     * Guarda la noticia en la base de datos.
     * 
     * @param array $data
     * @throws PDOException
     */
    public function crear(array $data)
    {
        $db = DB::getConexion();
        $db->beginTransaction();

        try {
            $query = "INSERT INTO noticias (usuario_fk, estado_publicacion_fk, fecha_publicacion, titulo, sinopsis, texto, imagen, imagen_descripcion) 
                    VALUES (:usuario_fk, :estado_publicacion_fk, :fecha_publicacion, :titulo, :sinopsis, :texto, :imagen, :imagen_descripcion)";
            $stmt = $db->prepare($query);

            $stmt->execute([
                'usuario_fk'            => $data['usuario_fk'],
                'estado_publicacion_fk' => $data['estado_publicacion_fk'],
                'fecha_publicacion'     => $data['fecha_publicacion'],
                'titulo'                => $data['titulo'],
                'sinopsis'              => $data['sinopsis'],
                'texto'                 => $data['texto'],
                'imagen'                => $data['imagen'],
                'imagen_descripcion'    => $data['imagen_descripcion'],
            ]);

            $noticiaId = $db->lastInsertId();

            if(!empty($data['etiquetas'])) {
                $this->asociarEtiquetas($noticiaId, $data['etiquetas']);
            }

            $db->commit();
        } catch (\Exception $e) {
            $db->rollBack();
            throw $e;
        }
    }

    /**
     * Graba los registros de las relaciones entre la noticia y las etiquetas.
     */
    public function asociarEtiquetas(int $noticiaId, array $etiquetas)
    {
        /*
        Como vimos con SQL, para poder asocias las etiquetas a la noticia,
        tenemos que insertar registos en la tabla pivot, algo como:

        INSERT INTO noticias_tienen_etiquetas (noticia_fk, etiqueta_fk)
        VALUES
            (1, 5),
            (1, 7), ...

        Nada más que los pares de valores de las FKs, en este caso, son 
        dinámicos. El id de la noticia es el que se haya auto-generado
        (que lo pedimos como el parámetro $noticiaId), y los ids de las
        etiquetas son los que haya seleccionado el usuario (que los pedimos
        con el parámetro $etiquetas).

        Por tanto, vamos a tener que crear un par de valores para insertar
        por cada etiqueta que hayamos recibido.
        Esto va a tener que incluir el temita de los holders para prevenir
        posibles ataques de inyección SQL.

        Suponiendo que partimos de los datos:
        $noticiaId = 7;
        $etiquetas = [1, 4, 5, 8];
        El query resultante que tenemos que armar, va a ser algo como el 
        siguiente:
        INSERT INTO noticias_tienen_etiquetas (noticia_fk, etiqueta_fk)
        VALUES  (?, ?),     -- Par (7, 1)
                (?, ?),     -- Par (7, 4)
                (?, ?),     -- Par (7, 5)
                (?, ?);     -- Par (7, 8)

        Como usamos holders posicionales/secuenciales, vamos a necesitar
        pasar en el execute un array que contenga los valores que corresponden
        a cada holder como un arraty secuencial, en el orden que se tienen que
        asociar.
        Esto daría como resultado un array como este:
        $valores = [7, 1, 7, 4, 7, 5, 7, 8];
        */
        $holders = [];
        $valores = [];

        foreach($etiquetas as $etiquetaId) {
            $holders[] = "(?, ?)";
            $valores[] = $noticiaId;
            $valores[] = $etiquetaId;
        }

        $query = "INSERT INTO noticias_tienen_etiquetas (noticia_fk, etiqueta_fk)
                VALUES " . implode(', ', $holders);
        $db = DB::getConexion();
        $stmt = $db->prepare($query);
        $stmt->execute($valores);
    }

    public function desasociarEtiquetas(int $id)
    {
        $db = DB::getConexion();
        $query = "DELETE FROM noticias_tienen_etiquetas
                WHERE noticia_fk = ?";
        $stmt = $db->prepare($query);
        $stmt->execute([$id]);
    }

    public function editar(int $id, array $data)
    {
        $db = DB::getConexion();

        $db->beginTransaction();

        try {
            $query = "UPDATE noticias 
                    SET usuario_fk              = :usuario_fk,
                        estado_publicacion_fk   = :estado_publicacion_fk,
                        -- fecha_publicacion       = :fecha_publicacion,
                        titulo                  = :titulo,
                        sinopsis                = :sinopsis,
                        texto                   = :texto,
                        imagen                  = :imagen,
                        imagen_descripcion      = :imagen_descripcion
                    WHERE noticia_id = :noticia_id";
            $stmt = $db->prepare($query);

            $stmt->execute([
                'noticia_id'            => $id,
                'usuario_fk'            => $data['usuario_fk'],
                'estado_publicacion_fk' => $data['estado_publicacion_fk'],
                // 'fecha_publicacion'     => $data['fecha_publicacion'],
                'titulo'                => $data['titulo'],
                'sinopsis'              => $data['sinopsis'],
                'texto'                 => $data['texto'],
                'imagen'                => $data['imagen'],
                'imagen_descripcion'    => $data['imagen_descripcion'],
            ]);

            $this->desasociarEtiquetas($id);
            if(!empty($data['etiquetas'])) {
                $this->asociarEtiquetas($id, $data['etiquetas']);
            }

            $db->commit();
        } catch (\Exception $e) {
            $db->rollBack();
            throw $e;
        }
    }

    /**
     * Elimina la noticia de la base de datos.
     */
    public function eliminar(int $id)
    {
        $this->desasociarEtiquetas($id);

        $db = DB::getConexion();
        $query = "DELETE FROM noticias
                WHERE noticia_id = ?";
        $stmt = $db->prepare($query);
        $stmt->execute([$id]);
    }

    /************** Setters & Getters **************/
    public function setNoticiaId(int $noticia_id)
    {
        $this->noticia_id = $noticia_id;
    }

    public function getNoticiaId(): int
    {
        return $this->noticia_id;
    }

    public function getUsuarioFk(): int
    {
        return $this->usuario_fk;
    }

    public function setUsuarioFk(int $usuario_fk)
    {
        $this->usuario_fk = $usuario_fk;
    }

    public function getEstadoPublicacionFk(): int
    {
        return $this->estado_publicacion_fk;
    }

    public function setEstadoPublicacionFk(int $estado_publicacion_fk)
    {
        $this->estado_publicacion_fk = $estado_publicacion_fk;
    }

    public function setFechaPublicacion(string $fecha_publicacion)
    {
        $this->fecha_publicacion = $fecha_publicacion;
    }

    public function getFechaPublicacion(): string
    {
        return $this->fecha_publicacion;
    }

    public function setTitulo(string $titulo)
    {
        $this->titulo = $titulo;
    }

    public function getTitulo(): string
    {
        return $this->titulo;
    }

    public function setSinopsis(string $sinopsis)
    {
        $this->sinopsis = $sinopsis;
    }

    public function getSinopsis(): string
    {
        return $this->sinopsis;
    }

    public function setTexto(string $texto)
    {
        $this->texto = $texto;
    }

    public function getTexto(): string
    {
        return $this->texto;
    }

    public function setImagen(?string $imagen)
    {
        $this->imagen = $imagen;
    }

    public function getImagen(): ?string
    {
        return $this->imagen;
    }

    public function setImagenDescripcion(?string $imagen_descripcion)
    {
        $this->imagen_descripcion = $imagen_descripcion;
    }

    public function getImagenDescripcion(): ?string
    {
        return $this->imagen_descripcion;
    }

    public function setEstadoPublicacion(EstadoPublicacion $estado_publicacion)
    {
        $this->estado_publicacion = $estado_publicacion;
    }

    public function getEstadoPublicacion(): EstadoPublicacion
    {
        return $this->estado_publicacion;
    }

    public function setUsuario(Usuario $usuario)
    {
        $this->usuario = $usuario;
    }

    public function getUsuario(): Usuario
    {
        return $this->usuario;
    }

    public function setEtiquetasIds(array $etiquetas_ids)
    {
        $this->etiquetas_ids = $etiquetas_ids;
    }

    public function getEtiquetasIds(): array
    {
        return $this->etiquetas_ids;
    }

    /**
     * @param Etiqueta[] $etiquetas
     */
    public function setEtiquetas(array $etiquetas)
    {
        $this->etiquetas = $etiquetas;
    }

    /**
     * @return Etiqueta[]
     */
    public function getEtiquetas(): array
    {
        return $this->etiquetas;
    }

    /**
     * @param array $comentarios
     */
    public function setComentarios(array $comentarios): void
    {
        $this->comentarios = $comentarios;
    }

    /**
     * @return array
     */
    public function getComentarios(): array
    {
        return $this->comentarios;
    }

    /**
     * @param int $cantidadComentarios
     */
    public function setCantidadComentarios(int $cantidadComentarios): void
    {
        $this->cantidadComentarios = $cantidadComentarios;
    }

    /**
     * @return int
     */
    public function getCantidadComentarios(): int
    {
        return $this->cantidadComentarios;
    }
}
