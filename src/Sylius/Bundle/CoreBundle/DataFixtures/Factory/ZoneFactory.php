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

use Sylius\Bundle\CoreBundle\DataFixtures\DefaultValues\ZoneDefaultValuesInterface;
use Sylius\Bundle\CoreBundle\DataFixtures\Factory\State\WithCodeTrait;
use Sylius\Bundle\CoreBundle\DataFixtures\Factory\State\WithNameTrait;
use Sylius\Bundle\CoreBundle\DataFixtures\Transformer\ZoneTransformerInterface;
use Sylius\Bundle\CoreBundle\DataFixtures\Updater\ZoneUpdaterInterface;
use Sylius\Component\Addressing\Model\Zone;
use Sylius\Component\Addressing\Model\ZoneInterface;
use Sylius\Component\Resource\Factory\FactoryInterface;
use Zenstruck\Foundry\ModelFactory;
use Zenstruck\Foundry\Proxy;

/**
 * @extends ModelFactory<ZoneInterface>
 *
 * @method static ZoneInterface|Proxy createOne(array $attributes = [])
 * @method static ZoneInterface[]|Proxy[] createMany(int $number, array|callable $attributes = [])
 * @method static ZoneInterface|Proxy find(object|array|mixed $criteria)
 * @method static ZoneInterface|Proxy findOrCreate(array $attributes)
 * @method static ZoneInterface|Proxy first(string $sortedField = 'id')
 * @method static ZoneInterface|Proxy last(string $sortedField = 'id')
 * @method static ZoneInterface|Proxy random(array $attributes = [])
 * @method static ZoneInterface|Proxy randomOrCreate(array $attributes = [])
 * @method static ZoneInterface[]|Proxy[] all()
 * @method static ZoneInterface[]|Proxy[] findBy(array $attributes)
 * @method static ZoneInterface[]|Proxy[] randomSet(int $number, array $attributes = [])
 * @method static ZoneInterface[]|Proxy[] randomRange(int $min, int $max, array $attributes = [])
 * @method ZoneInterface|Proxy create(array|callable $attributes = [])
 */
class ZoneFactory extends ModelFactory implements ZoneFactoryInterface, FactoryWithModelClassAwareInterface
{
    use WithCodeTrait;
    use WithNameTrait;

    private static ?string $modelClass = null;

    public function __construct(
        private FactoryInterface $zoneFactory,
        private ZoneDefaultValuesInterface $defaultValues,
        private ZoneTransformerInterface $transformer,
        private ZoneUpdaterInterface $updater,
    ) {
        parent::__construct();
    }

    public static function withModelClass(string $modelClass): void
    {
        self::$modelClass = $modelClass;
    }

    public function withMembers(array $members, string $type = ZoneInterface::TYPE_ZONE): self
    {
        return $this->addState([
            'type' => $type,
            'members' => $members,
        ]);
    }

    public function withCountries(array $countries): self
    {
        return $this->withMembers($countries, ZoneInterface::TYPE_COUNTRY);
    }

    public function withProvinces(array $countries): self
    {
        return $this->withMembers($countries, ZoneInterface::TYPE_PROVINCE);
    }

    public function withScope(string $scope): self
    {
        return $this->addState(['scope' => $scope]);
    }

    protected function getDefaults(): array
    {
        return $this->defaultValues->getDefaults(self::faker());
    }

    protected function transform(array $attributes): array
    {
        return $this->transformer->transform($attributes);
    }

    protected function update(ZoneInterface $zone, array $attributes): void
    {
        $this->updater->update($zone, $attributes);
    }

    protected function initialize(): self
    {
        return $this
            ->beforeInstantiate(function (array $attributes): array {
                return $this->transform($attributes);
            })
            ->instantiateWith(function(array $attributes): ZoneInterface {
                /** @var ZoneInterface $zone */
                $zone = $this->zoneFactory->createNew();

                $this->update($zone, $attributes);

                return $zone;
            })
        ;
    }

    protected static function getClass(): string
    {
        return self::$modelClass ?? Zone::class;
    }
}