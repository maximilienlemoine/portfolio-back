<?php

namespace App\Test\Controller;

use App\Entity\Stack;
use Doctrine\ORM\EntityManagerInterface;
use Doctrine\ORM\EntityRepository;
use Symfony\Bundle\FrameworkBundle\KernelBrowser;
use Symfony\Bundle\FrameworkBundle\Test\WebTestCase;

class StackControllerTest extends WebTestCase
{
    private KernelBrowser $client;
    private EntityManagerInterface $manager;
    private EntityRepository $repository;
    private string $path = '/stack/';

    protected function setUp(): void
    {
        $this->client = static::createClient();
        $this->manager = static::getContainer()->get('doctrine')->getManager();
        $this->repository = $this->manager->getRepository(Stack::class);

        foreach ($this->repository->findAll() as $object) {
            $this->manager->remove($object);
        }

        $this->manager->flush();
    }

    public function testIndex(): void
    {
        $crawler = $this->client->request('GET', $this->path);

        self::assertResponseStatusCodeSame(200);
        self::assertPageTitleContains('Stack index');

        // Use the $crawler to perform additional assertions e.g.
        // self::assertSame('Some text on the page', $crawler->filter('.p')->first());
    }

    public function testNew(): void
    {
        $this->markTestIncomplete();
        $this->client->request('GET', sprintf('%snew', $this->path));

        self::assertResponseStatusCodeSame(200);

        $this->client->submitForm('Save', [
            'stack[title]' => 'Testing',
            'stack[icon]' => 'Testing',
            'stack[color]' => 'Testing',
            'stack[skills]' => 'Testing',
            'stack[projects]' => 'Testing',
        ]);

        self::assertResponseRedirects($this->path);

        self::assertSame(1, $this->repository->count([]));
    }

    public function testShow(): void
    {
        $this->markTestIncomplete();
        $fixture = new Stack();
        $fixture->setTitle('My Title');
        $fixture->setIcon('My Title');
        $fixture->setColor('My Title');
        $fixture->setSkills('My Title');
        $fixture->setProjects('My Title');

        $this->manager->persist($fixture);
        $this->manager->flush();

        $this->client->request('GET', sprintf('%s%s', $this->path, $fixture->getId()));

        self::assertResponseStatusCodeSame(200);
        self::assertPageTitleContains('Stack');

        // Use assertions to check that the properties are properly displayed.
    }

    public function testEdit(): void
    {
        $this->markTestIncomplete();
        $fixture = new Stack();
        $fixture->setTitle('Value');
        $fixture->setIcon('Value');
        $fixture->setColor('Value');
        $fixture->setSkills('Value');
        $fixture->setProjects('Value');

        $this->manager->persist($fixture);
        $this->manager->flush();

        $this->client->request('GET', sprintf('%s%s/edit', $this->path, $fixture->getId()));

        $this->client->submitForm('Update', [
            'stack[title]' => 'Something New',
            'stack[icon]' => 'Something New',
            'stack[color]' => 'Something New',
            'stack[skills]' => 'Something New',
            'stack[projects]' => 'Something New',
        ]);

        self::assertResponseRedirects('/stack/');

        $fixture = $this->repository->findAll();

        self::assertSame('Something New', $fixture[0]->getTitle());
        self::assertSame('Something New', $fixture[0]->getIcon());
        self::assertSame('Something New', $fixture[0]->getColor());
        self::assertSame('Something New', $fixture[0]->getSkills());
        self::assertSame('Something New', $fixture[0]->getProjects());
    }

    public function testRemove(): void
    {
        $this->markTestIncomplete();
        $fixture = new Stack();
        $fixture->setTitle('Value');
        $fixture->setIcon('Value');
        $fixture->setColor('Value');
        $fixture->setSkills('Value');
        $fixture->setProjects('Value');

        $this->manager->persist($fixture);
        $this->manager->flush();

        $this->client->request('GET', sprintf('%s%s', $this->path, $fixture->getId()));
        $this->client->submitForm('Delete');

        self::assertResponseRedirects('/stack/');
        self::assertSame(0, $this->repository->count([]));
    }
}
