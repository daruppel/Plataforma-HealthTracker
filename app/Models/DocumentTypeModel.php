<?php

namespace App\Models;

use CodeIgniter\Model;

class DocumentTypeModel extends Model
{
    protected $table = 'tipo_documento';
    protected $primaryKey = 'tipo_documento_id';
    protected $returnType = 'array';
    protected $allowedFields = ['nombre'];
}
