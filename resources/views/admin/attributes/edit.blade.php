@extends('admin.master')

@section('title', 'Chỉnh sửa Attribute')

@section('content')
    <div class="content-wrapper-scroll">
        <div class="content-wrapper">
            <div class="card">
                <div class="card-header d-flex justify-content-between align-items-center">
                    <div class="card-title">Chỉnh sửa Thuộc Tính</div>
                    <a href="{{ route('attributes.index') }}" class="btn btn-sm rounded-pill btn-secondary d-flex align-items-center">
                        <i class="bi bi-arrow-left me-2"></i> Trở về
                    </a>
                </div>
                <div class="card-body mt-4">

                    <form action="{{ route('attributes.update', $attribute->id) }}" method="POST" id="attributeForm" class="was-validated">
                        @csrf
                        @method('PUT')

                        <div class="form-group">
                            <label for="name">Tên Attribute:</label>
                            <input type="text" class="form-control @error('name') is-invalid @enderror" name="name"
                                id="name" value="{{ old('name', $attribute->name) }}" required>
                            @error('name')
                                <div class="invalid-feedback">{{ $message }}</div>
                            @enderror
                        </div>

                        <div class="mt-3">
                            <button type="submit" id="submitButton" class="btn btn-primary rounded-pill d-flex align-items-center" disabled>
                                <i class="bi bi-pencil me-2"></i> Cập nhật Attribute
                            </button>
                        </div>
                    </form>
                </div>
            </div>
        </div>
    </div>
@endsection

@section('scripts')
    <script>
        function validateForm() {
            const name = document.getElementById('name').value.trim();
            const submitButton = document.getElementById('submitButton');

            // Kiểm tra xem trường "Tên Attribute" có giá trị không
            if (name) {
                submitButton.disabled = false; // Kích hoạt nút nếu trường có giá trị
            } else {
                submitButton.disabled = true; // Khóa nút nếu trường trống
            }
        }

        // Thêm sự kiện input cho trường "Tên Attribute"
        document.getElementById('name').addEventListener('input', validateForm);
    </script>
@endsection