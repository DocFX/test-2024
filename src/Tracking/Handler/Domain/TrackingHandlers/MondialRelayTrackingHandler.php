<?php

namespace App\Tracking\Handler\Domain\TrackingHandlers;

use App\Customer\CustomerResolver;
use App\Tracking\Handler\TrackingHandlerInterface;
use App\Tracking\Sdk\MondialRelay\Dto\MondialRelayTrackingStatus;
use App\Tracking\Sdk\MondialRelay\MondialRelayTrackingProvider;

readonly class MondialRelayTrackingHandler implements TrackingHandlerInterface
{
    private MondialRelayTrackingProvider $mondialRelayTrackingProvider;
    private CustomerResolver $customerResolver;

    public function __construct()
    {
        $this->mondialRelayTrackingProvider = new MondialRelayTrackingProvider();
        $this->customerResolver = new CustomerResolver();
    }

    public function supports(string $trackingCode): bool
    {
        return preg_match('/MR-D?\\d{9}/', $trackingCode);
    }

    public function handle(string $trackingCode): void
    {
        $trackingDTO = $this->mondialRelayTrackingProvider->provide($trackingCode);
        $customerDTO = $this->customerResolver->resolveByParcelTrackingId($trackingCode);

        echo match ($trackingDTO->status) {
            MondialRelayTrackingStatus::Sent->value => '['.$customerDTO->name.' <'.$customerDTO->email.'>] New Mondial Relay parcel "'.$trackingCode.'" pending.',
            MondialRelayTrackingStatus::Delivered->value => '['.$customerDTO->name.' <'.$customerDTO->email.'>] New Mondial Relay parcel "'.$trackingCode.'" received.',
            MondialRelayTrackingStatus::Lost->value => '['.$customerDTO->name.' <'.$customerDTO->email.'>] New Mondial Relay parcel "'.$trackingCode.'" lost.',
            default => '['.$customerDTO->name.' <'.$customerDTO->email.'>] New Mondial Relay parcel "'.$trackingCode.'" missing.',
        };
    }
}
