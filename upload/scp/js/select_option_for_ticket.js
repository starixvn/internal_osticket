$(document).ready(function(){    
    // search league when create new ticket
    searchLeague();
    // search league when edit ticket
    searchLeagueWhenEdit();

    var options = {
        script: "ajax.php?api=tickets&f=searchbyemail&limit=10&",
        varname: "input",
        json: true,
        shownoresults:false,
        maxresults:10,
        callback: function (obj) {
            document.getElementById('email').value = obj.id;
            document.getElementById('name').value = obj.info;
            return false;
        }
    };
    
    var autosug = new bsn.AutoSuggest('email', options);
	
	$(".departments-select").chosen();

    // auto complate word for selectbox 'sport'
    $(".sport-select").chosen();

    // auto complate word for selectbox 'match'
    $(".match-select").chosen();

    // auto complate word for selectbox 'trader'
    $(".trader-select").chosen({
        no_results_text: "<a id='add_new_trader' href='javascript:void(0)'>Add New</a>"
        ,
        allow_single_deselect:true
    });

    // auto complate word for selectbox 'suspender'
    $(".suspender-select").chosen({
        no_results_text: "<a id='add_new_suspender' href='javascript:void(0)'>Add New</a>"
        ,
        allow_single_deselect:true
    });

    // auto complate word for selectbox 'csd'
    $(".csd-select").chosen({
        no_results_text: "<a id='add_new_csd' href='javascript:void(0)'>Add New</a>"
        ,
        allow_single_deselect:true
    });  
    // auto complate word for selectbox 'provider'
    $(".provider-select").chosen({
        no_results_text: "<a id='add_new_provider' href='javascript:void(0)'>Add New</a>"
        ,
        allow_single_deselect:true
    });
    
    $("#sport").live("change", function() { 
        var league_new = $("#league_new"),
            league_edit = $("#league_edit");
            
        // clear all leagues when create ticket
        if(league_new != undefined) {
			league_new.select2("val", "");
        }
       
       // search league when edit ticket
        if(league_edit != undefined) {
            $("#league_name").val("All Leagues");
            $("#league").val("");
            league_edit.select2("destroy");     
            searchLeagueWhenEdit();
            // when user search league, but user want to choose other sport 
            if($(".select2-chosen").val() == "") {
                $(".select2-chosen").html('All Leagues');
            }  
        }
        
        $("#match").removeAttr("disabled");
        loadMatchList();
    })
    
    // add new trader
    $( "#dialog_trader" ).dialog({
        autoOpen: false,
        width: 600,
        zIndex: 1222,
        open: function( event, ui ) {
            $('.layer_poup_display').show();
            // get value trader from textbox 'search'                   
            var trader_name = $("li.no-results > span").html();
            $('#trader_name').val(trader_name);
        },
        position:[210 ,200],
        buttons: [
        {
            text: "Ok",
            click: function() {
                var trader_name = $('#trader_name').val();
                var contact_info = $('#contact_info_trader').val();
                $.ajax({
                    type:"GET",
                    url: "loadAjax.php",
                    data: {
                        name:trader_name,
                        contact_info:contact_info,
                        type:'1'
                    },
                    dataType: "json",
                    success: function(data){                                        
                        $("#trader").prepend('<option value="' + data.actor_id + '">' + data.actor_name + '</option>');                                        
                        $(".trader-select").val(data.actor_id).trigger("liszt:updated");
                    },  
                    error: function(){}  
                });
                    
                // close poup
                $('.layer_poup_display').hide();
                $( this ).dialog( "close" );                                    
            }
        },
        {
            text: "Cancel",
            click: function() {
                $('.layer_poup_display').hide();
                $( this ).dialog( "close" );                                    
            }
        }
        ],
        close: function( event, ui ) {
            $( this ).dialog( "close" );
            $('.layer_poup_display').hide();
        } 
    });

    // event 'click' for add new trader
    $("#add_new_trader").live("click", function() {            
        //raise popup                    
        $( "#dialog_trader" ).dialog( "open" );
    })
        
    // add new suspender
    $( "#dialog_suspender" ).dialog({
        autoOpen: false,
        width: 600,
        zIndex: 1222,
        open: function( event, ui ) {
            $('.layer_poup_display').show();
            // get value trader from textbox 'search'
            var suspender_name = $("li.no-results > span").html();
            $('#suspender_name').val(suspender_name);
        },
        position:[210 ,230],
        buttons: [
        {
            text: "Ok",
            click: function() {
                var suspender_name = $('#suspender_name').val();
                var contact_info = $('#contact_info_suspender').val();
                $.ajax({
                    type:"GET",
                    url: "loadAjax.php",
                    data: {
                        name:suspender_name,
                        contact_info:contact_info,
                        type:'2'
                    },
                    dataType: "json",
                    success: function(data){                                        
                        $("#suspender").prepend('<option value="' + data.actor_id + '">' + data.actor_name + '</option>');                                        
                        $(".suspender-select").val(data.actor_id).trigger("liszt:updated");
                    },  
                    error: function(){}  
                });
                // close poup
                $('.layer_poup_display').hide();
                $( this ).dialog( "close" );                                    
            }
        },
        {
            text: "Cancel",
            click: function() {
                $('.layer_poup_display').hide();
                $( this ).dialog( "close" );                                    
            }
        }
        ],
        close: function( event, ui ) {
            $( this ).dialog( "close" );
            $('.layer_poup_display').hide();
        }  
    });
    // event 'click' for add new suspender
    $("#add_new_suspender").live("click", function() {            
        //raise popup                 
        $( "#dialog_suspender" ).dialog( "open" );
    })

    // add new csd
    $( "#dialog_csd" ).dialog({
        autoOpen: false,
        width: 600,
        zIndex: 1222,
        open: function( event, ui ) {
            $('.layer_poup_display').show();
            // get value trader from textbox 'search'
            var csd_name = $("li.no-results > span").html();
            $('#csd_name').val(csd_name);
        },
        position:[210 ,250],
        buttons: [
        {
            text: "Ok",
            click: function() {
                var csd_name = $('#csd_name').val();
                var contact_info = $('#contact_info_csd').val();
                $.ajax({
                    type:"GET",
                    url: "loadAjax.php",
                    data: {
                        name:csd_name,
                        contact_info:contact_info,
                        type:'3'
                    },
                    dataType: "json",
                    success: function(data){                                        
                        $("#csd").prepend('<option value="' + data.actor_id + '">' + data.actor_name + '</option>');                                        
                        $(".csd-select").val(data.actor_id).trigger("liszt:updated");
                    },  
                    error: function(){}  
                });
                // close poup
                $('.layer_poup_display').hide();
                $( this ).dialog( "close" );                                    
            }
        },
        {
            text: "Cancel",
            click: function() {
                $('.layer_poup_display').hide();
                $( this ).dialog( "close" );                                    
            }
        }
        ],
        close: function( event, ui ) {
            $( this ).dialog( "close" );
            $('.layer_poup_display').hide();
        } 
    });
    // event 'click' for add new csd
    $("#add_new_csd").live("click", function() {            
        //raise popup                 
        $( "#dialog_csd" ).dialog( "open" );
    })
    
    // add new provider
    $( "#dialog_provider" ).dialog({
        autoOpen: false,
        width: 600,
        zIndex: 1222,
        open: function( event, ui ) {
            $('.layer_poup_display').show();
            // get value trader from textbox 'search'
            var provider_name = $("li.no-results > span").html();
            $('#provider_name').val(provider_name);
        },
        position:[210 ,250],
        buttons: [
        {
            text: "Ok",
            click: function() {
                var provider_name = $('#provider_name').val();
                var contact_info = $('#contact_info_provider').val();
                $.ajax({
                    type:"GET",
                    url: "loadAjax.php",
                    data: {
                        name:provider_name,
                        contact_info:contact_info,
                        type:'4'
                    },
                    dataType: "json",
                    success: function(data){                                        
                        $("#provider").prepend('<option value="' + data.actor_id + '">' + data.actor_name + '</option>');                                        
                        $(".provider-select").val(data.actor_id).trigger("liszt:updated");
                    },  
                    error: function(){}  
                });
                // close poup
                $('.layer_poup_display').hide();
                $( this ).dialog( "close" );                                    
            }
        },
        {
            text: "Cancel",
            click: function() {
                $('.layer_poup_display').hide();
                $( this ).dialog( "close" );                                    
            }
        }
        ],
        close: function( event, ui ) {
            $( this ).dialog( "close" );
            $('.layer_poup_display').hide();
        } 
    });
    
    // event 'click' for add new provider
    $("#add_new_provider").live("click", function() {            
        //raise popup                 
        $( "#dialog_provider" ).dialog( "open" );
    })

    // add new match layer
    $('#fc').css({
        'z-index':'9998'
    });
    
    // auto complate word for selectbox 'home team'
    $(".home-popup-select").chosen();
    $(".home-popup-select-deselect").chosen({
        allow_single_deselect:true
    });

    // auto complate word for selectbox 'away team'
    $(".away-popup-select").chosen();
    $(".away-popup-select-deselect").chosen({
        allow_single_deselect:true
    });
    
    // if this team is chosen at home team, this team will not be chosen at away team
    $('#home_id_popup').change(function(){
        home_id = $('#home_id_popup').val();
        $("#away_id_popup option[value='" + home_id + "']").remove();

    });

    // if this team is chosen at away team, this team will not be chosen at home team
    $('#away_id_popup').change(function(){
        away_id = $('#away_id_popup').val();
        $("#home_id_popup option[value='" + away_id + "']").remove();

    });
    
    //filter for matches
    $('#filter').click(function(){
		if($('#league').val()) {
			$("#match").removeAttr("disabled");
			loadMatchList();
		}
		else {
			open_dialog('Please choose a League.');
		}
    })
    $("#match").live("change", function() { 
        // validate match
        var match_name = $('#match_chzn').find('a').find('span').html();
        if (match_name != 'Select Match') {
            $('#mess_match').html('');			
        }
    });
    // synchronise latest data
    $('#syncData').click(function(){
        $('#img_sync').attr('src', 'images/icons/syn_click.gif');
        var typeAPI = $('#sync').val();            
        switch(typeAPI) {
            case '0':// typeAPI = 0 => all
                $.ajax({
                    type: "GET",
                    url: 'loadAjax.php',
                    data: {
                        typeAPI:typeAPI, 
                        type:'sync_data'
                    },
                    dataType: "json",
                    success: function(data){
                        console.log(data);
                        var strAlert = '';                            
                        $.each(data, function(key, value_data){
                            if(key == 'sport') {
                                if(value_data.length > 0) {
                                    $.each(value_data, function(key_sport, value_sport){
                                        $("#sport").prepend('<option value="' + value_sport.sport_type + '">' + value_sport.sport_name + '</option>');
                                        $(".sport-select").trigger("liszt:updated");
                                    });
                                    strAlert += value_data.length + ' sport(s) updated.<br>';
                                } else {
                                    strAlert += 'No new data of sport.<br>';
                                } 
                            }
                            if(key == 'league') {
                                if(value_data.length > 0) {                                        
                                    strAlert += value_data.length + ' league(s) updated.<br>';
                                } else {
                                    strAlert += 'No new data of league.<br>';
                                }                                    
                            } 
                            if(key == 'team') {
                                if(value_data.length > 0) {
                                    strAlert += value_data.length + ' team(s) updated.<br>';
                                } else {
                                    strAlert += 'No new data of team.<br>';
                                }
                            }
                            if(key == 'match') {
                                if(value_data.length > 0) {
                                    strAlert += value_data.length + ' match(es) updated.<br>';
                                } else {
                                    strAlert += 'No new data of match.<br>';
                                }
                            }
                        });
                        if(strAlert != '') {
                            open_dialog(strAlert);                          
                        } else {
                            open_dialog('No new data.');
                        }
                        $('#img_sync').attr('src', 'images/icons/sync_1.png');
                    },
                    error: function(){
                        open_dialog('Cannot synchronize data.');
                        $('#img_sync').attr('src', 'images/icons/sync_1.png');
                    }
                });
                break;
            case '1':// typeAPI = 1 => sport
                $.ajax({
                    type: "GET",
                    url: 'loadAjax.php',
                    data: {
                        typeAPI:typeAPI, 
                        type:'sync_data'
                    },
                    dataType: "json",
                    success: function(data){
                        if(data.length > 0) {
                            $.each(data, function(key, value){ 
                                $("#sport").prepend('<option value="' + value.sport_type + '">' + value.sport_name + '</option>');                                      
                            });
                            $(".sport-select").trigger("liszt:updated");
                            open_dialog(data.length + ' sport(s) updated.');                         
                        } else {
                            open_dialog('No new data of sport.');
                        }
                        $('#img_sync').attr('src', 'images/icons/sync_1.png');
                    },
                    error: function(){
                        open_dialog('Cannot synchronize data of sport.');
                        $('#img_sync').attr('src', 'images/icons/sync_1.png');
                    }
                });
                break;
            case '2': // typeAPI = 2 => league
                $.ajax({
                    type: "GET",
                    url: 'loadAjax.php',
                    data: {
                        typeAPI:typeAPI, 
                        type:'sync_data'
                    },
                    dataType: "json",
                    success: function(data){
                        if(data.length > 0) {
                            open_dialog(data.length + ' league(s) updated.');                          
                        } else {
                            open_dialog('No new data of league.');
                        }
                        $('#img_sync').attr('src', 'images/icons/sync_1.png');
                    },
                    error: function(){
                        open_dialog('Cannot synchronize data of league.');
                        $('#img_sync').attr('src', 'images/icons/sync_1.png');
                    }
                });
                break;
            case '3': // typeAPI = 3 => team
                $.ajax({
                    type: "GET",
                    url: 'loadAjax.php',
                    data: {
                        typeAPI:typeAPI, 
                        type:'sync_data'
                    },
                    dataType: "json",
                    success: function(data){
                        if(data.length > 0) {
                            open_dialog(data.length + ' team(s) updated.');                         
                        } else {
                            open_dialog('No new data of team.');
                        }
                        $('#img_sync').attr('src', 'images/icons/sync_1.png');                       
                    },
                    error: function(){
                        open_dialog('Cannot synchronize data of team.');
                        $('#img_sync').attr('src', 'images/icons/sync_1.png');                       
                    }
                });
                break;
            case '4': // typeAPI = 4 => match
                $.ajax({
                    type: "GET",
                    url: 'loadAjax.php',
                    data: {
                        typeAPI:typeAPI, 
                        type:'sync_data'
                    },
                    dataType: "json",
                    success: function(data){
                        if(data.length > 0) {
                            open_dialog(data.length + ' match(es) updated.');
                        /*try {
                                alert(data.length + ' match(es) updated.');                               
                            } catch ($e){}  */                          
                        } else {
                            open_dialog('No new data of match.');
                        }
                        $('#img_sync').attr('src', 'images/icons/sync_1.png');
                    },
                    error: function(){
                        open_dialog('Cannot synchronize data of match.');
                        $('#img_sync').attr('src', 'images/icons/sync_1.png');
                    }
                });
                break;
            default:
                break;
        }
    })       
});

$(window).load(function() {
	/* put event date*/
    var startDateTextBox = $('#fromDate');
    var endDateTextBox = $('#toDate');    
    // set default for start date and end date
    var today = new Date();
    var dd = today.getDate();
    var mm = today.getMonth()+1; //January is 0!
    var yyyy = today.getFullYear();
    if(dd < 10){
        dd = '0'+dd;
    } 
    if(mm < 10){
        mm = '0' + mm;
    } 
    today = mm + '/' + dd + '/' + yyyy;
	
	var deptIds = $('#data-department').attr('data-department'),
		leagueId = $('#data-league').attr('data-league-id'),
		leagueName = $('#data-league').attr('data-league-name'),
		matchId = $('#data-match').attr('data-match-id'),
		matchName = $('#data-match').attr('data-match-name'),
		fromDate = $('#data-fromDate').attr('data-date'),
		fromTime = $('#data-fromDate').attr('data-time'),
		toDate = $('#data-toDate').attr('data-date'),
		toTime = $('#data-toDate').attr('data-time'),
		league_new = $("#league_new"),
		league_edit = $("#league_edit");
	
	if(deptIds){
		var ids = deptIds.split(',').map(function(value) { return parseInt(value); });
		$('.departments-select').val(ids).trigger("liszt:updated");
	}
	
	if(fromDate) {
		startDateTextBox.val(fromDate);
	}
	else {
		startDateTextBox.val(today);
	}
	
	if(toDate) {
		endDateTextBox.val(toDate);
	}
	else {
		endDateTextBox.val(today);
	}
	
	if(fromTime) {
		$('#fromTime').val(fromTime);
	}
	
	if(toTime) {
		$('#toTime').val(toTime);
	}
	
	if(leagueId) {		
		// create ticket
		if(league_new.length > 0) {
			$('#league_new').select2('data', {id: leagueId, name: leagueName});
			
			$('#match').removeAttr('disabled');		
			//set match default if any
			if(matchId) {
				$(document).ajaxComplete(function() {
					$('#match').val(parseInt(matchId)).trigger("liszt:updated");
				});
			}
		}
		
		// edit ticket
		if(league_edit.length > 0) {
			$("#league_edit").select2('data', {id: leagueId, name: leagueName});
			
			var match = $("#match");
			match.removeAttr('disabled');
			match.html('');
			match.append('<option value=""></option>');
			if(matchId) {
				match.append('<option value="' + parseInt(matchId) + '">' + matchName + '</option>');
				match.val(parseInt(matchId)).trigger("liszt:updated");
			}			
			$(".match-select").trigger("liszt:updated");
		}
	}
});

function open_dialog(strContent) {
    
    if (typeof strContent === "undefined" || strContent === null) { 
        strContent = ""; 
    }
    var myPos = [ $(window).width() / 2 - 150, 300 ];
    $("#mess-dialog").html(strContent).dialog({ 
        title: "Information",
        position: myPos,
        modal: true                               
    });
}

function loadMoreLeagueList()
{
    var sport_type = $("#sport").val();
    var keyword = $("#league_chzn").find('input').val();
    // clear all leagues
    $("#league").find("option").remove();        
    $(".league-select").trigger("liszt:updated");
    $("#league").append('<option value=""></option>');
    // show loading image
    $("#loading_league").show();
    $.ajax({
        type:"GET",
        url: "loadAjax.php",
        data: {
            sport_type: sport_type,
            is_ibc: true,
            keyword: keyword,
            type: "search_more_league"
        },
        dataType: "json",
        success: function(data){
            if(data.length > 0) {
                $.each(data, function(key, value){ 
                    $("#league").append('<option value="' + value.league_id + '">' + value.league_name + '</option>');                                      
                });                    
            }
            $(".league-select").trigger("liszt:updated");
        },  
        error: function(){}  
    });
    // hide loading image
    $("#loading_league").hide();    
}

function leagueFormatResult(league) {
    var markup = "<table class='league-result'><tr>";

    markup += "<td class='movie-info'><div class='movie-title'>" + league.name + "</div>";
    markup += "</td></tr></table>"
    return markup;
}

function leagueFormatSelection(league) {
    $('#league').val(league.id);
    loadMatchList();
    return league.name;
}

function loadMatchList() {
    var start_date = $('#fromDate').val() + " " + $('#fromTime').val(),
        end_date = $('#toDate').val() + " " + $('#toTime').val(),
        league_id = $('#league').val(),
        sport_type = $('#sport').val(),
        match = $("#match");
        
    match.html('');
    match.append('<option value=""></option>');    
    $(".match-select").trigger("liszt:updated");
    
    // show loading image
    $("#loading_match").show();
    $.ajax({
        type:"GET",
        url: "loadAjax.php",
        data: {
            league_id:league_id,
            sport_type:sport_type,
            sd:start_date ,
            ed:end_date,
            type:'filter_event_date_ibc'
        },
        dataType: "json",
        success: function(data){
            if(data.total > 1000) {
                open_dialog('You have too many datas, please choose a other datetime');
            } else {
                if(data.total > 0) {
                    if(data.list.length > 0) {
                        $.each(data.list, function(key, value){
                            match.append('<option value="' + value.match_id + '">' + value.match_name + '</option>');                                        
                        });
                    }
                }                    
            }
            $(".match-select").trigger("liszt:updated");
            // hide loading image
            $("#loading_match").hide();
        },  
        error: function(){
            $(".match-select").trigger("liszt:updated");
            // hide loading image
            $("#loading_match").hide();
        }  
    });          
}

function searchLeagueWhenEdit(strPlaceHolder)
{
    var strLeagueName = "";
    // select2 for editing ticket
    if($("#league_name") != undefined) {
        strLeagueName = $("#league_name").val();
    }
    
    $("#league_edit").select2({
        placeholder: strLeagueName,
        minimumInputLength: 2,
        quietMillis: 100,
        ajax: { // instead of writing the function to execute the request we use Select2's convenient helper
            url: "loadLeague.php",
            dataType: 'json',
            data: function (term, page) {
                var sportType = $('#sport').val();
                return {
                    keyword: term, // search term
                    page_limit: 10, // page size
                    page: page, // page number	
                    sportType: sportType
                };
            },
            results: function (data, page) { // parse the results into the format expected by Select2.
                var more = (page * 10) < data.total; // whether or not there are more results available
                return {
                    results: data.list, 
                    more: more
                };
            }
        },
        formatResult: leagueFormatResult, // omitted for brevity, see the source of this page
        formatSelection: leagueFormatSelection, // omitted for brevity, see the source of this page
        dropdownCssClass: "bigdrop", // apply css that makes the dropdown taller
        escapeMarkup: function (m) {
            return m;
        } // we do not want to escape markup since we are displaying html in results
    }); 
}

function searchLeague()
{
    $("#league_new").select2({
        placeholder: "All leagues",
        minimumInputLength: 2,
        quietMillis: 100,
        ajax: { // instead of writing the function to execute the request we use Select2's convenient helper
            url: "loadLeague.php",
            dataType: 'json',
            data: function (term, page) {
                var sportType = $('#sport').val();
                return {
                    keyword: term, // search term
                    page_limit: 10, // page size
                    page: page, // page number	
                    sportType: sportType
                };
            },
            results: function (data, page) { // parse the results into the format expected by Select2.
                var more = (page * 10) < data.total; // whether or not there are more results available
                return {
                    results: data.list, 
                    more: more
                };
            }
        },
        formatResult: leagueFormatResult, // omitted for brevity, see the source of this page
        formatSelection: leagueFormatSelection, // omitted for brevity, see the source of this page
        dropdownCssClass: "bigdrop", // apply css that makes the dropdown taller
        escapeMarkup: function (m) {
            return m;
        } // we do not want to escape markup since we are displaying html in results
    });
}
