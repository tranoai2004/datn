<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use App\Http\Requests\StoreProductRequest;
use App\Imports\ProductsImport;
use App\Models\Brand;
use App\Models\Catalogue;
use App\Models\Gallery;
use App\Models\Product;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Log;
use Illuminate\Support\Facades\Storage;
use Maatwebsite\Excel\Facades\Excel;

class ProductController extends Controller
{
    /**
     * Display a listing of the resource.
     */
    public function index()
    {
        $title = 'Danh Sách Sản Phẩm';
        $products = Product::with('brand', 'catalogue')->paginate(10); // Phân trang 10 sản phẩm trên 1 trang
        return view('admin.products.index', compact('products', 'title'));
    }

    /**
     * Show the form for creating a new resource.
     */
    public function create()
    {
        $title = 'Thêm Mới Sản Phẩm';
        $catalogues = Catalogue::all();
        $brands = Brand::all();
        return view('admin.products.create', compact('catalogues', 'brands', 'title'));
    }

    /**
     * Store a newly created resource in storage.
     */
    public function store(StoreProductRequest $request)
    {
        try {
            DB::beginTransaction();
            $imagePath = null;
            if ($request->hasFile('image_url')) {
                $imagePath = Storage::put('images', $request->image_url);
            }

            // Tạo mới sản phẩm
            $product = Product::create([
                'name' => $request->name,
                'catalogue_id' => $request->catalogue_id,
                'brand_id' => $request->brand_id,
                'slug' => $request->slug,
                'sku' => $request->sku,
                'price' => $request->price,
                'discount_price' => $request->discount_price,
                'stock' => $request->stock,
                'weight' => $request->weight,
                'dimensions' => $request->dimensions,
                'image_url' => $imagePath,
                'description' => $request->description,
                'is_active' => $request->is_active,
                'is_featured' => $request->has('is_featured'),
                'condition' => $request->condition,
                'tomtat' => $request->tomtat,
            ]);

            // Xử lý hình ảnh từ bảng galleries
            if ($request->hasFile('images')) {
                foreach ($request->file('images') as $image) {
                    if ($image) { // Kiểm tra xem hình ảnh có tồn tại không
                        $imagePath = Storage::put('galleries', $image);
                        Gallery::create([
                            'product_id' => $product->id,
                            'image_url' => $imagePath,
                        ]);
                    }
                }
            }

            DB::commit();
            return redirect()->route('products.index')->with('success', 'Sản phẩm đã được thêm mới!');
        } catch (\Throwable $th) {
            DB::rollBack();
            return back()->with('errors', $th->getMessage());
        }
    }


    /**
     * Display the specified resource.
     */
    public function show(string $id)
    {
        $title = 'Chi Tiết Sản Phẩm';
        $product = Product::with(['brand', 'catalogue', 'galleries'])->where('id', $id)->firstOrFail(); // Lấy hình ảnh

        return view('admin.products.detail', compact('product', 'title'));
    }

    /**
     * Show the form for editing the specified resource.
     */
    public function edit(string $id)
    {
        $title = 'Cập Nhật Sản Phẩm';
        $product = Product::findOrFail($id);
        $brands = Brand::all(); // Lấy tất cả thương hiệu
        $catalogues = Catalogue::all(); // Lấy tất cả danh mục

        return view('admin.products.edit', compact('product', 'brands', 'catalogues', 'title'));
    }

    /**
     * Update the specified resource in storage.
     */
    public function update(Request $request, $productId)
{
    try {
        DB::beginTransaction();

        $request->validate([
            'name' => 'required|string|max:255',
            'slug' => 'required|string|max:255|unique:products,slug,' . $productId,
            'sku' => 'nullable|string|max:255',
            'price' => 'required|numeric',
            'discount_price' => 'nullable|numeric',
            'weight' => 'nullable|numeric',
            'description' => 'nullable|string',
            'dimensions' => 'nullable|string|max:255',
            'is_featured' => 'nullable|boolean',
            'condition' => 'required|in:new,used,refurbished',
            'tomtat' => 'nullable|string',
            // Các quy tắc xác thực khác
        ]);

        $product = Product::findOrFail($productId);

        // Cập nhật thông tin sản phẩm
        $product->update([
            'name' => $request->name,
            'slug' => $request->slug,
            'sku' => $request->sku,
            'price' => $request->price,
            'discount_price' => $request->discount_price,
            'weight' => $request->weight,
            'dimensions' => $request->dimensions,
            'is_active' => $request->is_active,
            'is_featured' => $request->has('is_featured'),
            'condition' => $request->condition,
            'brand_id' => $request->brand_id,
            'description' => $request->description,
            'catalogue_id' => $request->catalogue_id,
            'tomtat' => $request->tomtat,
        ]);

        // Xử lý hình ảnh chính
        if ($request->hasFile("image_url")) {
            $imagePath = Storage::put('images', $request->image_url);
            $product->update(['image_url' => $imagePath]);
        }

        // Xử lý hình ảnh từ bảng galleries
        if ($request->hasFile('images')) {
            // Xóa tất cả hình ảnh cũ
            $product->galleries()->delete();

            // Thêm hình ảnh mới
            foreach ($request->file('images') as $image) {
                if ($image) {
                    $imagePath = Storage::put('galleries', $image);
                    Gallery::create([
                        'product_id' => $product->id,
                        'image_url' => $imagePath,
                    ]);
                }
            }
        } else {
            // Nếu không có hình ảnh mới, giữ lại hình ảnh cũ
            if ($request->has('existing_images')) {
                foreach ($request->existing_images as $existingImage) {
                    if ($existingImage) {
                        // Kiểm tra xem hình ảnh đã tồn tại trong bảng galleries chưa
                        if (!Gallery::where('product_id', $product->id)->where('image_url', $existingImage)->exists()) {
                            Gallery::create([
                                'product_id' => $product->id,
                                'image_url' => $existingImage,
                            ]);
                        }
                    }
                }
            }
        }

        DB::commit();
        return redirect()->route('products.index')->with('success', 'Sản phẩm đã được cập nhật thành công.');
    } catch (\Throwable $th) {
        DB::rollBack();
        return back()->with('errors', $th->getMessage());
    }
}
    public function import(Request $request)
{
    // Kiểm tra xem đã có file hay chưa
    if ($request->hasFile('file')) {
        // Lưu file vào thư mục tạm trong storage
        $path = $request->file('file')->store('temp');
        $filePath = storage_path('app/public/' . $path);

        // Kiểm tra xem file đã được lưu thành công hay chưa
        if (!Storage::exists($path)) {
            return redirect()->back()->with('error', 'Không thể lưu tệp vào thư mục tạm.');
        }

        // In ra đường dẫn tệp để kiểm tra
        Log::info('File path: ' . $filePath);

        // Thực hiện import và trích xuất ảnh
        try {
            // Kiểm tra xem file có tồn tại trước khi import
            if (!file_exists($filePath)) {
                return redirect()->back()->with('error', 'File không tồn tại: ' . $filePath);
            }

            Excel::import(new ProductsImport($filePath), $filePath);

            // Xóa file sau khi sử dụng
            Storage::delete($path);

            return redirect()->back()->with('success', 'Import sản phẩm và trích xuất hình ảnh thành công!');
        } catch (\Exception $e) {
            return redirect()->back()->with('error', 'Có lỗi khi nhập sản phẩm: ' . $e->getMessage());
        }
    }

    return redirect()->back()->with('error', 'Vui lòng chọn tệp để nhập.');
}



}
