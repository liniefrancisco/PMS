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
	<script type="text/javascript" src = "<?php echo base_url(); ?>js/ng-table.min.js"></script>
	<script type="text/javascript" src = "<?php echo base_url(); ?>js/mask.min.js"></script>
	<script type="text/javascript" src = "<?php echo base_url(); ?>js/app.js"></script>
	<script type="text/javascript" src = "<?php echo base_url(); ?>js/directives.js"></script>
	<script type="text/javascript" src = "<?php echo base_url(); ?>js/editable-grid.js"></script>
	<script type="text/javascript" src = "<?php echo base_url(); ?>js/angular-input-masks-standalone.min.js"></script>


	<script type="text/javascript">
	    <?php switch($flashdata):
	        case "Invalid Login": ?>
	            generate('error', '<div class="activity-item"> <i class="fa fa-ban text-success"></i> Invalid Login</div>');
	        <?php break;?>

	        <?php case "Added": ?>
	            generate('success', '<div class="activity-item"> <i class="fa fa-check text-success"></i> Successfully Saved.</div>');
	        <?php break;?>

	        <?php case "Not saved": ?>
	            generate('success', '<div class="activity-item"> <i class="fa fa-ban text-success"></i> Transaction not saved. Error Occured.</div>');
	        <?php break;?>

	        <?php case "Deleted": ?>
	            generate('success', '<div class="activity-item"> <i class="fa fa-check text-success"></i> Successfully Deleted.</div>');
	        <?php break;?>

	        <?php case "Error": ?>
	            generate('error', '<div class="activity-item"> <i class="fa fa-ban text-success"></i> Error Occured. Action not saved.</div>');
	        <?php break;?>

	        <?php case "Required": ?>
	            generate('error', '<div class="activity-item"> <i class="fa fa-ban text-success"></i> Pleas Fill out all Required Fields</div>');
	        <?php break;?>

	        <?php case "Updated": ?>
	            generate('success', '<div class="activity-item"> <i class="fa fa-check text-success"></i> Changes Successfully Updated.</div>');
	        <?php break;?>

	        <?php case "Terminated": ?>
	            generate('success', '<div class="activity-item"> <i class="fa fa-check text-success"></i> Contract Successfully Terminated.</div>');
	        <?php break;?>

	        <?php case "Blocked": ?>
	            generate('success', '<div class="activity-item"> <i class="fa fa-check text-success"></i> User Successfully Blocked.</div>');
	        <?php break;?>

	        <?php case "Activated": ?>
	            generate('success', '<div class="activity-item"> <i class="fa fa-check text-success"></i> User Successfully Activated.</div>');
	        <?php break;?>

	        <?php case "Reset": ?>
	            generate('success', '<div class="activity-item"> <i class="fa fa-check text-success"></i> Password Successfully Reset.</div>');
	        <?php break;?>

	        <?php case "Invalid Key": ?>
	            generate('error', '<div class="activity-item"> <i class="fa fa-times text-alert"></i> Invalid Manager&#39;s Key</div>');
	        <?php break;?>

	        <?php case "IncorrectMangersKey": ?>
	            generate('error', "Incorrect Manager's Key.");
	        <?php break;?>

	        <?php case "Approved": ?>
	            generate('success', '<div class="activity-item"> <i class="fa fa-thumbs-up text-success"></i> Successfully Approved.</div>');
	        <?php break;?>
	        <?php case "Denied": ?>
	            generate('success', '<div class="activity-item"> <i class="fa fa-thumbs-down text-success"></i> Successfully Denied.</div>');
	        <?php break;?>
	        <?php case "Restored": ?>
	            generate('success', "Successfully Restored.");
	        <?php break;?>
	        <?php case "Restriction": ?>
	            generate('error', "Not allowed. User Restriction");
	        <?php break;?>
	        <?php case "Posted": ?>
	            generate('success', "Successfully posted.");
	        <?php break;?>
	        <?php case "Saved": ?>
	            generate('success', "Successfully Saved.");
	        <?php break;?>
	    <?php endswitch;?>
	</script>

</html>
