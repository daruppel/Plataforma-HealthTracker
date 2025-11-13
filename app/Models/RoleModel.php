<?php

namespace App\Models;
use CodeIgniter\Model;

class RoleModel extends Model
{
    protected $table = 'rol';
    protected $primaryKey = 'rol_id';
    protected $allowedFields = ['descripcion', 'nombre'];
}
