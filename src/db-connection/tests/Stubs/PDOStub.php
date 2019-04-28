<?php

declare(strict_types=1);
/**
 * This file is part of Hyperf.
 *
 * @link     https://hyperf.org
 * @document https://wiki.hyperf.org
 * @contact  group@hyperf.org
 * @license  https://github.com/hyperf-cloud/hyperf/blob/master/LICENSE
 */

namespace HyperfTest\DbConnection\Stubs;

class PDOStub extends \PDO
{
    public $dsn;

    public $username;

    public $passwd;

    public $options;

    public function __construct(string $dsn, string $username, string $passwd, array $options)
    {
        $this->dsn = $dsn;
        $this->username = $username;
        $this->passwd = $passwd;
        $this->options = $options;
    }

    public function prepare($statement, $driver_options = null)
    {
        return new PDOStatementStub();
    }

    public function exec($statement)
    {
    }
}