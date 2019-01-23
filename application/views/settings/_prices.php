<div class="portlet box blue-hoki" id="prices_box">
    <div class="portlet-title">
        <div class="caption">
            <i class="fa fa-cogs"></i>هزینه ها
        </div>
        <div class="actions">
            <?php if(check_perm('add_extra_service')): ?>
                <a href="javascript:;" class="btn btn-default btn-sm show-add-modal" data-action="insert">
                    <i class="fa fa-plus"></i> اضافه 
                </a>
            <?php endif; ?>
        </div>
    </div>
    <div class="portlet-body">
        <table id="prices-table" class="table table-bordered table-striped table-hovered">
            <thead>
            <tr>
                <th>عنوان</th>
                <th>قیمت</th>
                <th>دسته</th>
                <th class="no-sort">عملیات</th>
            </tr>
            </thead>
            <tbody class="persian-number">
            <?php
            $delete_btn_fmt = "<button data-id='%s' class='btn btn-sm btn-circle btn-danger delete-price'>حذف</button>";
            $edit_btn_fmt = "<button data-id='%s' class='btn btn-sm btn-circle btn-success edit-price' data-cat-id='%s' data-description='%s' data-price='%s' data-action='update'>ویرایش</button>";
            foreach ($prices as $v) {
                
                echo "<tr>";
                    et("td",$v->description);
                    et("td", format_currency($v->price));
                    et("td", $v->cat_name);
                    echo "<td>";
                        echo check_perm('delete_extra_service') ?  sprintf($delete_btn_fmt, $v->id) : "";
                        
                        echo check_perm('edit_extra_service') ?  
                                sprintf($edit_btn_fmt, $v->id, $v->cat_id, $v->description, $v->price) : "";
                        
                    echo "</td>";
                echo "</tr>";
            }
            ?>
            </tbody>
        </table>
        <script>
            $(document).ready(function () {
                $("#prices-table").DataTable({
                    "ordering": true,
                    "searching":true,
                    "paging": true,
                    "info":false,
                    columnDefs: [{
                        orderable: false,
                        targets: "no-sort"
                    }],
                      "language": {
                        "emptyTable": "هیچ رکوردی یافت نشد",
                        'search':'جستجو',
                        'info':'نمایش صفحه _PAGE_ از _PAGES_',
                        'infoEmpty':'هیچ رکوردی یافت نشده'
                    }                    
                });
            });
        </script>
    </div>
</div>

<div class="modal fade" id="modal" tabindex="-1" role="dialog"
     aria-labelledby="modalLabel" aria-hidden="true">
    <div class="modal-dialog" role="document">
        <div class="modal-content">
            <div class="modal-header">
                <h5 class="modal-title" id="modalLabel">ثبت هزینه جدید</h5>
                <button type="button" class="close" data-dismiss="modal" aria-label="Close">
                    <span aria-hidden="true">&times;</span>
                </button>
            </div>
            <div class="modal-body">
                <form>
                    <div class="form-group">
                        <label for="recipient-name" class="col-form-label">انتخاب گروه</label>
                        <select id="category-select" class="form-control category-select">
                            <option value="" disabled selected>دسته ها</option>
                            <?php
                            foreach ($categories as $v) {
                                echo sprintf("<option value='%s'>%s</otpion>", $v->id, $v->name);
                            }
                            ?>
                        </select>
                        <span id="item-select-error"></span>
                    </div>

                    <div class="form-group">
                        <label for="recipient-name" class="col-form-label">عنوان هزینه</label>
                        <input type="text" class="form-control" id="item-description" name="item-description">
                        <span id="item-description-error"></span>
                    </div>
                    <div class="form-group">
                        <label for="message-text" class="col-form-label">قیمت</label>
                        <input type="text" class="form-control" id="item-price" name="item-price">
                        <span id="item-price-error"></span>
                    </div>
                </form>
            </div>
            <div class="modal-footer">
                <button type="button" class="btn btn-secondary" data-dismiss="modal">بستن</button>
                <button type="button" class="btn btn-primary" id="add-new-price">ثبت</button>
                <input type="hidden" value="insert" id="action"/>
                <input type="hidden" value="" id="price_id"/>
            </div>
        </div>
    </div>
</div>

<script>

    $(document).ready(function () {

<?php if(check_perm('edit_extra_service')): ?>
        $(".edit-price").click(function () {
            $(".category-select option[value='" + $(this).attr("data-cat-id") + "']").attr('selected', 'selected');
            $("#item-description").val($(this).attr("data-description"));
            $("#item-price").val($(this).attr("data-price"));
            $("#action").val("update");
            $("#price_id").val($(this).attr("data-id"));
            setTimeout(function () {
                $("#modal").modal();
            }, 100);
        });
<?php endif; ?>

        $(".show-add-modal").click(function () {
            $("#item-description").val("");
            $("#item-price").val("");
            $("#action").val("insert");
            $("#modal").modal();
        });

<?php if(check_perm('add_extra_service')): ?>        

        $(document).on('click', '#add-new-price', function () {
            var action = $("#action").val();

            if (!$("#item-description").val()) {
                $("#item-description-error").html('عنوان هزینه وارد نشده است');
                $("#item-description-error").addClass('text-danger');
                return;
            } else {
                $("#item-description-error").html("");
            }

            if (!$("#item-price").val()) {
                $("#item-price-error").html("قیمت مشخص نشده است");
                $("#item-price-error").addClass('text-danger');
                return;
            } else {
                $("#item-price-error").html("");
            }

            if (!$("select#category-select option:checked").val()) {
                $("#item-select-error").html("هیچ دسته ای انتخاب نشده است");
                $("#item-select-error").addClass('text-danger');
                return;
            } else {
                $("#item-select-error").html("");
            }

            var data = {
                "price": $("#item-price").val(),
                "description": $("#item-description").val(),
                "cat_id": $("select#category-select option:checked").val(),
                "action": $("#action").val()
            };

            if ($("#action").val() == "update") {
                data["price_id"] = $("#price_id").val();
            }
            $.ajax({
                url: 'settings/add_update_price',
                async: 'false',
                cache: 'false',
                type: 'POST',
                data: data,
                success: function (response) {
                    var data = JSON.parse(response);
                    if (data["success"] == false) {
                        // $("#codetxt").css("background-color", "orange");
                        alert(data["msg"]);
                    } else {
                        window.location = "<?php echo base_url("settings"); ?>"
                    }
                }
            }); //$.ajax

        });
<?php endif; ?>

<?php if(check_perm('add_extra_service')): ?>
        $(".delete-price").click(function (e) {
            e.preventDefault();
            // alert("in delete_price");
            var id = $(this).attr("data-id");

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
                        url: 'settings/delete_price',
                        async: 'false',
                        cache: 'false',
                        type: 'POST',
                        data: {'id': id},
                        success: function (response) {
                            var data = JSON.parse(response);
                            if (data["success"] == false) {
                                swal("", "خطا در حذف", "warning")
                            } else {
                                swal("", "عملیات با موفقیت انجام شد.", "success")
                                window.location = "<?php echo base_url("settings"); ?>"
                            }
                        }
                    });
                });

        });
<?php endif; ?>


    });
</script>
