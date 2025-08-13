<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;

class LauncherController extends Controller
{
    public function index()
    {
        try {

            $launchers = DB::table('launchers')
                ->where('idCountry', 1)
                ->where('is_active', true)
                ->orderBy(DB::raw('COALESCE(`order`, 0)'))
                ->orderBy('label')
                ->get()
                ->map(function ($item) {
                    return [
                        'label' => $item->label,
                        'icone' => $item->icone,
                        'link' => $item->link,
                        'category' => $this->normalizeCategory($item->category ?? 'other'),
                        'order' => $item->order ?? 0,
                    ];
                });

            $categories = [
                'pedagogie' => ['name' => 'PÉDAGOGIE', 'items' => []],
                'administration' => ['name' => 'ADMINISTRATION', 'items' => []],
                'logistique' => ['name' => 'LOGISTIQUE', 'items' => []],
                'analytics' => ['name' => 'ANALYTICS & ÉVALUATION', 'items' => []],
                'other' => ['name' => 'AUTRES', 'items' => []]
            ];

            foreach ($launchers as $launcher) {
                $categoryKey = $this->getCategoryKey($launcher['category']);
                $categories[$categoryKey]['items'][] = $launcher;
            }

            $filteredCategories = array_values(array_filter($categories, function ($category) {
                return !empty($category['items']);
            }));

            return response()->json([
                'status' => 'success',
                'data' => $filteredCategories
            ]);
        } catch (\Exception $e) {
            return response()->json([
                'status' => 'error',
                'message' => 'Une erreur est survenue lors de la récupération des applications',
                'error' => $e->getMessage()
            ], 500);
        }
    }

    private function normalizeCategory($category)
    {

        $category = strtolower(trim($category));

        $mapping = [
            'pédagogie' => 'pedagogie',
            'admin' => 'administration',
            'administratif' => 'administration',
            'log' => 'logistique',
            'analytic' => 'analytics',
            'évaluation' => 'analytics',
        ];

        return $mapping[$category] ?? $category;
    }

    private function getCategoryKey($category)
    {
        $category = strtolower(trim($category));

        if (in_array($category, ['pedagogie', 'administration', 'logistique', 'analytics'])) {
            return $category;
        }

        return 'other';
    }
}
