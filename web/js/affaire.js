var idleTime = 0;

var commercialPref = 'Tous';
var timeStepPref = '';
var etatPref = '';
var enCours = true;
var oublie = true;
var suspendu = false;
var fin = false;
var signe = false;
var signEC = false;
var etatFilter = 'En Cours|Oublié';

jQuery.fn.extend({
    isCheckSetFilter: function($state){
        if($(this).is(":checked")){
            if (etatFilter != false){
                etatFilter += '|';
            }else{
                etatFilter = ''
            }
            etatFilter += $state;
        }else{
            etatFilter = delstrpart(etatFilter, $state);
        }
        setConfig();
    }
});

function tabSelectVal(table, index){
    return table.cell('.selected', index).data();
}

function tacheFormat ( id, debut, commercial ) {
    var today = new Date();
    if(commercial){
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
                    '<td class="spHide tdColor"></td>'+
                    '<td class="spHide centerCol">'+ commercial +'</td>'+
                    '<td></td>'+
                '</tr>'+
                '<tr><form>'+
                    '<td><select id=\'type'+ id +'\' class="typeTacheSelect">'+
                        '<option value=\'Appel\'>Appel</option>'+
                        '<option value=\'Démo\'>Démo</option>'+
                        '<option value=\'Propal\'>Propal</option>'+
                        '<option value=\'Rappel\'>Rappel</option>'+
                        '<option value=\'Sign EC\'>Signature en cours</option>'+
                        '<option value=\'Signature\'>Signature</option>'+
                        '<option value=\'Suspension\'>Suspension</option>'+
                        '<option value=\'Fin\'>Fin</option>'+
                    '</select></td>'+
                    '<td class="centerCol"><input type=\'date\' id=\'dateTache'+ id +'\' value="'+ formatDate(today) +'"></td>'+
                    '<td class="centerCol spHide" colspan=2><dl class="dropdown" id=\'selectComm'+ id +'\'>'+
                        '<dt>Cial<span class="fas fa-caret-down"></span></dt>'+
                        '<dd>'+
                            '<ul class="listcontent">'+
                            '</ul>'+
                        '</dd>'+
                    '</dl></td>'+
                    '<td class="centerCol clickPlus"> <span class="plus">+</span>'+
                '</form></tr>'+
            '</tbody>'+
        '</table>';
    }else{
        return '<table class="tacheTabClass" id="tacheTab'+ id +'">'+
            '<thead>'+ 
                '<tr>'+
                    '<th>Type</th>'+
                    '<th>Date</th>'+
                    '<th class="spHide" colspan=2>Cial</th>'+
                    '<th></th>'+
                '</tr>'+
            '</thead>'+
            '<tbody>'+
                '<tr>'+
                    '<td>Début</td>'+
                    '<td class="centerCol">'+ reorderDate(debut) +'</td>'+
                    '<td>Assign:</td>'+
                    '<td class="centerCol spHide"><dl class="dropdown" id=\'selectComm'+ id +'\'>'+
                        '<dt>Cial<span class="fas fa-caret-down"></span></dt>'+
                        '<dd>'+
                            '<ul class="listcontent">'+
                            '</ul>'+
                        '</dd>'+
                    '</dl></td>'+
                    '<td class="centerCol clickAssign"><span class="plus">+</span></td>'+
                '</tr>'+
            '</tbody>'+
        '</table>';
    }
    
}

function infoFormat ( table ) {

    return '<table class="infoTab spHide">'+
            '<tr>'+
                '<td>'+(table.Civilite?table.Civilite:'')+'</td>'+
            '</tr>'+
            '<tr>'+
                '<td>'+(table.Rue?table.Rue:'')+'<br/>'+
                       (table.Complement?table.Complement:'')+'<br/>'+
                       (table.CP?table.CP:'')+' '+(table.Ville?table.Ville:'')+'</td>'+
            '</tr>'+
            '<tr>'+
                '<td>'+table.Mail+'</td>'+
            '</tr>'+
            '<tr>'+
                '<td>'+(table.Provenance?table.Provenance:'')+'</td>'+
            '</tr>'+
            '<tr>'+
            	'<td>'+(table.Commentaire?table.Commentaire:'')+'</td>'+
            '</tr>'+
            '<tr>'+
                '<td>N° Dossier : '+(table.NumDossier?table.NumDossier:'')+'</td>'+
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
    table.cell(selector, 20).data(info);
}


function delstrpart ( str, part ){
    var index = str.indexOf(part);
    if(index == 0){index++}
    var rsp = str.substring(0, (index - 1)) + str.substring( index + part.length );
    if( rsp.length ){
        return rsp;
    }
    return false;
};

jQuery.fn.dataTable.Api.register( 'page.jumpToData()', function ( data, column ) {
    var pos = this.column(column, {order:'current'}).data().indexOf( data );

    if ( pos >= 0 ) {
        var page = Math.floor( pos / this.page.info().length );
        this.page( page ).draw( false );
    }

    return this;
} );



$(document).ready( function () {

    initConfig();
    
    disable("#delAffaireBtn");
    disable("#modifAffaireBtn");



    $.fn.dataTable.ext.search.push( function( settings, data, dataIndex ) {
        var date = reorderDate(data[17]);
        var minReorder = min?reorderDate(min):'';
        var maxReorder = max?reorderDate(max):'';

        if ( ( minReorder == '' && maxReorder == '' ) ||
             ( minReorder == '' && date <= maxReorder ) ||
             ( minReorder <= date && maxReorder == '' ) ||
             ( minReorder <= date && date <= maxReorder ) ){
            return true;
        }
        return false;
    });

    jQuery.extend(jQuery.fn.dataTableExt.oSort, {
        
        "date-eu-pre": function ( date ) {
            date = date.replace(" ", "");
            if ( ! date ) {return 0;}
            var year;
            var eu_date = date.split(/[\.\-\/]/);
            if ( eu_date[2] ) {year = eu_date[2];}
            else {year = 0;}
            var month = eu_date[1];
            if ( month.length == 1 ) {month = 0+month;}
            var day = eu_date[0];
            if ( day.length == 1 ) {day = 0+day;}

            return (year + month + day) * 1;
        },
     
        "date-eu-asc": function ( a, b ) {
            return ((a < b) ? -1 : ((a > b) ? 1 : 0));
        },
     
        "date-eu-desc": function ( a, b ) {
            return ((a < b) ? 1 : ((a > b) ? -1 : 0));
        }
    });


    /*  Table des affaires  */
	var table = $('.client_Tab').DataTable({
		"language": {
		    "sProcessing":     "Traitement en cours...",
		    "sSearch":         "Rechercher&nbsp;:",
		    "sLengthMenu":     "Afficher _MENU_ &eacute;l&eacute;ments",
		    "sInfo":           "_START_ - _END_ (pour _TOTAL_ affaires)",
		    "sInfoEmpty":      "0 (pour 0 affaire)",
		    "sInfoFiltered":   "",
		    "sInfoPostFix":    "",
		    "sLoadingRecords": "Chargement en cours...",
		    "sZeroRecords":    "Oups... Il semblerait qu'il n'y ait pas d'affaire.",
		    "sEmptyTable":     "Oups... Il semblerait qu'il n'y ait pas d'affaire.",
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
                            img = '<img src="/images/blue.jpg" class="colorSquare">'
                            break;
                        case 'Oublié':
                            img = '<img src="/images/yellow.jpg" class="colorSquare">'
                            break;
                        case 'Signé':
                            img = '<img src="/images/green.jpg" class="colorSquare">'
                            break;
                        case 'Sign EC':
                            img = '<img src="/images/green_2.jpg" class="colorSquare">'
                            break;
                        case 'Suspendu':
                            img = '<img src="/images/orange.jpg" class="colorSquare">'
                            break;
                        case 'Fin':
                            img = '<img src="/images/red.jpg" class="colorSquare">'
                            break;
                    }

                    return img;
                    
                },                
            },
            
            { "data": "Civilite", "visible": false },         //2
			{ "data": "Nom" },                                //3
			{ "data": "Societe" },                            //4
            { "data": "Rue", "visible": false },              //5
            { "data": "Complement", "visible": false },       //6
            { "data": "CP", "visible": false },               //7
            { "data": "Ville" },                              //8
            { "data": "Mail", "visible": false },             //9
            { "data": "Telephone" },                          //10
            { "data": "Nb_Controller" },                      //11
            { "data": "Devi_Type" },                          //12
            { "data": "System_Type" },                        //13
            { "data": "Provenance", "visible": false },       //14
            { "data": "Debut", "visible": false },            //15
            { "data": "Etat", "visible": false },             //16
            { 
                "data": "Rappel",
                "width": "10%",
                "className": "rappelCol",
                "render": function ( data, type, row, meta ){
                    return reorderDate(data);
                },
                "type": "date-eu"
            },                                                //17
            { "data": "Commercial", "width": "9%" },          //18
            { "data": "Commentaire", "visible": false },      //19
            { "data": "Info", "visible": false },             //20
            { "data": "NumDossier", "visible": false },       //21
            { "data": "Id", "visible": false },               //22
        ],

        "pageLength": Math.trunc((window.innerHeight-$('#header').height()-100)/47),
        "order": [[ 17, "desc" ]],
        "dom": 'tpr<"top"i>',
        "autoWidth": false,
        "initComplete": function () {
            this.api().columns(18).every( function () {
                var column = this;
                var select = $('<select class="selector"><option value="Tous">Tous</option></select>')
                    .appendTo( "#commercialSelect" ).on( 'change', function () {
                        var val = $.fn.dataTable.util.escapeRegex($(this).val());
                        
                        if(val === ''){
                            column.search( '^$', true, false ).draw();
                        }else if(val === 'Tous'){
                            column.search( '', true, false ).draw();
                        }else{
                            column.search( val ? '^'+val+'$' : '', true, false ).draw();
                        }
                        
                    });
 
                column.data().unique().sort().each( function ( d, j ) {
                    select.append( '<option value="'+d.slice(84)+'">'+d.slice(84)+'</option>' );
                });
                var initComm = commercialPref;

                select.val( initComm );
                column.search( initComm == 'Tous' ? '' : (!initComm ? '^$' : initComm), true, false ).draw();

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
        modifAffaire( table , tabSelectVal(table,22));
    });

    $('#delAffaireBtn').on('click', function() {
        $('#delModalNom').empty().prepend(table.cell('.selected' ,3).data());
        $('#delModalSociete').empty().prepend(table.cell('.selected', 4).data());
    });
    $('#delConfirmBtn').on('click', function(){
        delAffaire(table.cell('.selected' ,22).data(), table);
    });


    $('.stateCheckbox').click(function(){
        switch ($(this).attr('id')){
            case 'cbEnCours':
                $(this).isCheckSetFilter('En Cours');
                break;
            case 'cbOublie':
                $(this).isCheckSetFilter('Oublié');
                break;
            case 'cbFin':
                $(this).isCheckSetFilter('Fin');
                break;
            case 'cbSuspendu':
                $(this).isCheckSetFilter('Suspendu');
                break;
            case 'cbSignEC':
                $(this).isCheckSetFilter('Sign EC');
                break;
            case 'cbSigne':
                $(this).isCheckSetFilter('Signé');
                break;
        }
        table.column( 16 ).search(etatFilter, true, false).draw();
    })


    $('#timeSelect').change(function(){
        timePref = setTimeStep($(this).val());
        min = timePref.min;
        max = timePref.max;

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

        var idAffaire = table.cell(this, 22).data();
        var debut = table.cell(this, 15).data();
        var commercial = table.cell(this, 18).data().slice(84);

        if ( row.child.isShown() ) {
            $('div.slider', row.child()).slideUp( function () {
                row.child().removeClass('infoLine');
                row.child.hide();
                tr.removeClass('shown');
            })
            tdi.first().removeClass('fa-minus');
            tdi.first().addClass('fa-plus');
            $('#infoArea'+idAffaire).remove();
            table.draw();
        }
        else {
            row.child( '<div class=\'slider\'>'+tacheFormat( idAffaire, debut, commercial ) + infoFormat(row.data()) + '<div class="infoText" id="infoArea'+ idAffaire +'"><textarea>'+(table.cell(this, 20).data()?table.cell(this, 20).data():'')+'</textarea></div>' + '</div>' ).show();
            getTacheCommercial(idAffaire, commercial);
            row.child().addClass('infoLine');
            tr.addClass('shown');
            tdi.first().removeClass('fa-plus');
            tdi.first().addClass('fa-minus');
            $('div.slider', row.child()).slideDown();
            var textareaWidth = $('#infoArea'+ idAffaire).width();

            $('#infoArea'+idAffaire).css({
                right: - (textareaWidth + 10) + 'px',
            })
        }   
    });



    /*  Gestionnaire de tache   */

    $(document).on("change", ".typeTacheSelect", function(){
        var idAffaire = event.target.id.slice(4);
        if($(event.target).find(":selected").val() == 'Signature')
        {
            $("<tr class='numDossier"+idAffaire+"'>"+
                    "<td>N° Dossier :</td><td class='centerCol'>"+
                    "<input type='number' class='numDossierInput'>"+
                    "</td><td colspan=3></td>"+
                "</tr>")
                .appendTo($(event.target.parentElement.parentElement.parentElement));
            $('tbody .numDossier'+idAffaire)
                .find('td')
                .wrapInner('<div style="display: none;" />')
                .parent()
                .find('td > div')
                .slideDown("fast", function(){
                    var $set = $(this);
                    $set.replaceWith($set.contents());
            });  
            $(event.target.parentElement).addClass('Signature');
        }else if($(event.target.parentElement).hasClass('Signature'))
        {
            $(event.target.parentElement).removeClass('Signature')
            $('tbody .numDossier'+idAffaire)
                .find('td')
                .wrapInner('<div style="display: block;" />')
                .parent()
                .find('td > div')
                .slideUp("fast", function(){
                    $(this).parent().parent().remove();
            });
        }
    })

    $(document).on( "click", ".clickAssign", function(){
        var trData = $(this).closest('tr.infoLine').prev();
        var idAffaire =  table.cell(trData, 22).data();
        var commercial = $('#selectComm'+idAffaire+' dt').html().split('</span>');
        if(commercial){
            setCommercial( table, idAffaire, trData, commercial[1], commercial[0]+'</span>' );
        }else{
            $('#selectComm'+idAffaire).fadeTo(400, 0.33).fadeTo(400, 1).fadeTo(400, 0.33).fadeTo(400, 1);
            alert('Veuillez rentrer le commercial à assigner à l\'affaire.');
        }
    });

    $(document).on( "click", ".clickPlus", function(){
        var trData = $(this).closest('tr.infoLine').prev();
        var idAffaire =  table.cell(trData, 22).data();
        if($('#dateTache'+idAffaire).val()){
            if($('#type'+idAffaire+' option:selected').val() == 'Signature'){
                numDossier = $(event.target.parentElement.parentElement).next().find('.numDossierInput').val();
                if(numDossier){
                    addTache( idAffaire, trData, table, numDossier );
                }else{
                    alert('Veuillez rentrer le numéros de dossier');
                }
            }else{
                addTache( idAffaire, trData, table, null );
            }
            
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

    $(document).on('change', "div.infoText textarea", function(event){
        var tr = $(this).closest('tr');
        var row = table.row( tr );
        var trData = $(this).closest('tr.infoLine').prev();
        var idAffaire = event.target.parentElement.id.slice(8);

        setAffaireInfo(idAffaire, trData, table, $('#infoArea'+idAffaire+' textarea').val());
    });
    
    /*      Auto Update: 5 minute d'inactivité      */
    setInterval(function(){updateTable( table );}, 300000); //300 000ms = 5 minutes

    $(document).keydown(function(event){
        if( $('#delAffaireModal').is(":hidden") &&
            $('#addAffaireModal').is(":hidden") &&
            !$('#searchbox').is(':focus') &&
            !$('textarea').is(':focus')
        ){
            if (event.which == 39){
                $('#DataTables_Table_0_next').click();
            }else if (event.which == 37){
                $('#DataTables_Table_0_previous').click();
            }
        }
    })
});