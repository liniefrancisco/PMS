
/////////////////////////////////////// AJAX SUBMIT ////////////////////////////////////



$("#frm_deleteEntry").submit(function(e) {
    var url = '../admin/deleteEntry';
    $.ajax({
        type: "POST",
        url: url,
        data: $("#frm_deleteEntry").serialize(),
        beforeSend: function() {openSpinner();},
        success: function(data)
        {
            $('#spinner_modal').modal('toggle');
            data = JSON.parse(data);
            if (data['msg'] == 'Success')
            {
            	clearForm('frm_deleteEntry');
                alertMe('Success', 'Operation Completed');
            }
            else
            {
            	alertMe('error', 'Operation not Completed');
            }
        }
    });
    e.preventDefault();
});



$("#frm_cancelSoa").submit(function(e) {
    var url = '../admin/cancel_soa';
    $.ajax({
        type: "POST",
        url: url,
        data: $("#frm_cancelSoa").serialize(),
        beforeSend: function() {openSpinner();},
        success: function(data)
        {
            $('#spinner_modal').modal('toggle');
            data = JSON.parse(data);
            if (data['msg'] == 'Success')
            {
            	clearForm('frm_cancelSoa');
                alertMe('Success', 'Operation Completed');
            }
            else
            {
            	alertMe('error', 'Operation not Completed');
            }
        }
    });
    e.preventDefault();
});


$("#frm_cancelPayment").submit(function(e) {
    var url = '../admin/cancel_payment';
    $.ajax({
        type: "POST",
        url: url,
        data: $("#frm_cancelPayment").serialize(),
        beforeSend: function() {openSpinner();},
        success: function(data)
        {
            $('#spinner_modal').modal('toggle');
            data = JSON.parse(data);
            if (data['msg'] == 'Success')
            {
                clearForm('frm_cancelPayment');
                alertMe('Success', 'Operation Completed');
            }
            else
            {
                alertMe('error', 'Operation not Completed');
            }
        }
    });
    e.preventDefault();
});



$("#frm_changeDueDate").submit(function(e) {
    var url = '../admin/change_dueDate';
    $.ajax({
        type: "POST",
        url: url,
        data: $("#frm_changeDueDate").serialize(),
        beforeSend: function() {openSpinner();},
        success: function(data)
        {
            $('#spinner_modal').modal('toggle');
            data = JSON.parse(data);
            if (data['msg'] == 'Success')
            {
            	clearForm('frm_changeDueDate');
                alertMe('Success', 'Operation Completed');
            }
            else
            {
            	alertMe('error', 'Operation not Completed');
            }
        }
    });
    e.preventDefault();
});


$("#frm_changePostingDate").submit(function(e) {
    var url = '../admin/change_postingDate';

    $.ajax({
        type: "POST",
        url: url,
        data: $("#frm_changePostingDate").serialize(),
        beforeSend: function() {openSpinner();},
        success: function(data)
        {
            $('#spinner_modal').modal('toggle');
            data = JSON.parse(data);
            if (data['msg'] == 'Success')
            {
                clearForm('frm_changePostingDate');
                alertMe('Success', 'Operation Completed');
            }
            else
            {
                alertMe('error', 'Operation not Completed');
            }
        }
    });
    e.preventDefault();
});



$("#frm_changeSOADate").submit(function(e) {
    var url = '../admin/change_SOACollectionDate';
    $.ajax({
        type: "POST",
        url: url,
        data: $("#frm_changeSOADate").serialize(),
        beforeSend: function() {openSpinner();},
        success: function(data)
        {
            $('#spinner_modal').modal('toggle');
            data = JSON.parse(data);
            if (data['msg'] == 'Success')
            {
            	clearForm('frm_changeSOADate');
                alertMe('Success', 'Operation Completed');
            }
            else
            {
            	alertMe('error', 'Operation not Completed');
            }
        }
    });
    e.preventDefault();
});


$("#frm_changeReceiptNo").submit(function(e) {
    var url = '../admin/change_receiptNo';
    $.ajax({
        type: "POST",
        url: url,
        data: $("#frm_changeReceiptNo").serialize(),
        beforeSend: function() {openSpinner();},
        success: function(data)
        {
            $('#spinner_modal').modal('toggle');
            data = JSON.parse(data);
            if (data['msg'] == 'Success')
            {
            	clearForm('frm_changeReceiptNo');
                alertMe('Success', 'Operation Completed');
            }
            else
            {
            	alertMe('error', 'Operation not Completed');
            }
        }
    });
    e.preventDefault();
});




$(document).ready(function(){

	$("input#search_tradeName").keyup(function() {
		var url = '../admin/search_tradeName';
        var val = $("input#search_tradeName").val();
        var loc = window.location.href;
        var lastIndex = loc.match(/([^\/]*)\/*$/)[1];
     	$.ajax({
	        type: "POST",
	        url: url,
	        success: function(data)
	        {
	        	var data = JSON.parse(data);
	            $("input#search_tradeName").autocomplete({
		            source: data,
		            select: function( event, ui ) {
		            	$('#details').html();

                        if (lastIndex == 'add_charges_page')
                        {
                            populate_addChargesDetails(ui.item.id);
                        }
                        else if (lastIndex == 'update_charges_page')
                        {
                            populate_updateChargesDetails(ui.item.id);
                        }
                        else if (lastIndex == 'change_basicRental_page')
                        {
                            populate_updateBasicRental(ui.item.id);
                        }
                        else if (lastIndex == 'add_preop_page') {
                            populate_addPreop(ui.item.id);

                        }
		            }
		        });
	        }
	    });
    });

    $("#searchclear").click(function(){
    	$('input#search_tradeName').autocomplete('close').val('');
    });



    // Search Receipt No for VDS tagging


    $("input#search_receiptNo").keyup(function() {
        var url = '../admin/search_receiptNo';
        var val = $("input#search_receiptNo").val();
        var loc = window.location.href;
        var lastIndex = loc.match(/([^\/]*)\/*$/)[1];
        $.ajax({
            type: "POST",
            url: url,
            success: function(data)
            {
                var data = JSON.parse(data);
                $("input#search_receiptNo").autocomplete({
                    source: data,
                    select: function( event, ui ) {
                        $('#details').html();


                        $('#details').html(
                            '<div class="form-group">' +
                                '<label class="col-sm-3 control-label col-lg-3 col-md-offset-1" for="posting_date"><b>Posting Date</b></label>' +
                                '<div class="col-lg-4">' +
                                    '<input type="hidden"  name = "receipt_no" value = "' + ui.item.value + '" >' +
                                    '<input type="text" autocomplete="off" name = "contract_no" value = "' + ui.item.posting_date + '" readonly class="form-control" id="contract_no">' +
                                '</div>' +
                            '</div>' +
                            '<div class="form-group">' +
                                '<label class="col-sm-3 control-label col-lg-3 col-md-offset-1" for="trade_name"><b>Trade Name</b></label>' +
                                '<div class="col-lg-4">' +
                                    '<input type="text" autocomplete="off" name = "trade_name" value = "' + ui.item.trade_name + '" readonly class="form-control" id="trade_name">' +
                                '</div>' +
                            '</div>' +
                            '<div class="form-group">' +
                                '<label class="col-sm-3 control-label col-lg-3 col-md-offset-1" for="tenant_id"><b>Tenant ID</b></label>' +
                                '<div class="col-lg-4">' +
                                    '<input type="text" autocomplete="off" name = "tenant_id" value = "' + ui.item.tenant_id + '" readonly class="form-control" id="tenant_id">' +
                                '</div>' +
                            '</div>' +
                            '<div class="form-group">' +
                                '<label class="col-sm-3 control-label col-lg-3 col-md-offset-1" for="vds_no"><b>VDS No.</b></label>' +
                                '<div class="col-lg-4">' +
                                    '<input type="text" autocomplete="off" name = "vds_no" value = "' + ui.item.vds_no + '" required class="form-control" id="vds_no">' +
                                '</div>' +
                            '</div>' +
                            '<br>' +
                            '<div class="form-group">' +
                                '<div class="col-md-4 col-md-offset-4">' +
                                    '<button type = "submit" class="btn btn-theme03 btn-lg btn-block">Submit</button>' +
                                '</div>' +
                            '</div>');
                    }
                });
            }
        });
    });

    $("#clear_receiptNo").click(function(){
        $('#details').html();
        $('input#search_receiptNo').autocomplete('close').val('');
    }); 


});


function populate_addChargesDetails(id)
{
	var url = '../admin/populate_addChargesDetails';
	$.ajax({
        type: "POST",
        url: url,
        data: ({id: id}),
        success: function(data)
        {
        	data = JSON.parse(data);
        	$('#details').html(
        		'<div class="form-group">' +
                    '<label class="col-sm-3 control-label col-lg-3 col-md-offset-1" for="tenant_id"><b>Tenant ID</b></label>' +
                    '<div class="col-lg-4">' +
                        '<input type="text" autocomplete="off" name = "tenant_id" value = "' + data['tenant_id'] + '" readonly class="form-control" id="tenant_id">' +
                    '</div>' +
                '</div>' +
                '<div class="form-group">' +
                    '<label class="col-sm-3 control-label col-lg-3 col-md-offset-1" for="trade_name"><b>Trade Name</b></label>' +
                    '<div class="col-lg-4">' +
                        '<input type="text" autocomplete="off" name = "trade_name" value = "' + data['trade_name'] + '" readonly class="form-control" id="trade_name">' +
                    '</div>' +
                '</div>' +
                '<div class="form-group">' +
                    '<label class="col-sm-3 control-label col-lg-3 col-md-offset-1" for="description"><b>Description</b></label>' +
                    '<div class="col-lg-4">' +
                        '<select class = "form-control" name = "description">' + data['charges'] + '</select>' +
                    '</div>' +
                '</div>' +
                '<div class="form-group">' +
                    '<label class="col-sm-3 control-label col-lg-3 col-md-offset-1" for="description"><b>UOM</b></label>' +
                    '<div class="col-lg-4">' +
                        '<select class = "form-control" name = "uom">' +
                            '<option value="" disabled="" selected="" style="display:none">Please Select One</option>' +
                        	'<option>Per Kilowatt Hour</option>' +
                            '<option>Per Kilogram</option>' +
                            '<option>Per Cubic Meter</option>' +
                            '<option>Per Square Meter</option>' +
                            '<option>Per Grease Trap</option>' +
                            '<option>Per Feet</option>' +
                            '<option>Per Ton</option>' +
                            '<option>Per Hour</option>' +
                            '<option>Per Piece</option>' +
                            '<option>Per Contract</option>' +
                            '<option>Per Linear</option>' +
                            '<option>Per Page</option>' +
                            '<option>Fixed Amount</option>' +
                            '<option>Inputted</option>' +
                        '</select>' +
                    '</div>' +
                '</div>' +
                '<div class="form-group">' +
                    '<label class="col-sm-3 control-label col-lg-3 col-md-offset-1" for="unit_price"><b>Unit Price</b></label>' +
                    '<div class="col-lg-4">' +
                        '<input type="text" autocomplete="off" name = "unit_price"  class="form-control currency" id="unit_price">' +
                    '</div>' +
                '</div>' +
                '<br>' +
                    '<div class="form-group">' +
                        '<div class="col-md-4 col-md-offset-4">' +
                            '<button type = "submit" class="btn btn-theme03 btn-lg btn-block">Submit</button>' +
                        '</div>' +
                    '</div>');



			$('#unit_price').inputmask({ 'alias': 'decimal', 'groupSeparator': ',', 'autoGroup': true, 'digits': 2, 'digitsOptional': false, 'placeholder': '0.00'});
        }
    });

}


$("#frm_addCharges").submit(function(e) {
    var url = '../admin/add_charges';
    $.ajax({
        type: "POST",
        url: url,
        data: $("#frm_addCharges").serialize(),
        beforeSend: function() {openSpinner();},
        success: function(data)
        {
            $('#spinner_modal').modal('toggle');
            data = JSON.parse(data);
            if (data['msg'] == 'Success')
            {
                clearForm('frm_addCharges');
                alertMe('Success', 'Operation Completed');
                $('#details').html("");
            }
            else
            {
                alertMe('error', 'Operation not Completed');
            }
        }
    });
    e.preventDefault();
});


$('#frm_vdsTagging').submit(function(e){
    var url = '../admin/save_vdsTagging';
    $.ajax({
        type: "POST",
        url: url,
        data: $("#frm_vdsTagging").serialize(),
        beforeSend: function() {openSpinner();},
        success: function(data)
        {
            $('#spinner_modal').modal('toggle');
            data = JSON.parse(data);
            if (data['msg'] == 'Success')
            {
                clearForm('frm_vdsTagging');
                alertMe('Success', 'Operation Completed');
                $('#details').html("");
            }
            else
            {
                alertMe('error', 'Operation not Completed');
            }
        }
    });
    e.preventDefault();
});


function populate_updateChargesDetails(id)
{
    var url = '../admin/populate_updateChargesDetails';
    $.ajax({
        type: "POST",
        url: url,
        data: ({id: id}),
        success: function(data)
        {
            console.log(data);
            data = JSON.parse(data);

            $('#details').html(
                '<div class="form-group">' +
                    '<label class="col-sm-3 control-label col-lg-3 col-md-offset-1" for="tenant_id"><b>Tenant ID</b></label>' +
                    '<div class="col-lg-4">' +
                        '<input type="text" autocomplete="off" name = "tenant_id" value = "' + data['tenant_id'] + '" readonly class="form-control" id="tenant_id">' +
                    '</div>' +
                '</div>' +
                '<div class="form-group">' +
                    '<label class="col-sm-3 control-label col-lg-3 col-md-offset-1" for="trade_name"><b>Trade Name</b></label>' +
                    '<div class="col-lg-4">' +
                        '<input type="text" autocomplete="off" name = "trade_name" value = "' + data['trade_name'] + '" readonly class="form-control" id="trade_name">' +
                    '</div>' +
                '</div> <br>' +
                '<div class = "row">' +
                    '<div class = "col-md-12">' +
                        '<section id="unseen">' +
                            '<table class="table table-striped table-advance table-hover" id = "datatable">' +
                                '<thead>' +
                                    '<tr>' +
                                        '<th>Description</th>' +
                                        '<th>Charges Code</th>' +
                                        '<th>Unit of Measure</th>' +
                                        '<th class="numeric">Unit Price</th>' +
                                        '<th>Action</th>' +
                                    '</tr>' +
                                '</thead>' +
                                '<tbody>' + data['charges'] + '</tbody>' +
                            '</table>' +
                        '</section>' +
                    '</div>' +
                '</div>');

            $('#datatable').DataTable();
            $('[data-toggle="tooltip"]').tooltip();
        }
    });
}


function populate_updateBasicRental(id)
{

    var url = '../admin/populate_updateBasicRental';
    $.ajax({
        type: "POST",
        url: url,
        data: ({id: id}),
        success: function(data)
        {
            data = JSON.parse(data);

            $('#details').html(
                '<div class="form-group">' +
                    '<label class="col-sm-3 control-label col-lg-3 col-md-offset-1" for="tenant_id"><b>Tenant ID</b></label>' +
                    '<div class="col-lg-4">' +
                        '<input type="text" autocomplete="off" name = "tenant_id" value = "' + data['tenant_id'] + '" readonly class="form-control" id="tenant_id">' +
                    '</div>' +
                '</div>' +
                '<div class="form-group">' +
                    '<label class="col-sm-3 control-label col-lg-3 col-md-offset-1" for="trade_name"><b>Trade Name</b></label>' +
                    '<div class="col-lg-4">' +
                        '<input type="text" autocomplete="off" name = "trade_name" value = "' + data['trade_name'] + '" readonly class="form-control" id="trade_name">' +
                    '</div>' +
                '</div>' +
                '<div class="form-group">' +
                    '<label class="col-sm-3 control-label col-lg-3 col-md-offset-1" for="contract_no"><b>Contract No.</b></label>' +
                    '<div class="col-lg-4">' +
                        '<input type="text" autocomplete="off" name = "contract_no" value = "' + data['contract_no'] + '" readonly class="form-control" id="contract_no">' +
                    '</div>' +
                '</div>' +
                '<div class="form-group">' +
                    '<label class="col-sm-3 control-label col-lg-3 col-md-offset-1" for="basic_rental"><b>Basic Rental</b></label>' +
                    '<div class="col-lg-4">' +
                        '<input type="text" autocomplete="off" name = "basic_rental" value = "' + data['basic_rental'] + '"   class="form-control currency" id="basic_rental">' +
                    '</div>' +
                '</div>' +
                '<br>' +
                    '<div class="form-group">' +
                        '<div class="col-md-4 col-md-offset-4">' +
                            '<button type = "submit" class="btn btn-theme03 btn-lg btn-block">Submit</button>' +
                        '</div>' +
                    '</div>');



            $('#basic_rental').inputmask({ 'alias': 'decimal', 'groupSeparator': ',', 'autoGroup': true, 'digits': 2, 'digitsOptional': false, 'placeholder': '0.00'});
        }
    });
}


function populate_addPreop(tenant_id){
    $('#details').html(
            '<div class="form-group">' +
                '<label class="col-sm-3 control-label col-lg-3 col-md-offset-1" for="tenant_id"><b>Tenant ID</b></label>' +
                '<div class="col-lg-4">' +
                    '<input type="text" autocomplete="off" readonly name = "tenant_id" value="' + tenant_id + '" required class="form-control" id="tenant_id">' +
                '</div>' +
            '</div>' +
            '<div class="form-group">' +
                '<label class="col-sm-3 control-label col-lg-3 col-md-offset-1" for="tenant_id"><b>Description</b></label>' +
                '<div class="col-lg-4">' +
                    '<select class="form-control" name = "description" required>' +
                        '<option value="" disabled="" selected="" style = "display:none">Please Select One</option>' +
                        '<option>Security Deposit</option>' +
                        '<option>Advance Rent</option>' +
                        '<option>Construction Bond</option>' +
                    '</select>' +
                '</div>' +
            '</div>' +
            '<div class="form-group">' +
                '<label class="col-sm-3 control-label col-lg-3 col-md-offset-1" for="bank_name"><b>Bank Name</b></label>' +
                '<div class="col-lg-4">' +
                    '<input type="text" required autocomplete="off" name = "bank_name" class="form-control" id="bank_name">'+
                '</div>'+
           ' </div>'+
            '<div class="form-group">' +
                '<label class="col-sm-3 control-label col-lg-3 col-md-offset-1" for="bank_code"><b>Bank Code</b></label>' +
                '<div class="col-lg-4">' +
                    '<input type="text" required autocomplete="off" name = "bank_code" class="form-control" id="bank_code">' +
                '</div>' +
            '</div>' +
            '<div class="form-group">' +
                '<label class="col-sm-3 control-label col-lg-3 col-md-offset-1" for="soa_no"><b>Document No.</b></label>' +
                '<div class="col-lg-4">' +
                    '<input type="text" required autocomplete="off" name = "doc_no" class="form-control" id="doc_no">' +
                '</div>' +
            '</div>' +
            '<div class="form-group">' +
                '<label class="col-sm-3 control-label col-lg-3 col-md-offset-1" for="soa_no"><b>Posting Date</b></label>' +
                '<div class="col-lg-4">' +
                    '<input type="text" required autocomplete="off" name = "posting_date" class="form-control" id="posting_date">' +
                '</div>' +
            '</div>' +
            '<div class="form-group">' +
                '<label class="col-sm-3 control-label col-lg-3 col-md-offset-1" for="soa_no"><b>Amount</b></label>' +
                '<div class="col-lg-4">' +
                    '<input type="text" required autocomplete="off" name = "preop_amount" class="form-control currency" id="preop_amount">' +
                '</div>' +
            '</div>' +
            '<br>' +
            '<div class="form-group">' +
                '<div class="col-md-4 col-md-offset-4">' +
                    '<button type = "submit" class="btn btn-theme03 btn-lg btn-block">Submit</button>' +
                '</div>' +
            '</div>');


        $( "#posting_date" ).datepicker({ dateFormat: 'yy-mm-dd' }).val();
        $('#preop_amount').inputmask({ 'alias': 'decimal', 'groupSeparator': ',', 'autoGroup': true, 'digits': 2, 'digitsOptional': false, 'placeholder': '0.00'});
}



function selectedCharge(id)
{
    var url = '../admin/get_charges';
    $.ajax({
        type: "POST",
        url: url,
        data: ({id: id}),
        success: function(data)
        {
            openModal('modal_updateCharges');
            data = JSON.parse(data);
            $('#frm_updateCharge').html(data['result']);
            $('#unit_price').inputmask({ 'alias': 'decimal', 'groupSeparator': ',', 'autoGroup': true, 'digits': 2, 'digitsOptional': false, 'placeholder': '0.00'});
        }
    });
}


function deleteCharge(id)
{
    var url = '../admin/delete_charges/' + id;
    $("a#anchor_delete").attr("href", url);
    $('#confirm_msg').html('You wish to delete this data?');
    openModal('modal_confirmation');
}



$("#frm_updateCharge").submit(function(e) {
    var url = '../admin/update_charges';
    $.ajax({
        type: "POST",
        url: url,
        data: $("#frm_updateCharge").serialize(),
        beforeSend: function() {openSpinner();},
        success: function(data)
        {
            $('#spinner_modal').modal('toggle');
            data = JSON.parse(data);
            if (data['msg'] == 'Success')
            {
                $('#frm_updateCharge').html("");
                $('#details').html("");
                $('#modal_updateCharges').modal('toggle');
                $('input#search_tradeName').autocomplete('close').val('');
                alertMe('Success', 'Operation Completed');
            }
            else
            {
                alertMe('error', 'Operation not Completed');
            }
        }
    });
    e.preventDefault();
});

$("#frm_updateBasicRental").submit(function(e) {
    var url = '../admin/update_basicRental';

    $.ajax({
        type: "POST",
        url: url,
        data: $("#frm_updateBasicRental").serialize(),
        beforeSend: function() {openSpinner();},
        success: function(data)
        {
            $('#spinner_modal').modal('toggle');
            data = JSON.parse(data);
            if (data['msg'] == 'Success')
            {

                clearForm('frm_updateBasicRental');
                alertMe('Success', 'Operation Completed');
                $('#details').html("");
            }
            else
            {
                alertMe('error', 'Operation not Completed');
            }
        }
    });
    e.preventDefault();
});


$('#frm_preop').submit(function(e){
    var url = '../admin/add_preopcharges';
    $.ajax({
        type: "POST",
        url: url,
        data: $("#frm_preop").serialize(),
        beforeSend: function() {openSpinner();},
        success: function(data)
        {
            $('#spinner_modal').modal('toggle');
            data = JSON.parse(data);
            if (data['msg'] == 'Success')
            {
                clearForm('frm_preop');
                alertMe('Success', 'Operation Completed');
            }
            else if (data['msg'] == 'No Amount')
            {
                alertMe('error', 'No Specified Amount');
            }
            else
            {
                alertMe('error', 'Operation not Completed');
            }
        }
    });
    e.preventDefault();
});



$("#frm_changeBankTagging").submit(function(e) {
    var url = '../admin/update_bankTagging';

    $.ajax({
        type: "POST",
        url: url,
        data: $("#frm_changeBankTagging").serialize(),
        beforeSend: function() {openSpinner();},
        success: function(data)
        {
            $('#spinner_modal').modal('toggle');
            data = JSON.parse(data);
            if (data['msg'] == 'Success')
            {

                clearForm('frm_changeBankTagging');
                alertMe('Success', 'Operation Completed');
            }
            else
            {
                alertMe('error', 'Operation not Completed');
            }
        }
    });
    e.preventDefault();
});




function update_terms(id){ $('#frm_updateTenantDetails').load('get_tenantTerms/update/' + id);openModal('modal_updateTerms');}

function view_terms(id){$('#view_TenantDetails').load('get_tenantTerms/view/' + id);openModal('modal_viewTerms');}

function  view_prospect(id){$('#view_TenantDetails').load('get_prospectDetails/' + id); openModal('modal_viewProspect');}

function approve_prospect(id)
{
    var url = '../admin/approve_prospect/' + id;
    $("a#anchor_delete").attr("href", url);
    $('#confirm_msg').html('You wish to continue this action?');
    openModal('modal_confirmation');
}

function deny_prospect(id)
{
    var url = '../admin/deny_prospect/' + id;
    $("a#anchor_delete").attr("href", url);
    $('#confirm_msg').html('You wish to continue this action?');
    openModal('modal_confirmation');
}


function restore_contract(id)
{
    var url = '../admin/restore_contract/' + id;
    $("a#anchor_delete").attr("href", url);
    $('#confirm_msg').html('You wish to continue this action?');
    openModal('modal_confirmation');
}


/////////////////////////////////////// END OF AJAX SUBMIT ////////////////////////////////////



$(document).ready(function(){

    $( "#opening_date" ).datepicker({ dateFormat: 'yy-mm-dd' }).val();
    $( "#expiry_date" ).datepicker({ dateFormat: 'yy-mm-dd' }).val();
    $( "#posting_date" ).datepicker({ dateFormat: 'yy-mm-dd' }).val();
    $('#rent_percentage').inputmask({ 'alias': 'decimal', 'groupSeparator': ',', 'autoGroup': true, 'digits': 2, 'digitsOptional': false, 'placeholder': '0.00'});
    $('#vat_percentage').inputmask({ 'alias': 'decimal', 'groupSeparator': ',', 'autoGroup': true, 'digits': 2, 'digitsOptional': false, 'placeholder': '0.00'});
    $('#wht_percentage').inputmask({ 'alias': 'decimal', 'groupSeparator': ',', 'autoGroup': true, 'digits': 2, 'digitsOptional': false, 'placeholder': '0.00'});
    $("#tin").inputmask("999-999-999-999");
    $('#vatable').bootstrapToggle();
    $('#less_wht').bootstrapToggle();
    $('#penalty_exempt').bootstrapToggle();
    $('#preop_amount').inputmask({ 'alias': 'decimal', 'groupSeparator': ',', 'autoGroup': true, 'digits': 2, 'digitsOptional': false, 'placeholder': '0.00'});
    var url = window.location;
    $('ul.sidebar-menu li a[href="'+ url +'"]').addClass('active');
});



$('[data-toggle="tooltip"]').tooltip();

function openModal(selector)
{
    $('#' + selector).modal({
        backdrop: 'static',
        keyboard: false
    });
}

function clearForm(form_id)
{
	$('#' + form_id).closest('form').find("input[type=text], textarea").val("");
}

function alertMe(type, msg)
{
	$.dreamAlert({
        'type'      :   type,
        'message'   :   msg
    });
}


function openSpinner()
{
	$('#spinner_modal').modal({
        backdrop: 'static',
        keyboard: false
    });
}

function check_rentalType()
{
    var rental_type = $('#rental_type').val();
    if (rental_type == 'Fixed' || rental_type == 'WOF')
    {
        $('#percentage_holder').html('');
    }
    else
    {
        $('#percentage_holder').html('<div class="row">' +
                '<div class="col-md-12">' +
                    '<div class="form-group">' +
                        '<label class="col-sm-4 control-label col-lg-4" for="rent_percentage"><b>Rent Percentage </b></label>' +
                        '<div class="col-lg-8">' +
                            '<input type="text" name = "rent_percentage" required class="form-control currency" id="rent_percentage">' +
                        '</div>' +
                    '</div>' +
                '</div>' +
            '</div>');


        $('#rent_percentage').inputmask({ 'alias': 'decimal', 'groupSeparator': ',', 'autoGroup': true, 'digits': 2, 'digitsOptional': false, 'placeholder': '0.00'});

    }
}
