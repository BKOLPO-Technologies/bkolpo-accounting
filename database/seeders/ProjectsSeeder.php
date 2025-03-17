<?php

namespace Database\Seeders;

use App\Models\Project; // Ensure you import the Project model
use App\Models\ProjectItem; // Ensure you import the ProjectItem model
use Illuminate\Database\Seeder;

class ProjectsSeeder extends Seeder
{
    public function run()
    {
        // Loop through and create 3 projects with dynamic reference_no
        $projectsData = [
            [
                'project_name' => 'Project Titan',
                'project_location' => 'Location A',
                'project_coordinator' => 'John Doe',
                'client_id' => 1, // Assuming client with ID 1 exists
                'schedule_date' => '2025-06-01',
                'total_discount' => 500,
                'subtotal' => 10000,
                'transport_cost' => 200,
                'carrying_charge' => 150,
                'vat' => 1200,
                'tax' => 800,
                'grand_total' => 11650,
                'paid_amount' => 0,
                'status' => 'pending',
                'project_type' => 'Running',
                'description' => 'This is the description for Project Titan.',
                'terms_conditions' => 'Terms and conditions for Project Titan.',
            ],
            [
                'project_name' => 'Project Everest',
                'project_location' => 'Location B',
                'project_coordinator' => 'Jane Smith',
                'client_id' => 2, // Assuming client with ID 2 exists
                'schedule_date' => '2025-07-01',
                'total_discount' => 700,
                'subtotal' => 15000,
                'transport_cost' => 300,
                'carrying_charge' => 180,
                'vat' => 1800,
                'tax' => 1200,
                'grand_total' => 15320,
                'paid_amount' => 0,
                'status' => 'pending',
                'project_type' => 'Running',
                'description' => 'This is the description for Project Everest.',
                'terms_conditions' => 'Terms and conditions for Project Everest.',
            ],
            [
                'project_name' => 'Project Horizon',
                'project_location' => 'Location C',
                'project_coordinator' => 'Mark Lee',
                'client_id' => 3, // Assuming client with ID 3 exists
                'schedule_date' => '2025-08-01',
                'total_discount' => 1000,
                'subtotal' => 20000,
                'transport_cost' => 500,
                'carrying_charge' => 250,
                'vat' => 2400,
                'tax' => 1600,
                'grand_total' => 20650,
                'paid_amount' => 0,
                'status' => 'pending',
                'project_type' => 'Running',
                'description' => 'This is the description for Project Horizon.',
                'terms_conditions' => 'Terms and conditions for Project Horizon.',
            ]
        ];
        

        foreach ($projectsData as $projectData) {
            // Generate a dynamic reference number for each project
            $randomNumber = rand(100000, 999999);
            $fullDate = now()->format('dmy'); // Current date in 'dmy' format
            $reference_no = 'BCL-PR-'.$fullDate.'-'.$randomNumber;

            // Add the reference_no to the project data
            $projectData['reference_no'] = $reference_no;

            // Create or update the project
            $project = Project::updateOrCreate(
                ['reference_no' => $reference_no],
                $projectData
            );

            // Create project items for the created project
            $this->createProjectItems($project);
        }
    }

    /**
     * Create project items for the given project
     * 
     * @param Project $project
     */
    private function createProjectItems(Project $project)
    {
        $itemsData = [
            [
                'items' => 'Item 1',
                'order_unit' => 'Unit 1',
                'unit_price' => 100,
                'quantity' => 10,
                'subtotal' => 1000,
                'discount' => 100,
                'total' => 900,
                'project_id' => $project->id,
            ],
            [
                'items' => 'Item 2',
                'order_unit' => 'Unit 2',
                'unit_price' => 200,
                'quantity' => 5,
                'subtotal' => 1000,
                'discount' => 200,
                'total' => 800,
                'project_id' => $project->id,
            ],
            [
                'items' => 'Item 3',
                'order_unit' => 'Unit 3',
                'unit_price' => 300,
                'quantity' => 3,
                'subtotal' => 900,
                'discount' => 150,
                'total' => 750,
                'project_id' => $project->id,
            ]
        ];

        foreach ($itemsData as $itemData) {
            ProjectItem::updateOrCreate(
                ['items' => $itemData['items'], 'project_id' => $project->id],
                $itemData
            );
        }
    }
}
