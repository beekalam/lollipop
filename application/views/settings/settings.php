<div class="page-content-wrapper">
    <!-- BEGIN CONTENT BODY -->
    <div class="page-content">
        <!-- BEGIN PAGE HEADER-->

        <div class="page-bar"></div>
        <div class="row">

            <!-- vorodi  -->
            <?php if (check_perm('view_durations')): ?>
                <div class="col-sm-6 col-md-12 col-xs-12" id="durations_wrapper">
                    <?php $this->load->view('settings/_vorodi.php'); ?>
                </div>
            <?php endif; ?>

            <!-- prices  -->
            <?php if (check_perm('view_extra_service')): ?>
                <div class="col-sm-6 col-md-12 col-xs-12" id="prices_wrapper">
                    <?php $this->load->view('settings/_prices.php'); ?>
                </div>
            <?php endif; ?>

            <!-- categories  -->
            <?php if (check_perm('view_categories')): ?>
                <div class="col-sm-6 col-md-12 col-xs-12" id="categories_wrapper">
                    <?php $this->load->view('settings/_categories.php'); ?>
                </div>
            <?php endif; ?>

            <!-- categories  -->
            <?php if (check_perm('view_discounts')): ?>
                <div class="col-sm-6 col-md-12 col-xs-12" id="discounts_wrapper">
                    <?php $this->load->view('settings/_discounts.php'); ?>
                </div>
            <?php endif; ?>


        </div>
    </div>
</div>
<script>
    $(document).ready(function () {
        set_height("durations_wrapper", "prices_wrapper");
        set_height("prices_wrapper", "discounts_wrapper");
    });
</script>

