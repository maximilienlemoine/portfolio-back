<?php

namespace App\Service;

use Symfony\Component\Mailer\Exception\TransportExceptionInterface;
use Symfony\Component\Mailer\MailerInterface;
use Symfony\Component\Mime\Email;

class MailerService
{
    private MailerInterface $mailer;
    private string $noReplyEmail;

    public function __construct(MailerInterface $mailer, string $noReplyEmail)
    {
        $this->mailer = $mailer;
        $this->noReplyEmail = $noReplyEmail;
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
            ->from($this->noReplyEmail)
            ->to($destinataire)
            ->html($corps);
        $this->mailer->send($mail);
    }
}