<?php

namespace Tests\Feature;

use App\Http\Controllers\Admins\ToppingController;
use App\Http\Controllers\Controller;
use App\Services\Admins\ToppingService;
use Illuminate\Foundation\Testing\RefreshDatabase;
use Illuminate\Foundation\Testing\WithFaker;
use Mockery;
use Tests\TestCase;

class ToppingControllerTest extends TestCase
{
    /**
     * A basic feature test example.
     *
     * @return void
     */
    public function test_example()
    {
        $response = $this->get('/');

        $response->assertStatus(200);
    }

    public function test_view_successfully()
    {
        $mockedService = Mockery::mock(ToppingService::class);
        $mockedService->shouldReceive('ViewAll')->once()->with(1)->andReturn([
            [
                'id' => 1,
                'name' => 'abc',
                'price' => 10000,
                'image' => '123.abc'
            ],
            [
                'id' => 2,
                'name' => 'abc',
                'price' => 10000,
                'image' => '123.abc'
            ]
        ]);
        $controller = new ToppingController($mockedService);

        $response = $controller->View(1);
        $responseData = $response->getData(true);

        // Kiểm tra HTTP status code
        $this->assertEquals(200, $response->status());

        // Kiểm tra JSON response trả về có đúng không
        $this->assertEquals('success', $responseData['status']);
        $this->assertEquals([
            [
                'id' => 1,
                'name' => 'abc',
                'price' => 10000,
                'image' => '123.abc'
            ],
            [
                'id' => 2,
                'name' => 'abc',
                'price' => 10000,
                'image' => '123.abc'
            ]
        ], $responseData['data']);
    }

    public function test_delete_successfully()
    {
        $mockedService = Mockery::mock(ToppingService::class);
        $mockedService->shouldReceive('DeleteTopping')->once()->with(1)->andReturn(1);
        $controller = new ToppingController($mockedService);

        $response = $controller->Delete(1);
        $responseData = $response->getData(true);

        // Kiểm tra HTTP status code
        $this->assertEquals(200, $response->status());

        // Kiểm tra JSON response trả về có đúng không
        $this->assertEquals('success', $responseData['status']);
    }

    public function test_delete_DeleteTopping_erorr()
    {
        $mockedService = Mockery::mock(ToppingService::class);
        $mockedService->shouldReceive('DeleteTopping')->once()->with(10)->andReturn(0);
        $controller = new ToppingController($mockedService);

        $response = $controller->delete(10);
        $responseData = $response->getData(true);

        // Kiểm tra HTTP status code
        $this->assertEquals(400, $response->status());

        // Kiểm tra JSON response trả về có đúng không
        $this->assertEquals('error', $responseData['status']);
    }
}
