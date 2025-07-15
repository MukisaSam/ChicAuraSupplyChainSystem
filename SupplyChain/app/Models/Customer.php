<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Foundation\Auth\User as Authenticatable;
use Illuminate\Notifications\Notifiable;

class Customer extends Authenticatable
{
    use HasFactory, Notifiable;

    protected $fillable = [
        'name',
        'email',
        'password',
        'phone',
        'city',
        'address',
        'age_group',
        'gender',
        'income_bracket',
        'shopping_preferences',
        'purchase_frequency',
        'is_active',
        'email_verified_at',
    ];

    protected $hidden = [
        'password',
        'remember_token',
    ];

    protected $casts = [
        'email_verified_at' => 'datetime',
        //'password' => 'hashed',
        'shopping_preferences' => 'array',
        'is_active' => 'boolean',
    ];

    public function customerOrders()
    {
        return $this->hasMany(CustomerOrder::class);
    }

    public function getAgeGroupOptions()
    {
        return [
            '18-25' => '18-25 years',
            '26-35' => '26-35 years',
            '36-45' => '36-45 years',
            '46-55' => '46-55 years',
            '56-65' => '56-65 years',
            '65+' => '65+ years'
        ];
    }

    public function getIncomeBracketOptions()
    {
        return [
            'low' => 'Below $30,000',
            'middle-low' => '$30,000 - $50,000',
            'middle' => '$50,000 - $75,000',
            'middle-high' => '$75,000 - $100,000',
            'high' => 'Above $100,000',
            'prefer-not-to-say' => 'Prefer not to say'
        ];
    }

    public function getPurchaseFrequencyOptions()
    {
        return [
            'weekly' => 'Weekly',
            'monthly' => 'Monthly',
            'quarterly' => 'Quarterly',
            'yearly' => 'Yearly',
            'occasional' => 'Occasional'
        ];
    }

    public function getShoppingPreferencesOptions()
    {
        return [
            'casual_wear' => 'Casual Wear',
            'formal_wear' => 'Formal Wear',
            'sports_wear' => 'Sports Wear',
            'luxury_items' => 'Luxury Items',
            'budget_friendly' => 'Budget Friendly',
            'eco_friendly' => 'Eco Friendly',
            'latest_trends' => 'Latest Trends',
            'classic_styles' => 'Classic Styles'
        ];
    }
}