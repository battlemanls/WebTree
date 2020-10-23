<?php

require_once 'application/models/model_tree.php';

class Controller_Main extends Controller
{

    function __construct()
    {
        $this->model = new Model_Tree();
        $this->view = new View();
    }

    function action_index()
    {
        $data = $this->model->get_data();
        if($data) {
            $this->view->generate('main_view.php', 'template_view.php', $data);
        }
        else{
            $this->view->generate('main_view.php', 'template_view.php');
        }
    }

    function action_add()
    {
        if (isset($_POST['parent_id'])) {
            $parent_id = $_POST['parent_id'];
            $tree = new Model_Tree();
            $id = $tree->add($parent_id);
            $data = ['id' => $id, 'parent_id' => $parent_id];
            echo json_encode($data);
        }
    }

    function action_sub(){
        if (isset($_POST['id'])) {
            $id = $_POST['id'];
            $tree = new Model_Tree();
            $res = $tree->sub($id);
            if($res){
                $data = ['id' => $id];
                echo json_encode($data);
            }
            else{
                echo false;
            }

        }
    }

    function action_add_root()
    {
        $tree = new Model_Tree();
        $id = $tree->add();
        if($id){
            $data = ['id' => $id, 'parent_id' => $id];
            echo json_encode($data);
        }
        else{
            echo false;
        }

    }

}
