@extends('firebase.organizer-app')

@section('content')

<div class="d-flex justify-content-between mb-3">
    <h3 class="fw-bold fs-4 mb-1 mt-4 ms-4">Event List</h3> 
    <a href="{{ url('event-setup') }}" class="add-criteria-setup-btn btn btn-primary">
        <i class="ri-add-circle-line"></i> Add Event
    </a>
</div>
    <div class="table-row mt-3 " >
        <div class="col-12 ">
            <table class="table table-sm table-hover ">
                <thead class="table-light ">
                    <tr class="highlightr text-center" >
                        <th scope="row">#</th>
                        <th scope="col">Event Name</th>
                        <th scope="col">Organizer</th>
                        <th scope="col">Status</th>
                        <th scope="col">Action</th>
                    </tr>
                </thead>
                <tbody class="table-group-divider text-center">
                    @php $i = 1; @endphp
                    @forelse ($events as $key => $item)
                    <tr> <!-- Start of the row -->
                        <td>{{ $i++ }}</td>
                        <td>{{ isset($item['ename']) ? $item['ename'] : '' }}</td>
                        <td>{{ isset($item['eorganizer']) ? $item['eorganizer'] : '' }}</td>
                        <td></td> <!-- If you need to leave this column blank -->
                        <td>
                        <a href="{{ url('edit-event/' . $key) }}" class="btn btn-primary btn-sm">
                            <i class="ri-edit-box-line"></i> Edit
                        </a>
                        
                        <a href="{{ url('delete-event/' . $key) }}" class="btn  btn-danger btn-sm">
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