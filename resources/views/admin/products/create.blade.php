@extends('admin.master')

@section('title', 'Thêm mới sản phẩm')

@section('content')
    <div class="content-wrapper-scroll">
        <div class="content-wrapper">
            @if (session('success'))
                <div class="alert alert-success">{{ session('success') }}</div>
            @endif
            @if ($errors->any())
                <div class="alert alert-danger">
                    <ul>
                        @foreach ($errors->all() as $error)
                            <li>{{ $error }}</li>
                        @endforeach
                    </ul>
                </div>
            @endif

            <div class="row">
                <div class="col-sm-12 col-12">
                    <div class="card">
                        <div class="card-header d-flex justify-content-between align-items-center">
                            <div class="card-title">Thêm Mới Sản Phẩm</div>
                            <a href="{{ route('products.index') }}" class="btn btn-sm rounded-pill btn-secondary">
                                <i class="bi bi-arrow-left me-2"></i> Trở về
                            </a>
                        </div>
                        <div class="card-body">
                            <form id="productForm" action="{{ route('products.store') }}" method="POST"
                                enctype="multipart/form-data">
                                @csrf
                                <div class="mb-3">
                                    <label for="productName" class="form-label">Tên Sản Phẩm</label>
                                    <input type="text" class="form-control" id="productName" name="name"
                                        value="{{ old('name') }}" oninput="ChangeToSlug()">
                                </div>
                                <div class="mb-3">
                                    <label for="sku" class="form-label">SKU</label>
                                    <input type="text" class="form-control" id="sku" name="sku"
                                        value="{{ old('sku') }}" readonly>
                                </div>
                                <div class="mb-3">
                                    <label for="slug" class="form-label">Slug</label>
                                    <input type="text" class="form-control" id="slug" name="slug"
                                        value="{{ old('slug') }}" readonly>
                                </div>
                                <div class="mb-3">
                                    <label for="catalogue_id" class="form-label">Danh Mục</label>
                                    <select class="form-control" id="catalogue_id" name="catalogue_id">
                                        @foreach ($catalogues as $catalogue)
                                            <option value="{{ $catalogue->id }}">{{ $catalogue->name }}</option>
                                        @endforeach
                                    </select>
                                </div>
                                <div class="mb-3">
                                    <label for="brand_id" class="form-label">Thương Hiệu</label>
                                    <select class="form-control" id="brand_id" name="brand_id">
                                        <option value="">Không có</option>
                                        @foreach ($brands as $brand)
                                            <option value="{{ $brand->id }}">{{ $brand->name }}</option>
                                        @endforeach
                                    </select>
                                </div>
                                <div class="form-group">
                                    <label for="weight">Cân nặng (kg)</label>
                                    <input type="text" name="weight" class="form-control" id="weight"
                                        value="{{ old('weight') }}">
                                </div>
                                <div class="form-group">
                                    <label for="dimensions">Kích thước (DxRxC)</label>
                                    <input type="text" name="dimensions" class="form-control" id="dimensions"
                                        value="{{ old('dimensions') }}">
                                </div>
                                <div class="mb-3">
                                    <label for="price" class="form-label">Giá</label>
                                    <input type="number" step="0.01" class="form-control" id="price" name="price"
                                        value="{{ old('price') }}">
                                </div>

                                <div class="form-group">
                                    <label for="discount_price">Giá Khuyến Mãi</label>
                                    <input type="text" name="discount_price" id="discount_price" class="form-control"
                                        value="{{ old('discount_price', $product->discount_price ?? '') }}">
                                </div>

                                <div class="mb-3">
                                    <label for="stock" class="form-label">Tồn Kho</label>
                                    <input type="number" class="form-control" id="stock" name="stock"
                                        value="{{ old('stock') }}">
                                </div>
                                <div class="mb-3">
                                    <label for="image_url" class="form-label">Ảnh Sản Phẩm</label>
                                    <input type="file" class="form-control" id="image_url" name="image_url"
                                        onchange="previewImageUrl(event)">
                                    <img id="imageUrlPreview" src="" alt="Hình ảnh xem trước" class="mt-2"
                                        style="display: none; width: 100px;">
                                </div>
                                <div class="mb-3">
                                    <label for="editor" class="form-label">Mô Tả</label>
                                    <textarea class="form-control" id="editor" name="description">{{ old('description') }}</textarea>
                                </div>
                                <div class="mb-3">
                                    <label for="is_active" class="form-label">Trạng Thái</label>
                                    <select class="form-control" id="is_active" name="is_active">
                                        <option value="1" selected>Active</option>
                                        <option value="0">Inactive</option>
                                    </select>
                                </div>

                                <div class="mb-3">
                                    <label for="is_featured" class="form-label">Nổi bật</label>
                                    <input type="checkbox" id="is_featured" name="is_featured" value="1"
                                        {{ old('is_featured', $product->is_featured ?? false) ? 'checked' : '' }}>
                                </div>

                                <div class="mb-3">
                                    <label for="condition" class="form-label">Tình trạng</label>
                                    <select class="form-select" id="condition" name="condition" required>
                                        <option value="">Chọn tình trạng</option>
                                        <option value="new">Mới</option>
                                        <option value="used">Đã qua sử dụng</option>
                                        <option value="refurbished">Tái chế</option>
                                    </select>
                                </div>
                                <!-- Thêm trường Tóm Tắt -->
                                <div class="mb-3">
                                    <label for="tomtat" class="form-label">Tóm Tắt</label>
                                    <textarea class="form-control" id="tomtat" name="tomtat">{{ old('tomtat') }}</textarea>
                                </div>

                                <div id="image-inputs">
                                    <div class="form-group d-flex align-items-center">
                                        <label for="image1" class="me-2">Gallery 1</label>
                                        <input type="file" name="images[]" id="image1" class="form-control me-2"
                                            accept="image/*" onchange="previewGalleryImage(event, this)">
                                        <button type="button" class="btn btn-secondary add-image">Thêm</button>
                                    </div>
                                </div>
                                <div id="gallery" class="mt-3 d-flex flex-wrap"></div>

                                <button type="button" id="generateSkuBtn" class="btn btn-rounded btn-secondary">Tạo
                                    SKU</button>
                                <button type="submit" class="btn btn-rounded btn-primary">Thêm Mới</button>
                            </form>
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </div>

    <script src="https://cdn.ckeditor.com/4.16.2/standard/ckeditor.js"></script>
    <script>
        CKEDITOR.replace('editor');
    </script>

    <script>
        function ChangeToSlug() {
            var productName, slug;

            // Lấy text từ thẻ input title
            productName = document.getElementById("productName").value;

            // Đổi chữ hoa thành chữ thường
            slug = productName.toLowerCase();

            // Đổi ký tự có dấu thành không dấu
            slug = slug.replace(/á|à|ả|ạ|ã|ă|ắ|ằ|ẳ|ẵ|ặ|â|ấ|ầ|ẩ|ẫ|ậ/gi, 'a');
            slug = slug.replace(/é|è|ẻ|ẽ|ẹ|ê|ế|ề|ể|ễ|ệ/gi, 'e');
            slug = slug.replace(/i|í|ì|ỉ|ĩ|ị/gi, 'i');
            slug = slug.replace(/ó|ò|ỏ|õ|ọ|ô|ố|ồ|ổ|ỗ|ộ|ơ|ớ|ờ|ở|ỡ|ợ/gi, 'o');
            slug = slug.replace(/ú|ù|ủ|ũ|ụ|ư|ứ|ừ|ử|ữ|ự/gi, 'u');
            slug = slug.replace(/ý|ỳ|ỷ|ỹ|ỵ/gi, 'y');
            slug = slug.replace(/đ/gi, 'd');

            // Xóa các ký tự đặc biệt
            slug = slug.replace(/\`|\~|\!|\@|\#|\$|\%|\^|\&|\*|\(|\)|\+|\=|\,|\.|\/|\?|\>|\<|\'|\"|\:|\;|_/gi, '');

            // Đổi khoảng trắng thành ký tự gạch ngang
            slug = slug.replace(/ /gi, "-");

            // Đổi nhiều ký tự gạch ngang liên tiếp thành 1 ký tự gạch ngang
            slug = slug.replace(/\-\-\-\-\-/gi, '-');
            slug = slug.replace(/\-\-\-\-/gi, '-');
            slug = slug.replace(/\-\-\-/gi, '-');
            slug = slug.replace(/\-\-/gi, '-');

            // Xóa các ký tự gạch ngang ở đầu và cuối
            slug = '@' + slug + '@';
            slug = slug.replace(/\@\-|\-\@|\@/gi, '');

            // In slug ra textbox có id “slug”
            document.getElementById('slug').value = slug;
        }

        document.getElementById('generateSkuBtn').addEventListener('click', function() {
            const randomSku = 'SKU-' + Math.random().toString(36).substr(2, 9).toUpperCase(); // Tạo SKU ngẫu nhiên
            document.getElementById('sku').value = randomSku;
        });

        function previewImageUrl(event) {
            const imageUrlPreview = document.getElementById('imageUrlPreview');
            const file = event.target.files[0];

            if (file) {
                const reader = new FileReader();
                reader.onload = function(e) {
                    imageUrlPreview.src = e.target.result;
                    imageUrlPreview.style.display = 'block';
                };
                reader.readAsDataURL(file);
            } else {
                imageUrlPreview.src = '';
                imageUrlPreview.style.display = 'none';
            }
        }
    </script>

    <script>
        document.querySelector('.add-image').addEventListener('click', function() {
            const container = document.getElementById('image-inputs');
            const newIndex = container.children.length + 1; // Tính số lượng trường hiện tại

            const newInput = document.createElement('div');
            newInput.classList.add('form-group', 'd-flex', 'align-items-center');
            newInput.innerHTML = `
            <label for="image${newIndex}" class="me-2">Gallery ${newIndex}</label>
            <input type="file" name="images[]" id="image${newIndex}" class="form-control me-2" accept="image/*" onchange="previewGalleryImage(event, this)">
            <button type="button" class="btn btn-danger ms-2 remove-image">Xóa</button>
        `;

            container.appendChild(newInput);

            // Xử lý sự kiện cho nút "Xóa" mới
            newInput.querySelector('.remove-image').addEventListener('click', function() {
                container.removeChild(newInput);
                updateGallery(); // Cập nhật lại gallery khi xóa
            });
        });

        function previewGalleryImage(event, input) {
            const gallery = document.getElementById('gallery');
            const file = event.target.files[0];

            // Xóa hình ảnh xem trước cũ nếu có
            const existingImages = gallery.querySelectorAll(`img[data-input-id="${input.id}"]`);
            existingImages.forEach(img => img.remove());

            if (file) {
                const reader = new FileReader();
                reader.onload = function(e) {
                    const img = document.createElement('img');
                    img.src = e.target.result;
                    img.className = 'gallery-image me-2 mb-2';
                    img.style.width = '100px';
                    img.style.height = 'auto';
                    img.setAttribute('data-input-id', input.id); // Gán ID trường nhập vào hình ảnh
                    gallery.appendChild(img);
                };
                reader.readAsDataURL(file);
            }
        }

        function updateGallery() {
            const gallery = document.getElementById('gallery');
            gallery.innerHTML = ''; // Xóa tất cả hình ảnh hiện có

            const images = document.querySelectorAll('input[type="file"]');
            images.forEach(input => {
                const file = input.files[0];
                if (file) {
                    const reader = new FileReader();
                    reader.onload = function(e) {
                        const img = document.createElement('img');
                        img.src = e.target.result;
                        img.className = 'gallery-image me-2 mb-2';
                        img.style.width = '100px';
                        img.style.height = 'auto';
                        img.setAttribute('data-input-id', input.id); // Gán ID trường nhập vào hình ảnh
                        gallery.appendChild(img);
                    };
                    reader.readAsDataURL(file);
                }
            });
        }
    </script>
@endsection
