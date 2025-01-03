<!--main content start-->
<section id="main-content">
    <section class="wrapper site-min-height">

        <!-- INLINE FORM ELELEMNTS -->
        <div class="row mt">
            <div class="col-md-10 col-md-offset-1">
                <div class="panel panel-theme">
                    <div class="panel-heading"><i class="fa fa-pencil"></i> Adjust Denomination</div>
                    <div class="panel-body">
                        <div class="row">
                            <div class="col-md-12">
                                <section id="unseen">
                                    <div class="form-panel">
                                        <form class="form-horizontal tasi-form" action="#" method="post"
                                            id="frm_adjustDenomination">
                                            <div class="form-group">
                                                <label class="col-sm-3 control-label col-lg-3 col-md-offset-1"
                                                    for="search_cfsName"><b>Name</b></label>
                                                <div class="col-lg-4">
                                                    <select name="search_cfsName" id="search_cfsName" required
                                                        class="form-control">
                                                        <option value="">Select Name</option>
                                                    </select>
                                                </div>
                                            </div>
                                            <div class="form-group">
                                                <label class="col-sm-3 control-label col-lg-3 col-md-offset-1"
                                                    for="date"><b>Date</b></label>
                                                <div class="col-lg-4">
                                                    <div class="input-group">
                                                        <div class="input-group-addon input-date"><strong><i
                                                                    class="fa fa-calendar"></i></strong></div>
                                                        <input type="text" required placeholder="Choose a date"
                                                            class="form-control" id="date" name="date"
                                                            autocomplete="off">
                                                    </div>
                                                </div>
                                            </div>
                                            <br>
                                            <div class="form-group">
                                                <div class="col-md-4 col-md-offset-4">
                                                    <button type="button" id="generateBtn"
                                                        class="btn btn-theme03 btn-md btn-block">Generate</button>
                                                </div>
                                            </div>
                                        </form>
                                        <div id="table"></div>
                                    </div>
                                </section>
                            </div>
                        </div>
                    </div><!-- /panel-body -->
                </div> <!-- /panel-theme -->
            </div><!-- /col-lg-12 -->
        </div><!-- /row -->

    </section>

</section><!-- /MAIN CONTENT -->