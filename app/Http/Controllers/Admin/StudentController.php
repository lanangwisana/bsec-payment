<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use App\Models\Student;
use Illuminate\Http\Request;

class StudentController extends Controller
{
    /**
     * Display a listing of the resource.
     */
    public function index(Request $request)
    {
        $query = Student::latest();

        if ($request->filled('search')) {
            $search = $request->search;
            $query->where('name', 'like', "%$search%")
                  ->orWhere('parent_name', 'like', "%$search%");
        }

        $students = $query->paginate(15);
        return view('admin.students.index', compact('students'));
    }

    /**
     * Show the form for creating a new resource.
     */
    public function create()
    {
        return view('admin.students.create');
    }

    /**
     * Store a newly created resource in storage.
     */
    public function store(Request $request)
    {
        $validated = $request->validate([
            'name' => 'required|string|max:255',
            'grade' => 'required|string',
            'classroom' => 'nullable|string',
            'school' => 'required|string',
            'program' => 'required|string',
            'registration_date' => 'required|date',
            'monthly_fee' => 'required|numeric|min:0',
            'parent_name' => 'required|string',
            'parent_phone' => 'required|string',
            'parent_email' => 'nullable|email',
            'address' => 'nullable|string',
            'birth_place_date' => 'nullable|string',
        ]);

        $student = Student::create($validated);

        return redirect()->route('admin.students.index')->with('success', 'Siswa berhasil didaftarkan. Tagihan otomatis akan dibuat setiap tanggal ' . \Carbon\Carbon::parse($student->registration_date)->format('d') . '.');
    }

    /**
     * Display the specified resource.
     */
    public function show(Student $student)
    {
        $student->load('invoices');
        return view('admin.students.show', compact('student'));
    }

    /**
     * Show the form for editing the specified resource.
     */
    public function edit(Student $student)
    {
        return view('admin.students.edit', compact('student'));
    }

    /**
     * Update the specified resource in storage.
     */
    public function update(Request $request, Student $student)
    {
        $validated = $request->validate([
            'name' => 'required|string|max:255',
            'grade' => 'required|string',
            'classroom' => 'nullable|string',
            'school' => 'required|string',
            'program' => 'required|string',
            'registration_date' => 'required|date',
            'monthly_fee' => 'required|numeric|min:0',
            'parent_name' => 'required|string',
            'parent_phone' => 'required|string',
            'parent_email' => 'nullable|email',
            'address' => 'nullable|string',
            'birth_place_date' => 'nullable|string',
            'is_active' => 'required|boolean',
        ]);

        $student->update($validated);

        return redirect()->route('admin.students.index')->with('success', 'Data siswa berhasil diperbarui.');
    }

    /**
     * Remove the specified resource from storage.
     */
    public function destroy(Student $student)
    {
        $student->delete();
        return redirect()->route('admin.students.index')->with('success', 'Data siswa berhasil dihapus.');
    }
}
