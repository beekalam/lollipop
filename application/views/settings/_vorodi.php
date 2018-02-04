<div class="portlet box blue-hoki">
    <div class="portlet-title">
        <div class="caption">
            <i class="fa fa-cogs"></i>بازه های زمانی
        </div>
        <div class="actions">
            <a href="javascript:;"
               class="btn btn-default btn-sm" id="new-vorodi">
                <i class="fa fa-plus"></i>بازه جدید</a>
        </div>
    </div>
    <div class="portlet-body">
        <table id="vorodi-table" class="table table-bordered table-striped table-hovered">
            <thead>
            <tr>
                <th class="no-sort">توضیحات</th>
                <th>بازه</th>
                <th>قیمت</th>
                <th class="no-sort">عملیات</th>
            </tr>
            </thead>
            <tbody>
            <?php
            foreach ($vorodi as $v) 
            {
                echo "<tr>";
                echo "<td>{$v->description}</td>";
                echo "<td>";
                        $tooltip=" data-toggle='tooltip' title='" . formatDuration($v->duration) ."'";
                        $duration = substr(formatDuration($v->duration),0,5);
                    echo "<span". $tooltip . ">". $duration  . "</span>";
                echo "</td>";
                echo "<td>{$v->price}</td>";
                echo "<td>";
                    echo  "<button data-id='{$v->id}' data-price='{$v->price}' data-duration='{$v->duration}' data-description='{$v->description}' class='btn btn-sm btn-success edit-vorodi'>ویرایش</button>";
                    echo "<button class='btn btn-sm btn-danger delete-vorodi' data-id={$v->id}'>حذف</button>";
                echo "</td>";
                echo "</tr>";
            }
            ?>
            </tbody>
        </table>
        <div class="modal fade" id="vorodi-modal" tabindex="-1" role="dialog" aria-labelledby="modalLabel"
             aria-hidden="true">
            <div class="modal-dialog" role="document">
                <div class="modal-content">
                    <div class="modal-header">
                        <h5 class="modal-title" id="modalLabel"></h5>
                        <button type="button" class="close" data-dismiss="modal" aria-label="Close">
                            <span aria-hidden="true">&times;</span>
                        </button>
                    </div>
                    <div class="modal-body">
                        <form method="POST" action="<?php echo base_url('settings/add_duration') ?>"
                              id="new-vorodi-form">
                            <div id="vorodi-validaions"></div>

                            <div class="form-group">
                                <label for="vorodi-description" class="col-form-label">توضیحات</label>
                                <input type="text" class="form-control" id="vorodi-description"
                                       name="vorodi-description">
                                <span id="vorodi-description-error" class="text-danger"></span>
                            </div>

                            <div class="form-group" id="duration-form-group">
                                <label for="vorodi-duration" class="col-form-label">مدت(ساعت:دقیقه)</label>
                                <input type="text" class="form-control" 
                                       id="vorodi-duration" name="vorodi-duration">
                                <span id="vorodi-description-error"></span>
                            </div>
                            <style>
                                .bootstrap-timepicker-widget{
                                        left: 773.5px !important;
                                        direction: ltr !important;
                                }
                            </style>
                            <script>
                                $(document).ready(function()
                                {
                                    $("#vorodi-duration").timepicker({
                                        minuteStep:1,
                                        showInputs:false,
                                        disableFocus:true,
                                        showSeconds:false,
                                        showMeridian: false,
                                        defaultTime: false
                                    });
                                });
                            </script>
                            <div class="form-group">
                                <label for="vorodi-price" class="col-form-label">قیمت</label>
                                <input type="text" class="form-control" id="vorodi-price" name="vorodi-price">
                                <span id="vorodi-price-error"></span>
                            </div>
                    </div>
                    <div class="modal-footer">
                        <button type="button" class="btn btn-secondary" data-dismiss="modal">بستن</button>
                        <button type="submit" class="btn btn-primary" id="add-new-duration">ثبت</button>
                        <input type="hidden" value="new" id="vorodi-action" name="voridi-action">
                        <input type="hidden" value="" name="vorodi-id" id="vorodi-id">
                    </div>
                    </form>
                </div>
            </div>
        </div>
        <script>
            $(document).ready(function () {
                $("#vorodi-table").DataTable({
                    "ordering": false,
                    "searching":false
                    // columnDefs: [{
                    //     orderable: false,
                    //     targets: "no-sort"
                    // }]
                });

                $("#new-vorodi").click(function () {
                    $("#vorodi-validaions").html("");
                    $("#vorodi-duration").val("");
                    $("#vorodi-duration").removeAttr("disabled");
                    $("#vorodi-price").val("");
                    $("#vorodi-description").val("");
                    $("#vorodi-modal").modal();
                    $("voridi-action").val("new");
                    $("#duration-form-group").show();
                });

                $('#new-vorodi-form').on('submit', function (e) {
                    var form = $(this);
                    //prevent the submit
                    e.preventDefault();
                    var valid = true;
                    var error = "";
                    if($("#vorodi-duration").val()=="" || $("#vorodi-duration").val()=="0:00"){
                        valid=false;
                        error="مدت مقدار نامعتبر دارد.";                        
                    }

                    if(!valid)
                    {
                        var err = "<div class='alert-danger'>" + error + "</div>";
                        $("#vorodi-validaions").html(err);
                    }
                    else if($("#vorodi-action").val()=="new")
                    {
                        $.ajax({
                            url: '<?php echo base_url('settings/validate_duration'); ?>',
                            async: 'false',
                            cache: 'false',
                            type: 'POST',
                            data: {"what": $("#vorodi-duration").val()},
                            success: function (response) {
                                var data = JSON.parse(response);
                                if (data["success"] == false) {
                                    var err = "<div class='alert-danger'>" + data["msg"] + "</div>";
                                    $("#vorodi-validaions").html(err);
                                } else {
                                    form[0].submit()
                                }
                            }
                        }); //$.ajax
                    }else{
                        form[0].submit();
                    }
                });

                $(".edit-vorodi").on('click', function () {
                    $("#vorodi-validaions").html("");
                    $("#vorodi-duration").val($(this).attr("data-duration"));
                    $("#vorodi-duration").attr("disabled","disabled");
                    $("#vorodi-price").val($(this).attr("data-price"));
                    $("#vorodi-description").val($(this).attr("data-description"));
                    $("#vorodi-action").val("update");
                    $("#new-vorodi-form").attr("action","<?php echo base_url('settings/update_duration'); ?>")
                    $("#vorodi-id").val($(this).attr("data-id"));
                    $("#duration-form-group").hide();
                    $("#vorodi-modal").modal();
                });

                $('.delete-vorodi').on('click',function(e)
                {
                        e.preventDefault();
                        var id = $(this).attr("data-id");
                        var thisrow = $(this).parents("tr");
                        swal({
                            title: "",
                            text: "",
                            type: "warning",
                            showCancelButton: true,
                            confirmButtonClass: "btn-danger",
                            confirmButtonText: "حذف",
                            cancelButtonText: 'بازگشت',
                            closeOnConfirm: false
                        },
                        function () {

                             $.ajax({
                                url: '<?php echo base_url('settings/delete_duration'); ?>',
                                async: 'false',
                                cache: 'false',
                                type: 'POST',
                                data: {"what": id},
                                success: function (response) {
                                    var data = JSON.parse(response);
                                    if (data["success"] == false) {
                                        var msg = data["error"] || "خطا در حذف";
                                        swal("", msg , "warning")
                                    } else {
                                        swal("", "عملیات با موفقیت انجام شد.", "success")
                                        thisrow.remove().draw();
                                    }
                                }
                            }); //$.ajax
                        });

                });
            });
        </script>
    </div>
</div>
