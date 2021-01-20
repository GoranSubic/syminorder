<?php

namespace App\EventSubscriber;

use ApiPlatform\Core\EventListener\EventPriorities;
use App\Entity\Order;
use Symfony\Bridge\Twig\Mime\TemplatedEmail;
use Symfony\Component\EventDispatcher\EventSubscriberInterface;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\HttpKernel\Event\ViewEvent;
use Symfony\Component\HttpKernel\KernelEvents;
use Symfony\Component\Mailer\MailerInterface;
use Symfony\Component\Mime\Address;

final class OrderMailSubscriber implements EventSubscriberInterface
{
    private $mailer;

    public function __construct(MailerInterface $mailer)
    {
        $this->mailer = $mailer;
    }

    public static function getSubscribedEvents()
    {
        return [
            KernelEvents::VIEW => ['sendMail', EventPriorities::POST_WRITE],
        ];
    }

    public function sendMail(ViewEvent $event): void
    {
        $order = $event->getControllerResult();
        $method = $event->getRequest()->getMethod();

        if (!$order instanceof Order || Request::METHOD_POST !== $method) {
            return;
        }

        $templatedEmail = (new TemplatedEmail())
                ->from(new Address('info@olala.co.rs', 'Olala Porudžbina'))
                ->to(new Address('porudzbine@olala.co.rs'))
                ->subject('Olala Nova Porudžbina')
                ->htmlTemplate('notification/order_notification_email.html.twig');

        $context = $templatedEmail->getContext();
        $context['orderId'] = $order->getId();
        $context['orderUser'] = $order->getCustomer()->getUsername() ? $order->getCustomer()->getUsername() : "NoName ;-)";
        $context['address'] = $order->getAddress();
        $context['phone'] = $order->getPhone();
        $context['note'] = $order->getNoteCart();

        $templatedEmail->context($context);

        $this->mailer->send($templatedEmail);
    }
}