<?php

namespace App\Http\Controllers;

use App\Models\Patient;
use App\Models\Diagnosis;
use Illuminate\Http\Request;
use App\Http\Controllers\Controller;
use Illuminate\Support\Facades\Storage;

class PatientController extends Controller
{
    // Get a list of all patients (id, full name, profile photo)
    public function index()
{
    $patients = Patient::select('id', 'first_name', 'last_name', 'profile_photo', 'created_at')
        ->get()
        ->map(function ($patient) {
            return [
                'id' => $patient->id,
                'full_name' => trim($patient->first_name . ' ' . $patient->last_name),
                'profile_photo' => $patient->profile_photo ? asset('storage/profile_photos/' . $patient->profile_photo) : null,
                'created_at' => $patient->created_at->format('j M Y'),
            ];
        });

    return response()->json([
        'success' => "true",
        'message' => 'Retrived all Patients successfully',
        'patients' => $patients
    ]);
}

    // Create a new patient and optionally link to a diagnosis
    public function store(Request $request)
{
    $validated = $request->validate([
        'first_name'    => 'required|string|max:255',
        'last_name'     => 'required|string|max:255',
        'gender'        => 'required|in:male,female',
        'age'           => 'required|integer|min:0',
        'profile_photo' => 'nullable|image|max:2048',
        'diagnosis_id'  => 'nullable|exists:diagnoses,id',
    ]);

    if ($request->hasFile('profile_photo')) {
        $photoPath = $request->file('profile_photo')->store('public/profile_photos');
        $validated['profile_photo'] = basename($photoPath);
    }

    $patient = Patient::create($validated);

    if ($request->filled('diagnosis_id')) {
        Diagnosis::where('id', $request->diagnosis_id)
            ->update(['patient_id' => $patient->id]);
    }

    $formattedPatient = [
        'id' => $patient->id,
        'full_name' => trim($patient->first_name . ' ' . $patient->last_name),
        'profile_photo' => $patient->profile_photo ? asset('storage/profile_photos/' . $patient->profile_photo) : null,
        'created_at' => $patient->created_at->format('j M Y'),
    ];

    return response()->json([
        'success' => true,
        'message' => 'Patient and diagnosis linked successfully.',
        'patient' => $formattedPatient,
    ]);
}


    // Show details of a specific patient with diagnoses
    public function show($id)
{
    $patient = Patient::with('diagnoses')->findOrFail($id);

    $formattedPatient = [
        'id' => $patient->id,
        'full_name' => trim($patient->first_name . ' ' . $patient->last_name),
        'profile_photo' => $patient->profile_photo ? asset('storage/profile_photos/' . $patient->profile_photo) : null,
        'created_at' => $patient->created_at->format('j M Y'),
        'diagnoses' => $patient->diagnoses
    ];

    return response()->json([
        'success' => true,
        'message' => 'Patient retrieved successfully',
        'patient' => $formattedPatient,
    ]);
}


    // Update an existing patient's information
    public function update(Request $request, $id)
{
    $patient = Patient::findOrFail($id);

    $data = $request->validate([
        'first_name'    => 'sometimes|required|string|max:255',
        'last_name'     => 'sometimes|required|string|max:255',
        'gender'        => 'sometimes|required|in:male,female',
        'age'           => 'sometimes|required|integer|min:0',
        'profile_photo' => 'nullable|image|max:2048',
    ]);

    if ($request->hasFile('profile_photo')) {
        if ($patient->profile_photo) {
            Storage::delete('public/profile_photos/' . $patient->profile_photo);
        }

        $photoPath = $request->file('profile_photo')->store('public/profile_photos');
        $data['profile_photo'] = basename($photoPath);
    }

    $patient->update($data);

    // ✅ تأكد إن البيانات اللي هترجع محدثة
    $patient->refresh();

    $formattedPatient = [
        'id' => $patient->id,
        'full_name' => trim($patient->first_name . ' ' . $patient->last_name),
        'profile_photo' => $patient->profile_photo ? asset('storage/profile_photos/' . $patient->profile_photo) : null,
        'created_at' => $patient->created_at->format('j M Y'),
    ];

    return response()->json([
        'success' => true,
        'message' => 'Patient updated successfully',
        'patient' => $formattedPatient
    ], 200);
}


    // Delete a patient and their profile photo (if exists)
    public function destroy($id)
    {
        $patient = Patient::findOrFail($id);

        if ($patient->profile_photo) {
            Storage::delete('public/profile_photos/' . $patient->profile_photo);
        }

        $patient->delete();

        return response()->json([
            'success' => true,
            'message' => 'Patient deleted successfully'
        ], 200);
    }

    // Get diagnosis history for a specific patient
    public function diagnosisHistory($id)
    {
        $patient = Patient::with('diagnoses')->findOrFail($id);

        return response()->json([
            'status' => 200,
            'diagnoses' => $patient->diagnoses->map(function ($diag) {
                return [
                    'image_url' => asset('storage/diagnosis_images/' . $diag->image_path),
                    'predicted_class' => $diag->predicted_class,
                    'description' => $diag->description,
                    'analysis' => $diag->analysis,
                    'detailed_analysis' => $diag->detailed_analysis,
                    'recommendations' => $diag->recommendations,
                    'created_at' => $diag->created_at->toDateTimeString()
                ];
            })
        ]);
    }
}
