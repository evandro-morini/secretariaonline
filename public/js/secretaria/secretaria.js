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
        data: {count: count, tipo: tipo},
        success: function (html) {
            $(html).insertBefore('.end-disc-select');
            $('#totalDisciplinas').val(total + 1);
        }
    });
}

function excluirDisciplina() {
    $("div[class*='disciplina'").last().remove();
    var total = parseInt($('#totalDisciplinas').val());
    if (total > 1) {
        $('#totalDisciplinas').val(total - 1);
    }
}

function visualizarSolicitacao(protocolo) {
    window.location.href = '../solicitacao/visualizar/' + protocolo;
}

function visualizarTarefa(protocolo) {
    window.location.href = '../public/solicitacao/visualizar/' + protocolo;
}

function editarUsuario(id) {
    window.location.href = '../public/adm/editar-usuario/' + id;
}

function editarPerfil(id) {
    window.location.href = '../public/adm/editar-perfil/' + id;
}

function editarCurso(id) {
    window.location.href = '../public/adm/editar-curso/' + id;
}

function editarProfessor(id) {
    window.location.href = '../public/adm/editar-professor/' + id;
}

function editarDisciplina(id) {
    window.location.href = '../public/adm/editar-disciplina/' + id;
}

function validaCPF() {
    c = $('#cpfinput').val();
    var i;
    s = c;
    var c = s.substr(0, 9);
    var dv = s.substr(9, 2);
    var d1 = 0;
    var v = false;

    switch (c) {
        case '111111111':
            alert("CPF Inválido");
            $('#cpfinput').val('');
            v = true;
            return false;
            break;
        case '222222222':
            alert("CPF Inválido");
            $('#cpfinput').val('');
            v = true;
            return false;
            break;
        case '333333333':
            alert("CPF Inválido");
            $('#cpfinput').val('');
            v = true;
            return false;
            break;
        case '444444444':
            alert("CPF Inválido");
            $('#cpfinput').val('');
            v = true;
            return false;
            break;
        case '555555555':
            alert("CPF Inválido");
            $('#cpfinput').val('');
            v = true;
            return false;
            break;
        case '666666666':
            alert("CPF Inválido");
            $('#cpfinput').val('');
            v = true;
            return false;
            break;
        case '777777777':
            alert("CPF Inválido");
            $('#cpfinput').val('');
            v = true;
            return false;
            break;
        case '888888888':
            alert("CPF Inválido");
            $('#cpfinput').val('');
            v = true;
            return false;
            break;
        case '999999999':
            alert("CPF Inválido");
            $('#cpfinput').val('');
            v = true;
            return false;
            break;
    }

    for (i = 0; i < 9; i++) {
        d1 += c.charAt(i) * (10 - i);
    }
    if (d1 == 0) {
        alert("CPF Inválido");
        $('#cpfinput').val('');
        v = true;
        return false;
    }
    d1 = 11 - (d1 % 11);
    if (d1 > 9)
        d1 = 0;
    if (dv.charAt(0) != d1) {
        alert("CPF Inválido");
        $('#cpfinput').val('');
        v = true;
        return false;
    }

    d1 *= 2;
    for (i = 0; i < 9; i++) {
        d1 += c.charAt(i) * (11 - i);
    }
    d1 = 11 - (d1 % 11);
    if (d1 > 9)
        d1 = 0;
    if (dv.charAt(1) != d1) {
        alert("CPF Inválido");
        $('#cpfinput').val('');
        v = true;
        return false;
    }

}