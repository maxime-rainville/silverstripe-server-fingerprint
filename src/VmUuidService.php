<?php

namespace MaximeRainville\SilverstripeServerFingerprint;

use LogicException;
use SilverStripe\Core\Config\Configurable;

/**
 * Tries to identify a unique identifier for the server responding to the request.
 *
 * Looks up various files that can expected to be unique for a specific server
 * https://docs.aws.amazon.com/AWSEC2/latest/UserGuide/identify_ec2_instances.html
 */
class VmUuidService implements Service
{

    use Configurable;

    private static $uniqueIDFiles = [
        'Hypervisor' => '/sys/hypervisor/uuid',
        'BoardAssets' => '/sys/devices/virtual/dmi/id/board_asset_tag',
        'MachineID' => '/etc/machine-id',
    ];

    /**
     * Generate a server specific string that should not change overtime
     */
    public function fingerprint(): string
    {
        /** @var array $files */
        $files = self::config()->get('uniqueIDFiles');

        foreach ($files as $prefix => $filename) {
            if (file_exists($filename) && is_readable($filename)) {
                $uuid = trim(file_get_contents($filename));
                if ($uuid) {
                    return sprintf('%s::%s', $prefix, $uuid);
                }
            }
        }

        return '';
    }
}
