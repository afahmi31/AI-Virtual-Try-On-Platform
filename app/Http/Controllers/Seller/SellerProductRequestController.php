<?php

namespace App\Http\Controllers\Seller;

use App\Http\Controllers\Controller;
use App\Models\AuditLog;
use App\Models\ProductRequest;
use App\Models\Seller;
use App\Support\CurrentSellerResolver;
use Carbon\Carbon;
use Illuminate\Http\RedirectResponse;
use Illuminate\Http\Request;
use Illuminate\Validation\Rule;

class SellerProductRequestController extends Controller
{
    public function __construct(private readonly CurrentSellerResolver $currentSellerResolver)
    {
    }

    private function seller(): Seller
    {
        return $this->currentSellerResolver->resolveForUser(auth()->user());
    }

    public function index(Request $request)
    {
        $seller = $this->seller();
        $status = trim((string) $request->query('status', 'all'));

        $requestsQuery = ProductRequest::query()
            ->with('linkedProduct:id,name,slug,status,seller_id')
            ->where('seller_id', $seller->id)
            ->latest();

        if ($status === 'new') {
            $requestsQuery->where(function ($query): void {
                $query->whereIn('status', ['new', 'pending'])
                    ->orWhereNull('status')
                    ->orWhere('status', '');
            });
        } elseif (in_array($status, ['not_added', 'added'], true)) {
            $requestsQuery->where('status', $status);
        }

        $requests = $requestsQuery
            ->paginate(20)
            ->withQueryString();

        return view('seller.product-requests.index', compact('seller', 'requests', 'status'));
    }

    public function updateStatus(Request $request, int $requestId): RedirectResponse
    {
        $seller = $this->seller();
        $productRequest = ProductRequest::query()
            ->where('seller_id', $seller->id)
            ->findOrFail($requestId);

        $payload = $request->validate([
            'status' => ['required', 'in:new,not_added,added'],
            'linked_product_id' => [
                'nullable',
                'integer',
                Rule::exists('products', 'id')->where(function ($query) use ($seller): void {
                    $query->where('seller_id', $seller->id);
                }),
            ],
        ]);

        $nextStatus = (string) $payload['status'];
        $linkedProductId = isset($payload['linked_product_id']) && is_numeric($payload['linked_product_id'])
            ? (int) $payload['linked_product_id']
            : null;

        $productRequest->update([
            'status' => $nextStatus,
            'linked_product_id' => $nextStatus === 'added' ? $linkedProductId : null,
            'reviewed_at' => $nextStatus === 'new' ? null : Carbon::now(),
        ]);

        AuditLog::query()->create([
            'actor_user_id' => auth()->id(),
            'action' => 'product_request_status_updated_seller',
            'entity_type' => ProductRequest::class,
            'entity_id' => $productRequest->id,
            'payload_json' => [
                'seller_id' => $seller->id,
                'status' => $nextStatus,
                'linked_product_id' => $nextStatus === 'added' ? $linkedProductId : null,
            ],
        ]);

        return redirect()
            ->route('seller.product-requests.index')
            ->with('success', (string) __('ui.product_requests_page.status_updated'));
    }
}
