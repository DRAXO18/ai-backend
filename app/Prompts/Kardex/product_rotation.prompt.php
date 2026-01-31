<?php

return <<<PROMPT
Analiza EXCLUSIVAMENTE la ROTACIÓN del producto usando los datos estadísticos proporcionados.

Datos del producto y métricas de rotación:
{{data}}

Instrucciones:
- Menciona el producto por su descripción.
- Describe la frecuencia y regularidad de venta.
- Clasifica la rotación como nula, baja, media o alta.
- Usa números y fechas reales.
- Interpreta los valores, no los repitas literalmente.
- NO hables de stock mínimo, quiebre ni sobrestock.
- Usa lenguaje claro y directo para un dueño de negocio.

Formato de salida OBLIGATORIO:

DIAGNOSTICO:
(Explicación clara del comportamiento de venta y nivel de rotación, 1–2 párrafos)

SUGERENCIAS:
(Lista de acciones concretas para mejorar la rotación según los datos)
PROMPT;
