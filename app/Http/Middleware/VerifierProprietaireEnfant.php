<?php
namespace App\Http\Middleware;

use Closure;
use Illuminate\Http\Request;
use App\Models\Enfant;

class VerifierProprietaireEnfant
{
    public function handle(Request $request, Closure $next)
    {
        $enfantId = $request->route('enfant') ?? $request->route('id');
        
        if ($enfantId) {
            $enfant = Enfant::find($enfantId);
            
            if (!$enfant) {
                return response()->json(['message' => 'Enfant non trouvé'], 404);
            }
            
            if ($enfant->user_id !== auth()->id()) {
                return response()->json(['message' => 'Non autorisé'], 403);
            }
        }
        
        return $next($request);
    }
}