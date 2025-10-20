<!DOCTYPE html>
<html lang="pt-BR">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Comparativo Meta vs Resultado - {{ $curso->nome }}</title>
    <style>
        body {
            font-family: Arial, sans-serif;
            margin: 20px;
            color: #333;
        }
        .header {
            text-align: center;
            margin-bottom: 30px;
            border-bottom: 2px solid #007bff;
            padding-bottom: 20px;
        }
        .header h1 {
            color: #007bff;
            margin: 0;
            font-size: 24px;
        }
        .header p {
            margin: 5px 0;
            font-size: 16px;
        }
        table {
            width: 100%;
            border-collapse: collapse;
            margin-bottom: 20px;
        }
        th, td {
            border: 1px solid #ddd;
            padding: 10px;
            text-align: center;
            font-size: 14px;
        }
        th {
            background-color: #f8f9fa;
            font-weight: bold;
        }
        .bg-info {
            background-color: #d1ecf1 !important;
        }
        .bg-primary {
            background-color: #cce5ff !important;
        }
        .bg-success-light {
            background-color: #d4edda !important;
        }
        .bg-warning {
            background-color: #fff3cd !important;
            color: #856404 !important;
        }
        .bg-success {
            background-color: #d4edda !important;
            color: #155724 !important;
        }
        .bg-secondary {
            background-color: #e2e3e5 !important;
            color: #383d41 !important;
        }
        .legend {
            margin-top: 30px;
            padding: 15px;
            background-color: #f8f9fa;
            border-radius: 5px;
        }
        .legend h5 {
            margin-bottom: 10px;
            color: #495057;
        }
        .legend-item {
            display: inline-block;
            margin-right: 20px;
            margin-bottom: 10px;
        }
        .badge {
            display: inline-block;
            padding: 4px 8px;
            border-radius: 4px;
            font-size: 12px;
            font-weight: bold;
        }
        .text-center {
            text-align: center;
        }
        .font-weight-bold {
            font-weight: bold;
        }
    </style>
</head>
<body>
    @php
        // Verificar se a função já existe para evitar redeclaração
        if (!function_exists('getColorClass')) {
            function getColorClass($valorResultado, $valorMeta, $isInverse = false) {
                if ($valorResultado === null || $valorMeta === null) {
                    return 'bg-secondary text-white'; // Sem dados
                }

                if ($isInverse) {
                    // Para infrequência (menor é melhor)
                    return $valorResultado <= $valorMeta ? 'bg-success text-white' : 'bg-warning text-dark';
                } else {
                    // Para outros valores (maior é melhor)
                    return $valorResultado >= $valorMeta ? 'bg-success text-white' : 'bg-warning text-dark';
                }
            }
        }
    @endphp

    <!-- Cabeçalho -->
    <table style="width: 100%; border: none; margin-bottom: 20px;">
        <tr>
            <td style="width: 20%; text-align: left;">
                <img src="{{ public_path('assets/img/logo_eep.png') }}" alt="Logo EEP" style="max-width: 80px;">
            </td>
            <td style="width: 60%; text-align: center;">
                <h2 style="margin: 0; color: #228a33;">E.E.E.P. DR. JOSÉ ALVES DA SILVEIRA</h2>
                <h3 style="margin: 5px 0; color: #333;">{{ $curso->nome }}</h3>
                <p style="margin: 0; font-size: 14px;">Comparativo Meta vs Resultado - {{ $curso->ano }}ª ano</p>
            </td>
            <td style="width: 20%; text-align: right;">
                <!-- Espaço para outros elementos se necessário -->
            </td>
        </tr>
    </table>

    <table>
        <thead>
            <tr>
                <th rowspan="2" class="bg-info">Período</th>
                <th rowspan="2" class="bg-info">Tipo</th>
                <th rowspan="2" class="bg-info">Alunos</th>
                <th rowspan="2" class="bg-info">Média Geral</th>
                <th colspan="2" class="bg-primary">Frequência</th>
                <th colspan="6" class="bg-success">Aprovações</th>
                <th rowspan="2" class="bg-warning">IDE-SALA</th>
            </tr>
            <tr>
                <th class="bg-primary">Infrequência (%)</th>
                <th class="bg-primary">Frequência</th>
                <th class="bg-success-light">LP</th>
                <th class="bg-success-light">% PT</th>
                <th class="bg-success-light">MT</th>
                <th class="bg-success-light">% MAT</th>
                <th class="bg-success-light">Geral</th>
                <th class="bg-success-light">% Geral</th>
            </tr>
        </thead>
        <tbody>
            @for($i = 1; $i <= 4; $i++)
            @php
                $meta = $metas[$i];
                $resultado = $dadosPeriodos[$i] ?? null;
            @endphp
            <tr>
                <td rowspan="2" class="bg-info">{{ $i }}º</td>
                <td class="bg-info font-weight-bold">Meta</td>
                <td>{{ $meta->alunos ?? 0 }}</td>
                <td>{{ number_format($meta->media_geral ?? 0, 2) }}</td>
                <td>{{ number_format($meta->infrequencia ?? 0, 2) }}</td>
                <td>{{ number_format($meta->frequencia ?? 0, 2) }}</td>
                <td>{{ $meta->aprovacao_lp ?? 0 }}</td>
                <td>{{ number_format($meta->percentual_pt ?? 0, 2) }}</td>
                <td>{{ $meta->aprovacao_mt ?? 0 }}</td>
                <td>{{ number_format($meta->percentual_mat ?? 0, 2) }}</td>
                <td>{{ $meta->aprovacao_geral ?? 0 }}</td>
                <td>{{ number_format($meta->percentual_geral ?? 0, 2) }}</td>
                <td>{{ number_format($meta->ide_sala ?? 0, 2) }}</td>
            </tr>
            <tr>
                <td class="bg-info font-weight-bold">Resultado</td>
                <td class="{{ getColorClass($resultado->total_alunos ?? null, $meta->alunos ?? null) }}">
                    {{ $resultado->total_alunos ?? 'N/D' }}
                </td>
                <td class="{{ getColorClass($resultado->media_geral ?? null, $meta->media_geral ?? null) }}">
                    {{ $resultado ? number_format($resultado->media_geral, 2) : 'N/D' }}
                </td>
                <td class="{{ getColorClass($resultado->infrequencia ?? null, $meta->infrequencia ?? null, true) }}">
                    {{ $resultado ? number_format($resultado->infrequencia, 2) : 'N/D' }}
                </td>
                <td class="{{ getColorClass($resultado->frequencia ?? null, $meta->frequencia ?? null) }}">
                    {{ $resultado ? number_format($resultado->frequencia, 2) : 'N/D' }}
                </td>
                <td class="{{ getColorClass($resultado->acima_media_pt ?? null, $meta->aprovacao_lp ?? null) }}">
                    {{ $resultado->acima_media_pt ?? 'N/D' }}
                </td>
                <td class="{{ getColorClass($resultado->percentual_pt ?? null, $meta->percentual_pt ?? null) }}">
                    {{ $resultado ? number_format($resultado->percentual_pt, 2) : 'N/D' }}
                </td>
                <td class="{{ getColorClass($resultado->acima_media_mat ?? null, $meta->aprovacao_mt ?? null) }}">
                    {{ $resultado->acima_media_mat ?? 'N/D' }}
                </td>
                <td class="{{ getColorClass($resultado->percentual_mat ?? null, $meta->percentual_mat ?? null) }}">
                    {{ $resultado ? number_format($resultado->percentual_mat, 2) : 'N/D' }}
                </td>
                <td class="{{ getColorClass($resultado->acima_media_geral ?? null, $meta->aprovacao_geral ?? null) }}">
                    {{ $resultado->acima_media_geral ?? 'N/D' }}
                </td>
                <td class="{{ getColorClass($resultado->percentual_aprovacao_geral ?? null, $meta->percentual_geral ?? null) }}">
                    {{ $resultado ? number_format($resultado->percentual_aprovacao_geral, 2) : 'N/D' }}
                </td>
                <td class="{{ getColorClass($resultado->media_total ?? null, $meta->ide_sala ?? null) }}">
                    {{ $resultado ? number_format($resultado->media_total, 2) : 'N/D' }}
                </td>
            </tr>
            @endfor
        </tbody>
    </table>

    <!-- Legenda das Cores -->
    <div style="margin-top: 30px; padding: 15px; background-color: #f8f9fa; border-radius: 5px;">
        <h5 style="margin-bottom: 10px; color: #495057;">Legenda das Cores</h5>
        <div style="display: inline-block; margin-right: 20px; margin-bottom: 10px;">
            <span style="display: inline-block; padding: 4px 8px; border-radius: 4px; font-size: 12px; font-weight: bold; background-color: #d4edda; color: #155724;">Verde</span>
            <span>Resultado atingiu ou superou a meta</span>
        </div>
        <div style="display: inline-block; margin-right: 20px; margin-bottom: 10px;">
            <span style="display: inline-block; padding: 4px 8px; border-radius: 4px; font-size: 12px; font-weight: bold; background-color: #fff3cd; color: #856404;">Amarelo</span>
            <span>Resultado abaixo da meta</span>
        </div>
        <div style="display: inline-block; margin-right: 20px; margin-bottom: 10px;">
            <span style="display: inline-block; padding: 4px 8px; border-radius: 4px; font-size: 12px; font-weight: bold; background-color: #e2e3e5; color: #383d41;">Cinza</span>
            <span>Dados não criados ainda</span>
        </div>
    </div>
</body>
</html>
