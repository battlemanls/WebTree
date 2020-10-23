<div class="main_block">
    <div class = "root">
        <?php if($data==false){ ?>
        <a id = 'create_root' href="/<?php echo $NAME_SITE?>/main/add_root">Create root</a>
        <?php }
        else{
            ?>
        <a hidden="true" id = 'create_root' href="/<?php echo $NAME_SITE?>/main/add_root">Create root</a>
            <?php
        }
        ?>



    </div>

    <div class="error_block">
        <?php
        if(isset($data['error'])){
            echo $data['error'];
        }
        ?>
    </div>


    <ul class = 'ul_tree' id="block_tree">
        <?php
        render_tree($data, 0);
        function render_tree($data,$parent_id = 0 ) {
            global $NAME_SITE;
            //Условия выхода из рекурсии
            if(empty($data[$parent_id])) {
                return;
            } ?>

            <ul class = 'ul_tree_2'>
                 <?php  for($i = 0; $i < count($data[$parent_id]);$i++) {
                    ?>


                   <li class="li_tree" id = " <?php echo $data[$parent_id][$i]['id'] ?>">
                       <div class="li_bloc">


                       <?php
                       $o = $data[$parent_id][$i]['id'];
                       if (isset($data[$o]) && is_array($data[$o]) && count($data[$o])>1){
                           ?>

                           <button> <?php echo "^" ?></button>


                       <?php    } ?>


                    <div class='col-12'><div><?php echo $data[$parent_id][$i]['text'] ?></div></div>
                       <div class='col-6'><a class='add_r'  href="/<?php echo $NAME_SITE?>/main/add">+</a> </div>
                        <?php if($data[$parent_id][$i]['parent_id']  == 0){ ?>
                            <div class='col-6'><a id = 'root'  href="/<?php echo $NAME_SITE ?>/main/sub" data-toggle='modal' data-target='#exampleModal'>-</a> </div>
                       <?php }
                       else
                           {?>
                               <div class='col-6'><a class='sub_r'  href="/<?php echo $NAME_SITE ?>/main/sub">-</a> </div>
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

<script>

    $(function () {

        $('#create_root').on('click', function(e){
            e.preventDefault();
            create_root();
        });

        $('#block_tree').on('click','.add_r', function (e) {
            e.preventDefault();
            let parent_li = $(this).parent().parent().parent();
            let parent_id = parent_li.attr('id');
            console.log('sss');
            add_tree(parent_id, parent_li);
        });

        $('#block_tree').on('click','.sub_r', function (e) {
            e.preventDefault();
            let select_li = $(this).parent().parent().parent();
            let id = select_li.attr('id');
            sub_tree(id, select_li);
        });

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
                    $('#create_root').removeAttr('hidden');
                    $('#create_root').show();
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

        function sub_tree(id, select_li) {
            $.ajax({
                    headers: {
                    },
                    url: "/<?php echo $NAME_SITE?>/main/sub",
                    data: {id: id },
                    cache: false,
                    type: 'POST',
                    success: function (res) {
                        if(res) {
                            select_li.remove()
                        }
                    },
                    error: function (file, response) {
                        return "Ошибка!";
                    },
                }
            )
        }

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
                            parent_li.append(
                                "<ul class = 'ul_tree_2'>" +
                                " <li id = " + dataofconfirm.id + ">" +
                                    "<div class='li_bloc'>"+
                                "<div class='col-12'>" + "<div>Root</div>" + "</div>" +
                                "<div class='col-6'><a class='add_r'  href='/<?php echo $NAME_SITE?>/main/add'>+</a> </div> " +
                                "<div class='col-6'><a class='sub_r'  href='/<?php echo $NAME_SITE?>/main/sub'>-</a> </div> " +
                                    "</div>" +
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

        function create_root() {
            $.ajax({
                    headers: {
                    },
                    url: "/<?php echo $NAME_SITE?>/main/add_root",
                    cache: false,
                    type: 'POST',
                    success: function (res) {
                        if(res) {
                            $('#create_root').hide();
                            let dataofconfirm = JSON.parse(res);
                            $('#block_tree').append(
                                "<ul class = 'ul_tree_2'>" +
                                " <li id = " + dataofconfirm.id + ">" +
                                "<div class='li_bloc'>"+
                                "<div class='col-12'>" + "<div>Root</div>" + "</div>" +
                                "<div class='col-6'><a class='add_r'  href='/<?php echo $NAME_SITE?>/main/add'>+</a> </div> " +
                                "<div class='col-6'><a id = 'root'  href='/<?php echo $NAME_SITE?>/main/sub' data-toggle='modal' data-target='#exampleModal'>-</a> </div> " +
                                "</div>"+
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
    });

</script>