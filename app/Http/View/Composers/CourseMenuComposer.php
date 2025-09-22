<?php

namespace App\Http\View\Composers;

use Illuminate\View\View;
use App\Models\Course;

class CourseMenuComposer
{
    public function compose(View $view)
    {
        $dynamicMenu = [];

        foreach (Course::all()->groupBy('year') as $year => $courses) {
            $submenuCourses = [];
            foreach ($courses as $course) {
                $periodSubmenu = collect($course->periods ?? [])->map(fn($period) => [
                    'text' => $period,
                    'url'  => '#'
                ])->toArray();

                $submenuCourses[] = [
                    'text' => $course->name,
                    'url'  => route('courses.edit', $course->id),
                    'icon' => 'fas fa-book',
                    'submenu' => $periodSubmenu,
                ];
            }

            $dynamicMenu[] = [
                'text'    => $year . 'º Ano',
                'icon'    => 'fas fa-layer-group',
                'submenu' => $submenuCourses,
            ];
        }

        $view->with('dynamicMenu', $dynamicMenu);
    }
}
