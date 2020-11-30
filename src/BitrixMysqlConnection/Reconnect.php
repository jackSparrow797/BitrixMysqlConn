<?php

namespace Jack797\BitrixMysqlConnection;

trait Reconnect
{
    protected $reconnectErrors = [2006];

    /** @var int */
    protected $maxAttempts = 5;

    /** @var int */
    protected $attempts = 0;

    /**
     * @return bool
     */
    protected function isNeedTryConnect(): bool
    {
        return $this->maxAttempts > $this->attempts;
    }

    /**
     * @return bool
     */
    protected function isNeedReconnect(): bool
    {
        /** @var $con \mysqli */
        $con = $this->resource;
        return in_array($con->errno, $this->reconnectErrors, true);
    }


    private function newAttempt(): void
    {
        $this->attempts++;
    }
}