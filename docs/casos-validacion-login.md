# Casos de prueba de validación del login

## 1. Información general

**Proyecto:** SISTEMA DE CONTROL AUTOMATIZADO DE GRANJAS DE CODORNIZ PARA LA GESTIÓN DE ALIMENTACIÓN, HIDRATACIÓN, TEMPERATURA Y CONTEO DE HUEVOS EN LA COMUNIDAD DE TUSCAPUJYO.

**Módulo:** Formulario de login o inicio de sesión de la plataforma web.

**Objetivo:** Verificar las validaciones de entrada y el comportamiento de autenticación para los campos de correo electrónico y contraseña.

Estos casos se basan en el código actualmente observado en `AuthController`: el formulario recibe los campos `email` y `password`; `email` tiene las reglas `required` y `email`; `password` tiene la regla `required`; luego Laravel intenta autenticar las credenciales mediante el guard por defecto. El nombre de usuario no se utiliza en la implementación observada.

Los casos no confirman requisitos que no estén definidos. Cuando no existe una regla verificable en el código o en la información del proyecto, el resultado esperado se marca como **requiere verificación**.

## 2. Reglas conocidas y pendientes

| Campo o comportamiento | Evidencia disponible | Estado para pruebas |
|---|---|---|
| `email` obligatorio | Regla `required` en `AuthController` | Confirmado en código |
| `email` con formato de correo | Regla `email` en `AuthController` | Confirmado en código; el detalle exacto del formato depende de Laravel |
| `email` existente | `Auth::attempt` solo autentica credenciales coincidentes | Confirmado como rechazo de autenticación si no existe; el mensaje exacto requiere verificación |
| `password` obligatorio | Regla `required` en `AuthController` | Confirmado en código |
| Comparación contra contraseña almacenada | `Auth::attempt` y modelo autenticable `User` | Confirmado el uso del mecanismo de autenticación; algoritmo y configuración final requieren verificación |
| Campo de nombre de usuario | No aparece en el controlador revisado | No aplica según el código observado; confirmar si existe otra implementación |
| Longitud mínima y máxima | No hay reglas `min`, `max` en el login | Requiere verificación; no se debe asumir |
| Tipo `string` explícito | No hay regla `string` en el login | Requiere verificación del comportamiento ante tipos no escalares |
| Límite de intentos, throttle o bloqueo | No se observa en `AuthController` | Requiere verificación en rutas, middleware y configuración |
| Correo duplicado | La migración declara `users.email` como único | La duplicidad se previene en registro/base de datos; no es una condición válida de login |
| Mensajes exactos | El rechazo de credenciales usa un mensaje definido, pero la validación usa mensajes por defecto | Requiere verificación de idioma y contenido final |

## 3. Datos base de prueba

Usar una base de datos de pruebas y un usuario identificado como `usuario.login@prueba.test`, con contraseña conocida `ClaveValida-123`, creado previamente y habilitado para autenticación. No utilizar credenciales reales.

Cuando un caso cambie un campo, mantener el otro con el valor válido indicado, salvo que el caso pruebe una combinación inválida.

## 4. Casos de prueba

### CP-LOGIN-001. Login válido con correo y contraseña correctos

- **Tipo:** Positivo
- **Campo(s):** `email`, `password`
- **Prioridad:** Alta
- **Precondiciones:** Usuario de prueba existente y credenciales válidas.
- **Datos:** `usuario.login@prueba.test` / `ClaveValida-123`.
- **Pasos:** Abrir el login, ingresar ambos valores y enviar el formulario.
- **Resultado esperado:** Se crea la sesión autenticada y el usuario es redirigido al inicio o a la ruta prevista. No se muestran errores de validación.
- **Regla comprobada:** Credenciales válidas y acceso mediante `Auth::attempt`.

### CP-LOGIN-002. Correo vacío

- **Tipo:** Negativo
- **Campo:** `email`
- **Prioridad:** Alta
- **Datos:** `email` vacío; contraseña válida.
- **Pasos:** Dejar vacío el correo, ingresar la contraseña válida y enviar.
- **Resultado esperado:** La solicitud es rechazada por campo obligatorio, no se crea sesión y se muestra un error asociado al correo. El texto exacto requiere verificación.
- **Regla comprobada:** `email` tiene `required`.

### CP-LOGIN-003. Contraseña vacía

- **Tipo:** Negativo
- **Campo:** `password`
- **Prioridad:** Alta
- **Datos:** Correo válido; contraseña vacía.
- **Pasos:** Ingresar el correo válido, dejar vacía la contraseña y enviar.
- **Resultado esperado:** La solicitud es rechazada por campo obligatorio, no se crea sesión y se muestra un error asociado a la contraseña. El texto exacto requiere verificación.
- **Regla comprobada:** `password` tiene `required`.

### CP-LOGIN-004. Ambos campos vacíos

- **Tipo:** Negativo
- **Campo(s):** `email`, `password`
- **Prioridad:** Alta
- **Datos:** Ambos campos vacíos.
- **Pasos:** Abrir el login sin ingresar valores y enviar.
- **Resultado esperado:** Se identifican ambos campos obligatorios, no se crea sesión y no se ejecuta un acceso autenticado. El orden y texto de los errores requieren verificación.

### CP-LOGIN-005. Correo con solo espacios

- **Tipo:** Negativo
- **Campo:** `email`
- **Prioridad:** Alta
- **Datos:** `   `; contraseña válida.
- **Pasos:** Ingresar únicamente espacios en el correo y enviar.
- **Resultado esperado:** No se autentica. Debe registrarse si Laravel lo interpreta como vacío, como formato inválido o como credencial inexistente; el resultado exacto requiere verificación.

### CP-LOGIN-006. Contraseña con solo espacios

- **Tipo:** Negativo
- **Campo:** `password`
- **Prioridad:** Alta
- **Datos:** Correo válido; `   `.
- **Pasos:** Ingresar el correo válido, usar solo espacios como contraseña y enviar.
- **Resultado esperado:** No se autentica. Si se rechaza por validación o por credencial incorrecta depende del comportamiento aplicado a este valor y requiere verificación.

### CP-LOGIN-007. Correo con formato inválido

- **Tipo:** Negativo
- **Campo:** `email`
- **Prioridad:** Alta
- **Datos de variación:** `usuario`, `usuario@`, `@dominio.test`, `usuario dominio.test`, `usuario@dominio`.
- **Pasos:** Ejecutar el caso con cada valor y contraseña válida.
- **Resultado esperado:** Cada valor que no cumpla la regla `email` es rechazado por validación y no crea sesión. El conjunto exacto de formatos aceptados depende de la regla de Laravel y requiere verificación.
- **Regla comprobada:** `email` tiene la regla `email`.

### CP-LOGIN-008. Correo válido con mayúsculas o espacios laterales

- **Tipo:** Negativo o de compatibilidad
- **Campo:** `email`
- **Datos:** ` Usuario.Login@Prueba.Test `; contraseña válida.
- **Pasos:** Enviar el correo con mayúsculas y espacios laterales.
- **Resultado esperado:** No debe producir un acceso no autorizado. Si el sistema recorta espacios o normaliza mayúsculas, debe documentarse; esa normalización no está confirmada y requiere verificación.

### CP-LOGIN-009. Contraseña con caracteres especiales válidos

- **Tipo:** Positivo condicionado
- **Campo:** `password`
- **Datos:** Crear un usuario de prueba cuya contraseña contenga caracteres como `Clave!@#-123`, y usarla exactamente.
- **Pasos:** Ingresar correo y contraseña con caracteres especiales; enviar.
- **Resultado esperado:** Si la contraseña fue almacenada correctamente, se autentica con coincidencia exacta. El soporte de caracteres, codificación y política de complejidad requieren verificación.

### CP-LOGIN-010. Correo con caracteres especiales no válidos para su formato

- **Tipo:** Negativo
- **Campo:** `email`
- **Datos:** `usuario<>@prueba.test`, `usuario()@prueba.test`, `usuario@dominio..test`.
- **Pasos:** Ejecutar cada variación con contraseña válida.
- **Resultado esperado:** Los valores rechazados por la regla `email` no crean sesión. La aceptación o rechazo exacto de cada forma requiere verificación contra la versión de Laravel instalada.

### CP-LOGIN-011. Contraseña demasiado corta

- **Tipo:** Negativo condicionado
- **Campo:** `password`
- **Datos:** Contraseña de 0, 1, 5 y otros tamaños inferiores al mínimo que se apruebe.
- **Pasos:** Usar cada longitud con un correo válido.
- **Resultado esperado:** La longitud solo debe rechazarse si existe una regla mínima configurada. Actualmente el login no declara `min`; el resultado esperado y el límite exacto requieren verificación.

### CP-LOGIN-012. Contraseña demasiado larga

- **Tipo:** Negativo condicionado
- **Campo:** `password`
- **Datos:** Contraseña que supere el máximo aprobado.
- **Pasos:** Enviar el valor con correo válido.
- **Resultado esperado:** La longitud solo debe rechazarse si existe una regla máxima configurada. Actualmente el login no declara `max`; el límite y el comportamiento requieren verificación.

### CP-LOGIN-013. Correo demasiado largo

- **Tipo:** Negativo condicionado
- **Campo:** `email`
- **Datos:** Correo con longitud superior al máximo aprobado.
- **Pasos:** Enviar el valor con contraseña válida.
- **Resultado esperado:** La longitud solo debe rechazarse si existe una regla máxima configurada. Actualmente el login no declara `max`; el límite y el comportamiento requieren verificación.

### CP-LOGIN-014. Tipo de dato incorrecto en correo

- **Tipo:** Negativo
- **Campo:** `email`
- **Datos:** Arreglo, objeto, número o estructura no escalar enviada directamente a la solicitud HTTP.
- **Pasos:** Construir la petición con el tipo indicado y enviarla.
- **Resultado esperado:** No se crea sesión ni se produce un acceso no autorizado. El código HTTP, mensaje y si Laravel lo rechaza por tipo o por formato requieren verificación.

### CP-LOGIN-015. Tipo de dato incorrecto en contraseña

- **Tipo:** Negativo
- **Campo:** `password`
- **Datos:** Arreglo, objeto, número o estructura no escalar enviada directamente a la solicitud HTTP.
- **Pasos:** Mantener un correo válido, enviar la contraseña con el tipo indicado y observar la respuesta.
- **Resultado esperado:** No se crea sesión ni se produce un acceso no autorizado. Como no existe una regla `string` explícita, el comportamiento exacto y la gestión de posibles errores requieren verificación.

### CP-LOGIN-016. Correo no registrado

- **Tipo:** Negativo
- **Campo:** `email`
- **Datos:** `noexiste@prueba.test`; contraseña válida.
- **Pasos:** Enviar el formulario con un correo que no exista en `users`.
- **Resultado esperado:** La validación de formato puede ser exitosa, pero `Auth::attempt` rechaza las credenciales y no crea sesión. Debe mostrarse el mensaje de credenciales incorrectas previsto; el texto exacto requiere verificación.
- **Regla comprobada:** El correo debe corresponder a credenciales existentes para autenticar.

### CP-LOGIN-017. Contraseña incorrecta

- **Tipo:** Negativo
- **Campo:** `password`
- **Datos:** Correo existente; `ClaveIncorrecta-123`.
- **Pasos:** Enviar el formulario con correo válido y contraseña distinta a la almacenada.
- **Resultado esperado:** `Auth::attempt` rechaza las credenciales, no crea sesión y devuelve el flujo de error previsto.

### CP-LOGIN-018. Contraseña con diferencia de mayúsculas

- **Tipo:** Negativo
- **Campo:** `password`
- **Datos:** Si la válida es `ClaveValida-123`, usar `clavevalida-123`.
- **Pasos:** Enviar el correo válido con la contraseña modificada.
- **Resultado esperado:** No se autentica si la comparación es exacta, como normalmente ocurre con contraseñas. El comportamiento final requiere verificación.

### CP-LOGIN-019. Contraseña con espacios adicionales

- **Tipo:** Negativo
- **Campo:** `password`
- **Datos:** Contraseña válida con un espacio inicial o final.
- **Pasos:** Enviar el correo válido y la contraseña alterada con espacios.
- **Resultado esperado:** No se autentica si los espacios forman parte del valor comparado. El sistema no debe recortar silenciosamente la contraseña salvo regla documentada; requiere verificación.

### CP-LOGIN-020. Campos intercambiados

- **Tipo:** Negativo
- **Campo(s):** `email`, `password`
- **Datos:** Colocar la contraseña en `email` y el correo en `password`.
- **Pasos:** Enviar el formulario con los valores invertidos.
- **Resultado esperado:** La solicitud es rechazada; como mínimo, el campo `email` debe fallar la regla de formato. No se crea sesión.

### CP-LOGIN-021. Campos con nombres incorrectos o ausentes

- **Tipo:** Negativo
- **Campo(s):** `email`, `password`
- **Datos:** Enviar `username`/`clave`, o enviar solo campos no reconocidos.
- **Pasos:** Enviar la petición sin los nombres esperados por el controlador.
- **Resultado esperado:** `email` y `password` se consideran ausentes, no se crea sesión y se aplican sus validaciones obligatorias. El campo `username` no sustituye a `email` en el código observado.

### CP-LOGIN-022. Datos duplicados de correo en la base de datos

- **Tipo:** Negativo de integridad / requiere preparación controlada
- **Campo:** `email`
- **Datos:** Intentar crear dos usuarios con el mismo correo antes de ejecutar el login.
- **Pasos:** Intentar insertar el duplicado y, si la base lo permite en un ambiente controlado, ejecutar el login con ese correo.
- **Resultado esperado:** La migración declara `users.email` como `unique`, por lo que el duplicado debe impedirse en la base de datos. La duplicidad no es una validación del formulario de login y no debe permitir seleccionar una cuenta arbitrariamente. El comportamiento con datos corruptos requiere verificación.

### CP-LOGIN-023. Campos con contenido SQL o HTML como texto

- **Tipo:** Negativo de seguridad de entrada
- **Campo(s):** `email`, `password`
- **Datos:** Valores como `' OR '1'='1`, `<script>alert(1)</script>` y otros textos no válidos.
- **Pasos:** Enviar cada valor usando la solicitud normal del formulario y revisar respuesta, sesión, mensajes y logs.
- **Resultado esperado:** No se crea sesión indebida, no se ejecuta contenido HTML/JavaScript y la solicitud se trata como dato. La protección concreta proviene del framework y requiere verificación mediante prueba de seguridad.

### CP-LOGIN-024. Repetición de credenciales incorrectas

- **Tipo:** Negativo condicionado
- **Campo(s):** `email`, `password`
- **Datos:** Credenciales incorrectas enviadas repetidamente desde la misma sesión o dirección de prueba.
- **Pasos:** Repetir el intento según una cantidad definida por el plan de pruebas.
- **Resultado esperado:** Siempre se rechaza el acceso. La existencia de límite, espera progresiva, throttle o bloqueo no está visible en el controlador y requiere verificación en middleware, rutas y configuración; no se debe declarar un número de intentos esperado sin esa información.

### CP-LOGIN-025. Sesión previa y acceso al formulario

- **Tipo:** Positivo de control / flujo de autenticación
- **Campo(s):** No aplica inicialmente
- **Datos:** Usuario ya autenticado.
- **Pasos:** Conservar una sesión válida y acceder a la ruta de login.
- **Resultado esperado:** El controlador observado redirige al inicio cuando `Auth::check()` es verdadero. Debe verificarse que no se muestre nuevamente el formulario ni se pierda la sesión.

## 5. Verificaciones específicas por campo

| Campo | Obligatorio | Tipo | Mínimo | Máximo | Formato | Valores permitidos | Valores no permitidos | Duplicidad | Relación | Regla de negocio |
|---|---|---|---|---|---|---|---|---|---|---|
| `email` | Sí, confirmado por `required`. | Se espera texto; regla `string` explícita no confirmada. | No definido. | No definido en login. La columna `users.email` usa `string` de base de datos, pero eso no fija el máximo de validación del formulario. | Correo válido según regla `email` de Laravel. | Un correo con formato aceptado y credenciales existentes para autenticar. | Vacío, formato inválido, credencial inexistente y tipos no escalares; el detalle de algunos casos requiere verificación. | No debe duplicarse en `users`; la unicidad está confirmada en la migración, pero no es una regla de login. | Debe corresponder a un registro de `users` para que `Auth::attempt` autentique. | Solo usuarios registrados con credenciales coincidentes pueden acceder. |
| `password` | Sí, confirmado por `required`. | Texto esperado; regla `string` explícita no confirmada. | No definido. | No definido. | No se aplica formato de email; la contraseña se compara con la almacenada. | El valor exacto que coincida con la contraseña almacenada. | Vacío, contraseña incorrecta y cualquier valor que no coincida; longitudes, caracteres y tipos específicos requieren verificación. | No aplica como duplicidad de login; distintas cuentas pueden tener la misma contraseña salvo política no proporcionada. | Se relaciona con el usuario identificado por `email` y se valida mediante `Auth::attempt`. | No debe mostrarse en texto plano ni permitir autenticación si no coincide. |

## 6. Información pendiente para cerrar las pruebas

1. Confirmar que el login definitivo utiliza `email` y no un nombre de usuario alternativo en todas las rutas y vistas.
2. Confirmar la versión de Laravel y la implementación final de autenticación.
3. Confirmar reglas `string`, `min`, `max`, complejidad y normalización de ambos campos.
4. Definir si se recortan espacios del correo y si se normalizan mayúsculas; no recortar contraseñas sin una regla explícita.
5. Confirmar el algoritmo y configuración del hash de contraseñas almacenadas.
6. Confirmar middleware de throttle, límite de intentos, espera, bloqueo y recuperación.
7. Definir mensajes, idioma, código HTTP y estructura esperada de los errores de validación.
8. Confirmar comportamiento ante tipos no escalares enviados directamente por HTTP.
9. Confirmar política de correos duplicados y procedimiento para corregir datos inconsistentes.
10. Definir requisitos formales de seguridad para inyección, XSS, CSRF, enumeración de usuarios y sesión.
11. Confirmar rutas protegidas y comportamiento esperado después del login y logout.
12. Definir criterios de aceptación para disponibilidad, rendimiento y número de usuarios concurrentes.

Hasta completar estos datos, los casos marcados como condicionados deben ejecutarse como exploración o verificación de configuración, no como conformidad definitiva de una regla no aprobada.