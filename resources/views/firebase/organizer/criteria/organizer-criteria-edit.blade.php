@extends('firebase.organizer-app')

@section('content')

<div class="p-5">
    <div class="event-setup-form-header justify-content-center">
        <div class="event-icon-container align-items-center">
            <i class="ri-group-line"></i>
            <span>Criteria Setup</span>
        </div>
    </div>

    <form action="{{ route('criteria.update', $criteria['id']) }}" method="POST" id="criteriaForm">
    @csrf
    @method('PUT')

        <!-- Event Selection -->
        <div class="contestant-form-row row">
            <div class="col">
                <label for="event" class="form-label mt-1 ms-2">Select Event <span class="text-danger">*</span></label>
                <select name="event_name" id="event" class="form-control" required>
                    <option value="" disabled selected>Select Event</option>
                    @foreach($events as $eventId => $event)
                        @if(isset($event['ename']))
                            <option value="{{ $event['ename'] }}" {{ $criteria['event_name'] == $event['ename'] ? 'selected' : '' }}>
                                {{ $event['ename'] }}
                            </option>
                        @endif
                    @endforeach
                </select>

        <!-- Initial Default Category Form Structure -->
<div id="categories-container">
@foreach($criteria['categories'] as $categoryIndex => $category)
    <div class="category-group mb-3 mt-3">
        <!-- Default Category Input -->
        <div class="row">
            <div class="col">
                <label for="category" class="form-label mt-1 ms-2">Category <span class="text-danger">*</span></label>
                <input type="text" class="form-control" name="categories[{{$categoryIndex}}][category_name]" placeholder="Category" value="{{ $category['category_name'] }}" required>

                <label for="criteria_details" class="form-label mt-3 ms-2">Criteria Details <span class="text-danger">*</span></label>
                <textarea class="form-control" name="categories[{{$categoryIndex}}][criteria_details]" rows="2" required> {{ $category['criteria_details'] }}</textarea>
            </div>
            <div class="col-auto">
                @if($categoryIndex === 0)
                    <button type="button" class="btn btn-primary add-category-btn">+</button>
                @else
                    <button type="button" class="btn btn-danger remove-category-btn">-</button>
                @endif
            </div>
        </div>

        <!-- Default Main and Sub Criteria -->
        @foreach($category['main_criteria'] as $mainIndex => $mainCriteria)
        <div class="row mb-2 mt-3">
            <!-- Default Main Criteria -->
            <div class="main-criteria-group col-6">
                <div class="row">
                    <div class="col">
                        <label class="form-label">Main Criteria <span class="text-danger">*</span></label>
                        <input type="text" class="form-control" name="main_criteria[{{ $categoryIndex }}][]" value="{{ $mainCriteria['name'] }}" required>
                    </div>
                    <div class="col">
                        <label class="form-label">Percentage <span class="text-danger">*</span></label>
                        <input type="number" class="form-control" name="main_criteria_percentage[{{ $categoryIndex }}][]" value="{{ $mainCriteria['percentage'] }}" required>
                    </div>
                    <div class="col-auto">
                        @if($mainIndex === 0)
                            <button type="button" class="btn btn-primary add-main-criteria-btn" data-category-index="{{ $categoryIndex }}">+</button>
                        @else
                            <button type="button" class="btn btn-danger remove-main-criteria-btn">-</button>
                        @endif
                    </div>
                </div>
            </div>

            <!-- Default Sub Criteria -->
            <div class="sub-criteria-container col-6" data-category-index="{{ $categoryIndex }}" data-main-criteria-index="{{ $mainIndex }}">
                    @foreach($mainCriteria['sub_criteria'] as $subIndex => $subCriteria)
                        <div class="row sub-criteria-row {{ $subIndex > 0 ? 'mt-2' : '' }}">
                            <div class="col">
                                <label class="form-label">Sub-Criteria <span class="text-danger">*</span></label>
                                <input type="text" class="form-control" name="sub_criteria[{{ $categoryIndex }}][{{ $mainIndex }}][]" value="{{ $subCriteria['name'] }}" required>
                            </div>
                            <div class="col">
                                <label class="form-label">Percentage <span class="text-danger">*</span></label>
                                <input type="number" class="form-control" name="sub_criteria_percentage[{{ $categoryIndex }}][{{ $mainIndex }}][]" value="{{ $subCriteria['percentage'] }}" required>
                            </div>
                            <div class="col-auto">
                                @if($subIndex === 0)
                                    <button type="button" class="btn btn-primary add-sub-criteria-btn">+</button>
                                @else
                                    <button type="button" class="btn btn-danger remove-sub-criteria-btn">-</button>
                                @endif
                            </div>
                        </div>
                @endforeach
                </div>
            </div>
        @endforeach
    </div>
    @endforeach
</div>
    <!-- Form Buttons -->
        <div class="form-group text-center">
            <form action="{{ url(route('criteria-list')) }}" method="get">
            <button type="button" class="btn-cancel" onclick="window.location.href='{{ route('criteria-list') }}'">Cancel</button>
            </form>
            <button type="submit" class="btn-add">Update</button>
        </div>
    </form>
</div>

<script>
// Add main criteria row
document.addEventListener('click', function(e) {
    if (e.target.classList.contains('add-main-criteria-btn')) {
        const categoryGroup = e.target.closest('.category-group');
        const categoryIndex = Array.from(document.querySelectorAll('.category-group')).indexOf(categoryGroup);
        const mainCriteriaContainer = e.target.closest('.main-criteria-group').parentNode;
        const mainCriteriaIndex = mainCriteriaContainer.querySelectorAll('.main-criteria-group').length;
        
        const newMainCriteria = `
            <div class="row mb-2 mt-3">
                <div class="main-criteria-group col-6">
                    <div class="row">
                        <div class="col">
                            <label class="form-label">Main Criteria <span class="text-danger">*</span></label>
                            <input type="text" class="form-control" name="main_criteria[${categoryIndex}][]" placeholder="Main Criteria" required>
                        </div>
                        <div class="col">
                            <label class="form-label">Percentage <span class="text-danger">*</span></label>
                            <input type="number" class="form-control" name="main_criteria_percentage[${categoryIndex}][]" placeholder="Percentage" required>
                        </div>
                        <div class="col-auto">
                            <button type="button" class="btn btn-danger remove-main-criteria-btn">-</button>
                        </div>
                    </div>
                </div>
                <div class="sub-criteria-container col-6" data-category-index="${categoryIndex}" data-main-criteria-index="${mainCriteriaIndex}">
                    <div class="row sub-criteria-row">
                        <div class="col">
                            <label class="form-label">Sub-Criteria <span class="text-danger">*</span></label>
                            <input type="text" class="form-control" name="sub_criteria[${categoryIndex}][${mainCriteriaIndex}][]" placeholder="Sub-Criteria" required>
                        </div>
                        <div class="col">
                            <label class="form-label">Percentage <span class="text-danger">*</span></label>
                            <input type="number" class="form-control" name="sub_criteria_percentage[${categoryIndex}][${mainCriteriaIndex}][]" placeholder="Percentage" required>
                        </div>
                        <div class="col-auto">
                            <button type="button" class="btn btn-primary add-sub-criteria-btn">+</button>
                        </div>
                    </div>
                </div>
            </div>
        `;
        mainCriteriaContainer.insertAdjacentHTML('beforeend', newMainCriteria);
    }
});

// Add sub-criteria row
document.addEventListener('click', function(e) {
    if (e.target.classList.contains('add-sub-criteria-btn')) {
        const subCriteriaContainer = e.target.closest('.sub-criteria-container');
        const categoryIndex = subCriteriaContainer.getAttribute('data-category-index');
        const mainCriteriaIndex = subCriteriaContainer.getAttribute('data-main-criteria-index');
        
        const newSubCriteria = `
            <div class="row sub-criteria-row mt-2">
                <div class="col">
                    <label class="form-label">Sub-Criteria <span class="text-danger">*</span></label>
                    <input type="text" class="form-control" name="sub_criteria[${categoryIndex}][${mainCriteriaIndex}][]" placeholder="Sub-Criteria" required>
                </div>
                <div class="col">
                    <label class="form-label">Percentage <span class="text-danger">*</span></label>
                    <input type="number" class="form-control" name="sub_criteria_percentage[${categoryIndex}][${mainCriteriaIndex}][]" placeholder="Percentage" required>
                </div>
                <div class="col-auto">
                    <button type="button" class="btn btn-danger remove-sub-criteria-btn">-</button>
                </div>
            </div>
        `;
        subCriteriaContainer.insertAdjacentHTML('beforeend', newSubCriteria);
    }
});

// Remove handlers
document.addEventListener('click', function(e) {
    if (e.target.classList.contains('remove-category-btn')) {
        e.target.closest('.category-group').remove();
    }
    if (e.target.classList.contains('remove-main-criteria-btn')) {
        e.target.closest('.row.mb-2').remove();
    }
    if (e.target.classList.contains('remove-sub-criteria-btn')) {
        e.target.closest('.sub-criteria-row').remove();
    }
});
</script>

@endsection