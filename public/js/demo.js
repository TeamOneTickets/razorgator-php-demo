function handleProductionWarning() {
    'use strict';

    var selectedEnvironment = $('#environment').val(),
        selectedMethod = $('#libraryMethod').val();

//     if (selectedEnvironment === 'production' && selectedMethod.indexOf("create") >= 0) {
//         $('#productionWarning').show();
//         $('.btn-primary').addClass('btn-danger').removeClass('btn-primary');
//     } else {
//         $('#productionWarning').hide();
//         $('.btn-danger').addClass('btn-primary').removeClass('btn-danger');
//     }

}


function changeEnvironment() {
    'use strict';

    var selectedEnvironment = $('#environment').val();
    //alert(selectedEnvironment);

    switch (selectedEnvironment) {
        case 'sandbox':
            $('.sandbox').show();
            $('.staging').hide();
            $('.production').hide();

            $('.btn-primary').text('Submit to Sandbox');
            break;

        case 'staging':
            $('.sandbox').hide();
            $('.staging').show();
            $('.production').hide();

            $('.btn-primary').text('Submit to Staging');
            break;

        case 'production':
            $('.sandbox').hide();
            $('.staging').hide();
            $('.production').show();

            $('.btn-primary').text('Submit to Production');
            break;
    }

    handleProductionWarning();
}


function toggleOptions() {
    'use strict';

    var selectedMethod = $('#libraryMethod').val(),
        $methodInput = $('#methodInput');

    if (selectedMethod.indexOf('list') >= 0 || selectedMethod.indexOf('search') >= 0) {
        $('#listParameters').show();
    } else {
        $('#listParameters').hide();
    }

    $methodInput.show();

    $methodInput.children('legend').html(selectedMethod + '() Specific Parameters');
    $methodInput.find('.control-group').hide();

    $('.' + selectedMethod).show();

    if (selectedMethod === 'acceptOrder'
     || selectedMethod === 'shipOrder'
     || selectedMethod === 'rejectOrder'
     ) {
        $('#api-demo-form').attr('method', 'POST');
    } else {
        $('#api-demo-form').attr('method', 'GET');
    }

    $('.btn-primary').removeClass('disabled');

    handleProductionWarning();

}


function checkForm() {
    'use strict';

    // Set any hidden inputs to inactive to keep them from being submitted.
    // Keeps the URL cleaner
    $('input:hidden', 'fieldset').attr('disabled', true);
    $('select:hidden', 'fieldset').attr('disabled', true);
    $('textarea:hidden', 'fieldset').attr('disabled', true);

    return true;
}


$(document).ready(function() {
    'use strict';

    changeEnvironment();

});


