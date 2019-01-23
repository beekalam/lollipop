<div class="portlet box blue-hoki">
    <div class="portlet-title">
        <div class="caption">
            <i class="fa fa-cogs"></i>تخفیف ها
        </div>
        <div class="actions">

            <?php if(check_perm('add_discount')): ?>
                <a href="javascript:;"class="btn btn-default btn-sm" id="new-discount">
                    <i class="fa fa-plus"></i> تخفیف جدید 
                </a>
            <?php endif; ?>
        </div>
    </div>
    <div class="portlet-body">
        <table id="discounts-table" class="table table-bordered table-striped table-hovered">
            <thead>
            <tr>
                <!-- <th>عنوان</th> -->
                <th>مقدار تخفیف</th>
                <!-- <th>دسته</th> -->
                <th class="no-sort">عملیات</th>
            </tr>
            </thead>
            <tbody>
            <?php
            foreach ($discounts as $v) {
                echo "<tr>";
                    echo "<td class='persian-number'>{$v->value} %</td>";
                    echo "<td>";
                        if(check_perm('delete_discount')){
                            echo "<button data-id='". $v->id . "' class='btn btn-sm btn-circle btn-danger delete-discount'>حذف
                            <i class='icon-trash'><i>
                            </button>";
                        }
                    echo "</td>";
                echo "</tr>";
            }
            ?>
            </tbody>
        </table>
        <script>
            $(document).ready(function () 
            {
                $("#discounts-table").DataTable({
                    "ordering": false,
                    "searching":false,
                    "paging": false,
                    "info":false,
                "language": {
                    "sEmptyTable":     "هیچ داده ای در جدول وجود ندارد",
                    "sInfo":           "نمایش _START_ تا _END_ از _TOTAL_ رکورد",
                    "sInfoEmpty":      "نمایش 0 تا 0 از 0 رکورد",
                    "sInfoFiltered":   "(فیلتر شده از _MAX_ رکورد)",
                    "sInfoPostFix":    "",
                    "sInfoThousands":  ",",
                    "sLengthMenu":     "نمایش _MENU_ رکورد",
                    "sLoadingRecords": "در حال بارگزاری...",
                    "sProcessing":     "در حال پردازش...",
                    "sSearch":         "جستجو:",
                    "sZeroRecords":    "رکوردی با این مشخصات پیدا نشد",
                    "oPaginate": {
                      "sFirst":    "ابتدا",
                      "sLast":     "انتها",
                      "sNext":     "بعدی",
                      "sPrevious": "قبلی"
                    },
                    "oAria": {
                      "sSortAscending":  ": فعال سازی نمایش به صورت صعودی",
                      "sSortDescending": ": فعال سازی نمایش به صورت نزولی"
                    }
                }});
            });
        </script>
    </div>
</div>

<div class="modal fade" id="new-discount-modal" tabindex="-1" role="dialog"
     aria-labelledby="modalLabel" aria-hidden="true">
    <div class="modal-dialog" role="document">
        <div class="modal-content">
            <div class="modal-header">
                <h5 class="modal-title" id="modalLabel">ثبت تخفیف جدید</h5>
                <button type="button" class="close" data-dismiss="modal" aria-label="Close">
                    <span aria-hidden="true">&times;</span>
                </button>
            </div>
            <div class="modal-body">
                <form action="<?php echo base_url('settings/add_discount'); ?>" method="POST" id="new-discount-form">
                    <div id="new-discount-validate">
                    </div>
                    <div class="form-group">
                        <label for="discount-value" class="col-form-label">تخفیف</label>
                        <input type="number" min="0" max="100" class="form-control" 
                               id="discount-value" 
                               name="discount-value"  required> 
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
<?php if(check_perm('delete_discount')): ?>        
        $(".delete-discount").click(function(e)
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
                        url: 'settings/delete_discount',
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
<?php endif; ?>


<?php if(check_perm('add_discount')): ?>
        $("#new-discount").click(function()
        {
            // $('#new-category-validate').html("");
            // $("#category-name").val("");
            $("#new-discount-modal").modal();
        });

        $('#new-discount-form').on('submit', function (e) {
            var form = $(this);
            e.preventDefault();

            $.ajax({
                url: '<?php echo base_url('settings/ajax_validate_discount'); ?>',
                async: 'false',
                cache: 'false',
                type: 'POST',
                data: {"what": $("#discount-value").val()},
                success: function (response) {
                    var data = JSON.parse(response);
                    if (data["success"] == false) {
                        var err = "<div class='alert-danger'>" + data["error"] + "</div>";
                        $("#new-discount-validate").html(err);
                    } else {
                        form[0].submit()
                    }
                }
            }); //$.ajax
     
        });
<?php endif; ?>        
                
    });
</script>