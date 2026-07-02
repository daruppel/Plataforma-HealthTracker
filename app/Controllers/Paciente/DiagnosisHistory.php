<?php

namespace App\Controllers\Paciente;

use App\Controllers\BaseController;
use App\Models\DiagnosisModel;
use App\Models\CarePlanTaskModel;
use App\Models\CumplimientoMetaModel;

class DiagnosisHistory extends BaseController
{
    protected $diagnosisModel;
    protected $carePlanTaskModel;
    protected $cumplimientoMetaModel;

    public function __construct()
    {
        $this->diagnosisModel = new DiagnosisModel();
        $this->carePlanTaskModel = new CarePlanTaskModel();
        $this->cumplimientoMetaModel = new CumplimientoMetaModel();
    }

    /**
     * Muestra el historial de diagnósticos del paciente logueado.
     */
    public function index()
    {
        $patientId = session()->get('user_id');

        $data['history'] = $this->diagnosisModel->getHistoryByPatient($patientId);

        return view('templates/header')
            . view('templates/sidebar')
            . view('paciente/history/index', $data)
            . view('templates/footer');
    }

    /**
     * Muestra el detalle del plan de cuidado asociado a un diagnóstico.
     */
    public function show($diagnosisId)
    {
        $pacienteId = (int) session()->get('user_id');

        $diagnostico = $this->diagnosisModel
            ->select('diagnostico.*, tipo_diagnostico.nombre AS tipo_diagnostico, medico.nombre AS medico_nombre, medico.apellido AS medico_apellido')
            ->join('tipo_diagnostico', 'tipo_diagnostico.tipo_diagnostico_id = diagnostico.tipo_diagnostico_id', 'left')
            ->join('usuario medico', 'medico.usuario_id = diagnostico.medico_id', 'left')
            ->where('diagnostico.diagnostico_id', $diagnosisId)
            ->where('diagnostico.paciente_id', $pacienteId)
            ->first();

        if (!$diagnostico) {
            if ($this->request->isAJAX()) {
                return $this->response->setJSON(['status' => 'error', 'message' => 'Diagnóstico no encontrado.']);
            }
            return redirect()
                ->to('/paciente/care-plan-history')
                ->with('error', 'Diagnóstico no encontrado.');
        }

        $planCuidado = null;
        $metas = [];
        if (!empty($diagnostico['plan_cuidado_id'])) {
            $carePlanModel = new \App\Models\CarePlanModel();
            $planCuidado = $carePlanModel->find($diagnostico['plan_cuidado_id']);
            if ($planCuidado) {
                $metas = $this->cumplimientoMetaModel->listar_metas_plan(
                    (int)$diagnostico['plan_cuidado_id'],
                    $pacienteId
                );
            }
        }

        if ($this->request->isAJAX()) {
            return $this->response->setJSON([
                'status' => 'success',
                'diagnostico' => $diagnostico,
                'plan_cuidado' => $planCuidado,
                'metas' => $metas
            ]);
        }

        return view('templates/header')
            . view('templates/sidebar')
            . view('paciente/cumplimiento/index', [
                'metas' => $metas
            ])
            . view('templates/footer');
    }
}