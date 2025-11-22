<?php
/**
 * Servicio de correo electrónico usando PHPMailer
 */

// Importar clases de PHPMailer
use PHPMailer\PHPMailer\PHPMailer;
use PHPMailer\PHPMailer\SMTP;
use PHPMailer\PHPMailer\Exception;

// Cargar PHPMailer
require_once __DIR__ . '/../vendor/phpmailer/src/PHPMailer.php';
require_once __DIR__ . '/../vendor/phpmailer/src/SMTP.php';
require_once __DIR__ . '/../vendor/phpmailer/src/Exception.php';

class MailService {
    private static $instance = null;
    private $config;

    private function __construct() {
        // Cargar configuración de email desde la base de datos o config
        $this->loadConfig();
    }

    public static function getInstance() {
        if (self::$instance === null) {
            self::$instance = new self();
        }
        return self::$instance;
    }

    /**
     * Cargar configuración de email
     */
    private function loadConfig() {
        $db = Database::getInstance();

        // Intentar cargar desde la base de datos
        try {
            $configs = $db->select("SELECT clave, valor FROM configuracion_sistema WHERE categoria = 'email'");

            $this->config = [
                'smtp_host' => '',
                'smtp_port' => 587,
                'smtp_secure' => 'tls',
                'smtp_username' => '',
                'smtp_password' => '',
                'from_email' => '',
                'from_name' => APP_NAME ?? 'Sistema',
                'smtp_debug' => 0
            ];

            foreach ($configs as $conf) {
                $key = str_replace('email_', '', $conf['clave']);
                $this->config[$key] = $conf['valor'];
            }
        } catch (Exception $e) {
            // Si falla, usar valores por defecto
            $this->config = [
                'smtp_host' => 'smtp.gmail.com',
                'smtp_port' => 587,
                'smtp_secure' => 'tls',
                'smtp_username' => '',
                'smtp_password' => '',
                'from_email' => 'noreply@example.com',
                'from_name' => APP_NAME ?? 'Sistema',
                'smtp_debug' => 0
            ];
        }
    }

    /**
     * Enviar correo de recuperación de contraseña
     */
    public function sendPasswordReset($email, $token, $username) {
        $reset_link = APP_URL . "/index.php?action=reset_password&token=" . urlencode($token);

        $subject = "Recuperación de Contraseña - " . ($this->config['from_name'] ?? APP_NAME);

        $body = "
        <html>
        <head>
            <style>
                body { font-family: Arial, sans-serif; line-height: 1.6; color: #333; }
                .container { max-width: 600px; margin: 0 auto; padding: 20px; }
                .header { background: linear-gradient(135deg, #667eea 0%, #764ba2 100%); color: white; padding: 30px; text-align: center; border-radius: 10px 10px 0 0; }
                .content { background: #f9f9f9; padding: 30px; border-radius: 0 0 10px 10px; }
                .button { display: inline-block; padding: 12px 30px; background: #667eea; color: white; text-decoration: none; border-radius: 5px; margin: 20px 0; }
                .footer { text-align: center; margin-top: 20px; color: #666; font-size: 12px; }
            </style>
        </head>
        <body>
            <div class='container'>
                <div class='header'>
                    <h1>Recuperación de Contraseña</h1>
                </div>
                <div class='content'>
                    <p>Hola <strong>{$username}</strong>,</p>
                    <p>Hemos recibido una solicitud para restablecer tu contraseña. Si no realizaste esta solicitud, puedes ignorar este correo.</p>
                    <p>Para restablecer tu contraseña, haz clic en el siguiente botón:</p>
                    <p style='text-align: center;'>
                        <a href='{$reset_link}' class='button'>Restablecer Contraseña</a>
                    </p>
                    <p>O copia y pega el siguiente enlace en tu navegador:</p>
                    <p style='word-break: break-all; background: white; padding: 10px; border-left: 3px solid #667eea;'>{$reset_link}</p>
                    <p><strong>Este enlace expirará en 1 hora.</strong></p>
                    <p>Si tienes algún problema, contacta al administrador del sistema.</p>
                </div>
                <div class='footer'>
                    <p>Este es un correo automático, por favor no respondas a este mensaje.</p>
                    <p>&copy; " . date('Y') . " {$this->config['from_name']}. Todos los derechos reservados.</p>
                </div>
            </div>
        </body>
        </html>
        ";

        return $this->send($email, $subject, $body);
    }

    /**
     * Enviar correo genérico
     */
    public function send($to, $subject, $body, $altBody = '') {
        $mail = new PHPMailer(true);

        try {
            // Configuración del servidor
            $mail->isSMTP();
            $mail->Host       = $this->config['smtp_host'];
            $mail->SMTPAuth   = true;
            $mail->Username   = $this->config['smtp_username'];
            $mail->Password   = $this->config['smtp_password'];
            $mail->SMTPSecure = $this->config['smtp_secure'];
            $mail->Port       = $this->config['smtp_port'];
            $mail->CharSet    = 'UTF-8';

            // Debug (solo en desarrollo)
            if (isset($this->config['smtp_debug']) && $this->config['smtp_debug'] > 0) {
                $mail->SMTPDebug = SMTP::DEBUG_SERVER;
            }

            // Destinatarios
            $mail->setFrom($this->config['from_email'], $this->config['from_name']);
            $mail->addAddress($to);
            $mail->addReplyTo($this->config['from_email'], $this->config['from_name']);

            // Contenido
            $mail->isHTML(true);
            $mail->Subject = $subject;
            $mail->Body    = $body;
            $mail->AltBody = $altBody ?: strip_tags($body);

            $mail->send();

            write_log('INFO', 'Email sent successfully', [
                'to' => $to,
                'subject' => $subject
            ]);

            return [
                'success' => true,
                'message' => 'Correo enviado exitosamente'
            ];

        } catch (Exception $e) {
            write_log('ERROR', 'Email send failed', [
                'to' => $to,
                'error' => $mail->ErrorInfo
            ]);

            return [
                'success' => false,
                'message' => 'No se pudo enviar el correo: ' . $mail->ErrorInfo
            ];
        }
    }

    /**
     * Actualizar configuración de email
     */
    public function updateConfig($config) {
        $this->config = array_merge($this->config, $config);
        return true;
    }

    /**
     * Probar conexión SMTP
     */
    public function testConnection() {
        $mail = new PHPMailer(true);

        try {
            $mail->isSMTP();
            $mail->Host       = $this->config['smtp_host'];
            $mail->SMTPAuth   = true;
            $mail->Username   = $this->config['smtp_username'];
            $mail->Password   = $this->config['smtp_password'];
            $mail->SMTPSecure = $this->config['smtp_secure'];
            $mail->Port       = $this->config['smtp_port'];
            $mail->SMTPDebug  = SMTP::DEBUG_CONNECTION;

            // Intentar conectar
            $mail->smtpConnect();
            $mail->smtpClose();

            return [
                'success' => true,
                'message' => 'Conexión SMTP exitosa'
            ];

        } catch (Exception $e) {
            return [
                'success' => false,
                'message' => 'Error de conexión: ' . $mail->ErrorInfo
            ];
        }
    }
}
