<?php

namespace App\Services;

use App\Repository\Interface\ContentRepoInterface;
use App\Models\Content;
use App\Models\User;
use App\Enums\ContentTier;
use App\Enums\UserSubscriptionStatus;
use Illuminate\Pagination\LengthAwarePaginator;
use Illuminate\Support\Facades\Log;
class ContentService
{
    protected $contentRepo;

    public function __construct(ContentRepoInterface $contentRepo)
    {
        $this->contentRepo = $contentRepo;
    }

    public function getAllContents(User $user, int $perPage = 10, ?string $search = null, string $sort = 'desc', ?string $sortBy = null): LengthAwarePaginator
    {   
        $userSubscription = $user->userSubscriptions()
            ->where('status', UserSubscriptionStatus::ACTIVE)
            ->where('expired_at', '>', now())
            ->with('subscriptionPlan')
            ->first();

        $allowedTiers = $this->getAllowedTiers($userSubscription);
        log::info("Allow tiers: ", $allowedTiers);
        return $this->contentRepo->getAll($allowedTiers, $perPage, $search, $sort, $sortBy);
    }

    private function getAllowedTiers($userSubscription): array
    {
        if (!$userSubscription || !$userSubscription->subscriptionPlan) {
            return [ContentTier::FREE->value];
        }

        $planName = strtolower($userSubscription->subscriptionPlan->name);

        return match ($planName) {
            'premium' => [ContentTier::FREE->value, ContentTier::PLUS->value, ContentTier::PREMIUM->value],
            'plus' => [ContentTier::FREE->value, ContentTier::PLUS->value],
            'free' => [ContentTier::FREE->value],
            default => [ContentTier::FREE->value],
        };
    }

    public function getContentById(Content $content): ?Content
    {
        $this->contentRepo->incrementView($content);
        return $this->contentRepo->findById($content);
    }

    public function createContent(array $data): Content
    {
        return $this->contentRepo->create(data: $data);
    }

    public function updateContent(Content $content, array $data): bool
    {
        return $this->contentRepo->update($content, $data);
    }

    public function deleteContent(Content $content): bool
    {
        return $this->contentRepo->delete($content);
    }
}
