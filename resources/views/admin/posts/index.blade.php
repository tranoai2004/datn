@extends('admin.master')

@section('title', 'Danh sách bài viết')

@section('content')
    <div class="content-wrapper-scroll">
        <div class="content-wrapper">
            {{-- @if (session('success'))
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
            @endif --}}

            <div class="row">
                <div class="col-sm-12 col-12">
                    <div class="card">
                        <div class="card-header d-flex justify-content-between align-items-center">
                            <div class="card-title">Danh Sách Bài Viết</div>
                            <div>
                                <a href="{{ route('posts.create') }}"
                                    class="btn btn-primary btn-rounded d-flex align-items-center">
                                    <i class="bi bi-plus-circle me-2"></i> Thêm Mới
                                </a>
                                <a href="{{ route('posts.trash') }}"
                                    class="btn btn-primary btn-rounded d-flex align-items-center mt-3">
                                    <i class="bi bi-trash me-2"></i> Thùng Rác
                                </a>
                            </div>
                        </div>
                        <div class="card-body">
                            <form method="GET" action="" class="mb-3">
                                <div class="row g-2">
                                    <div class="col-auto">
                                        <input type="text" id="id" name="search"
                                            class="form-control form-control-sm" placeholder="Tìm kiếm"
                                            value="{{ request()->search }}">
                                    </div>
                                    <div class="col-auto">
                                        <button type="submit" class="btn btn-sm btn-primary">Tìm kiếm</button>
                                    </div>
                                </div>
                            </form>
                            <table class="table table-striped">
                                <thead>
                                    <tr>
                                        <th>ID</th>
                                        <th>Tiêu đề</th>
                                        <th>Tóm tắt</th>
                                        <th>Danh mục</th>
                                        <th>Hình Ảnh</th>
                                        <th>Ngày tạo</th>
                                        <th>Nổi bật</th>
                                        <th>Hành động</th>
                                    </tr>
                                </thead>
                                <tbody>
                                    @foreach ($posts as $post)
                                        <tr>
                                            <td>{{ $post->id }}</td>
                                            <td class="title-column" style="width: 18%;">{{ $post->title }}</td>
                                            <td class="title-column" style="width: 18%;">{{ $post->tomtat }}</td>
                                            @if ($post->category)
                                                <td>{{ $post->category->name }}</td>
                                            @else
                                                <td>Không có danh mục</td>
                                            @endif
                                            <td>
                                                @if ($post->image && \Storage::exists($post->image))
                                                    <img src="{{ \Storage::url($post->image) }}" alt="{{ $post->name }}"
                                                        style="max-width: 100px; height: auto;">
                                                @else
                                                    Không có ảnh
                                                @endif
                                            </td>
                                            <td>{{ $post->created_at->format('d/m/Y H:i') }}</td>

                                            <td>
                                                @if ($post->is_featured)
                                                    <span class="badge rounded-pill bg-warning">Nổi bật</span>
                                                @else
                                                    <span class="badge rounded-pill bg-secondary">Không nổi bật</span>
                                                @endif
                                            </td>


                                            <td class="title-column" style="width: 20%;">
                                                <!-- Nút sửa bài viết -->
                                                <a href="{{ route('posts.edit', $post->id) }}" class="editRow"
                                                    title="Sửa" style="margin-right: 15px;">
                                                    <i class="bi bi-pencil-square text-warning"
                                                        style="font-size: 1.8em;"></i>
                                                </a>

                                                <!-- Form để xóa bài viết -->
                                                <form action="{{ route('posts.destroy', $post->id) }}" method="POST"
                                                    class="d-inline-block delete-form"
                                                    onsubmit="return confirm('Bạn có chắc muốn xóa bài viết này không?');">
                                                    @csrf
                                                    @method('DELETE')
                                                    <button type="submit" class="delete-btn"
                                                        style="background: none; border: none; padding: 0;"
                                                        title="Xóa bài viết">
                                                        <i class="bi bi-trash text-danger" style="font-size: 1.8em;"></i>
                                                    </button>
                                                </form>
                                            </td>
                                        </tr>
                                    @endforeach
                                </tbody>
                            </table>
                            {{ $posts->links() }}
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </div>
@endsection

@section('scripts')
    <link rel="stylesheet" href="https://cdn.jsdelivr.net/npm/sweetalert2@11/dist/sweetalert2.min.css">
    <script src="https://cdn.jsdelivr.net/npm/sweetalert2@11/dist/sweetalert2.all.min.js"></script>

    @if (session()->has('success'))
        <script>
            Swal.fire({
                position: "top",
                icon: "success",
                title: "{{ session('success') }}",
                showConfirmButton: false,
                timerProgressBar: true,
                timer: 1500
            });
        </script>
    @endif

    @if (session()->has('error'))
        <script>
            Swal.fire({
                position: "top",
                icon: "error",
                title: "{{ session('error') }}",
                showConfirmButton: false,
                timerProgressBar: true,
                timer: 2500
            });
        </script>
    @endif

    <script>
        document.querySelectorAll('.delete-btn').forEach(button => {
            button.addEventListener('click', function(e) {
                e.preventDefault();
                const form = this.closest('.delete-form');
                Swal.fire({
                    position: "top",
                    title: 'Bạn có chắc chắn muốn xóa bài viết này?',
                    icon: 'warning',
                    showCancelButton: true,
                    confirmButtonColor: '#3085d6',
                    cancelButtonColor: '#d33',
                    confirmButtonText: 'Có',
                    cancelButtonText: 'Hủy',
                    timer: 3500
                }).then((result) => {
                    if (result.isConfirmed) {
                        form.submit();
                    }
                });
            });
        });
    </script>
@endsection
