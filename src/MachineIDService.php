<?php

namespace MaximeRainville\SilverstripeServerFingerprint;

use LogicException;

class MachineIDService implements Service
{

    /**
     * Generate a server specific string that should not change overtime
     */
    public function fingerprint(): string
    {
        $machineID = file_get_contents('/etc/machine-id');
        if ($machineID) {
            return $machineID;
        }

        throw new LogicException('Could not read machine ID');
    }
}
