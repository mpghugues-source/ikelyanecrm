<?php

namespace App\Controllers\Erp;

use App\Controllers\BaseController;
use App\Models\Erp\ProjectModel;
use App\Models\Erp\ProjectTaskModel;
use App\Models\UserModel;

class ProjectController extends BaseController
{
    private ProjectModel     $model;
    private ProjectTaskModel $taskModel;

    public function __construct()
    {
        $this->model     = new ProjectModel();
        $this->taskModel = new ProjectTaskModel();
    }

    public function index(): string
    {
        $tid      = session()->get('tenant_id');
        $projects = $this->model->getWithChef($tid);
        return view('erp/projects/index', ['projects' => $projects]);
    }

    public function create(): string
    {
        $tid   = session()->get('tenant_id');
        $users = (new UserModel())->where('tenant_id', $tid)->findAll();
        return view('erp/projects/form', ['project' => null, 'users' => $users]);
    }

    public function store(): \CodeIgniter\HTTP\RedirectResponse
    {
        $tid  = session()->get('tenant_id');
        $data = $this->request->getPost([
            'nom','description','statut','priorite','date_debut','date_fin',
            'budget','chef_projet',
        ]);
        $data['tenant_id']  = $tid;
        $data['created_by'] = session()->get('user_id');
        $this->model->insert($data);
        return redirect()->to('/erp/projects')->with('success', lang('Erp.project_saved'));
    }

    public function view(int $id): string
    {
        $tid     = session()->get('tenant_id');
        $project = $this->model->where('tenant_id', $tid)->find($id);
        if (!$project) return redirect()->to('/erp/projects');
        $tasks = $this->taskModel->getWithAssignee($id);
        $users = (new UserModel())->where('tenant_id', $tid)->findAll();
        return view('erp/projects/view', [
            'project' => $project,
            'tasks'   => $tasks,
            'users'   => $users,
        ]);
    }

    public function edit(int $id): string
    {
        $tid     = session()->get('tenant_id');
        $project = $this->model->where('tenant_id', $tid)->find($id);
        if (!$project) return redirect()->to('/erp/projects');
        $users = (new UserModel())->where('tenant_id', $tid)->findAll();
        return view('erp/projects/form', ['project' => $project, 'users' => $users]);
    }

    public function update(int $id): \CodeIgniter\HTTP\RedirectResponse
    {
        $tid  = session()->get('tenant_id');
        $data = $this->request->getPost([
            'nom','description','statut','priorite','date_debut','date_fin',
            'budget','cout_reel','chef_projet',
        ]);
        $this->model->where('tenant_id', $tid)->update($id, $data);
        return redirect()->to('/erp/projects')->with('success', lang('Erp.project_updated'));
    }

    public function delete(int $id): \CodeIgniter\HTTP\RedirectResponse
    {
        $tid = session()->get('tenant_id');
        $this->model->where('tenant_id', $tid)->update($id, ['statut' => 'cancelled']);
        return redirect()->to('/erp/projects')->with('success', lang('Erp.project_cancelled'));
    }

    // ── TASKS ─────────────────────────────────────────────────────────

    public function storeTask(int $projectId): \CodeIgniter\HTTP\RedirectResponse
    {
        $tid     = session()->get('tenant_id');
        $project = $this->model->where('tenant_id', $tid)->find($projectId);
        if (!$project) return redirect()->to('/erp/projects');
        $data = $this->request->getPost([
            'titre','description','statut','priorite','assigned_to','date_echeance','heures_estimees',
        ]);
        $data['project_id'] = $projectId;
        $this->taskModel->insert($data);
        $this->taskModel->updateProjectProgress($projectId);
        return redirect()->to("/erp/projects/{$projectId}/view")->with('success', lang('Erp.task_saved'));
    }

    public function updateTask(int $projectId, int $taskId): \CodeIgniter\HTTP\RedirectResponse
    {
        $data = $this->request->getPost([
            'titre','statut','priorite','assigned_to','date_echeance','heures_estimees','heures_reelles',
        ]);
        $this->taskModel->update($taskId, $data);
        $this->taskModel->updateProjectProgress($projectId);
        return redirect()->to("/erp/projects/{$projectId}/view")->with('success', lang('Erp.task_updated'));
    }

    public function deleteTask(int $projectId, int $taskId): \CodeIgniter\HTTP\RedirectResponse
    {
        $this->taskModel->delete($taskId);
        $this->taskModel->updateProjectProgress($projectId);
        return redirect()->to("/erp/projects/{$projectId}/view")->with('success', lang('Erp.task_deleted'));
    }
}
