$(function() {
    var student = null;
    var classe = [];
    var origine = 0;
    var indice = 0;

    $('.fleche').click(print);
    $('#S2').change(function() {
        if($(this).prop('selectedIndex') != 0) {renderTable();}
    });

    $(window).load(function() {
        /************ Initialisation **************/
        //  classe.splice(0);
        student =  $.url('-1');    // $.url('path') = window.location.pathname
        var allstud=  $('li .dropdown-submenu ul li');
        $.each(allstud, function(i, elem) {
            if(elem.dataset.id == student) {
                 indice = $(this).parents('li').index();
                 indice++;
            }
        });
        var studclass = $('li .dropdown-submenu:nth-child(' + indice + ') ul li');
        $.each(studclass, function(i, elem) {
            classe.push(elem.dataset.id);
        });
        origine = classe.indexOf(student);
    });
    $('li .dropdown-submenu > a').mouseenter(function() {
        var allstudent = $(this).next().children();

    });

    $('#S1').change(function () {
        $.post('/loadcat', {dscp: $('#S1').prop('selectedIndex')},      // the prop method is used to read properties of a DOM element
            function(data, status){
                var html = '<option></option>';
                $.each(JSON.parse(data), function(indice, objet){
                    html += '<option data-id="' + objet.id + '">' + objet.intitule + '</option>';
                });
                $('#S2').html(html);
            })
    });

    function renderTable() {
        //var index = $('#S2').prop('selectedIndex');
        //var item = $('#S2').prop('options')[index];
        //var js = document.querySelector('#S2').selectedIndex.dataset.id;

        var item =  $('#S2').find('option:selected')[0];
        $.post('/loadcomp', {dscp: $('#S1').prop('selectedIndex'), cat: item.dataset.id, stud: student},
            function(data, status){
                var html  = '<div class="tableread"><h2>' + item.value + '</h2><table class="main"><tr><th>Etre capable de</th><th>Niv</th><th>Dates de validation</th></tr>';
                var validComp = [];
                var allComp = JSON.parse(data)[0];
                var result = JSON.parse(data)[1];
                $.each(result, function(i, obj){
                    validComp[obj.competence.id] = i;
                });
                $.each(allComp, function(idx, obj){
                    if(obj.id in validComp) {
                        var i = validComp[obj.id];
                        html += '<tr><td class="items">' + (idx+1) + '/ ' + obj.intitule + '</td><td class="level" data-id="'+ result[i].etat +'"></td><td  class="date"><table><tr><td>' + result[i].successone+ '</td><td>' + result[i].successtwo + '</td><td>' + result[i].successtree + '</td></tr></table></td></tr>';
                    } else {
                        html += '<tr><td class="items">' + (idx+1) + '/ ' + obj.intitule + '</td><td class="level" data-id="1"></td><td  class="date"><table><tr><td class="false">X</td><td class="false">X</td><td class="false">X</td></tr></table></td></tr>';
                    }
                });
                html += '</table></div>';
                $('section>article').html(html);

                $.each($('.level'), function(i, elem) {
                    // elem is a DOM node, without access to jQuery methods, what you need to use is $(this) or $(element)
                    switch (elem.dataset.id) {
                        case '2':
                            $(this).css('background-color', 'orange');
                            break;
                        case '3':
                            $(this).css('background-color', 'yellow');
                            break;
                        case '4':
                            $(this).css('background-color', 'green');
                            break;
                        default:
                            $(this).css('background-color', 'red');
                            break;
                    }
                });
            })
    }
    function refresh() {
        var selection = document.querySelectorAll('.fleche');
        for (var i = 0; i < selection.length; i++) {
            selection[i].addEventListener('click', print);
        }
    }
    function print(evt) {
        var numStud = classe.length;
        var arrows = $('.fleche');
        if (evt.target == arrows[1]) {
            if (origine < numStud-1) {
                origine++;
            } else {
                origine = 0;
            }
        }
        if (evt.target == arrows[0]) {
            if (origine > 0) {
                origine--;
            } else {
                origine = numStud-1;
            }
        }
        student = classe[origine];
        $.post('/loadstud', {stud: student},
            function (data, status){
                var content = JSON.parse(data);
                var classname = $('li .dropdown-submenu>a')[indice-1].innerHTML.toUpperCase();
                var html = '<h2>' + classname + '</h2>';
                html += '<div class="photo"><img src="/upload/' + content.photo + '" alt="photo indisponible"></div><div class="info"><p>Nom: <span>' + content.nom + '</span></p><p>Prénom: <span>' + content.prenom + '</span></p><p>Adresse: <span>' + content.numero + ', ' + content.voie + '</span></p><p><span>' + content.complement + '</span></p><p><span>' + content.codepost + ' ' + content.ville + '</span></p><p>Age: <span>' + content.age + '</span></p><p>Réussite: <span>' + content.succes + ' %</span></p></div><div class="fleche"></div><div class="fleche droite"></div>';
                $('article').html(html);
                refresh();     // l'idéal aurait été de ne pas effacer la section entière et donc les flèches!
                if($('#S2').prop('selectedIndex') > 0) {renderTable();}
            })
    }
});