# Plan de Pruebas

## Proyecto

**SISTEMA DE CONTROL AUTOMATIZADO DE GRANJAS DE CODORNIZ PARA LA GESTIÓN DE ALIMENTACIÓN, HIDRATACIÓN, TEMPERATURA Y CONTEO DE HUEVOS EN LA COMUNIDAD DE TUSCAPUJYO**

## 1. Introducción

El presente Plan de Pruebas define el enfoque, el alcance, los recursos y los criterios que se utilizarán para verificar el sistema de control automatizado y gestión digital para granjas de codorniz de la comunidad de Tuscapujyo.

El sistema integra un microcontrolador ESP32, sensores y actuadores físicos, conectividad Wi-Fi y una plataforma web desarrollada con PHP y Laravel, con persistencia de información en MySQL. Las pruebas se ejecutarán de forma progresiva, desde la verificación de componentes y módulos individuales hasta la validación de la solución integrada.

Este documento describe cómo se realizarán las pruebas. No constituye evidencia de pruebas ejecutadas ni contiene resultados anticipados.

## 2. Objetivo general de las pruebas

Verificar que el sistema controle y gestione de manera funcional, integrada y confiable la alimentación, la hidratación, las variables de temperatura y humedad, y el conteo de huevos, además de transmitir, visualizar y almacenar la información mediante la conectividad Wi-Fi, la plataforma web y la base de datos MySQL.

## 3. Objetivos específicos

- Verificar la orquestación de sensores y actuadores realizada por el ESP32.
- Comprobar el suministro controlado de alimento mediante servomotores.
- Comprobar el suministro controlado de agua mediante mini-bombas.
- Verificar la lectura de humedad y temperatura y la ejecución de acciones correctivas automáticas.
- Validar el uso de las celdas de carga y del módulo HX711 para obtener el peso acumulado de la producción.
- Verificar la transformación del peso acumulado en unidades registrables para el conteo y la gestión de inventario.
- Comprobar la comunicación Wi-Fi entre el ESP32 y la plataforma web.
- Verificar el monitoreo en tiempo real desde la plataforma web.
- Comprobar el almacenamiento y la consulta de datos históricos de producción y variables ambientales en MySQL.
- Detectar, registrar, priorizar y dar seguimiento a los defectos encontrados durante las pruebas.

## 4. Alcance

El alcance comprende la verificación de los siguientes procesos y componentes:

- Control central basado en ESP32.
- Lectura y procesamiento de sensores de humedad y temperatura.
- Accionamiento de servomotores para alimentación.
- Accionamiento de mini-bombas para hidratación.
- Lectura de celdas de carga mediante HX711.
- Conteo y registro de huevos a partir del peso acumulado.
- Comunicación entre el ESP32 y la plataforma web mediante Wi-Fi.
- Recepción, procesamiento, visualización y almacenamiento de datos en la plataforma.
- Gestión de información histórica de producción y variables ambientales.
- Integración entre el backend PHP/Laravel y la base de datos MySQL.
- Verificación de los criterios de aceptación definidos para el sistema antes de su puesta en producción.

## 5. Fuera de alcance

Quedan fuera del alcance de este plan:

- El diseño o fabricación física de nuevos componentes de hardware.
- La modificación de la infraestructura de conectividad Wi-Fi existente.
- La evaluación de aspectos comerciales de la producción de la granja.
- La validación veterinaria o biológica de la salud de las codornices.
- La evaluación de la calidad nutricional del alimento o del agua.
- La medición de resultados productivos de largo plazo que no puedan verificarse mediante el sistema.
- La evaluación de tecnologías distintas de PHP, Laravel, MySQL, ESP32, los sensores, las celdas de carga, el HX711, los servomotores, las mini-bombas y Wi-Fi indicados para el proyecto.

## 6. Descripción del sistema a probar

El sistema tiene como finalidad apoyar la gestión técnica de una granja de codorniz mediante automatización y gestión digital. El ESP32 actúa como unidad central para orquestar sensores y actuadores a través de conectividad Wi-Fi.

El sistema contempla el suministro de alimento e hidratación mediante servomotores y mini-bombas de agua, respectivamente. También monitorea humedad y temperatura para permitir acciones correctivas automáticas de regulación ambiental.

Para la producción de huevos, las celdas de carga y el módulo HX711 permiten obtener el peso acumulado. Este peso se utiliza para generar unidades registrables destinadas al conteo y a la gestión de inventario. La plataforma web permite el monitoreo en tiempo real y el almacenamiento de datos históricos de producción y de variables ambientales.

## 7. Módulos incluidos

### 7.1. Módulo de control y automatización central

Se verificará la capacidad del ESP32 para recibir lecturas, procesar las condiciones del sistema y coordinar los actuadores correspondientes.

### 7.2. Módulo de alimentación

Se verificará el accionamiento de los servomotores y el suministro controlado de alimento según la lógica definida para el sistema.

### 7.3. Módulo de hidratación

Se verificará el accionamiento de las mini-bombas de agua y el suministro controlado de hidratación.

### 7.4. Módulo de sensores ambientales

Se verificará la lectura de humedad y temperatura, su transmisión y la respuesta de regulación ambiental cuando corresponda.

### 7.5. Módulo de conteo y gestión de inventario de huevos

Se verificará la lectura de las celdas de carga a través del HX711, el procesamiento del peso acumulado y el registro de unidades de producción.

### 7.6. Módulo de conectividad e integración

Se verificará la comunicación Wi-Fi entre el ESP32 y la plataforma web, incluyendo el intercambio de información necesario para el monitoreo y el registro.

### 7.7. Módulo de plataforma web y persistencia

Se verificará la presentación del monitoreo, el almacenamiento de datos históricos y la interacción del backend PHP/Laravel con MySQL.

## 8. Tipos de pruebas

Se aplicarán los siguientes tipos de pruebas:

- **Pruebas unitarias:** verificar de forma aislada las funciones o componentes de software que procesen lecturas, controlen acciones o gestionen registros.
- **Pruebas de integración:** verificar el intercambio de información entre ESP32, sensores, actuadores, Wi-Fi, backend Laravel y MySQL.
- **Pruebas funcionales:** comprobar que cada módulo cumpla las funciones previstas de alimentación, hidratación, monitoreo, conteo y gestión.
- **Pruebas de hardware y control:** verificar lecturas de sensores, recepción de señales de celdas de carga y activación de servomotores y mini-bombas.
- **Pruebas de conectividad:** comprobar el envío y la recepción de datos mediante Wi-Fi y el comportamiento ante una comunicación no disponible.
- **Pruebas de persistencia de datos:** comprobar que los datos recibidos se almacenen y puedan consultarse como información histórica en MySQL.
- **Pruebas de interfaz web:** comprobar que la plataforma muestre correctamente la información de monitoreo y los registros disponibles.
- **Pruebas de validación de datos:** verificar que los datos de sensores, producción y variables ambientales se procesen y registren de forma consistente.
- **Pruebas de regresión:** repetir las pruebas relevantes después de corregir defectos o modificar componentes.
- **Pruebas de aceptación:** verificar, con base en los objetivos del proyecto, que la solución integrada sea apta para la evaluación final y el paso a producción.

## 9. Estrategia de pruebas

La estrategia será incremental y basada en riesgos funcionales:

1. Preparar el ambiente, el hardware y los datos de prueba.
2. Verificar de manera aislada los módulos de software, control, sensores y actuadores.
3. Integrar progresivamente ESP32, sensores, actuadores y comunicación Wi-Fi.
4. Integrar la comunicación con la plataforma web desarrollada con Laravel.
5. Verificar el almacenamiento y la consulta de datos en MySQL.
6. Ejecutar escenarios normales, límites, datos inválidos o incompletos y pérdida de conectividad cuando sea aplicable.
7. Registrar evidencias y defectos, corregir los defectos priorizados y ejecutar las pruebas de regresión correspondientes.
8. Ejecutar la validación integral y evaluar los criterios de salida.

La prioridad de las pruebas será: control de alimentación e hidratación, monitoreo ambiental, conteo de huevos, comunicación de datos, persistencia y visualización web. Las pruebas se realizarán con condiciones controladas y con medidas de seguridad apropiadas para el hardware y los animales.

## 10. Ambiente de pruebas

El ambiente de pruebas deberá incluir:

- Una instalación o montaje de prueba del sistema de granja de codorniz.
- Un ESP32 conectado a los sensores y actuadores definidos.
- Sensores de humedad y temperatura.
- Celdas de carga conectadas al módulo HX711.
- Servomotores para el módulo de alimentación.
- Mini-bombas de agua para el módulo de hidratación.
- Una red Wi-Fi disponible para la comunicación entre el ESP32 y la plataforma web.
- Un equipo o entorno donde pueda ejecutarse la plataforma web PHP/Laravel.
- Una instancia de base de datos MySQL para almacenar los registros de prueba.
- Herramientas de observación y registro de evidencias, como capturas de pantalla, fotografías, registros de ejecución y reportes de defectos.

El ambiente deberá aislar, en lo posible, los datos de prueba de cualquier información real de producción.

## 11. Tecnologías utilizadas

Las pruebas considerarán las siguientes tecnologías del sistema:

- **Backend web:** PHP con framework Laravel.
- **Base de datos:** MySQL.
- **Hardware embebido:** ESP32.
- **Sensores:** sensores de humedad y temperatura, y celdas de carga con módulo HX711.
- **Actuadores:** servomotores y mini-bombas de agua.
- **Conectividad:** Wi-Fi entre el ESP32 y la plataforma web.

Para Laravel se verificará el procesamiento de la información y la comunicación del backend con MySQL. Para MySQL se verificará la persistencia, consulta y consistencia de los registros generados por el sistema.

## 12. Datos de prueba

Los datos de prueba se prepararán de forma controlada y estarán identificados para distinguirlos de los datos reales. Incluirán:

- Lecturas representativas de humedad y temperatura.
- Cambios en las lecturas ambientales que permitan verificar acciones correctivas automáticas.
- Activaciones previstas de servomotores para suministro de alimento.
- Activaciones previstas de mini-bombas para suministro de agua.
- Lecturas de peso obtenidas mediante celdas de carga y HX711.
- Valores de peso que permitan verificar la transformación en unidades registrables.
- Registros de producción de huevos para comprobar el conteo y el inventario.
- Datos correspondientes a diferentes momentos para comprobar el historial.
- Escenarios de comunicación Wi-Fi disponible y no disponible.
- Datos incompletos, fuera de formato o no válidos, cuando el módulo permita recibirlos, para verificar su tratamiento.

No se establecerán resultados numéricos ni valores de aceptación que no hayan sido definidos por el proyecto. Los valores concretos utilizados en cada ejecución deberán quedar registrados en el caso de prueba y en su evidencia correspondiente.

## 13. Roles y responsables

| Rol | Responsabilidades |
|---|---|
| Responsable de QA | Elaborar y mantener el plan; diseñar casos de prueba; coordinar la ejecución; registrar evidencias y defectos; verificar criterios de salida. |
| Responsable de desarrollo web | Atender defectos del backend PHP/Laravel y de la plataforma web; apoyar las pruebas de integración y regresión. |
| Responsable de base de datos | Apoyar la preparación del ambiente MySQL y verificar la persistencia, consulta y consistencia de los datos. |
| Responsable de hardware embebido | Preparar y revisar el ESP32, sensores, celdas de carga, HX711, servomotores y mini-bombas; apoyar la ejecución de pruebas físicas. |
| Responsable del proyecto | Revisar avances, priorizar defectos y aprobar la evaluación de preparación para producción. |

Una misma persona podrá asumir más de un rol cuando la organización del proyecto lo requiera, siempre que las responsabilidades queden identificadas.

## 14. Criterios de entrada

Las pruebas podrán iniciar cuando se cumpla lo siguiente:

- El alcance funcional del sistema esté identificado.
- Los módulos incluidos estén disponibles para ser evaluados.
- El ESP32, sensores, actuadores, celdas de carga y HX711 estén disponibles para las pruebas correspondientes.
- La conectividad Wi-Fi requerida esté disponible para las pruebas de integración.
- La plataforma PHP/Laravel pueda ejecutarse en el ambiente de pruebas.
- La base de datos MySQL esté disponible y separada de los datos reales de producción.
- Los datos y casos de prueba hayan sido preparados.
- Los responsables de las pruebas hayan sido designados.
- Se hayan definido los medios para registrar resultados, evidencias y defectos.

## 15. Criterios de salida

La etapa de pruebas podrá cerrarse cuando:

- Se hayan ejecutado los casos de prueba planificados para el alcance aprobado.
- Los módulos incluidos hayan sido verificados individualmente y como solución integrada.
- Los defectos encontrados estén registrados con su estado y prioridad.
- No existan defectos abiertos que impidan las funciones principales de alimentación, hidratación, monitoreo ambiental, conteo de huevos, comunicación o persistencia, salvo que exista una aceptación formal del riesgo.
- Se hayan ejecutado las pruebas de regresión asociadas a las correcciones.
- Las evidencias requeridas estén organizadas y vinculadas con los casos de prueba.
- Se haya emitido el informe o acta de cierre de pruebas con la recomendación correspondiente.

El cumplimiento de estos criterios debe sustentarse con evidencias; no se asumirá por el solo hecho de completar una ejecución.

## 16. Riesgos de las pruebas

| Riesgo | Impacto posible | Tratamiento previsto |
|---|---|---|
| Falla o desconexión del ESP32 | Interrupción del control y de la comunicación | Revisar conexiones, registrar la condición y repetir la prueba en un ambiente controlado. |
| Lecturas incorrectas de sensores o celdas de carga | Acciones ambientales o conteos no confiables | Verificar conexiones, condiciones de prueba y trazabilidad de las lecturas obtenidas. |
| Activación incorrecta de servomotores o mini-bombas | Suministro insuficiente o excesivo | Ejecutar pruebas controladas y supervisar físicamente cada activación. |
| Pérdida de conectividad Wi-Fi | Datos no transmitidos o monitoreo no actualizado | Ejecutar escenarios de comunicación disponible y no disponible, y registrar el comportamiento observado. |
| Fallas del backend Laravel | Procesamiento o visualización incorrectos | Revisar registros de ejecución, reproducir el caso y realizar pruebas de integración y regresión. |
| Errores de persistencia en MySQL | Pérdida, duplicación o inconsistencia del historial | Comparar los datos enviados con los datos almacenados y consultados. |
| Datos de prueba mezclados con datos reales | Resultados o inventario contaminados | Utilizar un ambiente y registros identificados para pruebas. |
| Riesgo para personas, equipos o animales durante una prueba física | Daños o interrupción de la ejecución | Supervisar las pruebas, trabajar con condiciones controladas y detener la prueba ante una condición insegura. |

## 17. Gestión de defectos

Cada defecto deberá registrarse con, como mínimo, los siguientes datos:

- Identificador único.
- Fecha de detección.
- Caso de prueba relacionado.
- Módulo afectado.
- Descripción clara del comportamiento observado.
- Comportamiento esperado.
- Pasos para reproducirlo.
- Datos y condiciones utilizados.
- Evidencia asociada.
- Severidad y prioridad.
- Responsable de la corrección.
- Estado del defecto.
- Fecha y resultado de la verificación de la corrección.

El flujo de gestión será: **Nuevo**, **Asignado**, **En corrección**, **Listo para verificar**, **Verificado** o **Reabierto**. Los defectos que impidan las funciones principales deberán atenderse antes de recomendar el paso a producción, o contar con una aceptación formal del riesgo por parte del responsable del proyecto.

## 18. Evidencias requeridas

Para cada prueba ejecutada se conservará, según corresponda:

- Caso de prueba y datos utilizados.
- Fecha, responsable y ambiente de ejecución.
- Resultado observado y comparación con el resultado esperado.
- Fotografías o videos de sensores, actuadores, ESP32, celdas de carga y HX711 cuando la prueba sea física.
- Capturas de la plataforma web durante el monitoreo.
- Registros de valores transmitidos y recibidos por Wi-Fi.
- Evidencia de los registros almacenados y consultados en MySQL.
- Registros de ejecución del backend PHP/Laravel cuando sean necesarios para reproducir un defecto.
- Reportes de defectos y evidencia de su verificación.
- Resumen de ejecución, cobertura del alcance y estado final de los defectos.

## 19. Cronograma sugerido

El cronograma se organizará por etapas y podrá ajustarse a la disponibilidad del hardware y del equipo:

| Etapa | Actividad |
|---|---|
| 1 | Revisión del alcance, preparación del plan y definición de casos de prueba. |
| 2 | Preparación del ambiente PHP/Laravel, MySQL, ESP32, sensores y actuadores. |
| 3 | Pruebas unitarias y funcionales de los módulos de control, alimentación, hidratación, sensores y conteo. |
| 4 | Pruebas de integración entre hardware, ESP32 y conectividad Wi-Fi. |
| 5 | Pruebas de integración de la plataforma web Laravel con MySQL. |
| 6 | Pruebas de escenarios normales, límites, datos inválidos y pérdida de conectividad. |
| 7 | Registro, corrección y verificación de defectos; ejecución de regresión. |
| 8 | Pruebas de aceptación, consolidación de evidencias y emisión del cierre de pruebas. |

Las fechas y la duración de cada etapa deberán establecerse de acuerdo con el calendario del proyecto de titulación.

## 20. Criterios para determinar si el sistema está listo para pasar a producción

El sistema podrá recomendarse para producción cuando:

- Las funciones principales de alimentación, hidratación, monitoreo de humedad y temperatura, conteo de huevos y gestión digital hayan sido verificadas.
- La comunicación entre el ESP32 y la plataforma web mediante Wi-Fi funcione en los escenarios definidos.
- Los datos de producción y variables ambientales se visualicen y almacenen correctamente en la plataforma y en MySQL.
- El conteo basado en el peso acumulado sea procesado conforme a la lógica definida para el sistema.
- Las correcciones realizadas hayan superado las pruebas de regresión correspondientes.
- No existan defectos críticos o de alta prioridad sin una decisión formal documentada.
- Las evidencias y resultados de las pruebas estén completos, organizados y revisados.
- El responsable del proyecto apruebe formalmente el paso a producción considerando el estado de los riesgos pendientes.

Estos criterios determinan la preparación del sistema; no sustituyen la autorización del responsable del proyecto ni la verificación de condiciones operativas de la granja.

## 21. Conclusiones

Este Plan de Pruebas establece una guía formal para verificar el sistema automatizado de gestión de granjas de codorniz, cubriendo sus módulos de control, alimentación, hidratación, monitoreo ambiental, conteo de huevos, conectividad Wi-Fi, plataforma web y persistencia en MySQL.

La aplicación progresiva de pruebas unitarias, funcionales, de hardware, de integración, de persistencia, de regresión y de aceptación permitirá reunir evidencias sobre el comportamiento del sistema y gestionar los defectos antes de su puesta en producción.

La ejecución de este plan deberá documentarse con los casos, datos, resultados y evidencias obtenidos durante las pruebas. Por tanto, este documento define el proceso de verificación, pero no afirma que las pruebas hayan sido realizadas ni que el sistema haya alcanzado los criterios de salida.
