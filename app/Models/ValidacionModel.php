<?php

namespace App\Models;

use CodeIgniter\Model;

class ValidacionModel extends Model
{
    // ponytail: misma tabla que CumplimientoMetaModel, distintos allowedFields
    //           para la operación de escritura del médico (update, no insert).
    protected $table      = 'cumplimiento_meta';
    protected $primaryKey = 'cumplimiento_meta_id';
    protected $returnType = 'array';
    protected $useTimestamps = true;
    protected $createdField  = 'created_at';
    protected $updatedField  = 'updated_at';

    protected $allowedFields = [
        'puntuacion',
        'comentario_medico',
        'validado_por',
        'validado_at',
    ];

    /**
     * Cumplimientos pendientes de los pacientes del médico.
     * ponytail: validado_at IS NULL como condición de "pendiente";
     *           evita columna estado ENUM extra.
     */
    public function listar_pendientes(int $medico_id): array
    {
        return $this->db->query('
            SELECT cm.*,
                u.nombre AS paciente_nombre, u.apellido AS paciente_apellido,
                mpc.descripcion AS meta_descripcion,
                tm.nombre AS tipo_nombre
            FROM cumplimiento_meta cm
            JOIN metas_plan_cuidado mpc ON mpc.metas_plan_cuidado_id = cm.metas_plan_cuidado_id
            JOIN tipo_meta tm ON tm.tipo_meta_id = mpc.tipo_meta_id
            JOIN plan_cuidado pc ON pc.plan_cuidado_id = mpc.plan_cuidado_id
            JOIN diagnostico d ON d.plan_cuidado_id = pc.plan_cuidado_id AND d.deleted_at IS NULL
            JOIN usuario u ON u.usuario_id = cm.paciente_id
            WHERE d.medico_id = ?
              AND pc.deleted_at IS NULL
              AND cm.validado_at IS NULL
            ORDER BY cm.fecha ASC
        ', [$medico_id])->getResultArray();
    }

    public function validar(int $cumplimiento_id, array $datos): bool
    {
        return $this->update($cumplimiento_id, $datos);
    }
}
