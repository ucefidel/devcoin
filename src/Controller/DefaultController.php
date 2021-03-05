<?php


namespace App\Controller;


use Doctrine\ORM\EntityManagerInterface;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\Security\Core\Encoder\UserPasswordEncoderInterface;
use Symfony\Component\Security\Core\Security;
use Symfony\Component\String\Slugger\SluggerInterface;

class DefaultController extends AbstractController
{
    protected EntityManagerInterface $manager;
    protected UserPasswordEncoderInterface $encoder;
    protected Security $security;
    protected SluggerInterface $slugger;

    public function __construct(
        EntityManagerInterface $manager,
        UserPasswordEncoderInterface $encoder,
        Security $security,
        SluggerInterface $slugger
    )
    {
        $this->manager = $manager;
        $this->encoder = $encoder;
        $this->security = $security;
        $this->slugger = $slugger;
    }

}