function printThermal(widthMM, widthPx, heightPx) {
    let isi = document.getElementById('thermal-print').innerHTML;

    let win = window.open('', '', `width=${widthPx},height=${heightPx}`);

    win.document.write(`
        <html>
        <head>
            <title>Laporan Shift</title>
            <style>
                @page {
                    margin: 0;
                }
                body {
                    font-family: 'Courier New', Courier, monospace;
                    width: ${widthMM}mm;
                    margin: 0 auto;
                    padding: 4px;
                    font-size: ${widthMM === 58 ? '11px' : '13px'};
                    color: #000;
                    line-height: 1.2;
                }
                .center {
                    text-align: center;
                }
                .right {
                    text-align: right;
                }
                /* Mengatur layout label dan nominal agar sejajar rapi */
                .row {
                    display: flex;
                    justify-content: space-between;
                }
                hr {
                    border: none;
                    border-top: 1px dashed #000;
                    margin: 4px 0;
                }
            </style>
        </head>
        <body>
            ${isi}
        </body>
        </html>
    `);

    win.document.close();

    setTimeout(() => {
        win.print();
    }, 300);
}

function print58() { printThermal(58, 300, 600); }
function print80() { printThermal(80, 500, 700); }