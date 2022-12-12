<?php

namespace MaximeRainville\SilverstripeServerFingerprint\Questions;

use MaximeRainville\SilverstripeServerFingerprint\Answer;
use SilverStripe\ORM\DataObject;


/**
 * A question to ask from a specifc Server instance.
 */
class ListFolder extends Question
{

    private static $db = [
        'Root' => 'Enum("BASE_PATH,TEMP_PATH,ASSETS_PATH,PUBLIC_PATH,System", "BASE_PATH")',
        'Path' => 'Varchar'
    ];

    private static $table_name = 'ListFolderServerQuestion';

    public function Ask(): string
    {
        $roots = [
            'BASE_PATH' => BASE_PATH,
            'TEMP_PATH' => TEMP_PATH,
            'ASSETS_PATH' => ASSETS_PATH,
            'PUBLIC_PATH' => PUBLIC_PATH,
            'System' => ''
        ];

        $root = $roots[$this->Root] ?: BASE_PATH;

        $cmd = sprintf('ls -la %s%s%s', $root, DIRECTORY_SEPARATOR, $this->Path);
        $success = exec($cmd, $output);

        if ($success) {
            return $cmd . "\n" . implode("\n", $output);
        }

        return 'Listing Failed';
    }

}
