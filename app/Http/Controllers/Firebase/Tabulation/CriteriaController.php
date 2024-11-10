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
    $categoryReference = $this->database->getReference("{$this->tablename}/{$criteriaId}/categories");

    foreach ($validatedData['category_name'] as $catIndex => $categoryName) {
        $categoryData = [
            'category_name' => $categoryName,
            'criteria_details' => $validatedData['criteria_details'][$catIndex],
        ];

        $categoryId = $categoryReference->push($categoryData)->getKey();

        // Create a separate reference for each category's main criteria
        $mainCriteriaReference = $this->database->getReference("{$this->tablename}/{$criteriaId}/categories/{$categoryId}/main_criteria");

        // Loop through main criteria for each category
        $mainCriteria = array_values($validatedData['main_criteria'][$catIndex] ?? []);
        foreach ($mainCriteria as $mainIndex => $mainCriteriaValue) {
            $mainCriteriaReference->push([
                'name' => $mainCriteriaValue,
                'percentage' => $validatedData['main_criteria_percentage'][$catIndex][$mainIndex] ?? null,
                'sub_criteria' => $this->getSubCriteriaData($validatedData, $catIndex, $mainIndex),
            ]);
        }
    }

    return redirect()->route('criteria-setup')->with('success', 'Criteria setup successfully created');
}

private function getSubCriteriaData($validatedData, $catIndex, $mainIndex)
{
    if (!isset($validatedData['sub_criteria'][$catIndex][$mainIndex]) || !is_array($validatedData['sub_criteria'][$catIndex][$mainIndex])) {
        return [];
    }

    $subCriteriaData = [];
    foreach ($validatedData['sub_criteria'][$catIndex][$mainIndex] as $subIndex => $subCriteria) {
        $subCriteriaData[] = [
            'name' => $subCriteria,
            'percentage' => $validatedData['sub_criteria_percentage'][$catIndex][$mainIndex][$subIndex] ?? null,
        ];
    }

    return array_values($subCriteriaData);
}

}