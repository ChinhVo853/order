<?php

namespace Tests\Feature;

use App\Http\Controllers\Admins\SizeController;
use App\Http\Controllers\Controller;
use App\Services\Admins\SizeService;
use Illuminate\Foundation\Testing\RefreshDatabase;
use Illuminate\Foundation\Testing\WithFaker;
use Tests\TestCase;
use Mockery;
use Illuminate\Http\Request;

class SizeControllerTest extends TestCase
{
    /**
     * A basic feature test example.
     *
     * @return void
     */
    public function test_example()
    {
        // Giả lập SizeService và phương thức size()
        $mockedService = Mockery::mock(SizeService::class);

        /** 
         * @var \App\Services\Admins\SizeService $mockedService 
         */
        $mockedService->shouldReceive('ListSize')->once()->andReturn([
            [
                'id' => 1,
                'name' => 'loai1',

            ],
            [
                'id' => 2,
                'name' => 'loai2'
            ]
        ]);

        // Tạo instance của SizeController và inject service đã mock vào
        $controller = new SizeController($mockedService);

        // Gọi hàm View và lấy response
        $response = $controller->View();

        // Chuyển response sang dạng mảng để kiểm tra
        $responseData = $response->getData(true);

        // Kiểm tra HTTP status code
        $this->assertEquals(200, $response->status());

        // Kiểm tra JSON response trả về có đúng không
        $this->assertEquals('success', $responseData['status']);
        $this->assertEquals([['id' => 1, 'name' => 'loai1'], ['id' => 2, 'name' => 'loai2']], $responseData['data']);
    }


    /**
     * Test trường hợp CheckAdd trả về lỗi.
     *
     * @return void
     */
    public function test_add_size_with_checkadd_error()
    {
        // Tạo mock cho SizeService
        $mockedService = Mockery::mock(SizeService::class);

        // Thiết lập CheckAdd trả về lỗi không phải 1
        $mockedService->shouldReceive('CheckAdd')->once()->with('New Size')->andReturn('Invalid size');

        // Inject mock vào SizeController
        $controller = new SizeController($mockedService);

        // Tạo một request giả với dữ liệu cần kiểm tra
        $request = new Request(['Name' => 'New Size']);

        // Gọi phương thức Add và kiểm tra phản hồi
        $response = $controller->Add($request);

        // Chuyển response sang dạng mảng để kiểm tra
        $responseData = $response->getData(true);

        // Kiểm tra HTTP status code và nội dung JSON
        $this->assertEquals(400, $response->status());
        $this->assertEquals('error', $responseData['status']);
        $this->assertEquals('Invalid size', $responseData['errors']);
    }

    /**
     * Test trường hợp CheckSizeName trả về lỗi size đã tồn tại.
     *
     * @return void
     */
    public function test_add_size_with_existing_size_error()
    {
        // Tạo mock cho SizeService
        $mockedService = Mockery::mock(SizeService::class);

        // Thiết lập CheckAdd trả về 1 (hợp lệ)
        $mockedService->shouldReceive('CheckAdd')->once()->with('New Size')->andReturn(1);

        // Thiết lập CheckSizeName trả về 0 (size đã tồn tại)
        $mockedService->shouldReceive('CheckSizeName')->once()->with('New Size')->andReturn(0);

        // Inject mock vào SizeController
        $controller = new SizeController($mockedService);

        // Tạo một request giả với dữ liệu cần kiểm tra
        $request = new Request(['Name' => 'New Size']);

        // Gọi phương thức Add và kiểm tra phản hồi
        $response = $controller->Add($request);

        // Chuyển response sang dạng mảng để kiểm tra
        $responseData = $response->getData(true);

        // Kiểm tra HTTP status code và nội dung JSON
        $this->assertEquals(400, $response->status());
        $this->assertEquals('error', $responseData['status']);
        $this->assertEquals('Size already exists', $responseData['errors']);
    }

    /**
     * Test trường hợp thành công khi tất cả kiểm tra đều hợp lệ.
     *
     * @return void
     */
    public function test_add_size_successfully()
    {
        // Tạo mock cho SizeService
        $mockedService = Mockery::mock(SizeService::class);

        // Thiết lập CheckAdd trả về 1 (hợp lệ)
        $mockedService->shouldReceive('CheckAdd')->once()->with('New Size')->andReturn(1);

        // Thiết lập CheckSizeName trả về 1 (size không tồn tại)
        $mockedService->shouldReceive('CheckSizeName')->once()->with('New Size')->andReturn(1);

        // Thiết lập AddSize được gọi một lần với giá trị 'New Size'
        $mockedService->shouldReceive('AddSize')->once()->with('New Size');

        // Inject mock vào SizeController
        $controller = new SizeController($mockedService);

        // Tạo một request giả với dữ liệu cần kiểm tra
        $request = new Request(['Name' => 'New Size']);

        // Gọi phương thức Add và kiểm tra phản hồi
        $response = $controller->Add($request);

        // Chuyển response sang dạng mảng để kiểm tra
        $responseData = $response->getData(true);

        // Kiểm tra HTTP status code và nội dung JSON
        $this->assertEquals(200, $response->status());
        $this->assertEquals('success', $responseData['status']);
    }


    /**
     * Test trường hợp CheckDelete trả về lỗi.
     *
     * @return void
     */
    public function test_delete_size_with_checkdelete_error()
    {
        // Tạo mock cho SizeService
        $mockedService = Mockery::mock(SizeService::class);

        // Thiết lập CheckDelete trả về lỗi không phải 1
        $mockedService->shouldReceive('CheckDelete')->once()->with(1)->andReturn('Invalid Size');

        // Inject mock vào SizeController
        $controller = new SizeController($mockedService);

        // Gọi phương thức Delete và kiểm tra phản hồi
        $response = $controller->Delete(1);

        // Chuyển response sang dạng mảng để kiểm tra
        $responseData = $response->getData(true);

        // Kiểm tra HTTP status code và nội dung JSON
        $this->assertEquals(400, $response->status());
        $this->assertEquals('error', $responseData['status']);
        $this->assertEquals('Invalid Size', $responseData['errors']);
    }

    /**
     * Test trường hợp CheckSizeName trả về lỗi size đã tồn tại.
     *
     * @return void
     */

    public function test_delete_size_with_checksizeid_error()
    {        // Tạo mock cho SizeService
        $mockedService = Mockery::mock(SizeService::class);

        // Thiết lập CheckDelete trả về lỗi không phải 1
        $mockedService->shouldReceive('CheckDelete')->once()->with(1)->andReturn(1);
        $mockedService->shouldReceive('CheckSizeId')->once()->with(1)->andReturn(0);

        // Inject mock vào SizeController
        $controller = new SizeController($mockedService);

        // Gọi phương thức Delete và kiểm tra phản hồi
        $response = $controller->Delete(1);

        // Chuyển response sang dạng mảng để kiểm tra
        $responseData = $response->getData(true);

        // Kiểm tra HTTP status code và nội dung JSON
        $this->assertEquals(400, $response->status());
        $this->assertEquals('error', $responseData['status']);
        $this->assertEquals('Size does not exist yet', $responseData['errors']);
    }



    /**
     * Delete test trường hợp thành công khi tất cả kiểm tra đều hợp lệ.
     *
     * @return void
     */
    public function test_delete_size_successfully()
    {
        $mockedService = Mockery::mock(SizeService::class);
        $mockedService->shouldReceive('CheckDelete')->once()->with(1)->andReturn(1);
        $mockedService->shouldReceive('CheckSizeId')->once()->with(1)->andReturn(1);
        $mockedService->shouldReceive('DeleteSize')->once()->with(1);

        // Inject mock vào SizeController
        $controller = new SizeController($mockedService);

        // Gọi phương thức Delete và kiểm tra phản hồi
        $response = $controller->Delete(1);

        // Chuyển response sang dạng mảng để kiểm tra
        $responseData = $response->getData(true);

        // Kiểm tra HTTP status code và nội dung JSON
        $this->assertEquals(200, $response->status());
        $this->assertEquals('success', $responseData['status']);
    }

    public function test_update_size_successfully()
    {
        $mockedService = Mockery::mock(SizeService::class);
        $mockedService->shouldReceive('CheckAdd')->once()->with('New Name Size')->andReturn(1);
        $mockedService->shouldReceive('CheckDelete')->once()->with(1)->andReturn(1);
        $mockedService->shouldReceive('CheckSizeId')->once()->with(1)->andReturn(1);
        $mockedService->shouldReceive('UpdateSize')->once()->with('New Name Size');

        $controller = new SizeController($mockedService);

        $request = new Request(['Name' => 'New Name Size']);
        // Gọi phương thức Delete và kiểm tra phản hồi
        $response = $controller->Update($request, 1);

        // Chuyển response sang dạng mảng để kiểm tra
        $responseData = $response->getData(true);

        // Kiểm tra HTTP status code và nội dung JSON
        $this->assertEquals(200, $response->status());
        $this->assertEquals('success', $responseData['status']);
    }
}
