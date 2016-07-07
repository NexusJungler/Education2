$(function() {
    var choices = [];
    var clasSelect = $('#classe');
    var classId = clasSelect.prop('options')[0].dataset.id;
    console.log(classId);

    $('#S2').change(function () {
        var item = $(this).find('option:selected')[0];
        // [0] parce qu'on accède à une node et non à la balise html!
        // autre écriture possible: $('option:selected', this)[0];

        $.post('/loadcomp', {dscp: $('#S1').prop('selectedIndex'), cat: item.dataset.id}, function(data, status){
            var html = '<option></option>';
            $.each(JSON.parse(data), function(indice, objet){
                html += '<option data-id="' + objet.id + '">' + objet.intitule + '</option>';
            });
            $('#S3').html(html);
        })
    });
    $('#S1').change(function () {
        $.post('/loadcat', {dscp: $('#S1').prop('selectedIndex')},
            function(data, status){
                var html = '<option></option>';
                $.each(JSON.parse(data), function(indice, objet){
                    html += '<option data-id="' + objet.id + '">' + objet.intitule + '</option>';
                });
                $('#S2').html(html);
            })
    });
    $('#S3').change(function () {
        var render = $('.tableread');
        var elemId = $(this).find('option:selected')[0].dataset.id;
        /* if(choices.length == 0) {
            render.html('<h3>Edition Document</h3><table><th></th><th>DISCIPLINE</th><th>CATEGORIE</th><th>COMPETENCE</th></table>');
        } // à intégrer à la vue!   */
        if($.inArray(elemId, choices) < 0) {
            choices.push(elemId);
            var html = '<tr><td>' + (choices.length) + '</td><td>' + $('#S1').find('option:selected')[0].text + '</td><td>' + $('#S2').find('option:selected')[0].text + '</td><td>'  +
                $(this).find('option:selected')[0].text + '</td><td>X</td></tr>';
            render.append(html);
        }
    });

    // If you are creating the elements after the DOM has been created, you have to use the "on" selector to get the precise element that is dynamically created.
    $('.tableread').on('click', 'td:last-child', function() {
       var index = $(this).parent().prevAll().length;  //tester un prev('tr');
        var ItemsNumber = $('.tableread td:first-child:gt('+ (index-2) +')');
        console.log($(this).parent().prevAll());  // why index-2?   h3 et table référencés
        $.each(ItemsNumber, function(i, val) {
            $(this).html(i+index-1); // $(this) pour agir sur l'élément DOM et non pas val qui représente seulement le contenu html de l'élément!
        });
       /**************************************************************************
        *         Autre manière d'actualiser l'index des items du tableau ==>
        *         for(var i = index-2; i<choices.length; i++) {
        *           var numItem =  $('.tableread td:first-child:eq('+ i +')');
        *           numItem.html(i+1);
        *          }
        *
        *************************************************************************/
        choices.splice(index-2, 1);
        $(this).parent().remove();
    });
    $('#datapdf').click(function () {
        var params = [classId, choices.length];
        $.each(choices, function(i, cptce) {
            params.push(cptce);
        });
        $('input').val(params);
        document.selection.submit();
    });
    clasSelect.change(function () {
        classId = $(this).find('option:selected')[0].dataset.id;
        console.log(classId);
    });
});
