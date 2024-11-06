@extends('firebase.app')

@section('content')

<div class="p-5">
    <div class="event-setup-form-header justify-content-center">
        <div class="event-icon-container align-items-center">
            <i class="ri-group-line"></i>
            <span>Criteria Setup</span>
        </div>
    </div>

    <form action="{{ route('criteria-list') }}" method="POST" id="criteriaForm">
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
            </div>
        </div>

        <!-- Categories Container -->
        <div id="categories-container">
            <div class="row mb-2 mt-3">
                <div class="main-criteria-group col-6">
                    <div class="row">
                        <div class="col">
                            <label class="form-label">Main Criteria <span class="text-danger">*</span></label>
                            <input type="text" class="form-control" name="main_criteria[]" placeholder="Main Criteria" required>
                        </div>
                        <div class="col">
                            <label class="form-label">Percentage <span class="text-danger">*</span></label>
                            <input type="number" class="form-control" name="main_criteria_percentage[]" placeholder="Percentage" required>
                        </div>
                        <div class="col-auto">
                            <button type="button" class="btn btn-primary add-main-criteria-btn">+</button>
                        </div>
                    </div>
                </div>
            </div>
        </div>

        <!-- Form Buttons -->
        <div class="form-group text-center">
            <button type="button" class="btn-cancel">Cancel</button>
            <button type="submit" class="btn-add">Add</button>
        </div>
    </form>

</div>

<script>
    document.querySelector('.add-category-btn').addEventListener('click', function () {
        const categoriesContainer = document.getElementById('categories-container');
        const newCategory = `
            <div class="category-group mb-3">
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
            </div>
        `;

        categoriesContainer.insertAdjacentHTML('beforeend', newCategory);
    });

    document.addEventListener('click', function (e) {
        if (e.target.classList.contains('add-main-criteria-btn')) {
            // Locate the main criteria container
            const mainCriteriaContainer = e.target.closest('.main-criteria-group').parentNode;

            // Create a unique index for new main criteria and sub-criteria entries
            const mainCriteriaIndex = mainCriteriaContainer.querySelectorAll('.main-criteria-group').length;

            // Define new HTML structure for main criteria and sub-criteria
            const newMainCriteria = `
                <div class="row mb-2 mt-3">
                    <div class="main-criteria-group col-6">
                        <div class="row">
                            <div class="col">
                                <label class="form-label">Main Criteria <span class="text-danger">*</span></label>
                                <input type="text" class="form-control" name="main_criteria[]" placeholder="Main-Criteria" required>
                                <small class="text-danger d-none main-criteria-error" style="margin-top: 2px; margin-bottom: 1px;"></small>
                            </div>
                            <div class="col">
                                <label class="form-label">Percentage <span class="text-danger">*</span></label>
                                                               <input type="number" class="form-control" name="main_criteria_percentage[]" placeholder="Percentage" required>
                                                           </div>
                                                           <div class="col-auto">
                                                               <button type="button" class="btn btn-primary add-sub-criteria-btn">+</button>
                                                           </div>
                                                       </div>
                                                   </div>
                                               </div>
                                           `;
                               
                                           // Insert the new main criteria into the main criteria container
                                           mainCriteriaContainer.insertAdjacentHTML('beforeend', newMainCriteria);
                                       }
                               
                                       // Add sub-criteria when the sub-criteria button is clicked
                                       if (e.target.classList.contains('add-sub-criteria-btn')) {
                                           const subCriteriaContainer = e.target.closest('.main-criteria-group');
                                           const mainCriteriaIndex = subCriteriaContainer.getAttribute('data-index');
                               
                                           // Create new sub-criteria HTML with incremented index
                                           const newSubCriteria = `
                                               <div class="row sub-criteria-row mt-2">
                                                   <div class="col">
                                                       <label class="form-label">Sub-Criteria <span class="text-danger">*</span></label>
                                                       <input type="text" class="form-control" name="sub_criteria[${mainCriteriaIndex}][]" placeholder="Sub-Criteria" required>
                                                   </div>
                                                   <div class="col">
                                                       <label class="form-label">Percentage <span class="text-danger">*</span></label>
                                                       <input type="number" class="form-control" name="sub_criteria_percentage[${mainCriteriaIndex}][]" placeholder="Percentage" required>
                                                   </div>
                                                   <div class="col-auto">
                                                       <button type="button" class="btn btn-danger remove-sub-criteria-btn">-</button>
                                                   </div>
                                               </div>
                                           `;
                               
                                           // Insert the new sub-criteria into the sub-criteria container
                                           subCriteriaContainer.insertAdjacentHTML('beforeend', newSubCriteria);
                                       }
                               
                                       // Remove sub-criteria row when remove button is clicked
                                       if (e.target.classList.contains('remove-sub-criteria-btn')) {
                                           e.target.closest('.sub-criteria-row').remove();
                                       }
                                   });
                               </script>
                               
                               @endsection