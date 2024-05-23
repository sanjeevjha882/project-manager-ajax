<?php
class Project_model extends CI_Model
{
    public function __construct()
    {
        parent::__construct();
        $this->load->database();
        $this->load->helper('url');
    }

    /*
        Get all the records from the database
    */
    public function get_all()
    {
        $query = $this->db->get("projects");
        return $query->result();
    }

    /*
        Store the record in the database
    */
    public function store()
    {
        $data = [
            'name' => $this->input->post('name', TRUE),
            'description' => $this->input->post('description', TRUE),
            'status' => $this->input->post('status', TRUE)
        ];

        $result = $this->db->insert('projects', $data);
        return $result ? $this->db->insert_id() : false;
    }

    /*
        Get a specific record from the database
    */
    public function get($id)
    {
        $query = $this->db->get_where('projects', ['id' => $id]);
        return $query->row();
    }

    /*
        Update or Modify a record in the database
    */
    public function update($id)
    {
        $data = [
            'name' => $this->input->post('name', TRUE),
            'description' => $this->input->post('description', TRUE),
            'status' => $this->input->post('status', TRUE)
        ];

        $this->db->where('id', $id);
        return $this->db->update('projects', $data);
    }

    /*
        Destroy or Remove a record in the database
    */
    public function delete($id)
    {
        return $this->db->delete('projects', ['id' => $id]);
    }
}
?>
