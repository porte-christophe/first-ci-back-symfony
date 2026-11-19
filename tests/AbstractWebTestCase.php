<?php

namespace App\Tests;

use App\Repository\UserRepository;
use Doctrine\Bundle\DoctrineBundle\Repository\ServiceEntityRepository;
use Symfony\Bundle\FrameworkBundle\KernelBrowser;
use Symfony\Bundle\FrameworkBundle\Test\WebTestCase;
use Symfony\Component\DomCrawler\Crawler;

abstract class AbstractWebTestCase extends WebTestCase
{
    public const string URL_HOME = '/';
    public const string URL_LOGIN = '/login';
    public const string URL_REGISTER = '/register';
    public const string URL_FAVORITE = '/favoris';
    public const string URL_ARTIST = '/artiste';
    public const string URL_ALBUM = '/album';
    public const string URL_ADMIN_ALBUM_NEW = '/admin/album/new';
    public const string URL_PROFILE = '/profil';

    protected function loginUser(
        KernelBrowser $client,
        string $email,
    ): void {
        $userRepo = $this->getRepository(UserRepository::class);
        $user = $userRepo->findOneBy(['email' => $email]);
        if (null !== $user) {
            $client->loginUser($user);
        }
    }

    protected function getRepository(string $repoClass): ServiceEntityRepository
    {
        return $this->getContainer()->get($repoClass);
    }

    protected function removeEntity(?object $object): void
    {
        if (null !== $object) {
            $em = $this->getContainer()->get('doctrine.orm.entity_manager');
            $em->remove($object);
            $em->flush();
        }
    }

    protected function fillAndSendForm(KernelBrowser $client, Crawler $crawler, string $formSelector, array $formData): void
    {
        $form = $crawler->selectButton($formSelector)->form();
        foreach ($formData as $name => $value) {
            $form[$name] = $value;
        }
        $client->submit($form);
    }
}
