<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use App\Models\Family;
use App\Models\FamilySequence;
use App\Models\Product;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;

class ProductCodeController extends Controller
{
    public function generate(Request $request)
    {
        $data = $request->validate([
            'family_id' => ['required', 'exists:families,id'],
        ]);

        $familyId = (int)$data['family_id'];

        $code = DB::transaction(function () use ($familyId) {
            $family = Family::findOrFail($familyId);

            // bloquea fila por family_id para evitar duplicados en concurrencia
            $seq = FamilySequence::where('family_id', $familyId)->lockForUpdate()->first();

            if (!$seq) {
                $seq = FamilySequence::create([
                    'family_id' => $familyId,
                    'last_number' => 0,
                ]);
                // volver a leer con lock
                $seq = FamilySequence::where('family_id', $familyId)->lockForUpdate()->first();
            }

            $next = $seq->last_number + 1;

            // arma código: CODFAM-000001 (6 dígitos)
            $candidate = strtoupper($family->code).'-'.str_pad((string)$next, 6, '0', STR_PAD_LEFT);

            // ultra-seguro: si ya existe (por imports viejos), incrementa hasta uno libre
            while (Product::where('internal_code', $candidate)->exists()) {
                $next++;
                $candidate = strtoupper($family->code).'-'.str_pad((string)$next, 6, '0', STR_PAD_LEFT);
            }

            $seq->update(['last_number' => $next]);

            return $candidate;
        });

        return response()->json([
            'internal_code' => $code,
        ]);
    }
}