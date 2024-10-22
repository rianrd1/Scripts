<!DOCTYPE html>
<html lang="en">

<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Leitor de Arquivo TXT</title>
</head>

<body>
    <input type="file" id="fileInput" style="display: none;">
    <textarea id="txtContent" rows="10" cols="30" onselect="updateCharacterCount()"></textarea>
    <br>
    <button onclick="loadTxt()">Carregar</button>
    <button onclick="exportTxt()">Exportar</button>
    <span id="characterCount">Caracteres selecionados: 0</span>

    <script>
        let txtContent = document.getElementById('txtContent');
        let characterCountSpan = document.getElementById('characterCount');

        function importTxt() {
            let fileInput = document.getElementById('fileInput');
            fileInput.addEventListener('change', function () {
                let file = fileInput.files[0];
                let reader = new FileReader();
                reader.onload = function (e) {
                    let content = e.target.result;
                    txtContent.value = content;
                    updateCharacterCount();
                };
                reader.readAsText(file);
            });
            fileInput.click();
        }

        function loadTxt() {

            let lines = txtContent.value.split('\n');
            let processedLines = lines.map(line => formatLine(line)).join('\n');
            txtContent.value = processedLines;
            updateCharacterCount();
        }

        function formatLine(line) {

            line = line.normalize('NFD').replace(/[\u0300-\u036f]/g, '');
            line = line.replace(/[^a-zA-Z0-9\s]/g, '');
            line = line.toUpperCase();

            let words = line.split(/\s+/);
            for (let i = 0; i < words.length; i++) {
                let word = words[i];
                if (i !== 11) {
                    word = word.replace(/\s|[.\/-]/g, '');
                }
                words[i] = word;
            }

            let formattedLine = words[0] + ' ' + words[1] + words.slice(2).join(' ');

            while (formattedLine.length < 85) {
                formattedLine += ' ';
            }

            return formattedLine.substring(0, 85);

        }

        function exportTxt() {
            let content = txtContent.value;
            let lines = content.split('\n');
            let processedLines = lines.map(line => formatLine(line)).join('\n');
            let blob = new Blob([processedLines], { type: 'text/plain' });
            let a = document.createElement('a');
            a.href = window.URL.createObjectURL(blob);
            a.download = 'CNPJ';
            a.style.display = 'none';
            document.body.appendChild(a);
            a.click();
            document.body.removeChild(a);
        }

        function updateCharacterCount() {
            let selectedText = txtContent.value.substring(txtContent.selectionStart, txtContent.selectionEnd);
            let characterCount = selectedText.length;
            characterCountSpan.textContent = "Caracteres selecionados: " + characterCount;
        }
    </script>
</body>

</html>