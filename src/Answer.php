<?php

namespace MaximeRainville\SilverstripeServerFingerprint;

use MaximeRainville\SilverstripeServerFingerprint\Questions\Question;
use SilverStripe\ORM\DataObject;


/**
 * Some information about a specifc server instance
 */
class Answer extends DataObject
{

    private static $db = [
        'Output' => 'Text'
    ];

    private static $indexes = [
    ];

    private static $has_one = [
        'Question' => Question::class,
        'Server' => Server::class
    ];

    private static $owned_by = [
        'Server'
    ];

    private static $table_name = 'ServerAnswer';

    private static ?self $current = null;

    private static $summary_fields = [
        'Question.Title' => 'Title',
        'Question.ClassName' => 'Type'
    ];

    public function canCreate($member = null, $context = [])
    {
        return false;
    }

    public function canEdit($member = null, $context = [])
    {
        return false;
    }

    public function getCMSFields()
    {
        $fields = parent::getCMSFields();

        $fields->fieldByName('Root.Main.Output')->addExtraClass('text-monospace');

        return $fields;
    }
}
