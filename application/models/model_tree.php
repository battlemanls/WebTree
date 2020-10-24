<?php

/**
 * Model Model_Tree
 *
 */

class Model_Tree extends Model{


    /**
     * reading and returning all data from the database
     * @return array tree data
     */

    public function get_data()
    {
        $db = DB::get_db();
        $res = pg_query($db, "WITH RECURSIVE sub_tree(id, parent_id, text, level) AS (
        SELECT id, parent_id, text, 1 FROM tree WHERE parent_id=0 UNION ALL 
        SELECT t.id, t.parent_id, t.text, level+1
	    FROM tree t, sub_tree st
	    WHERE t.parent_id = st.id ) SELECT id, parent_id, text, level FROM sub_tree ORDER BY id;");

        if($res) {
            $mass = [];
            while ($row = pg_fetch_assoc($res)) {
                //Forming an array, where the keys are id to parent categories
                if (empty($mass[$row['parent_id']])) {
                    $mass[$row['parent_id']] = array();
                }
                $mass[$row['parent_id']][] = $row;
            }
            return $mass;
        }
        else{
            return false;
        }
        }

    /**
     * adding data to the database, returning the record id if successful
     * @return integer tree data
     */

    public function add($parent_id=0, $text = 'root'){
        $db = DB::get_db();
        $res = pg_query($db, "INSERT INTO tree (parent_id, text) VALUES ('$parent_id', '$text') RETURNING id");

        //get last id
        if($res) {
            $row = pg_fetch_row($res);
            $new_id = $row['0'];
            return $new_id;
        }
        else{
            return false;
        }

    }

    /**
     *deleting a record from the database, returning a true record if successful
     * @return boolean
     */

    public function sub($id){
        $db = DB::get_db();

        $res = pg_query($db, "WITH RECURSIVE sub_tree(id, parent_id, text, level) AS (
        SELECT id, parent_id, text, 1 FROM tree WHERE parent_id='$id' UNION ALL 
        SELECT t.id, t.parent_id, t.text, level+1
	    FROM tree t, sub_tree st
	    WHERE t.parent_id = st.id ) DELETE FROM tree WHERE parent_id IN (SELECT parent_id FROM sub_tree);");

        $res_2 = pg_query($db, " DELETE FROM tree WHERE id = '$id'");
        if ($res && $res_2) {
            return true;
        }
        else{
            return false;
        }

    }

    /**
     *change name a record from the database, returning a true record if successful
     * @return boolean
     */

    public function change_name($id, $name){

        $db = DB::get_db();
        $res = pg_query($db, "UPDATE tree SET text = '$name' WHERE id = '$id'");
        if ($res){
            return true;
        }
        else{
            return false;
        }

    }

}