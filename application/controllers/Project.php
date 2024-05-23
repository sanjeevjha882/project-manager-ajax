<?php
defined('BASEPATH') or exit('No direct script access allowed');

class Project extends CI_Controller
{
    public function __construct()
    {
        parent::__construct();
        $this->load->library('form_validation');
        $this->load->library('session');
        $this->load->model('Project_model', 'project');
    }

    /*
      View page of project
    */
    public function index()
    {
        $data['title'] = 'CodeIgniter Project Manager';
        $this->load->view('projects', $data);
    }

    /*
      Get all records 
    */
    public function show_all()
    {
        $projects = $this->project->get_all();
        header('Content-Type: application/json');
        echo json_encode($projects);
    }

    /*
      Get a record
    */
    public function show($id)
    {
        $project = $this->project->get($id);
        if ($project) {
            header('Content-Type: application/json');
            echo json_encode($project);
        } else {
            http_response_code(404);
            echo json_encode(['status' => 'error', 'message' => 'Project not found']);
        }
    }

    /*
      Save the submitted record
    */
    public function store()
    {
        $this->form_validation->set_rules('name', 'Name', 'required');
        $this->form_validation->set_rules('description', 'Description', 'required');

        if (!$this->form_validation->run()) {
            http_response_code(412);
            header('Content-Type: application/json');
            echo json_encode([
                'status' => 'error',
                'errors' => validation_errors()
            ]);
        } else {
            if ($this->project->store()) {
                header('Content-Type: application/json');
                echo json_encode(['status' => 'success']);
            } else {
                http_response_code(500);
                echo json_encode(['status' => 'error', 'message' => 'Failed to save project']);
            }
        }
    }

    /*
      Edit a record 
    */
    public function edit($id)
    {
        $project = $this->project->get($id);
        if ($project) {
            header('Content-Type: application/json');
            echo json_encode($project);
        } else {
            http_response_code(404);
            echo json_encode(['status' => 'error', 'message' => 'Project not found']);
        }
    }

    /*
      Update the submitted record
    */
    public function update($id)
    {
        $this->form_validation->set_rules('name', 'Name', 'required');
        $this->form_validation->set_rules('description', 'Description', 'required');

        if (!$this->form_validation->run()) {
            http_response_code(412);
            header('Content-Type: application/json');
            echo json_encode([
                'status' => 'error',
                'errors' => validation_errors()
            ]);
        } else {
            if ($this->project->update($id)) {
                header('Content-Type: application/json');
                echo json_encode(['status' => 'success']);
            } else {
                http_response_code(500);
                echo json_encode(['status' => 'error', 'message' => 'Failed to update project']);
            }
        }
    }

    /*
      Delete a record
    */
    public function delete($id)
    {
        if ($this->project->delete($id)) {
            header('Content-Type: application/json');
            echo json_encode(['status' => 'success']);
        } else {
            http_response_code(500);
            echo json_encode(['status' => 'error', 'message' => 'Failed to delete project']);
        }
    }
}
