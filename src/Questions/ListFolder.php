<?php

namespace MaximeRainville\SilverstripeServerFingerprint\Questions;

/**
 * A question to ask from a specific Server instance.
 *
 * @property string $Root
 * @property string $Path
 * @property bool $Recursive
 */
class ListFolder extends Question
{

    private static $db = [
        'Root' => 'Enum("BASE_PATH,TEMP_PATH,ASSETS_PATH,PUBLIC_PATH,System", "BASE_PATH")',
        'Path' => 'Varchar',
        'Recursive' => 'Boolean'
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

        $param = '-la' . ($this->Recursive ? 'R' : '');

        $root = $roots[$this->Root] ?: BASE_PATH;

        $cmd = sprintf('ls %s %s%s%s', $param, $root, DIRECTORY_SEPARATOR, $this->Path);
        $success = exec($cmd, $output);

        if ($success) {
            return $cmd . "\n" . implode("\n", $output);
        }

        return 'Listing Failed';
    }

}
