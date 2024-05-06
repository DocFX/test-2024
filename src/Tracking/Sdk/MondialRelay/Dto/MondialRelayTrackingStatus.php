<?php

namespace App\Tracking\Sdk\MondialRelay\Dto;

enum MondialRelayTrackingStatus: int
{
    case Sent = 2;
    case Delivered = 3;
    case Lost = 4; // Out of requirements scope, assumed ;)

    public function isDelivered(): bool
    {
        return self::Delivered->value === $this->value;
    }
}
