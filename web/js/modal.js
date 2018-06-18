
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
    $('#form_Civilite option').prop('selected', false);
    $('#form_Nom').val(null);
    $('#form_Societe').val(null);

    $('#form_Rue').val(null);
    $('#form_Complement').val(null);
    $('#form_CP').val(null);
    $('#form_Ville').val(null);

    $('#form_EMail').val(null);
    $('#form_Telephone').val(null);

    $('#form_NbController').val(null);
    $('#form_DevisType option').prop('selected', false);
    $('#form_SystemType option').prop('selected', false);
    $('#form_Provenance option').prop('selected', false);

    $('#form_Debut').val(null);
    $('#form_Commercial option').prop('selected', false);
    $('#form_Commentaire').val(null);
}

function fillForm( table ){
    $('#form_Civilite option').filter(function(){
        return $(this).text() == tabSelectVal(table, 2);
    }).prop('selected', true);
    $('#form_Nom').val(tabSelectVal(table, 3));
    $('#form_Societe').val(tabSelectVal(table, 4));

    $('#form_Rue').val(tabSelectVal(table, 5));
    $('#form_Complement').val(tabSelectVal(table, 6));
    $('#form_CP').val(tabSelectVal(table, 7));
    $('#form_Ville').val(tabSelectVal(table, 8));

    $('#form_EMail').val(tabSelectVal(table, 9));
    $('#form_Telephone').val(tabSelectVal(table, 10));

    $('#form_NbController').val(tabSelectVal(table, 11));
    $('#form_DevisType option').filter(function(){
        return $(this).text() == tabSelectVal(table, 12);
    }).prop('selected', true);
    $('#form_SystemType option').filter(function(){
        return $(this).text() == tabSelectVal(table, 13);
    }).prop('selected', true);
    $('#form_Provenance option').filter(function(){
        return $(this).text() == tabSelectVal(table, 14);
    }).prop('selected', true);
    $('#form_Debut').val(tabSelectVal(table, 15));

    $('#form_Commercial option').filter(function(){
        return $(this).text() == tabSelectVal(table, 16);
    }).prop('selected', true);
    $('#form_Commentaire').val(tabSelectVal(table, 19));
}

function getFormVal( id ){
    jsonResp = { 
                    'civilite' : $('#form_Civilite').val(),
                    'nom' : $('#form_Nom').val(),
                    'societe' : $('#form_Societe').val(),

                    'rue' : $('#form_Rue').val(),
                    'complement' : $('#form_Complement').val(),
                    'cp' : $('#form_CP').val(),
                    'ville' : $('#form_Ville').val(),
                    
                    'email' : $('#form_EMail').val(),
                    'telephone' : $('#form_Telephone').val(),
                    
                    'nbController' : $('#form_NbController').val(),
                    'devisType' : $('#form_DevisType').val(),
                    'systemType' : $('#form_SystemType').val(),
                    'provenance' : $('#form_Provenance').val(),
                    
                    'debut' : $('#form_Debut').val(),
                    'commercial' : $('#form_Commercial').val(),
                    'commentaire' : $('#form_Commentaire').val(),
                    'id' : id
                };


    return jsonResp;
}

/*  Modal  */
var addaffaireModal = document.getElementById('addAffaireModal');
var delModal = document.getElementById('delAffaireModal');

$(document).on('keyup', function(e) {
    if(e.which == 27){
        addaffaireModal.style.display = "none";
        delModal.style.display = "none";
        $('#delResponse').empty();
    }else if(e.which == 13){
        /*e.preventDefault();
        $('#modifConfirmBtn').click();*/
    }
});


$('.close').on('click', function() {
    addaffaireModal.style.display = "none";
    delModal.style.display = "none";
    $('#delResponse').empty();
});

window.onclick = function(event) {
    if (event.target == addaffaireModal) {
        addaffaireModal.style.display = "none";
    }else if (event.target == delModal) {
        delModal.style.display = "none";
        $('#delResponse').empty();
    }
}


$('#addAffaireBtn').on('click', function() {
    cleanForm();
    $('#modifConfirmBtn').css('display' , 'none');
    $('#form_Ajouter').css('display', 'inline-block');
    addaffaireModal.style.display = "block";
    $('#addAffaireModal').removeClass('modifAffaire');
    $('#addAffaireModal').addClass('newAffaire');
});

$('#modifAffaireBtn').on('click', function() {
    $('#form_Ajouter').css('display' , 'none');
    $('#modifConfirmBtn').css('display', 'inline-block');
    addaffaireModal.style.display = "block";
    $('#addAffaireModal').removeClass('newAffaire');
    $('#addAffaireModal').addClass('modifAffaire');
});

$('#delAffaireBtn').on('click', function() {
    delModal.style.display = "block";
});