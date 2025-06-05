<?php

namespace App\Tests\Controller;

use App\Entity\Job;
use Doctrine\ORM\EntityManagerInterface;
use Doctrine\ORM\EntityRepository;
use Symfony\Bundle\FrameworkBundle\KernelBrowser;
use Symfony\Bundle\FrameworkBundle\Test\WebTestCase;

final class JobControllerTest extends WebTestCase
{
    private KernelBrowser $client;
    private EntityManagerInterface $manager;
    private EntityRepository $jobRepository;
    private string $path = '/job/';

    protected function setUp(): void
    {
        $this->client = static::createClient();
        $this->manager = static::getContainer()->get('doctrine')->getManager();
        $this->jobRepository = $this->manager->getRepository(Job::class);

        foreach ($this->jobRepository->findAll() as $object) {
            $this->manager->remove($object);
        }

        $this->manager->flush();
    }

    public function testIndex(): void
    {
        $this->client->followRedirects();
        $crawler = $this->client->request('GET', $this->path);

        self::assertResponseStatusCodeSame(200);
        self::assertPageTitleContains('Job index');

        // Use the $crawler to perform additional assertions e.g.
        // self::assertSame('Some text on the page', $crawler->filter('.p')->first());
    }

    public function testNew(): void
    {
        $this->markTestIncomplete();
        $this->client->request('GET', sprintf('%snew', $this->path));

        self::assertResponseStatusCodeSame(200);

        $this->client->submitForm('Save', [
            'job[title]' => 'Testing',
            'job[description]' => 'Testing',
            'job[country]' => 'Testing',
            'job[city]' => 'Testing',
            'job[remoteAllowed]' => 'Testing',
            'job[salaryRange]' => 'Testing',
            'job[company]' => 'Testing',
            'job[categories]' => 'Testing',
            'job[jobType]' => 'Testing',
        ]);

        self::assertResponseRedirects($this->path);

        self::assertSame(1, $this->jobRepository->count([]));
    }

    public function testShow(): void
    {
        $this->markTestIncomplete();
        $fixture = new Job();
        $fixture->setTitle('My Title');
        $fixture->setDescription('My Title');
        $fixture->setCountry('My Title');
        $fixture->setCity('My Title');
        $fixture->setRemoteAllowed('My Title');
        $fixture->setSalaryRange('My Title');
        $fixture->setCompany('My Title');
        $fixture->setCategories('My Title');
        $fixture->setJobType('My Title');

        $this->manager->persist($fixture);
        $this->manager->flush();

        $this->client->request('GET', sprintf('%s%s', $this->path, $fixture->getId()));

        self::assertResponseStatusCodeSame(200);
        self::assertPageTitleContains('Job');

        // Use assertions to check that the properties are properly displayed.
    }

    public function testEdit(): void
    {
        $this->markTestIncomplete();
        $fixture = new Job();
        $fixture->setTitle('Value');
        $fixture->setDescription('Value');
        $fixture->setCountry('Value');
        $fixture->setCity('Value');
        $fixture->setRemoteAllowed('Value');
        $fixture->setSalaryRange('Value');
        $fixture->setCompany('Value');
        $fixture->setCategories('Value');
        $fixture->setJobType('Value');

        $this->manager->persist($fixture);
        $this->manager->flush();

        $this->client->request('GET', sprintf('%s%s/edit', $this->path, $fixture->getId()));

        $this->client->submitForm('Update', [
            'job[title]' => 'Something New',
            'job[description]' => 'Something New',
            'job[country]' => 'Something New',
            'job[city]' => 'Something New',
            'job[remoteAllowed]' => 'Something New',
            'job[salaryRange]' => 'Something New',
            'job[company]' => 'Something New',
            'job[categories]' => 'Something New',
            'job[jobType]' => 'Something New',
        ]);

        self::assertResponseRedirects('/job/');

        $fixture = $this->jobRepository->findAll();

        self::assertSame('Something New', $fixture[0]->getTitle());
        self::assertSame('Something New', $fixture[0]->getDescription());
        self::assertSame('Something New', $fixture[0]->getCountry());
        self::assertSame('Something New', $fixture[0]->getCity());
        self::assertSame('Something New', $fixture[0]->getRemoteAllowed());
        self::assertSame('Something New', $fixture[0]->getSalaryRange());
        self::assertSame('Something New', $fixture[0]->getCompany());
        self::assertSame('Something New', $fixture[0]->getCategories());
        self::assertSame('Something New', $fixture[0]->getJobType());
    }

    public function testRemove(): void
    {
        $this->markTestIncomplete();
        $fixture = new Job();
        $fixture->setTitle('Value');
        $fixture->setDescription('Value');
        $fixture->setCountry('Value');
        $fixture->setCity('Value');
        $fixture->setRemoteAllowed('Value');
        $fixture->setSalaryRange('Value');
        $fixture->setCompany('Value');
        $fixture->setCategories('Value');
        $fixture->setJobType('Value');

        $this->manager->persist($fixture);
        $this->manager->flush();

        $this->client->request('GET', sprintf('%s%s', $this->path, $fixture->getId()));
        $this->client->submitForm('Delete');

        self::assertResponseRedirects('/job/');
        self::assertSame(0, $this->jobRepository->count([]));
    }
}
