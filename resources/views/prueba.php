<!DOCTYPE html>

<html lang="es">

<head>
    <title>Tabla de Amortización</title>
    <meta charset="utf-8" />

    <style>
        @page { margin: 1.5in 1in 1in 1in; }
        html,body {font-family: "Courier New" sans-serif;}
        .header{
            width: 100%;
            text-align: center;
            position: fixed;
        }
        .footer {
            width: 100%;
            text-align: center;
            position: fixed;
        }
        .header {
            top: -1in;  height: 1in;
        }
        .footer {
            bottom: -1in;
            height: 1in;
        }
        .pagenum:before {
            content: counter(page);
        }
        .table {
            width: 100%;
            max-width: 100%;
            margin-bottom: 20px;
        }
        table {
            background-color: transparent;
        }
        table {
            border-spacing: 0;
            border-collapse: collapse;
        }
        .table > thead > tr > th {
            vertical-align: bottom;
            border-bottom: 2px solid #ddd;
            padding: 8px;
        }
        .table > tbody > tr > td {
            vertical-align: bottom;
            border-bottom: 1px solid #ddd;
            padding: 5px;
        }

        .text-center {
            text-align: center;
        }
        .text-left {
            text-align: left;
        }
        .text-right {
            text-align: right;
        }
        .text-justify {
            text-align: justify;
        }
    </style>
</head>

<body>
<div class="header">
        <table class="table">
            <thead>
            <tr>
                <th>
                    <img src="images/logotipo-antejo-png.png" style="width: 164px;height: 50px">
                </th>
                <th class="text-right">
                    <p>
                        <?php
                        date_default_timezone_set("America/New_York");
                        echo date("d/m/Y");
                        ?>
                    </p>
                </th>
            </tr>
            </thead>
        </table>
</div>
<div class="footer">
        <table class="table">
            <thead>
            <tr>
                <th>
                    <img src="images/logotipo-antejo-png.png" style="width: 164px;height: 50px">
                </th>
                <th class="text-right">
                    <br>
                    <p>Page: <span class="pagenum"></span></p>
                </th>
            </tr>
            </thead>
        </table>
</div>
<div>
    <table class="table">
        <thead>
        <tr>
            <th class="text-center" colspan="2">
                <h3>Tabla de Amortización</h3>
            </th>
        </tr>
        </thead>
    </table>
    <table class="table">
        <thead>
        <tr>
            <th class="text-center">
                Periodo
            </th>
            <th class="text-center">
                Intereses
            </th>
            <th class="text-center">
                IVA
            </th>
            <th class="text-center">
                Saldo Capital
            </th>
            <th class="text-center">
                Saldo Interes
            </th>
            <th class="text-center">
                Saldo IVA
            </th>
        </tr>
        </thead>
        <tbody>
        <?php foreach ($data as $item): ?>
            <tr>
                <td class="text-center">
                    <?=(isset($item->periodo))? $item->periodo->format('d/m/Y') :$item->period->format('d/m/Y') ?>
                </td>
                <td class="text-center">
                    <?=(isset($item->capital_balance))? $item->interest :$item->interes ?>
                </td>
                <td class="text-center">
                    <?=(isset($item->iva))? $item->iva :$item->iva ?>
                </td>
                <td class="text-center">
                    <?=(isset($item->capital_balance))? $item->capital_balance :$item->saldocapital ?>
                </td>
                <td class="text-center">
                    <?=(isset($item->interest_balance))? $item->interest_balance :$item->saldointeres ?>
                </td>
                <td class="text-center">
                    <?=(isset($item->iva_balance))? $item->iva_balance :$item->saldoiva ?>
                </td>
            </tr>
        <?php endforeach; ?>
        </tbody>
    </table>
</div>
</body>
</html>