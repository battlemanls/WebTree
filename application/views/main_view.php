<div class="main_block">
    <div class="error_block">
        <?php
        if(isset($data['error'])){
            echo $data['error'];
        }
        ?>
    </div>

        <?php if($data==false){ ?>
    <div class = "root ">
        <a id = 'create_root' class="btn btn-primary" href="/<?php echo $NAME_SITE?>/main/add_root">Create root</a>    </div>
        <?php }
        else{
            ?>
    <div hidden class = "root ">
        <a ="true" id = 'create_root' class="btn btn-primary" href="/<?php echo $NAME_SITE?>/main/add_root">Create root</a>     </div>
            <?php
        }
        ?>

    <ul class = 'ul_tree' id="block_tree">
        <?php
        render_tree($data, 0);
        function render_tree($data,$parent_id = 0 ) {
            global $NAME_SITE;
            //Conditions for exiting recursion
            if(empty($data[$parent_id])) {
                ?>
                <ul class = 'ul_tree_2'></ul>
              <?php  return;



            } ?>
            <ul class = 'ul_tree_2'>
                 <?php  for($i = 0; $i < count($data[$parent_id]);$i++) {
                    ?>
                       <?php
                       $o = $data[$parent_id][$i]['id'];
                       if (isset($data[$o]) && is_array($data[$o]) && count($data[$o])>0){
                           ?>
                           <li class="li_tree" id = " <?php echo $data[$parent_id][$i]['id'] ?>"><div class="li_bloc"><span class="caret"></span>

                       <?php    }else{ ?>
                           <li class="li_tree" id = " <?php echo $data[$parent_id][$i]['id'] ?>"><div class="li_bloc">
                               <?php    } ?>

                    <div ><div class="name_block"><?php echo $data[$parent_id][$i]['text'] ?></div></div>
                       <div ><a class='add_r'  href="/<?php echo $NAME_SITE?>/main/add">+</a> </div>
                        <?php if($data[$parent_id][$i]['parent_id']  == 0){ ?>
                            <div ><a id = 'root'  href="/<?php echo $NAME_SITE ?>/main/sub" data-toggle='modal' data-target='#exampleModal'>-</a> </div>
                       <?php }
                       else
                           {?>
                               <div><a class='sub_r'  href="/<?php echo $NAME_SITE ?>/main/sub">-</a> </div>
                       <?php } ?>
                       </div>

                   <?php render_tree($data,$data[$parent_id][$i]['id']);?>
                    </li>
                <?php   } ?>
            </ul>
        <?php }?>
    </ul>
</div>

<div class="modal fade" id="exampleModal" tabindex="-1" role="dialog" aria-labelledby="exampleModalLabel" aria-hidden="true">
    <div class="modal-dialog" role="document">
        <div class="modal-content">
            <div class="modal-header">
                <h5 class="modal-title" id="exampleModalLabel">Delete confirm</h5>
                <button type="button"  class="sub_r_cancel close" data-dismiss="modal" aria-label="Close">
                    <span aria-hidden="true">&times;</span>
                </button>
            </div>
            <div class="modal-body">
                Are you sure
            </div>
            <div class="modal-footer">
                <div id = 'modal_timer'>20</div>
                <button  type="button" class=" sub_r_cancel btn btn-secondary" data-dismiss="modal">No</button>
                <button id="sub_r_confirm" type="button" class="btn btn-primary">Yes</button>
            </div>
        </div>
    </div>
</div>


<div class="modal fade" id="change_name" tabindex="-1" role="dialog" aria-labelledby="change_nameModalLabel" aria-hidden="true">
    <div class="modal-dialog" role="document">
        <div class="modal-content">
            <div class="modal-header">
                <h5 class="modal-title" id="change_nameModalLabel">Change name</h5>
                <button type="button"  class="sub_r_cancel close" data-dismiss="modal" aria-label="Close">
                    <span aria-hidden="true">&times;</span>
                </button>
            </div>
            <div class="modal-body">

                <form id="form_change_name" name="form_change_name">
                    <div class="form-group">
                        <label for="recipient-name" class="col-form-label">Name: </label>
                        <input name="new_name" type="text" class="form-control" id="recipient-name">
                    </div>
                </form>


            </div>
            <div class="modal-footer">
                <button  type="button" class="sub_n_cancel btn btn-secondary" data-dismiss="modal">No</button>
                <button form="form_change_name"  type="submit" class="btn btn-primary">Change</button>
            </div>
        </div>
    </div>
</div>

<script>

    $(function () {
        /**
         * save dom object with a name block
         @type{object}
         * */

        let name_block;

        /**
         * save the id of the element for which the name will change
         @type{integer}
         * */

        let id;

        /**
         * listener for clicking on a block named and calling the modal menu
         */

        $('#block_tree').on('click', '.name_block', function (e) {
            e.preventDefault();
            name_block = this;
            $('#change_name').modal('show');
            id = $(this).parent().parent().parent().attr('id');
        });

        /**
         * Listener for submitting form data
         */

        $('#form_change_name').on('submit', function (e) {
            e.preventDefault();
            let name = this.new_name.value;
            change_name(id, name);
            if (change_name){
                $(name_block).html(name);
                $('#change_name').modal('hide');
            }
        });

        /**
         *  listener for closing a form
         */

        $('.sub_n_cancel').on('click', function(){
            $('#change_name').modal('hide');
        })

        /**
         *  listener for calling the function to create a root
         */

        $('#create_root').on('click', function(e){
            e.preventDefault();
            create_root();
        });

        /**
         *  listener for fold and expand the menu
         */

        $('#block_tree').on('click', ".caret", function(e){
            e.preventDefault();
            $(this).toggleClass("caret-down", "caret");
            $(this).closest('.li_tree').find($('.ul_tree_2')).toggleClass("nested", "active");

        });

        /**
         *  listener for adding new record
         */

        $('#block_tree').on('click','.add_r', function (e) {
            e.preventDefault();
            let parent_li = $(this).parent().parent().parent();
            let parent_id = parent_li.attr('id');
            let li_block = $(this).parent().parent()

            if($(this).closest('.li_bloc').children().is(".caret")==false) {
                $(this).closest('.li_bloc').prepend("<span class='caret'></span>")
            }
            add_tree(parent_id, parent_li);
        });

        /**
         *  listener for deleting record
         */

        $('#block_tree').on('click','.sub_r', function (e) {
            e.preventDefault();
            let select_li = $(this).closest('.li_tree');
            let parent_ul = $(select_li).closest('.ul_tree_2').parent();
            let id = select_li.attr('id');
            let res = sub_tree(id, select_li);
            if(res){
                let count_cat = $(parent_ul).children('.ul_tree_2').children().length;
                if(count_cat<2){
                    $(parent_ul).children('.li_bloc').children('.caret').remove();
                }
            }
        });

        /**
         *  listener for deleting all record and call modal menu
         */

        $('#block_tree').on('click', '#root', function (e) {
            e.preventDefault();
            let select_li = $(this).parent().parent().parent();
            let id = select_li.attr('id');
            const time = $('#modal_timer');
            intervalId = setInterval(timerDecrement, 1000);
            function timerDecrement() {
                const newTime = time.text() - 1;
                time.text(newTime);
                $('#sub_r_confirm').on('click', function(){
                    clearInterval(intervalId);
                    $('#exampleModal').modal('hide');
                    $('#create_root').parent().removeAttr('hidden');
                    $('#create_root').parent().show();
                    sub_tree(id, select_li);
                })

                $('.sub_r_cancel').on('click', function(){
                    clearInterval(intervalId);
                    $('#exampleModal').modal('hide');
                })
                if(newTime === 0){
                    clearInterval(intervalId);
                    $('#exampleModal').modal('hide');
                }
            }
            $('#modal_timer').html(20)
        });

        /**
         * ajax request to remove item and remove item from page on success
         * @param {number} id
         * @param {Object} select_li
         */

        function sub_tree(id, select_li) {
           return $.ajax({
                    headers: {
                    },
                    url: "/<?php echo $NAME_SITE?>/main/sub",
                    data: {id: id },
                    cache: false,
                    type: 'POST',
                    success: function (res) {
                        if(res) {
                            select_li.remove()
                            return true;
                        }
                    },
                    error: function (file, response) {
                        return "Ошибка!";
                    },
                }
            )
        }

        /**
         * ajax request to adding item and adding item from page on success
         * @param {number} parent_id
         * @param {Object} parent_li
         */

        function add_tree(parent_id, parent_li) {
            $.ajax({
                    headers: {
                    },
                    url: "/<?php echo $NAME_SITE?>/main/add",
                    data: {parent_id: parent_id},
                    cache: false,
                    type: 'POST',
                    success: function (res) {
                        if(res) {
                            let dataofconfirm = JSON.parse(res);
                            let d = $(parent_li).children('.ul_tree_2');
                            d.append(

                                " <li class='li_tree' id = " + dataofconfirm.id + ">" +
                                    "<div class='li_bloc'>"+
                                "<div>" + "<div class='name_block'>root</div>" + "</div>" +
                                "<div><a class='add_r'  href='/<?php echo $NAME_SITE?>/main/add'>+</a> </div> " +
                                "<div><a class='sub_r'  href='/<?php echo $NAME_SITE?>/main/sub'>-</a> </div> " +
                                    "</div>" +
                                   " <ul class = 'ul_tree_2'> </ul>" +
                                "</li>"

                            );
                        }
                    },
                    error: function (file, response) {
                        return "Ошибка!";
                    },
                }
            )
        }


        /**
         * ajax request to adding root item and adding item from page on success
         */

        function create_root() {
            $.ajax({
                    headers: {
                    },
                    url: "/<?php echo $NAME_SITE?>/main/add_root",
                    cache: false,
                    type: 'POST',
                    success: function (res) {
                        if(res) {
                            $('#create_root').parent().hide();
                            let dataofconfirm = JSON.parse(res);
                            $('#block_tree').append(
                                "<ul class = 'ul_tree_2'>" +
                                " <li class='li_tree' id = " + dataofconfirm.id + ">" +
                                "<div class='li_bloc'>"+
                                "<div>" + "<div class='name_block'>root</div>" + "</div>" +
                                "<div><a class='add_r'  href='/<?php echo $NAME_SITE?>/main/add'>+</a> </div> " +
                                "<div><a id = 'root'  href='/<?php echo $NAME_SITE?>/main/sub' data-toggle='modal' data-target='#exampleModal'>-</a> </div> " +
                                "</div>"+
                                " <ul class = 'ul_tree_2'> </ul>" +
                                "</li>" +
                                "</ul>"
                            );
                        }
                    },
                    error: function (file, response) {
                        return "Ошибка!";
                    },
                }
            )
        }

        /**
         * ajax request to change record
         * @param {number} id
         * @param {string} name
         * @return boolean
         */

        function change_name(id, name) {
            $.ajax({
                    headers: {
                    },
                    url: "/<?php echo $NAME_SITE?>/main/change_name",
                    data: {id: id, name: name},
                    cache: false,
                    type: 'POST',
                    success: function (res) {
                        return true
                    },
                    error: function (file, response) {
                        return false;
                    },
                }
            )
        }





    });

</script>