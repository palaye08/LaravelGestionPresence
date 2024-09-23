<?php

namespace App\Http\Middleware;

use Closure;
use Illuminate\Http\Request;
use Symfony\Component\HttpFoundation\Response;

class PromotionClosed
{
    /**
     * Handle an incoming request.
     *
     * @param  \Closure(\Illuminate\Http\Request): (\Symfony\Component\HttpFoundation\Response)  $next
     */
    public function handle(Request $request, Closure $next): Response
    {
        $id = $request->route('id');
        $promotion = $this->promotionRepository->getPromotionById($id);
    
        if ($promotion && $promotion->etat === 'cloturer') {
            return response()->json(['error' => 'Impossible d\'effectuer cette opération sur une promotion clôturée'], 403);
        }
    
        return $next($request);
    }
}
