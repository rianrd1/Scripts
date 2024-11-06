<!DOCTYPE html>
<html lang="en">

<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Leitor de Arquivo TXT</title>
    <style>
        body {
            background-color: #1e1e1e;
            color: #c5c8c6;
            font-family: 'Courier New', monospace;
            display: flex;
            justify-content: center;
            align-items: center;
            flex-direction: column;
            height: 100vh;
            margin: 0;
        }

        input[type="file"] {
            display: none;
        }

        #txtContent {
            background-color: #2d2d2d;
            color: #f8f8f2;
            border: 1px solid #555;
            padding: 10px;
            font-family: 'Courier New', monospace;
            font-size: 14px;
            resize: vertical;
            width: calc(100% - 78px); /* Largura total com 38px de margem de cada lado */
            height: 70%;
            margin: 38px;
            box-shadow: 0px 0px 10px rgba(0, 0, 0, 0.5);
        }
        
        .button-container {
            display: flex;
            gap: 10px;
            margin-top: 10px;
        }

        button {
            background-color: #4a90e2;
            color: #fff;
            border: none;
            padding: 10px 20px;
            cursor: pointer;
            font-family: 'Courier New', monospace;
            transition: background-color 0.3s;
            border-radius: 4px;
        }

        button:hover {
            background-color: #357ab9;
        }

        #characterCount {
            margin-top: 10px;
            font-size: 14px;
            color: #a9a9a9;
        }
    </style>
</head>

<body>
    <input type="file" id="fileInput">
    <textarea id="txtContent" rows="10" onselect="updateCharacterCount()"></textarea>
    <div class="button-container">
        <button onclick="loadTxt()">Carregar</button>
        <button onclick="exportTxt()">Exportar</button>
    </div>
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
            a.download = 'CNPJ.txt';
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
