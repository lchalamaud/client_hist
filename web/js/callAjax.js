

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
    
    var start = new Date().getTime();
    var url = '/tache/affaire/nom/';
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
                        '<td class="centerCol spHide">'+ item.commercial +'</td>'+
                        '<td class="centerCol tdColor">'+ item.couleur +'</td>'+
                        '<td class="centerCol"><i class="far fa-times-circle" id="cross'+ item.id +'"></i></td>'+
                    '</td>'
                );
            });
            console.log(new Date().getTime() - start);
        },
        error: function(){
            $('html').css( 'cursor' , 'default');
            $('#tacheTab'+ idAffaire).prepend(
                    '<tr><td colspan=5 style="color : red">Erreur de lecture... Fermez et réouvrez l\'affaire</td></tr>'
            )
        },
        data : {'idAffaire' : idAffaire}
    });  
}

function addTache( idAffaire, trData, table, numDossier ){

    type = $("#type"+idAffaire).val();
    date = reorderDate($("#dateTache"+idAffaire).val());
    commercial = $('#selectComm'+idAffaire).find(":selected").val();

    tache = {
        'idAffaire' : idAffaire,
        'type' : type,
        'date' : date,
        'commercial' : commercial,
        'numDossier' : numDossier,
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
                    '<td class="centerCol">'+date+'</td>'+
                    '<td class="centerCol spHide">'+commercial+'</td>'+
                    '<td class="centerCol tdColor">'+ data.couleur +'</td>'+
                    '<td class="centerCol"><i class="far fa-times-circle" id="cross'+ data.id +'"></i></td>'+
                '</tr>'
            )
            var actualTextArea = $('#infoArea' + idAffaire +' textarea').val();
            $('#infoArea' + idAffaire +' textarea').val(type+', '+date+' ('+commercial+'):\n\n'+actualTextArea);
            table.cell(trData, 20).data( type+', '+date+' ('+commercial+'):\n\n'+actualTextArea);

            var todayDate = new Date();
            var todayToken = formatDate(todayDate).split('-');

            switch(type){
                case 'Signature':
                    table.cell(trData, 1).data('Signé');
                    $('tbody .numDossier'+idAffaire)
                        .find('td')
                        .wrapInner('<div style="display: block;" />')
                        .parent()
                        .find('td > div')
                        .slideUp("fast", function(){
                            $(this).parent().parent().remove();
                    });
                    break;
                case 'Suspension':
                    table.cell(trData, 1).data('Suspendu');
                    break;
                case 'Fin':
                    table.cell(trData, 1).data('Fin');
                    break;
                case 'Sign EC':
                    table.cell(trData, 1).data('Sign EC');
                    break;
                case 'Rappel':
                    if( reorderDate(date) > table.cell(trData, 17).data() ){
                        table.cell(trData, 17).data(date);
                    }
                    if( reorderDate(date) < todayToken[0]+'-'+todayToken[1]+'-'+todayToken[2]){
                        table.cell(trData, 1).data('Oublié');
                    }else{
                        table.cell(trData, 1).data('En Cours');
                    }
                    break;
                default:
                    if( reorderDate(date) < todayToken[0]+'-'+todayToken[1]+'-'+todayToken[2]){
                        table.cell(trData, 1).data('Oublié');
                    }else{
                        table.cell(trData, 1).data('En Cours');
                    }
                    break;
            }

        },
        error: function(){
            $('#debug').css('color', 'red');
        },
        data: tache
    });
}

function delTache( idTache, tacheRow, table, trData ){

    var url = '/del/tache/';
    $.ajax({
        type: "post",
        url: url,
        beforeSend: function(){
            for(i=0; i<3; i++){
                $(tacheRow).fadeTo(400, 0.33).fadeTo(400, 1);
            }

        },
        success: function(){
            if($(tacheRow).prev().prop('nodeName') != 'TR'){
                prevDate = reorderDate($(tacheRow).next().find('.spHide').prev().text());
                table.cell(trData, 17).data(prevDate);
            }
            $(tacheRow).remove();

        },
        error: function(){

        },
        data: { 'idTache' : idTache }
    });
}

function modifAffaire( table, id ){
    
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
            updateTableVal( table, jsonAffaire );
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


function updateDbFromMailBox( table ){
    

    var url ='/mail/database/';
    $.ajax({
        type: "GET",
        url: url,
        beforeSend: function(){

        },
        success: function(data){
            $.each(data.affaires, function(i, item){
                tmp = {
                    "" : null,
                    "etat" : item.etat,
                    "Civilite" : item.civilite,
                    "Nom" : item.nom,
                    "Societe" : item.societe,
                    "Rue" : item.rue,
                    "Complement" : item.complement,
                    "CP" : item.cp,
                    "Ville" : item.ville,
                    "Mail" : item.mail,
                    "Telephone" : item.telephone,
                    "Nb_Controller" : item.nb_controller,
                    "Devi_Type" : item.devi_type,
                    "System_Type" : item.system_type,
                    "Provenance" : item.provenance,
                    "Debut" : item.debut.date.split(' ')[0],
                    "Etat" : item.etat,
                    "Rappel" : item.debut.date.split(' ')[0],
                    "Commercial" : item.commercial,
                    "Commentaire" : item.commentaire,
                    "Info" : '',
                    "NumDossier" : null,
                    "Id" : item.id
                }
                table.rows.add([tmp]);
            })
            table.draw(false);
                
        },
        error: function(){

        },
    });
}