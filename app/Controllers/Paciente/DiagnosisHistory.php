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
        ->where('diagnostico_id', $diagnosticoId)
        ->where('paciente_id', $pacienteId)
        ->first();

    if (!$diagnostico) {
        return redirect()
            ->to('/paciente/care-plan-history')
            ->with('error', 'Diagnóstico no encontrado.');
    }

    $metas = $this->cumplimientoMetaModel
        ->listar_metas_plan(
            $diagnostico['plan_cuidado_id'],
            $pacienteId
        );

    return view('templates/header')
        . view('templates/sidebar')
        . view('paciente/cumplimiento/index', [
            'metas' => $metas
        ])
        . view('templates/footer');
    }
}