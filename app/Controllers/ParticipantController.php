<?php

namespace App\Controllers;

use App\Models\ParticipantModel;
use CodeIgniter\RESTful\ResourceController;
//HINAHANDLE LANG NETO LAHAT NG CRUD OPERATIONS

class ParticipantController extends ResourceController
{
    protected $modelName = 'App\Models\ParticipantModel';
    protected $format    = 'json';

    public function index()
    {
        return $this->respond($this->model->getParticipants());
    }

    public function create()
    {
        $data = $this->request->getPost();
        $this->model->insert($data);
        return $this->respondCreated($data);
    }

    public function update($id = null)
    {
        $data = $this->request->getRawInput();
        $this->model->update($id, $data);
        return $this->respond($data);
    }

    public function delete($id = null)
    {
        $this->model->delete($id);
        return $this->respondDeleted(['message' => 'Participant deleted']);
    }
}
