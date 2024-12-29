<?php
namespace Azuriom\Plugin\ShopLogger\Controllers\Admin;

use Azuriom\Http\Controllers\Controller;
use Azuriom\Plugin\Shop\Models\PaymentItem;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;

class ProductStatsController extends Controller
{
    public function index(Request $request)
    {
        // Récupérer les filtres de la requête
        $filter = $request->get('filter', 'today');  // Par défaut, le filtre est "aujourd'hui"

        // Requête de base pour récupérer les articles avec le nombre total vendu
        $itemsQuery = PaymentItem::select('buyable_id', 'name', DB::raw('SUM(quantity) as total_sold'))
            ->groupBy('buyable_id', 'name');

        // Appliquer les filtres en fonction du type sélectionné
        switch ($filter) {
            case 'last_7_days':
                $itemsQuery->where('created_at', '>=', now()->subDays(7));
                break;
            case 'this_month':
                $itemsQuery->whereMonth('created_at', now()->month);
                break;
            case 'custom_period':
                $startDate = $request->get('start_date');
                $endDate = $request->get('end_date');
                if ($startDate && $endDate) {
                    $itemsQuery->whereBetween('created_at', [$startDate, $endDate]);
                }
                break;
            case 'today':
            default:
                $itemsQuery->whereDate('created_at', today());
                break;
        }

        // Pagination des résultats
        $items = $itemsQuery->paginate(10);

        return view('shoplogger::admin.product-stats', compact('items', 'filter'));
    }

    /**
     * Afficher les utilisateurs ayant acheté un article.
     */
    public function showItemDetails($itemId)
    {
        // Récupérer l'article en fonction du buyable_id
        $items = PaymentItem::where('buyable_id', $itemId)->get();

        // Vérifier s'il existe un article
        if ($items->isEmpty()) {
            return redirect()->route('shoplogger::admin.product-details')->with('error', 'Aucun article trouvé.');
        }

        // On suppose que l'article a un nom (au cas où il y en a plusieurs)
        $item = $items->first(); // Vous pouvez récupérer le premier si vous êtes sûr qu'il y a un seul article.

        // Récupérer les utilisateurs ayant acheté cet article et le nombre de fois qu'ils l'ont acheté
        $userStats = [];
        $totalQuantity = 0;

        foreach ($items as $item) {
            // Récupérer les paiements associés à l'article
            $payments = $item->payment()->with('user')->get();

            // Calculer la quantité achetée par chaque utilisateur
            foreach ($payments as $payment) {
                $userId = $payment->user_id;

                // Si l'utilisateur n'est pas encore dans la liste, on l'ajoute
                if (!isset($userStats[$userId])) {
                    $userStats[$userId] = [
                        'user' => $payment->user,  // Récupérer l'utilisateur
                        'quantity' => 0,           // Initialiser la quantité achetée
                    ];
                }

                // Ajouter la quantité achetée pour cet utilisateur
                $userStats[$userId]['quantity'] += $item->quantity;
                $totalQuantity += $item->quantity;
            }
        }

        return view('shoplogger::admin.product-details', compact('item', 'userStats', 'totalQuantity'));
    }


}




