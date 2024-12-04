@extends('firebase.layouts.admin-app')

@section('content')
<div class="p-5">
    <div class="event-setup-form-header justify-content-center">
        <div class="event-icon-container align-items-center">
            <i class="ri-group-line"></i>
            <span>Criteria Edit</span>
        </div>
    </div>

    <form action="{{ route('admin.criteria.update', $criteria['id']) }}" method="POST" id="criteriaForm">
        @csrf
        @method('PUT')
        <input type="hidden" name="criteria_id" value="{{ $criteria['id'] }}">

        <!-- Event Selection -->
        <div class="contestant-form-row row">
            <div class="col">
                <label for="event" class="form-label mt-1 ms-2">Select Event <span class="text-danger">*</span></label>
                <select name="event_name" id="event" class="form-control" required>
                    <option value="" disabled>Select Event</option>
                    @foreach($events as $eventId => $event)
                        @if(isset($event['ename']))
                            <option value="{{ $event['ename'] }}" {{ $criteria['event_name'] == $event['ename'] ? 'selected' : '' }}>
                                {{ $event['ename'] }}
                            </option>
                        @endif
                    @endforeach
                </select>
            </div>
        </div>

        <!-- Categories Container -->
        <div id="categories-container">
            @foreach($criteria['categories'] as $categoryIndex => $category)
            <div class="category-group mb-3 mt-3">
                <!-- Category Input -->
                <div class="row">
                    <div class="col">
                        <input type="hidden" name="categories[{{$categoryIndex}}][id]" value="{{ $category['id'] }}">
                        <label class="form-label mt-1 ms-2">Category <span class="text-danger">*</span></label>
                        <input type="text" class="form-control" name="categories[{{$categoryIndex}}][category_name]" value="{{ $category['category_name'] }}" required>

                        <label class="form-label mt-3 ms-2">Criteria Details <span class="text-danger">*</span></label>
                        <textarea class="form-control" name="categories[{{$categoryIndex}}][criteria_details]" rows="2" required>{{ $category['criteria_details'] }}</textarea>
                    </div>
                    <div class="col-auto">
                        <button type="button" class="btn {{ $categoryIndex === 0 ? 'btn-primary add-category-btn' : 'btn-danger remove-category-btn' }}">
                            {{ $categoryIndex === 0 ? '+' : '-' }}
                        </button>
                    </div>
                </div>

                <!-- Main Criteria Section -->
                <div class="main-criteria-wrapper">
                    @foreach($category['main_criteria'] as $mainIndex => $mainCriteria)
                    <div class="row mb-2 mt-3">
                        <div class="main-criteria-group col-6">
                            <div class="row">
                                <input type="hidden" name="categories[{{$categoryIndex}}][main_criteria][{{$mainIndex}}][id]" value="{{ $mainCriteria['id'] }}">
                                <div class="col">
                                    <label class="form-label">Main Criteria <span class="text-danger">*</span></label>
                                    <input type="text" class="form-control" name="categories[{{$categoryIndex}}][main_criteria][{{$mainIndex}}][name]" value="{{ $mainCriteria['name'] }}" required>
                                </div>
                                <div class="col">
                                    <label class="form-label">Percentage <span class="text-danger">*</span></label>
                                    <input type="number" class="form-control" name="categories[{{$categoryIndex}}][main_criteria][{{$mainIndex}}][percentage]" value="{{ $mainCriteria['percentage'] }}" required>
                                </div>
                                <div class="col-auto">
                                    <button type="button" class="btn {{ $mainIndex === 0 ? 'btn-primary add-main-criteria-btn' : 'btn-danger remove-main-criteria-btn' }}" data-category-index="{{$categoryIndex}}">
                                        {{ $mainIndex === 0 ? '+' : '-' }}
                                    </button>
                                </div>
                            </div>
                        </div>

                        <!-- Sub Criteria Section -->
                        <div class="sub-criteria-container col-6" data-category-index="{{$categoryIndex}}" data-main-criteria-index="{{$mainIndex}}">
                            @foreach($mainCriteria['sub_criteria'] as $subIndex => $subCriteria)
                            <div class="row sub-criteria-row {{ $subIndex > 0 ? 'mt-2' : '' }}">
                                <input type="hidden" name="categories[{{$categoryIndex}}][main_criteria][{{$mainIndex}}][sub_criteria][{{$subIndex}}][id]" value="{{ $subCriteria['id'] }}">
                                <div class="col">
                                    <label class="form-label">Sub-Criteria <span class="text-danger">*</span></label>
                                    <input type="text" class="form-control" name="categories[{{$categoryIndex}}][main_criteria][{{$mainIndex}}][sub_criteria][{{$subIndex}}][name]" value="{{ $subCriteria['name'] }}" required>
                                </div>
                                <div class="col">
                                    <label class="form-label">Percentage <span class="text-danger">*</span></label>
                                    <input type="number" class="form-control" name="categories[{{$categoryIndex}}][main_criteria][{{$mainIndex}}][sub_criteria][{{$subIndex}}][percentage]" value="{{ $subCriteria['percentage'] }}" required>
                                </div>
                                <div class="col-auto">
                                    <button type="button" class="btn {{ $subIndex === 0 ? 'btn-primary add-sub-criteria-btn' : 'btn-danger remove-sub-criteria-btn' }}">
                                        {{ $subIndex === 0 ? '+' : '-' }}
                                    </button>
                                </div>
                            </div>
                            @endforeach
                        </div>
                    </div>
                    @endforeach
                </div>
            </div>
            @endforeach
        </div>

        <!-- Form Buttons -->
        <div class="form-group text-center">
            <button type="button" class="btn-cancel" onclick="window.location.href='{{ route('admin.criteria.list') }}'">Cancel</button>
            <button type="submit" class="btn-add">Update</button>
        </div>
    </form>
</div>

<!-- Include your existing JavaScript for dynamic additions -->
<script>
document.addEventListener('DOMContentLoaded', function() {
    // Add new category
    document.addEventListener('click', function(e) {
        if (e.target.classList.contains('add-category-btn')) {
            const categoriesContainer = document.getElementById('categories-container');
            const categoryIndex = categoriesContainer.querySelectorAll('.category-group').length;
            
            const newCategory = `
                <div class="category-group mb-3 mt-5">
                    <div class="row">
                        <div class="col">
                            <input type="hidden" name="categories[${categoryIndex}][id]" value="new_${Date.now()}">
                            <label class="form-label mt-1 ms-2">Category <span class="text-danger">*</span></label>
                            <input type="text" class="form-control" name="categories[${categoryIndex}][category_name]" placeholder="Category" required>

                            <label class="form-label mt-3 ms-2">Criteria Details <span class="text-danger">*</span></label>
                            <textarea class="form-control" name="categories[${categoryIndex}][criteria_details]" placeholder="Details" rows="2" required></textarea>
                        </div>
                        <div class="col-auto">
                            <button type="button" class="btn btn-danger remove-category-btn">-</button>
                        </div>
                    </div>
                    <div class="main-criteria-wrapper">
                        <div class="row mb-2 mt-3">
                            <div class="main-criteria-group col-6">
                                <div class="row">
                                    <input type="hidden" name="categories[${categoryIndex}][main_criteria][0][id]" value="new_main_${Date.now()}">
                                    <div class="col">
                                        <label class="form-label">Main Criteria <span class="text-danger">*</span></label>
                                        <input type="text" class="form-control" name="categories[${categoryIndex}][main_criteria][0][name]" placeholder="Main Criteria" required>
                                    </div>
                                    <div class="col">
                                        <label class="form-label">Percentage <span class="text-danger">*</span></label>
                                        <input type="number" class="form-control" name="categories[${categoryIndex}][main_criteria][0][percentage]" placeholder="Percentage" required>
                                    </div>
                                    <div class="col-auto">
                                        <button type="button" class="btn btn-primary add-main-criteria-btn" data-category-index="${categoryIndex}">+</button>
                                    </div>
                                </div>
                            </div>
                            <div class="sub-criteria-container col-6" data-category-index="${categoryIndex}" data-main-criteria-index="0">
                                <div class="row sub-criteria-row">
                                    <input type="hidden" name="categories[${categoryIndex}][main_criteria][0][sub_criteria][0][id]" value="new_sub_${Date.now()}">
                                    <div class="col">
                                        <label class="form-label">Sub-Criteria <span class="text-danger">*</span></label>
                                        <input type="text" class="form-control" name="categories[${categoryIndex}][main_criteria][0][sub_criteria][0][name]" placeholder="Sub-Criteria" required>
                                    </div>
                                    <div class="col">
                                        <label class="form-label">Percentage <span class="text-danger">*</span></label>
                                        <input type="number" class="form-control" name="categories[${categoryIndex}][main_criteria][0][sub_criteria][0][percentage]" placeholder="Percentage" required>
                                    </div>
                                    <div class="col-auto">
                                        <button type="button" class="btn btn-primary add-sub-criteria-btn">+</button>
                                    </div>
                                </div>
                            </div>
                        </div>
                    </div>
                </div>
            `;
            categoriesContainer.insertAdjacentHTML('beforeend', newCategory);
        }
    });

    // Add main criteria
    document.addEventListener('click', function(e) {
        if (e.target.classList.contains('add-main-criteria-btn')) {
            const categoryGroup = e.target.closest('.category-group');
            const categoryIndex = Array.from(document.querySelectorAll('.category-group')).indexOf(categoryGroup);
            const mainCriteriaWrapper = categoryGroup.querySelector('.main-criteria-wrapper');
            const mainCriteriaIndex = mainCriteriaWrapper.querySelectorAll('.main-criteria-group').length;
            
            const newMainCriteria = `
                <div class="row mb-2 mt-3">
                    <div class="main-criteria-group col-6">
                        <div class="row">
                            <input type="hidden" name="categories[${categoryIndex}][main_criteria][${mainCriteriaIndex}][id]" value="new_main_${Date.now()}">
                            <div class="col">
                                <label class="form-label">Main Criteria <span class="text-danger">*</span></label>
                                <input type="text" class="form-control" name="categories[${categoryIndex}][main_criteria][${mainCriteriaIndex}][name]" placeholder="Main Criteria" required>
                            </div>
                            <div class="col">
                                <label class="form-label">Percentage <span class="text-danger">*</span></label>
                                <input type="number" class="form-control" name="categories[${categoryIndex}][main_criteria][${mainCriteriaIndex}][percentage]" placeholder="Percentage" required>
                            </div>
                            <div class="col-auto">
                                <button type="button" class="btn btn-danger remove-main-criteria-btn">-</button>
                            </div>
                        </div>
                    </div>
                    <div class="sub-criteria-container col-6" data-category-index="${categoryIndex}" data-main-criteria-index="${mainCriteriaIndex}">
                        <div class="row sub-criteria-row">
                            <input type="hidden" name="categories[${categoryIndex}][main_criteria][${mainCriteriaIndex}][sub_criteria][0][id]" value="new_sub_${Date.now()}">
                            <div class="col">
                                <label class="form-label">Sub-Criteria <span class="text-danger">*</span></label>
                                <input type="text" class="form-control" name="categories[${categoryIndex}][main_criteria][${mainCriteriaIndex}][sub_criteria][0][name]" placeholder="Sub-Criteria" required>
                            </div>
                            <div class="col">
                                <label class="form-label">Percentage <span class="text-danger">*</span></label>
                                <input type="number" class="form-control" name="categories[${categoryIndex}][main_criteria][${mainCriteriaIndex}][sub_criteria][0][percentage]" placeholder="Percentage" required>
                            </div>
                            <div class="col-auto">
                                <button type="button" class="btn btn-primary add-sub-criteria-btn">+</button>
                            </div>
                        </div>
                    </div>
                </div>
            `;
            mainCriteriaWrapper.insertAdjacentHTML('beforeend', newMainCriteria);
        }
    });

    // Add sub-criteria
    document.addEventListener('click', function(e) {
        if (e.target.classList.contains('add-sub-criteria-btn')) {
            const subCriteriaContainer = e.target.closest('.sub-criteria-container');
            const categoryIndex = subCriteriaContainer.getAttribute('data-category-index');
            const mainCriteriaIndex = subCriteriaContainer.getAttribute('data-main-criteria-index');
            const subCriteriaIndex = subCriteriaContainer.querySelectorAll('.sub-criteria-row').length;
            
            const newSubCriteria = `
                <div class="row sub-criteria-row mt-2">
                    <input type="hidden" name="categories[${categoryIndex}][main_criteria][${mainCriteriaIndex}][sub_criteria][${subCriteriaIndex}][id]" value="new_sub_${Date.now()}">
                    <div class="col">
                        <label class="form-label">Sub-Criteria <span class="text-danger">*</span></label>
                        <input type="text" class="form-control" name="categories[${categoryIndex}][main_criteria][${mainCriteriaIndex}][sub_criteria][${subCriteriaIndex}][name]" placeholder="Sub-Criteria" required>
                    </div>
                    <div class="col">
                        <label class="form-label">Percentage <span class="text-danger">*</span></label>
                        <input type="number" class="form-control" name="categories[${categoryIndex}][main_criteria][${mainCriteriaIndex}][sub_criteria][${subCriteriaIndex}][percentage]" placeholder="Percentage" required>
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
});
</script>

@endsection