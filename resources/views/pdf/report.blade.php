@php use Carbon\Carbon; @endphp
    <!DOCTYPE html>
<html lang="es">

<head>
    <meta charset="UTF-8">
    <title>Reporte de Tareas</title>
    <style>
        body {
            font-family: Arial, sans-serif;
            margin: 20px auto;
            max-width: 750px;
            background-color: #fff;
            color: #212529;
        }

        .header {
            text-align: center;
            padding: 20px;
            background-color: #fff;
            border: 2px solid #000000;
            margin-bottom: 30px;
            position: relative;
            overflow: hidden;
        }

        .header h1 {
            margin: 0;
            font-size: 26px;
        }

        .header h2 {
            margin: 5px 0 0;
            font-size: 18px;
            font-weight: normal;
        }

        .info-container {
            width: 100%;
            margin-top: 20px;
            border-spacing: 10px;
        }

        .info-container td {
            vertical-align: top;
        }

        .info-section table {
            border-collapse: collapse;
            width: 100%;
        }

        .info-section td {
            padding: 6px 10px;
            border: 1px solid #ccc;
        }

        .info-section .dark {
            background-color: #003366;
            color: white;
            font-weight: bold;
        }

        .report-title {
            text-align: center;
            font-size: 22px;
            font-weight: bold;
            margin-top: 30px;
            padding: 10px;
            border: 2px solid #000000;
            color: #003366;
            background-color: #e9ecef;
        }

        table.main-table {
            width: 100%;
            border-collapse: collapse;
            margin-top: 30px;
            border: 2px solid #000000;
        }

        .main-table th,
        .main-table td {
            border: 1px solid #000000;
            padding: 10px;
            text-align: center;
        }

        .main-table th {
            background-color: #003366;
            color: white;
        }

        .main-table .table-title th {
            background-color: #aba8a8;
            color: #000000;
        }

        .total {
            font-size: 18px;
            text-align: center;
            margin-top: 30px;
        }

        .total span {
            border: 1px solid #003366;
            padding: 6px 12px;
            background-color: #fff;
            color: #000000;
        }
    </style>
</head>

<body>
<div class="header">
    <h1>SOLUCIONES INFORMÁTICAS</h1>
    <h2>SOFTWARE</h2>

    <table class="info-container">
        <tr>
            <td>
                <div class="info-section">
                    <table>
                        <tr>
                            <td class="dark">DESDE FECHA</td>
                            <td>{{ $search_data['start_date'] }}</td>
                        </tr>
                        <tr>
                            <td class="dark">HASTA FECHA</td>
                            <td>{{ $search_data['end_date'] }}</td>
                        </tr>
                    </table>
                </div>
            </td>
            <td>
                <div class="info-section">
                    <table>
                        <tr>
                            <td class="dark">PROYECTO ID</td>
                            <td>{{ $search_data['project_id'] }}</td>
                        </tr>
                        <tr>
                            <td class="dark">USUARIO</td>
                            <td>{{ $search_data['user_name'] }}</td>
                        </tr>
                    </table>
                </div>
            </td>
        </tr>
    </table>
</div>

<div class="report-title">INFORME DE TAREAS REALIZADAS</div>

<table class="main-table">
    <thead>
    <tr class="table-title">
        <th colspan="6">PROYECTO</th>
    </tr>
    <tr>
        <th>ID</th>
        <th>INICIO</th>
        <th>FIN</th>
        <th>MINUTOS</th>
        <th>USUARIO</th>
        <th>TAREA REALIZADA</th>
    </tr>
    </thead>
    <tbody>
    @php $totalMinutes = 0; @endphp
    @foreach ($tasks as $task)
        @php
            $from = Carbon::parse($task->date_from);
            $to = Carbon::parse($task->date_to);
            $minutes = $from->diffInMinutes($to);
            $totalMinutes += $minutes;
        @endphp
        <tr>
            <td>{{ $task->id }}</td>
            <td>{{ $from->format('d/m/Y H:i') }}</td>
            <td>{{ $to->format('d/m/Y H:i') }}</td>
            <td>{{ $minutes }}</td>
            <td>{{ $task->user->name }}</td>
            <td>{{ $task->info }}</td>
        </tr>
    @endforeach
    </tbody>
</table>

<div class="total">
    TOTAL MINUTOS: <span>{{ $totalMinutes }}</span>
</div>
</body>

</html>
