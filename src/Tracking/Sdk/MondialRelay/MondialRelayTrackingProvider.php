<?php

namespace App\Tracking\Sdk\MondialRelay;

use App\Tracking\Handler\Domain\TrackingFailureException;
use App\Tracking\Sdk\MondialRelay\Dto\MondialRelayTrackingResponse;
use App\Tracking\Sdk\MondialRelay\Dto\MondialRelayTrackingStatus;

class MondialRelayTrackingProvider
{
    public function provide(string $trackingCode): MondialRelayTrackingResponse
    {
        $parcels = [
            'MR-123456789' => MondialRelayTrackingStatus::Sent->value,
            'MR-D123456789' => MondialRelayTrackingStatus::Delivered->value,
        ];

        foreach ($parcels as $parcelCode => $parcelStatus) {
            if ($parcelCode === $trackingCode) {
                return new MondialRelayTrackingResponse(
                    $trackingCode,
                    $parcelStatus,
                    '78 rue Professeur Rochaix, 69003 LYON'
                );
            }
        }

        throw new TrackingFailureException(
            sprintf('Could not find MondialRelay parcel tracking with ID "%s".', $trackingCode)
        );
    }
}
