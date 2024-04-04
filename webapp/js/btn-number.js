//plugin bootstrap minus and plus
//http://jsfiddle.net/laelitenetwork/puJ6G/

function cantidadresta(tipo){
  console.log('entra a la funcion btn-number con tipo: '+tipo);
                    if(tipo=='bonif'){var input = $("#cant_bonifmodal");}
                      else
                    {var input = $("#cant_prodmodal");}

    var currentVal = parseInt(input.val());

    console.log(input)
    if (!isNaN(currentVal)) {

            if(currentVal > input.attr('min')) {
                input.val(currentVal - 1).change();
            /*      objeto.cant=currentVal - 1;
                  objeto.total = objeto.valor * objeto.cant;
            */
            cambiapreciopros();
            }
            if(parseInt(input.val()) == input.attr('min')) {
                input.attr('disabled', true);
            }

    } else {
        input.val(0);
    }

}


function cantidadsuma(tipo){
  console.log('entra a la funcion btn-number');
  if(tipo=='bonif'){var input = $("#cant_bonifmodal");}
    else
  {var input = $("#cant_prodmodal");}
    var currentVal = parseInt(input.val());
    console.log(input)
    if (!isNaN(currentVal)) {
            if(currentVal < input.attr('max')) {
                input.val(currentVal + 1).change();
              /*  objeto.cant = currentVal + 1;
                objeto.total = objeto.valor * objeto.cant;
                */
              cambiapreciopros();
            }
            if(parseInt(input.val()) == input.attr('max')) {
                input.attr('disabled', true);
            }


    } else {
        input.val(0);
    }

}


$('.btn-number').click(function(e){
  console.log('entra a la funcion btn-number');
    e.preventDefault();

    fieldName = $(this).attr('data-field');
    type      = $(this).attr('data-type');
    if($(this).attr('data-tipo')=='bonif'){var input = $("#cant_bonifmodal");}
      else
    {var input = $("#cant_prodmodal");}
    var currentVal = parseInt(input.val());
    if (!isNaN(currentVal)) {
        if(type == 'minus') {
console.log('entra a la funcion btn-number resta');
            if(currentVal > input.attr('min')) {
                input.val(currentVal - 1).change();
            }
            if(parseInt(input.val()) == input.attr('min')) {
                $(this).attr('disabled', true);
            }

        } else if(type == 'plus') {
console.log('entra a la funcion btn-number suma');
            if(currentVal < input.attr('max')) {
                input.val(currentVal + 1).change();
            }
            if(parseInt(input.val()) == input.attr('max')) {
                $(this).attr('disabled', true);
            }

        }
    } else {
        input.val(0);
    }
});
$('.input-number').focusin(function(){
   $(this).data('oldValue', $(this).val());
});
$('.input-number').change(function() {

    minValue =  parseInt($(this).attr('min'));
    maxValue =  parseInt($(this).attr('max'));
    valueCurrent = parseInt($(this).val());

    name = $(this).attr('name');
    if(valueCurrent >= minValue) {
        $(".btn-number[data-type='minus'][data-field='"+name+"']").removeAttr('disabled')

    } else {
        alert('Sorry, the minimum value was reached');
        $(this).val($(this).data('oldValue'));
    }
    if(valueCurrent <= maxValue) {
        $(".btn-number[data-type='plus'][data-field='"+name+"']").removeAttr('disabled')

    } else {
        alert('Sorry, the maximum value was reached');
        $(this).val($(this).data('oldValue'));
    }


});
$(".input-number").keydown(function (e) {
        // Allow: backspace, delete, tab, escape, enter and .
        if ($.inArray(e.keyCode, [46, 8, 9, 27, 13, 190]) !== -1 ||
             // Allow: Ctrl+A
            (e.keyCode == 65 && e.ctrlKey === true) ||
             // Allow: home, end, left, right
            (e.keyCode >= 35 && e.keyCode <= 39)) {
                 // let it happen, don't do anything
                 return;
        }
        // Ensure that it is a number and stop the keypress
        if ((e.shiftKey || (e.keyCode < 48 || e.keyCode > 57)) && (e.keyCode < 96 || e.keyCode > 105)) {
            e.preventDefault();
        }
    });
