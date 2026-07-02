<?php

namespace App\Controllers\Doctor;

use App\Controllers\BaseController;
use App\Models\CarePlanModel;

class CarePlan extends BaseController
{
    protected $carePlanModel;

    public function __construct()
    {
        $this->carePlanModel = new CarePlanModel();
    }

    public function index()
    {
        $medicoId = session()->get('user_id');

        $carePlans = $this->carePlanModel->select('plan_cuidado.*, usuario.nombre as paciente_nombre, usuario.apellido as paciente_apellido, tipo_diagnostico.nombre as diagnostico_tipo, diagnostico.diagnostico_id')
            ->join('diagnostico', 'diagnostico.plan_cuidado_id = plan_cuidado.plan_cuidado_id')
            ->join('usuario', 'usuario.usuario_id = diagnostico.paciente_id')
            ->join('tipo_diagnostico', 'tipo_diagnostico.tipo_diagnostico_id = diagnostico.tipo_diagnostico_id')
            ->where('diagnostico.medico_id', $medicoId)
            ->orderBy('plan_cuidado.created_at', 'DESC')
            ->findAll();

        $carePlanTaskModel = new \App\Models\CarePlanTaskModel();
        $cumplimientoModel = new \App\Models\CumplimientoMetaModel();

        // ponytail: N+1 per plan; el listado de planes por médico es pequeño (<20 típico)
        foreach ($carePlans as &$cp) {
            $cp['tasks'] = $carePlanTaskModel->select('metas_plan_cuidado.*, tipo_meta.nombre as tipo_nombre')
                ->join('tipo_meta', 'tipo_meta.tipo_meta_id = metas_plan_cuidado.tipo_meta_id')
                ->where('plan_cuidado_id', $cp['plan_cuidado_id'])
                ->findAll();

            $taskIds = array_column($cp['tasks'], 'metas_plan_cuidado_id');
            if (!empty($taskIds)) {
                $cp['cumplimientos_pendientes'] = $cumplimientoModel
                    ->whereIn('metas_plan_cuidado_id', $taskIds)
                    ->where('validado_at', null)
                    ->findAll();
                $cp['total_validados'] = $cumplimientoModel
                    ->whereIn('metas_plan_cuidado_id', $taskIds)
                    ->where('validado_at IS NOT NULL', null, false)
                    ->countAllResults();
            } else {
                $cp['cumplimientos_pendientes'] = [];
                $cp['total_validados'] = 0;
            }
            // Finalizable: al menos 1 validado Y ninguno pendiente
            $cp['puede_finalizar'] = $cp['total_validados'] > 0 && empty($cp['cumplimientos_pendientes']);
        }
        unset($cp);

        $data['carePlans'] = $carePlans;

        return view('templates/header')
            . view('templates/sidebar')
            . view('doctor/careplan/index', $data)
            . view('templates/footer');
    }

    public function create($diagnosticoId = null)
    {
        if (!$diagnosticoId) {
            return redirect()->to('/medical_staff/diagnosis')->with('error', 'Diagnóstico no especificado');
        }

        $diagnosisModel = new \App\Models\DiagnosisModel();
        $diagnosis = $diagnosisModel->select('diagnostico.*, usuario.nombre as paciente_nombre, usuario.apellido as paciente_apellido, tipo_diagnostico.nombre as tipo_diagnostico')
            ->join('usuario', 'usuario.usuario_id = diagnostico.paciente_id')
            ->join('tipo_diagnostico', 'tipo_diagnostico.tipo_diagnostico_id = diagnostico.tipo_diagnostico_id')
            ->where('diagnostico_id', $diagnosticoId)
            ->first();

        if (!$diagnosis) {
            return redirect()->to('/medical_staff/diagnosis')->with('error', 'Diagnóstico no encontrado');
        }

        if (!empty($diagnosis['plan_cuidado_id'])) {
            return redirect()->to('/medical_staff/care-plan')->with('error', 'El diagnóstico ya tiene un plan de cuidado asignado.');
        }

        $taskTypeModel = new \App\Models\TaskTypeModel();
        $taskTypes = $taskTypeModel->findAll();

        if ($this->request->getMethod() === 'GET') {
            $data = [
                'diagnosis' => $diagnosis,
                'taskTypes' => $taskTypes
            ];
            return view('templates/header')
                . view('templates/sidebar')
                . view('doctor/careplan/create', $data)
                . view('templates/footer');
        }

        if ($this->request->getMethod() === 'POST') {
            $validationRules = [
                'fec_inicio' => 'required|valid_date[Y-m-d]',
                'fec_fin' => 'required|valid_date[Y-m-d]',
                'comentario_paciente' => 'permit_empty|min_length[3]'
            ];

            if (!$this->validate($validationRules)) {
                return redirect()->back()->withInput()->with('errors', $this->validator->getErrors());
            }

            $tasks = $this->request->getPost('tasks');
            if (empty($tasks) || !is_array($tasks)) {
                return redirect()->back()->withInput()->with('error', 'Debe agregar al menos una tarea/meta al plan de cuidado.');
            }

            foreach ($tasks as $task) {
                if (empty($task['tipo_meta_id']) || empty($task['descripcion'])) {
                    return redirect()->back()->withInput()->with('error', 'Todas las tareas deben tener tipo y descripción.');
                }
            }

            $db = \Config\Database::connect();
            $db->transStart();

            $carePlanData = [
                'fec_inicio' => $this->request->getPost('fec_inicio'),
                'fec_fin' => $this->request->getPost('fec_fin'),
                'comentario_paciente' => $this->request->getPost('comentario_paciente') ?? ''
            ];

            $this->carePlanModel->insert($carePlanData);
            $carePlanId = $this->carePlanModel->getInsertID();

            $carePlanTaskModel = new \App\Models\CarePlanTaskModel();
            foreach ($tasks as $task) {
                $taskData = [
                    'plan_cuidado_id' => $carePlanId,
                    'tipo_meta_id' => $task['tipo_meta_id'],
                    'meta_cumplida' => 0,
                    'descripcion' => $task['descripcion']
                ];
                $carePlanTaskModel->insert($taskData);
            }

            $diagnosisModel->update($diagnosticoId, [
                'plan_cuidado_id' => $carePlanId,
                'estado_id' => 2 // 'en_proceso'
            ]);

            $db->transComplete();

            if ($db->transStatus() === false) {
                return redirect()->back()->withInput()->with('error', 'Error al guardar el plan de cuidado.');
            }

            return redirect()->to('/medical_staff/care-plan')->with('success', 'Plan de cuidado creado correctamente');
        }
    }

    public function edit($carePlanId = null)
    {
        if (!$carePlanId) {
            return redirect()->to('/medical_staff/care-plan')->with('error', 'Plan de cuidado no especificado');
        }

        $carePlan = $this->carePlanModel->find($carePlanId);
        if (!$carePlan) {
            return redirect()->to('/medical_staff/care-plan')->with('error', 'Plan de cuidado no encontrado');
        }

        $diagnosisModel = new \App\Models\DiagnosisModel();
        $diagnosis = $diagnosisModel->select('diagnostico.*, usuario.nombre as paciente_nombre, usuario.apellido as paciente_apellido, tipo_diagnostico.nombre as tipo_diagnostico')
            ->join('usuario', 'usuario.usuario_id = diagnostico.paciente_id')
            ->join('tipo_diagnostico', 'tipo_diagnostico.tipo_diagnostico_id = diagnostico.tipo_diagnostico_id')
            ->where('diagnostico.plan_cuidado_id', $carePlanId)
            ->first();

        $carePlanTaskModel = new \App\Models\CarePlanTaskModel();
        $tasks = $carePlanTaskModel->where('plan_cuidado_id', $carePlanId)->findAll();

        $taskTypeModel = new \App\Models\TaskTypeModel();
        $taskTypes = $taskTypeModel->findAll();

        $data = [
            'carePlan' => $carePlan,
            'diagnosis' => $diagnosis,
            'tasks' => $tasks,
            'taskTypes' => $taskTypes
        ];

        return view('templates/header')
            . view('templates/sidebar')
            . view('doctor/careplan/edit', $data)
            . view('templates/footer');
    }

    public function update()
    {
        $id = $this->request->getPost('care_plan_update_id');
        if (!$id) {
            return redirect()->back()->with('error', 'ID del plan de cuidado no especificado');
        }

        $validationRules = [
            'fec_inicio' => 'required|valid_date[Y-m-d]',
            'fec_fin' => 'required|valid_date[Y-m-d]',
            'comentario_paciente' => 'permit_empty|min_length[3]'
        ];

        if (!$this->validate($validationRules)) {
            return redirect()->back()->withInput()->with('errors', $this->validator->getErrors());
        }

        $tasks = $this->request->getPost('tasks');
        if (empty($tasks) || !is_array($tasks)) {
            return redirect()->back()->withInput()->with('error', 'Debe haber al menos una tarea en el plan de cuidado.');
        }

        foreach ($tasks as $task) {
            if (empty($task['tipo_meta_id']) || empty($task['descripcion'])) {
                return redirect()->back()->withInput()->with('error', 'Todas las tareas deben tener tipo y descripción.');
            }
        }

        $db = \Config\Database::connect();
        $db->transStart();

        $carePlanData = [
            'plan_cuidado_id' => $id,
            'fec_inicio' => $this->request->getPost('fec_inicio'),
            'fec_fin' => $this->request->getPost('fec_fin'),
            'comentario_paciente' => $this->request->getPost('comentario_paciente') ?? ''
        ];

        $this->carePlanModel->save($carePlanData);

        // Delete old tasks and insert updated tasks list (ponytail: clean & simple replacement pattern)
        $carePlanTaskModel = new \App\Models\CarePlanTaskModel();
        $carePlanTaskModel->where('plan_cuidado_id', $id)->delete();

        foreach ($tasks as $task) {
            $taskData = [
                'plan_cuidado_id' => $id,
                'tipo_meta_id' => $task['tipo_meta_id'],
                'meta_cumplida' => isset($task['meta_cumplida']) ? (int)$task['meta_cumplida'] : 0,
                'descripcion' => $task['descripcion']
            ];
            $carePlanTaskModel->insert($taskData);
        }

        $db->transComplete();

        if ($db->transStatus() === false) {
            return redirect()->back()->withInput()->with('error', 'Error al actualizar el plan de cuidado.');
        }

        return redirect()->to('/medical_staff/care-plan')->with('success', 'Plan de cuidado actualizado correctamente');
    }

    public function delete()
    {
        $id = $this->request->getPost('care_plan_id');

        if (!$id) {
            return redirect()->back()->with('error', 'ID del plan de cuidado no especificado');
        }

        $db = \Config\Database::connect();
        $db->transStart();

        $this->carePlanModel->delete($id);

        $carePlanTaskModel = new \App\Models\CarePlanTaskModel();
        $carePlanTaskModel->where('plan_cuidado_id', $id)->delete();

        $diagnosisModel = new \App\Models\DiagnosisModel();
        $diagnosis = $diagnosisModel->where('plan_cuidado_id', $id)->first();
        if ($diagnosis) {
            $diagnosisModel->update($diagnosis['diagnostico_id'], [
                'plan_cuidado_id' => 0,
                'estado_id' => 1 // Pendiente
            ]);
        }

        $db->transComplete();

        if ($db->transStatus() === false) {
            return redirect()->back()->with('error', 'No se pudo eliminar el plan de cuidado');
        }

        return redirect()->to('/medical_staff/care-plan')
            ->with('success', 'Plan de cuidado eliminado correctamente');
    }

    public function validarCumplimiento()
    {
        if (!$this->validate([
            'cumplimiento_id'   => 'required|integer',
            'accion'            => 'required|in_list[validado,rechazado]',
            'puntuacion'        => 'permit_empty|integer|greater_than[0]|less_than_equal_to[5]',
            'comentario_medico' => 'permit_empty',
        ])) {
            return redirect()->back()->with('errors', $this->validator->getErrors());
        }

        $accion = $this->request->getPost('accion');

        if ($accion === 'validado' && empty($this->request->getPost('puntuacion'))) {
            return redirect()->back()->with('error', 'La puntuación es requerida al aprobar un cumplimiento.');
        }

        $model = new \App\Models\ValidacionModel();
        $model->validar(
            (int) $this->request->getPost('cumplimiento_id'),
            [
                'validado_at'       => date('Y-m-d H:i:s'),
                'validado_por'      => (int) session()->get('user_id'),
                'puntuacion'        => $accion === 'validado' ? (int) $this->request->getPost('puntuacion') : null,
                'comentario_medico' => $this->request->getPost('comentario_medico') ?? '',
            ]
        );

        $msg = $accion === 'validado' ? 'Cumplimiento aprobado.' : 'Cumplimiento rechazado.';
        return redirect()->to('/medical_staff/care-plan')->with('success', $msg);
    }

    public function finalizarPlan()
    {
        $id = (int) $this->request->getPost('plan_cuidado_id');
        if (!$id) {
            return redirect()->back()->with('error', 'Plan de cuidado no especificado.');
        }

        // Actualiza el diagnóstico asociado a estado 'finalizado' (estado_id = 3)
        \Config\Database::connect()
            ->table('diagnostico')
            ->where('plan_cuidado_id', $id)
            ->update(['estado_id' => 3]);

        return redirect()->to('/medical_staff/care-plan')
            ->with('success', 'Plan de cuidado finalizado. El diagnóstico fue marcado como finalizado.');
    }
}
