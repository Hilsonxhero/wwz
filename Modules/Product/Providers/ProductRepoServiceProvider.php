<?php

namespace Modules\Product\Providers;

use Modules\Product\Repository\FeatureRepository;
use Modules\Product\Repository\ProductRepository;
use Modules\Product\Repository\VariantRepository;
use Modules\Product\Repository\RecommendationRepo;
use Modules\Product\Repository\FeatureValueRepository;
use Modules\Product\Repository\VariantGroupRepository;
use Modules\Product\Repository\ProductReviewRepository;
use Modules\Product\Repository\ProductFeatureRepository;
use Modules\Product\Repository\ProductGalleryRepository;
use Modules\Product\Repository\ProductVariantRepository;
use Modules\Product\Repository\ProductQuestionRepository;
use Modules\Product\Repository\RecommendationProductRepo;
use Modules\Product\Repository\FeatureRepositoryInterface;
use Modules\Product\Repository\ProductRepositoryInterface;
use Modules\Product\Repository\VariantRepositoryInterface;
use Modules\Product\Repository\IncredibleProductRepository;
use Modules\Product\Repository\RecommendationRepoInterface;
use Modules\Product\Repository\ProductAnnouncementRepository;
use Modules\Product\Repository\FeatureValueRepositoryInterface;
use Modules\Product\Repository\VariantGroupRepositoryInterface;
use Modules\Product\Repository\ProductReviewRepositoryInterface;
use Modules\Product\Repository\ProductFeatureRepositoryInterface;
use Modules\Product\Repository\ProductGalleryRepositoryInterface;
use Modules\Product\Repository\ProductVariantRepositoryInterface;
use Modules\Product\Repository\ProductQuestionRepositoryInterface;
use Modules\Product\Repository\RecommendationProductRepoInterface;
use Modules\Product\Repository\IncredibleProductRepositoryInterface;
use Modules\Product\Repository\ProductAnnouncementRepositoryInterface;
use Illuminate\Foundation\Support\Providers\RouteServiceProvider as ServiceProvider;
use Modules\Product\Repository\PriceHistoryRepository;
use Modules\Product\Repository\PriceHistoryRepositoryInterface;
use Modules\Product\Repository\ProductWishRepository;
use Modules\Product\Repository\ProductWishRepositoryInterface;

class ProductRepoServiceProvider extends ServiceProvider
{
    /**
     * Register services.
     *
     * @return void
     */
    public function register()
    {
        //
    }

    /**
     * Bootstrap services.
     *
     * @return void
     */
    public function boot()
    {
        $this->app->bind(FeatureRepositoryInterface::class, FeatureRepository::class);
        $this->app->bind(FeatureValueRepositoryInterface::class, FeatureValueRepository::class);
        $this->app->bind(ProductRepositoryInterface::class, ProductRepository::class);
        $this->app->bind(VariantGroupRepositoryInterface::class, VariantGroupRepository::class);
        $this->app->bind(VariantRepositoryInterface::class, VariantRepository::class);
        $this->app->bind(ProductFeatureRepositoryInterface::class, ProductFeatureRepository::class);
        $this->app->bind(ProductVariantRepositoryInterface::class, ProductVariantRepository::class);
        $this->app->bind(IncredibleProductRepositoryInterface::class, IncredibleProductRepository::class);
        $this->app->bind(RecommendationProductRepoInterface::class, RecommendationProductRepo::class);
        $this->app->bind(RecommendationRepoInterface::class, RecommendationRepo::class);
        $this->app->bind(ProductQuestionRepositoryInterface::class, ProductQuestionRepository::class);
        $this->app->bind(ProductGalleryRepositoryInterface::class, ProductGalleryRepository::class);
        $this->app->bind(ProductReviewRepositoryInterface::class, ProductReviewRepository::class);
        $this->app->bind(ProductAnnouncementRepositoryInterface::class, ProductAnnouncementRepository::class);
        $this->app->bind(ProductWishRepositoryInterface::class, ProductWishRepository::class);
        $this->app->bind(PriceHistoryRepositoryInterface::class, PriceHistoryRepository::class);
    }
}
