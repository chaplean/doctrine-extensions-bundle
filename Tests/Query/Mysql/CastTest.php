<?php

namespace Chaplean\Bundle\DoctrineExtensionsBundle\Tests\Query\Mysql;

use Chaplean\Bundle\UnitBundle\Test\LogicalTest;

/**
 * CastTest.php.
 *
 * @author    Valentin - Chaplean <valentin@chaplean.com>
 * @copyright 2014 - 2015 Chaplean (http://www.chaplean.com)
 * @since     1.0.0
 */
class CastTest extends LogicalTest
{
    public function testNamespaceDefined()
    {
        $this->assertTrue(class_exists('DoctrineExtensions\Query\Mysql\IfElse'));
    }
}