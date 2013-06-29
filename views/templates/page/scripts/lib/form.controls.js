

//
function buildText(selector, name){
    $(selector).append(createInput(name, 'text', ''));
}

//
function buildParagraph(selector, name){
    $(selector).append(createText(name));
}

//
function buildCheckbox(selector, name, values){
    //ul
    var ul = document.createElement('ul');
    ul.setAttribute('data-field-name', name);
    $(selector).append(ul);

    for (index in values){
        var li = document.createElement('li');
        field = values[index];
        $(li).append(createInput(name, 'checkbox', field.value)).append(field.label);
        $(ul).append(li);
    }

}

//
function buildRadio(selector, name, values){
    //ul
    var ul = document.createElement('ul');
    ul.setAttribute('data-field-name', name);
    $(selector).append(ul);
    for (index in values){
        var li = document.createElement('li');
        field = values[index];
        $(li).append(createInput(name, 'radio', field.value)).append(field.label);
        $(ul).append(li);
    }

}

//
function buildSelect(selector, name, values){

    var select = createSelect(name);
    $(selector).append(select);
    // build options for select
    for (index in values){
        field = values[index];
        $(select).append(createOption(field.label, field.value));
    }

}

function formValidation(errors, evt, formId, formName) {

    $('#error').html(''); // clear last errors
    if (errors.length > 0) {
        var errorString = '';

        for (var i = 0, errorLength = errors.length; i < errorLength; i++) {
            errorString += errors[i].message + '<br />';
        }
        console.log(errorString);
        $('#error').html(errorString);
    }
    else{
        formProcess(formId, '#'+formName);
        // CLEAR FORM?
        $('#'+formName).each (function(){
            this.reset();
        });
    }

}

function formProcess(formId, formObj){

    var data = JSON.stringify({"meta":$(formObj).serializeObject(),
                              "event_id":formId,
                              "task_id":0,
                              "user":"test@local",
                              "lon":0,
                              "lat":0});
    //console.log(data);

    $.ajax({
        type: "POST",
        url: "http://api.openreport.local/event/"+formId,
        data: data,
        success: function(data){
            console.log(data);
            // DISPLAY RESULTS???
        }
    });

    return false;

}

// low level libs
// input type text
function createInput(name, type, value){
    var field = document.createElement('input');
    field.setAttribute('name',name);
    field.setAttribute('id',name);
    field.setAttribute('value',value);
    field.setAttribute('type',type);
    return field;
}
// textarea (paragraph)
function createText(name){
    var field = document.createElement('textarea');
    field.setAttribute('name',name);
    field.setAttribute('id',name);
    return field;
}

function createSelect(name){
    var field = document.createElement('select');
    field.setAttribute('id',name);
    field.setAttribute('name',name);
    return field;
}
//
function createOption(label, value){
    var option=document.createElement("option");
    option.text = label;
    option.value = value;
    return option;
}
//
function createLabel(title){
    var label = document.createElement('label');
    $(label).append(title);
    return label;
}
