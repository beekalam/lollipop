<div class="page-content-wrapper">
    <!-- BEGIN CONTENT BODY -->
    <div class="page-content">
        <!-- BEGIN PAGE HEADER-->
        <div class="page-bar"> </div>
        <?php if($user): ?>
        <div class="row">
            <!-- profile info -->
            <div class="col-xs-12 col-md-6">
                <div class="portlet box green">
                    <div class="portlet-title">
                        <div class="caption">
                            <i class="icon-user"></i>
                            <span class="caption-subject bold uppercase">
                                 مشخصات
                                </span>
                        </div>
                    </div>
                    <div class="portlet-body form">
                        <form role="form" method="POST" action="<?php echo base_url() . 'users/edit'; ?>" enctype="multipart/form-data">
                            <input type="hidden" value="<?php echo $user->id; ?>" name="id" />
                            <?php if(!empty(validation_errors())): ?>
                            <div class="alert alert-danger">
                                <?php echo validation_errors(); ?>
                            </div>
                            <?php endif; ?>
                            <div class="form-body">
                                <div class="form-group">
                                    <label class="col-md-3 control-label">نام</label>
                                    <div class="col-md-9">
                                        <input type="text" id="first_name" name="first_name" class="form-control input-sm" placeholder="نام" value="<?php echo $user->first_name; ?>">
                                    </div>
                                </div>
                            </div>
                            <div class="form-body">
                                <div class="form-group">
                                    <label class="col-md-3 control-label">نام خانوادگی</label>
                                    <div class="col-md-9">
                                        <input type="text" id="last_name" name="last_name" class="form-control input-sm" placeholder="نام خانوادگی" value="<?php echo $user->last_name; ?>">
                                    </div>
                                </div>
                                <div class="form-group" style="padding-bottom: 100px;">
                                    <label for="" class="col-md-3 control-label">عکس پروفایل</label>
                                    <div class="col-md-9">
                                        <img id="avatar_preview" src="<?php echo $_SESSION['profile_img']; ?>" class="form-control" style="width: 100px; height: 100px; text-align: left; float: left; float: left !important; border-top: 10px; margin-top: 15px; " />

                                         <span id="upload_btn" class="btn green fileinput-button" style="margin-top: 68px;">
                                                <i class="fa fa-plus"></i>
                                                <span> تصویر جدید </span>
                                        </span>
                                                <input type="file" name="profile_file"  
                                                                   id="profile_file" class="hidden">


                                    </div>
                                </div>
                            </div>
                            <div class="form-actions">
                                <button type="submit" class="btn blue btn-circle">ثبت</button>
                                <input type="hidden" value="" name="img_profile" id="img_profile" />
                                <!-- <button type="button" class="btn default">لغو</button> -->
                            </div>
                        </form>
                    </div>
                </div>
            </div>
            <!-- change password -->
            <div class="col-xs-12 col-md-6">
                <div class="portlet box green">
                    <div class="portlet-title">
                        <div class="caption">
                            <i class="icon-user"></i>
                            <span class="caption-subject bold uppercase">
                                 تغییر پسورد
                                </span>
                        </div>
                    </div>
                    <div class="portlet-body form">
                        <form role="form" method="POST" action="<?php echo base_url() . 'users/user_change_password'; ?>">
                            <input type="hidden" value="<?php echo $user->id; ?>" name="id" />
                            <?php if(!empty(validation_errors())): ?>
                            <div class="alert alert-danger">
                                <?php echo validation_errors(); ?>
                            </div>
                            <?php endif; ?>
                            <div class="form-body">
                                <div class="form-group">
                                    <label class="col-md-3 control-label">پسورد قبلی</label>
                                    <div class="col-md-9">
                                        <input type="password" id="previous_password" name="previous_password" class="form-control input-sm" placeholder="پسورد">
                                    </div>
                                </div>
                            </div>
                            <div class="form-body">
                                <div class="form-group">
                                    <label class="col-md-3 control-label">پسورد جدید</label>
                                    <div class="col-md-9">
                                        <input type="password" id="new_password" name="new_password" class="form-control input-sm" placeholder="پسورد جدید">
                                    </div>
                                </div>
                            </div>
                            <div class="form-body">
                                <div class="form-group">
                                    <label class="col-md-3 control-label">تکرار پسورد</label>
                                    <div class="col-md-9">
                                        <input type="password" id="repeat_password" name="repeat_password" class="form-control input-sm" placeholder="تکرار پسورد">
                                    </div>
                                </div>
                            </div>
                            <div class="form-actions">
                                <button type="submit" class="btn blue btn-circle">ثبت</button>
                            </div>
                        </form>
                    </div>
                </div>
            </div>
        </div>
        <?php endif; ?>
    </div>
</div>
<script>
$(document).ready(function() {
    $("#upload_btn").click(function()
    {
        $("#profile_file").click();
    });

    $("input[name='profile_file']").change(function(e)
    {
        console.log(e);
        console.log("finished upload");
        files = [];
        $.each(e.target.files, function(index,file){
            var reader = new FileReader();
            reader.onload = function(event) {
                object = {};
                object.filename = file.name;
                object.data = event.target.result;
                files.push(object);
                try {
                    if(files[0] && files[0].data){
                        console.log(files[0].data);
                        $("#img_profile").val(files[0].data);
                        $('#avatar_preview').prop("src", files[0].data);
                    }
                } catch (e) {
                    // console.log(e);
                    // info(e);
                }
            };
            reader.readAsDataURL(file);
        });
    });

    // $("body").on("change", "#avatar", function(event) {
    //     files = []; 
    //     $.each(event.target.files, function(index, file) {
    //         var reader = new FileReader();
    //         reader.onload = function(event) {
    //             object = {};
    //             object.filename = file.name;
    //             object.data = event.target.result;
    //             files.push(object);
    //             try {
    //                 $('#avatar_preview').prop("src", files[0].data);
    //             } catch (e) {
    //                 info(e);
    //             }
    //         };
    //         reader.readAsDataURL(file);
    //     });
    // });
});
</script>