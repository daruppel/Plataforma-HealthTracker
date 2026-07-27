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

    public function obtenerHistorialMediciones(int $pacienteId): array
    {
        return $this->db->table('cumplimiento_meta cm')
            ->select('cm.*, mpc.descripcion AS meta_descripcion, mpc.plan_cuidado_id, tm.nombre AS tipo_meta_nombre, v.nombre AS medico_nombre, v.apellido AS medico_apellido')
            ->join('metas_plan_cuidado mpc', 'mpc.metas_plan_cuidado_id = cm.metas_plan_cuidado_id')
            ->join('tipo_meta tm', 'tm.tipo_meta_id = mpc.tipo_meta_id')
            ->join('usuario v', 'v.usuario_id = cm.validado_por', 'left')
            ->where('cm.paciente_id', $pacienteId)
            ->orderBy('cm.fecha', 'DESC')
            ->orderBy('cm.created_at', 'DESC')
            ->get()
            ->getResultArray();
    }

    public function obtenerEstadisticasCumplimiento(int $pacienteId): array
    {
        $metas = $this->db->table('diagnostico d')
            ->select('mpc.metas_plan_cuidado_id, mpc.meta_cumplida, tm.nombre AS tipo_meta_nombre')
            ->join('plan_cuidado pc', 'pc.plan_cuidado_id = d.plan_cuidado_id')
            ->join('metas_plan_cuidado mpc', 'mpc.plan_cuidado_id = pc.plan_cuidado_id')
            ->join('tipo_meta tm', 'tm.tipo_meta_id = mpc.tipo_meta_id')
            ->where('d.paciente_id', $pacienteId)
            ->where('d.deleted_at', null)
            ->where('pc.deleted_at', null)
            ->where('mpc.deleted_at', null)
            ->get()
            ->getResultArray();

        $totalMetas = count($metas);
        $metasCumplidas = 0;
        $metasPorCategoria = [];

        foreach ($metas as $meta) {
            $cat = $meta['tipo_meta_nombre'];
            if (!isset($metasPorCategoria[$cat])) {
                $metasPorCategoria[$cat] = ['total' => 0, 'cumplidas' => 0];
            }
            $metasPorCategoria[$cat]['total']++;

            $value = $meta['meta_cumplida'];
            $isCompleted = false;
            if (is_bool($value)) {
                $isCompleted = $value;
            } elseif (is_int($value)) {
                $isCompleted = $value === 1;
            } else {
                $isCompleted = in_array(trim((string)$value), ['1', 'true', 'on', 'si', 'sí', "\x31"], true);
            }

            if ($isCompleted) {
                $metasCumplidas++;
                $metasPorCategoria[$cat]['cumplidas']++;
            }
        }

        $logsSummary = $this->db->table('cumplimiento_meta')
            ->select('COUNT(cumplimiento_meta_id) as total_logs, SUM(duracion_minutos) as total_minutos, AVG(duracion_minutos) as promedio_minutos')
            ->where('paciente_id', $pacienteId)
            ->get()
            ->getRowArray();

        $totalLogs = (int) ($logsSummary['total_logs'] ?? 0);
        $totalMinutos = (int) ($logsSummary['total_minutos'] ?? 0);
        $promedioMinutos = round((float) ($logsSummary['promedio_minutos'] ?? 0), 1);

        $porcentajeGlobal = $totalMetas > 0 ? round(($metasCumplidas / $totalMetas) * 100, 2) : 0;

        return [
            'total_metas' => $totalMetas,
            'metas_cumplidas' => $metasCumplidas,
            'metas_pendientes' => $totalMetas - $metasCumplidas,
            'porcentaje_global' => $porcentajeGlobal,
            'total_logs' => $totalLogs,
            'total_minutos' => $totalMinutos,
            'promedio_minutos' => $promedioMinutos,
            'categorias' => $metasPorCategoria
        ];
    }

    /**
     * Retorna los planes de cuidado asociados al paciente para usar en el selector de la vista.
     */
    public function obtenerPlanesDelPaciente(int $pacienteId): array
    {
        return $this->db->table('diagnostico d')
            ->select('pc.plan_cuidado_id, pc.fec_inicio, pc.fec_fin, td.nombre AS tipo_diagnostico')
            ->join('plan_cuidado pc', 'pc.plan_cuidado_id = d.plan_cuidado_id')
            ->join('tipo_diagnostico td', 'td.tipo_diagnostico_id = d.tipo_diagnostico_id')
            ->where('d.paciente_id', $pacienteId)
            ->where('d.deleted_at', null)
            ->where('pc.deleted_at', null)
            ->where('d.plan_cuidado_id >', 0)
            ->orderBy('pc.fec_inicio', 'DESC')
            ->get()
            ->getResultArray();
    }

    /**
     * Calcula estadísticas de cumplimiento para un plan específico.
     * Mismo formato que obtenerEstadisticasCumplimiento pero filtrado por plan.
     */
    public function obtenerEstadisticasPorPlan(int $planId, int $pacienteId): array
    {
        $metas = $this->db->table('metas_plan_cuidado mpc')
            ->select('mpc.metas_plan_cuidado_id, mpc.meta_cumplida, tm.nombre AS tipo_meta_nombre')
            ->join('tipo_meta tm', 'tm.tipo_meta_id = mpc.tipo_meta_id')
            ->where('mpc.plan_cuidado_id', $planId)
            ->where('mpc.deleted_at', null)
            ->get()
            ->getResultArray();

        $totalMetas = count($metas);
        $metasCumplidas = 0;
        $metasPorCategoria = [];

        foreach ($metas as $meta) {
            $cat = $meta['tipo_meta_nombre'];
            if (!isset($metasPorCategoria[$cat])) {
                $metasPorCategoria[$cat] = ['total' => 0, 'cumplidas' => 0];
            }
            $metasPorCategoria[$cat]['total']++;

            $value = $meta['meta_cumplida'];
            $isCompleted = false;
            if (is_bool($value)) {
                $isCompleted = $value;
            } elseif (is_int($value)) {
                $isCompleted = $value === 1;
            } else {
                $isCompleted = in_array(trim((string)$value), ['1', 'true', 'on', 'si', 'sí', "\x31"], true);
            }

            if ($isCompleted) {
                $metasCumplidas++;
                $metasPorCategoria[$cat]['cumplidas']++;
            }
        }

        $logsSummary = $this->db->table('cumplimiento_meta cm')
            ->select('COUNT(cm.cumplimiento_meta_id) as total_logs, SUM(cm.duracion_minutos) as total_minutos, AVG(cm.duracion_minutos) as promedio_minutos')
            ->join('metas_plan_cuidado mpc', 'mpc.metas_plan_cuidado_id = cm.metas_plan_cuidado_id')
            ->where('mpc.plan_cuidado_id', $planId)
            ->where('cm.paciente_id', $pacienteId)
            ->get()
            ->getRowArray();

        $totalLogs = (int) ($logsSummary['total_logs'] ?? 0);
        $totalMinutos = (int) ($logsSummary['total_minutos'] ?? 0);
        $promedioMinutos = round((float) ($logsSummary['promedio_minutos'] ?? 0), 1);

        $porcentajeGlobal = $totalMetas > 0 ? round(($metasCumplidas / $totalMetas) * 100, 2) : 0;

        return [
            'total_metas' => $totalMetas,
            'metas_cumplidas' => $metasCumplidas,
            'metas_pendientes' => $totalMetas - $metasCumplidas,
            'porcentaje_global' => $porcentajeGlobal,
            'total_logs' => $totalLogs,
            'total_minutos' => $totalMinutos,
            'promedio_minutos' => $promedioMinutos,
            'categorias' => $metasPorCategoria
        ];
    }
}
