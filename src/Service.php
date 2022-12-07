<?php

namespace MaximeRainville\SilverstripeServerFingerprint;

interface Service
{

    /**
     * Generate a server specific string that should not change overtime
     */
    public function fingerprint(): string;
}
