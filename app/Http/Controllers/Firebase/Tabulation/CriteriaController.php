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
    
    $criteriaList = [];
    
    // Check if there are any criterias
    if ($criterias) {
        foreach ($criterias as $criteriaId => $criteriaData) {
            // Verify that the required data exists
            if (!isset($criteriaData['ename']) || !isset($criteriaData['categories'])) {
                continue; // Skip this iteration if required data is missing
            }

            $criteriaList[$criteriaId] = [
                'event_name' => $criteriaData['ename'],
                'categories' => [],
            ];
            
            foreach ($criteriaData['categories'] as $categoryId => $categoryData) {
                // Verify category data exists
                if (!isset($categoryData['category_name']) || !isset($categoryData['criteria_details'])) {
                    continue;
                }

                $criteriaList[$criteriaId]['categories'][$categoryId] = [
                    'category_name' => $categoryData['category_name'],
                    'criteria_details' => $categoryData['criteria_details'],
                    'main_criteria' => [],
                    'sub_criteria' => [],
                ];
            
                if (isset($categoryData['main_criteria'])) {
                    foreach ($categoryData['main_criteria'] as $mainCriteriaId => $mainCriteriaData) {
                        // Verify main criteria data exists
                        if (!isset($mainCriteriaData['name']) || !isset($mainCriteriaData['percentage'])) {
                            continue;
                        }

                        $criteriaList[$criteriaId]['categories'][$categoryId]['main_criteria'][$mainCriteriaId] = [
                            'name' => $mainCriteriaData['name'],
                            'percentage' => $mainCriteriaData['percentage'],
                        ];
            
                        if (isset($mainCriteriaData['sub_criteria'])) {
                            foreach ($mainCriteriaData['sub_criteria'] as $subCriteriaId => $subCriteriaData) {
                                // Verify sub criteria data exists
                                if (!isset($subCriteriaData['name']) || !isset($subCriteriaData['percentage'])) {
                                    continue;
                                }

                                $criteriaList[$criteriaId]['categories'][$categoryId]['sub_criteria'][$mainCriteriaId][$subCriteriaId] = [
                                    'name' => $subCriteriaData['name'],
                                    'percentage' => $subCriteriaData['percentage'],
                                ];
                            }
                        }
                    }
                }
            }
        }
    }

    return view('firebase.tabulation.criteria.criteria-list', compact('criteriaList'));
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
    
        return redirect()->route('criteria-list')->with('success', 'Criteria setup successfully created');
    }

    public function edit($id)
    {
        $key = $id;
        $editdata = $this->database->getReference($this->tablename)->getChild($key)->getValue();
        if ($editdata) {
            $criteria = [
                'id' => $key,
                'event_name' => $editdata['ename'],
                'categories' => [],
            ];
    
            foreach ($editdata['categories'] as $categoryId => $categoryData) {
                $criteria['categories'][] = [
                    'id' => $categoryId,
                    'category_name' => $categoryData['category_name'],
                    'criteria_details' => $categoryData['criteria_details'],
                    'main_criteria' => [],
                ];
    
                if (isset($categoryData['main_criteria'])) {
                    foreach ($categoryData['main_criteria'] as $mainCriteriaId => $mainCriteriaData) {
                        $mainCriteria = [
                            'id' => $mainCriteriaId,
                            'name' => $mainCriteriaData['name'],
                            'percentage' => $mainCriteriaData['percentage'],
                            'sub_criteria' => [],
                        ];
    
                        if (isset($mainCriteriaData['sub_criteria'])) {
                            foreach ($mainCriteriaData['sub_criteria'] as $subCriteriaId => $subCriteriaData) {
                                $mainCriteria['sub_criteria'][] = [
                                    'id' => $subCriteriaId,
                                    'name' => $subCriteriaData['name'],
                                    'percentage' => $subCriteriaData['percentage'],
                                ];
                            }
                        }
                        
                        $criteria['categories'][count($criteria['categories'])-1]['main_criteria'][] = $mainCriteria;
                    }
                }
            }
    
            // Fetch events from Firebase
            $events = $this->database->getReference('events')->getValue();
    
            return view('firebase.tabulation.criteria.criteria-edit', compact('criteria', 'events'));
        } else {
            return redirect('firebase.tabulation.criteria.criteria-edit')->with('status', 'Criteria ID not Found');
        }
    }
    
    public function update(Request $request, $id)
    {
        $key = $id;
        
        $updateData = [
            'ename' => $request->input('event_name'),
            'categories' => []
        ];
    
        if ($request->has('categories')) {
            foreach ($request->input('categories') as $categoryIndex => $category) {
                $categoryId = $category['id'] ?? uniqid('cat_');
                $updateData['categories'][$categoryId] = [
                    'category_name' => $category['category_name'],
                    'criteria_details' => $category['criteria_details'],
                    'main_criteria' => []
                ];
    
                if (isset($category['main_criteria'])) {
                    foreach ($category['main_criteria'] as $mainIndex => $mainCriteria) {
                        $mainCriteriaId = $mainCriteria['id'] ?? uniqid('main_');
                        $updateData['categories'][$categoryId]['main_criteria'][$mainCriteriaId] = [
                            'name' => $mainCriteria['name'],
                            'percentage' => $mainCriteria['percentage'],
                            'sub_criteria' => []
                        ];
    
                        if (isset($mainCriteria['sub_criteria'])) {
                            foreach ($mainCriteria['sub_criteria'] as $subIndex => $subCriteria) {
                                $subCriteriaId = $subCriteria['id'] ?? uniqid('sub_');
                                $updateData['categories'][$categoryId]['main_criteria'][$mainCriteriaId]['sub_criteria'][$subCriteriaId] = [
                                    'name' => $subCriteria['name'],
                                    'percentage' => $subCriteria['percentage']
                                ];
                            }
                        }
                    }
                }
            }
        }
    
        try {
            $this->database->getReference($this->tablename)->getChild($key)->set($updateData);
            return redirect()->route('criteria-list')->with('success', 'Criteria updated successfully');
        } catch (\Exception $e) {
            return redirect()->route('criteria-list')->with('error', 'Failed to update criteria: ' . $e->getMessage());
        }
    }

}