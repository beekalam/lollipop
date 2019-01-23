<div class="portlet box blue-hoki">
    <div class="portlet-title">
        <div class="caption">
            <i class="icon-grid"></i>
            کاربران
        </div>
    </div>

    <div class="portlet-body">
        <div class="portlet-toolbar">
            <div class="row">
                <div class="col-xs-12">
                    <table class="table table-striped table-bordered" id="last-customers">
                        <thead>
                        <tr>
                            <th>نام کاربری</th>
                            <th>نام</th>
                            <th>موجودی صندوق</th>
                        </tr>
                        </thead>
                        <tbody class="persian-number">
                        <?php
                        foreach ($user_summary as $v) {
                            echo "<tr>";
                            et("td", $v["user_name"]);
                            et("td", $v["first_name"] . " " . $v["last_name"]);
                            et("td", format_currency($v["sum"]));
                            echo "</tr>";
                        }
                        ?>
                        </tbody>
                    </table>
                </div>
            </div>
        </div>
    </div>
</div>