@extends('firebase.layouts.organizer-app')

@section('content')
<div class="p-5">
    <div class="event-setup-form-header justify-content-center">
        <div class="event-icon-container align-items-center">
            <i class="ri-group-line"></i>
            <span>Organizer Criteria Setup</span>
        </div>
    </div>

    <form action="{{ route('organizer.criteria.store') }}" method="POST" id="criteriaForm">
        @csrf

        <!-- Event Selection -->
        <div class="contestant-form-row row">
            <div class="col">
                <label for="event" class="form-label mt-1 ms-2">Select Event <span class="text-danger">*</span></label>
                <select name="event_name" id="event" class="form-control" required>
                    <option value="" disabled selected>Select Event</option>
                    @foreach($events as $eventId => $event)
                        @if(isset($event['ename']))
                            <option value="{{ $event['ename'] }}">{{ $event['ename'] }}</option>
                        @else
                            <option value="{{ $eventId }}">Unnamed Event</option>
                        @endif
                    @endforeach
                </select>

        <!-- Initial Default Category Form Structure -->
        <div id="categories-container">
            <div class="category-group mb-3 mt-3">
                <!-- Default Category Input -->
                <div class="row">
                    <div class="col">
                        <label for="category" class="form-label mt-1 ms-2">Category <span class="text-danger">*</span></label>
                        <input type="text" class="form-control" name="category_name[]" placeholder="Category" required>

                        <label for="criteria_details" class="form-label mt-3 ms-2">Criteria Details <span class="text-danger">*</span></label>
                        <textarea class="form-control" name="criteria_details[]" placeholder="Details" rows="2" required></textarea>
                    </div>
                    <div class="col-auto">
                        <button type="button" class="btn btn-primary add-category-btn">+</button>
                    </div>
                </div>

                <!-- Default Main and Sub Criteria -->
                <div class="row mb-2 mt-3">
                    <!-- Default Main Criteria -->
                    <div class="main-criteria-group col-6">
                        <div class="row">
                            <div class="col">
                                <label class="form-label">Main Criteria <span class="text-danger">*</span></label>
                                <input type="text" class="form-control" name="main_criteria[0][]" placeholder="Main-Criteria" required>
                            </div>
                            <div class="col">
                                <label class="form-label">Percentage <span class="text-danger">*</span></label>
                                <input type="number" class="form-control" name="main_criteria_percentage[0][]" placeholder="Percentage" required>
                            </div>
                            <div class="col-auto">
                                <button type="button" class="btn btn-primary add-main-criteria-btn" data-category-index="0">+</button>
                            </div>
                        </div>
                    </div>

                    <!-- Default Sub Criteria -->
                    <div class="sub-criteria-container col-6" data-category-index="0" data-main-criteria-index="0">
                        <div class="row sub-criteria-row">
                            <div class="col">
                                <label class="form-label">Sub-Criteria <span class="text-danger">*</span></label>
                                <input type="text" class="form-control" name="sub_criteria[0][0][]" placeholder="Sub-Criteria" required>
                            </div>
                            <div class="col">
                                <label class="form-label">Percentage <span class="text-danger">*</span></label>
                                <input type="number" class="form-control" name="sub_criteria_percentage[0][0][]" placeholder="Percentage" required>
                            </div>
                            <div class="col-auto">
                                <button type="button" class="btn btn-primary add-sub-criteria-btn">+</button>
                            </div>
                        </div>
                    </div>
                </div>
            </div>
        </div>
        <!-- Form Buttons -->
        <div class="form-group text-center">
            <button type="button" class="btn-cancel" onclick="window.location.href='{{ route('organizer.dashboard') }}'">Cancel</button>
            <button type="submit" class="btn-add">Add</button>
        </div>
    </form>
</div>

<script>
// Add new category
document.querySelector('.add-category-btn').addEventListener('click', function() {
    const categoriesContainer = document.getElementById('categories-container');
    const categoryIndex = categoriesContainer.querySelectorAll('.category-group').length;
    
    const newCategory = `
        <div class="category-group mb-3 mt-5">
            <div class="row">
                <div class="col">
                    <label for="category" class="form-label mt-1 ms-2">Category <span class="text-danger">*</span></label>
                    <input type="text" class="form-control" name="category_name[]" placeholder="Category" required>

                    <label for="criteria_details" class="form-label mt-3 ms-2">Criteria Details <span class="text-danger">*</span></label>
                    <textarea class="form-control" name="criteria_details[]" placeholder="Details" rows="2" required></textarea>
                </div>
                <div class="col-auto">
                    <button type="button" class="btn btn-danger remove-category-btn">-</button>
                </div>
            </div>
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
                            <button type="button" class="btn btn-primary add-main-criteria-btn" data-category-index="${categoryIndex}">+</button>
                        </div>
                    </div>
                </div>
                <div class="sub-criteria-container col-6" data-category-index="${categoryIndex}" data-main-criteria-index="0">
                    <div class="row sub-criteria-row">
                        <div class="col">
                            <label class="form-label">Sub-Criteria <span class="text-danger">*</span></label>
                            <input type="text" class="form-control" name="sub_criteria[${categoryIndex}][0][]" placeholder="Sub-Criteria" required>
                        </div>
                        <div class="col">
                            <label class="form-label">Percentage <span class="text-danger">*</span></label>
                            <input type="number" class="form-control" name="sub_criteria_percentage[${categoryIndex}][0][]" placeholder="Percentage" required>
                        </div>
                        <div class="col-auto">
                            <button type="button" class="btn btn-primary add-sub-criteria-btn">+</button>
                        </div>
                    </div>
                </div>
            </div>
        </div>
    `;
    categoriesContainer.insertAdjacentHTML('beforeend', newCategory);
});

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