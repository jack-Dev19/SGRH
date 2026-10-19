<?php

namespace App\Http\Controllers;

use App\Models\OffreEmploi;
use Illuminate\Http\Request;

class PublicController extends Controller
{
    /**
     * Landing page — affiche les 3 offres publiées les plus récentes.
     */
    public function index()
    {
        $offresRecentes = OffreEmploi::publiees()->take(3)->get();
        $totalOffres    = OffreEmploi::publiees()->count();

        return view('index', compact('offresRecentes', 'totalOffres'));
    }

    /**
     * Page listant TOUTES les offres publiées.
     */
    public function toutesLesOffres(Request $request)
    {
        $query = OffreEmploi::publiees();

        if ($type = $request->input('type_contrat')) {
            $query->where('type_contrat', $type);
        }

        if ($lieu = $request->input('lieu')) {
            $query->where('lieu', 'like', "%{$lieu}%");
        }

        if ($search = $request->input('search')) {
            $query->where(function ($q) use ($search) {
                $q->where('titre', 'like', "%{$search}%")
                  ->orWhere('description', 'like', "%{$search}%");
            });
        }

        $offres = $query->paginate(9)->withQueryString();

        return view('offres.index', compact('offres'));
    }

    /**
     * Détail d'une offre publiée.
     */
    public function detailOffre(string $slug)
    {
        $offre = OffreEmploi::where('slug', $slug)
            ->where('statut', 'publiee')
            ->firstOrFail();

        $autresOffres = OffreEmploi::publiees()
            ->where('id', '!=', $offre->id)
            ->take(3)
            ->get();

        return view('offres.show', compact('offre', 'autresOffres'));
    }
}