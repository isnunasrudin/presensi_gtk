@extends('layouts.admin')

@section('main-content')
    <!-- Page Heading -->
    <h1 class="h3 mb-4 text-gray-800">{{ $title ?? __('Blank Page') }}</h1>

    <!-- Main Content goes here -->
    <div class="d-flex">
        <div class="d-block">
            <button class="btn btn-primary mb-3" id="ket">Tambah Keterangan</button>
            <a class="btn btn-primary mb-3" href="{{ route('ptk.download', ['kapan' => request()->get('kapan')]) }}">Rekap</a>
        </div>
        <form class="ml-auto d-flex">
            <input type="text" name="kapan" class="form-control" style="width: 100px" value="{{ request()->get('kapan') ?? \Carbon\Carbon::now()->format('Y-m') }}">
            <button class="btn btn-primary mb-3 ml-2" id="ket">Update</button>
        </form>
    </div>

    <div class="alert alert-success">
        {{ \Carbon\Carbon::parse(request()->get('kapan') ?? now())->formatLocalized('%B %Y') }}<br />
        Hari Kerja : {{ App\FreeDay::jumlah_hk(request()->get('kapan') ?? \Carbon\Carbon::now()->format('Y-m')) }}
    </div>

    @if (session('message'))
        <div class="alert alert-success">
            {{ session('message') }}
        </div>
    @endif

    <div class="table-responsive">

        <table class="table table-bordered table-stripped table-sm">
            <thead>
                <tr>
                    <th rowspan="2" class="text-center">No</th>
                    <th rowspan="2" class="text-center">Nama</th>
                    <th colspan="{{ count($days) }}" class="text-center">Tanggal</th>
                    {{-- <!-- <th>Email</th>
                    <th>#</th> --> --}}
                </tr>
                <tr>
                    @foreach ($days as $day)
                        <th>{{ $day->format('d') }}</th>
                    @endforeach
                </tr>
            </thead>
            <tbody>
                @foreach ($ptks as $ptk)
                    <tr>
                        <td scope="row">{{ $loop->iteration }}</td>
                        <td>{{ $ptk->name }}</td>
                        @foreach ($days as $day)
                            @if($day->isWeekend())
                            <td class="bg-secondary"></td>
                            @elseif (App\FreeDay::isFree($day))
                            <td class="bg-danger"></td>
                            @else
                                @if (App\Presence::where('date', $day)->where('ptk_id', $ptk->id)->count())
                                    <td class="bg-warning">{{ implode(", ", App\Presence::where('date', $day)->where('ptk_id', $ptk->id)->get()->pluck('type')->toArray()) }}</td>
                                @else
                                <td>Y</td>
                                @endif
                            @endif
                        @endforeach
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
    $('#ket').click(function(){
        Swal.fire({
            title: 'Tambah Keterangan',
            html: `
            <div id="anzay" class="container">
                <div class="form-group mb-4">
                        <label>Nama</label>
                        <select name="ptk_id" class="form-control">
                        @foreach (App\Ptk::orderBy('name')->get() as $ptk)
                            <option value={{ $ptk->id }}>{{ $ptk->name }}</option>
                        @endforeach
                    </select>
                </div>
                <div class="row">
                    <div class="col">
                        <div class="form-group">
                            <label>Tanggal</label>
                            <input type="date" class="form-control" name="date" placeholder="Masukkan" value="{{ request()->get('kapan') ? (request()->get('kapan') . '-01') : \Carbon\Carbon::now()->format('Y-m') }}">
                        </div>
                    </div>
                    <div class="col">
                        <div class="form-group">
                            <label>Keterangan</label>
                            <select name="type" class="form-control">
                                @foreach(["X", "Y", "V", "T1", "T2", "T3", "T4", "PC1", "PC2", "PC3", "PC4", "TAD", "TAP", "ET", "EPC", "S", "CT", "CBS", "CBSR", "CS", "CM", "CAP", "CH", "CN", "MJ", "CLN", "DL", "R", "I", "TB", "IB", "TR", "P5"] as $type)
                                <option value="{{ $type }}">{{ $type }}</option>
                                @endforeach
                            </select>
                        </div>
                    </div>
                </div>
                <div class="form-group">
                    <label>Nilai (Dalam Menit)</label>
                    <input type="number" class="form-control" name="value" placeholder="Kosongi jika tidak perlu">
                </div>
            </div>
            `,
        showConfirmButton: true,
        showCancelButton: true,
        confirmButtonText: "Simpan",
        preConfirm(){
            return $.ajax({
                type : 'POST',
                url : '{{ route('ptk.addPresence') }}',
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
