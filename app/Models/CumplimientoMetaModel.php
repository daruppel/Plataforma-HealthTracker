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
    /**
     * Retorna las metas del plan activo del paciente que aún no han sido cumplidas.
     */
    public function listar_metas_plan_activo(int $paciente_id): array
    {
        return $this->db->query('
            SELECT mpc.*, tm.nombre AS tipo_nombre,
                (cm.cumplimiento_meta_id IS NOT NULL) AS registrado
            FROM diagnostico d
            JOIN plan_cuidado pc
                ON pc.plan_cuidado_id = d.plan_cuidado_id AND pc.deleted_at IS NULL
            JOIN metas_plan_cuidado mpc
                ON mpc.plan_cuidado_id = pc.plan_cuidado_id AND mpc.deleted_at IS NULL
            JOIN tipo_meta tm ON tm.tipo_meta_id = mpc.tipo_meta_id
            LEFT JOIN cumplimiento_meta cm
                ON cm.metas_plan_cuidado_id = mpc.metas_plan_cuidado_id
               AND cm.paciente_id = ?
            WHERE d.paciente_id = ?
              AND d.deleted_at IS NULL
              AND d.plan_cuidado_id > 0
              AND (mpc.meta_cumplida IS NULL OR mpc.meta_cumplida = 0 OR mpc.meta_cumplida = 0x30 OR mpc.meta_cumplida = "0")
        ', [$paciente_id, $paciente_id])->getResultArray();
    }

    public function insertar(array $datos): bool
    {
        $ok = $this->save($datos);
        if ($ok && !empty($datos['metas_plan_cuidado_id'])) {
            $this->db->table('metas_plan_cuidado')
                ->where('metas_plan_cuidado_id', $datos['metas_plan_cuidado_id'])
                ->update(['meta_cumplida' => 1]);
        }
        return $ok;
    }

    public function ya_registrado(int $metas_plan_cuidado_id, int $paciente_id): bool
    {
        return $this->where('metas_plan_cuidado_id', $metas_plan_cuidado_id)
                    ->where('paciente_id', $paciente_id)
                    ->countAllResults() > 0;
    }

    public function listar_metas_plan(int $planId, int $pacienteId): array
    {
        return $this->db->query('
            SELECT
                mpc.*,
                tm.nombre AS tipo_nombre,
                (cm.cumplimiento_meta_id IS NOT NULL) AS registrado
            FROM plan_cuidado pc
            JOIN metas_plan_cuidado mpc
                ON mpc.plan_cuidado_id = pc.plan_cuidado_id
               AND mpc.deleted_at IS NULL
            JOIN tipo_meta tm
                ON tm.tipo_meta_id = mpc.tipo_meta_id
            LEFT JOIN cumplimiento_meta cm
                ON cm.metas_plan_cuidado_id = mpc.metas_plan_cuidado_id
               AND cm.paciente_id = ?
            WHERE pc.plan_cuidado_id = ?
              AND pc.deleted_at IS NULL
        ', [$pacienteId, $planId])->getResultArray();
    }
}
