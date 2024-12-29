<?php
namespace Azuriom\Plugin\ShopLogger\Controllers\Admin;

use Azuriom\Plugin\Shop\Models\Payment;
use Azuriom\Plugin\Shop\Models\Category;  // Import pour la catégorie
use Azuriom\Http\Controllers\Controller;
use Illuminate\Http\Request;

class PaymentLogController extends Controller
{
    public function index(Request $request)
    {
        // Récupérer les filtres de la requête
        $filter = $request->get('filter', 'today');  // Par défaut, le filtre est "aujourd'hui"
        $categoryId = $request->get('category_id');  // Catégorie
        $article = $request->get('article');  // Article

        // Requête de base pour récupérer les paiements
        $paymentsQuery = Payment::with('user', 'items.buyable')  // Eager loading pour les articles et catégories
        ->latest();

        // Appliquer les filtres en fonction du type sélectionné
        switch ($filter) {
            case 'last_7_days':
                $paymentsQuery->where('created_at', '>=', now()->subDays(7));
                break;
            case 'this_month':
                $paymentsQuery->whereMonth('created_at', now()->month);
                break;
            case 'custom_period':
                $startDate = $request->get('start_date');
                $endDate = $request->get('end_date');
                if ($startDate && $endDate) {
                    $paymentsQuery->whereBetween('created_at', [$startDate, $endDate]);
                }
                break;
            case 'today':
            default:
                $paymentsQuery->whereDate('created_at', today());
                break;
        }

        // Filtrer par catégorie si sélectionnée
        if ($categoryId) {
            $paymentsQuery->whereHas('items.buyable', function ($query) use ($categoryId) {
                $query->where('category_id', $categoryId);
            });
        }

        // Filtrer par article si le terme est fourni
        if ($article) {
            $paymentsQuery->whereHas('items', function ($query) use ($article) {
                $query->where('name', 'like', '%' . $article . '%');
            });
        }

        // Pagination des résultats
        $payments = $paymentsQuery->paginate(15);

        // Récupérer toutes les catégories pour le formulaire
        $categories = Category::all();

        return view('shoplogger::admin.payment-logs', compact('payments', 'filter', 'categories', 'categoryId', 'article'));
    }
}
