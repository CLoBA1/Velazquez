<?php

namespace App\Services;

use App\Models\Product;
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
    public function add(Product $product, int $quantity = 1): void
    {
        $cart = $this->getContent();

        if ($cart->has($product->id)) {
            $item = $cart->get($product->id);
            $newQuantity = $item['quantity'] + $quantity;

            if ($newQuantity > $product->stock) {
                throw new \Exception("Stock insuficiente. Solo quedan {$product->stock} unidades disponibles.");
            }

            $item['quantity'] = $newQuantity;
            $cart->put($product->id, $item);
        } else {
            if ($quantity > $product->stock) {
                throw new \Exception("Stock insuficiente. Solo quedan {$product->stock} unidades disponibles.");
            }

            $cart->put($product->id, [
                'id' => $product->id,
                'name' => $product->name,
                'price' => $product->public_price,
                'image' => $product->image_url,
                'quantity' => $quantity,
                'max_stock' => $product->stock
            ]);
        }

        session()->put(self::CART_SESSION_KEY, $cart);
    }

    /**
     * Remove item from cart
     */
    public function remove(int $productId): void
    {
        $cart = $this->getContent();
        $cart->forget($productId);
        session()->put(self::CART_SESSION_KEY, $cart);
    }

    /**
     * Update item quantity
     */
    public function update(int $productId, int $quantity): void
    {
        $cart = $this->getContent();

        if ($cart->has($productId)) {
            $item = $cart->get($productId);
            if ($quantity > 0) {
                // Ensure we don't exceed stock
                $product = Product::find($productId);
                if ($product && $quantity > $product->stock) {
                    throw new \Exception("Stock insuficiente. Solo quedan {$product->stock} unidades disponibles.");
                }

                $item['quantity'] = $quantity;
                $cart->put($productId, $item);
            } else {
                $this->remove($productId);
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
