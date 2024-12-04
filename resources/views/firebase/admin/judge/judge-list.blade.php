@extends('firebase.layouts.admin-app')

@section('content')
<div class="d-flex justify-content-between mb-3">
    <h3 class="fw-bold fs-4 mb-1 mt-4 ms-4">Judge List</h3> 
    <a href="{{ route('admin.judge.setup') }}" class="add-criteria-setup-btn btn btn-primary">
        <i class="ri-add-circle-line"></i> Add Judge
    </a>
</div>

@if(session('success'))
<div class="alert alert-success">
    {!! session('success') !!}
</div>
@endif

<div class="table-row mt-3">
    <div class="col-12">
        <table class="table table-sm table-hover">
            <thead class="table-light">
                <tr class="highlightr text-center">
                    <th scope="row">#</th>
                    <th scope="col">Full Name</th>
                    <th scope="col">Username</th>
                    <th scope="col">Password</th>
                    <th scope="col">Assigned Event</th>
                    <th scope="col">Status</th>
                    <th scope="col">Action</th>
                </tr>
            </thead>
            <tbody class="table-group-divider text-center">
                @php $i = 1; @endphp
                @forelse ($judges as $key => $item)
                <tr>
                    <td>{{ $i++ }}</td>
                    <td>{{ $item['jfname'] }} {{ $item['jmname'] }} {{ $item['jlname'] }}</td>
                    <td>{{ $item['jusername'] }}</td>
                    <td>••••••••</td>
                    <td>{{ isset($item['event_name']) ? $item['event_name'] : 'N/A' }}</td>
                    <td>{{ isset($item['status']) ? $item['status'] : 'Active' }}</td>
                    <td>
                        <a href="{{ route('admin.judge.edit', $key)}}" class="btn btn-primary btn-sm">
                            <i class="ri-edit-box-line"></i> Edit
                        </a>
                        <a href="{{ route('admin.judge.delete', $key) }}" class="btn btn-danger btn-sm" 
                           onclick="return confirm('Are you sure you want to delete this judge?')">
                            <i class="ri-delete-bin-line"></i> Delete
                        </a>
                    </td>
                </tr>
                @empty
                <tr>
                    <td colspan="7">No Record Found</td>
                </tr>
                @endforelse
            </tbody>
        </table>
    </div>
</div>
@endsection