<div class="cover">
    <div class="container">
        <div class="col-md-12">
            <div class="row">
                <div class="inset text-center threed">
                    <h2>Short Term Tenant Management</h2>
                </div>
            </div>
        </div>
    </div>
    <div class="container" style="height:80vh">
        <div class="col-md-12">
            <!-- <div class="row">
                <div class="col-md-3">
                    <a href="<?php echo base_url(); ?>index.php/leasing_transaction/lst_Sforcontract">
                        <div class="tilt pic">
                            <img height="210px" width="250px" class="thumbnail thumbnail-menu"
                                src="<?php echo base_url(); ?>img/1.png" alt="For Contract">
                        </div>
                    </a>
                </div>
                <div class="col-md-3">
                    <a href="<?php echo base_url(); ?>index.php/leasing_transaction/lst_SpendingContract">
                        <div class="tilt pic">
                            <img height="210px" width="250px" class="thumbnail thumbnail-menu"
                                src="<?php echo base_url(); ?>img/2.png" alt="For Contract">
                        </div>
                    </a>
                </div>
                <div class="col-md-3">
                    <a href="<?php echo base_url(); ?>index.php/leasing_transaction/lst_Stenants">
                        <div class="tilt pic">
                            <img height="210px" width="250px" class="thumbnail thumbnail-menu"
                                src="<?php echo base_url(); ?>img/3.png" alt="Active Tenants">
                        </div>
                    </a>
                </div>
                <div class="col-md-3">
                    <a href="<?php echo base_url(); ?>index.php/leasing_transaction/sterminated_contract">
                        <div class="tilt pic">
                            <img height="210px" width="250px" class="thumbnail thumbnail-menu"
                                src="<?php echo base_url(); ?>img/4.png" alt="Terminated Contracts">
                        </div>
                    </a>
                </div>
            </div> -->
            <!-- gwaps -->
            <?php if (in_array($this->session->userdata('user_type'), ['IAD'])): ?>
            <div class="row">
                <div class="col-md-4"></div>
                <div class="col-md-4" style="padding-left: 80px;">
                        <a href="<?php echo base_url(); ?>index.php/leasing_transaction/lst_Stenants">
                            <div class="tilt pic">
                                <img height="210px" width="250px" class="thumbnail thumbnail-menu"
                                    src="<?php echo base_url(); ?>img/3.png" alt="Active Tenants">
                            </div>
                        </a>
                </div>
                <div class="col-md-4"></div>
            </div>
            <?php else: ?>
            <div class="row">
                <div class="col-md-3">
                    <a href="<?php echo base_url(); ?>index.php/leasing_transaction/lst_Sforcontract">
                        <div class="tilt pic">
                            <img height="210px" width="250px" class="thumbnail thumbnail-menu"
                                src="<?php echo base_url(); ?>img/1.png" alt="For Contract">
                        </div>
                    </a>
                </div>
                <div class="col-md-3">
                    <a href="<?php echo base_url(); ?>index.php/leasing_transaction/lst_SpendingContract">
                        <div class="tilt pic">
                            <img height="210px" width="250px" class="thumbnail thumbnail-menu"
                                src="<?php echo base_url(); ?>img/2.png" alt="For Contract">
                        </div>
                    </a>
                </div>
                <div class="col-md-3">
                    <a href="<?php echo base_url(); ?>index.php/leasing_transaction/lst_Stenants">
                        <div class="tilt pic">
                            <img height="210px" width="250px" class="thumbnail thumbnail-menu"
                                src="<?php echo base_url(); ?>img/3.png" alt="Active Tenants">
                        </div>
                    </a>
                </div>
                <div class="col-md-3">
                    <a href="<?php echo base_url(); ?>index.php/leasing_transaction/sterminated_contract">
                        <div class="tilt pic">
                            <img height="210px" width="250px" class="thumbnail thumbnail-menu"
                                src="<?php echo base_url(); ?>img/4.png" alt="Terminated Contracts">
                        </div>
                    </a>
                </div>
            </div>
            <?php endif; ?>
            <!-- gwaps end -->
        </div>
    </div>
</div>
</body>