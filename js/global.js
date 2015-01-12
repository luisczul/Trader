// JavaScript Document

//GLOBAL VARIABLES


var widgetsToReload = [];
var widgetsList = {};
var imgLoad = $('<img />', { src : '/img/ajax-loader.gif' });

var debug = true;

//TRAINEE
widgetsList['w_weights'] = "/widgets/weight";
widgetsList['w_workouts'] = "/widgets/workouts";
widgetsList['w_trendingWorkouts'] = "/widgets/trendingWorkouts";
widgetsList['w_workoutMarket'] = "/widgets/workoutMarket";
widgetsList['w_workoutMarket_full'] = "/widgets/workoutMarket/full";
widgetsList['w_exercises'] = "/widgets/exercises";
widgetsList['w_exercises_full'] = "/widgets/exercises_full";
widgetsList['w_objectives'] = "/widgets/objectives";
widgetsList['w_objectives_full'] = "/widgets/objectives/full";
widgetsList['w_weights_full'] = "/widgets/weight/full";
widgetsList['w_pictures_full'] = "/widgets/pictures/full";
widgetsList['w_friends'] = "/widgets/friends";
widgetsList['w_friends_full'] = "/widgets/friends/full";
widgetsList['w_measurements'] = "/widgets/measurements";
widgetsList['w_measurements_full'] = "/widgets/measurements/full";

//TRAINER
widgetsList['w_clients'] = "/widgets/clients";
widgetsList['w_appointments'] = "/widgets/appointments";
widgetsList['w_calendar_full'] = "/widgets/calendar/full";
widgetsList['w_feedClient'] = "/widgets/clientsFeed";
widgetsList['w_video_word_full'] = "/widgets/videoWord";
widgetsList['w_biography_full'] = "/widgets/biography/full";
widgetsList['w_testimonials_full'] = "/widgets/testimonials/full";
widgetsList['w_tasks'] = "/widgets/tasks";
widgetsList['w_sessions'] = "/widgets/sessions";
widgetsList['w_sessions_full'] = "/widgets/sessions/full";
widgetsList['w_workoutSales'] = "/widgets/workoutSales";
widgetsList['w_workoutsTrainer_full'] = "/widgets/workoutsTrainer/full";
widgetsList['w_workoutsTrainer'] = "/widgets/workoutsTrainer";


function toggle(id){
	$("#"+id).slideToggle();
	$("#"+id).closest(".widgets").find(".emptyMessage").toggle();
}

function down(id){
	$("#"+id).slideDown();
}

function up(id){
	$("#"+id).slideUp();
}

function upAndClearAdd(){
	$(".add").each(function(i, obj) {
	    $(obj).slideUp();
	    var userId = "";
	    userId = $(obj).find("input[name='userId']").val(); 
	    $(obj).find('input[type=text]').val(''); 
	    $(obj).find('input[type=file]').val(''); 
	    //$(obj).find('input:hidden').val('');    
	    $(obj).find('textarea').val('');    
	    //$(obj).find("form").trigger('reset');
	    if(userId!=""){
	    	$(obj).find("input[name='userId']").val(userId); 
	    }
	});
}

function toggleSideBar(){
	
	if($(".sidebar-nav").css("display") == "none"){
		$("#page-wrapper").css("margin-left","250px");
		$(".sidebar-nav").css("display","block");
	} else {
		$("#page-wrapper").css("margin-left","0px");
		$(".sidebar-nav").css("display","none");	
	}
	
	
}

//Print for datatables show button.
function echoEdit(id,functionName){
	var output = "";
	if(functionName !== undefined){
		output = '<td class="center"><button class="btn btn-primary btn-circle" type="button" onclick="'+functionName+'('+id+')"><i class="fa fa-list"></i></button></td>';
	} else {
		output = '<td class="center"><button class="btn btn-primary btn-circle" type="button" onclick="edit('+id+')"><i class="fa fa-list"></i></button></td>';
	}
	return output;
}
//Print for datatables show button.
function echoRemove(id,functionName){
		var output = "";
		if(functionName !== undefined){
			output = '<td class="center removeIcon"><button class="btn btn-danger btn-circle" type="button" onclick="'+functionName+'('+id+')"><i class="fa fa-times"></i></button></td>'
		} else {
			output = '<td class="center removeIcon"><button class="btn btn-danger btn-circle" type="button" onclick="del('+id+')"><i class="fa fa-times"></i></button></td>'
		}
		return output;
}

//Print for datatables show button.
function echoCustomFunction(id,functionName,buttonText){
	var output = "";
	output = '<td class="center"><button class="btn btn-primary" type="button" onclick="'+functionName+'(\''+id+'\',$(this))">'+buttonText+'</button></td>';
	return output;
}

//Print for datatables show button.
function echoEditString(id,functionName){
	var output = "";
	if(functionName !== undefined){
		output = '<td class="center"><button class="btn btn-primary btn-circle" type="button" onclick="'+functionName+'(\''+id+'\',$(this))"><i class="fa fa-list"></i></button></td>';
	} else {
		output = '<td class="center"><button class="btn btn-primary btn-circle" type="button" onclick="edit(\''+id+'\',$(this))"><i class="fa fa-list"></i></button></td>';
	}
	return output;
}
//Print for datatables show button.
function echoRemoveString(id,functionName){
	var output = "";
		if(functionName !== undefined){
			output = '<td class="center removeIcon"><button class="btn btn-danger btn-circle" type="button" onclick="'+functionName+'(\''+id+'\',$(this))"><i class="fa fa-times"></i></button></td>'
		} else {
			output = '<td class="center removeIcon"><button class="btn btn-danger btn-circle" type="button" onclick="del(\''+id+'\',$(this))"><i class="fa fa-times"></i></button></td>'
		}
		return output;
}

function successMessage(msg){
	$(".systemMessages")
		.show()
		.append("<div class='successBox' id='successBoxId'>"+msg+"</div>");
	setTimeout(function(){$("div#successBoxId").fadeOut(300)},6000);
}

function errorMessage(msg){
	$(".systemMessages")
		.show()
		.append("<div class='errorBox' id='errorBoxId'><a class='hideErrorMessageButton' href='JavaScript:void(0)' onClick='$(this).parent().hide();'>Hide</a>"+msg+"</div>");
	if(!debug){
		setTimeout(function(){$("div#errorBoxId").fadeOut(300)},8000);
	}
}

function callWidget(wid,pageSize,userId){
    $.ajax(
    {
        url :widgetsList[wid],
        type: "POST",
        data: { pageSize:pageSize },
        success:function(data, textStatus, jqXHR) 
        {
        	$("#"+wid).html(data);

        },
        error: function(jqXHR, textStatus, errorThrown) 
        {
        	errorMessage(jqXHR.responseText);
        },
        statusCode: {
	        500: function() {
	        	if(jqXHR.responseText != ""){
	        		errorMessage(jqXHR.responseText);
	        	}else {
	        		
	        	}
	            
	        }
	    }
    });
}

function callWidgetExternal(wid,pageSize,userId){

    $.ajax(
    {
        url :widgetsList[wid],
        type: "POST",
        data: { pageSize:pageSize,userId:userId  },
        success:function(data, textStatus, jqXHR) 
        {
        	$("#"+wid).html(data);

        },
        error: function(jqXHR, textStatus, errorThrown) 
        {
        	errorMessage(jqXHR.responseText);
        },
        statusCode: {
	        500: function() {
	        	if(jqXHR.responseText != ""){
	        		errorMessage(jqXHR.responseText);
	        	}else {
	        		
	        	}
	            
	        }
	    }
    });
}

function refreshWidgets(){
	for (var i = 0; i < widgetsToReload.length; i++) {
	    callWidget(widgetsToReload[i]);
	}
	widgetsToReload.splice(0,widgetsToReload.length);
}

function refreshWidgetsExternal(userId){

	for (var i = 0; i < widgetsToReload.length; i++) {
	    callWidgetExternal(widgetsToReload[i],null,userId);
	}
	widgetsToReload.splice(0,widgetsToReload.length);
}

$(document).ready(function(){


	//DATEPICKER
	$(function(datepicker) {
		$( ".datepicker" ).datepicker({
			changeYear: true,
			dateFormat: 'yy-mm-dd',
			yearRange: '1920:2019',
		});
		
	});

	//TIMEPICKER
	$('.time').timepicker();


	//ALL AJAX FORMS SAVE
	$("body").on("click",".ajaxSave",function(event){
		//alert(1);
		
		var handler = $(this);
		tForm = $(this).closest("form");
		widget = $(this).attr("widget");
		tForm.submit(function(e)
			{
				e.preventDefault(); //STOP default action
				e.stopImmediatePropagation();
			    //var postData = $(this).serializeArray();
			    var formURL = $(this).attr("action");
			    var preload;
			    $.ajax(
			    {
			        url : formURL,
			        type: "POST",
			        data: new FormData( this ),
				    processData: false,
				    contentType: false,
			        beforeSend:function() 
			        {
                                preLoad = showLoadWithElement(handler);
                    },
			        success:function(data, textStatus, jqXHR) 
			        {
			        	successMessage(data);
			        	upAndClearAdd();
			        	widgetsToReload.push(widget);
			        	refreshWidgets();
			        	return false;

			        },
			       	complete:function() 
			       	{
                                hideLoadWithElement(preLoad);
                    },
			        error: function(jqXHR, textStatus, errorThrown) 
			        {
			        	errorMessage(jqXHR.responseText);
			        	hideLoadWithElement(preLoad);
			        	return false;
			        },
			        statusCode: {
				        500: function() {
				        	if(jqXHR.responseText != ""){
				        		errorMessage(jqXHR.responseText);
				        		hideLoadWithElement(preLoad);
				        	}else {
				        		hideLoadWithElement(preLoad);
				        	}
				            
				        }
				    }
			    });
			    
			}
		);
	});

	$("body").on("click",".ajaxSaveExternal",function(event){
		//alert(1);
		
		var handler = $(this);
		tForm = $(this).closest("form");
		widget = $(this).attr("widget");
		var userId = tForm.find("input[name=userId]").val();
		tForm.submit(function(e)
			{
				e.preventDefault(); //STOP default action
				e.stopImmediatePropagation();
			    //var postData = $(this).serializeArray();
			    var formURL = $(this).attr("action");
			    var preload;
			    $.ajax(
			    {
			        url : formURL,
			        type: "POST",
			        data: new FormData( this ),
				    processData: false,
				    contentType: false,
			        beforeSend:function() 
			        {
                                preLoad = showLoadWithElement(handler);
                    },
			        success:function(data, textStatus, jqXHR) 
			        {
			        	successMessage(data);
			        	upAndClearAdd();
			        	widgetsToReload.push(widget);
			        	refreshWidgetsExternal(userId);
			        	return false;

			        },
			       	complete:function() 
			       	{
                                hideLoadWithElement(preLoad);
                    },
			        error: function(jqXHR, textStatus, errorThrown) 
			        {
			        	errorMessage(jqXHR.responseText);
			        	hideLoadWithElement(preLoad);
			        	return false;
			        },
			        statusCode: {
				        500: function() {
				        	if(jqXHR.responseText != ""){
				        		errorMessage(jqXHR.responseText);
				        		hideLoadWithElement(preLoad);
				        	}else {
				        		hideLoadWithElement(preLoad);
				        	}
				            
				        }
				    }
			    });
			    
			}
		);
	});

	//ALL AJAX FORMS SAVE
	$("body").on("click",".ajaxSaveFancyBox",function(event){
		//alert(1);
		
		var handler = $(this);
		tForm = $(this).closest("form");
		widget = $(this).attr("widget");
		tForm.submit(function(e)
			{
				e.preventDefault(); //STOP default action
				e.stopImmediatePropagation();
			    //var postData = $(this).serializeArray();
			    var formURL = $(this).attr("action");
			    var preload;
			    $.ajax(
			    {
			        url : formURL,
			        type: "POST",
			        data: new FormData( this ),
				    processData: false,
				    contentType: false,
			        beforeSend:function() 
			        {
                                preLoad = showLoadWithElement(handler);
                    },
			        success:function(data, textStatus, jqXHR) 
			        {
			        	successMessage(data);
			        	widgetsToReload.push("w_tasks");
			        	widgetsToReload.push("w_appointments");
			        	widgetsToReload.push("w_calendar_full");
			        	refreshWidgets();
			        	parent.jQuery.fancybox.close();
			        	return false;

			        },
			       	complete:function() 
			       	{
                                hideLoadWithElement(preLoad);
                    },
			        error: function(jqXHR, textStatus, errorThrown) 
			        {
			        	errorMessage(jqXHR.responseText);
			        	hideLoadWithElement(preLoad);
			        	return false;
			        },
			        statusCode: {
				        500: function() {
				        	if(jqXHR.responseText != ""){
				        		errorMessage(jqXHR.responseText);
				        		hideLoadWithElement(preLoad);
				        	}else {
				        		hideLoadWithElement(preLoad);
				        	}
				            
				        }
				    }
			    });
			    
			}
		);
	});

//ALL AJAX FORMS SAVE
	$("body").on("click",".ajaxSaveSubmit",function(event){
		//alert(1);
		
		var handler = $(this);
		tForm = $(this).closest("form");
		widget = $(this).attr("widget");
		tForm.submit(function(e)
			{
				e.preventDefault(); //STOP default action
				e.stopImmediatePropagation();
			    //var postData = $(this).serializeArray();
			    var formURL = $(this).attr("action");
			    var preload;
			    $.ajax(
			    {
			        url : formURL,
			        type: "POST",
			        data: new FormData( this ),
				    processData: false,
				    contentType: false,
			        beforeSend:function() 
			        {
                                preLoad = showLoadWithElement(handler);
                    },
			        success:function(data, textStatus, jqXHR) 
			        {
			        	successMessage(data);
			        	return false;

			        },
			       	complete:function() 
			       	{
                                hideLoadWithElement(preLoad);
                    },
			        error: function(jqXHR, textStatus, errorThrown) 
			        {
			        	errorMessage(jqXHR.responseText);
			        	return false;
			        },
			        statusCode: {
				        500: function() {
				        	if(jqXHR.responseText != ""){
				        		errorMessage(jqXHR.responseText);
				        	}else {
				        		
				        	}
				            
				        }
				    }
			    });
			    
			}
		);
	});

	$(window).scroll(function(){
	  var sticky = $('.headertop'),
	      scroll = $(window).scrollTop();

	  if (scroll >= 100) {
	  	sticky.addClass('fixed');
	  	$(".profiletitle").hide();
	  	$(".oneLineMenu").show();
	  } else { 
	  	sticky.removeClass('fixed');
	  	$(".oneLineMenu").hide();
	  	$(".profiletitle").show();
	}
	});

});

function showLoadWithElement(el, imageWidth, position){
    var newElement = el.clone();
    var newImage = getLoadImage(imageWidth, position, el);
    el.replaceWith(newImage);
    var elements = {
        element : newElement,
        image : newImage
    }
    return elements;
}
function hideLoadWithElement(elements){

    elements['image'].replaceWith(elements['element']);
}

function getLoadImage(width, position, el){

    var itemP = $('<p />', { id: 'p-loading', style: 'display: inline-block; padding: 0; height: auto; width: ' + el.css('width') +'; text-align: center;' });
    if (position == 'center'){
        itemP.append(imgLoad.clone());
        element = itemP;
    } else if (position == 'right'){
        element = imgLoad.clone();
        element.css({'float':'right'});
    } else
        element = imgLoad.clone();
    if (width > 0)
        element.css({width:width+'px'});
           
    return element;
}

function callNotifications(numero){
	$.ajax(
    {
        url :"/widgets/notifications",
        type: "POST",
        data: { pageSize:numero },
        success:function(data, textStatus, jqXHR) 
        {
        	var response = $.parseJSON(data);
        	if(response.total > 0){
        		$("#notificationsNumber").show();
        		$("#notificationsNumber").text(response.total);
        	} else {
        		$("#notificationsNumber").hide();
        	}
        	$("#notification_preloader").html(response.view);
        },
        error: function(jqXHR, textStatus, errorThrown) 
        {
        	errorMessage(jqXHR.responseText);
        },
        statusCode: {
	        500: function() {
	        	if(jqXHR.responseText != ""){
	        		errorMessage(jqXHR.responseText);
	        	}else {
	        		
	        	}
	            
	        }
	    }
    });
}



function showLastNotifications(){

	$(".notificationdropdown").show();
	$("#notification_preloader").slideDown();
	$.ajax(
    {
        url :"/widgets/notificationsRead",
        type: "POST",
        success:function(data, textStatus, jqXHR) 
        {
        	$("#notificationsNumber").hide();
        },
        error: function(jqXHR, textStatus, errorThrown) 
        {
        	errorMessage(jqXHR.responseText);
        },
        statusCode: {
	        500: function() {
	        	if(jqXHR.responseText != ""){
	        		errorMessage(jqXHR.responseText);
	        	}else {
	        		
	        	}
	            
	        }
	    }
    });
}


function callMessages(numero){
	$.ajax(
    {
        url :"/widgets/messages",
        type: "POST",
        data: { pageSize:numero },
        success:function(data, textStatus, jqXHR) 
        {
        	var response = $.parseJSON(data);
        	if(response.total > 0){
        		$("#messagesNumber").show();
        		$("#messagesNumber").text(response.total);
        		$("#notification_preloader").html(response.view);
        	} else {
        		$("#messagesNumber").hide();
        	}
        },
        error: function(jqXHR, textStatus, errorThrown) 
        {
        	errorMessage(jqXHR.responseText);
        },
        statusCode: {
	        500: function() {
	        	if(jqXHR.responseText != ""){
	        		errorMessage(jqXHR.responseText);
	        	}else {
	        		
	        	}
	            
	        }
	    }
    });
}

function heavy(obj){
		var handler = $(obj).find("input:submit");
		preLoad = showLoadWithElement(handler);
}
	
function handleNotification(url,target){
	if(target=='self'){
		$.ajax(
		    {
		        url : url,
		        type: "GET",
		        success:function(data, textStatus, jqXHR) 
		        {
		        	successMessage(data);
		        },
		        error: function(jqXHR, textStatus, errorThrown) 
		        {
		        	errorMessage(jqXHR.responseText);
		        },
		        statusCode: {
			        500: function() {
			        	if(jqXHR.responseText != ""){
			        		errorMessage(jqXHR.responseText);
			        	}else {
			        		
			        	}
			            
			        }
			    }
		    });
	} else {
		window.location = url;
	}
}

function showLastMessages(){
	$(".notificationdropdown").show();
	$("#notification_preloader").slideDown();
}

function available(date) {

    dmy = date.getDate() + "-" + (date.getMonth() + 1) + "-" + date.getFullYear();
  
    if ($.inArray(dmy, availableDates) != -1) {
        return [true, "", "Available"];
    } else {
        return [false, "", "unAvailable"];
    }
}




