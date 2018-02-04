<div class="portlet box blue-hoki">
    <div class="portlet-title">
        <div class="caption">
            <i class="fa fa-cogs"></i>دسته ها
        </div>
        <div class="actions">
            <a href="javascript:;"
               class="btn btn-default btn-sm" id="new-category">
                <i class="fa fa-plus"></i> دسته جدید </a>
        </div>
    </div>
    <div class="portlet-body">
        <table id="categories-table" class="table table-bordered table-striped table-hovered">
            <thead>
            <tr>
                <!-- <th>عنوان</th> -->
                <th>نام دسته</th>
                <!-- <th>دسته</th> -->
                <th class="no-sort">actions</th>
            </tr>
            </thead>
            <tbody>
            <?php
            foreach ($categories as $v) {
                echo "<tr>";
                    echo "<td>{$v->name}</td>";
                    echo "<td>";
                        echo "<button data-id='". $v->id . "' class='btn btn-sm btn-danger delete-category'>حذف</button>";
                    echo "</td>";
                echo "</tr>";
            }
            ?>
            </tbody>
        </table>
        <script>
            $(document).ready(function () {
                $("#categories-table").DataTable({
                    "ordering": false,
                    "searching":false,
                    "paging": false,
                    "info":false
                });
            });
        </script>
    </div>
</div>

<div class="modal fade" id="new-category-modal" tabindex="-1" role="dialog"
     aria-labelledby="modalLabel" aria-hidden="true">
    <div class="modal-dialog" role="document">
        <div class="modal-content">
            <div class="modal-header">
                <h5 class="modal-title" id="modalLabel">ثبت دسته جدید</h5>
                <button type="button" class="close" data-dismiss="modal" aria-label="Close">
                    <span aria-hidden="true">&times;</span>
                </button>
            </div>
            <div class="modal-body">
                <form action="<?php echo base_url('settings/add_category'); ?>" method="POST" id="new-category-form">
                    <div id="new-category-validate">
                    </div>
                    <div class="form-group">
                        <label for="category-name" class="col-form-label">نام دسته</label>
                        <input type="text" class="form-control" id="category-name" name="category-name" required>
                        <span id="category-name-error"></span>
                    </div>
            </div>
            <div class="modal-footer">
                <button type="button" class="btn btn-secondary" data-dismiss="modal">بستن</button>
                <button type="submit" class="btn btn-primary" id="add-new-category">ثبت</button>
            </div>
            </form>
        </div>
    </div>
</div>

<script>

    $(document).ready(function () {
        $(".delete-category").click(function(e)
        {
               e.preventDefault();
            // alert("in delete_price");
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
                        url: 'settings/delete_category',
                        async: 'false',
                        cache: 'false',
                        type: 'POST',
                        data: {'id': id},
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
                    });
                });
        });

        $("#new-category").click(function()
        {
            $('#new-category-validate').html("");
            $("#category-name").val("");
            $("#new-category-modal").modal();
        });

        $('#new-category-form').on('submit', function (e) {
            var form = $(this);
            e.preventDefault();

            $.ajax({
                url: '<?php echo base_url('settings/ajax_validate_category'); ?>',
                async: 'false',
                cache: 'false',
                type: 'POST',
                data: {"name": $("#category-name").val()},
                success: function (response) {
                    var data = JSON.parse(response);
                    if (data["success"] == false) {
                        var err = "<div class='alert-danger'>" + data["error"] + "</div>";
                        $("#new-category-validate").html(err);
                    } else {
                        form[0].submit()
                    }
                }
            }); //$.ajax
     
        });        
                
    });
</script>