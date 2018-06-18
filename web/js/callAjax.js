
function setConfig(timeStep, commercial, etat){
    var url = '/pref/save/';
    prefConfig  = {
        'timeStep' : timeStep,
        'commercial': commercial,
        'etat': etat,
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

function delAffaire(idAffaire, table){
    var url = '/del/affaire/'+idAffaire+'/';
    $.ajax({
        type: "post",
        url: url,
        beforeSend: function(){
            $('#delConfirmBtn').prop("disabled",true);
            $('html').css( 'cursor' , 'wait');
            $('#delResponse').empty().append('<p style="color: grey;margin-left:10px;">Suppression en cours...</p>');
        },
        success: function(){
            $('html').css( 'cursor' , 'default');
            $('#delResponse').empty();
            document.getElementById('delAffaireModal').style.display = "none";
            table.row('.selected').remove().draw();
            disable("#delAffaireBtn");
            disable("#modifAffaireBtn");
            $('#delConfirmBtn').prop("disabled",false);
        },
        error: function(){
            $('#delResponse').empty().append('<p style="color: red;margin-left:10px;">Erreur dans la suppression de l affaire.</p>');
            $('html').css( 'cursor' , 'default');
            $('#delConfirmBtn').prop("disabled",false);
        }
    });
}

function getTacheCommercial(idAffaire, commercial){
    
    var url = '/tache/affaire/nom/'+idAffaire+'/';
    $.ajax({
        type: "get",
        url: url,
        beforeSend: function(){
            $('html').css( 'cursor' , 'wait');

        },
        success: function(data){
            $('html').css( 'cursor' , 'default');

            $.each(data.commercial, function(i, item){
                if( item.acronyme == commercial){
                    $('#selectComm'+idAffaire).append('<option value="' + item.acronyme + '" selected>' + item.acronyme + '</option>');
                }else{
                    $('#selectComm'+idAffaire).append('<option value="' + item.acronyme + '">' + item.acronyme + '</option>');
                }
            })

            data.taches.sort(sortDateTab);
            $.each(data.taches, function(i, item) {
                $('#tacheTab'+ idAffaire).prepend(
                    '<tr>'+
                        '<td>'+ item.type +'</td>'+
                        '<td class="centerCol">'+ reorderDate(item.date) +'</td>'+
                        '<td class="centerCol">'+ item.commercial +'</td>'+
                        '<td class="centerCol tdColor">'+ item.couleur +'</td>'+
                    '</td>'
                )
            });

        },
        error: function(){
            $('html').css( 'cursor' , 'default');
            $('#tacheTab'+ idAffaire).prepend(
                    '<tr><td colspan=4 style="color : red">Erreur de lecture... Fermez et réouvrez l\'affaire</td></tr>'
            )
        }
    });  
}

function addTache( idAffaire ){

    type = $("#type"+idAffaire).val();
    date = $("#dateTache"+idAffaire).val();
    commercial = $('#selectComm'+idAffaire).find(":selected").val();

    tache = {
        'idAffaire' : idAffaire,
        'type' : type,
        'date' : date,
        'commercial' : commercial,
    };

    var url = '/add/tache/';
    $.ajax({
        type: "post",
        url: url,
        beforeSend: function(){

        },
        success: function(data){
            if($('#tacheTab'+idAffaire+' tr td').text().indexOf("Aucune tache associée à cette affaire") >= 0){
                $('#emptyLine'+idAffaire).remove();
            }
            $('#tacheTab'+idAffaire+' tbody').prepend(
                '<tr>'+
                    '<td>'+type+'</td>'+
                    '<td class="centerCol">'+reorderDate(date)+'</td>'+
                    '<td class="centerCol">'+commercial+'</td>'+
                    '<td class="centerCol tdColor">'+ data.couleur +'</td>'+
                '</tr>'
            )
            var actualTextArea = $('#infoArea' + idAffaire +' textarea').val();
            $('#infoArea' + idAffaire +' textarea').val(type+', '+reorderDate(date)+' ('+commercial+'):\n\n'+actualTextArea);

            switch(type){
                case 'Signature':
                    debug('Waow it is signed');
                    break;
                case 'Suspension':
                    debug('Wait and see');
                    break;
                case 'Fin':
                    debug('This is the end');
                    break;
                default:
                    debug('Still in progress');
                    break;
            }

        },
        error: function(){
            $('#debug').css('color', 'red');
            debug('Erreur dans l\'envoie de la tache');
        },
        data: tache
    });
}

function modifAffaireBtn( id ){
    
    jsonAffaire = getFormVal( id );
    
    var url = '/modif/affaire/';
    $.ajax({
        type: "POST",
        url: url,
        dataType: "json",
        beforeSend: function(){
            $('html').css( 'cursor' , 'wait');
        },
        success: function(data){
            $('html').css( 'cursor' , 'default');
            $('#addAffaireModal').css('display','none');
        },
        error: function(){
            $('html').css( 'cursor' , 'default');
            $('#debug').css( 'color' , 'red');
            debug('Une erreur est survenue lors de l\'envoie des données. Les paramètres précedent ont été conservé.')
        },
        data: jsonAffaire
    });

}

function setAffaireInfo( idAffaire, selector, table, info ){
    
    JsonInfo ={
        'idAffaire' : idAffaire,
        'info' : info,
    };

    var url ='/set/info/';
    $.ajax({
        type: "POST",
        url: url,
        dataType: "json",
        beforeSend: function(){
            $('html').css( 'cursor' , 'wait');
        },
        success: function(data){
            $('html').css( 'cursor' , 'default');
            setTableInfo(selector, table, info);
        },
        error: function(){
            $('html').css( 'cursor' , 'not-allowed');
            delay(function(){
                $('html').css( 'cursor' , 'default');
            }, 2000);
        },
        data: JsonInfo
    });
}