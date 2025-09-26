<?php

namespace App\Http\Controllers;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;
use App\Models\Course;

class CourseController extends Controller
{
    public function index()
    {
        $courses = Course::all()->groupBy('year'); // agrupa por ano
        return view('admin.courses.index', compact('courses'));
    }

    public function create()
    {
        return view('admin.courses.create');
    }

    public function store(Request $request)
    {
        $data = $request->validate([
            'name' => 'required|string|max:255',
            'year' => 'required|integer|min:1|max:3',
            'periods' => 'nullable|array',
            'periods.*' => 'string|max:255',
        ]);

        Course::create($data);
        return redirect()->route('admin.courses.index')->with('success', 'Curso criado!');
    }

    public function edit(Course $course)
    {
        return view('admin.courses.edit', compact('course'));
    }

    public function update(Request $request, Course $course)
    {
        $data = $request->validate([
            'name' => 'required|string|max:255',
            'year' => 'required|integer|min:1|max:3',
            'periods' => 'nullable|array',
            'periods.*' => 'string|max:255',
        ]);

        $course->update($data);
        return redirect()->route('admin.courses.index')->with('success', 'Curso atualizado!');
    }
}
