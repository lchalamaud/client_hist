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

function setCommercial( table, idAffaire, trData, commercial, color ){
    var today = new Date();

    var url = '/commercial/set/';
    $.ajax({
        type: "post",
        url: url,
        beforeSend: function(){
            $('#selectComm'+idAffaire).closest('td').next().removeClass('clickAssign');
        },
        success: function(data){
            table.cell( trData, 18 ).data(commercial);
            $('#selectComm'+idAffaire).closest('tbody').append(
                '<tr><form>'+
                    '<td><select id=\'type'+ idAffaire +'\' class="typeTacheSelect">'+
                        '<option value=\'Appel\'>Appel</option>'+
                        '<option value=\'Démo\'>Démo</option>'+
                        '<option value=\'Propal\'>Propal</option>'+
                        '<option value=\'Rappel\'>Rappel</option>'+
                        '<option value=\'Sign EC\'>Signature en cours</option>'+
                        '<option value=\'Signature\'>Signature</option>'+
                        '<option value=\'Suspension\'>Suspension</option>'+
                        '<option value=\'Fin\'>Fin</option>'+
                    '</select></td>'+
                    '<td class="centerCol"><input type=\'date\' id=\'dateTache'+ idAffaire +'\' value="'+ formatDate(today) +'"></td>'+
                    '<td class="centerCol spHide newTaskTab" colspan=2></td>'+
                    '<td class="centerCol clickPlus"> <span class="plus">+</span>'+
                '</form></tr>'
                );
            var select = $('#selectComm'+idAffaire).clone();
            $(".newTaskTab").append(select).removeClass('newTaskTab');
            $('#selectComm'+idAffaire).closest('td').next().empty();
            $('#selectComm'+idAffaire).closest('td').prev().empty().append(color);

            $('#selectComm'+idAffaire).closest('td').empty().append(commercial);
        },
        error: function(){
            $('#selectComm'+idAffaire).closest('td').next().addClass('clickAssign');
        },
        data : {
            'idAffaire' : idAffaire,
            'commercial' : commercial
        }
    });
}

function getTacheCommercial(idAffaire, commercial){
    
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
                $('#selectComm'+idAffaire+' .listcontent').append('<li><span class="colorSquare blockColorSquare" style="background-color:'+item.couleur+';"></span>'+item.acronyme+'</li>');
                if( item.acronyme == commercial){
                    $('#selectComm'+idAffaire+' dt').empty().append('<span class="colorSquare blockColorSquare" style="background-color:'+item.couleur+';"></span>'+item.acronyme);
                    $('#selectComm'+idAffaire+' dt').closest('tr').prev().find('.tdColor').empty().append('<span class="colorSquare blockColorSquare" style="background-color:'+item.couleur+';"></span>')
                }
            })


            data.taches.sort(sortDateTab);
            $.each(data.taches, function(i, item) {
                $('#tacheTab'+ idAffaire).prepend(
                    '<tr>'+
                        '<td>'+ item.type +'</td>'+
                        '<td class="centerCol">'+ reorderDate(item.date) +'</td>'+
                        '<td class="centerCol tdColor"><span class="colorSquare blockColorSquare" style="background-color: '+item.couleur +';"></span></td>'+
                        '<td class="centerCol spHide">'+ item.commercial +'</td>'+
                        '<td class="centerCol spHide"><i class="far fa-times-circle" id="cross'+ item.id +'"></i></td>'+
                    '</td>'
                );
            });
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
    commercial = $('#selectComm'+idAffaire+' dt').html().split('</span>')[1];

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
            $('#selectComm'+idAffaire).closest('td').next().removeClass('clickPlus');
        },
        success: function(data){
            if($('#tacheTab'+idAffaire+' tr td').text().indexOf("Aucune tache associée à cette affaire") >= 0){
                $('#emptyLine'+idAffaire).remove();
            }
            $('#tacheTab'+idAffaire+' tbody').prepend(
                '<tr>'+
                    '<td>'+type+'</td>'+
                    '<td class="centerCol">'+date+'</td>'+
                    '<td class="centerCol tdColor"><span class="colorSquare blockColorSquare" style="background-color: '+data.couleur +';"></span></td>'+
                    '<td class="centerCol spHide">'+commercial+'</td>'+
                    '<td class="centerCol spHide"><i class="far fa-times-circle" id="cross'+ data.id +'"></i></td>'+
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
                    table.cell(trData, 16).data('Signé');
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
                    table.cell(trData, 16).data('Suspendu');
                    break;
                case 'Fin':
                    table.cell(trData, 1).data('Fin');
                    table.cell(trData, 16).data('Fin');
                    break;
                case 'Sign EC':
                    table.cell(trData, 1).data('Sign EC');
                    table.cell(trData, 16).data('Sign EC');
                    break;
                case 'Rappel':
                    if( reorderDate(date) > table.cell(trData, 17).data() ){
                        table.cell(trData, 17).data(reorderDate(date));
                    }
                    if( reorderDate(date) < todayToken[0]+'-'+todayToken[1]+'-'+todayToken[2]){
                        table.cell(trData, 1).data('Oublié');
                        table.cell(trData, 16).data('Oublié');
                    }else{
                        table.cell(trData, 1).data('En Cours');
                        table.cell(trData, 16).data('En Cours');
                    }
                    break;
                default:
                    if( reorderDate(date) < todayToken[0]+'-'+todayToken[1]+'-'+todayToken[2]){
                        table.cell(trData, 1).data('Oublié');
                        table.cell(trData, 16).data('Oublié');
                    }else{
                        table.cell(trData, 1).data('En Cours');
                        table.cell(trData, 16).data('En Cours');
                    }
                    break;
            }
            $('#selectComm'+idAffaire).closest('td').next().addClass('clickPlus');

        },
        error: function(){
            $('#debug').css('color', 'red');
            $('#selectComm'+idAffaire).closest('td').next().addClass('clickPlus');
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
                var prevDate = reorderDate($(tacheRow).next().find('.tdColor').prev().text());
                table.cell(trData, 17).data(prevDate);
                
                var type = $(tacheRow).next().find('td').first().text();
                var todayDate = new Date();
                var todayToken = formatDate(todayDate).split('-');

                switch(type){
                    case 'Signature':
                        table.cell(trData, 1).data('Signé');
                        table.cell(trData, 16).data('Signé');
                        break;
                    case 'Suspension':
                        table.cell(trData, 1).data('Suspendu');
                        table.cell(trData, 16).data('Suspendu');
                        break;
                    case 'Fin':
                        table.cell(trData, 1).data('Fin');
                        table.cell(trData, 16).data('Fin');
                        break;
                    case 'Sign EC':
                        table.cell(trData, 1).data('Sign EC');
                        table.cell(trData, 16).data('Sign EC');
                        break;
                    default:
                        if( reorderDate(prevDate) < todayToken[0]+'-'+todayToken[1]+'-'+todayToken[2]){
                            table.cell(trData, 1).data('Oublié');
                            table.cell(trData, 16).data('Oublié');
                        }else{
                            table.cell(trData, 1).data('En Cours');
                            table.cell(trData, 16).data('En Cours');
                        }
                        break;
                }
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


function isNotShown( table, id ){
    var response = true;
    idList = table.cells('.shown', 22)[0];
    $.each( idList, function( index, val ){
        if( id == table.cell(val).data() ){
            response = false;
            return false;
        }
    })
    return response;
}

function updateTable( table ){
    var url ='/affaire/get/';
    $.ajax({
        type: "GET",
        url: url,
        beforeSend: function(){

        },
        success: function(data){
            var initId = tabSelectVal(table, 22);
            table.rows(':not(.shown)').remove();

            $.each(data.affaires, function(i, item){
                if( isNotShown( table, item.id ) ){
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
                        "Debut" : item.debut,
                        "Etat" : item.etat,
                        "Rappel" : item.rappel,
                        "Commercial" : item.commercial,
                        "Commentaire" : item.commentaire,
                        "Info" : item.info,
                        "NumDossier" : item.numDossier,
                        "Id" : item.id
                    };
                    rows = table.rows.add([tmp]).nodes();
                    td = $(rows).find('td');
                    td.eq(0).addClass('centerCol');
                    td.eq(1).addClass('centerCol');
                    td.eq(5).addClass('centerCol');
                    td.eq(6).addClass('centerCol');
                    td.eq(9).addClass('centerCol');
                    td.eq(10).addClass('centerCol cialCol');
                }
            })
            table.draw();
            table.page.jumpToData( initId, 22);
        },
        error: function(){

        },
    });
}