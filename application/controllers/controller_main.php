<?php

require_once 'application/models/model_tree.php';


/**
 * Main Controller
 *
 */


class Controller_Main extends Controller
{

    function __construct()
    {
        $this->model = new Model_Tree();
        $this->view = new View();
    }


    /**
     * reading and returning all data from the database and generating view
     * @return view tree data
     */

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


    /**
     * ajax
     * adding data to the database, returning the record id and parent_id
     * @return json tree data
     */

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

    /**
     * ajax
     * deleting a record from the database, returning a true record if successful
     * @return bool tree data
     */

    function action_sub(){
        if (isset($_POST['id'])) {
            $id = $_POST['id'];
            $tree = new Model_Tree();
            $res = $tree->sub($id);
            if($res){
                echo true;
            }
            else{
                echo false;
            }

        }
    }

    /**
     * ajax
     * adding root data to the database, returning the record id and parent_id
     * @return json tree data
     */

    function action_add_root()
    {
        $tree = new Model_Tree();
        $id = $tree->add();
        if($id){
            $data = ['id' => $id, 'parent_id' => 0];
            echo json_encode($data);
        }
        else{
            echo false;
        }

    }


    /**
     * ajax
     * change name a record from the database, returning a true record if successful
     * @return bool tree data
     */

    function action_change_name(){
        if(isset($_POST['id']) && isset($_POST['name'])){
            $id = $_POST['id'];
            $name = $_POST['name'];

            $tree = new Model_Tree();
            $res = $tree->change_name($id, $name);
            if ($res){
                echo true;
            }
            else {
                echo false;
            }
        }


    }

}
