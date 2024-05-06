<?php

namespace App\Tracking\Sdk\SoColissimo;

use App\Tracking\Handler\Domain\TrackingFailureException;
use App\Tracking\Sdk\SoColissimo\Dto\ColissimoRelayPointAddressResponse;
use App\Tracking\Sdk\SoColissimo\Dto\ColissimoTrackingResponse;
use App\Tracking\Sdk\SoColissimo\Dto\ColissimoTrackingStatus;

class ColissimoTrackingProvider
{
    public function provide (string $trackingCode): ColissimoTrackingResponse
    {
        $parcels = [
            'SOCO-D123456789' => ColissimoTrackingStatus::Delivered,
            'SOCO-123456789' => ColissimoTrackingStatus::Sent,
        ];

        foreach ($parcels as $parcelCode => $parcelStatus) {
            if ($parcelCode === $trackingCode) {
                return new ColissimoTrackingResponse(
                    $trackingCode,
                    $parcelStatus,
                    new ColissimoRelayPointAddressResponse(
                        '78 rue Professeur Rochaix',
                        'Lyon',
                        '69003',
                        'FR'
                    )
                );
            }
        }

        throw new TrackingFailureException(
            sprintf('Could not find Colissimo parcel tracking with ID "%s".', $trackingCode)
        );
    }
}
