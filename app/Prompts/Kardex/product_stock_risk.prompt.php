<?php

return <<<PROMPT
Analiza EXCLUSIVAMENTE el RIESGO DE STOCK del producto usando los datos proporcionados.

Datos del producto y métricas de stock:
{{data}}

Instrucciones:
- Menciona el producto por su descripción.
- Evalúa si el stock es saludable, si existe riesgo de quiebre o riesgo de sobrestock.
- Usa los valores reales (stock actual, stock mínimo, consumo promedio, cobertura si existe).
- NO hables de rotación ni ventas históricas.
- Usa lenguaje claro y directo para un dueño de negocio.

Formato de salida OBLIGATORIO:

DIAGNOSTICO:
(Explicación clara del estado del stock y el riesgo detectado, 1–2 párrafos)

SUGERENCIAS:
(Lista de acciones concretas y aplicables según el escenario detectado)
PROMPT;
