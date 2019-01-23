<div class="portlet box blue-hoki">
    <div class="portlet-title">
        <div class="caption">
            <i class="icon-grid"></i>
            تعداد حاضران(<?php echo isset($active_customers_count) ? $active_customers_count : 0; ?>)
        </div>
    </div>

    <div class="portlet-body">
        <div class="portlet-toolbar">
            <div class="row">
                <div class="col-xs-12">
                    <table class="table table-striped table-bordered" id="last-customers">
                        <thead>
                        <tr>
                            <th>نام</th>
                            <th>ساعت ورود</th>
                            <th>مدت حضور</th>
                            <th>موبایل</th>
                            <th>عملیات</th>
                        </tr>
                        </thead>
                        <tbody class="persian-number">
                        <?php
                        foreach ($active_customers as $v) {
                            $span       = "<span class='checkin' data-checkin='" . intVal(time() - $v->checkin) . "'>";
                            $span       .= formatDuration(time() - $v->checkin) . "</span>";
                            $user_panel = "<a class='btn btn-sm btn-success btn-circle' href='" . base_url() . "/customers/manage?id=" . $v->cardid . "'>پنل</a>";
                            echo "<tr>";
                            echo "<td>{$v->first_name} {$v->last_name}</td>";
                            echo "<td>" . unix_timestamp_to_jalali($v->checkin) . "</td>";
                            echo "<td>" . $span . "</td>";
                            echo "<td>" . $v->mobile . "</td>";
                            echo "<td>" . $user_panel . "</td>";
                            echo "</tr>";
                        }
                        ?>
                        </tbody>
                    </table>
                    <script>
                        $(document).ready(function () {
                            $("#last-customers").dataTable({
                                "language": {
                                    "sEmptyTable": "هیچ داده ای در جدول وجود ندارد",
                                    "sInfo": "نمایش _START_ تا _END_ از _TOTAL_ رکورد",
                                    "sInfoEmpty": "نمایش 0 تا 0 از 0 رکورد",
                                    "sInfoFiltered": "(فیلتر شده از _MAX_ رکورد)",
                                    "sInfoPostFix": "",
                                    "sInfoThousands": ",",
                                    "sLengthMenu": "نمایش _MENU_ رکورد",
                                    "sLoadingRecords": "در حال بارگزاری...",
                                    "sProcessing": "در حال پردازش...",
                                    "sSearch": "جستجو:",
                                    "sZeroRecords": "رکوردی با این مشخصات پیدا نشد",
                                    "oPaginate": {
                                        "sFirst": "ابتدا",
                                        "sLast": "انتها",
                                        "sNext": "بعدی",
                                        "sPrevious": "قبلی"
                                    },
                                    "oAria": {
                                        "sSortAscending": ": فعال سازی نمایش به صورت صعودی",
                                        "sSortDescending": ": فعال سازی نمایش به صورت نزولی"
                                    }
                                },
                            });

                        });
                    </script>
                </div>
            </div>
        </div>
    </div>
</div>
