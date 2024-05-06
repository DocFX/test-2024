<?php

namespace App\Tracking\Handler\Domain\TrackingHandlers;

use App\Customer\CustomerResolver;
use App\Tracking\Handler\TrackingHandlerInterface;
use App\Tracking\Sdk\SoColissimo\ColissimoTrackingProvider;
use App\Tracking\Sdk\SoColissimo\Dto\ColissimoTrackingStatus;

class SoColissimoTrackingHandler implements TrackingHandlerInterface
{
    private ColissimoTrackingProvider $soColissimoRelayTrackingProvider;
    private CustomerResolver $customerResolver;

    public function __construct()
    {
        $this->soColissimoRelayTrackingProvider = new ColissimoTrackingProvider();
        $this->customerResolver = new CustomerResolver();
    }

    public function supports(string $trackingCode): bool
    {
        return preg_match('/SOCO-D?\\d{9}/', $trackingCode);
    }

    public function handle(string $trackingCode): void
    {
        $trackingDTO = $this->soColissimoRelayTrackingProvider->provide($trackingCode);
        $customerDTO = $this->customerResolver->resolveByParcelTrackingId($trackingCode);

        echo match ($trackingDTO->status) {
            ColissimoTrackingStatus::Sent => '['.$customerDTO->name.' <'.$customerDTO->email.'>] New SoColissimo parcel "'.$trackingCode.'" pending.',
            ColissimoTrackingStatus::Delivered => '['.$customerDTO->name.' <'.$customerDTO->email.'>] SoColissimo parcel "'.$trackingCode.'" received.',
            ColissimoTrackingStatus::Lost => '['.$customerDTO->name.' <'.$customerDTO->email.'>] New SoColissimo parcel "'.$trackingCode.'" lost.',
            default => '['.$customerDTO->name.' <'.$customerDTO->email.'>] New SoColissimo parcel "'.$trackingCode.'" missing.',
        };
    }

}
