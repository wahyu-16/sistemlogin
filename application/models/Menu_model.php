<?php
defined('BASEPATH') or exit('No direct script access allowed');

class Menu_model extends CI_Model
{
    public function getSubMenu()
    {
        $query = "SELECT `user_sub_menu`.*, `user_menu`.`menu` 
                        FROM `user_sub_menu` JOIN `user_menu` 
                         ON `user_sub_menu`.`menu_id` = `user_menu`.`id` 
                  ";
        return $this->db->query($query)->result_array();
    }
    public function delete($where, $table)
    {
        $this->db->where($where);
        $this->db->delete($table);
    }
    public function getrole($id)
    {
        return $this->db->get_where('user_role', ['id' => $id])->row_array();
    }
    public function editrole()
    {
        $data = [
            "role" => $this->input->post('role', true)
        ];
        $this->db->where('id', $this->input->post('id'));
        $this->db->update('user_role', $data);
    }
    public function getmenu($id)
    {
        return $this->db->get_where('user_menu', ['id' => $id])->row_array();
    }
    public function editMenu()
    {
        $data = [
            "menu" => $this->input->post('menu', true)
        ];
        $this->db->where('id', $this->input->post('id'));
        $this->db->update('user_menu', $data);
    }
    public function getsmedit($id)
    {
        return $this->db->get_where('user_sub_menu', ['id' => $id])->row_array();
    }
    public function editSubMenu()
    {
        $data = [
            "title" => $this->input->post('title', true),
            "menu_id" => $this->input->post('menu_id', true),
            "url" => $this->input->post('url', true),
            "icon" => $this->input->post('icon', true),
            "is_active" => $this->input->post('is_active', true)
        ];
        $this->db->where('id', $this->input->post('id'));
        $this->db->update('user_sub_menu', $data);
    }
}
