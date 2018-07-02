var commercialPref = 'Tous';
var timeStepPref = '';
var etatPref = '';
var enCours = true;
var oublie = true;
var suspendu = false;
var fin = false;
var signe = false;
var etatFilter = 'En Cours|Oublié';

var delay = (function(){
    var timer = 0;
    return function(callback, ms){
        clearTimeout (timer);
        timer = setTimeout(callback, ms);
    };
})();

function tabSelectVal(table, index){
    return table.cell('.selected', index).data();
}

function tacheFormat ( id, debut ) {
    return '<table class="tacheTabClass" id="tacheTab'+ id +'">'+
            '<thead>'+ 
                '<tr>'+
                    '<th>Type</th>'+
                    '<th>Date</th>'+
                    '<th class="spHide" colspan=2>Commercial</th>'+
                    '<th></th>'+
                '</tr>'+
            '</thead>'+
            '<tbody>'+
                '<tr>'+
                    '<td>Début</td>'+
                    '<td class="centerCol">'+ reorderDate(debut) +'</td>'+
                    '<td class="spHide"></td>'+
                    '<td></td>'+
                    '<td></td>'+
                '</tr>'+
                '<tr><form>'+
                    '<td><select id=\'type'+ id +'\'>'+
                        '<option value=\'Appel\'>Appel</option>'+
                        '<option value=\'Démo\'>Démo</option>'+
                        '<option value=\'Rappel\'>Rappel</option>'+
                        '<option value=\'Propal\'>Propal</option>'+
                        '<option value=\'Tel\'>Tel</option>'+
                        '<option value=\'Signature\'>Signature</option>'+
                        '<option value=\'Suspension\'>Suspension</option>'+
                        '<option value=\'Fin\'>Fin</option>'+
                    '</select></td>'+
                    '<td class="centerCol"><input type=\'date\' id=\'dateTache'+ id +'\'></td>'+
                    '<td class="centerCol spHide" colspan=2><select id=\'selectComm'+ id +'\'>'+
                    '</select></td>'+
                    '<td class="centerCol clickPlus"> <span class="plus">+</span>'+
                '</form></tr>'+
            '</tbody>'+
        '</table>';
}

function infoFormat ( table ) {

    return '<table class="infoTab spHide">'+
            '<tr>'+
                '<td>'+table.Civilite+'</td>'+
            '</tr>'+
            '<tr>'+
                '<td>'+table.Rue+'<br/>'+
                       table.Complement+'<br/>'+
                       table.CP+' '+table.Ville+'</td>'+
            '</tr>'+
            '<tr>'+
                '<td>'+table.Mail+'</td>'+
            '</tr>'+
            '<tr>'+
                '<td>'+table.Provenance+'</td>'+
            '</tr>'+
            '<tr>'+
                '<td>'+table.System_Type+'</td>'+
            '</tr>'+
            '<tr>'+
            	'<td>'+table.Commentaire+'</td>'+
            '</tr>'+
        '</table>';
}

function reorderDate( date ){
    dateToken = date.split('-');
    return dateToken[2]+'-'+dateToken[1]+'-'+dateToken[0];
}
function sortDateTab(tache1, tache2){
    if (tache1.date === tache2.date) {
        return 0;
    }
    else {
        return (tache1.date < tache2.date) ? -1 : 1;
    }
}

function formatDate( date ){
    var month = date.getMonth()+1;
    var day = date.getDate();
    return date.getFullYear()+'-'+(month<10?'0':'')+month+'-'+(day<10?'0':'')+day;
}

function setTableInfo( selector, table, info ){
    table.cell(selector, 20).data(info)
}


function delstrpart ( str, part ){
    var index = str.indexOf(part);
    if(index == 0){index++}
    return str.substring(0, (index - 1)) + str.substring( index + part.length );
};


$(document).ready( function () {

    initConfig();
    
    disable("#delAffaireBtn");
    disable("#modifAffaireBtn");

    $.fn.dataTable.ext.search.push( function( settings, data, dataIndex ) {
        var date = data[17];
        if ( ( min == '' && max == '' ) ||
             ( min == '' && date <= max ) ||
             ( min <= date   && max == '' ) ||
             ( min <= date   && date <= max ) ){
            return true;
        }
        return false;
    });

    /*  Table des affaires  */
	var table = $('.client_Tab').DataTable({
		"language": {
		    "sProcessing":     "Traitement en cours...",
		    "sSearch":         "Rechercher&nbsp;:",
		    "sLengthMenu":     "Afficher _MENU_ &eacute;l&eacute;ments",
		    "sInfo":           "Affichage de l'&eacute;l&eacute;ment _START_ &agrave; _END_ sur _TOTAL_ &eacute;l&eacute;ments",
		    "sInfoEmpty":      "Affichage de l'&eacute;l&eacute;ment 0 &agrave; 0 sur 0 &eacute;l&eacute;ment",
		    "sInfoFiltered":   "(filtr&eacute; de _MAX_ &eacute;l&eacute;ments au total)",
		    "sInfoPostFix":    "",
		    "sLoadingRecords": "Chargement en cours...",
		    "sZeroRecords":    "Oups... Il semblerait qu'il n'y ai pas d'affaire.",
		    "sEmptyTable":     "Aucune donn&eacute;e disponible dans le tableau",
		    "oPaginate": {
		        "sFirst":      "Premier",
		        "sPrevious":   "<<",
		        "sNext":       ">>",
		        "sLast":       "Dernier"
		    },
		    "oAria": {
		        "sSortAscending":  ": activer pour trier la colonne par ordre croissant",
		        "sSortDescending": ": activer pour trier la colonne par ordre d&eacute;croissant"
		    }
		},
		"columns": [
            {
                "className":      'details-control',
                "orderable":      false,
                "data": null,
                "defaultContent": '',
                "render": function (){
                    return '<i class="fa fa-plus" aria-hidden="true"></i>';
                },
                width:"15px"
            },
            { 
                "data":      'etat',
                "orderable":      false,
                "render": function ( data, type, row, meta ){
                    switch (data){
                        case 'En Cours':
                            img = '<img src="/img/blue.jpg">'
                            break;
                        case 'Oublié':
                            img = '<img src="/img/maroon.jpg">'
                            break;
                        case 'Signé':
                            img = '<img src="/img/green.jpg">'
                            break;
                        case 'Suspendu':
                            img = '<img src="/img/orange.jpg">'
                            break;
                        case 'Fin':
                            img = '<img src="/img/red.jpg">'
                            break;
                    }

                    return img;
                    
                },                
            },
            
            { "data": "Civilite" },         //2
			{ "data": "Nom" },              //3
			{ "data": "Societe" },          //4
            { "data": "Rue" },              //5
            { "data": "Complement" },       //6
            { "data": "CP" },               //7
            { "data": "Ville" },            //8
            { "data": "Mail" },             //9
            { "data": "Telephone" },        //10
            { "data": "Nb_Controller" },    //11
            { "data": "Devi_Type" },        //12
            { "data": "System_Type" },      //13
            { "data": "Provenance" },       //14
            { "data": "Debut" ,},            //15
            { "data": "Etat" },             //16
            { "data": "Rappel" },            //17
            { "data": "Commercial" },       //18
            { "data": "Commentaire" },      //19
            { "data": "Info" },             //20
            { "data": "Id" },               //21
        ],
        "order": [[17, 'desc']],
        "pageLength": Math.trunc((window.innerHeight-$('#header').height()-100)/40),
        "dom": 'tpr',
        "autoWidth": false,
        "initComplete": function () {
            this.api().columns(18).every( function () {
                var column = this;
                var select = $('<select class="selector"><option value="">Tous</option></select>')
                    .appendTo( "#commercialSelect" ).on( 'change', function () {
                        var val = $.fn.dataTable.util.escapeRegex($(this).val());
                        
                        column.search( val ? '^'+val+'$' : '', true, false ).draw();
                    });
 
                column.data().unique().sort().each( function ( d, j ) {
                    select.append( '<option value="'+d+'">'+d+'</option>' );
                });
                var initComm = commercialPref;

                select.val( initComm );
                column.search( initComm ).draw();

            });
        },
	});   

    

    $('#waiter').remove();
    $('#affaireDetail').show();

    /*   Date   */

    var min = '';
    var max = '';

    timePref = setTimeStep( timeStepPref );
    min = timePref.min;
    max = timePref.max;
    setInitStateConfig();
    table.column( 16 ).search(etatFilter, true, false);
    table.draw();


    /*  Header  */


    $('#modifAffaireBtn').on('click', function() {
        fillForm( table );
    });
    $('#modifConfirmBtn').on('click', function(){
        modifAffaire(table.cell('.selected' ,21).data());
    });

    $('#delAffaireBtn').on('click', function() {
        $('#delModalNom').empty().prepend(table.cell('.selected' ,3).data());
        $('#delModalSociete').empty().prepend(table.cell('.selected', 4).data());
    });
    $('#delConfirmBtn').on('click', function(){
        delAffaire(table.cell('.selected' ,21).data(), table);
    });

    $('#cbEnCours').click(function(){
        if($(this).is(":checked")){
            if (etatFilter != ''){
                etatFilter += '|';
            }
            etatFilter += 'En Cours';
        }else{
            etatFilter = delstrpart(etatFilter, 'En Cours');
        }
        table.column( 16 ).search(etatFilter, true, false).draw();
        setConfig();
    })
    $('#cbOublie').click(function(){
        if($(this).is(":checked")){
            if (etatFilter != ''){
                etatFilter += '|';
            }
            etatFilter += 'Oublié';
        }else{
            etatFilter = delstrpart(etatFilter, 'Oublié');
        }
        table.column( 16 ).search(etatFilter, true, false).draw();
        setConfig();
    })
    $('#cbFin').click(function(){
        if($(this).is(":checked")){
            if (etatFilter != ''){
                etatFilter += '|';
            }
            etatFilter += 'Fin';
        }else{
            etatFilter = delstrpart(etatFilter, 'Fin');
        }
        table.column( 16 ).search(etatFilter, true, false).draw();
        setConfig();
    })
    $('#cbSuspendu').click(function(){
        if($(this).is(":checked")){
            if (etatFilter != ''){
                etatFilter += '|';
            }
            etatFilter += 'Suspendu';
        }else{
            etatFilter = delstrpart(etatFilter, 'Suspendu');
        }
        table.column( 16 ).search(etatFilter, true, false).draw();
        setConfig();
    })
    $('#cbSigne').click(function(){
        if($(this).is(":checked")){
            if (etatFilter != ''){
                etatFilter += '|';
            }
            etatFilter += 'Signé';
        }else{
            etatFilter = delstrpart(etatFilter, 'Signé');
        }
        table.column( 16 ).search(etatFilter, true, false).draw();
        setConfig();
    })

    $('#timeSelect').change(function(){
        switch ($(this).val()){
            case 'twoCloseWeek':
                timePref = setTimeStep( 'twoCloseWeek' );
                min = timePref.min;
                max = timePref.max;
                break;
            case 'onMonth':
                timePref = setTimeStep( 'onMonth' );
                min = timePref.min;
                max = timePref.max;
                break;
            case 'nextMonth':
                timePref = setTimeStep( 'nextMonth' );
                min = timePref.min;
                max = timePref.max;
                break;
            default:
                min = '';
                max = '';
                break;
        }
        table.draw();
        setConfig();
    });

    $('#searchbox').keyup(function(){
        table.search($(this).val()).draw() ;
    })
    $('#commercialSelect select, #etatSelect select').change( function() {
        setConfig();
    });



    /*  Select Row  */

    $(document).on( 'click', '.client_Tab tbody tr', function () {
        if ( $(this).hasClass('selected') ) {
            disable("#delAffaireBtn");
            disable("#modifAffaireBtn");
            $(this).removeClass('selected');
        }
        else if(!$(this).parents('.infoLine').length && !$(this).hasClass('infoLine')) {
            table.$('tr.selected').removeClass('selected');
            enable("#delAffaireBtn");
            enable("#modifAffaireBtn");
            $(this).addClass('selected');
        }
    } );


    /*      Open Detail Row     */

	$('.client_Tab tbody').on('click', 'td.details-control', function () {
        var tr = $(this).closest('tr');
        var tdi = tr.find("i.fa");
        var row = table.row( tr );

        var idAffaire = table.cell(this, 21).data();
        var debut = table.cell(this, 15).data();

        if ( row.child.isShown() ) {
            $('div.slider', row.child()).slideUp( function () {
                row.child().removeClass('infoLine');
                row.child.hide();
                tr.removeClass('shown');
            })
            tdi.first().removeClass('fa-minus');
            tdi.first().addClass('fa-plus');
            $('#infoArea'+idAffaire).remove();
        }
        else {
            row.child( '<div class=\'slider\'>'+tacheFormat( idAffaire, debut ) + infoFormat(row.data()) + '<div class="infoText" id="infoArea'+ idAffaire +'"><textarea>'+table.cell(this, 20).data()+'</textarea></div>' + '</div>' ).show();
            getTacheCommercial(idAffaire, table.cell(this, 18).data());
            row.child().addClass('infoLine');
            tr.addClass('shown');
            tdi.first().removeClass('fa-plus');
            tdi.first().addClass('fa-minus');
            $('div.slider', row.child()).slideDown();
            var textareaWidth = $('#infoArea'+ idAffaire).width();
            //tr.nextUntil('.'+tr.attr('class').split(' ')[0])
            //var infolineHeight = tr.nextUntil('.'+tr.attr('class').split(' ')[0]).height();

            $('#infoArea'+idAffaire).css({
                right: - (textareaWidth + 10) + 'px',
            })
        }   
    });



    /*  Gestionnaire de tache   */

    $(document).on( "click", ".clickPlus", function(){
        var trData = $(this).closest('tr.infoLine').prev();
        var idAffaire =  table.cell(trData, 21).data();
        if($('#dateTache'+idAffaire).val()){
            addTache( idAffaire, trData, table );
        }else{
            alert('Veuillez rentrer une date');
        }
    });

    $('.client_Tab').on( "click", ".fa-times-circle", function(){
        var idTache = event.target.id.slice(5);
        var tacheRow = event.target.parentElement.parentElement;
        var trData = $(this).closest('tr.infoLine').prev();

        delTache( idTache, tacheRow, table, trData );
    })



    $('#testBtn').on('click', function(){
        /*var i=0;
        var today = new Date();
        var todayDateToken = formatDate(today).split('-');
        table.rows().every(function(){
            var comSelect = $('#commercialSelect select option:selected').val();
            var etat = table.cell(this, 16).data();
            if( table.cell(this, 18).data() == comSelect || comSelect == ''){
                if ( etat == 'En Cours' || etat == 'Oublié'){
                    if( table.cell(this, 17).data() < (todayDateToken[0]+'-'+todayDateToken[1]+'-'+todayDateToken[2])){
                        alert('L\'affaire '+table.cell(this, 3).data() + ' - ' + table.cell(this, 4).data() + ' nécessite une opération.')
                        i++;
                    }
                }
            }
        })

        if( i == 0 ){
            alert('Pas de Rappel aujourd\'hui');
        }*/

        console.log(table.rows().count())

    });

    $(document).on('change', "div.infoText textarea", function(event){
        var tr = $(this).closest('tr');
        var row = table.row( tr );
        var trData = $(this).closest('tr.infoLine').prev();
        var idAffaire = event.target.parentElement.id.slice(8);

        setAffaireInfo(idAffaire, trData, table, $('#infoArea'+idAffaire+' textarea').val());
    });

});