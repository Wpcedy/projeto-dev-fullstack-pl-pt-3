<!DOCTYPE html>
<html lang="en">

<head>
  <meta charset="UTF-8">
  <meta http-equiv="X-UA-Compatible" content="IE=edge">
  <meta name="viewport" content="width=device-width, initial-scale=1.0">
  <title>Buscador de Endereço</title>
</head>
<script>
  var ceps = [];
  var csvData = [
    ['id', 'logradouro', 'complemento', 'bairro', 'localidade', 'uf']
  ];

  function adicionarCep() {
    let cep = document.getElementById('cep').value;
    if (!cep || cep.length != 8) {
      alert("CEP informado está vazio ou incorreto.");
    }

    ceps.push(cep);

    document.getElementById('ceps').value = ceps.toString();
    document.getElementById('cep').value = '';
  }

  function limparCeps() {
    ceps = [];
    csvData = [
      ['id', 'logradouro', 'complemento', 'bairro', 'localidade', 'uf']
    ];

    document.getElementById('ceps').value = ceps.toString();
    document.getElementById('cep').value = '';

    let list = document.getElementById("content");
    while (list.hasChildNodes()) {
      list.removeChild(list.firstChild);
    }
  }

  async function cepsSearch() {
    let list = document.getElementById("content");
    while (list.hasChildNodes()) {
      list.removeChild(list.firstChild);
    }

    await ceps.forEach(async (cep, index) => {
      let response = await fetch("https://viacep.com.br/ws/" + cep + "/json/");
      let address = await response.json();

      let div = document.createElement('div');
      div.className = 'row';

      if (address.erro) {
        div.innerHTML = "<span>Nenhum endereço foi encontrado com esse CEP</span>";
      } else {
        csvData.push([
          (index + 1),
          address.logradouro,
          address.complemento,
          address.bairro,
          address.localidade,
          address.uf
        ]);

        div.innerHTML =
          "<span><b>Logradouro: </b>" + address.logradouro + "</span><br>" +
          "<span><b>Complemento: </b>" + address.complemento + "</span><br>" +
          "<span><b>Bairro: </b>" + address.bairro + "</span><br>" +
          "<span><b>Localidade: </b>" + address.localidade + "</span><br>" +
          "<span><b>UF: </b>" + address.uf + "</span><br>";
      }

      document.getElementById('content').appendChild(div);
    });
  }

  download = function() {
    let csvContent = csvData.map(e => e.join(",")).join("\n");
    let blob = new Blob([csvContent], {
      type: 'text/csv'
    });
    let url = window.URL.createObjectURL(blob)

    let a = document.createElement('a')
    a.setAttribute('href', url)
    a.setAttribute('download', 'download.csv');
    a.click()
  }
</script>

<body>
  <span>Liste da CEPs que serão buscados</span><br>
  <input id="ceps" type="text" disabled><br><br>
  <span>Digite o CEP (somente números)</span><br>
  <input id="cep" type="text" maxlength="8">
  <button type="button" onclick="adicionarCep()">Adicionar CEP na busca</button>
  <button type="button" onclick="cepsSearch()">Buscar</button>
  <button type="button" onclick="download()">Baixar CSV</button>
  <button type="button" onclick="limparCeps()">Limpar CEPs</button>
  <br><br>
  <div id="content"></div>
</body>

</html>