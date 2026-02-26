<?php

namespace App\Services;

use App\Models\Product;
use App\Models\ProductUnit;
use Illuminate\Support\Collection;
use Illuminate\Support\Facades\Session;

class CartService
{
    const CART_SESSION_KEY = 'shopping_cart';

    /**
     * Get cart content
     * @return Collection
     */
    public function getContent(): Collection
    {
        return session()->get(self::CART_SESSION_KEY, collect());
    }

    /**
     * Add item to cart
     */
    public function add(Product $product, int $quantity = 1, ?int $productUnitId = null): void
    {
        $cart = $this->getContent();

        $cartItemId = $product->id;
        $price = $product->public_price;
        $name = $product->name;

        $stockLimit = $product->stock; // Base stock. Ideally, check unit conversion_factor for strict checks.

        if ($productUnitId) {
            $productUnit = ProductUnit::with('unit')->find($productUnitId);
            if ($productUnit && $productUnit->product_id === $product->id) {
                $cartItemId = $product->id . '_' . $productUnit->id;
                $price = $productUnit->public_price;
                $name = $product->name . ' (' . ($productUnit->unit->name ?? 'Variante') . ')';
                // Adjust stock checking logically (e.g. 1 Caja of 10 items)
                if ($productUnit->conversion_factor > 0) {
                    $stockLimit = floor($product->stock / $productUnit->conversion_factor);
                }
            }
        }

        if ($cart->has($cartItemId)) {
            $item = $cart->get($cartItemId);
            $newQuantity = $item['quantity'] + $quantity;

            if ($newQuantity > $stockLimit) {
                throw new \Exception("Stock insuficiente. Solo quedan {$stockLimit} disponibles en esta presentación.");
            }

            $item['quantity'] = $newQuantity;
            $cart->put($cartItemId, $item);
        } else {
            if ($quantity > $stockLimit) {
                throw new \Exception("Stock insuficiente. Solo quedan {$stockLimit} disponibles en esta presentación.");
            }

            $cart->put($cartItemId, [
                'id' => $cartItemId,
                'real_product_id' => $product->id, // Store base product ID for checkout
                'product_unit_id' => $productUnitId,
                'name' => $name,
                'price' => $price,
                'image' => $product->image_url,
                'quantity' => $quantity,
                'max_stock' => $stockLimit
            ]);
        }

        session()->put(self::CART_SESSION_KEY, $cart);
    }

    /**
     * Remove item from cart
     */
    public function remove(string $cartItemId): void
    {
        $cart = $this->getContent();
        $cart->forget($cartItemId);
        $cart->forget((int) $cartItemId); // backwards compatibility if an old pure-int exists
        session()->put(self::CART_SESSION_KEY, $cart);
    }

    /**
     * Update item quantity
     */
    public function update(string $cartItemId, int $quantity): void
    {
        $cart = $this->getContent();

        if ($cart->has($cartItemId)) {
            $item = $cart->get($cartItemId);
            if ($quantity > 0) {
                // Determine max stock
                $baseProductId = $item['real_product_id'] ?? $cartItemId;
                $productUnitId = $item['product_unit_id'] ?? null;
                $product = Product::find($baseProductId);

                $stockLimit = $product ? $product->stock : 0;

                if ($product && $productUnitId) {
                    $pUnit = ProductUnit::find($productUnitId);
                    if ($pUnit && $pUnit->conversion_factor > 0) {
                        $stockLimit = floor($product->stock / $pUnit->conversion_factor);
                    }
                }

                if ($product && $quantity > $stockLimit) {
                    throw new \Exception("Stock insuficiente. Solo quedan {$stockLimit} disponibles en esta presentación.");
                }

                $item['quantity'] = $quantity;
                $cart->put($cartItemId, $item);
            } else {
                $this->remove($cartItemId);
                return;
            }
        }

        session()->put(self::CART_SESSION_KEY, $cart);
    }

    /**
     * Clear cart
     */
    public function clear(): void
    {
        session()->forget(self::CART_SESSION_KEY);
    }

    /**
     * Get total items
     */
    public function count(): int
    {
        return $this->getContent()->sum('quantity');
    }

    /**
     * Get total price
     */
    public function total(): float
    {
        return $this->getContent()->sum(function ($item) {
            return $item['price'] * $item['quantity'];
        });
    }
}
