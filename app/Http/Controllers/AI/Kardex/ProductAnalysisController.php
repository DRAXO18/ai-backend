<?php

namespace App\Http\Controllers\AI\Kardex;

use App\Http\Controllers\Controller;
use App\Services\Metrics\Kardex\ProductRotationMetricsService;
use App\Services\Metrics\Kardex\ProductStockRiskMetricsService;

use App\Services\AI\OpenAIService;
use Illuminate\Http\Request;

class ProductAnalysisController extends Controller
{
    public function rotation(Request $request)
    {
        $productId = (int) $request->input('product_id');

        if (!$productId) {
            return response()->json([
                'error' => 'product_id es requerido'
            ], 422);
        }

        $metrics = app(ProductRotationMetricsService::class)
            ->calculate($productId);

        $analysis = app(OpenAIService::class)
            ->analyze('Kardex/product_rotation', $metrics);

        // Separar diagnóstico y sugerencias
        $parts = preg_split('/SUGERENCIAS:\s*/i', $analysis);

        $diagnostic = trim(
            preg_replace('/^DIAGNOSTICO:\s*/i', '', $parts[0] ?? '')
        );

        $suggestions = isset($parts[1])
            ? trim($parts[1])
            : null;

        return response()->json([
            'product' => $metrics['product'],
            'metrics' => $metrics['statistics'],
            'rotation_analysis' => $diagnostic,
            'suggestions' => $suggestions,
        ]);
    }


    public function stockRisk(Request $request)
    {
        $productId = (int) $request->input('product_id');

        if (!$productId) {
            return response()->json([
                'error' => 'product_id es requerido'
            ], 422);
        }

        $metrics = app(ProductStockRiskMetricsService::class)
            ->calculate($productId);

        $analysis = app(OpenAIService::class)
            ->analyze('Kardex/product_stock_risk', $metrics);

        // Separación simple y segura
        $parts = preg_split('/SUGERENCIAS:\s*/i', $analysis);

        $diagnostic = trim(
            preg_replace('/^DIAGNOSTICO:\s*/i', '', $parts[0] ?? '')
        );

        $suggestions = isset($parts[1])
            ? trim($parts[1])
            : null;


        return response()->json([
            'product' => $metrics['product'],
            'stock' => $metrics['stock'],
            'risk_analysis' => $diagnostic,
            'suggestions' => $suggestions,
        ]);
    }
}
