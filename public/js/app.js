(function($) {

	$(document).on('click','.btn-remove-2',function(){
		var link = $(this).attr('href');
		//Sweet Alert for delete action
		swal({
		  title: "Are you sure?",
		  text: "Once deleted, you will not be able to recover this record !",
		  icon: "warning",
		  buttons: true,
		  dangerMode: true,
		})
		.then((willDelete) => {
		  if (willDelete) {
			window.location.href = link;
		  } else {
			return false;
		  }
		});
		
		return false;
	});

	$(document).on('click','.btn-remove',function(){
		//Sweet Alert for delete action
		swal({
		  title: "Are you sure?",
		  text: "Once deleted, you will not be able to recover this record !",
		  icon: "warning",
		  buttons: true,
		  dangerMode: true,
		})
		.then((willDelete) => {
		  if (willDelete) {
			$(this).closest('form').submit();
		  } else {
			return false;
		  }
		});
		
		return false;
	});
	
	$(".select2").select2({
		theme : 'classic',
	});
	
	$(".datepicker").datepicker();
	
	$(".telephone").intlTelInput({
		nationalMode: false,
		//separateDialCode: true,
	});

	
	$(".monthpicker").datepicker( {
		format: "mm/yyyy",
		viewMode: "months", 
		minViewMode: "months"
	});	

	$('.dropify').dropify();
	
	$('.datetimepicker').datetimepicker({
		format:'YYYY-MM-DD HH:mm:00'
	});
	
	$('.timepicker').datetimepicker({
		format:'HH:mm:00'
	});

	//Form validation
	validate();	

    /*Summernote editor*/
	if ($("#summernote,.summernote").length) {
		$('#summernote,.summernote').summernote({
			height: 200,
			popover: {
			  image: [],
			  link: [],
			  air: []
		    },
			dialogsInBody: true
		});
	}		
	
	$(".float-field").keypress(function(event) {
	   if ((event.which != 46 || $(this).val().indexOf('.') != -1) &&
			(event.which < 48 || event.which > 57)) { event.preventDefault();
		}
	});	

	$(".int-field").keypress(function(event) {
		if ((event.which < 48 || event.which > 57)) { event.preventDefault();
		}
	});	
	
	$(document).on('click','#modal-fullscreen',function(){
		$("#main_modal >.modal-dialog").toggleClass("fullscreen-modal");
	});
	
	//Mask Plugin
	$('.year').mask('0000-0000');

	//Credit Card Mask
	$('.cc').mask('0000-0000-0000-0000', {placeholder: "____-____-____-____"});
	$('.cvv').mask('000', {placeholder: "___"});
	$('.exp-date').mask('00 / 0000', {placeholder: "MM / YYYY"});
	
	$("input:required, select:required, textarea:required").closest(".form-group").find('.control-label').append("<span class='required'> *</span>");
	
	//Print Command
	$(document).on('click','.print',function(){
		$("#preloader").css("display","block");
		var div = "#"+$(this).data("print");	
		$(div).print({
			timeout: 1000,
		});		
	});
	
	//Appsvan File Upload Field
	$(".appsvan-file").after("<input type='text' class='form-control filename' readOnly>"
	+"<button type='button' class='btn btn-info appsvan-upload-btn'>Browse</button>");
    
	$(".appsvan-file").each(function(){
		if($(this).data("value")){
			$(this).parent().find(".filename").val($(this).data("value"));
		}
		if($(this).attr("required")){
			$(this).parent().find(".filename").prop("required",true);
		}
	});
	
	$(document).on("click",".appsvan-upload-btn",function(){
		$(this).parent().find("input[type=file]").click();
	});
	
	$(document).on('change','.appsvan-file',function(){
		readFileURL(this);
	});

	function readFileURL(input) {
		if (input.files && input.files[0]) {
			var reader = new FileReader();
			reader.onload = function (e) {};
	
			$(input).parent().find(".filename").val(input.files[0].name);
			reader.readAsDataURL(input.files[0]);
		}
	}
	
	//Ajax Modal Function
	$(document).on("click",".ajax-modal",function(){
		 var link = $(this).data("href");
		 if ( typeof link == 'undefined' ) {
		 	link = $(this).attr("href");
		 }

		 var title = $(this).data("title");
		 var fullscreen = $(this).data("fullscreen");
		 $.ajax({
			 url: link,
			 beforeSend: function(){
				$("#preloader").css("display","block"); 
			 },success: function(data){
				$("#preloader").css("display","none");
				$('#main_modal .modal-title').html(title);
				$('#main_modal .modal-body').html(data);
				$("#main_modal .alert-success").css("display","none");
				$("#main_modal .alert-danger").css("display","none");
				$('#main_modal').modal('show'); 
				
				if(fullscreen ==true){
					$("#main_modal >.modal-dialog").addClass("fullscreen-modal");
				}else{
					$("#main_modal >.modal-dialog").removeClass("fullscreen-modal");
				}
				
				//init Essention jQuery Library
				$("select.select2").select2({
					theme : 'classic',
				});
				$('.year').mask('0000-0000');
				$(".ajax-submit").validate();
				$('.datepicker').datepicker({
					format: 'yyyy-mm-dd',
				}).on('changeDate', function(e){
					$(this).datepicker('hide');
				});
                $('.datepicker').datepicker().on('hide', function(e) {
					$("#main_modal").css("overflow-y","auto");
				});
				
				$(".telephone").intlTelInput({
					nationalMode: false,
					//separateDialCode: true,
				});

				$(".float-field").keypress(function(event) {
				   if ((event.which != 46 || $(this).val().indexOf('.') != -1) &&
						(event.which < 48 || event.which > 57)) { event.preventDefault();
					}
				});	

				$(".int-field").keypress(function(event) {
					if ((event.which < 48 || event.which > 57)) { event.preventDefault();
					}
				});	

				//Credit Card Mask
				$('.cc').mask('0000-0000-0000-0000', {placeholder: "____-____-____-____"});
				$('.cvv').mask('000', {placeholder: "___"});
				
				$(".dropify").dropify();
				$(".ajax-submit input:required, .ajax-submit select:required, .ajax-submit textarea:required").closest(".form-group").find('.control-label').append("<span class='required'> *</span>");
			 },
			  error: function (request, status, error) {
				console.log(request.responseText);
			  }
		 });
		 
		 return false;
	 }); 
	 
	 $("#main_modal").on('show.bs.modal', function () {
         $('#main_modal').css("overflow-y","hidden"); 		
     });
	 
	 $("#main_modal").on('shown.bs.modal', function () {
		setTimeout(function(){
		  $('#main_modal').css("overflow-y","auto");
		}, 1000);	
     });
	 
	 
	 //Ajax Modal Submit
	 $(document).on("submit",".ajax-submit",function(){			 
		 var link = $(this).attr("action");
		 $.ajax({
			 method: "POST",
			 url: link,
			 data:  new FormData(this),
			 mimeType:"multipart/form-data",
			 contentType: false,
			 cache: false,
			 processData:false,
			 beforeSend: function(){
				$("#preloader").css("display","block");  
			 },success: function(data){
				$("#preloader").css("display","none"); 
				var json = JSON.parse(data);
				if(json['result'] == "success"){
					$("#main_modal .alert-success").html(json['message']);
					$("#main_modal .alert-success").css("display","block");
					$("#main_modal .alert-danger").css("display","none");
					
					$(document).trigger('ajax-submit');

					if(json['action'] == "update"){
						$('#row_' + json['data']['id']).find('td').each (function() {
						   if(typeof $(this).attr("class") != "undefined"){
							   $(this).html(json['data'][$(this).attr("class")]);
						   }
						});  
						
					}else if(json['action'] == "store"){
						$('.ajax-submit')[0].reset();
						//store = true;
						
						var new_row = $("table").find('tr:eq(1)').clone();
        
						if(new_row.length == 0 || typeof new_row.attr('id') === "undefined"){
							window.location.reload();
						}
						
						$(new_row).attr("id", "row_" + json['data']['id']);
						
						$(new_row).find('td').each (function() {	
						   if(typeof $(this).attr("class") != "undefined"){
							   $(this).html(json['data'][$(this).attr("class").split(' ')[0]]);
						   }
						}); 
						
						var url  = window.location.href; 
						$(new_row).find('form').attr("action", url + "/" + json['data']['id']);
						$(new_row).find('.dropdown-edit').attr("data-href", url + "/" + json['data']['id']+"/edit");
						$(new_row).find('.dropdown-view').attr("data-href", url + "/" + json['data']['id']);
						
						$("table").prepend(new_row);
		
						//window.setTimeout(function(){window.location.reload()}, 2000);
					}
				}else{
					if(Array.isArray(json['message'])){
						jQuery.each( json['message'], function( i, val ) {
						   $("#main_modal .alert-danger").html("<p>"+val+"</p>");
						});
						$("#main_modal .alert-success").css("display","none");
						$("#main_modal .alert-danger").css("display","block");
					}else{
						$("#main_modal .alert-danger").html("<p>" + json['message'] + "</p>");	
						$("#main_modal .alert-secondary").css("display","none");
						$("#main_modal .alert-danger").css("display","block");
					}
				}
			 },
			 error: function (request, status, error) {
				console.log(request.responseText);
			 }
		 });

		 return false;
	 });
	 
	 //Ajax Modal Submit without loading
	 $(document).on("submit",".ajax-screen-submit",function(){			 
		 var link = $(this).attr("action");
		 var reload = $(this).data('reload');
		 var current_modal = $(this).closest('.modal');
		 
		 var elem = $(this);
		 $(elem).find("button[type=submit]").prop("disabled",true);
		 
		 $.ajax({
			 method: "POST",
			 url: link,
			 data:  new FormData(this),
			 mimeType:"multipart/form-data",
			 contentType: false,
			 cache: false,
			 processData:false,
			 beforeSend: function(){
				$("#preloader").css("display","block");  
			 },success: function(data){
				$(elem).find("button[type=submit]").attr("disabled",false);	
				$("#preloader").css("display","none"); 
				var json = JSON.parse(data);
	            
				if(json['result'] == "success"){

					$("#main_modal .alert-success").html(json['message']);
					$("#main_modal .alert-success").css("display","block");
					$("#main_modal .alert-danger").css("display","none");

					var table  = json['table'];
					
					if(json['action'] == "update"){
						
						$(table + ' tr[data-id="row_' + json['data']['id'] +'"]').find('td').each (function() {
						   if(typeof $(this).attr("class") != "undefined"){
							   $(this).html(json['data'][$(this).attr("class")]);
						   }
						});  
						
					}else if(json['action'] == "store"){
						$(elem)[0].reset();
						var new_row = $(table).find('tbody').find('tr:eq(0)').clone();
     
						$(new_row).attr("data-id", "row_"+json['data']['id']);
						
						
						$(new_row).find('td').each (function() {
						    if($(this).attr("class") == "dataTables_empty"){
							   window.location.reload();
						    }	
						    if(typeof $(this).attr("class") != "undefined"){
							   $(this).html(json['data'][$(this).attr("class").split(' ')[0]]);
						    }
						}); 
						

						$(new_row).find('form').attr("action", link + "/" + json['data']['id']);
						$(new_row).find('.dropdown-edit').attr("data-href", link + "/" + json['data']['id']+"/edit");
						$(new_row).find('.dropdown-view').attr("data-href", link + "/" + json['data']['id']);
						
						$(table).prepend(new_row);

					}
				
				}else if(json['result'] == "error"){
					if(Array.isArray(json['message'])){
						jQuery.each( json['message'], function( i, val ) {
						   $("#main_modal .alert-danger").html("<p>"+val+"</p>");
						});
						$("#main_modal .alert-success").css("display","none");
						$("#main_modal .alert-danger").css("display","block");
					}else{
						$("#main_modal .alert-danger").html("<p>" + json['message'] + "</p>");	
						$("#main_modal .alert-secondary").css("display","none");
						$("#main_modal .alert-danger").css("display","block");
					}
				}
			 },
			 error: function (request, status, error) {
				console.log(request.responseText);
			 }
		 });

		 return false;
	 });
	 
	 //Ajax submit with validate
	 $(".appsvan-submit-validate").validate({
		 submitHandler: function(form) {
			 var elem = $(form);
			 $(elem).find("button[type=submit]").prop("disabled",true);
			 var link = $(form).attr("action");
			 $.ajax({
				 method: "POST",
				 url: link,
				 data:  new FormData(form),
				 mimeType:"multipart/form-data",
				 contentType: false,
				 cache: false,
				 processData:false,
				 beforeSend: function(){
				   button_val = $(elem).find("button[type=submit]").text();
				   $(elem).find("button[type=submit]").html('<i class="fas fa-circle-notch fa-spin" aria-hidden="true"></i>');
				 
				 },success: function(data){
					$(elem).find("button[type=submit]").html(button_val);
					$(elem).find("button[type=submit]").attr("disabled",false);				
					var json = JSON.parse(data);
					if(json['result'] == "success"){
						Command: toastr["success"](json['message']);
					}else{
						jQuery.each( json['message'], function( i, val ) {
						   Command: toastr["error"](val);
						});
					}
				 }
			 });

			return false; 
		},invalidHandler: function(form, validator) {},
		  errorPlacement: function(error, element) {}
	 });
	 
	 //Ajax submit without validate
	 $(document).on("submit",".appsvan-submit",function(){		 
		 var elem = $(this);
		 $(elem).find("button[type=submit]").prop("disabled",true);
		 var link = $(this).attr("action");
		 $.ajax({
			 method: "POST",
			 url: link,
			 data:  new FormData(this),
			 mimeType:"multipart/form-data",
			 contentType: false,
			 cache: false,
			 processData:false,
			 beforeSend: function(){
			   button_val = $(elem).find("button[type=submit]").text();
			   $(elem).find("button[type=submit]").html('<i class="fas fa-circle-notch fa-spin" aria-hidden="true"></i>');
			 
			 },success: function(data){
				$(elem).find("button[type=submit]").html(button_val);
				$(elem).find("button[type=submit]").attr("disabled",false);				
				var json = JSON.parse(data);
				if(json['result'] == "success"){
					Command: toastr["success"](json['message']);
				}else{
					jQuery.each( json['message'], function( i, val ) {
					   Command: toastr["error"](val);
					});
					
				}
			 }
		 });

		 return false;
	 });
	 
	 
	$("#main_modal").on("hidden.bs.modal", function () {});

	//Auto Selected
	if ($(".auto-select").length) {
		$('.auto-select').each(function(i, obj) {
			$(this).val($(this).data('selected')).trigger('change');
		})	
	}


})(jQuery);


function validate(){
	//Validation Form
	$(".validate").validate({
		submitHandler: function(form) {
			form.submit();
		},invalidHandler: function(form, validator) {},
		  errorPlacement: function(error, element) {}
	});
}

