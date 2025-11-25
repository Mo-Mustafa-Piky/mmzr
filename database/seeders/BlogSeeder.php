<?php

namespace Database\Seeders;

use App\Models\Blog;
use Illuminate\Database\Seeder;

class BlogSeeder extends Seeder
{
    public function run(): void
    {
        $blogs = [
            [
                'title' => 'Top 10 Real Estate Investment Tips for 2024',
                'slug' => 'top-10-real-estate-investment-tips-for-2024',
                'content' => '<p>Real estate investment continues to be one of the most reliable ways to build wealth. Here are our top 10 tips for making smart property investments in 2024.</p><h2>1. Research the Market</h2><p>Understanding market trends is crucial for successful investment.</p><h2>2. Location Matters</h2><p>The location of a property can make or break your investment.</p>',
                'author_name' => 'Ahmed Altunki',
                'status' => 'published',
                'is_featured' => true,
                'published_at' => now()->subDays(5),
                'meta_title' => 'Top 10 Real Estate Investment Tips for 2024',
                'meta_description' => 'Discover the best real estate investment strategies for 2024. Expert tips to maximize your property investment returns.',
                'meta_keywords' => 'real estate, investment, property, 2024, tips',
            ],
            [
                'title' => 'Dubai Property Market Trends and Analysis',
                'slug' => 'dubai-property-market-trends-and-analysis',
                'content' => '<p>The Dubai property market has shown remarkable resilience and growth. This comprehensive analysis covers the latest trends and future predictions.</p><h2>Market Overview</h2><p>Dubai continues to attract international investors with its tax-free environment and world-class infrastructure.</p>',
                'author_name' => 'Sarah Johnson',
                'status' => 'published',
                'is_featured' => true,
                'published_at' => now()->subDays(10),
                'meta_title' => 'Dubai Property Market Trends 2024',
                'meta_description' => 'Complete analysis of Dubai property market trends, prices, and investment opportunities in 2024.',
                'meta_keywords' => 'dubai, property market, real estate trends, investment',
            ],
            [
                'title' => 'How to Choose the Perfect Family Home',
                'slug' => 'how-to-choose-the-perfect-family-home',
                'content' => '<p>Finding the perfect family home requires careful consideration of many factors. Here\'s our comprehensive guide to help you make the right choice.</p><h2>Consider Your Needs</h2><p>Think about your family\'s current and future needs.</p>',
                'author_name' => 'Ahmed Altunki',
                'status' => 'published',
                'is_featured' => false,
                'published_at' => now()->subDays(15),
                'meta_title' => 'Guide to Choosing the Perfect Family Home',
                'meta_description' => 'Expert advice on selecting the ideal family home. Learn what to look for when buying a property for your family.',
                'meta_keywords' => 'family home, buying property, real estate guide',
            ],
            [
                'title' => 'Luxury Properties: What Makes Them Worth It',
                'slug' => 'luxury-properties-what-makes-them-worth-it',
                'content' => '<p>Luxury properties offer more than just high price tags. Discover what truly defines a luxury property and why they\'re worth the investment.</p><h2>Premium Locations</h2><p>Luxury properties are always in prime locations.</p>',
                'author_name' => 'Michael Chen',
                'status' => 'published',
                'is_featured' => false,
                'published_at' => now()->subDays(20),
                'meta_title' => 'Understanding Luxury Property Investments',
                'meta_description' => 'What makes luxury properties worth the investment? Explore the features and benefits of high-end real estate.',
                'meta_keywords' => 'luxury properties, high-end real estate, premium homes',
            ],
            [
                'title' => 'First-Time Home Buyer\'s Complete Guide',
                'slug' => 'first-time-home-buyers-complete-guide',
                'content' => '<p>Buying your first home is an exciting milestone. This guide will walk you through every step of the process.</p><h2>Getting Started</h2><p>Understanding your budget is the first crucial step.</p>',
                'author_name' => 'Sarah Johnson',
                'status' => 'published',
                'is_featured' => false,
                'published_at' => now()->subDays(25),
                'meta_title' => 'First-Time Home Buyer\'s Guide 2024',
                'meta_description' => 'Complete guide for first-time home buyers. Learn everything you need to know about purchasing your first property.',
                'meta_keywords' => 'first-time buyer, home buying guide, property purchase',
            ],
        ];

        foreach ($blogs as $blog) {
            Blog::create($blog);
        }
    }
}
