<?php

namespace MaximeRainville\SilverstripeServerFingerprint;

use SilverStripe\Admin\ModelAdmin;

class Admin extends ModelAdmin
{

    private static $managed_models = [Server::class];

    private static $menu_title = 'Server Fingerprints';

    private static $url_segment = 'server-finger-prints';
}
