# Especificación de Casos de Prueba

## Sistema

**SISTEMA DE CONTROL AUTOMATIZADO DE GRANJAS DE CODORNIZ PARA LA GESTIÓN DE ALIMENTACIÓN, HIDRATACIÓN, TEMPERATURA Y CONTEO DE HUEVOS EN LA COMUNIDAD DE TUSCAPUJYO**

## Propósito

Este documento especifica los casos de prueba manuales para verificar las funcionalidades del sistema de control automatizado y gestión digital. Los casos cubren el control central con ESP32, la alimentación e hidratación, el monitoreo ambiental, el conteo de inventario, la plataforma web y la integración de datos mediante Wi-Fi.

Los casos deben ejecutarse en un ambiente de pruebas independiente de los datos reales de producción. Los campos **Resultado obtenido**, **Estado**, **Evidencia** y **Observaciones** deberán completarse o actualizarse durante la ejecución, según corresponda. Debido a que las pruebas aún no se han ejecutado, todos los casos comienzan con **Resultado obtenido** vacío y **Estado: PENDIENTE**.

## Convenciones

- **Prioridad Alta:** una falla afecta una función principal o la integración del sistema.
- **Prioridad Media:** una falla afecta una función relevante, pero no detiene toda la operación.
- **Prioridad Baja:** una falla afecta una condición secundaria o de presentación.
- Cuando el caso requiera un umbral, límite o equivalencia de peso, se utilizará el valor configurado y aprobado para el sistema; este documento no inventa valores técnicos.

---

## Módulo 1. Control central (ESP32)

### CP-001. Inicialización del control central

- **ID del caso:** CP-001
- **Requisito relacionado:** El ESP32 debe actuar como módulo central para orquestar sensores y actuadores.
- **Módulo:** Control central (ESP32)
- **Nombre del caso:** Inicialización correcta del ESP32
- **Objetivo:** Verificar que el ESP32 inicie y quede disponible para controlar los módulos conectados.
- **Prioridad:** Alta
- **Precondiciones:** ESP32 disponible; sensores y actuadores conectados; ambiente de pruebas preparado.
- **Datos de entrada:** Alimentación del ESP32 y conexiones configuradas.
- **Pasos de ejecución:**
  1. Conectar el ESP32 y los componentes definidos para la prueba.
  2. Encender el sistema.
  3. Observar el comportamiento de inicialización del ESP32.
  4. Verificar que el sistema quede disponible para recibir lecturas y ejecutar acciones.
- **Resultado esperado:** El ESP32 inicia sin interrupciones observables y queda disponible para coordinar sensores, actuadores y comunicación.
- **Resultado obtenido:**
- **Estado:** PENDIENTE
- **Evidencia:**
- **Observaciones:**

### CP-002. Orquestación de sensores y actuadores

- **ID del caso:** CP-002
- **Requisito relacionado:** El ESP32 debe orquestar sensores y actuadores.
- **Módulo:** Control central (ESP32)
- **Nombre del caso:** Coordinación de una lectura con una acción del sistema
- **Objetivo:** Verificar que el ESP32 reciba una lectura y coordine el actuador correspondiente conforme a la lógica configurada.
- **Prioridad:** Alta
- **Precondiciones:** ESP32 iniciado; sensores y actuadores conectados; condición de prueba definida.
- **Datos de entrada:** Lectura de sensor que corresponda a una acción configurada.
- **Pasos de ejecución:**
  1. Iniciar el ESP32.
  2. Generar o aplicar la condición de prueba en el sensor.
  3. Observar la lectura recibida por el ESP32.
  4. Observar el actuador relacionado.
  5. Registrar la secuencia de lectura y acción.
- **Resultado esperado:** El ESP32 recibe la lectura y ejecuta la acción del actuador que corresponda a la configuración del sistema.
- **Resultado obtenido:**
- **Estado:** PENDIENTE
- **Evidencia:**
- **Observaciones:**

### CP-003. Conexión del ESP32 a la red Wi-Fi

- **ID del caso:** CP-003
- **Requisito relacionado:** El ESP32 debe comunicarse con la plataforma web mediante Wi-Fi.
- **Módulo:** Control central (ESP32)
- **Nombre del caso:** Conexión Wi-Fi con credenciales válidas
- **Objetivo:** Verificar que el ESP32 pueda conectarse a la red Wi-Fi disponible.
- **Prioridad:** Alta
- **Precondiciones:** Red Wi-Fi disponible; credenciales de prueba configuradas; ESP32 operativo.
- **Datos de entrada:** Nombre de red y credenciales válidas de la red Wi-Fi.
- **Pasos de ejecución:**
  1. Configurar en el ESP32 los datos de la red Wi-Fi de pruebas.
  2. Encender o reiniciar el ESP32.
  3. Esperar el proceso de conexión.
  4. Verificar el estado de conexión del ESP32.
- **Resultado esperado:** El ESP32 establece la conexión con la red Wi-Fi y queda disponible para intercambiar información con la plataforma web.
- **Resultado obtenido:**
- **Estado:** PENDIENTE
- **Evidencia:**
- **Observaciones:**

### CP-004. Conexión Wi-Fi con datos inválidos

- **ID del caso:** CP-004
- **Requisito relacionado:** La comunicación entre ESP32 y plataforma web debe depender de una conexión Wi-Fi válida.
- **Módulo:** Control central (ESP32)
- **Nombre del caso:** Rechazo o gestión de credenciales Wi-Fi inválidas
- **Objetivo:** Verificar el comportamiento del ESP32 cuando se configuran datos Wi-Fi inválidos.
- **Prioridad:** Alta
- **Precondiciones:** ESP32 operativo; red Wi-Fi disponible; posibilidad de modificar temporalmente la configuración de prueba.
- **Datos de entrada:** Nombre de red o credenciales incorrectas.
- **Pasos de ejecución:**
  1. Configurar datos Wi-Fi incorrectos en el ESP32.
  2. Reiniciar el ESP32.
  3. Observar el proceso de conexión.
  4. Verificar si el sistema identifica o mantiene la condición de falta de conexión.
- **Resultado esperado:** El ESP32 no establece una conexión con credenciales inválidas y la condición queda identificable para la prueba; no debe registrarse una conexión inexistente como exitosa.
- **Resultado obtenido:**
- **Estado:** PENDIENTE
- **Evidencia:**
- **Observaciones:**

### CP-005. Operación con comunicación Wi-Fi no disponible

- **ID del caso:** CP-005
- **Requisito relacionado:** Integración de datos entre el ESP32 y la plataforma web mediante Wi-Fi.
- **Módulo:** Control central (ESP32)
- **Nombre del caso:** Gestión de pérdida de conectividad
- **Objetivo:** Verificar el comportamiento del sistema cuando la red Wi-Fi deja de estar disponible durante la operación.
- **Prioridad:** Alta
- **Precondiciones:** ESP32 conectado a la red Wi-Fi; plataforma web disponible; prueba controlada.
- **Datos de entrada:** Desconexión controlada de la red Wi-Fi durante una lectura o acción.
- **Pasos de ejecución:**
  1. Confirmar que el ESP32 esté conectado.
  2. Ejecutar una lectura o acción del sistema.
  3. Interrumpir de forma controlada la disponibilidad de la red Wi-Fi.
  4. Observar el ESP32 y la plataforma web.
  5. Registrar qué información se transmite, rechaza o queda pendiente, según el comportamiento implementado.
- **Resultado esperado:** El sistema identifica la falta de conectividad y no presenta como recibido en la plataforma un dato que no haya sido transmitido correctamente.
- **Resultado obtenido:**
- **Estado:** PENDIENTE
- **Evidencia:**
- **Observaciones:**

---

## Módulo 2. Alimentación e hidratación

### CP-006. Suministro de alimento mediante servomotor

- **ID del caso:** CP-006
- **Requisito relacionado:** El sistema debe suministrar alimento mediante servomotores de forma controlada.
- **Módulo:** Alimentación e hidratación
- **Nombre del caso:** Activación correcta del servomotor de alimentación
- **Objetivo:** Verificar que el servomotor ejecute la acción de suministro de alimento configurada.
- **Prioridad:** Alta
- **Precondiciones:** ESP32 operativo; servomotor instalado; mecanismo de alimentación preparado; condición de activación disponible.
- **Datos de entrada:** Orden o condición configurada para suministrar alimento.
- **Pasos de ejecución:**
  1. Verificar que el servomotor esté conectado al ESP32.
  2. Iniciar el sistema.
  3. Generar la orden o condición de suministro.
  4. Observar el movimiento del servomotor.
  5. Verificar que la acción corresponda al suministro de alimento.
- **Resultado esperado:** El servomotor se activa de acuerdo con la configuración y permite el suministro controlado de alimento.
- **Resultado obtenido:**
- **Estado:** PENDIENTE
- **Evidencia:**
- **Observaciones:**

### CP-007. Suministro de agua mediante mini-bomba

- **ID del caso:** CP-007
- **Requisito relacionado:** El sistema debe suministrar hidratación mediante mini-bombas de agua de forma controlada.
- **Módulo:** Alimentación e hidratación
- **Nombre del caso:** Activación correcta de la mini-bomba de agua
- **Objetivo:** Verificar que la mini-bomba se active ante la orden o condición configurada para hidratación.
- **Prioridad:** Alta
- **Precondiciones:** ESP32 operativo; mini-bomba conectada; suministro de agua disponible; circuito preparado.
- **Datos de entrada:** Orden o condición configurada para suministrar agua.
- **Pasos de ejecución:**
  1. Verificar la conexión de la mini-bomba.
  2. Iniciar el sistema.
  3. Generar la orden o condición de hidratación.
  4. Observar la activación de la mini-bomba.
  5. Verificar que se realice el suministro de agua.
- **Resultado esperado:** La mini-bomba se activa conforme a la configuración y realiza el suministro controlado de agua.
- **Resultado obtenido:**
- **Estado:** PENDIENTE
- **Evidencia:**
- **Observaciones:**

### CP-008. Desactivación de actuadores al finalizar la operación

- **ID del caso:** CP-008
- **Requisito relacionado:** El abastecimiento debe ser constante y controlado mediante actuadores programados.
- **Módulo:** Alimentación e hidratación
- **Nombre del caso:** Finalización controlada de alimentación o hidratación
- **Objetivo:** Verificar que el actuador deje de operar cuando finalice la condición o acción configurada.
- **Prioridad:** Alta
- **Precondiciones:** Servomotor o mini-bomba conectado y activo; condición de finalización definida por la configuración del sistema.
- **Datos de entrada:** Fin de la orden o condición de alimentación/hidratación.
- **Pasos de ejecución:**
  1. Activar el suministro de alimento o agua.
  2. Esperar o provocar el fin de la condición configurada.
  3. Observar el actuador.
  4. Verificar el estado del suministro después de la finalización.
- **Resultado esperado:** El actuador finaliza la operación conforme a la configuración y el suministro no continúa de forma indefinida.
- **Resultado obtenido:**
- **Estado:** PENDIENTE
- **Evidencia:**
- **Observaciones:**

### CP-009. Orden de alimentación sin actuador disponible

- **ID del caso:** CP-009
- **Requisito relacionado:** El sistema debe controlar los actuadores del módulo de alimentación.
- **Módulo:** Alimentación e hidratación
- **Nombre del caso:** Gestión de una operación incorrecta por servomotor no disponible
- **Objetivo:** Verificar que el sistema gestione una orden cuando el servomotor no esté conectado o disponible.
- **Prioridad:** Alta
- **Precondiciones:** ESP32 operativo; servomotor desconectado de forma controlada; ambiente de pruebas preparado.
- **Datos de entrada:** Orden o condición de suministro de alimento.
- **Pasos de ejecución:**
  1. Confirmar que el servomotor esté desconectado o no disponible.
  2. Iniciar el ESP32.
  3. Generar la orden de alimentación.
  4. Observar el comportamiento del sistema.
  5. Registrar la indicación o condición de error, si existe.
- **Resultado esperado:** El sistema no debe confirmar una activación física que no ocurrió y debe permitir identificar la operación incorrecta o la ausencia del actuador.
- **Resultado obtenido:**
- **Estado:** PENDIENTE
- **Evidencia:**
- **Observaciones:**

### CP-010. Orden de hidratación sin agua disponible

- **ID del caso:** CP-010
- **Requisito relacionado:** El sistema debe controlar el suministro de hidratación mediante mini-bombas.
- **Módulo:** Alimentación e hidratación
- **Nombre del caso:** Gestión de una operación incorrecta por falta de agua
- **Objetivo:** Verificar el comportamiento del módulo cuando se genera una orden de hidratación sin suministro de agua disponible.
- **Prioridad:** Alta
- **Precondiciones:** Mini-bomba instalada; depósito o fuente de agua no disponible de forma controlada; ESP32 operativo.
- **Datos de entrada:** Orden o condición de hidratación.
- **Pasos de ejecución:**
  1. Preparar la mini-bomba sin disponibilidad de agua.
  2. Iniciar el sistema.
  3. Generar la orden de hidratación.
  4. Observar la mini-bomba y la plataforma, si la operación se transmite.
  5. Registrar si el sistema diferencia la orden de la confirmación del suministro.
- **Resultado esperado:** El sistema no debe afirmar que se suministró agua si la condición física no permite realizar el suministro; la incidencia debe quedar identificable según las capacidades implementadas.
- **Resultado obtenido:**
- **Estado:** PENDIENTE
- **Evidencia:**
- **Observaciones:**

---

## Módulo 3. Monitoreo ambiental

### CP-011. Lectura de humedad y temperatura

- **ID del caso:** CP-011
- **Requisito relacionado:** El sistema debe monitorear constantemente la humedad y la temperatura.
- **Módulo:** Monitoreo ambiental
- **Nombre del caso:** Obtención de lecturas ambientales válidas
- **Objetivo:** Verificar que los sensores proporcionen lecturas de humedad y temperatura al sistema.
- **Prioridad:** Alta
- **Precondiciones:** Sensores conectados al ESP32; ESP32 operativo; condiciones ambientales disponibles.
- **Datos de entrada:** Condiciones actuales de humedad y temperatura del ambiente de prueba.
- **Pasos de ejecución:**
  1. Encender el ESP32 con los sensores conectados.
  2. Esperar la lectura de los sensores.
  3. Registrar las lecturas observadas.
  4. Verificar que correspondan a humedad y temperatura.
  5. Comprobar su disponibilidad para la plataforma web, si la conexión está activa.
- **Resultado esperado:** El sistema obtiene y diferencia lecturas de humedad y temperatura, y las deja disponibles para su procesamiento o transmisión.
- **Resultado obtenido:**
- **Estado:** PENDIENTE
- **Evidencia:**
- **Observaciones:**

### CP-012. Acción correctiva por condición ambiental inferior

- **ID del caso:** CP-012
- **Requisito relacionado:** El sistema debe ejecutar acciones correctivas automáticas para regular el ambiente.
- **Módulo:** Monitoreo ambiental
- **Nombre del caso:** Respuesta automática ante el límite inferior configurado
- **Objetivo:** Verificar la acción correctiva cuando la humedad o temperatura alcance el límite inferior configurado.
- **Prioridad:** Alta
- **Precondiciones:** Sensor conectado; ESP32 operativo; límite inferior y acción correctiva configurados por el sistema; actuador relacionado disponible.
- **Datos de entrada:** Lectura igual o inferior al límite inferior configurado.
- **Pasos de ejecución:**
  1. Confirmar el límite inferior configurado para la variable de prueba.
  2. Aplicar o simular una condición que produzca una lectura igual o inferior a dicho límite.
  3. Verificar la lectura recibida por el ESP32.
  4. Observar la acción correctiva automática.
  5. Registrar la lectura y la acción ejecutada.
- **Resultado esperado:** El ESP32 identifica la condición ambiental y ejecuta la acción correctiva definida para el límite inferior configurado.
- **Resultado obtenido:**
- **Estado:** PENDIENTE
- **Evidencia:**
- **Observaciones:**

### CP-013. Acción correctiva por condición ambiental superior

- **ID del caso:** CP-013
- **Requisito relacionado:** El sistema debe ejecutar acciones correctivas automáticas para regular el ambiente.
- **Módulo:** Monitoreo ambiental
- **Nombre del caso:** Respuesta automática ante el límite superior configurado
- **Objetivo:** Verificar la acción correctiva cuando la humedad o temperatura alcance el límite superior configurado.
- **Prioridad:** Alta
- **Precondiciones:** Sensor conectado; ESP32 operativo; límite superior y acción correctiva configurados por el sistema; actuador relacionado disponible.
- **Datos de entrada:** Lectura igual o superior al límite superior configurado.
- **Pasos de ejecución:**
  1. Confirmar el límite superior configurado para la variable de prueba.
  2. Aplicar o simular una condición que produzca una lectura igual o superior a dicho límite.
  3. Verificar la lectura recibida por el ESP32.
  4. Observar la acción correctiva automática.
  5. Registrar la lectura y la acción ejecutada.
- **Resultado esperado:** El ESP32 identifica la condición ambiental y ejecuta la acción correctiva definida para el límite superior configurado.
- **Resultado obtenido:**
- **Estado:** PENDIENTE
- **Evidencia:**
- **Observaciones:**

### CP-014. Lectura ambiental en el límite configurado

- **ID del caso:** CP-014
- **Requisito relacionado:** Monitoreo constante y regulación automática de humedad y temperatura.
- **Módulo:** Monitoreo ambiental
- **Nombre del caso:** Tratamiento del valor límite de humedad o temperatura
- **Objetivo:** Verificar que el sistema trate correctamente una lectura exactamente igual al límite configurado.
- **Prioridad:** Alta
- **Precondiciones:** Sensor operativo; límite de la variable identificado; acción asociada conocida.
- **Datos de entrada:** Lectura exactamente igual al límite inferior o superior configurado.
- **Pasos de ejecución:**
  1. Seleccionar una variable ambiental y uno de sus límites configurados.
  2. Aplicar o simular la lectura exactamente igual al límite.
  3. Observar cómo clasifica la condición el ESP32.
  4. Verificar si ejecuta la acción asociada al límite según la regla implementada.
  5. Registrar la regla aplicada.
- **Resultado esperado:** El sistema aplica de manera consistente la regla definida para el valor exactamente igual al límite, sin clasificarlo de forma contradictoria.
- **Resultado obtenido:**
- **Estado:** PENDIENTE
- **Evidencia:**
- **Observaciones:**

### CP-015. Sensor ambiental desconectado o con lectura inválida

- **ID del caso:** CP-015
- **Requisito relacionado:** El sistema debe monitorear humedad y temperatura de forma constante.
- **Módulo:** Monitoreo ambiental
- **Nombre del caso:** Gestión de datos inválidos del sensor ambiental
- **Objetivo:** Verificar el comportamiento ante una lectura ausente, inválida o un sensor desconectado.
- **Prioridad:** Alta
- **Precondiciones:** ESP32 operativo; sensor seleccionado desconectado o preparado para producir una lectura inválida de manera controlada.
- **Datos de entrada:** Lectura ausente, incompleta o inválida.
- **Pasos de ejecución:**
  1. Desconectar el sensor o aplicar la condición de prueba definida.
  2. Iniciar o mantener operativo el ESP32.
  3. Esperar el ciclo de lectura.
  4. Observar el dato procesado y las acciones del sistema.
  5. Verificar que la lectura inválida no se almacene como un valor ambiental válido.
- **Resultado esperado:** El sistema identifica la lectura inválida o la ausencia del sensor y evita utilizarla como dato válido para regulación o historial.
- **Resultado obtenido:**
- **Estado:** PENDIENTE
- **Evidencia:**
- **Observaciones:**

---

## Módulo 4. Conteo de inventario

### CP-016. Lectura de peso mediante celda de carga y HX711

- **ID del caso:** CP-016
- **Requisito relacionado:** El sistema debe utilizar celdas de carga y HX711 para obtener el peso acumulado de la producción.
- **Módulo:** Conteo de inventario
- **Nombre del caso:** Obtención de una lectura de peso válida
- **Objetivo:** Verificar que la celda de carga y el HX711 entreguen al sistema una lectura de peso procesable.
- **Prioridad:** Alta
- **Precondiciones:** Celda de carga y HX711 conectados; ESP32 operativo; superficie de prueba preparada.
- **Datos de entrada:** Peso de prueba colocado sobre la celda de carga.
- **Pasos de ejecución:**
  1. Encender el sistema de lectura.
  2. Verificar que la celda de carga esté disponible.
  3. Colocar el peso de prueba.
  4. Esperar la lectura del HX711.
  5. Registrar el valor recibido por el sistema.
- **Resultado esperado:** El sistema recibe una lectura de peso desde la celda de carga mediante el HX711 y la deja disponible para el algoritmo de conteo.
- **Resultado obtenido:**
- **Estado:** PENDIENTE
- **Evidencia:**
- **Observaciones:**

### CP-017. Transformación del peso en unidades registrables

- **ID del caso:** CP-017
- **Requisito relacionado:** El algoritmo debe transformar el peso acumulado en unidades registrables para el conteo de huevos.
- **Módulo:** Conteo de inventario
- **Nombre del caso:** Conversión de peso acumulado a conteo de huevos
- **Objetivo:** Verificar que el sistema convierta el peso recibido en unidades registrables según la lógica configurada.
- **Prioridad:** Alta
- **Precondiciones:** Celda de carga y HX711 operativos; regla de conversión configurada; plataforma disponible para registrar el resultado.
- **Datos de entrada:** Peso acumulado que corresponda a una cantidad registrable según la regla del sistema.
- **Pasos de ejecución:**
  1. Obtener una lectura de peso mediante la celda de carga.
  2. Confirmar que la lectura fue recibida por el ESP32.
  3. Ejecutar el procesamiento del algoritmo de conteo.
  4. Observar la cantidad de unidades calculada.
  5. Verificar que el conteo quede disponible para su registro.
- **Resultado esperado:** El sistema transforma el peso acumulado en el número de unidades registrables conforme a la regla configurada y prepara el dato para la gestión de inventario.
- **Resultado obtenido:**
- **Estado:** PENDIENTE
- **Evidencia:**
- **Observaciones:**

### CP-018. Valor límite de peso para el conteo

- **ID del caso:** CP-018
- **Requisito relacionado:** Algoritmo de conteo basado en peso acumulado.
- **Módulo:** Conteo de inventario
- **Nombre del caso:** Procesamiento de un peso en el límite configurado
- **Objetivo:** Verificar el comportamiento del algoritmo ante el valor mínimo o máximo definido para una conversión de conteo.
- **Prioridad:** Alta
- **Precondiciones:** Celda de carga y HX711 operativos; límites o reglas de conversión definidos para la prueba.
- **Datos de entrada:** Peso exactamente igual al límite configurado.
- **Pasos de ejecución:**
  1. Identificar el límite de peso que se probará.
  2. Colocar o simular el peso correspondiente.
  3. Registrar la lectura obtenida.
  4. Ejecutar el algoritmo de conversión.
  5. Verificar el conteo generado y su tratamiento en el inventario.
- **Resultado esperado:** El sistema procesa el valor límite de acuerdo con la regla de conversión definida, sin generar un tratamiento ambiguo o inconsistente.
- **Resultado obtenido:**
- **Estado:** PENDIENTE
- **Evidencia:**
- **Observaciones:**

### CP-019. Lectura de peso inválida o no disponible

- **ID del caso:** CP-019
- **Requisito relacionado:** Uso de celdas de carga y HX711 para el conteo de huevos.
- **Módulo:** Conteo de inventario
- **Nombre del caso:** Rechazo de lectura inválida del HX711
- **Objetivo:** Verificar que una lectura inválida o no disponible no se convierta en un conteo válido.
- **Prioridad:** Alta
- **Precondiciones:** ESP32 operativo; celda de carga o HX711 desconectado o preparado para una lectura inválida controlada.
- **Datos de entrada:** Lectura ausente, inválida o no procesable.
- **Pasos de ejecución:**
  1. Desconectar o dejar no disponible el componente seleccionado.
  2. Solicitar una lectura de peso.
  3. Observar el dato recibido por el sistema.
  4. Verificar el comportamiento del algoritmo de conteo.
  5. Revisar si se generó un registro de inventario.
- **Resultado esperado:** El sistema identifica la lectura no procesable y no genera un conteo ni un registro de inventario basado en un dato inválido.
- **Resultado obtenido:**
- **Estado:** PENDIENTE
- **Evidencia:**
- **Observaciones:**

### CP-020. Registro duplicado de una misma lectura de producción

- **ID del caso:** CP-020
- **Requisito relacionado:** Conteo y gestión de inventario de huevos.
- **Módulo:** Conteo de inventario
- **Nombre del caso:** Tratamiento de datos duplicados de conteo
- **Objetivo:** Verificar que una misma lectura o evento de producción no se registre dos veces por una repetición de envío o de operación.
- **Prioridad:** Alta
- **Precondiciones:** Un conteo válido disponible; plataforma web y base de datos accesibles; mecanismo de repetición controlada.
- **Datos de entrada:** El mismo dato de conteo enviado o procesado dos veces.
- **Pasos de ejecución:**
  1. Generar un conteo válido y confirmar el dato de prueba.
  2. Enviar o registrar el mismo conteo una primera vez.
  3. Repetir la misma operación con los mismos datos.
  4. Consultar el inventario y el historial.
  5. Comparar los registros generados.
- **Resultado esperado:** El sistema aplica la regla definida para datos duplicados y evita incrementar o duplicar indebidamente el inventario por la misma lectura.
- **Resultado obtenido:**
- **Estado:** PENDIENTE
- **Evidencia:**
- **Observaciones:**

---

## Módulo 5. Plataforma web de gestión

### CP-021. Autenticación con datos válidos

- **ID del caso:** CP-021
- **Requisito relacionado:** La plataforma web debe proporcionar autenticación para la gestión digital.
- **Módulo:** Plataforma web de gestión
- **Nombre del caso:** Inicio de sesión con credenciales válidas
- **Objetivo:** Verificar que un usuario registrado pueda autenticarse con datos válidos.
- **Prioridad:** Alta
- **Precondiciones:** Plataforma Laravel disponible; usuario de prueba registrado; credenciales válidas disponibles.
- **Datos de entrada:** Credenciales válidas del usuario de prueba.
- **Pasos de ejecución:**
  1. Abrir la plataforma web.
  2. Acceder al formulario de autenticación.
  3. Completar los campos con las credenciales válidas.
  4. Enviar el formulario.
  5. Observar la pantalla resultante.
- **Resultado esperado:** La plataforma autentica al usuario y permite acceder a las funciones autorizadas de gestión.
- **Resultado obtenido:**
- **Estado:** PENDIENTE
- **Evidencia:**
- **Observaciones:**

### CP-022. Autenticación con credenciales inválidas

- **ID del caso:** CP-022
- **Requisito relacionado:** La plataforma web debe controlar el acceso mediante autenticación.
- **Módulo:** Plataforma web de gestión
- **Nombre del caso:** Rechazo de credenciales inválidas
- **Objetivo:** Verificar que la plataforma no permita el acceso con credenciales incorrectas.
- **Prioridad:** Alta
- **Precondiciones:** Plataforma web disponible; usuario de prueba existente o datos controlados para autenticación.
- **Datos de entrada:** Usuario o contraseña incorrectos.
- **Pasos de ejecución:**
  1. Abrir la pantalla de autenticación.
  2. Ingresar datos de acceso incorrectos.
  3. Enviar el formulario.
  4. Observar la respuesta de la plataforma.
  5. Intentar acceder a una pantalla protegida.
- **Resultado esperado:** La plataforma rechaza las credenciales y no permite acceder a las funciones protegidas.
- **Resultado obtenido:**
- **Estado:** PENDIENTE
- **Evidencia:**
- **Observaciones:**

### CP-023. Campos obligatorios de autenticación

- **ID del caso:** CP-023
- **Requisito relacionado:** Autenticación de usuarios en la plataforma web.
- **Módulo:** Plataforma web de gestión
- **Nombre del caso:** Validación de campos obligatorios en el inicio de sesión
- **Objetivo:** Verificar que el formulario no procese una autenticación cuando falten datos obligatorios.
- **Prioridad:** Alta
- **Precondiciones:** Plataforma web disponible; formulario de autenticación accesible.
- **Datos de entrada:** Formulario vacío o con uno de los campos de autenticación vacío.
- **Pasos de ejecución:**
  1. Abrir el formulario de autenticación.
  2. Dejar vacíos todos los campos o uno de ellos.
  3. Seleccionar la opción de envío.
  4. Observar las validaciones mostradas.
  5. Verificar que no se cree una sesión autenticada.
- **Resultado esperado:** La plataforma identifica los campos obligatorios faltantes, informa la validación correspondiente y no autentica al usuario.
- **Resultado obtenido:**
- **Estado:** PENDIENTE
- **Evidencia:**
- **Observaciones:**

### CP-024. Registro de usuario con datos válidos

- **ID del caso:** CP-024
- **Requisito relacionado:** La plataforma web debe permitir el registro y la gestión de usuarios, según las funciones implementadas.
- **Módulo:** Plataforma web de gestión
- **Nombre del caso:** Registro de un usuario con datos válidos
- **Objetivo:** Verificar que el formulario de registro procese datos válidos y cree un usuario disponible para autenticación.
- **Prioridad:** Alta
- **Precondiciones:** Plataforma disponible; pantalla de registro accesible; datos de prueba no utilizados previamente.
- **Datos de entrada:** Datos válidos para todos los campos obligatorios del registro y un identificador no utilizado.
- **Pasos de ejecución:**
  1. Abrir el formulario de registro.
  2. Completar todos los campos obligatorios con datos válidos.
  3. Enviar el formulario.
  4. Verificar la respuesta de la plataforma.
  5. Intentar autenticarse con el usuario registrado.
- **Resultado esperado:** La plataforma registra el usuario y permite su autenticación conforme a los permisos asignados.
- **Resultado obtenido:**
- **Estado:** PENDIENTE
- **Evidencia:**
- **Observaciones:**

### CP-025. Registro con campos obligatorios vacíos

- **ID del caso:** CP-025
- **Requisito relacionado:** Registro de usuarios en la plataforma web.
- **Módulo:** Plataforma web de gestión
- **Nombre del caso:** Validación de campos obligatorios en el registro
- **Objetivo:** Verificar que no se registre un usuario cuando falten campos obligatorios.
- **Prioridad:** Alta
- **Precondiciones:** Plataforma disponible; formulario de registro accesible.
- **Datos de entrada:** Formulario vacío o con uno o más campos obligatorios sin información.
- **Pasos de ejecución:**
  1. Abrir el formulario de registro.
  2. Dejar vacío al menos un campo obligatorio.
  3. Completar los demás campos con datos de prueba.
  4. Enviar el formulario.
  5. Consultar la respuesta y verificar si se creó el usuario.
- **Resultado esperado:** La plataforma informa los campos obligatorios faltantes y no crea el registro incompleto.
- **Resultado obtenido:**
- **Estado:** PENDIENTE
- **Evidencia:**
- **Observaciones:**

### CP-026. Registro con datos inválidos

- **ID del caso:** CP-026
- **Requisito relacionado:** Registro y gestión de usuarios en la plataforma web.
- **Módulo:** Plataforma web de gestión
- **Nombre del caso:** Rechazo de datos inválidos en el registro
- **Objetivo:** Verificar que la plataforma valide el formato de los datos del registro.
- **Prioridad:** Alta
- **Precondiciones:** Plataforma disponible; formulario de registro accesible.
- **Datos de entrada:** Datos con formato inválido para uno o más campos del formulario.
- **Pasos de ejecución:**
  1. Abrir el formulario de registro.
  2. Completar los campos obligatorios.
  3. Introducir datos con formato inválido en un campo que tenga validación.
  4. Enviar el formulario.
  5. Verificar la respuesta y consultar si se creó el registro.
- **Resultado esperado:** La plataforma identifica los datos inválidos, informa la validación correspondiente y no almacena un registro incorrecto.
- **Resultado obtenido:**
- **Estado:** PENDIENTE
- **Evidencia:**
- **Observaciones:**

### CP-027. Registro de usuario duplicado

- **ID del caso:** CP-027
- **Requisito relacionado:** Registro de usuarios y persistencia en MySQL.
- **Módulo:** Plataforma web de gestión
- **Nombre del caso:** Rechazo de un registro duplicado
- **Objetivo:** Verificar que no se creen registros duplicados para un identificador de usuario ya registrado.
- **Prioridad:** Alta
- **Precondiciones:** Plataforma y MySQL disponibles; existe un usuario de prueba registrado.
- **Datos de entrada:** Datos de un usuario cuyo identificador ya existe.
- **Pasos de ejecución:**
  1. Confirmar la existencia del usuario de prueba.
  2. Abrir el formulario de registro.
  3. Ingresar nuevamente el identificador existente.
  4. Completar los demás datos solicitados.
  5. Enviar el formulario.
  6. Consultar el registro en la plataforma o en MySQL según el procedimiento autorizado.
- **Resultado esperado:** La plataforma rechaza el duplicado y conserva un único registro para el identificador utilizado.
- **Resultado obtenido:**
- **Estado:** PENDIENTE
- **Evidencia:**
- **Observaciones:**

### CP-028. Acceso según permisos y roles

- **ID del caso:** CP-028
- **Requisito relacionado:** La plataforma debe controlar el acceso a las funciones según la autenticación, los permisos y los roles configurados.
- **Módulo:** Plataforma web de gestión
- **Nombre del caso:** Acceso autorizado a una función de gestión
- **Objetivo:** Verificar que un usuario con el permiso o rol configurado pueda acceder a la función correspondiente.
- **Prioridad:** Alta
- **Precondiciones:** Usuario de prueba autenticable; permiso o rol de acceso configurado; plataforma disponible.
- **Datos de entrada:** Credenciales de un usuario con autorización para la función seleccionada.
- **Pasos de ejecución:**
  1. Iniciar sesión con el usuario autorizado.
  2. Navegar hacia la función de gestión asignada.
  3. Intentar consultar o ejecutar la operación permitida.
  4. Observar la respuesta de la plataforma.
- **Resultado esperado:** El usuario accede únicamente a la función para la que tiene permiso o rol configurado y puede ejecutar la operación autorizada.
- **Resultado obtenido:**
- **Estado:** PENDIENTE
- **Evidencia:**
- **Observaciones:**

### CP-029. Bloqueo de acceso no autorizado

- **ID del caso:** CP-029
- **Requisito relacionado:** Control de permisos y roles de la plataforma web.
- **Módulo:** Plataforma web de gestión
- **Nombre del caso:** Restricción de una función sin autorización
- **Objetivo:** Verificar que un usuario sin el permiso o rol requerido no pueda acceder a una función protegida.
- **Prioridad:** Alta
- **Precondiciones:** Usuario de prueba sin autorización para la función; plataforma disponible; sesión iniciada o acceso directo controlado.
- **Datos de entrada:** Credenciales de un usuario sin el permiso o rol requerido.
- **Pasos de ejecución:**
  1. Iniciar sesión con el usuario no autorizado.
  2. Intentar acceder desde la navegación a la función protegida.
  3. Intentar acceder mediante la dirección directa de la función, si aplica.
  4. Observar la respuesta de la plataforma.
- **Resultado esperado:** La plataforma bloquea el acceso y no permite ejecutar ni consultar la función no autorizada.
- **Resultado obtenido:**
- **Estado:** PENDIENTE
- **Evidencia:**
- **Observaciones:**

### CP-030. Navegación entre funciones de la plataforma

- **ID del caso:** CP-030
- **Requisito relacionado:** La plataforma web debe permitir el monitoreo y la gestión digital de la información.
- **Módulo:** Plataforma web de gestión
- **Nombre del caso:** Navegación correcta por las pantallas autorizadas
- **Objetivo:** Verificar que un usuario autenticado pueda desplazarse entre las funciones disponibles sin enlaces incorrectos ni pérdida indebida de sesión.
- **Prioridad:** Media
- **Precondiciones:** Usuario autenticado; plataforma web disponible; funciones autorizadas accesibles.
- **Datos de entrada:** Selección de las opciones de navegación disponibles.
- **Pasos de ejecución:**
  1. Iniciar sesión.
  2. Acceder a la pantalla de monitoreo.
  3. Navegar hacia la vista de registros o historial.
  4. Regresar a la vista anterior mediante las opciones disponibles.
  5. Verificar que cada pantalla corresponda a la opción seleccionada.
- **Resultado esperado:** La navegación dirige a las pantallas correspondientes, conserva el acceso autorizado y no presenta rutas inexistentes o contenido de otra función.
- **Resultado obtenido:**
- **Estado:** PENDIENTE
- **Evidencia:**
- **Observaciones:**

### CP-031. Visualización de datos en tiempo real

- **ID del caso:** CP-031
- **Requisito relacionado:** La plataforma web debe permitir el monitoreo en tiempo real de la granja.
- **Módulo:** Plataforma web de gestión
- **Nombre del caso:** Visualización de lecturas actuales y eventos recibidos
- **Objetivo:** Verificar que la plataforma muestre la información recibida del sistema de control.
- **Prioridad:** Alta
- **Precondiciones:** ESP32 conectado por Wi-Fi; plataforma web autenticada; sensores o actuadores disponibles.
- **Datos de entrada:** Nueva lectura ambiental o evento de alimentación, hidratación o conteo enviado por el ESP32.
- **Pasos de ejecución:**
  1. Iniciar sesión en la plataforma.
  2. Abrir la vista de monitoreo.
  3. Generar una lectura o evento en el ESP32.
  4. Esperar la comunicación con la plataforma.
  5. Verificar la información mostrada y compararla con el dato transmitido.
- **Resultado esperado:** La plataforma recibe y muestra el dato o evento correspondiente para el monitoreo del sistema.
- **Resultado obtenido:**
- **Estado:** PENDIENTE
- **Evidencia:**
- **Observaciones:**

### CP-032. Consulta del historial de datos

- **ID del caso:** CP-032
- **Requisito relacionado:** La plataforma debe almacenar y mostrar datos históricos de producción y variables ambientales.
- **Módulo:** Plataforma web de gestión
- **Nombre del caso:** Consulta de información histórica
- **Objetivo:** Verificar que un usuario autorizado pueda consultar registros históricos previamente almacenados.
- **Prioridad:** Alta
- **Precondiciones:** Existen registros de prueba almacenados; usuario con permiso de consulta; plataforma y MySQL disponibles.
- **Datos de entrada:** Solicitud de consulta de registros históricos.
- **Pasos de ejecución:**
  1. Iniciar sesión con un usuario autorizado.
  2. Abrir la sección de historial.
  3. Solicitar la consulta de los registros de prueba.
  4. Revisar los datos de producción y variables ambientales mostrados.
  5. Comparar la información con los datos registrados durante la prueba.
- **Resultado esperado:** La plataforma muestra los registros históricos almacenados y permite distinguir la información de producción de las variables ambientales, según la presentación implementada.
- **Resultado obtenido:**
- **Estado:** PENDIENTE
- **Evidencia:**
- **Observaciones:**

### CP-033. Persistencia de datos recibidos

- **ID del caso:** CP-033
- **Requisito relacionado:** La plataforma debe almacenar datos históricos de producción y variables ambientales en MySQL.
- **Módulo:** Plataforma web de gestión
- **Nombre del caso:** Almacenamiento y recuperación de un dato del sistema
- **Objetivo:** Verificar que un dato recibido desde el ESP32 se almacene en MySQL y pueda recuperarse desde la plataforma.
- **Prioridad:** Alta
- **Precondiciones:** ESP32 conectado; plataforma Laravel operativa; MySQL disponible; usuario autorizado.
- **Datos de entrada:** Lectura o evento de prueba transmitido por el ESP32.
- **Pasos de ejecución:**
  1. Generar una lectura o evento identificable en el ESP32.
  2. Confirmar que el evento sea transmitido a la plataforma.
  3. Consultar el historial desde la plataforma.
  4. Verificar el registro persistido en MySQL mediante el procedimiento autorizado.
  5. Comparar el dato transmitido, el almacenado y el mostrado.
- **Resultado esperado:** El dato se almacena en MySQL y se recupera en la plataforma sin pérdida ni transformación no prevista.
- **Resultado obtenido:**
- **Estado:** PENDIENTE
- **Evidencia:**
- **Observaciones:**

### CP-034. Persistencia ante datos obligatorios faltantes

- **ID del caso:** CP-034
- **Requisito relacionado:** Registro y almacenamiento de datos del sistema.
- **Módulo:** Plataforma web de gestión
- **Nombre del caso:** Rechazo de registro incompleto en la persistencia
- **Objetivo:** Verificar que Laravel y MySQL no almacenen un registro cuando falte un dato obligatorio.
- **Prioridad:** Alta
- **Precondiciones:** Plataforma Laravel y MySQL disponibles; usuario autorizado; operación de registro accesible.
- **Datos de entrada:** Registro de producción o variable ambiental con uno o más campos obligatorios ausentes.
- **Pasos de ejecución:**
  1. Abrir la función de registro correspondiente.
  2. Ingresar los datos disponibles y dejar vacío un campo obligatorio.
  3. Intentar guardar el registro.
  4. Observar la validación de Laravel.
  5. Consultar MySQL para verificar que no se haya almacenado el registro incompleto.
- **Resultado esperado:** La plataforma rechaza el registro, informa el dato faltante y no persiste información incompleta en MySQL.
- **Resultado obtenido:**
- **Estado:** PENDIENTE
- **Evidencia:**
- **Observaciones:**

### CP-035. Persistencia de registro duplicado

- **ID del caso:** CP-035
- **Requisito relacionado:** Almacenamiento de datos históricos y gestión de inventario.
- **Módulo:** Plataforma web de gestión
- **Nombre del caso:** Prevención de duplicidad en un registro ya almacenado
- **Objetivo:** Verificar que un mismo evento o registro no se almacene dos veces cuando la operación se repita.
- **Prioridad:** Alta
- **Precondiciones:** Registro de prueba existente en MySQL; operación de repetición controlada; usuario autorizado.
- **Datos de entrada:** Mismos datos identificadores del registro existente.
- **Pasos de ejecución:**
  1. Confirmar el registro original en la plataforma o MySQL.
  2. Repetir el envío o guardado con los mismos datos.
  3. Observar la respuesta de Laravel.
  4. Consultar nuevamente el historial.
  5. Verificar la cantidad de registros almacenados.
- **Resultado esperado:** El sistema rechaza, ignora o gestiona la repetición conforme a la regla definida, evitando duplicar el registro histórico o el inventario.
- **Resultado obtenido:**
- **Estado:** PENDIENTE
- **Evidencia:**
- **Observaciones:**

---

## Módulo 6. Integración de datos ESP32 ↔ plataforma web

### CP-036. Flujo integrado de lectura ambiental

- **ID del caso:** CP-036
- **Requisito relacionado:** Comunicación Wi-Fi entre ESP32 y plataforma web, monitoreo en tiempo real y almacenamiento histórico.
- **Módulo:** Integración de datos
- **Nombre del caso:** Flujo completo de una lectura de humedad o temperatura
- **Objetivo:** Verificar el recorrido completo desde el sensor hasta la visualización y persistencia del dato.
- **Prioridad:** Alta
- **Precondiciones:** Sensor conectado; ESP32 y Wi-Fi operativos; Laravel y MySQL disponibles; usuario autorizado.
- **Datos de entrada:** Lectura identificable de humedad o temperatura.
- **Pasos de ejecución:**
  1. Generar o esperar una lectura ambiental en el sensor.
  2. Confirmar que el ESP32 reciba la lectura.
  3. Verificar que el ESP32 la transmita por Wi-Fi.
  4. Revisar la visualización en la plataforma.
  5. Consultar el registro histórico y comprobar su persistencia en MySQL.
- **Resultado esperado:** La lectura recorre correctamente el flujo sensor–ESP32–Wi-Fi–Laravel–MySQL y queda disponible para monitoreo y consulta histórica.
- **Resultado obtenido:**
- **Estado:** PENDIENTE
- **Evidencia:**
- **Observaciones:**

### CP-037. Flujo integrado de alimentación o hidratación

- **ID del caso:** CP-037
- **Requisito relacionado:** Control de actuadores, comunicación Wi-Fi y gestión digital de eventos.
- **Módulo:** Integración de datos
- **Nombre del caso:** Registro de un evento de alimentación o hidratación
- **Objetivo:** Verificar que una acción ejecutada por un actuador pueda ser comunicada y registrada en la plataforma, según la funcionalidad implementada.
- **Prioridad:** Alta
- **Precondiciones:** ESP32, actuador y Wi-Fi operativos; plataforma Laravel y MySQL disponibles; usuario autorizado.
- **Datos de entrada:** Orden de alimentación o hidratación.
- **Pasos de ejecución:**
  1. Generar la orden de alimentación o hidratación.
  2. Confirmar la activación del servomotor o mini-bomba.
  3. Verificar el envío del evento desde el ESP32.
  4. Revisar la información recibida en la plataforma.
  5. Consultar el historial y la persistencia en MySQL.
- **Resultado esperado:** El evento se ejecuta en el actuador y, cuando esté contemplado por la implementación, se transmite, visualiza y almacena sin inconsistencias.
- **Resultado obtenido:**
- **Estado:** PENDIENTE
- **Evidencia:**
- **Observaciones:**

### CP-038. Flujo integrado de conteo de huevos

- **ID del caso:** CP-038
- **Requisito relacionado:** Conteo mediante celdas de carga y HX711, comunicación con la plataforma y gestión de inventario.
- **Módulo:** Integración de datos
- **Nombre del caso:** Registro integral de unidades de producción
- **Objetivo:** Verificar el flujo desde el peso acumulado hasta el conteo y su almacenamiento en la plataforma.
- **Prioridad:** Alta
- **Precondiciones:** Celda de carga y HX711 operativos; ESP32 conectado por Wi-Fi; Laravel y MySQL disponibles; regla de conversión configurada.
- **Datos de entrada:** Peso acumulado de prueba que pueda transformarse según la regla configurada.
- **Pasos de ejecución:**
  1. Colocar el peso de prueba en la celda de carga.
  2. Verificar la lectura del HX711.
  3. Confirmar el conteo calculado por el ESP32.
  4. Verificar la transmisión del conteo a la plataforma.
  5. Consultar el inventario y el historial.
  6. Verificar la persistencia del registro en MySQL.
- **Resultado esperado:** El peso se transforma en unidades registrables, el conteo se transmite, se muestra y se almacena como información de inventario sin inconsistencias.
- **Resultado obtenido:**
- **Estado:** PENDIENTE
- **Evidencia:**
- **Observaciones:**

### CP-039. Integración con dato inválido

- **ID del caso:** CP-039
- **Requisito relacionado:** Integración de datos entre ESP32 y plataforma web.
- **Módulo:** Integración de datos
- **Nombre del caso:** Rechazo de un dato inválido transmitido a la plataforma
- **Objetivo:** Verificar que Laravel no persista ni muestre como válido un dato inválido recibido desde el ESP32.
- **Prioridad:** Alta
- **Precondiciones:** ESP32 y plataforma conectados; posibilidad de generar un dato inválido de forma controlada; MySQL disponible.
- **Datos de entrada:** Lectura o evento con formato inválido, incompleto o no procesable.
- **Pasos de ejecución:**
  1. Preparar el dato inválido sin afectar datos reales.
  2. Transmitirlo desde el ESP32 hacia la plataforma.
  3. Observar la respuesta del backend Laravel.
  4. Consultar la vista de monitoreo.
  5. Consultar MySQL para verificar el almacenamiento.
- **Resultado esperado:** La plataforma valida el dato, lo rechaza o lo marca según la lógica implementada, y no lo presenta ni persiste como un registro válido.
- **Resultado obtenido:**
- **Estado:** PENDIENTE
- **Evidencia:**
- **Observaciones:**

### CP-040. Repetición de un mensaje integrado

- **ID del caso:** CP-040
- **Requisito relacionado:** Comunicación ESP32 ↔ plataforma web, gestión de inventario y persistencia de información.
- **Módulo:** Integración de datos
- **Nombre del caso:** Gestión de mensaje duplicado entre ESP32 y plataforma
- **Objetivo:** Verificar que la repetición del mismo mensaje no produzca duplicidad en la visualización, el inventario o el historial.
- **Prioridad:** Alta
- **Precondiciones:** Comunicación disponible; un mensaje o evento de prueba registrado; mecanismo de repetición controlada.
- **Datos de entrada:** El mismo mensaje de lectura o evento enviado dos veces.
- **Pasos de ejecución:**
  1. Enviar el mensaje de prueba una primera vez.
  2. Confirmar su recepción y registro.
  3. Reenviar exactamente el mismo mensaje.
  4. Consultar el monitoreo, el historial y el inventario si corresponde.
  5. Comparar la información antes y después de la repetición.
- **Resultado esperado:** La plataforma gestiona el mensaje duplicado según la regla definida y evita generar una segunda operación o un segundo registro no justificado.
- **Resultado obtenido:**
- **Estado:** PENDIENTE
- **Evidencia:**
- **Observaciones:**

### CP-041. Recuperación de comunicación después de una interrupción

- **ID del caso:** CP-041
- **Requisito relacionado:** Conectividad Wi-Fi entre ESP32 y plataforma web.
- **Módulo:** Integración de datos
- **Nombre del caso:** Comportamiento después de restablecer la red Wi-Fi
- **Objetivo:** Verificar el comportamiento de la comunicación cuando la red se interrumpe y posteriormente vuelve a estar disponible.
- **Prioridad:** Alta
- **Precondiciones:** ESP32 conectado; plataforma web operativa; red Wi-Fi disponible; prueba controlada.
- **Datos de entrada:** Lecturas o eventos generados antes, durante y después de la interrupción.
- **Pasos de ejecución:**
  1. Confirmar la comunicación normal entre ESP32 y plataforma.
  2. Interrumpir la red Wi-Fi de forma controlada.
  3. Generar un dato durante la interrupción y registrar la condición.
  4. Restablecer la red Wi-Fi.
  5. Generar un nuevo dato.
  6. Verificar qué datos se reciben, visualizan y almacenan.
- **Resultado esperado:** Al restablecerse la comunicación, el sistema vuelve a gestionar los datos conforme a la lógica implementada y no presenta como recibido un dato que no haya sido transmitido correctamente.
- **Resultado obtenido:**
- **Estado:** PENDIENTE
- **Evidencia:**
- **Observaciones:**

---

## Resumen de cobertura

| Categoría solicitada | Casos relacionados |
|---|---|
| Positivos | CP-001, CP-002, CP-003, CP-006, CP-007, CP-011, CP-016, CP-017, CP-021, CP-024, CP-028, CP-030, CP-031, CP-032, CP-033, CP-036, CP-037, CP-038 |
| Negativos | CP-004, CP-005, CP-009, CP-010, CP-015, CP-019, CP-022, CP-029, CP-039 |
| Valores límite | CP-012, CP-013, CP-014, CP-018 |
| Campos obligatorios | CP-023, CP-025, CP-034 |
| Datos inválidos | CP-004, CP-015, CP-019, CP-026, CP-039 |
| Datos duplicados | CP-020, CP-027, CP-035, CP-040 |
| Operaciones incorrectas | CP-005, CP-009, CP-010, CP-019, CP-039 |
| Permisos y roles | CP-028, CP-029 |
| Navegación | CP-030 |
| Persistencia de información | CP-020, CP-027, CP-032, CP-033, CP-034, CP-035, CP-038, CP-039, CP-040, CP-041 |

## Nota de ejecución

Los resultados, estados definitivos y evidencias deberán completarse únicamente después de ejecutar cada caso en el ambiente de pruebas correspondiente. No se deben sustituir los valores pendientes ni declarar aprobación o rechazo sin evidencia verificable.
