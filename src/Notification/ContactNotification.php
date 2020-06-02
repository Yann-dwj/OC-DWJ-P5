<?php

namespace App\Notification;

use Twig\Environment;
use App\Entity\Contact;
use Symfony\Component\Mime\Email;
use Symfony\Component\Mailer\MailerInterface;

class ContactNotification
{
    /**
     * @var MailerInterface
     */
    private $mailer;

    /**
     * @var Environment
     */
    private $renderer;

    public function __construct(MailerInterface $mailer, Environment $renderer)
    {
        $this->mailer = $mailer;
        $this->renderer = $renderer;
    }

    public function notify(Contact $contact)
    {
        $message = (new Email())
            ->from('noreply@monecole.fr')
            ->to('contact@monecole.fr')
            ->replyTo($contact->getEmail())
            ->html($this->renderer->render('emails/contact.html.twig', [
                'contact' => $contact
            ]));

            $this->mailer->send($message);
    }
}
