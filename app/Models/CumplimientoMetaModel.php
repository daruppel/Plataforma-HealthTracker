<?php

namespace App\Models;

use CodeIgniter\Model;

class CumplimientoMetaModel extends Model
{
    protected $table      = 'cumplimiento_meta';
    protected $primaryKey = 'cumplimiento_meta_id';
    protected $returnType = 'array';

    // ponytail: sin soft delete — los registros de cumplimiento son logs auditables inmutables
    protected $useTimestamps = true;
    protected $createdField  = 'created_at';
    protected $updatedField  = 'updated_at';

    protected $allowedFields = [
        'metas_plan_cuidado_id',
        'paciente_id',
        'fecha',
        'duracion_minutos',
        'comentario',
    ];

    protected $validationRules = [
        'metas_plan_cuidado_id' => 'required|integer',
        'paciente_id'           => 'required|integer',
        'fecha'                 => 'required|valid_date[Y-m-d]',
        'duracion_minutos'      => 'required|integer|greater_than[0]',
        'comentario'            => 'permit_empty',
    ];

    /**
     * Retorna las metas del plan activo del paciente con flag registrado_hoy.
     * ponytail: raw query + LEFT JOIN para calcular el flag en una sola vuelta a BD;
     *           evita N round trips desde el controller.
     */
    public function listar_metas_plan_activo(int $paciente_id): array
    {
        return $this->db->query('
            SELECT mpc.*, tm.nombre AS tipo_nombre,
                (cm.cumplimiento_meta_id IS NOT NULL) AS registrado_hoy
            FROM diagnostico d
            JOIN plan_cuidado pc
                ON pc.plan_cuidado_id = d.plan_cuidado_id AND pc.deleted_at IS NULL
            JOIN metas_plan_cuidado mpc
                ON mpc.plan_cuidado_id = pc.plan_cuidado_id AND mpc.deleted_at IS NULL
            JOIN tipo_meta tm ON tm.tipo_meta_id = mpc.tipo_meta_id
            LEFT JOIN cumplimiento_meta cm
                ON cm.metas_plan_cuidado_id = mpc.metas_plan_cuidado_id
               AND cm.paciente_id = ?
               AND cm.fecha = CURDATE()
            WHERE d.paciente_id = ?
              AND d.deleted_at IS NULL
              AND d.plan_cuidado_id > 0
        ', [$paciente_id, $paciente_id])->getResultArray();
    }

    public function insertar(array $datos): bool
    {
        return $this->save($datos);
    }

    public function ya_registrado_hoy(int $metas_plan_cuidado_id, int $paciente_id): bool
    {
        return $this->where('metas_plan_cuidado_id', $metas_plan_cuidado_id)
                    ->where('paciente_id', $paciente_id)
                    ->where('fecha', date('Y-m-d'))
                    ->countAllResults() > 0;
    }
}
