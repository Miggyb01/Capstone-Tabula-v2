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
    
        $rootReference = $this->database->getReference($this->tablename);
        $criteriaReference = $rootReference->push([
            'ename' => $validatedData['event_name'],
        ]);
    
        $criteriaId = $criteriaReference->getKey();
        
        // For each category
        foreach ($validatedData['category_name'] as $catIndex => $categoryName) {
            $categoryReference = $this->database->getReference("{$this->tablename}/{$criteriaId}/categories");
            
            // Create category entry
            $categoryData = [
                'category_name' => $categoryName,
                'criteria_details' => $validatedData['criteria_details'][$catIndex],
            ];
            
            $categoryId = $categoryReference->push($categoryData)->getKey();
            
            // Get main criteria for this category
            $mainCriteriaArray = $validatedData['main_criteria'][$catIndex] ?? [];
            $mainCriteriaPercentages = $validatedData['main_criteria_percentage'][$catIndex] ?? [];
            
            // Reference for main criteria
            $mainCriteriaReference = $this->database->getReference(
                "{$this->tablename}/{$criteriaId}/categories/{$categoryId}/main_criteria"
            );
    
            // Process each main criteria
            foreach ($mainCriteriaArray as $mainIndex => $mainCriteriaName) {
                if (empty($mainCriteriaName)) continue;
    
                $mainCriteriaData = [
                    'name' => $mainCriteriaName,
                    'percentage' => $mainCriteriaPercentages[$mainIndex] ?? 0,
                ];
    
                // Handle sub-criteria
                if (isset($validatedData['sub_criteria'][$catIndex][$mainIndex]) && 
                    is_array($validatedData['sub_criteria'][$catIndex][$mainIndex])) {
                    
                    $subCriteriaArray = $validatedData['sub_criteria'][$catIndex][$mainIndex];
                    $subCriteriaPercentages = $validatedData['sub_criteria_percentage'][$catIndex][$mainIndex] ?? [];
                    
                    $subCriteria = [];
                    foreach ($subCriteriaArray as $subIndex => $subCriteriaName) {
                        if (!empty($subCriteriaName)) {
                            $subCriteria[] = [
                                'name' => $subCriteriaName,
                                'percentage' => $subCriteriaPercentages[$subIndex] ?? 0
                            ];
                        }
                    }
                    
                    if (!empty($subCriteria)) {
                        $mainCriteriaData['sub_criteria'] = $subCriteria;
                    }
                }
    
                // Push main criteria with its sub-criteria
                $mainCriteriaReference->push($mainCriteriaData);
            }
        }
    
        return redirect()->route('criteria-setup')->with('success', 'Criteria setup successfully created');
    }
}