<?php

namespace App\Services\Metrics\Kardex;

use Illuminate\Support\Facades\DB;
use Carbon\Carbon;

class ProductRotationMetricsService
{
    public function calculate(int $productId): array
    {
        $product = DB::table('productos')
            ->where('id', $productId)
            ->first();

        if (!$product) {
            throw new \Exception('Producto no encontrado');
        }

        $movements = DB::table('kardex')
            ->select(
                DB::raw("DATE_TRUNC('month', fecha) as mes"),
                DB::raw("SUM(cantidad) as total_vendido")
            )
            ->where('id_producto', $productId)
            ->whereRaw('LOWER(tipo) = ?', ['salida'])
            ->groupBy('mes')
            ->orderBy('mes')
            ->get();

        $monthlyTotals = $movements
            ->pluck('total_vendido')
            ->map(fn($v) => (float) $v)
            ->toArray();

        $months = count($monthlyTotals);
        $totalSold = array_sum($monthlyTotals);
        $average = $months ? $totalSold / $months : 0;

        sort($monthlyTotals);

        $median = $months
            ? ($months % 2
                ? $monthlyTotals[intval($months / 2)]
                : ($monthlyTotals[$months / 2 - 1] + $monthlyTotals[$months / 2]) / 2)
            : 0;

        $variance = 0;
        foreach ($monthlyTotals as $value) {
            $variance += pow($value - $average, 2);
        }
        $variance = $months ? $variance / $months : 0;
        $stdDeviation = sqrt($variance);

        return [
            'product' => [
                'id' => $product->id,
                'descripcion' => $product->descripcion,
            ],
            'statistics' => [
                'meses_analizados' => $months,
                'total_vendido' => round($totalSold, 2),
                'promedio_mensual' => round($average, 2),
                'mediana_mensual' => round($median, 2),
                'desviacion_mensual' => round($stdDeviation, 2),
                'ultima_venta' => optional($movements->last())->mes,
            ],
        ];
    }

    
}
