<x-filament-panels::page>
    <div class="space-y-6">
        <!-- Cart Section -->
        <div class="grid grid-cols-1 lg:grid-cols-3 gap-6">
            <!-- Cart Items -->
            <div class="lg:col-span-2">
                <x-filament::section>
                    <x-slot name="heading">
                        <div class="flex items-center justify-between">
                            <h2 class="text-lg font-medium">Shopping Cart</h2>
                            <div class="flex items-center gap-2">
                                <span class="text-sm text-gray-500">{{ $this->cartCount }} items</span>
                                @if(!empty($this->cart))
                                    <x-filament::button 
                                        size="sm" 
                                        color="danger" 
                                        wire:click="clearCart"
                                    >
                                        Clear Cart
                                    </x-filament::button>
                                @endif
                            </div>
                        </div>
                    </x-slot>
                    
                    @if(empty($this->cart))
                        <div class="text-center py-12">
                            <div class="mx-auto w-24 h-24 bg-gray-100 rounded-full flex items-center justify-center mb-4">
                                <svg class="w-12 h-12 text-gray-400" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M16 11V7a4 4 0 00-8 0v4M5 9h14l1 12H4L5 9z" />
                                </svg>
                            </div>
                            <p class="text-gray-500">Your cart is empty</p>
                            <p class="text-sm text-gray-400 mt-2">Add products from the list below to get started</p>
                        </div>
                    @else
                        <div class="space-y-2">
                            @foreach($this->cart as $productId => $item)
                                <div class="flex items-center justify-between p-3 bg-gray-50 rounded-lg">
                                    <div class="flex-1">
                                        <h4 class="font-medium">{{ $item['name'] }}</h4>
                                        <p class="text-sm text-gray-500">SKU: {{ $item['sku'] ?? 'N/A' }}</p>
                                        <p class="text-sm text-gray-500">Stock: {{ $item['stock'] }}</p>
                                    </div>
                                    
                                    <div class="flex items-center gap-3">
                                        <div class="flex items-center gap-2">
                                            <x-filament::button 
                                                size="xs" 
                                                wire:click="updateQuantity('{{ $productId }}', {{ $item['qty'] - 1 }})"
                                                :disabled="$item['qty'] <= 1"
                                            >
                                                -
                                            </x-filament::button>
                                            
                                            <input 
                                                type="number" 
                                                value="{{ $item['qty'] }}" 
                                                min="1" 
                                                max="{{ $item['stock'] }}"
                                                class="w-16 text-center border rounded px-2 py-1"
                                                wire:model.live="cart.{{ $productId }}.qty"
                                                wire:change="$wire.updateQuantity('{{ $productId }}', $event.target.value)"
                                            />
                                            
                                            <x-filament::button 
                                                size="xs" 
                                                wire:click="updateQuantity('{{ $productId }}', {{ $item['qty'] + 1 }})"
                                                :disabled="$item['qty'] >= $item['stock']"
                                            >
                                                +
                                            </x-filament::button>
                                        </div>
                                        
                                        <div class="text-right">
                                            <p class="font-medium">${{ number_format($item['price'], 2) }}</p>
                                            <p class="text-sm text-gray-500">${{ number_format($item['price'] * $item['qty'], 2) }}</p>
                                        </div>
                                        
                                        <x-filament::button 
                                            size="xs" 
                                            color="danger" 
                                            wire:click="removeFromCart('{{ $productId }}')"
                                        >
                                            <svg class="w-4 h-4" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M19 7l-.867 12.142A2 2 0 0116.138 21H7.862a2 2 0 01-1.995-1.858L5 7m5 4v6m4-6v6m1-10V4a1 1 0 00-1-1h-4a1 1 0 00-1 1v3M4 7h16" />
                                            </svg>
                                        </x-filament::button>
                                    </div>
                                </div>
                            @endforeach
                        </div>
                    @endif
                </x-filament::section>
            </div>
            
            <!-- Cart Summary -->
            <div>
                <x-filament::section>
                    <x-slot name="heading">
                        <h2 class="text-lg font-medium">Order Summary</h2>
                    </x-slot>
                    
                    <div class="space-y-4">
                        <!-- Customer Selection -->
                        <div>
                            <x-filament::label>Customer (Optional)</x-filament::label>
                            <select 
                                wire:model.live="selectedCustomerId"
                                class="w-full mt-1 border rounded-lg px-3 py-2"
                            >
                                <option value="">Walk-in Customer</option>
                                @foreach(\App\Models\User::all() as $user)
                                    <option value="{{ $user->id }}">{{ $user->name }}</option>
                                @endforeach
                            </select>
                        </div>
                        
                        <!-- Payment Method -->
                        <div>
                            <x-filament::label>Payment Method</x-filament::label>
                            <div class="mt-2 space-y-2">
                                <label class="flex items-center">
                                    <input type="radio" name="payment" value="cash" wire:model="paymentMethod" checked>
                                    <span class="ml-2">Cash</span>
                                </label>
                                <label class="flex items-center">
                                    <input type="radio" name="payment" value="card" wire:model="paymentMethod">
                                    <span class="ml-2">Card</span>
                                </label>
                                <label class="flex items-center">
                                    <input type="radio" name="payment" value="other" wire:model="paymentMethod">
                                    <span class="ml-2">Other</span>
                                </label>
                            </div>
                        </div>
                        
                        <!-- Totals -->
                        <div class="border-t pt-4 space-y-2">
                            <div class="flex justify-between text-sm">
                                <span>Subtotal:</span>
                                <span>${{ number_format($this->cartTotal, 2) }}</span>
                            </div>
                            <div class="flex justify-between text-sm">
                                <span>Tax (10%):</span>
                                <span>${{ number_format($this->cartTotal * 0.1, 2) }}</span>
                            </div>
                            <div class="flex justify-between font-bold text-lg">
                                <span>Total:</span>
                                <span>${{ number_format($this->cartTotal * 1.1, 2) }}</span>
                            </div>
                        </div>
                        
                        <!-- Checkout Button -->
                        <x-filament::button 
                            wire:click="checkout"
                            color="success"
                            size="lg"
                            class="w-full"
                            :disabled="empty($this->cart)"
                        >
                            <svg class="w-5 h-5 mr-2" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M17 9V7a2 2 0 00-2-2H5a2 2 0 00-2 2v6a2 2 0 002 2h2m2 4h10a2 2 0 002-2v-6a2 2 0 00-2-2H9a2 2 0 00-2 2v6a2 2 0 002 2zm7-5a2 2 0 11-4 0 2 2 0 014 0z" />
                            </svg>
                            Complete Sale
                        </x-filament::button>
                    </div>
                </x-filament::section>
            </div>
        </div>
        
        <!-- Products Table -->
        <x-filament::section>
            <x-slot name="heading">
                <div class="flex items-center justify-between">
                    <h2 class="text-lg font-medium">Available Products</h2>
                    <div class="flex items-center gap-2">
                        <input 
                            type="text" 
                            placeholder="Search products..." 
                            wire:model.live.debounce.300ms="search"
                            class="border rounded-lg px-3 py-2"
                        />
                    </div>
                </div>
            </x-slot>
            
            {{ $this->table }}
        </x-filament::section>
    </div>
</x-filament-panels::page>
