public function index()
{
    $enfants = Enfant::with(['carnets' => function($q) {
        $q->latest()->limit(1);
    }])->get();
    
    return view('dashboard', compact('enfants'));
}