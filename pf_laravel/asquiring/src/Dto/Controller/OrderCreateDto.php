<?php

namespace App\Dto\Controller;

use App\Enum\OrderTypes;
use App\Enum\Source;
use OpenApi\Attributes\Property;
use Symfony\Component\Serializer\Annotation\SerializedName;
use Symfony\Component\Validator\Constraints as Assert;
use Symfony\Component\Validator\Context\ExecutionContextInterface;

class OrderCreateDto implements RequestDtoInterface
{
    #[Assert\NotBlank(null, '"source" not specified.')]
    #[Assert\Choice(choices: Source::SOURCES, message: 'Available sources: {{ choices }}')]

    #[Property(description: 'Источник платежа (наименование сервиса): b2b|b2c...')]
    public string $source;

    #[Assert\NotNull(null, '"total_amount" not specified.')]
    #[Assert\GreaterThan(0, null, '"total_amount" must be greater than 0.')]
    #[Property(description: 'Общая сумма платежа')]
    #[SerializedName('total_amount')]
    public float $amount;

    #[Assert\Valid]
    #[Assert\NotBlank(message: 'Не указаны данные по продуктам "payment_basis"')]
    #[Property(description: 'Данные по продукту (убытку), списком')]
    #[SerializedName('payment_basis')]
    /** @var PaymentBaseDto[] */
    public array $paymentBasis;

    #[SerializedName('client_id')]
    #[Property(description: 'Идентификатор клиента. Заполняется, если полис был заведен в b2c без участия агента. Должен быть указан либо клиент, либо агент')]
    public ?string $clientId = null;

    #[SerializedName('agent_id')]
    #[Property(description: 'Идентификатор агента. Заполняется, если полис был заведен через агента. Должен быть указан либо клиент, либо агент')]
    public ?string $agentId = null;

    #[SerializedName('expires_at')]
    #[Property(description: 'Дата, до которой необходимо внести оплату по полису.')]
    public ?\DateTimeInterface $expiresAt = null;

    #[SerializedName('ucs_bill_need')]
    #[Assert\NotNull(message: 'Не указан признак создания счета в УЦС "ucs_bill_need"')]
    #[Assert\Type(type: 'bool', message: 'Признак необходимости создания счета в УЦС "ucs_bill_need" должен иметь значение true либо false')]
    #[Property(description: 'Признак необходимости создания счета в УЦС.')]
    public $ucsBillNeed;

    #[SerializedName('contractor')]
    #[Assert\Valid]
    #[Property(description: 'Плательщик.')]
    public ?Contractor $contractor = null;

    #[Assert\NotBlank(message: 'Адрес возврата не может быть пустой строкой', allowNull: true)]
    #[Property(description: 'Адрес возврата')]
    #[Assert\Type(type: 'string', message: 'Адрес возврата указывается в виде строки')]
    #[SerializedName('return_url')]
    public $returnUrl = null;

    #[Assert\Choice(choices: OrderTypes::TYPES, message: 'Значение должно быть одним из: client|agent')]
    #[Property(description: 'Тип счета: client|agent')]
    public string $type = OrderTypes::CLIENT_TYPE;

    #[Assert\Callback]
    public function validate(ExecutionContextInterface $context, $payload)
    {
        if (empty($this->clientId) && empty($this->agentId)) {
            $context->addViolation('"agent_id" or "client_id" must be specified.');
        }
    }

    #[Assert\Callback]
    public function validateLotus(ExecutionContextInterface $context, $payload)
    {
        if (Source::LOTUS === $this->source && 1 != count($this->paymentBasis)) {
            $context->addViolation('Only one unit_id must be specified.');
        }
    }
}
