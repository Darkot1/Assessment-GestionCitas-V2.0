<!DOCTYPE html>
<html>
<head>
    <meta charset="utf-8">
    <style>
        body { font-family: DejaVu Sans; }
        .header { text-align: center; margin-bottom: 30px; }
        .details { margin-bottom: 20px; }
        .label { font-weight: bold; }
    </style>
</head>
<body>
    <div class="header">
        <h1>Historial de Cita Médica</h1>
    </div>

    <div class="details">
        <p><span class="label">Paciente:</span> {{ $history->patient->user->name }}</p>
        <p><span class="label">Doctor:</span> Dr. {{ $history->doctor->user->name }}</p>
        <p><span class="label">Fecha:</span> {{ \Carbon\Carbon::parse($history->appointment->date)->format('d/m/Y') }}</p>
        <p><span class="label">Hora:</span> {{ \Carbon\Carbon::parse($history->appointment->time)->format('H:i') }}</p>
        <p><span class="label">Estado:</span> {{ $history->action }}</p>
    </div>

    <div class="content">
        <h3>Motivo de la consulta</h3>
        <p>{{ $history->appointment->reason ?? 'No especificado' }}</p>

        <h3>Notas médicas</h3>
        <p>{{ $history->notes ?? 'Sin notas adicionales' }}</p>
    </div>
</body>
</html>
