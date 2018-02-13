<?php
declare(strict_types=1);

namespace Core\Main\Infrastructure\Persistence\Doctrine;

use Doctrine\DBAL\Logging\SQLLogger;

class FileSQLLogger implements SQLLogger
{
    /**
     * Executed SQL queries.
     *
     * @var array
     */
    public $query = [];

    /**
     * @var string
     */
    protected $file;

    /**
     * @var float|null
     */
    public $start = null;

    /**
     * @var integer
     */
    public $currentQuery = 0;

    /**
     * FileSQLLogger constructor.
     * @param string $file
     */
    public function __construct(string $file)
    {
        if (!is_dir(dirname($file))) {
            mkdir(dirname($file));
        }
        $this->file = $file;
    }

    /**
     * Logs a SQL statement somewhere.
     *
     * @param string $sql The SQL to be executed.
     * @param array|null $params The SQL parameters.
     * @param array|null $types The SQL parameter types.
     *
     * @return void
     */
    public function startQuery($sql, array $params = null, array $types = null)
    {
        $this->start = microtime(true);
        $this->query = [
            'sql' => $sql,
            'params' => $params,
            'types' => $types,
            'executionMS' => 0
        ];
    }

    /**
     * Marks the last started query as stopped. This can be used for timing of queries.
     *
     * @return void
     */
    public function stopQuery()
    {
        $this->query['executionMS'] = microtime(true) - $this->start;

        $logLineTemplate = <<<MSG

timestamp => %s
sql => %s,
params => %s
types => %s,
executionMS => %s

MSG;

        $logLine = sprintf(
            $logLineTemplate,
            time(),
            $this->query['sql'],
            var_export($this->query['params'], true),
            var_export($this->query['types'], true),
            $this->query['executionMS']
        );

        file_put_contents($this->file, $logLine, FILE_APPEND);
    }
}
