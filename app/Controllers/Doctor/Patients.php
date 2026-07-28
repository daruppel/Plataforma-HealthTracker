<?php

namespace App\Controllers\Doctor;

use App\Controllers\BaseController;
use App\Models\DiagnosisModel;

class Patients extends BaseController
{
    protected $diagnosisModel;

    public function __construct()
    {
        $this->diagnosisModel = new DiagnosisModel();
    }

    public function index()
    {
        $doctorId = (int) session()->get('user_id');
        $data['patients'] = $this->diagnosisModel->getPatientsByDoctor($doctorId);

        return view('templates/header')
            . view('templates/sidebar')
            . view('doctor/patients/index', $data)
            . view('templates/footer');
    }

    public function historial(int $pacienteId)
    {
        $doctorId = (int) session()->get('user_id');

        // Reuse getGroupedByPatient filtered to one patient
        $rows = $this->diagnosisModel
            ->select('diagnostico.*, usuario.usuario_id as paciente_id, usuario.nombre, usuario.apellido, usuario.email,
                      tipo_diagnostico.nombre as tipo_diagnostico, estado_diagnostico.estado,
                      plan_cuidado.fec_inicio, plan_cuidado.fec_fin, plan_cuidado.comentario_paciente')
            ->join('usuario', 'usuario.usuario_id = diagnostico.paciente_id', 'left')
            ->join('tipo_diagnostico', 'tipo_diagnostico.tipo_diagnostico_id = diagnostico.tipo_diagnostico_id', 'left')
            ->join('estado_diagnostico', 'estado_diagnostico.estado_diagnostico_id = diagnostico.estado_id', 'left')
            ->join('plan_cuidado', 'plan_cuidado.plan_cuidado_id = diagnostico.plan_cuidado_id AND plan_cuidado.deleted_at IS NULL', 'left')
            ->where('diagnostico.medico_id', $doctorId)
            ->where('diagnostico.paciente_id', $pacienteId)
            ->orderBy('diagnostico.fecha', 'DESC')
            ->findAll();

        if (empty($rows)) {
            return redirect()->to('/medical_staff/patients')->with('error', 'Paciente no encontrado o sin diagnósticos.');
        }

        $data['paciente'] = [
            'nombre'   => $rows[0]['nombre'],
            'apellido' => $rows[0]['apellido'],
            'email'    => $rows[0]['email'],
        ];
        $data['diagnosticos'] = $rows;

        return view('templates/header')
            . view('templates/sidebar')
            . view('doctor/patients/historial', $data)
            . view('templates/footer');
    }
}
