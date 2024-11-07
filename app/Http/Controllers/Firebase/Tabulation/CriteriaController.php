<?php

namespace App\Http\Controllers\Firebase\Tabulation;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;
use Kreait\Firebase\Contract\Database;
use Illuminate\Support\Facades\Log;
use Illuminate\Support\Facades\Validator;



class CriteriaController extends Controller
{
    protected $database, $tablename;

    public function __construct(Database $database)
    {
        $this->database = $database;
        $this->tablename = 'criterias';
    }

    public function list()
    {
        // Fetching data from Firebase
        $criterias = $this->database->getReference($this->tablename)->getValue();
        return view('firebase.tabulation.criteria.criteria-list', compact('criterias'));
    }

    public function create()
    {
        // Fetching events from Firebase
        $events = $this->database->getReference('events')->getValue();
        return view('firebase.tabulation.criteria.criteria-setup', compact('events'));
    }


    public function store(Request $request)
    {
        $validatedData = $request->validate([
            'event_name' => 'required',
            'category_name' => 'required|array',
            'criteria_details' => 'required|array',
            'main_criteria' => 'required|array',
            'main_criteria_percentage' => 'required|array',
            'sub_criteria' => 'sometimes|array',
            'sub_criteria_percentage' => 'sometimes|array',
        ]);

        // Initialize the root structure with event_name at the root level
        $rootData = [
            'ename' => $validatedData['event_name'],
        ];

        // Create the root reference for criteria
        $rootReference = $this->database->getReference($this->tablename);

        // Push ename under criteria at the root level
        $criteriaReference = $rootReference->push($rootData);
        $criteriaId = $criteriaReference->getKey();

        // Process each category along with its main and sub-criteria
        foreach ($validatedData['category_name'] as $catIndex => $categoryName) {
            $categoryData = [
                'category_name' => $categoryName,
                'criteria_details' => $validatedData['criteria_details'][$catIndex],
            ];

            // Add category under the newly created criteria node
            $categoryReference = $this->database->getReference("{$this->tablename}/{$criteriaId}/categories");
            $categoryId = $categoryReference->push($categoryData)->getKey();

            // Process each main criteria for the category
            foreach ($validatedData['main_criteria'] as $index => $mainCriteria) {
                $subCriteriaData = [];

                // Add sub-criteria under each main criteria
                if (isset($validatedData['sub_criteria'][$index]) && is_array($validatedData['sub_criteria'][$index])) {
                    foreach ($validatedData['sub_criteria'][$index] as $subIndex => $subCriteria) {
                        $subCriteriaData[] = [
                            'name' => $subCriteria,
                            'percentage' => $validatedData['sub_criteria_percentage'][$index][$subIndex] ?? null,
                        ];
                    }

                    // Add main criteria with sub-criteria under the current category
                    $mainCriteriaReference = $this->database->getReference("{$this->tablename}/{$criteriaId}/categories/{$categoryId}/main_criteria");
                    $mainCriteriaReference->push([
                        'name' => $mainCriteria,
                        'percentage' => $validatedData['main_criteria_percentage'][$index],
                        'sub_criteria' => array_values($subCriteriaData), // Ensures correct indexing
                    ]);
                } else {
                    // Add main criteria without sub-criteria under the current category
                    $mainCriteriaReference = $this->database->getReference("{$this->tablename}/{$criteriaId}/categories/{$categoryId}/main_criteria");
                    $mainCriteriaReference->push([
                        'name' => $mainCriteria,
                        'percentage' => $validatedData['main_criteria_percentage'][$index],
                    ]);
                }
            }
        }

        return redirect()->route('criteria-setup')->with('success', 'Criteria setup successfully created');
    }

    
    

}

