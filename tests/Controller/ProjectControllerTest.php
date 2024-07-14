<?php

namespace App\Test\Controller;

use App\Entity\Project;
use Doctrine\ORM\EntityManagerInterface;
use Doctrine\ORM\EntityRepository;
use Symfony\Bundle\FrameworkBundle\KernelBrowser;
use Symfony\Bundle\FrameworkBundle\Test\WebTestCase;

class ProjectControllerTest extends WebTestCase
{
    private KernelBrowser $client;
    private EntityManagerInterface $manager;
    private EntityRepository $repository;
    private string $path = '/project/';

    protected function setUp(): void
    {
        $this->client = static::createClient();
        $this->manager = static::getContainer()->get('doctrine')->getManager();
        $this->repository = $this->manager->getRepository(Project::class);

        foreach ($this->repository->findAll() as $object) {
            $this->manager->remove($object);
        }

        $this->manager->flush();
    }

    public function testIndex(): void
    {
        $crawler = $this->client->request('GET', $this->path);

        self::assertResponseStatusCodeSame(200);
        self::assertPageTitleContains('Project index');

        // Use the $crawler to perform additional assertions e.g.
        // self::assertSame('Some text on the page', $crawler->filter('.p')->first());
    }

    public function testNew(): void
    {
        $this->markTestIncomplete();
        $this->client->request('GET', sprintf('%snew', $this->path));

        self::assertResponseStatusCodeSame(200);

        $this->client->submitForm('Save', [
            'project[title]' => 'Testing',
            'project[description]' => 'Testing',
            'project[projectLink]' => 'Testing',
            'project[sourceCodeLink]' => 'Testing',
            'project[goals]' => 'Testing',
            'project[stacks]' => 'Testing',
        ]);

        self::assertResponseRedirects($this->path);

        self::assertSame(1, $this->repository->count([]));
    }

    public function testShow(): void
    {
        $this->markTestIncomplete();
        $fixture = new Project();
        $fixture->setTitle('My Title');
        $fixture->setDescription('My Title');
        $fixture->setProjectLink('My Title');
        $fixture->setSourceCodeLink('My Title');
        $fixture->setGoals('My Title');
        $fixture->setStacks('My Title');

        $this->manager->persist($fixture);
        $this->manager->flush();

        $this->client->request('GET', sprintf('%s%s', $this->path, $fixture->getId()));

        self::assertResponseStatusCodeSame(200);
        self::assertPageTitleContains('Project');

        // Use assertions to check that the properties are properly displayed.
    }

    public function testEdit(): void
    {
        $this->markTestIncomplete();
        $fixture = new Project();
        $fixture->setTitle('Value');
        $fixture->setDescription('Value');
        $fixture->setProjectLink('Value');
        $fixture->setSourceCodeLink('Value');
        $fixture->setGoals('Value');
        $fixture->setStacks('Value');

        $this->manager->persist($fixture);
        $this->manager->flush();

        $this->client->request('GET', sprintf('%s%s/edit', $this->path, $fixture->getId()));

        $this->client->submitForm('Update', [
            'project[title]' => 'Something New',
            'project[description]' => 'Something New',
            'project[projectLink]' => 'Something New',
            'project[sourceCodeLink]' => 'Something New',
            'project[goals]' => 'Something New',
            'project[stacks]' => 'Something New',
        ]);

        self::assertResponseRedirects('/project/');

        $fixture = $this->repository->findAll();

        self::assertSame('Something New', $fixture[0]->getTitle());
        self::assertSame('Something New', $fixture[0]->getDescription());
        self::assertSame('Something New', $fixture[0]->getProjectLink());
        self::assertSame('Something New', $fixture[0]->getSourceCodeLink());
        self::assertSame('Something New', $fixture[0]->getGoals());
        self::assertSame('Something New', $fixture[0]->getStacks());
    }

    public function testRemove(): void
    {
        $this->markTestIncomplete();
        $fixture = new Project();
        $fixture->setTitle('Value');
        $fixture->setDescription('Value');
        $fixture->setProjectLink('Value');
        $fixture->setSourceCodeLink('Value');
        $fixture->setGoals('Value');
        $fixture->setStacks('Value');

        $this->manager->persist($fixture);
        $this->manager->flush();

        $this->client->request('GET', sprintf('%s%s', $this->path, $fixture->getId()));
        $this->client->submitForm('Delete');

        self::assertResponseRedirects('/project/');
        self::assertSame(0, $this->repository->count([]));
    }
}
