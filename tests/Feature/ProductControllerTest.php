<?php

namespace Tests\Feature;

use App\Http\Controllers\Admins\ProductController;
use App\Http\Controllers\Controller;
use App\Services\Admins\ProductService;
use Illuminate\Foundation\Testing\RefreshDatabase;
use Illuminate\Foundation\Testing\WithFaker;
use Illuminate\Http\Request;
use Mockery;
use Mockery\Mock;
use Tests\TestCase;

class ProductControllerTest extends TestCase
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

    public function test_view_product_successfully()
    {
        $mockedService = Mockery::mock(ProductService::class);

        $mockedService->shouldReceive('ViewProduct')->once()
            ->andReturn(['name_product' => 'Name Product', 'name_product_type' => 'Name Type', 'image' => 'img1']);
        $controller = new ProductController($mockedService);
        $response = $controller->View();

        $responseData = $response->getData(true);
        // Kiểm tra HTTP status code
        $this->assertEquals(200, $response->status());

        // Kiểm tra JSON response trả về có đúng không
        $this->assertEquals('success', $responseData['status']);
        $this->assertEquals(['name_product' => 'Name Product', 'name_product_type' => 'Name Type', 'image' => 'img1'], $responseData['data']);
    }

    /**
     * Test trường hợp CheckDelete trả về lỗi.
     *
     * @return void
     */

    public function  test_delete_product_with_checkdelete_error()
    {

        $mockedService = Mockery::mock(ProductService::class);
        $mockedService->shouldReceive('CheckDelete')->once()->with(1)->andReturn('Invalid product');
        $controller = new ProductController($mockedService);
        $response = $controller->Delete(1);
        $responseData = $response->getData(true);

        $this->assertEquals(400, $response->status());
        $this->assertEquals('error', $responseData['status']);
        $this->assertEquals('Invalid product', $responseData['errors']);
    }

    /**
     * Test trường hợp CheckProductId trả về lỗi.
     *
     * @return void
     */

    public function test_delete_product_with_checkproductid_error()
    {
        $mockedService = Mockery::mock(ProductService::class);
        $mockedService->shouldReceive('CheckDelete')->once()->with(1)->andReturn(1);
        $mockedService->shouldReceive('CheckProductId')->once()->with(1)->andReturn(0);
        // Mock phương thức DeleteProduct
        $controller = new ProductController($mockedService);
        $response = $controller->Delete(1);
        $responseData = $response->getData(true);

        $this->assertEquals(400, $response->status());
        $this->assertEquals('error', $responseData['status']);
        $this->assertEquals('Product does not exist yet', $responseData['errors']);
    }


    /**
     * Test trường hợp CheckAdd trả về lỗi.
     *
     * @return void
     */

    public function test_add_product_with_checkadd_error()
    {
        $mockedService = Mockery::mock(ProductService::class);
        $mockedService->shouldReceive('CheckAdd')
            ->once()
            ->with('New product', 1, [1, '123'], [1], [10000, 20000]) // Đảm bảo khớp với các tham số
            ->andReturn([
                'Size' => 'Size must be an array.'
            ]);

        $controller = new ProductController($mockedService);

        $request = new Request([
            'Name' => 'New product',
            'Style' => 1,
            'Size' => [1, '123'], // Sử dụng dữ liệu không hợp lệ cho Size
            'Topping' => [1],
            'Price' => [10000, 20000]
        ]);

        $response = $controller->Add($request);
        $responseData = $response->getData(true);

        $this->assertEquals(400, $response->status());
        $this->assertEquals('error', $responseData['status']);
        $this->assertEquals('Size must be an array.', $responseData['errors']['Size']);
    }

    /**
     * Test trường hợp Add thành công.
     *
     * @return void
     */
    public function test_add_product()
    {
        // Tạo mock cho ProductService
        $mockedService = Mockery::mock(ProductService::class);

        // Thiết lập expectation cho CheckAdd
        $mockedService->shouldReceive('CheckAdd')
            ->once()
            ->with('New product', 1, [1, 2], [1], [10000, 20000]) // Đảm bảo khớp với các tham số
            ->andReturn(1);

        // Thiết lập expectation cho CheckName
        $mockedService->shouldReceive('CheckName')
            ->once()
            ->with('New product') // Đảm bảo khớp với tên đã sử dụng trong request
            ->andReturn(1);

        // Thiết lập expectation cho AddProduct
        $mockedService->shouldReceive('AddProduct')
            ->once()
            ->with('New product', 1, [1, 2], [1], 'image.jpg', [10000, 20000])
            ->andReturn(1);

        // Tạo controller với mock
        $controller = new ProductController($mockedService);

        // Tạo request với dữ liệu hợp lệ
        $request = new Request([
            'Name' => 'New product', // Chú ý đến chữ thường
            'Style' => 1,
            'Size' => [1, 2],
            'Topping' => [1],
            'Price' => [10000, 20000],
            'Image' => 'image.jpg'
        ]);

        // Gọi phương thức Add
        $response = $controller->Add($request);
        $responseData = $response->getData(true);

        // Kiểm tra các giá trị trả về
        $this->assertEquals(200, $response->status());
        $this->assertEquals('success', $responseData['status']);
    }
}
