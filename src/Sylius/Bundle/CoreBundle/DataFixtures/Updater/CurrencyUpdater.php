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

namespace Sylius\Bundle\CoreBundle\DataFixtures\Updater;

use Sylius\Component\Currency\Model\CurrencyInterface;

final class CurrencyUpdater implements CurrencyUpdaterInterface
{
    public function update(CurrencyInterface $currency, array $attributes): void
    {
        $currency->setCode($attributes['code']);
    }
}