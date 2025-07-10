<?php

namespace App\Http\Controllers\v1;

use Illuminate\Http\Request;
use Illuminate\Support\Facades\Http;
use Illuminate\Support\Facades\Storage;
use App\Models\Diagnosis;
use App\Http\Controllers\Controller;

class DiagnosisController extends Controller
{
    public function diagnose(Request $request)
    {
        // ✅ 1. Validate image (patient_id is optional)
        $request->validate([
            'image' => 'required|image|max:5120',
            'patient_id' => 'nullable|exists:patients,id'
        ]);

        // ✅ 2. Save image locally
        $imagePath = $request->file('image')->store('public/diagnosis_images');
        $imageName = basename($imagePath);

        // ✅ 3. Send image to FastAPI
        $image = fopen($request->file('image')->getRealPath(), 'r');
        $response = Http::attach(
            'file',
            $image,
            $request->file('image')->getClientOriginalName()
            )->post('https://3b0c914914ec.ngrok-free.app/predict/');
        fclose($image);

        // ✅ 4. Handle FastAPI failure
        if (!$response->ok()) {
            return response()->json([
                'status' => $response->status(),
                'data' => null
            ], $response->status());
        }

        // ✅ 5. Always create diagnosis in DB (even if no patient_id yet)
        $data = $response->json();

        $diagnosis = Diagnosis::create([
            'patient_id' => $request->patient_id, // ممكن تبقى null
            'image_path' => $imageName,
            'predicted_class' => $data['predicted_class_name'],
            'description' => $data['description'],
            'analysis' => $data['analysis'],
            'detailed_analysis' => $data['detailed_analysis'],
            'recommendations' => $data['recommendations'],
        ]);

        // ✅ 6. Return prediction + diagnosis ID
        return response()->json([
            'status' => 200,
            'diagnosis_id' => $diagnosis->id,
            'data' => $data
        ]);
    }
}
