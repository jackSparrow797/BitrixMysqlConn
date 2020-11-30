<?php

namespace Jack797\BitrixMysqlConnection;

use Bitrix\Main\DB\MysqliConnection as BaseMysqliConnection;
use Bitrix\Main\DB\SqlQueryException;

class MysqliConnection extends BaseMysqliConnection
{
    use Reconnect;

    public function __construct(array $configuration)
    {
        parent::__construct($configuration);

        $this->maxAttempts = $configuration['maxAttempts'] ?? $this->maxAttempts;
    }

    /*********************************************************
     * Query
     *********************************************************/

    /**
     * Executes a query against connected database.
     * Rises SqlQueryException on any database error.
     * <p>
     * When object $trackerQuery passed then calls its startQuery and finishQuery
     * methods before and after query execution.
     *
     * @param string                            $sql          Sql query.
     * @param array                             $binds        Array of binds.
     * @param \Bitrix\Main\Diag\SqlTrackerQuery $trackerQuery Debug collector object.
     *
     * @throws \Bitrix\Main\Db\SqlQueryException
     * @return resource
     */
    protected function queryInternal($sql, array $binds = null, \Bitrix\Main\Diag\SqlTrackerQuery $trackerQuery = null)
    {
        $this->connectInternal();

        if ($trackerQuery != null) {
            $trackerQuery->startQuery($sql, $binds);
        }

        /** @var $con \mysqli */
        $con = $this->resource;
        $result = $con->query($sql, MYSQLI_STORE_RESULT);

        if ($trackerQuery != null) {
            $trackerQuery->finishQuery();
        }

        $this->lastQueryResult = $result;

        if (!$result) {
            if ($this->isNeedReconnect() && $this->isNeedTryConnect()) {
                $this->newAttempt();
                $this->connect();
                $result = $this->queryInternal($sql, $binds, $trackerQuery);
            } else {
                throw new SqlQueryException('Mysql query error', $this->getErrorMessage(), $sql);
            }
        }

        return $result;
    }

}