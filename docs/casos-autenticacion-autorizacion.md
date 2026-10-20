# Casos de prueba de autenticación, autorización y control de acceso

## 1. Información general

**Proyecto:** SISTEMA DE CONTROL AUTOMATIZADO DE GRANJAS DE CODORNIZ PARA LA GESTIÓN DE ALIMENTACIÓN, HIDRATACIÓN, TEMPERATURA Y CONTEO DE HUEVOS EN LA COMUNIDAD DE TUSCAPUJYO.

**Objetivo:** verificar el inicio y cierre de sesión, la protección de rutas, la autorización por rol, el manejo de sesiones y el control de operaciones críticas.

Estos casos son una especificación de pruebas. La ejecución de una prueba puede revelar un defecto, pero ningún defecto o vulnerabilidad se considera confirmado antes de observar el resultado y documentar la evidencia.

## 2. Alcance confirmado y pendientes

### 2.1. Autenticación observada

- El login usa los campos `email` y `password`.
- Ambos campos tienen validación `required`; el correo también tiene validación `email`.
- La autenticación se realiza mediante `Auth::attempt`.
- Una autenticación exitosa regenera la sesión y redirige a la ruta prevista del inicio.
- Un fallo de credenciales devuelve el formulario con un mensaje de error y conserva únicamente el correo ingresado.
- El logout ejecuta `Auth::logout`, invalida la sesión y regenera el token de sesión.

### 2.2. Protección de rutas observada

Las siguientes rutas están dentro de un grupo `middleware(['auth'])` y deben requerir una sesión autenticada:

- `/`
- `/configuracion`
- `/usuarios`, `/usuarios/nuevo`, `/usuarios/guardar`, `/usuarios/eliminar/{id}`
- `/aves`, `/aves/nuevo`, `/aves/guardar`, `/aves/{id}/editar`, `/aves/{id}`, `/aves/eliminar/{id}`
- `/reportes/diario` y `/reportes/historial`
- `/monitoreo/alimentacion`, `/monitoreo/hidratacion`, `/monitoreo/temperatura` y `/monitoreo/conteo-huevos`

Las rutas `/login` y `/logout` se declaran fuera de ese grupo. El comportamiento exacto de acceso a `/login` con una sesión activa se controla en `AuthController` y debe verificarse.

### 2.3. Autorización observada y pendientes

- Las acciones de `UserController` comprueban explícitamente que el rol sea `admin`; un usuario con otro rol debe recibir HTTP 403 en esas acciones.
- No se observa un middleware global de rol en las rutas.
- Las rutas de lotes, reportes, configuración y monitoreo están dentro de `auth`, pero no muestran una restricción de rol en `web.php`; debe verificarse si esa apertura es intencional.
- El rol `propietario` aparece como valor permitido al crear usuarios, pero su vista/interfaz y sus permisos específicos están **pendientes de implementación**. No se debe asumir qué puede o no puede hacer.
- Las rutas API `/v1/despachar-agua`, `/v1/recargar-agua`, `/v1/despachar-comida` y `/v1/recargar-comida` no aparecen dentro del grupo web `auth`. Su protección debe verificarse mediante pruebas; no se afirma que exista una vulnerabilidad.
- `/v1/sensor-update` usa un token Bearer propio del ESP32. No se debe confundir esa autenticación de dispositivo con la sesión del usuario web.

## 3. Requisitos de prueba relacionados

| ID | Requisito verificable derivado | Estado |
|---|---|---|
| RA-001 | Un usuario registrado con credenciales válidas puede iniciar sesión. | Derivado del objetivo; validar con el código y la prueba. |
| RA-002 | Las credenciales incorrectas no permiten iniciar sesión. | Derivado del objetivo; el rechazo está implementado mediante `Auth::attempt`. |
| RA-003 | Los campos obligatorios del login deben validarse. | Confirmado en código para `email` y `password`. |
| RA-004 | Las funciones protegidas solo deben estar disponibles para usuarios autenticados. | Confirmado como intención en el grupo `auth`; verificar todas las rutas. |
| RA-005 | Las funciones de administración de usuarios deben limitarse al rol `admin`. | Comprobación explícita observada en `UserController`. |
| RA-006 | El cierre de sesión debe invalidar la sesión y el token correspondiente. | Implementado en `AuthController`; verificar comportamiento observable. |
| RA-007 | Los permisos del rol `propietario` deben estar definidos antes de validar restricciones específicas. | Pendiente de implementación y especificación. |
| RA-008 | Las operaciones críticas de alimentación e hidratación no deben ejecutarse por actores no autorizados. | Requisito de seguridad derivado; protección actual de las rutas API requiere verificación. |

## 4. Datos y precondiciones comunes

Usar una base de datos de pruebas separada de producción y cuentas no reales:

- **Administrador:** `admin.qa@prueba.test`, contraseña conocida y rol `admin`.
- **Propietario:** `propietario.qa@prueba.test`, contraseña conocida y rol `propietario`, solo si la cuenta puede crearse y la prueba de su rol está habilitada.
- **Usuario inexistente:** correo que no figure en `users`.
- Sesiones independientes y navegadores o perfiles separados para evitar reutilizar cookies.
- Registros de lote, reportes y dispositivos de prueba cuando una ruta requiera datos existentes.

Los mensajes exactos, códigos HTTP esperados para cada flujo y redirecciones deben registrarse durante la ejecución si no están formalmente definidos.

## 5. Casos de prueba

### CP-AUT-001. Inicio de sesión correcto del administrador

- **Requisito:** RA-001.
- **Tipo:** Positivo.
- **Prioridad:** Alta.
- **Precondiciones:** Cuenta `admin` activa y credenciales válidas.
- **Pasos:** Abrir `/login`, ingresar correo y contraseña correctos, enviar el formulario y observar la respuesta.
- **Resultado esperado:** Se crea una sesión autenticada, se regenera la sesión y el usuario accede al inicio o a la ruta prevista. No se muestran errores.

### CP-AUT-002. Inicio de sesión con contraseña incorrecta

- **Requisito:** RA-002.
- **Tipo:** Negativo.
- **Prioridad:** Alta.
- **Precondiciones:** Correo de administrador existente.
- **Pasos:** Ingresar el correo válido con una contraseña incorrecta y enviar.
- **Resultado esperado:** `Auth::attempt` rechaza el acceso, no se crea una sesión autenticada y se devuelve el flujo de error previsto. El mensaje y código exactos requieren verificación.

### CP-AUT-003. Inicio de sesión con usuario inexistente

- **Requisito:** RA-002.
- **Tipo:** Negativo.
- **Prioridad:** Alta.
- **Precondiciones:** El correo no existe en `users` y tiene formato válido.
- **Pasos:** Ingresar el correo inexistente y una contraseña cualquiera; enviar.
- **Resultado esperado:** No se crea sesión ni se permite acceso. La respuesta no debe revelar innecesariamente si el correo existe; el mensaje final requiere verificación.

### CP-AUT-004. Campos vacíos en el login

- **Requisito:** RA-003.
- **Tipo:** Negativo.
- **Prioridad:** Alta.
- **Pasos:** Enviar el formulario con ambos campos vacíos; repetir dejando vacío solo el correo y luego solo la contraseña.
- **Resultado esperado:** La validación rechaza cada campo obligatorio ausente, no crea sesión y muestra los errores correspondientes. El contenido exacto de los mensajes requiere verificación.

### CP-AUT-005. Correo con formato inválido

- **Requisito:** RA-003.
- **Tipo:** Negativo.
- **Prioridad:** Alta.
- **Datos:** `admin`, `admin@`, `@prueba.test` y un correo con espacios.
- **Pasos:** Enviar cada valor con una contraseña válida.
- **Resultado esperado:** La regla `email` rechaza los valores que no cumplan el formato aceptado y no se crea sesión. El conjunto exacto aceptado depende de la versión de Laravel.

### CP-AUT-006. Acceso a una URL protegida sin autenticación

- **Requisito:** RA-004.
- **Tipo:** Negativo.
- **Prioridad:** Alta.
- **Precondiciones:** Sesión inexistente, cookies eliminadas o ventana privada.
- **Pasos:** Solicitar directamente `/`, `/configuracion`, `/usuarios`, `/aves`, `/reportes/diario` y cada ruta de monitoreo.
- **Resultado esperado:** El middleware `auth` impide el acceso al contenido protegido y redirige al login o devuelve la respuesta de no autenticado configurada. Debe comprobarse cada ruta y registrar código, ubicación y ausencia de datos protegidos.

### CP-AUT-007. Acceso autorizado del administrador a funciones protegidas

- **Requisito:** RA-004 y RA-005.
- **Tipo:** Positivo.
- **Precondiciones:** Sesión activa con rol `admin`.
- **Pasos:** Acceder a dashboard, configuración, gestión de usuarios, lotes, reportes y monitoreo mediante navegación y URL directa.
- **Resultado esperado:** El administrador accede a las funciones que forman parte del alcance y las operaciones permitidas responden sin rechazo de autorización.

### CP-AUT-008. Usuario autenticado no administrador en gestión de usuarios

- **Requisito:** RA-005.
- **Tipo:** Negativo.
- **Prioridad:** Alta.
- **Precondiciones:** Cuenta autenticada con rol distinto de `admin`, incluido `propietario` solo si su cuenta/vista ya está disponible.
- **Pasos:** Solicitar `/usuarios`, `/usuarios/nuevo`, enviar `/usuarios/guardar` y ejecutar `/usuarios/eliminar/{id}`.
- **Resultado esperado:** Las comprobaciones de `UserController` devuelven HTTP 403 y no permiten listar, crear ni eliminar usuarios. Para `propietario`, la ejecución de este caso depende de que la cuenta pueda autenticarse; sus restricciones adicionales siguen **pendientes de implementación**.

### CP-AUT-009. Acceso del propietario a funciones no definidas

- **Requisito:** RA-007.
- **Tipo:** Exploratorio condicionado.
- **Prioridad:** Alta.
- **Precondiciones:** Vista/interfaz y permisos del rol `propietario` implementados y documentados.
- **Pasos:** Iniciar sesión como propietario e intentar acceder a dashboard, configuración, monitoreo, reportes, lotes, acciones físicas y gestión de usuarios.
- **Resultado esperado:** Comparar el acceso con una matriz de permisos aprobada. Mientras esa matriz y la vista no existan, el resultado debe registrarse como **pendiente de implementación**, no como aprobado o fallido.

### CP-AUT-010. Acceso directo a función no autorizada por URL

- **Requisito:** RA-005 y RA-007.
- **Tipo:** Negativo condicionado.
- **Precondiciones:** Usuario autenticado no administrador; permisos del propietario definidos si se prueba ese rol.
- **Pasos:** Escribir directamente las URL de gestión de usuarios y las rutas de operaciones no permitidas, sin usar enlaces de navegación.
- **Resultado esperado:** Ocultar un enlace no se considera protección suficiente. El servidor debe rechazar la operación con la respuesta autorizada. Para propietario, el criterio concreto está **pendiente de implementación**.

### CP-AUT-011. Cierre de sesión correcto

- **Requisito:** RA-006.
- **Tipo:** Positivo.
- **Prioridad:** Alta.
- **Precondiciones:** Usuario autenticado.
- **Pasos:** Enviar la petición POST de logout desde la interfaz, observar la redirección y revisar la sesión.
- **Resultado esperado:** `Auth::logout` se ejecuta, la sesión se invalida, se regenera el token y el usuario vuelve al login. El método HTTP y la protección CSRF deben ser respetados.

### CP-AUT-012. Acceso después de cerrar sesión

- **Requisito:** RA-004 y RA-006.
- **Tipo:** Negativo.
- **Prioridad:** Alta.
- **Precondiciones:** El usuario acaba de cerrar sesión.
- **Pasos:** Usar el botón atrás, repetir una URL protegida, recargar una página protegida y enviar una operación previamente preparada.
- **Resultado esperado:** El servidor no reutiliza la sesión invalidada, no entrega contenido protegido y exige autenticación nuevamente. La caché del navegador no debe confundirse con autorización del servidor.

### CP-AUT-013. Reutilización de cookie o identificador de sesión anterior

- **Requisito:** RA-006.
- **Tipo:** Negativo de seguridad.
- **Prioridad:** Alta.
- **Precondiciones:** Sesión autenticada capturada solo en el ambiente de pruebas y luego cerrada.
- **Pasos:** Intentar solicitar una ruta protegida usando la cookie o identificador anterior.
- **Resultado esperado:** La sesión anterior no permite acceder después de la invalidación. No debe considerarse vulnerabilidad hasta observar una respuesta que contradiga este resultado.

### CP-AUT-014. Regeneración de sesión después del login

- **Requisito:** RA-001 y RA-006.
- **Tipo:** Positivo de seguridad.
- **Prioridad:** Alta.
- **Precondiciones:** Sesión anónima antes del login.
- **Pasos:** Registrar el identificador de sesión antes del login, autenticar correctamente y comparar el identificador posterior.
- **Resultado esperado:** El identificador cambia después de la autenticación, de acuerdo con `session()->regenerate()`, y la sesión nueva conserva el estado autenticado.

### CP-AUT-015. Acceso con sesión de otro usuario

- **Requisito:** RA-004 y RA-005.
- **Tipo:** Negativo de autorización.
- **Prioridad:** Alta.
- **Precondiciones:** Dos cuentas de prueba con roles o identidades diferentes.
- **Pasos:** Autenticar la primera cuenta, cambiar parámetros de URL o identificadores de recursos a los pertenecientes a la segunda cuenta y enviar solicitudes.
- **Resultado esperado:** El sistema no debe conceder permisos adicionales por modificar la URL o el identificador. Si el sistema no define separación por propietario de recursos, el alcance y criterio concreto requieren verificación.

### CP-AUT-016. Protección de creación de usuarios por método y rol

- **Requisito:** RA-005.
- **Tipo:** Negativo.
- **Precondiciones:** Sesión de propietario o usuario no administrador.
- **Pasos:** Enviar directamente POST a `/usuarios/guardar` con datos válidos y también intentar GET a la misma ruta.
- **Resultado esperado:** El POST es rechazado por la comprobación de rol con HTTP 403 y el GET no debe crear registros. No se debe considerar suficiente que el formulario no sea visible.

### CP-AUT-017. Protección de eliminación de usuarios

- **Requisito:** RA-005.
- **Tipo:** Negativo y positivo de control.
- **Precondiciones:** Usuario objetivo distinto del administrador de prueba; sesiones admin y no admin.
- **Pasos:** Intentar DELETE a `/usuarios/eliminar/{id}` como no admin; repetir como admin; finalmente intentar que el admin se elimine a sí mismo.
- **Resultado esperado:** El no admin recibe 403 y no elimina; el admin puede eliminar solo cuando la operación esté permitida; el administrador no puede eliminar su propia cuenta y recibe el flujo de error implementado.

### CP-AUT-018. Protección de operaciones de alimentación e hidratación

- **Requisito:** RA-008.
- **Tipo:** Negativo y positivo condicionado.
- **Precondiciones:** Sesiones web con y sin autenticación; API y registros de prueba disponibles.
- **Pasos:** Solicitar `/v1/despachar-agua`, `/v1/recargar-agua`, `/v1/despachar-comida` y `/v1/recargar-comida` sin sesión, con sesión de propietario y con sesión de administrador.
- **Resultado esperado:** Comparar la respuesta con la política de autorización aprobada y verificar que no se modifique la base ni se active una orden cuando el actor no tiene permiso. Actualmente estas rutas no aparecen dentro del grupo web `auth`, por lo que el resultado es **requiere verificación**; no se afirma que exista una vulnerabilidad.

### CP-AUT-019. Autenticación de la actualización del ESP32

- **Requisito:** RA-008.
- **Tipo:** Negativo y positivo de integración.
- **Precondiciones:** Endpoint `/api/v1/sensor-update` accesible y token de prueba configurado.
- **Pasos:** Enviar una actualización sin Bearer token, con token incorrecto y con token válido.
- **Resultado esperado:** Sin token o con token incorrecto se recibe rechazo no autorizado; con token válido se procesa según el contrato del dispositivo. Este token autentica al ESP32 y no debe otorgar automáticamente permisos a una sesión web.

### CP-AUT-020. Intento de acceso a rutas públicas con sesión activa

- **Requisito:** RA-001 y RA-006.
- **Tipo:** Positivo de control.
- **Precondiciones:** Sesión autenticada.
- **Pasos:** Solicitar GET `/login` y observar el resultado.
- **Resultado esperado:** El controlador observado detecta `Auth::check()` y redirige al inicio. Debe confirmarse que no se crea una sesión adicional ni se pierde la existente.

### CP-AUT-021. Múltiples sesiones y cierre de una sesión

- **Requisito:** RA-006 y manejo de sesión.
- **Tipo:** Exploratorio condicionado.
- **Precondiciones:** Dos navegadores o perfiles con el mismo usuario.
- **Pasos:** Iniciar sesión en ambos perfiles, cerrar sesión en uno y consultar una ruta protegida desde el otro.
- **Resultado esperado:** Debe observarse y documentarse la política real sobre sesiones concurrentes. El proyecto no proporciona una regla de invalidación global, por lo que no se debe exigir cierre de todas las sesiones sin especificación.

### CP-AUT-022. Envío de operación crítica con token CSRF ausente o inválido

- **Requisito:** RA-008.
- **Tipo:** Negativo de seguridad.
- **Precondiciones:** Usuario autenticado y operación web que use POST, PUT o DELETE.
- **Pasos:** Repetir una operación crítica quitando o alterando el token CSRF de la petición.
- **Resultado esperado:** La operación debe rechazarse según la protección CSRF configurada y no debe modificar datos ni activar una orden. El middleware exacto y la respuesta requieren verificación en el ambiente.

## 6. Matriz de cobertura por área

| Área | Casos |
|---|---|
| Inicio de sesión correcto | CP-AUT-001 |
| Contraseña incorrecta | CP-AUT-002 |
| Usuario inexistente | CP-AUT-003 |
| Campos vacíos | CP-AUT-004 |
| Cierre de sesión | CP-AUT-011 |
| URL protegida sin autenticación | CP-AUT-006 |
| Función no autorizada | CP-AUT-008, CP-AUT-010, CP-AUT-015, CP-AUT-016, CP-AUT-017 |
| Restricciones por rol | CP-AUT-007, CP-AUT-008, CP-AUT-009, CP-AUT-010 |
| Acceso después de logout | CP-AUT-012, CP-AUT-013 |
| Manejo y regeneración de sesión | CP-AUT-011, CP-AUT-014, CP-AUT-021 |
| Operaciones críticas | CP-AUT-018, CP-AUT-019, CP-AUT-022 |

## 7. Información pendiente para completar la autorización

1. Matriz formal de permisos por rol, especialmente para `propietario`.
2. Vista/interfaz y flujo de trabajo del propietario; actualmente está **pendiente de implementación**.
3. Lista oficial de todas las operaciones críticas y si requieren usuario, rol, token de dispositivo o ambos.
4. Confirmación de middleware aplicado a las rutas API y de la política de autorización para despachos y recargas.
5. Confirmación de si configuración, lotes, reportes y monitoreo deben estar disponibles para ambos roles.
6. Política de sesiones concurrentes, expiración por inactividad, duración y cierre global.
7. Política de bloqueo o limitación de intentos fallidos.
8. Criterios de respuesta esperados: códigos HTTP, redirecciones y mensajes.
9. Requisitos de CSRF, protección de sesión, cookies y seguridad de transporte.
10. Cuentas de prueba y datos autorizados para ejecutar las operaciones físicas sin afectar producción.

Hasta que se implemente y documente la vista del propietario, todos los casos específicos de sus permisos deben permanecer marcados como **pendiente de implementación** o **requiere verificación**.