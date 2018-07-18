function initConfig(){
	var config = $('#config').text().split('-');
	timeStepPref = config[0];
	commercialPref = config[1];
    enCours = config[2];
    oublie = config[3];
    suspendu = config[4];
    fin = config[5];
    signe = config[6];
    signEC = config[7];
}

function setInitStateConfig(){
    etatFilter = '';
    if( enCours ){
        $('#cbEnCours').prop( "checked", true );
        etatFilter = 'En Cours';
    }
    if( oublie ){
        $('#cbOublie').prop( "checked", true );
        if(etatFilter == ''){
            etatFilter += 'Oublié';
        }else{
            etatFilter += '|Oublié';
        }
    }
    if( suspendu ){
        $('#cbSuspendu').prop( "checked", true );
        if(etatFilter == ''){
            etatFilter += 'Suspendu';
        }else{
            etatFilter += '|Suspendu';
        }
    }
    if( fin ){
        $('#cbFin').prop( "checked", true );
        if(etatFilter == ''){
            etatFilter += 'Fin';
        }else{
            etatFilter += '|Fin';
        }
    }
    if( signe ){
        $('#cbSigne').prop( "checked", true );
        if(etatFilter == ''){
            etatFilter += 'Signé';
        }else{
            etatFilter += '|Signé';
        }
    }
    if( signEC ){
        $('#cbSignEC').prop( "checked", true );
        if(etatFilter == ''){
            etatFilter += 'Sign EC';
        }else{
            etatFilter += '|Sign EC';
        }
    }
}

function setTimeStep( timeStep ){

	var today = new Date();
    var todayDateToken = formatDate(today).split('-');
    var dayOfWeek = today.getDay();
   switch( timeStep ){
        case 'prevDay':
            today.setDate(today.getDate()-1);
            min = formatDate(today);
            max = min;
            break;
        case 'onDay':
            min = formatDate(today);
            max = min;
            break;
        case 'nextDay':
            today.setDate(today.getDate()+1);
            min = formatDate(today);
            max = min;
            break;
        case 'prevWeek':
            today.setDate(today.getDate()-dayOfWeek-6);
            min = formatDate(today);
            today.setDate(today.getDate()+6);
            max = formatDate(today);
            break;
        case 'onWeek':
            today.setDate(today.getDate()-dayOfWeek+1);
            min = formatDate(today);
            today.setDate(today.getDate()+6);
            max = formatDate(today);
            break;
        case 'nextWeek':
            today.setDate(today.getDate()+8-dayOfWeek);
            min = formatDate(today);
            today.setDate(today.getDate()+6);
            max = formatDate(today);
            break;
        case 'prevMonth':
            var minDate = new Date( todayDateToken[0], todayDateToken[1]-2, 1);
            min = formatDate(minDate);
            var maxDate = new Date( todayDateToken[0], todayDateToken[1]-1, 0);
            max = formatDate(maxDate);
            break;
        case 'onMonth':
            var minDate = new Date( todayDateToken[0], todayDateToken[1]-1, 1);
            min = formatDate(minDate);
            var maxDate = new Date( todayDateToken[0], todayDateToken[1], 0);
            max = formatDate(maxDate);
            break;
        case 'nextMonth':
            var minDate = new Date( todayDateToken[0], todayDateToken[1], 1);
            min = formatDate(minDate);
            var maxDate = new Date( todayDateToken[0], parseInt(todayDateToken[1])+1, 0);
            max = formatDate(maxDate);
            break;
        default :
            min = '';
            max = '';
            break;
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
        'signEC': $('#cbSignEC').is(":checked"),
    };
    dataSend = JSON.stringify(prefConfig);
    $.ajax({
        type: "post",
        url: url,
        success: function(data){
        },
        error: function(){
        },
        data: dataSend,
        dataType: 'json',
        contentType: 'application/json'
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