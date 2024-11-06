@extends('firebase.app')

@section('content')

<h3 class=" fw-bold fs-4 mb-1 mt-4 ms-4">Criteria List</h3> 
    <div class="table-row mt-3 " >
        <div class="col-12 ">
            <table class="table table-sm table-hover ">
                <thead class="table-light ">
                    <tr class="highlightr text-center" >
                        <th scope="row">#</th>
                        <th scope="col">Event Name</th>
                        <th scope="col">Crit</th>
                        <th scope="col">Password</th>
                        <th scope="col">Event Assigned</th>
                        <th scope="col">Action</th>
                    </tr>
                </thead>
                <tbody class="table-group-divider text-center">
                    @php $i = 1; @endphp
                    @forelse ($criterias as $key => $item)
                    <tr> <!-- Start of the row -->
                        <td>{{ $i++ }}</td>
                        <td>{{ $item['jfname'] }} {{ $item['jmname'] }} {{ $item['jlname'] }}</td>
                        <td>{{ $item['jusername'] }}</td>
                        <td>{{ $item['jpassword'] }}</td>
                        <td></td> <!-- If you need to leave this column blank -->
                        <td>
                        <a href="{{ url('edit-judge/' . $key) }}" class="btn btn-primary btn-sm">
                            <i class="ri-edit-box-line"></i> Edit
                        </a>
                        
                        <a href="{{ url('delete-judge/' . $key) }}" class="btn  btn-danger btn-sm">
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