<?php
namespace App\Traits;

use App\Models\Project;

trait ProjectProfitLossTrait
{
    /**
     * Get Project Profit & Loss for a specific date range using Eloquent.
     *
     * @param  string  $fromDate
     * @param  string  $toDate
     * @return \Illuminate\Support\Collection
     */
    public function getProjectProfitLossData($fromDate, $toDate)
    {
        // Fetch projects with their total sales and total purchases
        $projects = Project::with(['sales' => function ($query) use ($fromDate, $toDate) {
            $query->when($fromDate && $toDate, function ($query) use ($fromDate, $toDate) {
                // If both fromDate and toDate are provided, use whereBetween
                $query->whereBetween('created_at', [$fromDate, $toDate]);
            })
            ->when(!$fromDate || !$toDate || $toDate == now()->format('Y-m-d'), function ($query) use ($toDate) {
                // If fromDate or toDate is not provided or toDate is today, apply orWhere with greater than condition
                $query->orWhere('created_at', '>', $toDate);
            });
        }, 'purchases' => function ($query) use ($fromDate, $toDate) {
            $query->when($fromDate && $toDate, function ($query) use ($fromDate, $toDate) {
                // If both fromDate and toDate are provided, use whereBetween
                $query->whereBetween('created_at', [$fromDate, $toDate]);
            })
            ->when(!$fromDate || !$toDate || $toDate == now()->format('Y-m-d'), function ($query) use ($toDate) {
                // If fromDate or toDate is not provided or toDate is today, apply orWhere with greater than condition
                $query->orWhere('created_at', '>', $toDate);
            });
        }])
        ->get()
        ->map(function ($project) {
            // Calculate total sales and total purchases for each project
            $totalSales = $project->sales->sum('total');
            $totalPurchases = $project->purchases->sum('total');
            
            // Calculate profit or loss
            $project->total_sales = $totalSales;
            $project->total_purchases = $totalPurchases;
            $project->profit_loss = $totalSales - $totalPurchases;
            
            return $project;
        });

        return $projects;
    }
}
