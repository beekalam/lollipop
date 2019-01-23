<div class="page-content-wrapper">
    <!-- BEGIN CONTENT BODY -->
    <div class="page-content">
        <!-- BEGIN PAGE HEADER-->

            <div class="page-bar"></div>
            <div class="row">
                  <div class="col-md-12">
                    <div class="portlet box blue-hoki">
                        <div class="portlet-title">
                          <div class="caption">
                             <i class="icon-bubbles"></i> لیست کاربران
                          </div>
                          <div class="actions">
                              <a href="javascript:;"
                                 class="btn btn-default btn-sm btn-circle" id="new-user">
                                  <i class="fa fa-plus"></i> کاربر جدید </a>
                          <?php if($isadmin): ?>
                           <a href="<?php echo base_url('users/roles') ?>"
                                 class="btn btn-default btn-sm btn-circle">
                                  <i class="fa fa-plus"></i> مدیریت رولها </a>                                  
                          <?php endif; ?>
                          </div>
                        </div>
                        <div class="portlet-body">
                          
                          <table border="0" class="table table-responsive table-bordered table-striped" 
                                id="customers-table">
                            <thead>
                                <tr>
                                    <th>نام</th>
                                    <th>نام کاربری</th>
                                    <td>نوع کاربر</td>
                                    <td>عملیات</td>
                                </tr>
                            </thead>
                            <tbody>
                               <?php 
                               	foreach ($users as $value) {
                               		echo "<tr>";
                               			echo "<td>" . $value->first_name ." " . $value->last_name . "</td>";
                               			echo "<td>" . $value->user_name . "</td>";
                               			echo "<td>";
                               				if($value->isadmin)
                               					echo "<span class='label label-sm label-info border-radius-5px'> admin </span>";
                               				else if($value->role_name != 'NA')
                               					echo "<span class='label label-sm label-success border-radius-5px'> {$value->role_name} </span>";
                               			echo "</td>";
                               			echo "<td>";
                                      if($value->user_name !== 'admin' && $isadmin){
                               				   echo "<button class='btn btn-circle btn-sm btn-danger delete-user' data-id='" . $value->id . "' >حذف کابر</button>";
                               			  	echo "<button class='btn  btn-circle btn-sm btn-success change-password' data-id='" . $value->id . "'> تغییر پسورد</button>";
                                         echo  "<button class='btn btn-circle btn-sm btn-info change-role'
                                         data-id='" . $value->id . "'>تغییر رول</button>";
                                        }
                               			echo "</td>";
                               		echo "</tr>";
                               	}
                                ?>
                            </tbody>
                          </table>
                          
  <div class="modal fade" id="change-password-modal" tabindex="-1" role="dialog" aria-labelledby="modalLabel"
             aria-hidden="true">
            <div class="modal-dialog" role="document">
                <div class="modal-content">
                    <div class="modal-header">
                        <h5 class="modal-title persian-font" id="modalLabel">تغییر گذر واژه</h5>
                        <button type="button" class="close" data-dismiss="modal" aria-label="Close">
                            <span aria-hidden="true">&times;</span>
                        </button>
                    </div>
                    <div class="modal-body">
                        <form method="POST" action="<?php echo base_url('users/change_password') ?>"
                              id="">

                            <div class="form-group">
                                <label for="new-password" class="col-form-label">گذر واژه جدید</label>
                                <input type="text" class="form-control" 
                                                  id="new-password"
                                                  name="new-password">
                            </div>
                           
                    </div>
                    <div class="modal-footer">
                        <button type="button" class="btn btn-secondary" data-dismiss="modal">بستن</button>
                        <input type="hidden" value="" id="user-id" name="user-id" />
                        <button type="submit" class="btn btn-primary">ثبت</button>
                    </div>
                    </form>
                </div>
            </div>
  </div>

     <div class="modal fade" id="new-user-modal" tabindex="-1" role="dialog" aria-labelledby="modalLabel"
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
                        <form method="POST" action="<?php echo base_url('users/new_user') ?>"
                              id="">

                            <div class="form-group">
                                <label for="first_name" class="col-form-label">نام</label>
                                <input type="text" class="form-control" 
                                                  id="first_name"
                                                  name="first_name" required>
                            </div>

                            <div class="form-group">
                                <label for="last_name" class="col-form-label">نام خانوادگی</label>
                                <input type="text" class="form-control" 
                                                  id="last_name"
                                                  name="last_name" required>
                            </div>

                            <div class="form-group">
                                <label for="username" class="col-form-label">نام کاربری</label>
                                <input type="text" class="form-control" 
                                                  id="username"
                                                  name="username" required>
                            </div>

                            <div class="form-group">
                                <label for="password" class="col-form-label">گذر واژه</label>
                                <input type="text" class="form-control" 
                                                  id="password"
                                                  name="password" required>
                            </div>                          
                            <div class="form-group">
                              <label for="pass" class="col-form-label">انتخاب رول</label>
                              <select name='role_type' class='form-control'>
                                <?php foreach($roles as $role)
                                {
                                  echo "<option value='{$role['id']}'>{$role['name']}</option>";
                                }
                                ?>
                              </select>
                            </div>
                    </div>
                    <div class="modal-footer">
                        <button type="button" class="btn btn-secondary" data-dismiss="modal">بستن</button>
                        <input type="hidden" value="" id="user-id" name="user-id" />
                        <button type="submit" class="btn btn-primary">ثبت</button>
                    </div>
                    </form>
                </div>
            </div>
  </div>


                      
  <div class="modal fade" id="change-role-modal" tabindex="-1" role="dialog" aria-labelledby="modalLabel"
             aria-hidden="true">
            <div class="modal-dialog" role="document">
                <div class="modal-content">
                    <div class="modal-header">
                        <h5 class="modal-title" id="modalLabel">تغییر رول</h5>
                        <button type="button" class="close" data-dismiss="modal" aria-label="Close">
                            <span aria-hidden="true">&times;</span>
                        </button>
                    </div>
                    <div class="modal-body">
                        <form method="POST" action="<?php echo base_url('users/change_role') ?>"
                              id=""d>

                            <div class="form-group">
                                <label for="role_type" class="col-form-label">تغییر رول</label>
                                <select name="role_type" class="form-control">
                                <?php                                  
                                  foreach($roles as $role){
                                    echo "<option value='{$role['id']}'>{$role['name']}</option>";
                                  }
                                ?>
                                </select>
                            </div>
                           
                    </div>
                    <div class="modal-footer">
                        <button type="button" class="btn btn-secondary" data-dismiss="modal">بستن</button>
                        <input type="hidden" name='user_id' id='user_id_role_form' value="" />
                        <button type="submit" class="btn btn-primary">ثبت</button>
                    </div>
                    </form>
                </div>
            </div>
  </div>

                          <script>
                              var roles=[];
                              <?php 
                                foreach($roles as $role){
                                   echo "roles['" . $role['id'] . "']='"  .$role['name']  ."';";
                                }
                              ?>
                              

                              $(document).ready(function()
                              {
                                  $("#customers-table").DataTable({
                                    	"searchign":false,
                                    	"sorting":false,
                                    	"ordering":false,
                                       scrollY: 300,
                                       scroller: {
                                              loadingIndicator: true
                                      },
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

                                  $(document).on('click',".delete-user",function()
                                  {
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
      				                                url: '<?php echo base_url('users/delete_user'); ?>',
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
      				                                    	window.location="<?php echo base_url('users/users') ?>"
      				                                    }
      				                                }
      				                            }); //$.ajax
      				                        });
                                  });

                                  $(document).on('click','.change-password',function()
                                  {
                                        var id = $(this).attr("data-id");
                                        $("#user-id").attr("value",id);
                                        $("#change-password-modal").modal();
                                  });

                                  $("#new-user").click(function()
                                  {
                                    $("#new-user-modal").modal();
                                  })

                                  $(".change-role").click(function()
                                  {
                                    $user_id = $(this).attr("data-id");
                                    $("#user_id_role_form").val($user_id);
                                    // console.log($("#user_id_role_form").val());
                                    // return;
                                    
                                    $("#change-role-modal").modal();
                                  })
                              });
                          </script>
                        </div>
                    </div>
                </div>
            </div>

    </div>
</div>
