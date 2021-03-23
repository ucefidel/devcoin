<?php

namespace App\Tests\Controller;

use App\Controller\AnnonceController;
use App\Controller\DefaultController;
use Doctrine\ORM\EntityManagerInterface;
use PHPUnit\Framework\TestCase;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\Security\Core\Encoder\UserPasswordEncoderInterface;
use Symfony\Component\Security\Core\Security;
use Symfony\Component\String\Slugger\SluggerInterface;

class AnnonceControllerTest extends TestCase
{
    protected AnnonceController $annonceController;

    public function setUp(): void
    {
        $manager = $this->createMock(EntityManagerInterface::class);
        $encoder = $this->createMock(UserPasswordEncoderInterface::class);
        $security = $this->createMock(Security::class);
        $slugger = $this->createMock(SluggerInterface::class);

        $this->annonceController = new AnnonceController($manager, $encoder, $security, $slugger);
    }

    /** @test */
    public function test_first_function()
    {
        $response = $this->annonceController->unit(new Request);
        $this->assertEquals(200, $response->getStatusCode());
    }
}