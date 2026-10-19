<?php

namespace App\Http\Controllers\Gestion;

use App\Http\Controllers\Controller;
use App\Models\Document;
use App\Models\Employe;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Storage;
use Illuminate\Support\Str;

class DocumentController extends Controller
{
    /**
     * Liste les documents (tous ou d'un employé spécifique).
     */
    public function index(Request $request, ?Employe $employe = null)
    {
        $query = Document::with(['employe', 'uploadedBy']);

        if ($employe) {
            $query->where('employe_id', $employe->id);
        }

        if ($categorie = $request->input('categorie')) {
            $query->where('categorie', $categorie);
        }

        if ($search = $request->input('search')) {
            $query->where(function ($q) use ($search) {
                $q->where('titre', 'like', "%{$search}%")
                  ->orWhere('fichier_nom_original', 'like', "%{$search}%");
            });
        }

        $documents = $query->orderByDesc('created_at')->paginate(20)->withQueryString();

        $stats = [
            'total'    => $employe
                ? Document::where('employe_id', $employe->id)->count()
                : Document::count(),
            'photos'   => Document::where('categorie', 'photo_identite')->count(),
            'cin'      => Document::where('categorie', 'photo_cin')->count(),
            'contrats' => Document::where('categorie', 'contrat')->count(),
            'fiches'   => Document::where('categorie', 'fiche_paie')->count(),
        ];

        return view('gestion.documents.index', compact('documents', 'employe', 'stats'));
    }

    /**
     * Formulaire d'ajout.
     */
    public function create(Request $request, ?Employe $employe = null)
    {
        $employes = Employe::orderBy('nom')->get();

        $selectedEmployeId = $employe?->id ?? $request->input('employe_id');

        return view('gestion.documents.create', compact('employes', 'selectedEmployeId'));
    }

    /**
     * Enregistre un nouveau document.
     */
    public function store(Request $request)
    {
        $validated = $request->validate([
            'employe_id'  => ['required', 'exists:employes,id'],
            'categorie'   => ['required', 'in:photo_identite,photo_cin,contrat,fiche_paie,diplome,attestation,autre'],
            'titre'       => ['required', 'string', 'max:150'],
            'description' => ['nullable', 'string', 'max:1000'],
            'fichier'     => ['required', 'file', 'max:10240'],
            'mois'        => ['nullable', 'integer', 'between:1,12'],
            'annee'       => ['nullable', 'integer', 'between:2000,2100'],
        ], [
            'fichier.required' => 'Veuillez sélectionner un fichier.',
            'fichier.max'      => 'Le fichier ne doit pas dépasser 10 Mo.',
        ]);

        $file = $request->file('fichier');
        $extension = $file->getClientOriginalExtension();
        $nomFichier = Str::uuid() . '.' . $extension;
        $dossier = "documents/{$validated['employe_id']}/{$validated['categorie']}";
        $path = $file->storeAs($dossier, $nomFichier, 'public');

        Document::create([
            'employe_id'           => $validated['employe_id'],
            'categorie'            => $validated['categorie'],
            'titre'                => $validated['titre'],
            'description'          => $validated['description'] ?? null,
            'fichier_path'         => $path,
            'fichier_nom_original' => $file->getClientOriginalName(),
            'fichier_mime'         => $file->getMimeType(),
            'fichier_taille'       => $file->getSize(),
            'mois'                 => $validated['mois']  ?? null,
            'annee'                => $validated['annee'] ?? null,
            'uploaded_by'          => auth()->id(),
        ]);

        return redirect()
            ->route('gestion.employes.documents.index', $validated['employe_id'])
            ->with('success', 'Document ajouté avec succès.');
    }

    /**
     * Affiche un document.
     */
    public function show(Document $document)
    {
        return view('gestion.documents.show', compact('document'));
    }

    /**
     * Télécharge un document.
     */
    public function download(Document $document)
    {
        if (!Storage::disk('public')->exists($document->fichier_path)) {
            return back()->with('error', 'Fichier introuvable.');
        }

        return Storage::disk('public')->download(
            $document->fichier_path,
            $document->fichier_nom_original
        );
    }

    /**
     * Aperçu inline (image/PDF).
     */
    public function preview(Document $document)
    {
        if (!Storage::disk('public')->exists($document->fichier_path)) {
            abort(404, 'Fichier introuvable.');
        }

        $path = Storage::disk('public')->path($document->fichier_path);

        return response()->file($path, [
            'Content-Type'        => $document->fichier_mime,
            'Content-Disposition' => 'inline; filename="' . $document->fichier_nom_original . '"',
        ]);
    }

    /**
     * Formulaire de modification.
     */
    public function edit(Document $document)
    {
        $employes = Employe::orderBy('nom')->get();

        return view('gestion.documents.edit', compact('document', 'employes'));
    }

    /**
     * Met à jour un document.
     */
    public function update(Request $request, Document $document)
    {
        $validated = $request->validate([
            'titre'       => ['required', 'string', 'max:150'],
            'description' => ['nullable', 'string', 'max:1000'],
            'categorie'   => ['required', 'in:photo_identite,photo_cin,contrat,fiche_paie,diplome,attestation,autre'],
            'mois'        => ['nullable', 'integer', 'between:1,12'],
            'annee'       => ['nullable', 'integer', 'between:2000,2100'],
        ]);

        if ($request->hasFile('fichier')) {
            $request->validate(['fichier' => ['file', 'max:10240']]);

            if (Storage::disk('public')->exists($document->fichier_path)) {
                Storage::disk('public')->delete($document->fichier_path);
            }

            $file = $request->file('fichier');
            $extension = $file->getClientOriginalExtension();
            $nomFichier = Str::uuid() . '.' . $extension;
            $dossier = "documents/{$document->employe_id}/{$validated['categorie']}";
            $path = $file->storeAs($dossier, $nomFichier, 'public');

            $validated['fichier_path']         = $path;
            $validated['fichier_nom_original'] = $file->getClientOriginalName();
            $validated['fichier_mime']         = $file->getMimeType();
            $validated['fichier_taille']       = $file->getSize();
        }

        $document->update($validated);

        return redirect()
            ->route('gestion.employes.documents.index', $document->employe_id)
            ->with('success', 'Document modifié avec succès.');
    }

    /**
     * Supprime un document.
     */
    public function destroy(Document $document)
    {
        $employeId = $document->employe_id;

        if (Storage::disk('public')->exists($document->fichier_path)) {
            Storage::disk('public')->delete($document->fichier_path);
        }

        $document->delete();

        return redirect()
            ->route('gestion.employes.documents.index', $employeId)
            ->with('success', 'Document supprimé.');
    }
}