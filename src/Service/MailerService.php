<?php

namespace App\Service;

use Symfony\Component\Mailer\Exception\TransportExceptionInterface;
use Symfony\Component\Mailer\MailerInterface;
use Symfony\Component\Mime\Email;

class MailerService
{
    private MailerInterface $mailer;

    public function __construct(MailerInterface $mailer)
    {
        $this->mailer = $mailer;
    }

    /**
     * VALIDE UNE ADRESSE MAIL.
     */
    public function domainExists(string $mail_user, string $record = 'MX'): bool
    {
        list($user, $domain) = explode('@', $mail_user);

        return checkdnsrr($domain, $record);
    }

    /**
     * ENVOIE DE MAIL.
     * @throws TransportExceptionInterface
     */
    public function sendMail(string $object, string $destinataire, string $corps): void
    {
        $mail = (new Email())
            ->subject('Maximilien LEMOINE | '.$object)
            ->from('noreply@maximilien-lemoine.fr')
            ->to($destinataire)
            ->html($corps);
        $this->mailer->send($mail);
    }
}