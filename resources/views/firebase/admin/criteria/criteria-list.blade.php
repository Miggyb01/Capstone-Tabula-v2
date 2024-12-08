@extends('firebase.layouts.admin-app')

@section('content')
<div class="d-flex justify-content-between mb-3">
    <h3 class="fw-bold fs-4 mb-1 mt-4 ms-4">Criteria List</h3>
    <a href="{{ route('admin.criteria.setup') }}" class="add-criteria-setup-btn btn btn-primary">
        <i class="ri-add-circle-line"></i> Add Criteria
    </a>
</div>

<!-- Search and Sort Controls -->
<div class="row mb-3 px-4">
    <div class="col-md-9">
        <form action="{{ route('admin.criteria.list') }}" method="GET" class="d-flex">
            <div class="input-group">
                <input type="text" name="search" class="form-control" placeholder="Search by event name..." 
                       value="{{ request('search') }}">
                <button class="btn btn-outline-secondary" type="submit">
                    <i class="ri-search-line"></i> Search
                </button>
                @if(request('search'))
                    <a href="{{ route('admin.criteria.list') }}" class="btn btn-outline-primary">
                        <i class="ri-close-line"></i> Clear
                    </a>
                @endif
            </div>
        </form>
    </div>
    <div class="col-md-3 text-end">
        <div class="btn-group">
            <a href="{{ route('admin.criteria.list', array_merge(['sort' => 'newest'], request()->except('sort'))) }}" 
               class="btn btn-outline-primary btn-sm {{ request('sort', 'newest') === 'newest' ? 'active' : '' }}">
                Newest First
            </a>
            <a href="{{ route('admin.criteria.list', array_merge(['sort' => 'oldest'], request()->except('sort'))) }}" 
               class="btn btn-outline-primary btn-sm {{ request('sort') === 'oldest' ? 'active' : '' }}">
                Oldest First
            </a>
        </div>
    </div>
</div>

<!-- Results count -->
@if(request('search'))
<div class="px-4 mb-3">
    <small class="text-muted">
        Found {{ count($criteriaList) }} result(s) for "{{ request('search') }}"
    </small>
</div>
@endif

<div class="criteria-container px-4">
    @forelse ($criteriaList as $criteriaId => $criteriaData)
        <div class="criteria-card mb-4" style="border: 0.5px solid gray; border-radius: 15px;">
            <div class="criteria-header p-3">
                <div class="event-name fs-5">
                    <strong>Event Name: </strong>{{ $criteriaData['event_name'] }}
                </div>
                <div class="d-flex justify-content-end">
                    <a href="{{ route('admin.criteria.edit', $criteriaData['id']) }}" class="btn btn-primary btn-sm me-2">
                        <i class="ri-edit-box-line"></i> Edit
                    </a>
                    <a href="{{ route('admin.criteria.delete', $criteriaData['id']) }}" class="btn btn-danger btn-sm"
                       onclick="return confirm('Are you sure you want to delete this criteria?')">
                        <i class="ri-delete-bin-line"></i> Delete
                    </a>
                </div>
            </div>

            @if(isset($criteriaData['categories']))
                @foreach ($criteriaData['categories'] as $categoryId => $categoryData)
                    <div class="criteria-details px-3">
                        <strong>Criteria Details:</strong> {{ $categoryData['criteria_details'] ?? 'No details provided' }}
                    </div>

                    <div class="category-wrapper mb-4">
                        <div class="category-header px-3" data-bs-toggle="collapse" data-bs-target="#category{{ $categoryId }}">
                            <span>
                                <i class="ri-arrow-down-s-line"></i>
                                <strong>Category Name:</strong> {{ $categoryData['category_name'] ?? 'No Category Name' }}
                            </span>
                        </div>
                        
                        <div class="collapse" id="category{{ $categoryId }}">
                            <div class="category-content px-3">
                                @if (isset($categoryData['main_criteria']))
                                    @foreach ($categoryData['main_criteria'] as $mainCriteriaId => $mainCriteriaData)
                                        <div class="main-criteria-item">
                                            <div class="main-criteria-header fw-bold fs-5">
                                                <i class="ri-star-fill criteria-star"></i>
                                                <span>{{ $mainCriteriaData['name'] ?? 'No Name' }}</span>
                                                <span class="percentage-badge">({{ $mainCriteriaData['percentage'] ?? '0' }}%)</span>
                                            </div>
                                            
                                            @if(isset($categoryData['sub_criteria'][$mainCriteriaId]))
                                                <div class="sub-criteria-list">
                                                    @foreach($categoryData['sub_criteria'][$mainCriteriaId] as $subCriteria)
                                                        <div class="sub-criteria-item">
                                                            <span class="bullet">•</span> 
                                                            {{ $subCriteria['name'] ?? 'No Sub-criteria Name' }} 
                                                            ({{ $subCriteria['percentage'] ?? '0' }}%)
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
        <div class="alert alert-info">
            @if(request('search'))
                No criteria found matching "{{ request('search') }}"
            @else
                No criteria found
            @endif
        </div>
    @endforelse
</div>

<style>
.criteria-card {
    background: white;
    box-shadow: 0 2px 4px rgba(0,0,0,0.1);
}

.category-header {
    cursor: pointer;
    padding: 10px 0;
}

.category-header:hover {
    background-color: #f8f9fa;
}

.main-criteria-item {
    margin: 15px 0;
}

.criteria-star {
    color: #ffd700;
    margin-right: 5px;
}

.sub-criteria-list {
    margin-left: 25px;
}

.sub-criteria-item {
    margin: 5px 0;
}

.bullet {
    color: #6c757d;
    margin-right: 5px;
}

.percentage-badge {
    color: #6c757d;
    font-size: 0.9em;
    margin-left: 5px;
}

.btn-group {
    display: flex;
    justify-content: center;
}

.input-group {
    max-width: 100%;
}
</style>
@endsection