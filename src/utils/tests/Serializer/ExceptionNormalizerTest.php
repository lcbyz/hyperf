<?php

declare(strict_types=1);
/**
 * This file is part of Hyperf.
 *
 * @link     https://www.hyperf.io
 * @document https://doc.hyperf.io
 * @contact  group@hyperf.io
 * @license  https://github.com/hyperf-cloud/hyperf/blob/master/LICENSE
 */

namespace HyperfTest\Utils\Serializer;

use Hyperf\Utils\Serializer\ExceptionNormalizer;
use HyperfTest\Utils\Stub\SerializableException;
use PHPUnit\Framework\TestCase;

/**
 * @internal
 * @coversNothing
 */
class ExceptionNormalizerTest extends TestCase
{
    public function testInvalidArgumentException()
    {
        $normalizer = new ExceptionNormalizer();
        $ex = new \InvalidArgumentException('invalid param foo');
        $result = $normalizer->normalize($ex);
        $ret = $normalizer->denormalize($result, \InvalidArgumentException::class);
        $this->assertInstanceOf(\InvalidArgumentException::class, $ret);
        $this->assertEquals($ret->getMessage(), $ex->getMessage());
        $this->assertEquals($ret, $ex);
    }

    public function testSerializableException()
    {
        $normalizer = new ExceptionNormalizer();
        $ex = new SerializableException('invalid param foo');
        $result = $normalizer->normalize($ex);
        $ret = $normalizer->denormalize($result, SerializableException::class);
        $this->assertInstanceOf(SerializableException::class, $ret);
        $this->assertEquals($ret->getMessage(), $ex->getMessage());
        $this->assertEquals($ret, $ex);
    }
}
