//Secretaria JS

//função para inserir selects de disciplina nos forms de solicitações
function insereDisciplina() {
    var count = $('.form-control.disciplina').length;
    var tipo = $('.form-control.tipo').length;
    var total = parseInt($('#totalDisciplinas').val());
    $.ajax({
        type: 'POST',
        url: '../solicitacao/inserir-disciplina',
        dataType: "html",
        data: {count : count, tipo : tipo},
        success: function(html){
            $(html).insertBefore('.end-disc-select');
            $('#totalDisciplinas').val(total + 1);
        } 
    });
}

function excluirDisciplina() {
    $("div[class*='disciplina'").last().remove();
    var total = parseInt($('#totalDisciplinas').val());
    if(total > 1) {
        $('#totalDisciplinas').val(total - 1);
    }
}

function visualizarSolicitacao(protocolo) {
        window.location.href = '../solicitacao/visualizar/' + protocolo;
}

function visualizarTarefa(protocolo) {
        window.location.href = '../public/solicitacao/visualizar/' + protocolo;
}