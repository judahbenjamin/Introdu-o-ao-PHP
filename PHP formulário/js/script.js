function validarFormulario() {
    let nome = document.getElementById('txtNome').value.trim();
    let cpf = document.getElementById('txtCPF').value.trim();
    let dataNasc = document.getElementById('txtData').value.trim();
    let login = document.getElementById('txtLogin').value.trim();
    let senha1 = document.getElementById('txtSenha1').value;
    let senha2 = document.getElementById('txtSenha2').value;
    let estado = document.getElementById('listEstados').value;

    limparMensagensErro();
    let valido = true;

    if (nome === '') {
        exibirMensagemErro('txtNome', 'Por favor, preencha o nome.');
        valido = false;
    }

    if (cpf === '') {
        exibirMensagemErro('txtCPF', 'Por favor, preencha o CPF.');
        valido = false;
    } else if (!validarCPF(cpf)) {
        exibirMensagemErro('txtCPF', 'CPF inválido.');
        valido = false;
    }

    if (dataNasc === '') {
        exibirMensagemErro('txtData', 'Por favor, preencha a data de nascimento.');
        valido = false;
    } else if (!validarData(dataNasc)) {
        exibirMensagemErro('txtData', 'Data de nascimento inválida (DD/MM/AAAA).');
        valido = false;
    }

    if (estado === '') {
        exibirMensagemErro('listEstados', 'Por favor, selecione um estado.');
        valido = false;
    }

    if (login === '') {
        exibirMensagemErro('txtLogin', 'Por favor, preencha o login.');
        valido = false;
    }

    if (senha1 === '') {
        exibirMensagemErro('txtSenha1', 'Por favor, preencha a senha.');
        valido = false;
    }

    if (senha2 === '') {
        exibirMensagemErro('txtSenha2', 'Por favor, confirme a senha.');
        valido = false;
    } else if (senha1 !== senha2) {
        exibirMensagemErro('txtSenha2', 'As senhas não coincidem.');
        valido = false;
    }

    return valido;
}

function exibirMensagemErro(campoId, mensagem) {
    let campo = document.getElementById(campoId);
    let mensagemErro = document.createElement('div');
    mensagemErro.className = 'error-message';
    mensagemErro.textContent = mensagem;
    campo.parentNode.insertBefore(mensagemErro, campo.nextSibling);
}

function limparMensagensErro() {
    let mensagensErro = document.querySelectorAll('.error-message');
    mensagensErro.forEach(function(mensagem) {
        mensagem.remove();
    });
}

function validarCPF(cpf) {
    cpf = cpf.replace(/\D/g, '');
    if (cpf.length !== 11 || /^(\d)\1+$/.test(cpf)) return false;
    let soma = 0;
    for (let i = 0; i < 9; i++) soma += parseInt(cpf.charAt(i)) * (10 - i);
    let resto = 11 - (soma % 11);
    let digito1 = resto >= 10 ? 0 : resto;
    if (parseInt(cpf.charAt(9)) !== digito1) return false;
    soma = 0;
    for (let i = 0; i < 10; i++) soma += parseInt(cpf.charAt(i)) * (11 - i);
    resto = 11 - (soma % 11);
    let digito2 = resto >= 10 ? 0 : resto;
    return parseInt(cpf.charAt(10)) === digito2;
}

function validarData(data) {
    const regexData = /^(0[1-9]|[12][0-9]|3[01])\/(0[1-9]|1[012])\/(19|20)\d{2}$/;
    if (!regexData.test(data)) return false;
    const partes = data.split('/');
    const dia = parseInt(partes[0], 10);
    const mes = parseInt(partes[1], 10);
    const ano = parseInt(partes[2], 10);
    const dataObj = new Date(ano, mes - 1, dia);
    return dataObj.getDate() === dia && dataObj.getMonth() === mes - 1 && dataObj.getFullYear() === ano;
}