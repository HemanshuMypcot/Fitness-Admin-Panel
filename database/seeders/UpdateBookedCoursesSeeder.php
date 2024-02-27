<?php

namespace Database\Seeders;

use App\Models\BookedCourse;
use Illuminate\Database\Seeder;

class UpdateBookedCoursesSeeder extends Seeder
{
    /**
     * Run the database seeds.
     *
     * @return void
     */
    public function run()
    {
        $bookedCourses = BookedCourse::all();
        foreach ($bookedCourses as $bookedData) {
            $data = [
                'course_start_date' => $bookedData['details']['start_date'],
                'course_end_date' => $bookedData['details']['end_date']
            ];
            $bookedData->update($data);
        }
    }
}
