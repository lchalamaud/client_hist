
function disable( button ){
        $(button).prop("disabled",true);
        $(button).removeClass('enable');
        $(button).addClass('disable');
}

function enable( button ){
        $(button).prop("disabled",false);
        $(button).removeClass('disable');
        $(button).addClass('enable');
}

function cleanForm(){
    $('#affaire_Civilite option').prop('selected', false);
    $('#affaire_Nom').val(null);
    $('#affaire_Societe').val(null);

    $('#affaire_Rue').val(null);
    $('#affaire_Complement').val(null);
    $('#affaire_CP').val(null);
    $('#affaire_Ville').val(null);

    $('#affaire_EMail').val(null);
    $('#affaire_Telephone').val(null);

    $('#affaire_NbController').val(null);
    $('#affaire_DevisType option').prop('selected', false);
    $('#affaire_SystemType option').prop('selected', false);
    $('#affaire_Provenance option').prop('selected', false);

    $('#affaire_Debut').val(null);
    $('#affaire_Commercial option').prop('selected', false);
    $('#affaire_Commentaire').val(null);
}

function fillForm( table ){
    $('#affaire_Civilite option').filter(function(){
        return $(this).text() == tabSelectVal(table, 2);
    }).prop('selected', true);
    $('#affaire_Nom').val(tabSelectVal(table, 3));
    $('#affaire_Societe').val(tabSelectVal(table, 4));

    $('#affaire_Rue').val(tabSelectVal(table, 5));
    $('#affaire_Complement').val(tabSelectVal(table, 6));
    $('#affaire_CP').val(tabSelectVal(table, 7));
    $('#affaire_Ville').val(tabSelectVal(table, 8));

    $('#affaire_EMail').val(tabSelectVal(table, 9));
    $('#affaire_Telephone').val(tabSelectVal(table, 10));

    $('#affaire_NbController').val(tabSelectVal(table, 11));
    $('#affaire_DevisType option').filter(function(){
        return $(this).text() == tabSelectVal(table, 12);
    }).prop('selected', true);
    $('#affaire_SystemType option').filter(function(){
        return $(this).text() == tabSelectVal(table, 13);
    }).prop('selected', true);
    $('#affaire_Provenance option').filter(function(){
        return $(this).text() == tabSelectVal(table, 14);
    }).prop('selected', true);
    $('#affaire_Debut').val(tabSelectVal(table, 15));

    $('#affaire_Commercial option').filter(function(){
        return $(this).text() == tabSelectVal(table, 16);
    }).prop('selected', true);
    $('#affaire_Commentaire').val(tabSelectVal(table, 19));
}

function getFormVal( id ){
    jsonResp = { 
                    'civilite' : $('#affaire_Civilite').val(),
                    'nom' : $('#affaire_Nom').val(),
                    'societe' : $('#affaire_Societe').val(),

                    'rue' : $('#affaire_Rue').val(),
                    'complement' : $('#affaire_Complement').val(),
                    'cp' : $('#affaire_CP').val(),
                    'ville' : $('#affaire_Ville').val(),
                    
                    'email' : $('#affaire_EMail').val(),
                    'telephone' : $('#affaire_Telephone').val(),
                    
                    'nbController' : $('#affaire_NbController').val(),
                    'devisType' : $('#affaire_DevisType').val(),
                    'systemType' : $('#affaire_SystemType').val(),
                    'provenance' : $('#affaire_Provenance').val(),
                    
                    'debut' : $('#affaire_Debut').val(),
                    'commercial' : $('#affaire_Commercial').val(),
                    'commentaire' : $('#affaire_Commentaire').val(),
                    'id' : id
                };


    return jsonResp;
}

/*  Modal  */

$(document).on('keyup', function(e) {
    if(e.which == 27){
        $('#addAffaireModal').hide();
        $('#delAffaireModal').hide();
        $('#delResponse').empty();
    }else if(e.which == 13){
        /*e.preventDefault();
        $('#modifConfirmBtn').click();*/
    }
});


$('.close').on('click', function() {
    $('#addAffaireModal').hide();
    $('#delAffaireModal').hide();
    $('#delResponse').empty();
});

window.onclick = function(event) {
    if (event.target.id == 'addAffaireModal') {
        $('#addAffaireModal').hide();
    }else if (event.target.id == 'delAffaireModal') {
        $('#delAffaireModal').hide();
        $('#delResponse').empty();
    }
}


$('#addAffaireBtn').on('click', function() {
    cleanForm();
    $('#modifConfirmBtn').hide();
    $('#affaire_Ajouter').show();
    $('#addAffaireModal').show();
    $('#addAffaireModal').removeClass('modifAffaire');
    $('#addAffaireModal').addClass('newAffaire');
});

$('#modifAffaireBtn').on('click', function() {
    $('#affaire_Ajouter').css('display' , 'none');
    $('#modifConfirmBtn').css('display', 'inline-block');
    $('#addAffaireModal').show();
    $('#addAffaireModal').removeClass('newAffaire');
    $('#addAffaireModal').addClass('modifAffaire');
});

$('#delAffaireBtn').on('click', function() {
    $('#delAffaireModal').show();
});