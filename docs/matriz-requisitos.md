# Matriz de requisitos verificables

## Proyecto

**SISTEMA DE CONTROL AUTOMATIZADO DE GRANJAS DE CODORNIZ PARA LA GESTIÓN DE ALIMENTACIÓN, HIDRATACIÓN, TEMPERATURA Y CONTEO DE HUEVOS EN LA COMUNIDAD DE TUSCAPUJYO**

## Alcance y advertencias

Esta matriz transforma en elementos verificables los requisitos derivados de los objetivos del proyecto proporcionados para este análisis. No corresponde a una lista formal aprobada de requisitos; por ello, cada ambigüedad o criterio no verificable con la información disponible se indica explícitamente.

Los casos relacionados son referencias a los casos ya documentados en [docs/casos-de-prueba.md](casos-de-prueba.md). Que un caso exista no significa que haya sido ejecutado ni que el requisito esté aprobado.

### Prioridades

- **Alta:** una falla afecta una función principal, la operación automatizada, la integridad de los datos o la integración.
- **Media:** una falla afecta una capacidad relevante, pero no impide toda la operación.

## Matriz

| ID | Tipo de requisito | Requisito | Módulo | Prioridad | Criterio de aceptación | Cómo puede verificarse | Caso(s) de prueba relacionado(s) |
|---|---|---|---|---|---|---|---|
| RF-001 | Funcional | El sistema debe controlar y orquestar sensores y actuadores mediante un microcontrolador ESP32 conectado por Wi-Fi. | Control central (ESP32); Integración de datos | Alta | Ante una condición de entrada definida, el ESP32 recibe la lectura, procesa la condición y coordina el actuador correspondiente; además, establece la comunicación Wi-Fi prevista. La condición, la secuencia y el tiempo máximo están pendientes de definición. | Prueba de integración con sensores, actuadores y red Wi-Fi; observar la lectura, la acción y los registros de comunicación. | CP-001, CP-002, CP-003, CP-004, CP-005, CP-036, CP-037, CP-038 |
| RF-002 | Funcional | El sistema debe suministrar alimento de forma automática mediante servomotores. | Alimentación e hidratación | Alta | Cuando se produzca la condición u orden configurada, el servomotor se activa y realiza el suministro de alimento; al finalizar, deja de operar. La cantidad, frecuencia, duración y condición exacta están pendientes de definición. | Activar una condición controlada, observar el servomotor y comprobar el resultado físico y el registro generado. | CP-006, CP-008, CP-009, CP-037 |
| RF-003 | Funcional | El sistema debe suministrar agua de forma automática mediante mini-bombas de agua. | Alimentación e hidratación | Alta | Cuando se produzca la condición u orden configurada, la mini-bomba se activa y realiza el suministro de agua; al finalizar, deja de operar. La cantidad, frecuencia, duración y condición exacta están pendientes de definición. | Activar una condición controlada, observar la mini-bomba y comprobar el resultado físico y el registro generado. | CP-007, CP-008, CP-010, CP-037 |
| RF-004 | Funcional | El sistema debe monitorear de forma constante la humedad y temperatura del ambiente. | Monitoreo ambiental | Alta | El sistema obtiene y registra lecturas de humedad y temperatura durante la operación, sin aceptar como válidas lecturas inválidas o sensores no disponibles. La frecuencia de lectura, el significado de “constante” y los rangos válidos están pendientes de definición. | Ejecutar lecturas durante un periodo definido, comparar sensores con los datos recibidos y almacenados, y simular una lectura inválida o sensor desconectado. | CP-011, CP-015, CP-036 |
| RF-005 | Funcional | El sistema debe ejecutar acciones correctivas automáticas cuando la humedad o temperatura salgan de rango. | Monitoreo ambiental; Control central | Alta | Para cada condición ambiental fuera de los rangos aprobados, se ejecuta la acción correctiva correspondiente y se evita una acción incorrecta cuando la lectura está dentro del rango. Los rangos, las acciones y el tiempo máximo de respuesta no están proporcionados; por tanto, el requisito no es completamente verificable todavía. | Aplicar condiciones inferiores, superiores y límite; comprobar lectura, acción, ausencia de acción indebida y tiempo observado una vez definidos los parámetros. | CP-012, CP-013, CP-014, CP-036 |
| RF-006 | Funcional | El sistema debe contar la producción de huevos a partir del peso acumulado, usando celdas de carga y el módulo HX711. | Conteo de inventario | Alta | Ante un peso conocido y válido, el sistema convierte el peso en una cantidad de huevos y registra el resultado sin duplicar una misma lectura. La equivalencia peso-huevo, unidad, tolerancia, calibración y definición de “peso acumulado” están pendientes de aprobación. | Usar pesos de referencia, límites y lecturas repetidas; comparar el resultado con la equivalencia aprobada y revisar el registro persistido. | CP-016, CP-017, CP-018, CP-019, CP-020, CP-038, CP-040 |
| RF-007 | Funcional | El sistema debe registrar en una plataforma web los datos de producción y variables ambientales en tiempo real. | Plataforma web; Integración de datos | Alta | Un dato válido enviado por el ESP32 queda disponible en la plataforma web con sus valores de producción y variables ambientales, sin presentarse como recibido si la transmisión falla. El tiempo máximo que define “tiempo real” está pendiente de especificación. | Enviar una lectura válida y una transmisión fallida; comparar payload, respuesta, base de datos y visualización web; medir el tiempo observado cuando se defina el límite. | CP-031, CP-033, CP-036, CP-037, CP-038, CP-039 |
| RF-008 | Funcional | El sistema debe almacenar un historial de los datos de producción y variables ambientales para consulta posterior. | Plataforma web; Persistencia | Alta | Los datos válidos permanecen almacenados y pueden consultarse posteriormente con los mismos valores y su información temporal; los datos inválidos o incompletos no deben crear registros aceptados. El periodo de retención y la política ante duplicados están pendientes de definición. | Insertar datos controlados, consultar el historial, comparar origen y destino, y repetir casos con campos faltantes, duplicados y fechas diferentes. | CP-032, CP-033, CP-034, CP-035, CP-039, CP-040 |
| RNF-001 | No funcional | El sistema debe transmitir los datos del ESP32 hacia la plataforma web de forma oportuna (near real-time). | Integración de datos; Plataforma web | Alta | La diferencia entre el envío del ESP32 y la disponibilidad en la plataforma debe estar dentro del tiempo máximo aprobado. Actualmente no puede declararse aprobado porque el tiempo exacto de respuesta no está definido. | Medir timestamps de envío, recepción, persistencia y visualización bajo condiciones normales; repetir con carga representativa y comparar con el umbral aprobado. | CP-003, CP-031, CP-036, CP-037, CP-038 |
| RNF-002 | No funcional | El sistema debe mantener disponible el almacenamiento histórico de datos sin pérdida de información. | Persistencia; Plataforma web | Alta | Cada dato válido confirmado debe poder recuperarse posteriormente sin pérdida ni modificación no autorizada, dentro de la política de retención aprobada. La definición de disponibilidad, respaldo, recuperación, retención y tolerancia de pérdida no está proporcionada. | Comparar un conjunto identificado de datos enviados, almacenados y consultados; simular interrupción y recuperación de la comunicación o del servicio de datos. | CP-032, CP-033, CP-035, CP-040, CP-041 |
| RNF-003 | No funcional | El sistema debe operar de forma estable ante variaciones normales de conectividad Wi-Fi en un entorno rural. | Integración de datos; Control central | Alta | Ante una interrupción o variación de Wi-Fi considerada normal, el sistema conserva un estado controlado, identifica la pérdida de comunicación y se recupera según el comportamiento aprobado, sin confirmar datos no transmitidos. Los criterios de estabilidad, reconexión, reintentos, almacenamiento temporal y tiempo de recuperación están pendientes de definición. | Interrumpir y restablecer la red durante la operación; observar ESP32, API, registros y plataforma; medir pérdida, duplicación y tiempo de recuperación. | CP-004, CP-005, CP-041 |
| RNF-004 | No funcional | La plataforma web debe ser desarrollada en PHP, Laravel y MySQL. | Plataforma web; Persistencia | Media | La solución ejecutada utiliza PHP, Laravel y MySQL en las versiones aprobadas para el proyecto, y la plataforma puede operar con esa arquitectura. Las versiones aprobadas y el criterio de compatibilidad no están proporcionados. | Verificación documental y de configuración del proyecto; inspección de dependencias, ejecución del sistema y comprobación de conexión entre Laravel y MySQL. No es un criterio funcional que se valide solo mediante los casos de uso. | No existe un caso específico en la especificación actual; agregar una verificación de configuración/ambiente. |

## Requisitos ambiguos o no verificables actualmente

Los siguientes puntos impiden cerrar criterios de aceptación sin información adicional:

- **“De forma automática”** no define el evento, horario, frecuencia, duración ni cantidad de alimento o agua.
- **“De forma constante”** no define frecuencia de muestreo, intervalo de transmisión ni tolerancia de interrupción.
- **“Fuera de rango”** no define valores mínimos y máximos de temperatura y humedad ni si los límites son inclusivos.
- **“Acciones correctivas”** no identifica qué actuador debe responder a cada condición ni el tiempo máximo de respuesta.
- **“A partir del peso acumulado”** no define si el valor es acumulado por lectura, periodo o producción, ni cómo evitar duplicados.
- **“Tiempo real” o “near real-time”** no define una latencia máxima medible.
- **“Sin pérdida de información”** no define respaldo, transacciones, reintentos, recuperación ni pérdida máxima aceptable.
- **“Estable” ante Wi-Fi rural** no define porcentaje de disponibilidad, cantidad de reconexiones, tiempo de recuperación ni comportamiento fuera de línea.
- El requisito tecnológico no especifica versiones de PHP, Laravel y MySQL, ni compatibilidad, rendimiento o seguridad mínimos.

## Información necesaria para completar la matriz

Para convertir todas las filas en requisitos plenamente verificables se debe proporcionar:

1. Requisitos formales del capítulo de análisis de la tesis, con su numeración y criterios de aceptación.
2. Rangos exactos de temperatura y humedad, unidades, límites inclusivos y acciones correctivas asociadas.
3. Frecuencia de lectura, frecuencia de envío y latencia máxima aceptable para “tiempo real”.
4. Cantidad, frecuencia, duración y condiciones de activación de los suministros de alimento y agua.
5. Equivalencia aprobada entre peso y huevo, unidad de medida, tolerancia, calibración y regla de acumulación.
6. Tratamiento de lecturas repetidas, mensajes duplicados, datos fuera de orden y datos inválidos.
7. Estrategia ante pérdida de Wi-Fi: reintentos, almacenamiento temporal, sincronización y tiempo máximo de recuperación.
8. Definición medible de estabilidad y disponibilidad en el entorno rural.
9. Política de persistencia: transacciones, respaldo, recuperación, retención y pérdida máxima aceptable.
10. Versiones de PHP, Laravel y MySQL, requisitos de compatibilidad y ambiente oficial de pruebas.
11. Requisitos formales de seguridad, autenticación, autorización, protección del token y auditoría.
12. Roles y permisos de usuario, si la plataforma web los contempla.
13. Carga esperada: cantidad de dispositivos, frecuencia de registros, usuarios concurrentes y tiempos de respuesta.
14. Criterios de entrada, salida, severidad, prioridad y aprobación de los resultados.

Hasta recibir estos datos, la matriz puede utilizarse para diseñar pruebas preliminares, pero no para declarar conformidad definitiva de los requisitos ambiguos.