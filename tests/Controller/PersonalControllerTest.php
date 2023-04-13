<?php

namespace App\Test\Controller;

use App\Entity\Personal;
use App\Repository\PersonalRepository;
use Symfony\Bundle\FrameworkBundle\KernelBrowser;
use Symfony\Bundle\FrameworkBundle\Test\WebTestCase;

class PersonalControllerTest extends WebTestCase
{
    private KernelBrowser $client;
    private PersonalRepository $repository;
    private string $path = '/personal/';

    protected function setUp(): void
    {
        $this->client = static::createClient();
        $this->repository = static::getContainer()->get('doctrine')->getRepository(Personal::class);

        foreach ($this->repository->findAll() as $object) {
            $this->repository->remove($object, true);
        }
    }

    public function testIndex(): void
    {
        $crawler = $this->client->request('GET', $this->path);

        self::assertResponseStatusCodeSame(200);
        self::assertPageTitleContains('Personal index');

        // Use the $crawler to perform additional assertions e.g.
        // self::assertSame('Some text on the page', $crawler->filter('.p')->first());
    }

    public function testNew(): void
    {
        $originalNumObjectsInRepository = count($this->repository->findAll());

        $this->markTestIncomplete();
        $this->client->request('GET', sprintf('%snew', $this->path));

        self::assertResponseStatusCodeSame(200);

        $this->client->submitForm('Save', [
            'personal[image]' => 'Testing',
            'personal[name]' => 'Testing',
            'personal[surname]' => 'Testing',
            'personal[rol]' => 'Testing',
            'personal[workshops]' => 'Testing',
            'personal[signin]' => 'Testing',
            'personal[holidays]' => 'Testing',
            'personal[documents]' => 'Testing',
            'personal[vacation]' => 'Testing',
        ]);

        self::assertResponseRedirects('/personal/');

        self::assertSame($originalNumObjectsInRepository + 1, count($this->repository->findAll()));
    }

    public function testShow(): void
    {
        $this->markTestIncomplete();
        $fixture = new Personal();
        $fixture->setImage('My Title');
        $fixture->setName('My Title');
        $fixture->setSurname('My Title');
        $fixture->setRol('My Title');
        $fixture->setWorkshops('My Title');
        $fixture->setSignin('My Title');
        $fixture->setHolidays('My Title');
        $fixture->setDocuments('My Title');
        $fixture->setVacation('My Title');

        $this->repository->save($fixture, true);

        $this->client->request('GET', sprintf('%s%s', $this->path, $fixture->getId()));

        self::assertResponseStatusCodeSame(200);
        self::assertPageTitleContains('Personal');

        // Use assertions to check that the properties are properly displayed.
    }

    public function testEdit(): void
    {
        $this->markTestIncomplete();
        $fixture = new Personal();
        $fixture->setImage('My Title');
        $fixture->setName('My Title');
        $fixture->setSurname('My Title');
        $fixture->setRol('My Title');
        $fixture->setWorkshops('My Title');
        $fixture->setSignin('My Title');
        $fixture->setHolidays('My Title');
        $fixture->setDocuments('My Title');
        $fixture->setVacation('My Title');

        $this->repository->save($fixture, true);

        $this->client->request('GET', sprintf('%s%s/edit', $this->path, $fixture->getId()));

        $this->client->submitForm('Update', [
            'personal[image]' => 'Something New',
            'personal[name]' => 'Something New',
            'personal[surname]' => 'Something New',
            'personal[rol]' => 'Something New',
            'personal[workshops]' => 'Something New',
            'personal[signin]' => 'Something New',
            'personal[holidays]' => 'Something New',
            'personal[documents]' => 'Something New',
            'personal[vacation]' => 'Something New',
        ]);

        self::assertResponseRedirects('/personal/');

        $fixture = $this->repository->findAll();

        self::assertSame('Something New', $fixture[0]->getImage());
        self::assertSame('Something New', $fixture[0]->getName());
        self::assertSame('Something New', $fixture[0]->getSurname());
        self::assertSame('Something New', $fixture[0]->getRol());
        self::assertSame('Something New', $fixture[0]->getWorkshops());
        self::assertSame('Something New', $fixture[0]->getSignin());
        self::assertSame('Something New', $fixture[0]->getHolidays());
        self::assertSame('Something New', $fixture[0]->getDocuments());
        self::assertSame('Something New', $fixture[0]->getVacation());
    }

    public function testRemove(): void
    {
        $this->markTestIncomplete();

        $originalNumObjectsInRepository = count($this->repository->findAll());

        $fixture = new Personal();
        $fixture->setImage('My Title');
        $fixture->setName('My Title');
        $fixture->setSurname('My Title');
        $fixture->setRol('My Title');
        $fixture->setWorkshops('My Title');
        $fixture->setSignin('My Title');
        $fixture->setHolidays('My Title');
        $fixture->setDocuments('My Title');
        $fixture->setVacation('My Title');

        $this->repository->save($fixture, true);

        self::assertSame($originalNumObjectsInRepository + 1, count($this->repository->findAll()));

        $this->client->request('GET', sprintf('%s%s', $this->path, $fixture->getId()));
        $this->client->submitForm('Delete');

        self::assertSame($originalNumObjectsInRepository, count($this->repository->findAll()));
        self::assertResponseRedirects('/personal/');
    }
}
