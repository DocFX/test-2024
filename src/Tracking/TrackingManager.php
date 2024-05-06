<?php

namespace App\Tracking;

use App\Tracking\Domain\NoMatchingHandlerException;
use App\Tracking\Handler\Domain\TrackingHandlers\MondialRelayTrackingHandler;
use App\Tracking\Handler\Domain\TrackingHandlers\SoColissimoTrackingHandler;
use App\Tracking\Handler\TrackingHandlerInterface;

class TrackingManager
{
    /**
     * @var array<TrackingHandlerInterface>
     */
    private array $handlers;

    public function __construct() {
        $this->handlers = [
            'MR' => new MondialRelayTrackingHandler(),
            'SOCO' => new SoColissimoTrackingHandler(),
        ];
    }

    /**
     * @param string $trackingCode the parcel tracking code.
     *
     * @throws NoMatchingHandlerException when there is no handler that matched with given tracking code.
     */
    public function track(string $trackingCode): void
    {
        foreach ($this->handlers as $handler) {
            if ($handler->supports($trackingCode)) {
                $handler->handle($trackingCode);
                return;
            }
        }

        throw new NoMatchingHandlerException($trackingCode);
    }
}
