<?php

declare(strict_types = 1);

namespace CodelyTv\Context\Mooc\Module\Notification\Application\Create;

use CodelyTv\Context\Mooc\Module\Notification\Domain\Notification;
use CodelyTv\Context\Mooc\Module\Notification\Domain\NotificationId;
use CodelyTv\Context\Mooc\Module\Notification\Domain\NotificationText;
use CodelyTv\Context\Mooc\Module\Notification\Domain\NotificationType;
use CodelyTv\Shared\Infrastructure\Uuid\UuidGenerator;
use CodelyTv\Shared\Domain\Bus\Event\DomainEventPublisher;

final class NotificationCreator
{
    private $uuidGenerator;
    private $publisher;

    public function __construct(
        UuidGenerator $uuidGenerator,
        DomainEventPublisher $publisher
    ) {
        $this->uuidGenerator = $uuidGenerator;
        $this->publisher     = $publisher;
    }

    public function __invoke(NotificationText $text, NotificationType $action)
    {
        $id = new NotificationId($this->uuidGenerator->next());

        $notification = Notification::create($id, $text, $action);

        $this->publisher->publish(...$notification->pullDomainEvents());
    }
}