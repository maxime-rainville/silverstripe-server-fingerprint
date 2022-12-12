<?php

namespace MaximeRainville\SilverstripeServerFingerprint;

use MaximeRainville\SilverstripeServerFingerprint\Questions\Question;
use SilverStripe\Admin\ModelAdmin;
use SilverStripe\Forms\GridField\GridFieldAddNewButton;
use SilverStripe\Forms\GridField\GridFieldConfig;
use Symbiote\GridFieldExtensions\GridFieldAddNewMultiClass;

class Admin extends ModelAdmin
{

    private static $managed_models = [Server::class, Question::class];

    private static $menu_title = 'Server Fingerprints';

    private static $url_segment = 'server-finger-prints';


    protected function getGridFieldConfig(): GridFieldConfig
    {
        $config = parent::getGridFieldConfig();

        if ($this->modelClass === Question::class) {
            $config->removeComponentsByType(GridFieldAddNewButton::class)
                ->addComponent(GridFieldAddNewMultiClass::create());
        }

        return $config;
    }

}
