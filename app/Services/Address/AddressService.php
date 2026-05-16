<?php

namespace App\Services\Address;

use App\Models\Address;
use App\Models\User;
use Illuminate\Database\Eloquent\Collection;
use Illuminate\Support\Facades\DB;

class AddressService
{
    /**
     * Get all addresses for a user
     */
    public function getUserAddresses(User $user): Collection
    {
        return $user->addresses()
            ->orderByDesc('is_default')
            ->orderByDesc('created_at')
            ->get();
    }

     /**
     * Create a new address for a user
     */
    public function create(User $user, array $data): Address
    {
        return DB::transaction(function () use ($user, $data) {
            $hasNoAdresses = !$user->addresses()->exists();

            if($hasNoAdresses) {
                $data['is_default'] = true;
            }

            if(!empty($data['is_default'])) {
                $this->unsetDefaultForUser($user);
            }

            return $user->addresses()->create($data);
        });
    }

    public function update(Address $address, array $data)
    {
        return DB::transaction(function () use ($address, $data) {

            if(!empty($data['is_default'])) {
                $this->unsetDefaultForUser($address->user);
            }
            $address->update($data);

            return $address->fresh();
        });
    }

    public function delete(Address $address)
    {
        return DB::transaction(function() use ($address) {

            $wasDefault = $address->is_default;
            $userId = $address->user_id;

            $deleted = $address->delete();

            if($wasDefault && $deleted) {
                $this->promoteFallbackToDefault($userId);
            }

            return $deleted;
        });
    }

     /**
     * Set an address as the default
     */
    public function setAsDefault(Address $address): Address
    {
        return DB::transaction(function () use ($address) {
            $this->unsetDefaultForUser($address->user);

            $address->update(['is_default' => true]);

            return $address->fresh();
        });
    }

    /**
     * Unset the default flag for all of a user's addresses
     */

    private function unsetDefaultForUser(User $user): void
    {
        $user->addresses()
            ->where('is_default', true)
            ->update(['is_default' => false]);
    }

    /**
     * After deleting the default address, promote another to default
     */

    private function promoteFallbackToDefault(int $userId)
    {
        $fallback = Address::where('user_id', $userId)
            ->orderByDesc('created_at')
            ->first();

        if($fallback) {
            $fallback->update(['is_default' => true]);
        }
    }
}
