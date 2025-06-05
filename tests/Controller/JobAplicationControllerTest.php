<?php

namespace App\Tests\Controller;

use App\Entity\JobAplication;
use Doctrine\ORM\EntityManagerInterface;
use Doctrine\ORM\EntityRepository;
use Symfony\Bundle\FrameworkBundle\KernelBrowser;
use Symfony\Bundle\FrameworkBundle\Test\WebTestCase;

final class JobAplicationControllerTest extends WebTestCase
{
    private KernelBrowser $client;
    private EntityManagerInterface $manager;
    private EntityRepository $jobAplicationRepository;
    private string $path = '/job/aplication/';

    protected function setUp(): void
    {
        $this->client = static::createClient();
        $this->manager = static::getContainer()->get('doctrine')->getManager();
        $this->jobAplicationRepository = $this->manager->getRepository(JobAplication::class);

        foreach ($this->jobAplicationRepository->findAll() as $object) {
            $this->manager->remove($object);
        }

        $this->manager->flush();
    }

    public function testIndex(): void
    {
        $this->client->followRedirects();
        $crawler = $this->client->request('GET', $this->path);

        self::assertResponseStatusCodeSame(200);
        self::assertPageTitleContains('JobAplication index');

        // Use the $crawler to perform additional assertions e.g.
        // self::assertSame('Some text on the page', $crawler->filter('.p')->first());
    }

    public function testNew(): void
    {
        $this->markTestIncomplete();
        $this->client->request('GET', sprintf('%snew', $this->path));

        self::assertResponseStatusCodeSame(200);

        $this->client->submitForm('Save', [
            'job_aplication[coverLetter]' => 'Testing',
            'job_aplication[createdAt]' => 'Testing',
            'job_aplication[user]' => 'Testing',
            'job_aplication[job]' => 'Testing',
        ]);

        self::assertResponseRedirects($this->path);

        self::assertSame(1, $this->jobAplicationRepository->count([]));
    }

    public function testShow(): void
    {
        $this->markTestIncomplete();
        $fixture = new JobAplication();
        $fixture->setCoverLetter('My Title');
        $fixture->setCreatedAt('My Title');
        $fixture->setUser('My Title');
        $fixture->setJob('My Title');

        $this->manager->persist($fixture);
        $this->manager->flush();

        $this->client->request('GET', sprintf('%s%s', $this->path, $fixture->getId()));

        self::assertResponseStatusCodeSame(200);
        self::assertPageTitleContains('JobAplication');

        // Use assertions to check that the properties are properly displayed.
    }

    public function testEdit(): void
    {
        $this->markTestIncomplete();
        $fixture = new JobAplication();
        $fixture->setCoverLetter('Value');
        $fixture->setCreatedAt('Value');
        $fixture->setUser('Value');
        $fixture->setJob('Value');

        $this->manager->persist($fixture);
        $this->manager->flush();

        $this->client->request('GET', sprintf('%s%s/edit', $this->path, $fixture->getId()));

        $this->client->submitForm('Update', [
            'job_aplication[coverLetter]' => 'Something New',
            'job_aplication[createdAt]' => 'Something New',
            'job_aplication[user]' => 'Something New',
            'job_aplication[job]' => 'Something New',
        ]);

        self::assertResponseRedirects('/job/aplication/');

        $fixture = $this->jobAplicationRepository->findAll();

        self::assertSame('Something New', $fixture[0]->getCoverLetter());
        self::assertSame('Something New', $fixture[0]->getCreatedAt());
        self::assertSame('Something New', $fixture[0]->getUser());
        self::assertSame('Something New', $fixture[0]->getJob());
    }

    public function testRemove(): void
    {
        $this->markTestIncomplete();
        $fixture = new JobAplication();
        $fixture->setCoverLetter('Value');
        $fixture->setCreatedAt('Value');
        $fixture->setUser('Value');
        $fixture->setJob('Value');

        $this->manager->persist($fixture);
        $this->manager->flush();

        $this->client->request('GET', sprintf('%s%s', $this->path, $fixture->getId()));
        $this->client->submitForm('Delete');

        self::assertResponseRedirects('/job/aplication/');
        self::assertSame(0, $this->jobAplicationRepository->count([]));
    }
}
