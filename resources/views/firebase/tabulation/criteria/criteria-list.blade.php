@extends('firebase.app')

@section('content')


<div class="d-flex justify-content-between mb-3">
    <h3 class="fw-bold fs-4 mb-1 mt-4 ms-4">Criteria List</h3>
    <a href="{{ url('criteria-setup') }}" class="add-criteria-setup-btn btn btn-primary">
        <i class="ri-add-circle-line"></i> Add Criteria
    </a>
</div>

<div class="criteria-container">
    @forelse ($criteriaList as $criteriaId => $criteriaData)
        <div class="criteria-card" style="border: 0.5px solid gray; border-radius: 15px; ">
            <div class="criteria-header"  >
                <div class="event-name fs-5"><strong>Event Name: </strong>{{ $criteriaData['event_name'] ?? 'No Event Name' }}</div>
                <div class="d-flex justify-content-end">
                    <a href="{{ url('edit-criteria/' . $criteriaId) }}" class="btn btn-primary btn-sm me-2">
                        <i class="ri-edit-box-line"></i> Edit
                    </a>
                    <a href="{{ url('delete-criteria/' . $criteriaId) }}" class="btn btn-danger btn-sm">
                        <i class="ri-delete-bin-line"></i> Delete
                    </a>
                </div>
                
            </div>
            @if(isset($criteriaData['categories']))
                @foreach ($criteriaData['categories'] as $categoryId => $categoryData)
                <div class="criteria-details  ">
                    <strong>Criteria Details:</strong> {{ $categoryData['criteria_details'] ?? 'No details provided' }}
                </div>

                    <div class="category-wrapper mb-4">
                        <div class="category-header" data-bs-toggle="collapse" data-bs-target="#category{{ $categoryId }}">
                            <span><i class="ri-arrow-down-s-line"></i><strong>Category Name:</strong> {{ $categoryData['category_name'] ?? 'No Category Name' }}</span>
                        </div>
                        
                        <div class="collapse" id="category{{ $categoryId }}">
                            <div class="category-content">
                                @if (isset($categoryData['main_criteria']))
                                    @foreach ($categoryData['main_criteria'] as $mainCriteriaId => $mainCriteriaData)
                                        <div class="main-criteria-item">
                                            <div class="main-criteria-header fw-bold fs-5">
                                                <i class="ri-star-fill criteria-star"></i>
                                                <span>{{ $mainCriteriaData['name'] ?? 'No Name' }}</span>
                                                <span class="percentage-badge">({{ $mainCriteriaData['percentage'] ?? '0' }}%)</span>
                                            </div>
                                            
                                            @if(isset($categoryData['sub_criteria'][$mainCriteriaId]))
                                                <div class="sub-criteria-list ">
                                                    @foreach($categoryData['sub_criteria'][$mainCriteriaId] as $subCriteria)
                                                        <div class="sub-criteria-item">
                                                            <span class="bullet">•</span> {{ $subCriteria['name'] ?? 'No Sub-criteria Name' }} ({{ $subCriteria['percentage'] ?? '0' }}%)
                                                        </div>
                                                    @endforeach
                                                </div>
                                            @endif
                                        </div>
                                    @endforeach
                                @endif
                            </div>
                        </div>
                    </div>
                @endforeach
            @endif
        </div>
    @empty
        <div class="alert alert-info">No Record Found</div>
    @endforelse
</div>
@endsection