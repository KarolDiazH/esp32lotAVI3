# Análisis orientado a pruebas

## 1. Identificación y alcance del análisis

**Sistema:** SISTEMA DE CONTROL AUTOMATIZADO DE GRANJAS DE CODORNIZ PARA LA GESTIÓN DE ALIMENTACIÓN, HIDRATACIÓN, TEMPERATURA Y CONTEO DE HUEVOS EN LA COMUNIDAD DE TUSCAPUJYO.

Este análisis se limita a identificar el alcance de QA, los riesgos, las dependencias y la información necesaria para diseñar las pruebas. No constituye evidencia de ejecución ni confirma que una funcionalidad sea correcta.

La información proporcionada confirma un sistema integrado por:

- Un subsistema IoT con ESP32, sensores, celdas de carga, HX711, servomotores, mini-bombas y Wi-Fi.
- Una plataforma web PHP/Laravel con persistencia en MySQL.
- Procesos de monitoreo, automatización, registro histórico y visualización.

En el código revisado también se observan rutas para autenticación, usuarios, lotes de aves, sectores de monitoreo, reportes y acciones web de despacho/recarga. Deben incluirse en las pruebas si forman parte del alcance aprobado; su presencia en el código no sustituye la especificación funcional.

## 2. Módulos que deben ser probados

### 2.1. Control central e IoT

- Inicialización y operación del ESP32.
- Lectura y procesamiento de sensores.
- Coordinación entre lecturas, reglas y actuadores.
- Identificación del dispositivo y transmisión de datos.
- Comportamiento ante reinicio, error de sensor o pérdida de conectividad.

### 2.2. Alimentación

- Activación del servomotor o mecanismo de alimentación.
- Registro del nivel o peso de alimento.
- Despacho solicitado desde la plataforma web.
- Recarga de alimento.
- Finalización de la operación y prevención de activaciones indefinidas.

En el backend observado, un despacho web reduce el valor registrado en 100 gramos y una recarga registra 2 kg. Estos valores requieren confirmación como reglas oficiales del sistema.

### 2.3. Hidratación

- Activación de la mini-bomba.
- Registro del nivel de agua.
- Despacho solicitado desde la plataforma web.
- Recarga de agua.
- Finalización de la operación y tratamiento de tanque vacío.

En el backend observado, un despacho web reduce el valor registrado en 0,25 litros y una recarga registra 3 litros. Estos valores requieren confirmación como reglas oficiales.

### 2.4. Monitoreo ambiental

- Lectura de temperatura y humedad.
- Validación de formato, rango y frecuencia de lecturas.
- Almacenamiento y consulta del historial.
- Acciones correctivas automáticas cuando corresponda.
- Comportamiento ante lecturas nulas, cero, imposibles o sensor desconectado.

### 2.5. Conteo e inventario de huevos

- Lectura de celdas de carga mediante HX711.
- Calibración y estabilidad de la medición.
- Conversión del peso a cantidad de huevos.
- Acumulación, consulta diaria e historial.
- Presentación de maples y huevos sueltos, si esta vista forma parte del alcance aprobado.

El backend observado calcula `floor(peso / 10)` cuando el peso es mayor o igual a 9. La unidad de peso, la tolerancia y la justificación de esos límites deben confirmarse antes de convertirlos en criterios de aceptación.

### 2.6. Comunicación e integración

- ESP32 con red Wi-Fi.
- Autenticación de la API mediante token.
- Recepción de lecturas en Laravel.
- Entrega de órdenes pendientes de agua y comida al ESP32.
- Reintentos, duplicados, pérdida de paquetes y recuperación de conexión.

### 2.7. Plataforma web y persistencia

- Inicio o dashboard con últimos valores disponibles.
- Vistas de alimentación, hidratación, temperatura/humedad y huevos.
- Actualización y consistencia de datos mostrados.
- Persistencia, integridad, fechas, dispositivo de origen e historial en MySQL.

### 2.8. Autenticación, usuarios y autorización

Las rutas observadas incluyen inicio de sesión, cierre de sesión y gestión de usuarios. El código contempla los roles `admin` y `propietario`, y restringe la administración de usuarios al rol administrador. Deben probarse únicamente si este módulo está incluido formalmente.

### 2.9. Lotes de aves

Las rutas observadas permiten registrar, editar, listar y eliminar lotes. Se deben validar sus reglas de datos, relaciones con la población activa y efecto en los reportes, si la gestión de lotes pertenece al alcance del proyecto.

### 2.10. Reportes

Se observan reportes diarios e historial con filtros de fecha y estado térmico. Deben verificarse los cálculos, filtros, paginación, ausencia de datos y consistencia con los registros almacenados, si están aprobados como funcionalidad del sistema.

## 3. Funcionalidades críticas

Por impacto operativo y de seguridad, la prioridad alta debe concentrarse en:

1. Activación y detención correcta de alimento y agua.
2. Prevención de suministro cuando el silo o tanque está vacío.
3. Lectura ambiental y ejecución de acciones correctivas dentro de los umbrales aprobados.
4. Medición y conversión confiable del peso para contar huevos.
5. Transmisión autenticada y correcta entre ESP32 y API.
6. No pérdida, duplicación ni alteración silenciosa de datos históricos.
7. Recuperación o comportamiento controlado ante pérdida de Wi-Fi.
8. Restricción de acceso a funciones protegidas y administración de usuarios.
9. Consistencia entre el dato recibido, el dato almacenado y el dato visualizado.

Las pruebas físicas deben realizarse con supervisión para evitar daños a personas, equipos o animales.

## 4. Requisitos que requieren validación

### Confirmados a nivel de descripción

- El ESP32 coordina sensores y actuadores.
- El sistema gestiona alimentación e hidratación automatizadas.
- Se monitorean temperatura y humedad.
- Se obtiene el conteo a partir de celdas de carga y HX711.
- La plataforma recibe, almacena y visualiza datos.
- La comunicación entre el dispositivo y la plataforma utiliza Wi-Fi.
- La plataforma utiliza PHP/Laravel y MySQL.

### Pendientes de especificación

- Requisitos funcionales numerados y criterios de aceptación.
- Rangos normales y límites de temperatura y humedad.
- Tiempo máximo aceptable entre lectura, almacenamiento y visualización.
- Frecuencia de muestreo y frecuencia de envío.
- Precisión y tolerancia de sensores y celdas de carga.
- Unidad exacta del peso y equivalencia peso-huevo.
- Acciones correctivas concretas para cada condición ambiental.
- Reglas para reintentos, datos fuera de línea y sincronización posterior.
- Contrato de la API: campos obligatorios, tipos, respuestas, errores y versionado.
- Política de expiración y rotación del token del ESP32.
- Compatibilidad con navegadores y confirmación de diseño responsive o solo escritorio.
- Disponibilidad, rendimiento y volumen esperado de dispositivos y registros.
- Requisitos de respaldo, recuperación y retención de datos.
- Requisitos de seguridad, auditoría y protección de credenciales.

## 5. Reglas de negocio que deben comprobarse

Las siguientes reglas se observan en la implementación o en la descripción, pero deben ser aprobadas por el responsable funcional:

| Regla o comportamiento | Estado para QA |
|---|---|
| Un token inválido para la actualización del ESP32 debe recibir rechazo no autorizado. | Observado en API; validar contrato esperado. |
| Una actualización válida registra temperatura, humedad y producción. | Observado en API; validar campos y transacción. |
| El peso se transforma en unidades de huevo mediante una división y truncamiento. | Observado en API; faltan unidad, calibración y criterio aprobado. |
| Un silo sin alimento no debe permitir un despacho confirmado. | Observado en código; validar comportamiento físico y mensaje. |
| Un tanque sin agua no debe permitir un despacho confirmado. | Observado en código; validar comportamiento físico y mensaje. |
| El despacho de comida descuenta 100 gramos. | Observado en código; confirmar como regla de negocio. |
| El despacho de agua descuenta 0,25 litros. | Observado en código; confirmar como regla de negocio. |
| La recarga de comida fija 2 kg. | Observado en código; confirmar capacidad real. |
| La recarga de agua fija 3 litros. | Observado en código; confirmar capacidad real. |
| La temperatura se clasifica como calor, frío u óptima según filtros. | Observado en reportes; confirmar límites y unidades. |
| Un maple representa 30 huevos y los restantes se muestran como sueltos. | Observado en reportes; confirmar unidad de inventario. |
| Solo `admin` administra usuarios y los roles permitidos son `admin` y `propietario`. | Observado en código; confirmar matriz de permisos. |
| Un usuario no puede eliminar su propia cuenta activa. | Observado en código; confirmar comportamiento requerido. |
| El código de lote es obligatorio, único y se almacena en mayúsculas. | Observado en código; confirmar regla funcional. |
| La cantidad inicial de aves debe ser al menos 1 y la actual no puede ser negativa. | Observado en código; confirmar reglas de dominio. |

No se deben crear casos de alertas, exportaciones, notificaciones, programación horaria u otros comportamientos hasta que se confirme que existen.

## 6. Riesgos potenciales

- Lecturas erróneas por mala calibración, ruido, desconexión o deriva de sensores.
- Conteo incorrecto por una equivalencia peso-huevo no definida o por acumulación duplicada.
- Activación prolongada o repetida de servomotores y bombas.
- Desbordamiento, suministro insuficiente o falsa confirmación cuando un depósito está vacío.
- Pérdida, duplicación o desorden de registros durante cortes de Wi-Fi.
- Reenvío de datos sin control de idempotencia.
- Exposición o reutilización indebida del token del ESP32.
- Acceso no autorizado a dashboard, acciones físicas, usuarios o datos históricos.
- Inconsistencia entre datos cacheados, datos de base de datos y estado físico real.
- Errores de zona horaria o fecha que afecten reportes diarios e historiales.
- Cálculos de consumo, eficiencia, promedios y porcentajes incorrectos.
- Datos de prueba mezclados con datos productivos.
- Daño a equipos, derrames o afectación de animales durante pruebas físicas.
- Falta de respuesta o degradación de la plataforma con muchos registros.

## 7. Datos que deberían utilizarse

Los datos deben identificarse como pruebas y mantenerse separados de producción.

- Lecturas ambientales normales, en el límite, fuera de rango, cero, negativas si el protocolo las permite y ausentes.
- Lecturas repetidas para comprobar estabilidad, orden temporal y duplicados.
- Pesos inferiores, iguales y superiores a los umbrales configurados, incluyendo valores decimales y valores no válidos.
- Escenarios de peso equivalente a cero huevos, un huevo, varios huevos, un maple completo y excedentes.
- Niveles de alimento y agua por encima de cero, exactamente en cero y por debajo de cero.
- Órdenes válidas, repetidas, simultáneas y enviadas con actuador desconectado.
- Credenciales Wi-Fi correctas e incorrectas y cortes durante la transmisión.
- Tokens válidos, inválidos, ausentes y expirados, si existe expiración.
- Solicitudes API con campos faltantes, tipos incorrectos, valores extremos y dispositivo desconocido.
- Registros de varios días, cambios de mes, zona horaria y ausencia total de datos.
- Usuarios con roles definidos, correos duplicados, contraseñas cortas y confirmaciones distintas.
- Lotes con código duplicado, cantidades mínimas, cero aves actuales, estados permitidos e inválidos.

Los valores numéricos de aceptación, tolerancias y tiempos deben ser proporcionados por el responsable técnico o funcional; no deben inferirse únicamente de los valores de respaldo mostrados por la interfaz.

## 8. Tipos de pruebas recomendados

- **Unitarias:** conversiones de peso, cálculos de cantidades, porcentajes, promedios, límites y validaciones.
- **Funcionales:** alimentación, hidratación, monitoreo, conteo, vistas, acciones y reportes aprobados.
- **Integración:** ESP32-sensores-actuadores, ESP32-API, API-MySQL y API-interfaz web.
- **Hardware y control:** calibración, lectura, activación, detención y fallos físicos controlados.
- **Contrato de API:** autenticación, payloads, códigos HTTP, respuestas y errores.
- **Persistencia:** creación, consistencia, duplicación, orden, fechas, consultas e historial.
- **Autorización y seguridad:** sesiones, roles, acceso directo a URLs, token, validación de entradas y CSRF en operaciones web.
- **Interfaz y usabilidad:** legibilidad, estados vacíos, errores, formularios y adaptación al dispositivo confirmado.
- **Rendimiento:** tiempos de recepción, consulta y visualización; carga de lecturas y concurrencia.
- **Resiliencia:** Wi-Fi intermitente, reinicio del ESP32, API no disponible y recuperación.
- **Regresión:** repetición de pruebas afectadas por cambios en hardware, firmware, Laravel o base de datos.
- **Aceptación:** validación integral con el productor o usuario responsable, según criterios aprobados.

## 9. Dependencias entre módulos

1. Sensores y celdas de carga dependen de alimentación, cableado, calibración y firmware del ESP32.
2. Los actuadores dependen del ESP32, la lógica de control y la disponibilidad física de alimento o agua.
3. La comunicación depende de la configuración Wi-Fi, el token, la disponibilidad de la API y la red.
4. La API depende de validaciones Laravel, modelos, migraciones y disponibilidad de MySQL.
5. El dashboard y los sectores dependen de registros persistidos y de valores iniciales definidos.
6. Los reportes dependen de marcas de tiempo, filtros, registros de clima, producción, despachos y lotes.
7. Los cálculos de consumo y eficiencia dependen de la identificación correcta de acciones y de la población activa.
8. Las pruebas de aceptación dependen de que hardware, firmware, backend, base de datos y ambiente estén integrados.

## 10. Funcionalidades con mayor probabilidad de error

- Conversión del peso acumulado en cantidad de huevos y su posible doble conteo.
- Límites ambientales y diferencia entre lectura válida, cero y sensor desconectado.
- Sincronización de órdenes almacenadas temporalmente y confirmación de ejecución física.
- Despachos concurrentes o repetidos que actualicen dos veces el nivel.
- Cálculo de porcentajes cuando la capacidad, población o cantidad es cero.
- Reportes por día debido a zona horaria, límites de fecha y registros recientes.
- Diferencia entre capacidad configurada, valor inicial mostrado y capacidad real del depósito.
- Historiales que mezclen datos del ESP32 con acciones web de recarga o despacho.
- Autorización por rol en rutas protegidas y acceso directo a endpoints.
- Validación de entradas y exposición de mensajes de error.
- Estado de la interfaz cuando todavía no existen lecturas o registros.
- Actualización en tiempo real: la frecuencia y el mecanismo no están especificados.

## 11. Información que todavía debe proporcionar el proyecto

Para elaborar correctamente el plan detallado, la matriz de trazabilidad y los casos de prueba finales se necesita:

1. Lista numerada de requisitos funcionales y no funcionales.
2. Reglas de negocio aprobadas, incluyendo umbrales, unidades, tolerancias y capacidades.
3. Diagrama de arquitectura y flujo de datos entre ESP32, API, Laravel, MySQL e interfaz.
4. Documentación del firmware, pines, sensores, actuadores, calibración y estados de error.
5. Contrato de la API: autenticación, payloads, respuestas, reintentos y códigos de error.
6. Definición de usuarios, roles, permisos y acciones permitidas por rol.
7. Confirmación de si usuarios, lotes, sectores y reportes son parte del alcance oficial.
8. Definición de monitoreo “en tiempo real”, frecuencia de actualización y latencia máxima.
9. Navegadores, tamaños de pantalla y confirmación de soporte móvil/responsive.
10. Volumen esperado de datos, dispositivos concurrentes y objetivos de rendimiento.
11. Política de respaldo, recuperación, retención y eliminación de datos.
12. Ambiente de pruebas, versiones de PHP/Laravel/MySQL, firmware y sistema operativo.
13. Datos de prueba autorizados y procedimiento para simular fallas físicas sin riesgo.
14. Criterios de entrada, salida, severidad, prioridad y responsables de aprobar defectos.
15. Evidencias requeridas: capturas, logs, fotografías, consultas SQL, mediciones y trazas.
16. Criterios de aceptación del productor o responsable de la granja.

Hasta recibir esta información, los resultados de prueba solo podrán evaluarse contra la descripción disponible y las reglas observadas en el código. Las reglas observadas deben confirmarse antes de tratarlas como requisitos oficiales.