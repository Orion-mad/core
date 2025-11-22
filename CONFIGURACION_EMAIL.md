# Configuración de Email para Recuperación de Contraseña

## Requisitos

El sistema usa **phpMailer** para enviar correos electrónicos. Necesitarás configurar una cuenta SMTP para el envío de correos.

## Configuración en la Base de Datos

La configuración de email se almacena en la tabla `configuracion_sistema` con la categoría `email`. Aquí están los parámetros necesarios:

### Parámetros Requeridos

```sql
INSERT INTO configuracion_sistema (categoria, clave, valor, descripcion) VALUES
('email', 'smtp_host', 'smtp.gmail.com', 'Servidor SMTP'),
('email', 'smtp_port', '587', 'Puerto SMTP (587 para TLS, 465 para SSL)'),
('email', 'smtp_secure', 'tls', 'Tipo de encriptación (tls o ssl)'),
('email', 'smtp_username', 'tu-email@gmail.com', 'Usuario SMTP (tu correo)'),
('email', 'smtp_password', 'tu-contraseña-de-aplicacion', 'Contraseña SMTP'),
('email', 'from_email', 'noreply@tudominio.com', 'Email remitente'),
('email', 'from_name', 'Orion Suite', 'Nombre del remitente'),
('email', 'smtp_debug', '0', 'Nivel de debug (0=off, 1=client, 2=server)');
```

## Configuración para Gmail

Si usas Gmail, necesitas:

1. **Activar verificación en 2 pasos** en tu cuenta de Google
2. **Generar una contraseña de aplicación**:
   - Ve a https://myaccount.google.com/security
   - En "Iniciar sesión en Google" → "Contraseñas de aplicaciones"
   - Selecciona "Correo" y "Otro (nombre personalizado)"
   - Copia la contraseña generada (16 caracteres)

### Ejemplo de configuración para Gmail:

```sql
UPDATE configuracion_sistema SET valor = 'smtp.gmail.com' WHERE clave = 'smtp_host';
UPDATE configuracion_sistema SET valor = '587' WHERE clave = 'smtp_port';
UPDATE configuracion_sistema SET valor = 'tls' WHERE clave = 'smtp_secure';
UPDATE configuracion_sistema SET valor = 'tu-email@gmail.com' WHERE clave = 'smtp_username';
UPDATE configuracion_sistema SET valor = 'abcd efgh ijkl mnop' WHERE clave = 'smtp_password';
UPDATE configuracion_sistema SET valor = 'noreply@tudominio.com' WHERE clave = 'from_email';
UPDATE configuracion_sistema SET valor = 'Orion Suite' WHERE clave = 'from_name';
```

## Configuración para otros proveedores

### Office 365 / Outlook

```
smtp_host: smtp.office365.com
smtp_port: 587
smtp_secure: tls
smtp_username: tu-email@outlook.com
smtp_password: tu-contraseña
```

### Yahoo Mail

```
smtp_host: smtp.mail.yahoo.com
smtp_port: 587
smtp_secure: tls
smtp_username: tu-email@yahoo.com
smtp_password: contraseña-de-aplicacion
```

### Servidor SMTP personalizado

```
smtp_host: mail.tudominio.com
smtp_port: 587 (o el que use tu servidor)
smtp_secure: tls (o ssl)
smtp_username: tu-usuario
smtp_password: tu-contraseña
```

## Configurar APP_URL

En el archivo `config.php`, asegúrate de configurar la URL correcta de tu aplicación:

```php
define('APP_URL', 'http://tu-dominio.com');
// o para desarrollo local:
define('APP_URL', 'http://localhost/core.orionar.cloud');
```

Esta URL se usa para generar el enlace de recuperación de contraseña en los correos.

## Probar la Configuración

Puedes probar la configuración de email usando el siguiente código PHP:

```php
<?php
require_once 'config.php';
require_once INCLUDES_PATH . '/Database.php';
require_once INCLUDES_PATH . '/MailService.php';

$mailService = MailService::getInstance();

// Probar conexión SMTP
$result = $mailService->testConnection();
echo $result['success'] ? 'Conexión exitosa!' : 'Error: ' . $result['message'];

// Enviar correo de prueba
$result = $mailService->send(
    'destinatario@ejemplo.com',
    'Prueba de configuración',
    '<h1>¡Hola!</h1><p>Este es un correo de prueba.</p>'
);

echo $result['success'] ? 'Correo enviado!' : 'Error: ' . $result['message'];
?>
```

## Solución de Problemas

### Error: "SMTP Error: Could not authenticate"

- Verifica que el usuario y contraseña sean correctos
- Si usas Gmail, asegúrate de usar una contraseña de aplicación
- Verifica que la autenticación de 2 factores esté activada (para Gmail)

### Error: "SMTP connect() failed"

- Verifica que el host y puerto sean correctos
- Verifica que tu servidor/firewall permita conexiones salientes al puerto SMTP
- Intenta cambiar el puerto (587 ↔ 465) o el tipo de encriptación (tls ↔ ssl)

### Error: "Could not instantiate mail function"

- Verifica que la extensión PHP `openssl` esté habilitada
- Verifica que phpMailer esté correctamente instalado en `vendor/phpmailer/`

### Los correos no llegan

- Revisa la carpeta de spam
- Verifica los logs del sistema en `logs/app_YYYY-MM-DD.log`
- Activa el debug SMTP cambiando `smtp_debug` a `2`

## Seguridad

⚠️ **IMPORTANTE**:

- Nunca compartas tus credenciales SMTP
- Usa contraseñas de aplicación cuando sea posible
- En producción, usa conexiones SSL/TLS
- Considera usar variables de entorno para credenciales sensibles
- Limita el acceso a la tabla `configuracion_sistema`

## Características Implementadas

✅ Toggle mostrar/ocultar contraseña en login
✅ Link "¿Olvidaste tu contraseña?" en login
✅ Formulario de recuperación de contraseña
✅ Envío de correo con token de recuperación
✅ Página de restablecimiento de contraseña
✅ Validación de token y expiración (1 hora)
✅ Validación de requisitos de contraseña
✅ Logs de auditoría para recuperación de contraseña
✅ Protección CSRF en todos los formularios
