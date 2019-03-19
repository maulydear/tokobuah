<?php defined('BASEPATH') OR exit('No direct script access allowed');

class Product_model extends CI_Model
{
    private $_table = "products";

    public $product_id;
    public $name;
    public $price;
    public $image='.image/';
    public $description;

    public function rules()
    {
        return [
            ['field' => 'name',
            'label' => 'Name',
            'rules' => 'required'],

            ['field' => 'price',
            'label' => 'Price',
            'rules' => 'numeric'],
            
            ['field' => 'description',
            'label' => 'Description',
            'rules' => 'required']
        ];
    }


    public function getAll()
    {
        return $this->db->get($this->_table)->result();
    }
    
    public function getById($id)
    {
        return $this->db->get_where($this->_table, ["product_id" => $id])->row();
    }

    public function save()
    {
        $config['upload_path']='./image/';
        $config['allowed_types']='gif|jpg|png';
        $post = $this->input->post();
        $this->product_id = uniqid();
        $this->name = $post["name"];
        $this->price = $post["price"];
        $this->upload->initialize($config);
        $upload=$this->upload->do_upload('image');
        if($upload)
        {
            $this->image=base_url().'image/'.$this->upload->data('file_name');
            $this->description = $post["description"];
            $this->db->insert($this->_table, $this);
        }else{
             $this->session->set_flashdata('error', $this->upload->display_errors('',''));
        }
        
    }

    public function update()
    {
        $config['upload_path']='./image/';
        $config['allowed_types']='gif|jpg|png';
        $post = $this->input->post();
        $this->product_id = $post["id"];
        $this->name = $post["name"];
        $this->price = $post["price"];
        $this->upload->initialize($config);
        $upload=$this->upload->do_upload('image');
        if($upload)
        {
            $this->image=base_url().'image/'.$this->upload->data('file_name');
            $this->description = $post["description"];
            $this->db->update($this->_table, $this, array('product_id' => $post['id']));
        }else{
             $this->session->set_flashdata('error', $this->upload->display_errors('',''));
        }
    }

    public function delete($id)
    {
        return $this->db->delete($this->_table, array("product_id" => $id));
    }
}