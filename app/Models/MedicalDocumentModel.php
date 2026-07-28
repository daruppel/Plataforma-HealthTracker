<?php

namespace App\Models;

use CodeIgniter\Model;

class MedicalDocumentModel extends Model
{
    protected $table = 'documento_medico';
    protected $primaryKey = 'documento_medico_id';
    protected $returnType = 'array';
    protected $useSoftDeletes = true;

    protected $allowedFields = [
        'paciente_id',
        'tipo_documento_id',
        'descripcion',
        'archivo_path'
    ];

    protected $useTimestamps = true;
    protected $createdField = 'created_at';
    protected $updatedField = 'updated_at';
    protected $deletedField = 'deleted_at';

    protected $validationRules = [
        'paciente_id'       => 'required|integer',
        'tipo_documento_id' => 'required|integer',
        'descripcion'       => 'permit_empty|max_length[255]',
        'archivo_path'      => 'required|max_length[255]'
    ];

    /**
     * Obtiene los documentos de un paciente con el nombre del tipo de documento asociado.
     */
    public function getPatientDocuments(int $pacienteId): array
    {
        return $this->select('documento_medico.*, tipo_documento.nombre AS tipo_nombre')
                    ->join('tipo_documento', 'tipo_documento.tipo_documento_id = documento_medico.tipo_documento_id')
                    ->where('documento_medico.paciente_id', $pacienteId)
                    ->orderBy('documento_medico.created_at', 'DESC')
                    ->findAll();
    }
}
