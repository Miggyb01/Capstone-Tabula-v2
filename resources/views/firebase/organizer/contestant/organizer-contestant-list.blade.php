@extends('firebase.organizer-app')

@section('content')
<div class="d-flex justify-content-between mb-3">
    <h3 class="fw-bold fs-4 mb-1 mt-4 ms-4">Coontestant List</h3> 
    <a href="{{ url('contestant-setup') }}" class="add-criteria-setup-btn btn btn-primary">
        <i class="ri-add-circle-line"></i> Add Contestant
    </a>
</div>
    <div class="table-row mt-3 " >
        <div class="col-12 ">
            <table class="table table-sm table-hover ">
                <thead class="table-light ">
                    <tr class="highlightr text-center" >
                        <th scope="row">#</th>
                        <th scope="col">Full Name</th>
                        <th scope="col">Age</th>
                        <th scope="col">Gender</th>
                        <th scope="col">Personal Background</th>
                        <th scope="col">Action</th>
                    </tr>
                </thead>
                <tbody class="table-group-divider text-center">
                @php $i = 1; @endphp
                    @forelse ($contestants as $key => $item)
                    <tr> <!-- Start of the row -->
                        <td>{{ $i++ }}</td>
                        <td>{{ $item['cfname'] }} {{ $item['cmname'] }} {{ $item['clname'] }}</td>
                        <td>{{ $item['cage'] }}</td>
                        <td>{{ $item['cgender'] }}</td>
                        <td>{{ $item['cbackground'] }}</td>
                         <!-- If you need to leave this column blank -->
                        <td>
                        <a href="{{ url('edit-contestant/' . $key) }}" class="btn btn-primary btn-sm">
                            <i class="ri-edit-box-line"></i> Edit
                        </a>
                        
                        <a href="{{ url('delete-contestant/' . $key) }}" class="btn  btn-danger btn-sm">
                            <i class="ri-delete-bin-line"></i> Delete
                        </a>

                        </td>
                    </tr> <!-- End of the row -->
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