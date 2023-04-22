
namespace App\Tests\Controller;

use Symfony\Bundle\FrameworkBundle\Test\WebTestCase;

class ApiHolidaysControllerTest extends WebTestCase
{
    public function testIndex()
    {
        $client = static::createClient();
        $client->request('GET', '/apiholidays/list');

        $this->assertEquals(200, $client->getResponse()->getStatusCode());
        $this->assertResponseHeaderSame('content-type', 'application/json');
    }

    public function testCreate()
    {
        $client = static::createClient();

        // Mock data to create a new holiday
        $holidayData = [
            'date' => '2022-05-12',
        ];
        $jsonData = json_encode($holidayData);

        $client->request('POST', '/apiholidays/create', [], [], ['CONTENT_TYPE' => 'application/json'], $jsonData);

        $this->assertEquals(201, $client->getResponse()->getStatusCode());
        $this->assertResponseHeaderSame('content-type', 'application/json');
    }

    public function testNew()
    {
        $client = static::createClient();

        // Mock data to create a new holiday
        $holidayData = [
            'date' => '2022-05-12',
        ];
        $jsonData = json_encode($holidayData);

        $client->request('POST', '/apiholidays/new', [], [], ['CONTENT_TYPE' => 'application/json'], $jsonData);

        $this->assertEquals(201, $client->getResponse()->getStatusCode());
        $this->assertResponseHeaderSame('content-type', 'application/json');
    }

    public function testShow()
    {
        $client = static::createClient();
        $holidayId = 1; // Replace with a valid holiday id in your database
        $client->request('GET', "/apiholidays/{$holidayId}");

        $this->assertEquals(200, $client->getResponse()->getStatusCode());
        $this->assertResponseHeaderSame('content-type', 'application/json');
    }

    public function testUpdate()
    {
        $client = static::createClient();

        // Replace with an existing holiday id in your database
        $holidayId = 1;
        $holidayData = [
            'date' => '2022-05-12',
        ];
        $jsonData = json_encode($holidayData);

        $client->request('PUT', "/apiholidays/{$holidayId}", [], [], ['CONTENT_TYPE' => 'application/json'], $jsonData);

        $this->assertEquals(200, $client->getResponse()->getStatusCode());
        $this->assertResponseHeaderSame('content-type', 'application/json');
    }

    public function testDelete()
    {
        $client = static::createClient();

        // Replace with an existing holiday id that you want to delete
        $holidayIdToDelete = 1;
        $client->request('DELETE', "/apiholidays/{$holidayIdToDelete}");

        $this->assertEquals(200, $client->getResponse()->getStatusCode());
        $this->assertResponseHeaderSame('content-type', 'application/json');
    }

}