<?php

namespace HXPHP\System\Services\Email;

use PHPMailer\PHPMailer\PHPMailer;

class Email
{
    private $from = null;

    public function setFrom(array $from = []): self
    {
        $this->from = $from;

        return $this;
    }

    /**
     * Envia e-mail.
     *
     * @param string $to E-mail para qual será enviada a mensagem
     * @param string $assunto Assunto da mensagem
     * @param string $message Mensagem
     * @param bool $accept_html Define se a mensagem será enviada em TXT ou HTML
     *
     * @return bool Status de envio e mensagem
     */
    public function send(string $to, string $subject, string $message, bool $accept_html = true): bool
    {
        $mail = new PHPMailer(true);
        try {
            $mail->isSMTP();
            $mail->Host = 'smtp.gmail.com';
            $mail->SMTPAuth = true;
            $mail->Username = 'suporte.aduv@gmail.com';
            $mail->Password = 'wzDB481Y';
            $mail->SMTPSecure = 'tls';
            $mail->Port = 587;

            $mail->setFrom($this->from['from_mail'], $this->from['from_name']);
            $mail->addAddress($to);

            $mail->isHTML($accept_html);
            $mail->Subject = $subject;
            $mail->Body = $message;
            $mail->AltBody = $message;

            $mail->send();
            return true;
        } catch (Exception $e) {
            return false;
        }
    }
}
