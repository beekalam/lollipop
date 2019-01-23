<script src="https://cdn.jsdelivr.net/npm/es6-promise@4/dist/es6-promise.js"></script>

<div class="portlet box red">
    <div class="portlet-title">
        <div class="caption">
            <i class="icon-list"></i>
            <span class="caption-subject bold uppercase">تاریخچه</span>
        </div>
    </div>
    <div class="portlet-body">
        <table class="table table-striped table-bordered persian-number">
            <thead>
            <tr>
                <th>#</th>
                <th>تاریخ ورود</th>
                <th>تاریخ خروج</th>
                <th>مدت استفاده</th>
                <th>هزینه</th>
                <th>عملیات</th>
            </tr>
            </thead>
            <tbody>
            <?php

            $index = 1;
            define("MINUTES_40", 40 * 60);
            foreach ($customer_history as $row) {
                echo "<tr style='direction:ltr;text-align:right;'>";
                et("td", $index++);
                et("td", unix_timestamp_to_jalali($row["checkin"]));
                et("td", unix_timestamp_to_jalali($row["checkout"]));
                $diff = $row["checkout"] - $row["checkin"];
                et("td", formatDuration($diff));
                et("td", format_currency($row["total_with_discount"]), "class='rtl'");
                $factor_id = $row["id"];
                //fixme form
                if ($diff < MINUTES_40) {
                    $form = "<button class='btn btn-default btn-warning btn-circle' id='btn-reopen-factor'
                                onclick='reopen_factor($factor_id)' >
            بازگرداندن فاکتور
                        </button>";
                    et("td", $form);
                } else {
                    et("td", "");
                }
                echo " </tr > ";
            }

            ?>
            </tbody>
        </table>
    </div>
</div>
<script>

    function reopen_factor(factor_id) {
        assert(factor_id, "factor id is not set");

        make_yes_no_swal().then(function (result) {
            if (result.value) {
                $.ajax({
                    type: "POST",
                    cache: false,
                    url: '<?php echo base_url("customers/reopen_factor"); ?>',
                    data: {"factor_id": factor_id},
                    success: function (data) {
                        data = JSON.parse(data);
                        if (data["success"]) {
                            show_info_msg("عملیات با موفقیت انجام شد.");
                            location.reload();
                        } else {
                            show_warning_msg("خطا در انجام عملیات.");
                        }
                    }
                });
            }
        });
    }
</script>