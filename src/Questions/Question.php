<?php

namespace MaximeRainville\SilverstripeServerFingerprint\Questions;

use MaximeRainville\SilverstripeServerFingerprint\Answer;
use SilverStripe\ORM\DataObject;


/**
 * A question to ask from a specifc Server instance.
 */
class Question extends DataObject
{

    private static $db = [
        'Title' => 'Varchar',
        'OnEveryRequest' => 'Boolean'
    ];

    private static $default = [
        'OnEveryRequest' => false
    ];

    private static $indexes = [
    ];

    private static $has_many = [
        'Answers' => Answer::class
    ];

    private static $table_name = 'ServerQuestion';

    private static ?self $current = null;

    private static $summary_fields = [
        'Title',
        'Summary'
    ];

    public function getSummary(): string
    {
        return '';
    }

    public function Ask(): string
    {
        return 'test';
    }

    public function getCMSFields()
    {
        $fields = parent::getCMSFields();

        $fields->removeByName('Answers');

        return $fields;
    }

}
