<!--main content start-->
<section id="main-content">
    <section class="wrapper site-min-height">

        <h3 style="color:#0d1a35"> Dashboard</h3>
        <div class="row mt">
            <div class="col-lg-12">
                <! -- 1st ROW OF PANELS -->
                    <div class="row">
                        <div class="col-md-4 col-sm-4 mb">
                            <div class="green-panel pn">
                                <div class="panel-heading">
                                    <div class="row">
                                        <div class="col-xs-4 pn">
                                            <i class="fa fa-users fa-5x pull-right"></i>
                                        </div>
                                        <div class="col-xs-8 text-center pn1">
                                            <h4>ACTIVE TENANTS</h4>
                                            <div class="centered" style="margin-top:-10px;font-weight:bold">
                                                <?php echo number_format($active); ?>
                                            </div>
                                        </div>
                                    </div>
                                </div>
                            </div>
                        </div><!-- /col-md-4 -->


                        <div class="col-md-4 col-sm-4 mb">
                            <div class="darkblue-panel pn">
                                <div class="panel-heading">
                                    <div class="row">
                                        <div class="col-xs-4 pn">
                                            <i class="fa fa-signal fa-5x pull-right"></i>
                                        </div>
                                        <div class="col-xs-8 text-center pn1">
                                            <h4 style="color:#1f2937;font-family:tahoma">LONG TERM TENANTS</h4>
                                            <div class="centered" style="margin-top:-10px;font-weight:bold">
                                                <?php echo number_format($long_term); ?>
                                            </div>
                                        </div>
                                    </div>
                                </div>
                            </div>
                            <! -- /darkblue panel -->
                        </div><!-- /col-md-4 -->

                        <div class="col-md-4 col-sm-4 mb">
                            <div class="grey-panel pn">
                                <div class="panel-heading">
                                    <div class="row">
                                        <div class="col-xs-4 pn">
                                            <i class="fa fa-calendar fa-5x pull-right"></i>
                                        </div>
                                        <div class="col-xs-8 text-center pn1">
                                            <h4 style="color:#1f2937;font-family:tahoma">SHORT TERM TENANTS</h4>
                                            <div class="centered" style="margin-top:-10px;font-weight:bold">
                                                <?php echo number_format($short_term); ?>
                                            </div>
                                        </div>
                                    </div>
                                </div>
                            </div>
                            <! -- /grey panel -->
                        </div><!-- /col-md-4 -->
                    </div><!-- /END 1st ROW OF PANELS -->
            </div>
        </div>
        <div class="row mt">
            <div class="col-md-12">
                <div class="row">
                    <div class="col-md-4">
                        <div class="col-md-12 ds">
                            <!--COMPLETED ACTIONS DONUTS CHART-->
                            <h3>TENANTS PER AREA TYPE</h3>
                            <div class="desc">
                                <div class="row">
                                    <div class="col-md-12">
                                        <?php foreach ($area_type as $value): ?>
                                            <div class="row">
                                                <div class="col-md-9 col-md-offset-1">
                                                    <h4><i class="glyphicon glyphicon-asterisk"></i> <b>
                                                            <?php echo $value['area_type']; ?>
                                                        </b></h4>
                                                </div>
                                                <div class="col-md-2">
                                                    <span class="badge bg-important">
                                                        <?php echo $value['count']; ?>
                                                    </span>
                                                </div>
                                            </div>
                                        <?php endforeach ?>
                                    </div>
                                </div>
                            </div>
                        </div><!-- /col-md-12 -->
                        <div class="col-md-12 ds">
                            <h3>TENANTS PER AREA CLASSIFICATION</h3>
                            <div class="desc">
                                <div class="row">
                                    <div class="col-md-12">
                                        <?php foreach ($area_classification as $value): ?>
                                            <div class="row">
                                                <div class="col-md-9 col-md-offset-1">
                                                    <h4><i class="glyphicon glyphicon-asterisk"></i> <b>
                                                            <?php echo $value['area_classification']; ?>
                                                        </b></h4>
                                                </div>
                                                <div class="col-md-2">
                                                    <span class="badge bg-important">
                                                        <?php echo $value['count']; ?>
                                                    </span>
                                                </div>
                                            </div>
                                        <?php endforeach ?>
                                    </div>
                                </div>
                            </div>
                        </div><!-- /col-md-12 -->
                    </div>
                    <div class="col-md-4">
                        <div class="col-md-12 ds">
                            <!--COMPLETED ACTIONS DONUTS CHART-->
                            <h3>TENANTS PER CATEGORY</h3>
                            <div class="desc">
                                <div class="row">
                                    <div class="col-md-12">
                                        <?php foreach ($category as $value): ?>
                                            <div class="row">
                                                <div class="col-md-9 col-md-offset-1">
                                                    <h4><i class="glyphicon glyphicon-asterisk"></i> <b>
                                                            <?php echo $value['category']; ?>
                                                        </b></h4>
                                                </div>
                                                <div class="col-md-2">
                                                    <span class="badge bg-important">
                                                        <?php echo $value['count']; ?>
                                                    </span>
                                                </div>
                                            </div>
                                        <?php endforeach ?>
                                    </div>
                                </div>
                            </div>
                        </div><!-- /col-md-12 -->


                    </div>
                    <div class="col-md-4">
                        <div class="col-md-12 ds">
                            <h3>TENANTS PER LESSEE TYPE</h3>
                            <div class="desc">
                                <div class="row">
                                    <div class="col-md-12">
                                        <?php foreach ($lessee_type as $value): ?>
                                            <div class="row">
                                                <div class="col-md-9 col-md-offset-1">
                                                    <h4><i class="glyphicon glyphicon-asterisk"></i> <b>
                                                            <?php echo $value['type']; ?>
                                                        </b></h4>
                                                </div>
                                                <div class="col-md-2">
                                                    <span class="badge bg-important">
                                                        <?php echo $value['count']; ?>
                                                    </span>
                                                </div>
                                            </div>
                                        <?php endforeach ?>
                                    </div>
                                </div>
                            </div>
                        </div><!-- /col-md-12 -->

                    </div>
                </div>
            </div>
        </div>

        <div class="row mt">
            <div class="col-md-12">
                <div class="row">
                    <div class="col-md-4">

                    </div>
                </div>
            </div>
        </div>

    </section>
    <! --/wrapper -->
</section><!-- /MAIN CONTENT -->