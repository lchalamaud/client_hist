function initConfig(){
	var config = $('#config').text().split('-');
	timeStepPref = config[0];
	commercialPref = config[1];
    enCours = config[2];
    oublie = config[3];
    suspendu = config[4];
    fin = config[5];
    signe = config[6];
}

function setInitStateConfig(){
    etatFilter = '';
    if( enCours=='true' ){
        $('#cbEnCours').prop( "checked", true );
        etatFilter = 'En Cours';
    }
    if( oublie=='true' ){
        $('#cbOublie').prop( "checked", true );
        if(etatFilter == ''){
            etatFilter += 'Oublié';
        }else{
            etatFilter += '|Oublié';
        }
    }
    if( suspendu=='true' ){
        $('#cbSuspendu').prop( "checked", true );
        if(etatFilter == ''){
            etatFilter += 'Suspendu';
        }else{
            etatFilter += '|Suspendu';
        }
    }
    if( fin=='true' ){
        $('#cbFin').prop( "checked", true );
        if(etatFilter == ''){
            etatFilter += 'Fin';
        }else{
            etatFilter += '|Fin';
        }
    }
    if( signe=='true' ){
        $('#cbSigne').prop( "checked", true );
        if(etatFilter == ''){
            etatFilter += 'Signé';
        }else{
            etatFilter += '|Signé';
        }
    }

}

function setTimeStep( timeStep ){

	var today = new Date();
    var todayDateToken = formatDate(today).split('-');

    if(timeStep == '') {
        min = '';
        max = '';
        $('#timeSelect option').prop('checked', true);
    }else if(timeStep == 'twoCloseWeek') {
    	var weekDate = new Date( todayDateToken[0], todayDateToken[1]-1, todayDateToken[2]);
	    var dayOfWeek = weekDate.getDay();
	    weekDate.setDate(weekDate.getDate()-dayOfWeek-6);
	    min = formatDate(weekDate);
	    weekDate.setDate(weekDate.getDate()+13);
	    max = formatDate(weekDate);
    }else if(timeStep == 'onMonth') {
        var minDate = new Date( todayDateToken[0], todayDateToken[1]-1, 1);
        min = formatDate(minDate);
        var maxDate = new Date( todayDateToken[0], todayDateToken[1], 0);
        max = formatDate(maxDate);
    }else if(timeStep == 'nextMonth') {
        var minDate = new Date( todayDateToken[0], todayDateToken[1], 1);
        min = formatDate(minDate);
        var maxDate = new Date( todayDateToken[0], parseInt(todayDateToken[1])+1, 0);
        max = formatDate(maxDate);
    }
    $("#timeSelect option").filter(function() {
        return $(this).val() == timeStep; 
    }).prop('selected', true);

    return { 'min' : min, 'max' : max }
}


function setConfig(){
    var url = '/pref/save/';

    prefConfig  = {
        'timeStep' : $('#timeSelect option:selected').val(),
        'commercial': $('#commercialSelect select option:selected').val(),
        'enCours': $('#cbEnCours').is(":checked"),
        'oublie': $('#cbOublie').is(":checked"),
        'suspendu': $('#cbSuspendu').is(":checked"),
        'fin': $('#cbFin').is(":checked"),
        'signe': $('#cbSigne').is(":checked"),
    }
    $.ajax({
        type: "post",
        url: url,
        success: function(){
        },
        error: function(){
        },
        data: prefConfig
    });

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