<?php

namespace App\Tests\Controller;

use App\Entity\Company;
use Doctrine\ORM\EntityManagerInterface;
use Doctrine\ORM\EntityRepository;
use Symfony\Bundle\FrameworkBundle\KernelBrowser;
use Symfony\Bundle\FrameworkBundle\Test\WebTestCase;

final class CompanyControllerTest extends WebTestCase
{
    private KernelBrowser $client;
    private EntityManagerInterface $manager;
    private EntityRepository $companyRepository;
    private string $path = '/company/';

    protected function setUp(): void
    {
        $this->client = static::createClient();
        $this->manager = static::getContainer()->get('doctrine')->getManager();
        $this->companyRepository = $this->manager->getRepository(Company::class);

        foreach ($this->companyRepository->findAll() as $object) {
            $this->manager->remove($object);
        }

        $this->manager->flush();
    }

    public function testIndex(): void
    {
        $this->client->followRedirects();
        $crawler = $this->client->request('GET', $this->path);

        self::assertResponseStatusCodeSame(200);
        self::assertPageTitleContains('Company index');

        // Use the $crawler to perform additional assertions e.g.
        // self::assertSame('Some text on the page', $crawler->filter('.p')->first());
    }

    public function testNew(): void
    {
        $this->markTestIncomplete();
        $this->client->request('GET', sprintf('%snew', $this->path));

        self::assertResponseStatusCodeSame(200);

        $this->client->submitForm('Save', [
            'company[name]' => 'Testing',
            'company[description]' => 'Testing',
            'company[adress]' => 'Testing',
            'company[city]' => 'Testing',
            'company[country]' => 'Testing',
        ]);

        self::assertResponseRedirects($this->path);

        self::assertSame(1, $this->companyRepository->count([]));
    }

    public function testShow(): void
    {
        $this->markTestIncomplete();
        $fixture = new Company();
        $fixture->setName('My Title');
        $fixture->setDescription('My Title');
        $fixture->setAdress('My Title');
        $fixture->setCity('My Title');
        $fixture->setCountry('My Title');

        $this->manager->persist($fixture);
        $this->manager->flush();

        $this->client->request('GET', sprintf('%s%s', $this->path, $fixture->getId()));

        self::assertResponseStatusCodeSame(200);
        self::assertPageTitleContains('Company');

        // Use assertions to check that the properties are properly displayed.
    }

    public function testEdit(): void
    {
        $this->markTestIncomplete();
        $fixture = new Company();
        $fixture->setName('Value');
        $fixture->setDescription('Value');
        $fixture->setAdress('Value');
        $fixture->setCity('Value');
        $fixture->setCountry('Value');

        $this->manager->persist($fixture);
        $this->manager->flush();

        $this->client->request('GET', sprintf('%s%s/edit', $this->path, $fixture->getId()));

        $this->client->submitForm('Update', [
            'company[name]' => 'Something New',
            'company[description]' => 'Something New',
            'company[adress]' => 'Something New',
            'company[city]' => 'Something New',
            'company[country]' => 'Something New',
        ]);

        self::assertResponseRedirects('/company/');

        $fixture = $this->companyRepository->findAll();

        self::assertSame('Something New', $fixture[0]->getName());
        self::assertSame('Something New', $fixture[0]->getDescription());
        self::assertSame('Something New', $fixture[0]->getAdress());
        self::assertSame('Something New', $fixture[0]->getCity());
        self::assertSame('Something New', $fixture[0]->getCountry());
    }

    public function testRemove(): void
    {
        $this->markTestIncomplete();
        $fixture = new Company();
        $fixture->setName('Value');
        $fixture->setDescription('Value');
        $fixture->setAdress('Value');
        $fixture->setCity('Value');
        $fixture->setCountry('Value');

        $this->manager->persist($fixture);
        $this->manager->flush();

        $this->client->request('GET', sprintf('%s%s', $this->path, $fixture->getId()));
        $this->client->submitForm('Delete');

        self::assertResponseRedirects('/company/');
        self::assertSame(0, $this->companyRepository->count([]));
    }
}
