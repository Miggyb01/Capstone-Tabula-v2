<?php

namespace App\Http\Controllers\Firebase\Organizer;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;
use Kreait\Firebase\Contract\Database;
use Illuminate\Support\Facades\Session;

class OrganizerCriteriaController extends Controller
{
    protected $database;

    public function __construct(Database $database)
    {
        $this->database = $database;
    }

    protected function getOrganizerBasePath()
    {
        $organizerId = Session::get('user.id');
        return "user_organizer/{$organizerId}/user_data";
    }

    public function list(Request $request)
    {
        $organizerPath = $this->getOrganizerBasePath();
        
        // Fetch criteria from organizer's specific folder
        $criterias = $this->database->getReference("{$organizerPath}/criterias")->getValue() ?? [];
        
        $criteriaList = [];
        
        if ($criterias) {
            foreach ($criterias as $criteriaId => $criteriaData) {
                if (!isset($criteriaData['ename']) || !isset($criteriaData['categories'])) {
                    continue;
                }

                $criteriaList[$criteriaId] = [
                    'id' => $criteriaId,
                    'event_name' => $criteriaData['ename'],
                    'categories' => [],
                ];
                
                foreach ($criteriaData['categories'] as $categoryId => $categoryData) {
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
                        $this->processMainCriteria($categoryData['main_criteria'], $criteriaList[$criteriaId]['categories'][$categoryId]);
                    }
                }
            }
        }

        // Search and sort functionality
        $criteriaCollection = collect($criteriaList);

        if ($request->has('search')) {
            $searchTerm = strtolower($request->search);
            $criteriaCollection = $criteriaCollection->filter(function($criteria) use ($searchTerm) {
                return str_contains(strtolower($criteria['event_name']), $searchTerm);
            });
        }

        $sortBy = $request->get('sort', 'newest');
        if ($sortBy === 'newest') {
            $criteriaCollection = $criteriaCollection->sortByDesc('id');
        } else {
            $criteriaCollection = $criteriaCollection->sortBy('id');
        }

        $criteriaList = $criteriaCollection->all();

        return view('firebase.organizer.criteria.organizer-criteria-list', compact('criteriaList'));
    }

    private function processMainCriteria($mainCriteriaData, &$categoryData)
    {
        foreach ($mainCriteriaData as $mainCriteriaId => $mainCriteria) {
            if (!isset($mainCriteria['name']) || !isset($mainCriteria['percentage'])) {
                continue;
            }

            $mainCriteriaInfo = [
                'name' => $mainCriteria['name'],
                'percentage' => $mainCriteria['percentage'],
            ];

            if (isset($mainCriteria['sub_criteria'])) {
                foreach ($mainCriteria['sub_criteria'] as $subId => $sub) {
                    if (isset($sub['name']) && isset($sub['percentage'])) {
                        $categoryData['sub_criteria'][$mainCriteriaId][$subId] = [
                            'name' => $sub['name'],
                            'percentage' => $sub['percentage'],
                        ];
                    }
                }
            }

            $categoryData['main_criteria'][$mainCriteriaId] = $mainCriteriaInfo;
        }
    }

    public function create()
    {
        $organizerPath = $this->getOrganizerBasePath();
        $events = $this->database->getReference("{$organizerPath}/events")->getValue() ?? [];
        return view('firebase.organizer.criteria.organizer-criteria-setup', compact('events'));
    }

    public function store(Request $request)
    {
        $request->validate([
            'event_name' => 'required',
            'category_name' => 'required|array',
            'criteria_details' => 'required|array',
            'main_criteria' => 'required|array',
            'main_criteria_percentage' => 'required|array',
            'sub_criteria' => 'sometimes|array',
            'sub_criteria_percentage' => 'sometimes|array',
        ]);

        $organizerPath = $this->getOrganizerBasePath();
        
        $criteriaData = [
            'ename' => $request->event_name,
            'created_at' => ['.sv' => 'timestamp'],
            'organizer_id' => Session::get('user.id')
        ];

        // Store in organizer's folder
        $criteriaRef = $this->database->getReference("{$organizerPath}/criterias")->push($criteriaData);
        $criteriaId = $criteriaRef->getKey();

        // Process categories and criteria
        foreach ($request->category_name as $catIndex => $categoryName) {
            $categoryRef = $this->database->getReference("{$organizerPath}/criterias/{$criteriaId}/categories")
                ->push([
                    'category_name' => $categoryName,
                    'criteria_details' => $request->criteria_details[$catIndex],
                ]);

            $categoryId = $categoryRef->getKey();
            
            $this->processMainCriteriaStore(
                $request,
                $catIndex,
                $organizerPath,
                $criteriaId,
                $categoryId
            );
        }

        // Also store in main criteria collection for admin access
        $this->database->getReference("criterias/{$criteriaId}")
            ->set(array_merge(
                $criteriaData,
                [
                    'organizer_reference' => "{$organizerPath}/criterias/{$criteriaId}",
                    'categories' => $this->database->getReference("{$organizerPath}/criterias/{$criteriaId}/categories")->getValue()
                ]
            ));

        return redirect()->route('organizer.criteria.list')
            ->with('success', 'Criteria setup successfully created');
    }

    private function processMainCriteriaStore($request, $catIndex, $organizerPath, $criteriaId, $categoryId)
    {
        $mainCriteriaArray = $request->main_criteria[$catIndex] ?? [];
        $mainCriteriaPercentages = $request->main_criteria_percentage[$catIndex] ?? [];
        
        foreach ($mainCriteriaArray as $mainIndex => $mainCriteriaName) {
            if (empty($mainCriteriaName)) continue;

            $mainCriteriaData = [
                'name' => $mainCriteriaName,
                'percentage' => $mainCriteriaPercentages[$mainIndex] ?? 0,
            ];

            if (isset($request->sub_criteria[$catIndex][$mainIndex])) {
                $this->processSubCriteriaStore(
                    $request,
                    $catIndex,
                    $mainIndex,
                    $mainCriteriaData
                );
            }

            $this->database->getReference(
                "{$organizerPath}/criterias/{$criteriaId}/categories/{$categoryId}/main_criteria"
            )->push($mainCriteriaData);
        }
    }

    private function processSubCriteriaStore($request, $catIndex, $mainIndex, &$mainCriteriaData)
    {
        $subCriteriaArray = $request->sub_criteria[$catIndex][$mainIndex];
        $subCriteriaPercentages = $request->sub_criteria_percentage[$catIndex][$mainIndex] ?? [];
        
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

    public function edit($id)
    {
        $organizerPath = $this->getOrganizerBasePath();
        $editdata = $this->database->getReference("{$organizerPath}/criterias/{$id}")->getValue();
        
        if ($editdata) {
            $criteria = $this->formatCriteriaForEdit($editdata, $id);
            $events = $this->database->getReference("{$organizerPath}/events")->getValue();
            return view('firebase.organizer.criteria.organizer-criteria-edit', compact('criteria', 'events'));
        }

        return redirect()->route('organizer.criteria.list')
            ->with('status', 'Criteria not found');
    }

    private function formatCriteriaForEdit($editdata, $id)
    {
        $criteria = [
            'id' => $id,
            'event_name' => $editdata['ename'],
            'categories' => []
        ];

        if (isset($editdata['categories'])) {
            foreach ($editdata['categories'] as $categoryId => $categoryData) {
                $category = [
                    'id' => $categoryId,
                    'category_name' => $categoryData['category_name'] ?? '',
                    'criteria_details' => $categoryData['criteria_details'] ?? '',
                    'main_criteria' => []
                ];

                if (isset($categoryData['main_criteria'])) {
                    $this->formatMainCriteriaForEdit($categoryData['main_criteria'], $category);
                }
                
                $criteria['categories'][] = $category;
            }
        }

        return $criteria;
    }

    private function formatMainCriteriaForEdit($mainCriteriaData, &$category)
    {
        foreach ($mainCriteriaData as $mainCriteriaId => $mainCriteria) {
            $mainCriteriaFormatted = [
                'id' => $mainCriteriaId,
                'name' => $mainCriteria['name'] ?? '',
                'percentage' => $mainCriteria['percentage'] ?? 0,
                'sub_criteria' => []
            ];

            if (isset($mainCriteria['sub_criteria'])) {
                foreach ($mainCriteria['sub_criteria'] as $subCriteriaId => $subCriteria) {
                    $mainCriteriaFormatted['sub_criteria'][] = [
                        'id' => $subCriteriaId,
                        'name' => $subCriteria['name'] ?? '',
                        'percentage' => $subCriteria['percentage'] ?? 0
                    ];
                }
            }
            
            $category['main_criteria'][] = $mainCriteriaFormatted;
        }
    }

    public function update(Request $request, $id)
    {
        try {
            $organizerPath = $this->getOrganizerBasePath();
            
            $updateData = [
                'ename' => $request->input('event_name'),
                'categories' => [],
                'updated_at' => ['.sv' => 'timestamp']
            ];

            if ($request->has('categories')) {
                foreach ($request->input('categories') as $category) {
                    $categoryId = $category['id'];
                    $categoryData = $this->formatCategoryForUpdate($category);
                    $updateData['categories'][$categoryId] = $categoryData;
                }
            }

            // Update in organizer's folder
            $organizerUpdate = $this->database->getReference("{$organizerPath}/criterias/{$id}")
                ->update($updateData);

            // Update in main criteria collection
            if ($organizerUpdate) {
                $this->database->getReference("criterias/{$id}")
                    ->update($updateData);
            }

            return redirect()->route('organizer.criteria.list')
                ->with('success', 'Criteria updated successfully');

        } catch (\Exception $e) {
            return redirect()->route('organizer.criteria.list')
                ->with('error', 'Error updating criteria: ' . $e->getMessage());
        }
    }

    private function formatCategoryForUpdate($category)
    {
        $categoryData = [
            'category_name' => $category['category_name'],
            'criteria_details' => $category['criteria_details'],
            'main_criteria' => []
        ];

        if (isset($category['main_criteria'])) {
            foreach ($category['main_criteria'] as $mainCriteria) {
                $mainCriteriaId = $mainCriteria['id'];
                $mainCriteriaData = [
                    'name' => $mainCriteria['name'],
                    'percentage' => $mainCriteria['percentage'],
                    'sub_criteria' => []
                ];

                if (isset($mainCriteria['sub_criteria'])) {
                    foreach ($mainCriteria['sub_criteria'] as $subCriteria) {
                        $subCriteriaId = $subCriteria['id'];
                        $mainCriteriaData['sub_criteria'][$subCriteriaId] = [
                            'name' => $subCriteria['name'],
                            'percentage' => $subCriteria['percentage']
                        ];
                    }
                }

                $categoryData['main_criteria'][$mainCriteriaId] = $mainCriteriaData;
            }
        }

        return $categoryData;
    }

    public function destroy($id)
    {
        try {
            $organizerPath = $this->getOrganizerBasePath();
            
            // Delete from organizer's folder
            $deleteFromOrganizer = $this->database->getReference("{$organizerPath}/criterias/{$id}")
                ->remove();

            // Delete from main criteria collection
            if ($deleteFromOrganizer) {
                $this->database->getReference("criterias/{$id}")->remove();
            }

            return redirect()->route('organizer.criteria.list')
                ->with('success', 'Criteria deleted successfully');
        } catch (\Exception $e) {
            return redirect()->route('organizer.criteria.list')
                ->with('error', 'Error deleting criteria: ' . $e->getMessage());
        }
    }
}