<div class="portlet box red">
    <div class="portlet-title">
        <div class="caption">
            <i class="icon-list"></i>
            <span class="caption-subject bold uppercase">هزینه متفرقه</span>
        </div>
        <div class="actions">
          <?php if(isset($discount_percent)): ?>
            <form method="POST" 
                  action="<?php echo base_url('/customers/remove_discount'); ?>"
                  style="display:inline;">
                  <input type="hidden" value="<?php echo $customer->id; ?>" name="customer-id"/>
                  <input type="hidden" value="<?php echo $customer->cardid; ?>" name="rfid" />
                <input type="submit"
                  class="btn btn-circle btn-default btn-sm remove-discount"
                  value="حذف تخفیف">
            </form>
          <?php endif; ?>
            <a href="javascript:;" 
                  class="btn btn-circle btn-default btn-sm show-discount-modal" 
                  data-action="insert">
                <i class="fa fa-plus"></i> تخفیف </a>
            <a href="javascript:;" 
                class="btn btn-circle btn-default btn-sm show-add-modal" 
                data-action="insert">
                <i class="fa fa-plus"></i> اضافه </a>
        </div>
    </div>
    <div class="portlet-body">
       <table id="prices-table" class="table table-bordered table-striped table-hovered persian-number">
           <thead>
               <tr>
                   <th>عنوان</th>
                   <th>قیمت</th>
                   <th>تعداد</th>
                   <th>مجموع</th>
                   <th>عملیات</th>
               </tr>
           </thead>
           <tfoot>
             <tr>
              <th colspan="4" style="text-align:left;">مجموع:</th>
              <th> <?php echo number_format($total); ?> ریال</th>
             </tr>
             <tr>
               <th colspan="4" style="text-align: left;">مجموع بااحتساب ورودی</th>
               <th> <?php echo number_format($total+$vorodi); ?> ریال</th>
             </tr>

            <?php if(isset($discount_percent)): ?>
               <tr>
                 <th colspan="4" style="text-align: left;">مجموع با احتساب تخفیف</th>
                 <th><?php echo number_format($total_with_discount); ?> ریال</th>
               </tr>
            <?php endif; ?>

           </tfoot>
           <tbody>
               <?php 
                   $fmt = "<tr><td>%s</td><td>%s</td><td>%s</td><td>%s</td><td>%s</td></tr>";
                   $delete_btn_fmt = "<button data-id='%s' class='btn btn-sm btn-danger btn-circle delete-extra-service'>حذف</button>";
                   // $edit_btn_fmt = "<button data-id='%s' class='btn btn-sm btn-danger edit-price' data-cat-id='%s' data-description='%s' data-price='%s' data-action='update'>edit</button>";
                   foreach ($extra_services as  $v) {
                        $delete_btn=sprintf($delete_btn_fmt,$v->id);
                        echo sprintf($fmt,$v->description,number_format($v->price)
                              ,$v->num,number_format($v->price * $v->num),$delete_btn);
                   //      $edit_btn =sprintf($edit_btn_fmt,$v->id,$v->cat_id,$v->description,$v->price);
                   //      echo sprintf($fmt,$v->description,$v->price,$v->cat_name,$delete_btn,$edit_btn);
                    }
                ?>
           </tbody>
       </table>
       <script>
           $(document).ready(function()
           {
               $("#prices-table").DataTable({
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
                },
               });    
           });
       </script>
    </div>
</div>