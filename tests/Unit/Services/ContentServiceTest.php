<?php

use App\Models\Content;
use App\Models\User;
use App\Services\ContentService;
use App\Repository\Interface\ContentRepoInterface;
use Tests\TestCase;
use Illuminate\Foundation\Testing\RefreshDatabase;

uses(TestCase::class, RefreshDatabase::class);

beforeEach(function () {
    $this->mockRepo = Mockery::mock(ContentRepoInterface::class);
    $this->service = new ContentService($this->mockRepo);
});

it('can get all content', function () {
    $user = User::factory()->create();

    $this->mockRepo->shouldReceive('getAll')
        ->once()
        ->with(['free'], 10, null, 'desc', null)
        ->andReturn(new \Illuminate\Pagination\LengthAwarePaginator([], 0, 10));

    $result = $this->service->getAllContents($user);

    expect($result)->toBeInstanceOf(\Illuminate\Pagination\LengthAwarePaginator::class);
});


it('can get content by id', function () {
    $user = User::factory()->create();
    $content = Content::factory()->create();

    $this->mockRepo->shouldReceive('findById')
        ->once()
        ->with($content)
        ->andReturn(new Content());

    $result = $this->service->getContentById($content);

    expect($result)->toBeInstanceOf(Content::class);

});


it('can create content ', function(){
    $user = User::factory()->create();

    $data = [
        'title' => "Testing",
        'body' => "Testing body",
        'tier' => "free",
        'created_by' => $user->id
    ];


    $this->mockRepo->shouldReceive('create')
        ->once()
        ->with($data)
        ->andReturn(new Content());

    $result = $this->service->createContent($data);
    echo $result;
    expect($result)->toBeInstanceOf(Content::class);
});