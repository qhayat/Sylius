<?php

/*
 * This file is part of the Sylius package.
 *
 * (c) Paweł Jędrzejewski
 *
 * For the full copyright and license information, please view the LICENSE
 * file that was distributed with this source code.
 */

declare(strict_types=1);

namespace Sylius\Bundle\CoreBundle\DataFixtures\Factory;

use Sylius\Bundle\CoreBundle\DataFixtures\DefaultValues\CustomerDefaultValuesInterface;
use Sylius\Bundle\CoreBundle\DataFixtures\Factory\State\FemaleTrait;
use Sylius\Bundle\CoreBundle\DataFixtures\Factory\State\MaleTrait;
use Sylius\Bundle\CoreBundle\DataFixtures\Factory\State\WithEmailTrait;
use Sylius\Bundle\CoreBundle\DataFixtures\Factory\State\WithFirstNameTrait;
use Sylius\Bundle\CoreBundle\DataFixtures\Factory\State\WithLastNameTrait;
use Sylius\Bundle\CoreBundle\DataFixtures\Factory\State\WithPhoneNumberTrait;
use Sylius\Bundle\CoreBundle\DataFixtures\Transformer\CustomerTransformerInterface;
use Sylius\Bundle\CoreBundle\DataFixtures\Updater\CustomerUpdaterInterface;
use Sylius\Component\Core\Model\Customer;
use Sylius\Component\Core\Model\CustomerInterface;
use Sylius\Component\Customer\Model\CustomerGroupInterface;
use Sylius\Component\Resource\Factory\FactoryInterface;
use Zenstruck\Foundry\ModelFactory;
use Zenstruck\Foundry\Proxy;

/**
 * @extends ModelFactory<CustomerInterface>
 *
 * @method static CustomerInterface|Proxy createOne(array $attributes = [])
 * @method static CustomerInterface[]|Proxy[] createMany(int $number, array|callable $attributes = [])
 * @method static CustomerInterface|Proxy find(object|array|mixed $criteria)
 * @method static CustomerInterface|Proxy findOrCreate(array $attributes)
 * @method static CustomerInterface|Proxy first(string $sortedField = 'id')
 * @method static CustomerInterface|Proxy last(string $sortedField = 'id')
 * @method static CustomerInterface|Proxy random(array $attributes = [])
 * @method static CustomerInterface|Proxy randomOrCreate(array $attributes = [])
 * @method static CustomerInterface[]|Proxy[] all()
 * @method static CustomerInterface[]|Proxy[] findBy(array $attributes)
 * @method static CustomerInterface[]|Proxy[] randomSet(int $number, array $attributes = [])
 * @method static CustomerInterface[]|Proxy[] randomRange(int $min, int $max, array $attributes = [])
 * @method CustomerInterface|Proxy create(array|callable $attributes = [])
 */
final class CustomerFactory extends ModelFactory implements CustomerFactoryInterface, FactoryWithModelClassAwareInterface
{
    use WithEmailTrait;
    use WithFirstNameTrait;
    use WithLastNameTrait;
    use FemaleTrait;
    use MaleTrait;
    use WithPhoneNumberTrait;

    private static ?string $modelClass = null;

    public function __construct(
        private FactoryInterface $customerFactory,
        private CustomerDefaultValuesInterface $defaultValues,
        private CustomerTransformerInterface $transformer,
        private CustomerUpdaterInterface $updater,
    ) {
        parent::__construct();
    }

    public static function withModelClass(string $modelClass): void
    {
        self::$modelClass = $modelClass;
    }

    public function withBirthday(\DateTimeInterface|string $birthday): self
    {
        if (is_string($birthday)) {
            $birthday = new \DateTimeImmutable($birthday);
        }

        return $this->addState(['birthday' => $birthday]);
    }

    public function withPassword(string $password): self
    {
        return $this->addState(['password' => $password]);
    }

    public function withGroup(Proxy|CustomerGroupInterface|string $customerGroup): self
    {
        return $this->addState(['customer_group' => $customerGroup]);
    }

    protected function getDefaults(): array
    {
        return $this->defaultValues->getDefaults(self::faker());
    }

    protected function transform(array $attributes): array
    {
        return $this->transformer->transform($attributes);
    }

    protected function update(CustomerInterface $customer, array $attributes): void
    {
        $this->updater->update($customer, $attributes);
    }

    protected function initialize(): self
    {
        return $this
            ->beforeInstantiate(function (array $attributes): array {
                return $this->transform($attributes);
            })
            ->instantiateWith(function(array $attributes): CustomerInterface {
                /** @var CustomerInterface $customer */
                $customer = $this->customerFactory->createNew();

                $this->update($customer, $attributes);

                return $customer;
            })
        ;
    }

    protected static function getClass(): string
    {
        return self::$modelClass ?? Customer::class;
    }
}