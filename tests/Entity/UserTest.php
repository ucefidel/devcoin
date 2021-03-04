<?php

namespace App\Tests\Entity;

use App\Entity\User;
use PHPUnit\Framework\TestCase;

class UserTest extends TestCase
{
    /** @test */
    public function first_test()
    {
        $userTest = new User();

        $this->assertEquals(
            substr($userTest->getLastName(), 0, 1) . ". " . $userTest->getFirstName(),
            $userTest->getFullName()
        );
    }
}