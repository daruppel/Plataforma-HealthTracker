<?php

namespace App\Models;
use CodeIgniter\Model;

class CarePlanTaskModel extends Model
{
    protected $table = 'metas_plan_cuidado';
    protected $primaryKey = 'metas_plan_cuidado_id';
    protected $returnType = 'array'; // 'array', 'object' o nombre de clase
    //Activa el borrado logico cuando se utiliza '$modelo->delete($id);'
    protected $useSoftDeletes = true;
    
    // Campos que se pueden insertar/actualizar
    protected $allowedFields = [
        'plan_cuidado_id',
        'tipo_meta_id',
        'meta_cumplida',
        'descripcion',
    ];

    // Timestamps automáticos
    protected $useTimestamps = true;
    protected $createdField = 'created_at';  // Campo para fecha de creación
    protected $updatedField = 'updated_at';  // Campo para fecha de actualización
    protected $deletedField = 'deleted_at';  // Campo para fecha de eliminación
    
    // Validaciones del modelo
    protected $validationRules = [
        'plan_cuidado_id' => 'required|integer',
        'tipo_meta_id'    => 'required|integer',
        'meta_cumplida'   => 'permit_empty|in_list[0,1]',
        'descripcion'     => 'required|min_length[3]',
    ];
    
    protected $validationMessages = [
       'plan_cuidado_id' => [
            'required'     => 'El plan de cuidado es obligatorio.',
        ],
        'meta_cumplida' => [
            'in_list' => 'El estado de cumplimiento no es válido.',
        ],
        'descripcion' => [
            'required'   => 'La descripción es obligatoria.',
            'min_length' => 'La descripción debe tener al menos 3 caracteres.',
        ],
    ];
    // Validación solo en insert (no en update)
    protected $skipValidation = false;

    public function getComplianceStatisticsByDoctor(int $doctorId)
{
    $rows = $this->db->table('diagnostico d')
        ->select([
            'u.usuario_id AS paciente_id',
            'u.nombre AS paciente_nombre',
            'u.apellido AS paciente_apellido',
            'd.diagnostico_id',
            'd.plan_cuidado_id',
            'pc.fec_inicio',
            'pc.fec_fin',
            'metas_plan_cuidado.metas_plan_cuidado_id',
            'metas_plan_cuidado.meta_cumplida',
            'metas_plan_cuidado.descripcion AS meta_descripcion',
        ])
        ->join('usuario u', 'u.usuario_id = d.paciente_id')
        ->join('plan_cuidado pc', 'pc.plan_cuidado_id = d.plan_cuidado_id', 'left')
        ->join('metas_plan_cuidado', 'metas_plan_cuidado.plan_cuidado_id = pc.plan_cuidado_id', 'left')
        ->where('d.medico_id', $doctorId)
        ->where('d.deleted_at', null)
        ->where('u.deleted_at', null)
        ->groupStart()
            ->where('pc.deleted_at', null)
            ->orWhere('pc.plan_cuidado_id', null)
        ->groupEnd()
        ->groupStart()
            ->where('metas_plan_cuidado.deleted_at', null)
            ->orWhere('metas_plan_cuidado.metas_plan_cuidado_id', null)
        ->groupEnd()
        ->orderBy('u.apellido', 'ASC')
        ->orderBy('u.nombre', 'ASC')
        ->get()
        ->getResultArray();

    $patients = [];
    $totalTasks = 0;
    $completedTasks = 0;
    $pendingTasks = 0;

    foreach ($rows as $row) {
        $patientId = (int) $row['paciente_id'];

        if (!isset($patients[$patientId])) {
            $patients[$patientId] = [
                'paciente_id' => $patientId,
                'paciente' => trim($row['paciente_apellido'] . ', ' . $row['paciente_nombre']),
                'cantidad_diagnosticos' => [],
                'cantidad_planes' => [],
                'total_metas' => 0,
                'metas_cumplidas' => 0,
                'metas_pendientes' => 0,
                'porcentaje_cumplimiento' => 0,
            ];
        }

        if (!empty($row['diagnostico_id'])) {
            $patients[$patientId]['cantidad_diagnosticos'][$row['diagnostico_id']] = true;
        }

        if (!empty($row['plan_cuidado_id'])) {
            $patients[$patientId]['cantidad_planes'][$row['plan_cuidado_id']] = true;
        }

        if (!empty($row['metas_plan_cuidado_id'])) {
            $isCompleted = $this->isTaskCompleted($row['meta_cumplida']);

            $patients[$patientId]['total_metas']++;
            $totalTasks++;

            if ($isCompleted) {
                $patients[$patientId]['metas_cumplidas']++;
                $completedTasks++;
            } else {
                $patients[$patientId]['metas_pendientes']++;
                $pendingTasks++;
            }
        }
    }

    foreach ($patients as &$patient) {
        $patient['cantidad_diagnosticos'] = count($patient['cantidad_diagnosticos']);
        $patient['cantidad_planes'] = count($patient['cantidad_planes']);

        $patient['porcentaje_cumplimiento'] = $patient['total_metas'] > 0
            ? round(($patient['metas_cumplidas'] / $patient['total_metas']) * 100, 2)
            : 0;
    }
    unset($patient);

    $patients = array_values($patients);

    return [
        'summary' => [
            'total_pacientes' => count($patients),
            'total_metas' => $totalTasks,
            'metas_cumplidas' => $completedTasks,
            'metas_pendientes' => $pendingTasks,
            'porcentaje_global' => $totalTasks > 0
                ? round(($completedTasks / $totalTasks) * 100, 2)
                : 0,
        ],
        'patients' => $patients,
        'chart' => [
            'labels' => array_column($patients, 'paciente'),
            'compliance' => array_column($patients, 'porcentaje_cumplimiento'),
            'completed' => array_column($patients, 'metas_cumplidas'),
            'pending' => array_column($patients, 'metas_pendientes'),
            'donut' => [$completedTasks, $pendingTasks],
        ],
    ];
}

private function isTaskCompleted($value): bool
{
    if (is_bool($value)) {
        return $value;
    }

    if (is_int($value)) {
        return $value === 1;
    }

    if (is_string($value)) {
        return in_array(trim($value), ['1', 'true', 'TRUE', 'on', 'si', 'sí'], true);
    }

    return false;
}

}