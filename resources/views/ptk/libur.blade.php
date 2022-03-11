@extends('layouts.admin')

@section('main-content')
    <!-- Page Heading -->
    <h1 class="h3 mb-4 text-gray-800">{{ $title ?? __('Blank Page') }}</h1>

    <!-- Main Content goes here -->

    <button class="btn btn-primary mb-3" id="add">Tambah Libur</button>

    @if (session('message'))
        <div class="alert alert-success">
            {{ session('message') }}
        </div>
    @endif

    <div class="table-responsive">
        <table class="table table-bordered table-stripped table-sm">
            <thead>
                <tr>
                    <th class="text-center">No</th>
                    <th class="text-center">Tanggal</th>
                    <th class="text-center">Keterangan</th>
                </tr>
            </thead>
            <tbody>
                @foreach ($freedays as $fd)
                    <tr>
                        <td scope="row">{{ $loop->iteration }}</td>
                        <td>{{ $fd->date }}</td>
                        <td>{{ $fd->name }}</td>
                        {{-- <!-- <td>{{ $user->email }}</td>
                        <td>
                            <div class="d-flex">
                                <a href="{{ route('basic.edit', $user->id) }}" class="btn btn-sm btn-primary mr-2">Edit</a>
                                <form action="{{ route('basic.destroy', $user->id) }}" method="post">
                                    @csrf
                                    @method('delete')
                                    <button type="submit" class="btn btn-sm btn-danger" onclick="return confirm('Are you sure to delete this?')">Delete</button>
                                </form>
                            </div>
                        </td> --> --}}
                    </tr>
                @endforeach
            </tbody>
        </table>
    </div>

    {{-- <!-- {{ $users->links() }} --> --}}

    <!-- End of Main Content -->
@endsection

@push('notif')
    @if (session('success'))
        <div class="alert alert-success border-left-success alert-dismissible fade show" role="alert">
            {{ session('success') }}
            <button type="button" class="close" data-dismiss="alert" aria-label="Close">
                <span aria-hidden="true">&times;</span>
            </button>
        </div>
    @endif

    @if (session('status'))
        <div class="alert alert-success border-left-success" role="alert">
            {{ session('status') }}
        </div>
    @endif
@endpush

@push('js')
<script>
    $('#add').click(function(){
        Swal.fire({
            title: 'Tambah Libur',
            html: `
            <div id="anzay" class="container">
                <div class="form-group">
                    <label>Tanggal</label>
                    <input type="date" class="form-control" name="date" placeholder="Masukkan" value="{{ \Carbon\Carbon::now()->format('Y-m-d') }}">
                </div>
                <div class="form-group">
                    <label>Keterangan</label>
                    <input type="text" class="form-control" name="name">
                </div>
            </div>
            `,
        showConfirmButton: true,
        showCancelButton: true,
        confirmButtonText: "Simpan",
        preConfirm(){
            return $.ajax({
                type : 'POST',
                url : '{{ route('ptk.libur') }}',
                data : $('#anzay :input').serialize(),
                headers: {'X-Requested-With': 'XMLHttpRequest'}
            }).then(function(){
                location.href = ""
            });
        }
        })
    });

    $('#rekap').click(function(){

    });
</script>
@endpush
