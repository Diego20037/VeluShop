<?php
/*
*	Clase para manejar la tabla usuario de la base de datos.
*/
class Usuario extends Validator{

    // Declaración de atributos (propiedades).
    private $id_usuario = null;
    private $username = null;
    private $pssword = null;
    private $email = null;
    private $nombre = null;
    private $apellido = null;
    private $activo = null;
    private $id_rol = 1;

    public function setId_usuario($value)
    {
        if ($this->validateNaturalNumber($value)) {
            $this->id_usuario = $value;
            return true;
        } else {
            return false;
        }
    }
    
    public function setUsername($value)
    {
        if ($this->validateString($value,1,10)) {
            $this->username = $value;
            return true;
        } else {
            return false;
        }
    }

    public function setPssword($value)
    {
        if ($this->validatePassword($value)) {
                $this->pssword = password_hash($value, PASSWORD_DEFAULT);
            return true;
        } else {
            return false;
        }
    }

    public function setEmail($value)
    {
        if ($this->validateEmail($value)) {
            $this->email = $value;
            return true;
        } else {
            return false;
        }
    }

    public function setNombre($value)
    {
        if ($this->validateString($value,1,50)) {
            $this->nombre = $value;
            return true;
        } else {
            return false;
        }
    }

    public function setApellido($value)
    {
        if ($this->validateString($value,1,50)) {
            $this->apellido = $value;
            return true;
        } else {
            return false;
        }
    }

    public function setActivo($value)
    {
        if ($this->validateBoolean($value)) {
            $this->activo = $value;
            return true;
        } else {
            return false;
        }
    }

    public function setId_rol($value)
    {
        if ($this->validateNaturalNumber($value)) {
            $this->id_rol = $value;
            return true;
        } else {
            return false;
        }
    }
    
    // Métodos para obtener valores de los atributos.
    
    public function getId_usuario()
    {
        return $this->id_usuario;
    }

    public function getUsername()
    {
        return $this->username;
    }

    public function getPssword()
    {
        return $this->pssword;
    }

    public function getEmail()
    {
        return $this->email;
    }

    public function getNombre()
    {
        return $this->nombre;
    }

    public function getApellido()
    {
        return $this->apellido;
    }

    public function getActvo()
    {
        return $this->activo;
    }

    public function getId_rol()
    {
        return $this->id_rol;
    }
    
    /*
    *   Métodos para gestionar la cuenta del usuario.
    */

    public function checkUser($alias)
    {
        $sql = 'SELECT id_usuario FROM usuario WHERE username = ?';
        $params = array($alias);
        if ($data = Database::getRow($sql, $params)) {
            $this->id_usuario = $data['id_usuario'];
            $this->username = $alias;
            return true;
        } else {
            return false;
        }
    }

    public function checkPassword($pssword)
    {
        $sql = 'SELECT pssword FROM usuario WHERE id_usuario = ?';
        $params = array($this->id_usuario);
        $data = Database::getRow($sql, $params);
        // Se verifica si la contraseña coincide con el hash almacenado en la base de datos.
        
        if (password_verify($pssword, $data['pssword'])) {
            return true;
        } else {           
            return false;
            
        }
    }

    public function changePassword()
    {
        $sql = 'UPDATE usuario SET pssword = ? WHERE id_usuario = ?';
        $params = array($this->pssword, $_SESSION['id_usuario']);
        return Database::executeRow($sql, $params);
    }

    public function readProfile()
    {
        $sql = 'SELECT id_usuario, username, pssword, email, nombre, apellido, activo, rol
                FROM usuario INNER JOIN rol r ON r.id_rol = usuario.id_rol
                WHERE id_usuario = ?';
        $params = array($_SESSION['id_usuario']);
        return Database::getRow($sql, $params);
    }

    public function editProfile()
    {
        $sql = 'UPDATE usuarios
                SET username = ?, nombre = ?, correo_usuario = ?
                WHERE id_usuario = ?';
        $params = array($this->nombres, $this->apellidos, $this->correo, $_SESSION['id_usuario']);
        return Database::executeRow($sql, $params);
    }

    /*
    *   Métodos para realizar las operaciones SCRUD (search, create, read, update, delete).
    */
    public function searchRows($value)
    {
        $sql = 'SELECT id_usuario,username,pssword,email,nombre,activo,rol FROM v_usuario
                WHERE username ILIKE ?';
        $params = array("%$value%");
        return Database::getRows($sql, $params);
    }

    public function createRow()
    {
        $sql = 'INSERT INTO usuario( username, pssword, email, nombre, apellido, activo, id_rol)
                VALUES (?,?,?,?,?,?,?)';
        $params = array($this->username, $this->pssword, $this->email, $this->nombre, $this->apellido, $this->activo, $this->id_rol);
        return Database::executeRow($sql, $params);
    }

    public function readAll()
    {
        $sql = 'SELECT id_usuario ,username ,pssword ,email ,nombre ,apellido ,activo , id_rol FROM usuario
                ORDER BY id_usuario desc';
        $params = null;
        return Database::getRows($sql, $params);
    }

    public function readOne()
    {
        $sql = 'SELECT id_usuario,username,pssword,email,nombre,apellido,activo,id_rol FROM usuario
                WHERE id_usuario = ?';
        $params = array($this->id_usuario);
        return Database::getRow($sql, $params);
    }

    public function updateRow()
    {
        $sql = 'UPDATE usuario 
                SET email = ?, nombre = ?, apellido = ?, activo = ?, id_rol = ?
                WHERE id_usuario = ?';
        $params = array($this->email, $this->nombre, $this->apellido, $this->activo, $this->id_rol, $this->id_usuario);
        return Database::executeRow($sql, $params);
    }

    public function deleteRow()
    {
        $sql = 'DELETE FROM usuarios
                WHERE id_usuario = ?';
        $params = array($this->id);
        return Database::executeRow($sql, $params);
    }
}