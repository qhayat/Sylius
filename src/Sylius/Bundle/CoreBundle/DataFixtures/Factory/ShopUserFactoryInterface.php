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

use Sylius\Bundle\CoreBundle\DataFixtures\Factory\State\FemaleInterface;
use Sylius\Bundle\CoreBundle\DataFixtures\Factory\State\MaleInterface;
use Sylius\Bundle\CoreBundle\DataFixtures\Factory\State\WithEmailInterface;
use Sylius\Bundle\CoreBundle\DataFixtures\Factory\State\WithFirstNameInterface;
use Sylius\Bundle\CoreBundle\DataFixtures\Factory\State\WithLastNameInterface;
use Sylius\Bundle\CoreBundle\DataFixtures\Factory\State\WithPhoneNumberInterface;
use Sylius\Component\Core\Model\ShopUserInterface;
use Sylius\Component\Customer\Model\CustomerGroupInterface;
use Zenstruck\Foundry\ModelFactory;
use Zenstruck\Foundry\Proxy;

/**
 * @extends ModelFactory<ShopUserInterface>
 *
 * @method static ShopUserInterface|Proxy createOne(array $attributes = [])
 * @method static ShopUserInterface[]|Proxy[] createMany(int $number, array|callable $attributes = [])
 * @method static ShopUserInterface|Proxy find(object|array|mixed $criteria)
 * @method static ShopUserInterface|Proxy findOrCreate(array $attributes)
 * @method static ShopUserInterface|Proxy first(string $sortedField = 'id')
 * @method static ShopUserInterface|Proxy last(string $sortedField = 'id')
 * @method static ShopUserInterface|Proxy random(array $attributes = [])
 * @method static ShopUserInterface|Proxy randomOrCreate(array $attributes = [])
 * @method static ShopUserInterface[]|Proxy[] all()
 * @method static ShopUserInterface[]|Proxy[] findBy(array $attributes)
 * @method static ShopUserInterface[]|Proxy[] randomSet(int $number, array $attributes = [])
 * @method static ShopUserInterface[]|Proxy[] randomRange(int $min, int $max, array $attributes = [])
 * @method ShopUserInterface|Proxy create(array|callable $attributes = [])
 */
interface ShopUserFactoryInterface extends WithEmailInterface, FemaleInterface, MaleInterface, WithFirstNameInterface, WithLastNameInterface, WithPhoneNumberInterface
{
    public function withCustomerGroup(Proxy|CustomerGroupInterface|string $customerGroup): self;

    public function withBirthday(\DateTimeInterface|string $birthday): self;

    public function withPassword(string $password): self;
}