<div class="page-content-wrapper">
    <!-- BEGIN CONTENT BODY -->
    <div class="page-content">
        <!-- BEGIN PAGE HEADER-->
                
            <div class="page-bar"></div>
            <div class="row">
                <!-- vorodi  -->
                <!-- <div class="col-md-6 col-xs-12"> -->
                   <!-- //$this->load->view('settings/_old_vorodi.php');  -->
                <!-- </div> -->
                
                <!-- vorodi test  -->
                <div class="col-md-6 col-sm-12">
                  <?php $this->load->view('settings/_vorodi.php'); ?>
                </div>
                <!-- prices  -->
                <div class="col-md-6 col-sm-12">
                  <?php $this->load->view('settings/_prices.php'); ?>
                </div>

                <!-- categories  -->
                <div class="col-md-6 col-sm-12">
                  <?php $this->load->view('settings/_categories.php'); ?>
                </div>
                
            </div>
    </div>
</div>

