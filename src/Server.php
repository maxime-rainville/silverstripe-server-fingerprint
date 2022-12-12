<?php

namespace MaximeRainville\SilverstripeServerFingerprint;

use MaximeRainville\SilverstripeServerFingerprint\Questions\Question;
use SilverStripe\Core\ClassInfo;
use SilverStripe\Core\Injector\Injector;
use SilverStripe\Forms\CompositeValidator;
use SilverStripe\ORM\DataObject;
use SilverStripe\ORM\DB;
use SilverStripe\ORM\FieldType\DBDatetime;

/**
 * @property string $Fingerprint
 * @property string $Title
 * @property DateTime $LastAccess
 * @method DataList|Answer[] Answers()
 */
class Server extends DataObject
{

    private static $db = [
        'Nickname' => 'Varchar',
        'Fingerprint' => 'Varchar',
        'LastAccess' => 'DBDatetime'
    ];

    private static $indexes = [
        'Fingerprint' => true
    ];

    private static $table_name = 'ServerFingerprint';

    private static ?self $current = null;

    private static $summary_fields = [
        'ID',
        'Nickname',
        'Fingerprint',
        'LastAccess',
        'IsCurrent'
    ];

    private static $has_many = [
        'Answers' => Answer::class
    ];

    private static $owns = [
        'Answers'
    ];

    private static $cascade_deletes = [
        'Answers',
    ];

    public function current(): self
    {
        if (self::$current) {
            return self::$current;
        }

        /** @var Service $service */
        $service = Injector::inst()->get(Service::class);

        $fingerprint = $service->fingerprint();

        $server = $this->byFingerPrint($fingerprint);
        if (!$server) {
            $server = self::create();
            $server->Fingerprint = $fingerprint;
        }
        $server->touch();
        $server->write();

        self::$current = $server;
        return $server;
    }

    public function IsCurrent(): bool
    {
        return $this->current()->Fingerprint === $this->Fingerprint;
    }

    public function byFingerPrint(string $fingerprint): ?self
    {
        return self::get()->filter('Fingerprint', $fingerprint)->first();
    }

    private function touch(): void
    {
        $this->LastAccess = DBDatetime::now()->getValue();
    }

    public static function isReady(): bool
    {
        if (!DB::get_conn()->isActive()) {
            return false;
        }

        $schema = DataObject::getSchema();

        // Require table
        $table = $schema->tableName(self::class);
        if (!ClassInfo::hasTable($table)) {
            return false;
        }

        // Require table
        $dbFields = DB::field_list($schema->tableName(self::class));
        if (empty($dbFields)) {
            return false;
        }

        // Ensure that SiteConfig has all fields
        $objFields = $schema->databaseFields(self::class);
        $missingFields = array_diff_key($objFields ?? [], $dbFields);
        return empty($missingFields);
    }

    public function canCreate($member = null, $context = [])
    {
        return false;
    }

    public function getCMSFields()
    {
        $fields = parent::getCMSFields();

        $fields->fieldByName('Root.Main.Fingerprint')->setReadonly(true);
        $fields->fieldByName('Root.Main.LastAccess')->setReadonly(true);

        return $fields;
    }

    public function getHeader()
    {
        if ($this->Nickname) {
            return sprintf('%s %s', $this->Nickname, $this->Fingerprint);
        }

        return $this->Fingerprint;
    }

    public function answerQuestions(): void
    {
        $answeredQuestionIDs = $this->Answers()->column('QuestionID');
        $unansweredQuestions = Question::get();

        if (!empty($answeredQuestionIDs)) {
            $unansweredQuestions = $unansweredQuestions->exclude(['ID' => $answeredQuestionIDs]);
        }

        foreach ($unansweredQuestions as $question) {
            /** @var Question $question */
            $answer = Answer::create();
            $answer->ServerID = $this->ID;
            $answer->QuestionID = $question->ID;
            $answer->Output = $question->ask();
            $answer->write();
        }
    }

}
