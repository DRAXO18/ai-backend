<?php

    namespace App\Services\Metrics\Kardex;

use Illuminate\Support\Facades\DB;

class ProductStockRiskMetricsService
{
    public function calculate(int $productId): array
    {
        $product = DB::table('productos')
            ->where('id', $productId)
            ->first();

        if (!$product) {
            throw new \Exception('Producto no encontrado');
        }

        // Promedio mensual de salidas
        $avgMonthly = DB::table('kardex')
            ->where('id_producto', $productId)
            ->whereRaw('LOWER(tipo) = ?', ['salida'])
            ->selectRaw('AVG(cantidad) as avg')
            ->value('avg');

        $avgMonthly = (float) ($avgMonthly ?? 0);

        // Días de cobertura estimados
        $coverageDays = $avgMonthly > 0
            ? round(($product->stock / $avgMonthly) * 30)
            : null;

        return [
            'product' => [
                'id' => $product->id,
                'descripcion' => $product->descripcion,
            ],
            'stock' => [
                'stock_actual' => (float) $product->stock,
                'stock_minimo' => (int) $product->stock_minimo,
                'promedio_salida_mensual' => round($avgMonthly, 2),
                'dias_cobertura_estimados' => $coverageDays,
            ],
        ];
    }
}