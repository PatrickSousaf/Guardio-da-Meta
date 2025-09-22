<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use App\Models\Course;

class CourseMenuController extends Controller
{
    public function index()
    {
        $dynamicMenu = Course::all()->groupBy('year');
        return view('admin.menu_cursos', compact('dynamicMenu'));
    }
}
