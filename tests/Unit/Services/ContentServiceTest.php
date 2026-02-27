<?php

namespace Tests\Unit\Services;

use App\Models\Content;
use App\Models\User;
use App\Repository\Interface\ContentRepoInterface;
use App\Services\ContentService;
use Illuminate\Foundation\Testing\RefreshDatabase;
use Mockery;
use Mockery\MockInterface;
use Tests\TestCase;

class ContentServiceTest extends TestCase
{
    use RefreshDatabase;

    private MockInterface $mockRepo;
    private ContentService $service;

    protected function setUp(): void
    {
        parent::setUp();

        $this->mockRepo = Mockery::mock(ContentRepoInterface::class);
        $this->service = new ContentService($this->mockRepo);
    }

    protected function tearDown(): void
    {
        Mockery::close();
        parent::tearDown();
    }

    public function test_can_get_all_content(): void
    {
        $user = User::factory()->create();

        $this->mockRepo->shouldReceive('getAll')
            ->once()
            ->with(['free'], 10, null, 'desc', null)
            ->andReturn(new \Illuminate\Pagination\LengthAwarePaginator([], 0, 10));

        $result = $this->service->getAllContents($user);

        $this->assertInstanceOf(\Illuminate\Pagination\LengthAwarePaginator::class, $result);
    }

    public function test_can_get_content_by_id(): void
    {
        $content = Content::factory()->create();

        $this->mockRepo->shouldReceive('findById')
            ->once()
            ->with($content)
            ->andReturn(new Content());

        $result = $this->service->getContentById($content);

        $this->assertInstanceOf(Content::class, $result);
    }

    public function test_can_create_content(): void
    {
        $user = User::factory()->create();

        $data = [
            'title' => 'Testing',
            'body' => 'Testing body',
            'tier' => 'free',
            'created_by' => $user->id,
        ];

        $this->mockRepo->shouldReceive('create')
            ->once()
            ->with($data)
            ->andReturn(new Content());

        $result = $this->service->createContent($data);

        $this->assertInstanceOf(Content::class, $result);
    }
}
