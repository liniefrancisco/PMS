
</body>
<script type="text/javascript" src = "<?php echo base_url(); ?>js/jquery.js"></script>
<script type="text/javascript" src = "<?php echo base_url(); ?>js/bootstrap.js"></script>
<script type="text/javascript" src = "<?php echo base_url(); ?>js/jquery.noty.packaged.min.js"></script>
<script type="text/javascript" src = "<?php echo base_url(); ?>js/notification.js"></script>
<script type="text/javascript" src = "<?php echo base_url(); ?>js/angular.min.js"></script>
<script type="text/javascript" src = "<?php echo base_url(); ?>js/angular-sanitize.js"></script>
<script type="text/javascript" src = "<?php echo base_url(); ?>js/massautocomplete.js"></script>
<script type="text/javascript" src = "<?php echo base_url(); ?>js/Chart.min.js"></script>
<script type="text/javascript" src = "<?php echo base_url(); ?>js/angular-chart.js"></script>
<script type="text/javascript" src = "<?php echo base_url(); ?>js/angular.mask.js"></script>
<script type="text/javascript" src = "<?php echo base_url(); ?>js/fileinput.js"></script>
<script type="text/javascript" src = "<?php echo base_url(); ?>js/dynamic-element.js"></script>
<script type="text/javascript" src = "<?php echo base_url(); ?>js/angular-datepicker.js"></script>
<script type="text/javascript" src = "<?php echo base_url(); ?>js/angular-clock.js"></script>
<script type="text/javascript" src = "<?php echo base_url(); ?>js/ajax.js"></script>
<script type="text/javascript" src = "<?php echo base_url(); ?>js/moment.js"></script>
<script type="text/javascript" src = "<?php echo base_url(); ?>js/mask.min.js"></script>
<script type="text/javascript" src = "<?php echo base_url(); ?>js/ng-table.min.js"></script>
<script type="text/javascript" src = "<?php echo base_url(); ?>js/app.js"></script>
<script type="text/javascript" src = "<?php echo base_url(); ?>js/directives.js"></script>
<script type="text/javascript" src = "<?php echo base_url(); ?>js/editable-grid.js"></script>
<script type="text/javascript" src = "<?php echo base_url(); ?>js/angular-input-masks-standalone.min.js"></script>
<script type="text/javascript" src = "<?php echo base_url(); ?>js/treeview.js"></script>

<script type="text/javascript">
    <?php switch($flashdata):
        case "Invalid Login": ?>
            generate('error', '<div class="activity-item"> <i class="fa fa-ban text-success"></i> Invalid Login</div>');
        <?php break;?>

        <?php case "Added": ?>
            generate('success', '<div class="activity-item"> <i class="fa fa-check text-success"></i> Successfully Saved.</div>');
        <?php break;?>

        <?php case "Saved": ?>
            generate('success', "Successfully Saved.");
        <?php break;?>
        
    <?php endswitch;?>
</script>



</html>
