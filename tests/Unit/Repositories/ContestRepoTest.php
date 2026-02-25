<?php

use App\Models\Content;
use App\Models\User;
use App\Models\UserSubscription;
use App\Repository\ContentRepo;
use Database\Factories\ContentFactory;
use Database\Factories\UserFactory;
use App\Enums\SystemRole;
use Tests\TestCase;
use Illuminate\Foundation\Testing\RefreshDatabase;

uses(TestCase::class, RefreshDatabase::class);

beforeEach(function () {
    $this->repo = new ContentRepo();
})->group('test');

it('can gets all content with allowed tiers', function () {
    $contents = Content::factory()->count(10)->create();
    $userSubscription = UserSubscription::factory()->create();

    $allowTire = ['free', 'plus'];
    $repo = $this->repo->getAll($allowTire);

    expect($repo)->toBeObject();
});


it('can get speicific contetn', function () {
    $content = Content::factory()->create();
    echo json_encode($content->toArray(), JSON_PRETTY_PRINT);


    $result = $this->repo->findById($content);

    expect($result)->toBeObject();
    expect($result->title)->toBe($content->title);
});

it('cannot get undefine content', function () {
    $content = new Content();
    $result = $this->repo->findById($content);

    expect($result)->toBeNull();
});

it('can create a content', function () {
    $user = User::factory()->create();

    $data = [
        'title' => "Testing",
        'body' => "Testing body",
        'tier' => "free",
        'created_by' => $user->id
    ];


    $result = $this->repo->create($data);

    expect($result)->toBeObject();
    expect($result->title)->toBe($data['title']);

});

it('cannot create content with missing value', function () {
    $user = User::factory()->create();
    $data = [
        'body' => "Testing body",
        'tier' => "free",
        'created_by' => $user->id
    ];
    expect(fn() => $this->repo->create($data))->toThrow(\Illuminate\Database\QueryException::class);
})->only();