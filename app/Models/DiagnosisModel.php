<?php

namespace App\Models;

use CodeIgniter\Model;

class DiagnosisModel extends Model
{
    // Datos de diagnostico
    protected $table = 'diagnostico';
    protected $primaryKey = 'diagnostico_id';
    protected $returnType = 'array'; // 'array', 'object' o nombre de clase
    //Activa el borrado logico cuando se utiliza '$modelo->delete($id);'
    protected $useSoftDeletes = true;
    
    // Campos que se pueden insertar/actualizar
    protected $allowedFields = [
        'tipo_diagnostico_id',
        'paciente_id',
        'medico_id',
        'fecha',
        'descripcion',
        'estado_id',
        'plan_cuidado_id'
    ];

    // Timestamps automáticos
    protected $useTimestamps = true;
    protected $createdField = 'created_at';  // Campo para fecha de creación
    protected $updatedField = 'updated_at';  // Campo para fecha de actualización
    protected $deletedField = 'deleted_at';  // Campo para fecha de eliminación
    
    // Validaciones del modelo
    protected $validationRules = [
        'tipo_diagnostico_id' => 'required|integer',
        'paciente_id' => 'required|integer',
        'fecha' => 'required|valid_date',
        'descripcion' => 'required|min_length[3]|max_length[100]',
        'estado_id' => 'required|integer'
    ];
    //TODO: Revisar si las validaciones estan bien o me sarpe de boluda.
   protected $validationMessages = [
        'tipo_diagnostico_id' => [
            'required' => 'El tipo de diagnóstico es obligatorio',
            'in_list' => ' El tipo de diagnóstico seleccionado no es válido'
        ],
        'paciente_id' => [
            'required' => 'El paciente es obligatorio',
            'in_list' => ' El paciente seleccionado no es válido'
        ],
        'fecha' => [
            'required' => 'La fecha es obligatoria',
            'valid_date' => 'La fecha no es válida'
        ],
        'descripcion' => [
            'required' => 'La descripción es obligatoria',
            'min_length' => 'La descripción debe tener al menos 3 caracteres',
            'max_length' => 'La descripción no puede exceder 100 caracteres'
        ],
        'estado_id' => [
            'required' => 'El estado es obligatorio',
            'in_list' => ' El estado seleccionado no es válido'
        ],
        'medico_id' => [
            'required' => 'El médico es obligatorio',
            'in_list' => ' El médico seleccionado no es válido'
        ]
    ];
    
    // Validación solo en insert (no en update)
    protected $skipValidation = false;

   public function findAllByDoctor($doctorId){
        return $this->select('diagnostico.*, usuario.nombre, usuario.apellido, tipo_diagnostico.nombre as tipo_diagnostico, estado_diagnostico.estado')
                    ->join('usuario', 'usuario.usuario_id=diagnostico.paciente_id', 'left')
                    ->join('tipo_diagnostico','tipo_diagnostico.tipo_diagnostico_id=diagnostico.tipo_diagnostico_id','left')
                    ->join('estado_diagnostico','estado_diagnostico.estado_diagnostico_id=diagnostico.estado_id','left')
                    ->where('medico_id', $doctorId)
                    ->findAll();
    } 

    public function getGroupedByPatient($doctorId)
    {
        $rows = $this->select('
                diagnostico.*,
                usuario.usuario_id as paciente_id,
                usuario.nombre,
                usuario.apellido,
                tipo_diagnostico.nombre as tipo_diagnostico,
                estado_diagnostico.estado
            ')
            ->join('usuario', 'usuario.usuario_id = diagnostico.paciente_id', 'left')
            ->join('tipo_diagnostico','tipo_diagnostico.tipo_diagnostico_id = diagnostico.tipo_diagnostico_id','left')
            ->join('estado_diagnostico','estado_diagnostico.estado_diagnostico_id = diagnostico.estado_id','left')
            ->where('medico_id', $doctorId)
            ->orderBy('usuario.apellido', 'ASC')
            ->findAll();

        //Agrupar por paciente
        $grouped = [];
        foreach ($rows as $r) {
            $pid = $r['paciente_id'];
            if (!isset($grouped[$pid])) {
                $grouped[$pid] = [
                    'paciente_id' => $pid,
                    'nombre' => $r['nombre'] . ' ' . $r['apellido'],
                    'diagnosticos' => []
                ];
            }
            $grouped[$pid]['diagnosticos'][] = [
                'diagnostico_id' => $r['diagnostico_id'],
                'tipo' => $r['tipo_diagnostico'],
                'fecha' => $r['fecha'],
                'estado' => $r['estado']
            ];
        }
        return array_values($grouped);
    }
    public function getPatientsByDoctor(int $doctorId): array
    {
        return $this->db->query('
            SELECT DISTINCT u.usuario_id, u.nombre, u.apellido, u.email, u.created_at,
                COUNT(d.diagnostico_id) AS total_diagnosticos
            FROM diagnostico d
            JOIN usuario u ON u.usuario_id = d.paciente_id
            WHERE d.medico_id = ?
              AND d.deleted_at IS NULL
              AND u.deleted_at IS NULL
            GROUP BY u.usuario_id
            ORDER BY u.apellido ASC, u.nombre ASC
        ', [$doctorId])->getResultArray();
    }
    public function getHistoryByPatient($patientId)
    {
         return $this->select([
            'diagnostico.diagnostico_id',
            'diagnostico.fecha',
            'diagnostico.plan_cuidado_id',

            'tipo_diagnostico.nombre AS tipo_diagnostico',

            'estado_diagnostico.estado',

            'medico.nombre AS medico_nombre',
            'medico.apellido AS medico_apellido'
        ])
        ->join(
            'tipo_diagnostico',
            'tipo_diagnostico.tipo_diagnostico_id = diagnostico.tipo_diagnostico_id'
        )
        ->join(
            'estado_diagnostico',
            'estado_diagnostico.estado_diagnostico_id = diagnostico.estado_id'
        )
        ->join(
            'usuario medico',
            'medico.usuario_id = diagnostico.medico_id'
        )
        ->where('diagnostico.paciente_id', $patientId)
        ->orderBy('diagnostico.fecha', 'DESC')
        ->findAll();
    }
}