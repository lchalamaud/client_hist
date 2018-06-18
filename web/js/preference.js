function initConfig(){
	var config = $('#config').text().split('-');
	timeStepPref = config[0];
	commercialPref = config[1];
    etatPref = config[2];
	
}

function setTimeStep( timeStep ){
	
	if($('#twoCloseWeek').is(":checked")){
        $('#twoCloseWeek').prop('checked', false);
    }
    if($('#onMonth').is(":checked")){
        $('#onMonth').prop('checked', false);
    }
    if($('#twoCloseWeek').is(":checked")){
        $('#twoCloseWeek').prop('checked', false);
    }
    if($('#nextMonth').is(":checked")){
        $('#nextMonth').prop('checked', false);
    }

	var today = new Date();
    var todayDateToken = formatDate(today).split('-');

    if(timeStep == 0) {
        min = '';
        max = '';
    }else if(timeStep == 1) {
    	var weekDate = new Date( todayDateToken[0], todayDateToken[1]-1, todayDateToken[2]);
	    var dayOfWeek = weekDate.getDay();
	    weekDate.setDate(weekDate.getDate()-dayOfWeek-6);
	    min = formatDate(weekDate);
	    weekDate.setDate(weekDate.getDate()+13);
	    max = formatDate(weekDate);
        $('#twoCloseWeek').prop('checked', true);
    }else if(timeStep == 2) {
        var minDate = new Date( todayDateToken[0], todayDateToken[1]-1, 1);
        min = formatDate(minDate);
        var maxDate = new Date( todayDateToken[0], todayDateToken[1], 0);
        max = formatDate(maxDate);
        $('#onMonth').prop('checked', true);
    }else if(timeStep == 3) {
        var minDate = new Date( todayDateToken[0], todayDateToken[1], 1);
        min = formatDate(minDate);
        var maxDate = new Date( todayDateToken[0], parseInt(todayDateToken[1])+1, 0);
        max = formatDate(maxDate);
        $('#nextMonth').prop('checked', true);
    }

    return { 'min' : min, 'max' : max }
}

function getTimeConf(){
	if( $('#twoCloseWeek').prop('checked') ){
		return 1;
	}else if( $('#onMonth').prop('checked') ){
		return 2;
	}else if( $('#nextMonth').prop('checked') ){
		return 3;
	}else{
		return 0;
	}

}

/*
$('#nextMonth').on('click', function(){

    if($(this).is(":checked")){

        timePref = setTimeStep( 3 );
        min = timePref.min;
        max = timePref.max;

    }else{
        min = '';
        max = '';
    }
    table.draw();
    setConfig(getTimeConf(), $('#commercialSelect select option:selected').val());
});*/