<?php

namespace App\Domain\User\Handlers;

use App\Base\Exceptions\ExternalServiceException;
use App\Domain\Application\File\DTO\ImageDTO;
use App\Domain\Application\File\Services\ImageService;
use App\Domain\User\Entities\User;
use App\Domain\User\Entities\UserSocialProvider;
use App\Domain\User\Exceptions\AuthHandlerException;
use App\Domain\User\Exceptions\EmptyEmailException;
use App\Domain\User\Exceptions\UserExistsException;
use App\Domain\User\Repositories\BaseUserRepository;
use App\Domain\User\Repositories\UserSocialProviderRepository;
use App\Domain\User\Services\UserCrudService;
use Illuminate\Database\Eloquent\Builder;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Support\Facades\Log;
use Illuminate\Support\Str;
use Laravel\Socialite\Contracts\User as SocialUser;


class SocialAuthHandler
{
    public function __construct(
        private SocialUser $client,
        private string $provider,
        private UserSocialProviderRepository $userSocialProviderRepository,
        private BaseUserRepository $userRepository,
        private UserCrudService $userService,
        private ImageService $imageService,
    )
    {
    }

    public static function create(SocialUser $client, string $provider): static
    {
        return app(static::class, ['client' => $client, 'provider' => $provider]);
    }

    public function connect(?User $user): ?User
    {
        if ($user) {
            if ($socialClient = $this->findConnection()) {
                return $socialClient->user->is($user)
                    ? $user
                    : throw new AuthHandlerException(__('auth.social.linkedToAnotherUser', ['provider' => $socialClient->provider]));
            }
            if ($socialClient = $this->createConnection($user)) {
                return $user;
            }
        } else {
            if ($socialClient = $this->findConnection() ?: $this->createConnection($this->createUser())) {
                return $socialClient->user;
            }
        }

        return null;
    }

    private function findConnection(): UserSocialProvider|Builder|null
    {
        return $this->userSocialProviderRepository->find([
            'source'   => $this->provider,
            'sourceId' => $this->client->getId(),
        ]);
    }

    private function createConnection(User $user): UserSocialProvider|Model
    {
        return $user->socialProviders()->create([
            'provider'    => $this->provider,
            'provider_id' => $this->client->getId(),
            'attributes'  => serialize($this->client->getRaw()),
        ]);
    }

    private function createUser($email = null): ?User
    {
        $email = $this->client->getEmail() ?: $email;

        if (empty($email)) {
            throw new EmptyEmailException(__('auth.social.emptyEmail'));
        }

        if ($this->userRepository->search(['email' => $email])) {
            throw new UserExistsException(__('auth.social.emailExists', ['email' => $email]));
        }

        $nameParts = explode(' ', $this->client->getName());
        try {
            $avatarUrl = $this->client->getAvatar();
            $avatar = $avatarUrl ? $this->imageService->create(ImageDTO::createFromUrl($avatarUrl))->url : null;
        } catch (ExternalServiceException $exception) {
            $avatar = null;
            Log::error($exception);
        }

        return $this->userService->create([
            'email'     => $email,
            'firstName' => $nameParts[1] ?? null,
            'lastName'  => $nameParts[0] ?? null,
            'password'  => Str::random(8),
            'avatarId'  => $avatar->url ?? null,
        ]);
    }
}
